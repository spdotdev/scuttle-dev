<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Spdotdev\ScuttleDev\Mail\ContactMessage;
use Spdotdev\ScuttleDev\Models\ContactLead;
use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * Round 12 security review of the package itself. What was read and found clean is listed in docs/IMPROVEMENT-STATUS.md;
 * every test here pins a hole that was found, or a property that must stay true.
 */
class SecurityTest extends TestCase
{
    private function token(int $age = 10): string
    {
        $t = (string) (time() - $age);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /** @return array<string, mixed> */
    private function form(array $over = []): array
    {
        return $over + ['name' => 'Anna', 'email' => 'anna@example.com', 'about' => 'Health Plan', 'message' => 'We run an old PHP portal and need a plan.', 't' => $this->token(), 'website' => ''];
    }

    /** @return array<string, array{0: string}> */
    public static function hostileReferers(): array
    {
        return [
            'other host' => ['https://evil.example/contact'],
            'protocol relative' => ['//evil.example/contact'],
            'userinfo trick' => ['https://scuttle.dev@evil.example/contact'],
            'suffix trick' => ['https://scuttle.dev.evil.example/contact'],
            'javascript' => ['javascript:alert(1)'],
            'crlf' => ["https://scuttle.dev/contact\r\nSet-Cookie: a=b"],
        ];
    }

    #[DataProvider('hostileReferers')]
    public function test_a_hostile_referer_never_becomes_the_redirect_target(string $referer): void
    {
        $bad = $this->withHeader('Referer', $referer)->post('http://scuttle.dev/contact', $this->form(['email' => 'nope']));
        $this->assertSame(302, $bad->getStatusCode());
        $location = (string) $bad->headers->get('Location');
        $this->assertSame('scuttle.dev', parse_url($location, PHP_URL_HOST), 'validation error: '.$location);
        $this->assertStringEndsWith('#cform', $location);
        $this->assertStringNotContainsString("\n", $location);

        Mail::fake();
        $ok = $this->withHeader('Referer', $referer)->post('http://scuttle.dev/contact', $this->form());
        $this->assertSame('scuttle.dev', parse_url((string) $ok->headers->get('Location'), PHP_URL_HOST), 'success');
    }

    public function test_a_referer_on_this_site_still_brings_the_visitor_back_to_the_same_page_in_either_language(): void
    {
        $this->from('http://scuttle.dev/nl/contact?about=Health+Check')->post('http://scuttle.dev/contact', $this->form(['email' => 'nope']))
            ->assertRedirect('http://scuttle.dev/nl/contact?about=Health+Check#cform');
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['email' => 'nope']))
            ->assertRedirect('http://scuttle.dev/contact#cform');
    }

    public function test_the_lead_keeps_only_the_path_of_the_referer_and_no_query_or_host(): void
    {
        Mail::fake();
        $this->withHeader('Referer', 'https://evil.example/nl/contact?x=secret#frag')->post('http://scuttle.dev/contact', $this->form())->assertRedirect();
        $this->assertSame('/nl/contact', ContactLead::query()->sole()->page);
    }

    public function test_hostile_old_input_cannot_break_out_of_the_dutch_contact_page(): void
    {
        $evil = '"><b data-i="e4_h">x</b></textarea><script>alert(1)</script><!--';
        $html = (string) $this->withSession(['_old_input' => ['name' => $evil, 'email' => $evil, 'about' => $evil, 'message' => $evil]])
            ->get('http://scuttle.dev/nl/contact')->assertOk()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)', $html);
        $this->assertStringNotContainsString('<b data-i="e4_h">', $html);
        $this->assertSame(0, preg_match('/<option[^>]* selected/', $html), 'an unknown topic selects nothing');
    }

    public function test_array_and_odd_values_in_the_post_are_validation_errors_not_server_errors(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);
        foreach ([['name' => ['a']], ['email' => ['a@b.nl']], ['about' => ['x']], ['message' => ['x']], ['t' => ['x']], ['t' => 'no-dot'], ['t' => '.'], ['name' => str_repeat('x', 100000)]] as $over) {
            $r = $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form($over));
            $this->assertSame(302, $r->getStatusCode(), json_encode(array_keys($over)));
            $this->assertSame(0, ContactLead::query()->count());
        }
    }

    public function test_a_form_older_than_six_hours_or_signed_for_another_time_is_refused(): void
    {
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['t' => $this->token(6 * 3600 + 5)]))->assertSessionHasErrors('message');
        $t = (string) (time() - 10);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['t' => ($t - 100).'.'.hash_hmac('sha256', $t, (string) config('app.key'))]))->assertSessionHasErrors('message');
        $this->assertSame(0, ContactLead::query()->count());
    }

    public function test_turnstile_failures_refuse_the_post_and_store_nothing(): void
    {
        config(['scuttle-dev.turnstile_secret' => 'secret-value']);
        Mail::fake();

        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form())->assertSessionHasErrors('message');

        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false], 200)]);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['cf-turnstile-response' => 'tok']))->assertSessionHasErrors('message');

        Http::fake(['challenges.cloudflare.com/*' => Http::response('oops', 500)]);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['cf-turnstile-response' => 'tok']))->assertSessionHasErrors('message');

        $logged = [];
        Log::shouldReceive('warning')->andReturnUsing(function (string $m, array $c = []) use (&$logged) {
            $logged[] = $m.json_encode($c);
        });
        Http::fake(['challenges.cloudflare.com/*' => fn () => throw new ConnectionException('cannot reach for secret-value')]);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['cf-turnstile-response' => 'tok']))->assertSessionHasErrors('message');
        $this->assertStringNotContainsString('secret-value', implode(' ', $logged));

        $this->assertSame(0, ContactLead::query()->count());
        Mail::assertNothingSent();

    }

    public function test_turnstile_success_lets_the_post_through(): void
    {
        config(['scuttle-dev.turnstile_secret' => 'secret-value']);
        Mail::fake();
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true], 200)]);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['cf-turnstile-response' => 'tok']))->assertSessionHasNoErrors();
        $this->assertSame(1, ContactLead::query()->count());
    }

    public function test_a_visitor_cannot_set_the_columns_the_server_owns(): void
    {
        Mail::fake();
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['mail_status' => 'sent', 'is_test' => '1', 'id' => 999, 'language' => 'zz', 'ip_hash' => 'x', 'page' => '/x', 'mailed_at' => '2020-01-01']))->assertRedirect();
        $lead = ContactLead::query()->sole();
        $this->assertNotSame(999, $lead->id);
        $this->assertFalse($lead->is_test);
        $this->assertSame('en', $lead->language);
        $this->assertNotSame('x', $lead->ip_hash);
        $this->assertNotSame('/x', $lead->page);
        $this->assertNotSame('2020-01-01', (string) $lead->mailed_at?->format('Y-m-d'));
    }

    public function test_the_mail_cannot_carry_a_header_or_markup_from_the_visitor(): void
    {
        $name = "Anna\r\nBcc: spy@example.com <script>alert(1)</script>";
        $about = "Health Plan\r\nX-Evil: 1";
        $mail = new ContactMessage($name, 'anna@example.com', $about, "Line one\r\n<script>alert(2)</script> & more", 'hash', '/nl/contact');
        $mail->build();
        $this->assertDoesNotMatchRegularExpression('/[\r\n]/', (string) $mail->subject, 'the subject is one line');
        $html = $mail->render();
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);

        $text = view('scuttle::mail.contact-text', ['name' => 'A', 'email' => 'a@b.nl', 'about' => '', 'text' => 'x <b>y</b> & z', 'ip' => 'h', 'page' => ''])->render();
        $this->assertStringContainsString('x <b>y</b> & z', $text, 'the plain text part is not HTML escaped');
        $this->assertStringNotContainsString('&amp;', $text);

        // Through the form, a control character in the name or topic never reaches the mail at all.
        Mail::fake();
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['name' => "Anna\r\nBcc: spy@example.com"]))->assertSessionHasErrors('name');
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['about' => "x\r\nBcc: spy@example.com"]))->assertSessionHasErrors('about');
        Mail::assertNothingSent();
    }

    public function test_the_mail_is_a_reply_to_the_visitor_names_the_topic_and_the_page_and_has_a_text_part(): void
    {
        Mail::fake();
        $this->withHeader('Referer', 'http://scuttle.dev/nl/services/modernization?utm=x')->post('http://scuttle.dev/contact', $this->form(['about' => 'Modernization']))->assertRedirect();
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail): bool {
            $mail->build();

            return $mail->hasReplyTo('anna@example.com')
                && str_contains((string) $mail->subject, 'Modernization')
                && str_contains($mail->render(), '/nl/services/modernization')
                && ! empty($mail->textView)
                && str_contains(view($mail->textView, $mail->buildViewData())->render(), 'Page: /nl/services/modernization');
        });
    }

    public function test_the_log_never_holds_the_visitors_text_for_a_bot_check_or_a_save_failure(): void
    {
        $logged = [];
        Log::shouldReceive('error', 'warning', 'critical')->andReturnUsing(function (string $m, array $c = []) use (&$logged) {
            $logged[] = $m.json_encode($c);
        });
        config(['scuttle-dev.turnstile_secret' => 's']);
        Http::fake(['challenges.cloudflare.com/*' => fn () => throw new ConnectionException('boom anna@example.com')]);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['name' => "Ann\u{202E}a", 'cf-turnstile-response' => 't']));
        $this->assertStringNotContainsString('anna@example.com', implode(' ', $logged));
    }

    /** @return array<string, array{0: string}> */
    public static function hostileQueries(): array
    {
        return [['?p=<script>alert(1)</script>'], ['?p[]=1'], ['?page=abc'], ['?page[]=2'], ['?page=-5'], ['?page=99999999999999999999'], ['?m=zz&p=2026'], ['?m[]=1'], ['?p=2026-13'], ['?p=2026-W99'], ['?p=%00']];
    }

    #[DataProvider('hostileQueries')]
    public function test_hostile_query_strings_on_the_news_archive_never_break_or_echo_markup(string $q): void
    {
        $r = $this->get('http://scuttle.dev/news'.$q);
        $this->assertContains($r->getStatusCode(), [200, 404], $q);
        $this->assertStringNotContainsString('<script>alert(1)', (string) $r->getContent(), $q);
    }

    public function test_the_vcard_has_crlf_line_ends_and_no_stray_line_breaks_inside_a_field(): void
    {
        $v = (string) file_get_contents(__DIR__.'/../../public/scuttledev.vcf');
        $this->assertStringStartsWith("BEGIN:VCARD\r\n", $v);
        $this->assertSame(0, preg_match('/(?<!\r)\n/', $v), 'every line ends in CRLF');
        $this->assertStringContainsString("END:VCARD\r\n", $v);
    }
}
