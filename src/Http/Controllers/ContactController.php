<?php

namespace Spdotdev\ScuttleDev\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spdotdev\ScuttleDev\Mail\ContactMessage;
use Spdotdev\ScuttleDev\Models\ContactLead;

/**
 * The contact form: validated, rate limited, bot-checked (Cloudflare Turnstile
 * when keys are configured, otherwise a honeypot plus a signed minimum fill
 * time), stored in the database, then mailed to the configured address when a mail transport exists.
 */
class ContactController
{
    public function send(Request $request): RedirectResponse
    {
        // Both languages are built at once: the page shows the English text and carries the Dutch twin, so the EN/NL
        // switch flips the errors without a new request. The referer only decides the language stored with the lead.
        $nl = $this->isDutch($request);
        $validator = $this->validator($request, false);
        if ($validator->fails()) {
            // Back to the form itself (#cform), so the message sits in view and not above the fold.
            return $this->backToForm()->withInput()->withErrors($validator)
                ->with('contact_errors_en', array_values($validator->errors()->all()))
                ->with('contact_errors_nl', array_values($this->validator($request, true)->errors()->all()));
        }
        $data = $validator->validated();

        $to = (string) config('scuttle-dev.contact_to', 'info@scuttle.dev');

        if (! $this->humanEnough($request, $data)) {
            return $this->backWithError('message',
                'The bot check failed. Please try again or email '.$to.' directly.',
                'De botcontrole is mislukt. Probeer het opnieuw of mail direct naar '.$to.'.');
        }

        // Store first. A valid message is never lost because mail is down; success is shown only when it is stored.
        $path = (string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH);
        try {
            $lead = ContactLead::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'about' => $data['about'] ?? null,
                'message' => $data['message'],
                'language' => $nl ? 'nl' : 'en',
                'page' => $path !== '' ? mb_substr($path, 0, 200) : null,
                'ip_hash' => substr(hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')), 0, 32),
                'is_test' => str_starts_with(strtolower($data['name']), '[test]'),
            ]);
        } catch (\Throwable $e) {
            Log::error('scuttle.dev contact form: the message could not be stored', self::safeContext($e));

            return $this->backWithError('message',
                'Your message could not be saved right now, and I do not want you to think it arrived. Please email '.$to.' directly or book a call.',
                'Je bericht kon nu niet worden bewaard en ik wil niet dat je denkt dat het is aangekomen. Mail direct naar '.$to.' of plan een gesprek.');
        }

        $mailer = null;
        if ((string) config('scuttle-dev.mail_dsn', '') !== '') {
            config(['mail.mailers.contact_form' => ['transport' => 'smtp', 'url' => (string) config('scuttle-dev.mail_dsn')]]);
            $mailer = 'contact_form';
        }

        if ($mailer === null && config('scuttle-dev.require_real_mailer', true) && in_array((string) config('mail.default'), ['log', 'array'], true)) {
            Log::critical('scuttle.dev contact form: no mail transport (set SCUTTLE_CONTACT_MAIL_DSN and SCUTTLE_CONTACT_MAIL_FROM); lead #'.$lead->id.' is stored, no mail was sent. List: php artisan scuttle:contact-leads --unmailed');
            $lead->update(['mail_status' => 'not_configured']);

            return $this->backToForm()->with('contact_sent', 'stored');
        }

        try {
            Mail::mailer($mailer)->to($to)
                ->send(new ContactMessage($data['name'], $data['email'], (string) ($data['about'] ?? ''), $data['message'], (string) $lead->ip_hash));
            $lead->update(['mail_status' => 'sent', 'mailed_at' => now()]);
        } catch (\Throwable $e) {
            Log::critical('scuttle.dev contact form could not send mail; lead #'.$lead->id.' is stored', self::safeContext($e));
            $lead->update(['mail_status' => 'failed']);

            return $this->backToForm()->with('contact_sent', 'stored');
        }

        return $this->backToForm()->with('contact_sent', 'mailed');
    }

    /** The validator in one language; both are built on every failed post so the two lists have the same errors in the same order. */
    private function validator(Request $request, bool $nl): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120', $this->noControlChars(false, $nl)],
            'email' => ['required', 'email', 'max:160', $this->noControlChars(false, $nl)],
            'about' => ['nullable', 'string', 'max:120', $this->noControlChars(false, $nl)],
            'message' => ['required', 'string', 'min:10', 'max:4000', $this->noControlChars(true, $nl)],
            'website' => ['nullable', 'max:0'], // honeypot: humans never see it
            't' => ['required', 'string'],
            'cf-turnstile-response' => ['nullable', 'string'],
        ], $nl ? self::MESSAGES_NL : ['website.max' => 'Something went wrong, please email me directly.'], $nl ? self::ATTRIBUTES_NL : []);
    }

    /** A hand-written error: the English text is shown, the Dutch twin travels with it for the switch. */
    private function backWithError(string $field, string $en, string $nl): RedirectResponse
    {
        return $this->backToForm()->withInput()->withErrors([$field => $en])
            ->with('contact_errors_en', [$en])->with('contact_errors_nl', [$nl]);
    }

    private const ATTRIBUTES_NL = ['name' => 'naam', 'email' => 'e-mailadres', 'about' => 'onderwerp', 'message' => 'bericht', 't' => 'formulier'];

    private const MESSAGES_NL = [
        'required' => 'Vul het veld :attribute in.',
        'email' => 'Vul een geldig e-mailadres in.',
        'string' => 'Het veld :attribute is niet geldig.',
        'max' => 'Het veld :attribute is te lang (hoogstens :max tekens).',
        'min' => 'Het veld :attribute is te kort (minstens :min tekens).',
        'website.max' => 'Er ging iets mis, mail me direct.',
    ];

    /** The visitor wrote from a Dutch page (/nl or /nl/...): the answers come back in Dutch. */
    private function isDutch(Request $request): bool
    {
        $path = (string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH);

        return $path === '/nl' || str_starts_with($path, '/nl/');
    }

    /** Back to the page the form was on, at the form itself, so the answer is in view and the form is not hidden under the headline. */
    private function backToForm(): RedirectResponse
    {
        return redirect()->to(explode('#', url()->previous())[0].'#cform');
    }

    /**
     * Rejects control characters (and invalid UTF-8) so a submission cannot carry terminal escape sequences into the
     * admin's console. The message may keep newline and tab.
     */
    private function noControlChars(bool $multiline, bool $nl = false): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($multiline, $nl): void {
            $v = (string) $value;
            $pattern = $multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{80}-\x{9F}]/u' : '/[\x00-\x1F\x7F\x{80}-\x{9F}]/u';
            if (! mb_check_encoding($v, 'UTF-8') || preg_match($pattern, $v) === 1) {
                $fail($nl ? ':attribute bevat tekens die niet zijn toegestaan.' : 'The :attribute contains characters that are not allowed.');
            }
        };
    }

    /**
     * What may go into a log line about a failure: the exception class and, for a database error, the SQLSTATE.
     * Never the message (it can hold the SQL and the bound visitor values) and never the bindings.
     *
     * @return array<string, string>
     */
    private static function safeContext(\Throwable $e): array
    {
        $ctx = ['exception' => get_class($e)];
        if ($e instanceof QueryException) {
            $ctx['sqlstate'] = (string) ($e->errorInfo[0] ?? $e->getCode());
        }

        return $ctx;
    }

    /**
     * A signed timestamp the form carries; the form must be at least 3 seconds old.
     */
    public static function token(): string
    {
        $t = (string) time();

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function humanEnough(Request $request, array $data): bool
    {
        [$t, $sig] = array_pad(explode('.', (string) $data['t'], 2), 2, '');
        if (! hash_equals(hash_hmac('sha256', $t, (string) config('app.key')), $sig)) {
            return false;
        }
        if (time() - (int) $t < 3 || time() - (int) $t > 6 * 3600) {
            return false;
        }

        $secret = (string) config('scuttle-dev.turnstile_secret');
        if ($secret === '') {
            return true;
        }
        $token = (string) ($data['cf-turnstile-response'] ?? '');
        if ($token === '') {
            return false;
        }
        try {
            $r = Http::asForm()->timeout(8)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret, 'response' => $token, 'remoteip' => $request->ip(),
            ]);

            return (bool) $r->json('success', false);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification failed', self::safeContext($e));

            return false;
        }
    }
}
