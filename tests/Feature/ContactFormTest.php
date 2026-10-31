<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spdotdev\ScuttleDev\Models\ContactLead;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** The contact form is the only contact route, so it must never lose a lead and must always say what is wrong. */
class ContactFormTest extends TestCase
{
    private function token(): string
    {
        $t = (string) (time() - 10);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /** @return array<string, string> */
    private function form(array $over = []): array
    {
        return $over + ['name' => 'Anna', 'email' => 'anna@example.com', 'about' => 'Health Check', 'message' => 'We run an old PHP portal and need a plan.', 't' => $this->token(), 'website' => ''];
    }

    public function test_a_validation_error_returns_to_the_form_with_the_error_and_the_old_values_in_english(): void
    {
        $r = $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['email' => 'nope', 'about' => 'Security Test']));
        $r->assertRedirect('http://scuttle.dev/contact#cform')->assertSessionHasErrors('email');
        $html = (string) $this->withSession(['errors' => session('errors'), '_old_input' => ['name' => 'Anna', 'about' => 'Security Test', 'message' => 'Kept text here']])->get('http://scuttle.dev/contact')->getContent();
        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('value="Anna"', $html);
        $this->assertStringContainsString('Kept text here', $html);
        $this->assertMatchesRegularExpression('/<option value="Security Test" selected/', $html, 'the about select keeps its choice');
        $this->assertLessThan(strpos($html, 'name="name"'), strpos($html, 'role="alert"'), 'the error sits above the first field');
        $this->assertSame(0, ContactLead::query()->count());
    }

    public function test_validation_errors_from_the_dutch_page_are_dutch_and_return_to_the_dutch_form(): void
    {
        $r = $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $this->form(['email' => 'nope', 'message' => 'kort']));
        $r->assertRedirect('http://scuttle.dev/nl/contact#cform');
        $errors = session('errors')->all();
        $this->assertContains('Vul een geldig e-mailadres in.', $errors);
        $this->assertContains('Het veld bericht is te kort (minstens 10 tekens).', $errors);
        $this->assertStringNotContainsString('must be', implode(' ', $errors));
        $this->assertSame(0, ContactLead::query()->count());
    }

    public function test_a_missing_field_is_named_in_dutch_and_english(): void
    {
        $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $this->form(['name' => '']));
        $this->assertSame(['Vul het veld naam in.'], session('errors')->get('name'));
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['name' => '']));
        $this->assertStringContainsString('name field is required', session('errors')->first('name'));
    }

    public function test_the_bot_check_and_the_honeypot_answer_in_the_language_of_the_page(): void
    {
        $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $this->form(['t' => time().'.forged']));
        $this->assertStringContainsString('De botcontrole is mislukt', session('errors')->first('message'));
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['t' => time().'.forged']));
        $this->assertStringContainsString('The bot check failed', session('errors')->first('message'));
        $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $this->form(['website' => 'http://spam']));
        $this->assertSame(['Er ging iets mis, mail me direct.'], session('errors')->get('website'));
        $this->assertSame(0, ContactLead::query()->count(), 'a rejected post stores nothing');
    }

    public function test_a_post_made_in_under_three_seconds_is_rejected_and_stores_nothing(): void
    {
        $t = (string) time();
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form(['t' => $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'))]))
            ->assertSessionHasErrors('message');
        $this->assertSame(0, ContactLead::query()->count());
    }

    public function test_success_returns_to_the_form_clears_it_and_shows_sent_first_in_the_form(): void
    {
        config(['scuttle-dev.require_real_mailer' => false]);
        Mail::fake();
        $r = $this->from('http://scuttle.dev/contact?about=Health+Check')->post('http://scuttle.dev/contact', $this->form());
        $r->assertRedirect('http://scuttle.dev/contact?about=Health+Check#cform')->assertSessionHas('contact_sent', 'mailed')->assertSessionMissing('_old_input');
        $html = (string) $this->withSession(['contact_sent' => 'mailed'])->get('http://scuttle.dev/contact')->getContent();
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringNotContainsString('value="Anna"', $html, 'the form is empty after a send');
        $this->assertLessThan(strpos($html, 'name="name"'), strpos($html, 'role="status"'), 'Sent sits above the first field');
        $this->assertStringContainsString('#cform{scroll-margin-top', (string) file_get_contents(__DIR__.'/../../public/site.css'), 'the anchor clears the sticky bar');
    }

    public function test_a_mail_failure_keeps_the_lead_and_logs_no_visitor_data(): void
    {
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'smtp', 'scuttle-dev.mail_dsn' => '']);
        $logged = [];
        Log::shouldReceive('critical')->andReturnUsing(function (string $m, array $c = []) use (&$logged) {
            $logged[] = $m.json_encode($c);
        });
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('smtp down for anna@example.com'));
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form())->assertSessionHas('contact_sent', 'stored');
        $this->assertSame('failed', ContactLead::query()->sole()->mail_status);
        $this->assertNotEmpty($logged);
        $this->assertStringNotContainsString('anna@example.com', implode(' ', $logged));
        $this->assertStringNotContainsString('old PHP portal', implode(' ', $logged));
    }

    public function test_the_throttle_stops_the_seventh_post_in_a_minute(): void
    {
        config(['scuttle-dev.require_real_mailer' => false]);
        Mail::fake();
        for ($i = 0; $i < 6; $i++) {
            $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form())->assertRedirect();
        }
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->form())->assertStatus(429);
        $this->assertSame(6, ContactLead::query()->count());
    }

    public function test_the_about_query_is_escaped_and_selects_nothing_when_unknown(): void
    {
        $html = (string) $this->get('http://scuttle.dev/contact?about='.rawurlencode('<script>alert(1)</script>'))->assertOk()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertDoesNotMatchRegularExpression('/<option[^>]* selected/', $html);
        $this->assertMatchesRegularExpression('/<option value="Health Check" selected/', (string) $this->get('http://scuttle.dev/contact?about=Health+Check')->getContent());
    }

    public function test_the_dutch_contact_page_translates_every_about_option_and_label(): void
    {
        $html = (string) $this->get('http://scuttle.dev/nl/contact')->assertOk()->getContent();
        $this->assertStringContainsString('Nog niet zeker', $html);
        $this->assertStringContainsString('Iets anders', $html);
        $this->assertStringNotContainsString('>Not sure yet<', $html);
        $this->assertStringContainsString('>Versturen<', $html);
    }

    public function test_the_contact_lead_is_short_and_keeps_the_promise(): void
    {
        foreach (['http://scuttle.dev/contact' => 'within one working day', 'http://scuttle.dev/nl/contact' => 'binnen één werkdag'] as $url => $promise) {
            $html = (string) $this->get($url)->getContent();
            preg_match('/<p class="lead"[^>]*>(.*?)<\/p>/s', $html, $m);
            $this->assertLessThanOrEqual(25, str_word_count(strip_tags($m[1])), 'the lead fits the first phone screen');
            $this->assertStringContainsString($promise, $m[1]);
        }
    }
}
