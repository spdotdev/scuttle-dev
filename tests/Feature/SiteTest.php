<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Http\Controllers\ContactController;
use Spdotdev\ScuttleDev\Mail\ContactMessage;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Projects;
use Spdotdev\ScuttleDev\Tests\TestCase;

class SiteTest extends TestCase
{
    public function test_book_a_call_opens_the_booking_page_on_every_page(): void
    {
        $home = $this->get('http://scuttle.dev/')->assertOk()->getContent();
        // The top bar button and the contact section both lead to the booking page in sd-plan.
        $this->assertSame(2, substr_count($home, 'href="https://plan.scuttle.dev/book/stas"'));
        $this->assertStringNotContainsString('subject=Call%20about', $home);

        $this->get('http://scuttle.dev/health-check')->assertOk()
            ->assertSee('href="https://plan.scuttle.dev/book/stas"', false);
    }

    public function test_homepage_renders_on_the_configured_host(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertOk()
            ->assertSee('Scuttle Development');
    }

    public function test_footer_carries_no_built_with_credit(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertOk()
            ->assertDontSee('Antigravity')
            ->assertDontSee('Built with');
    }

    public function test_home_has_one_h1_the_vcard_and_the_qr_code_and_no_map(): void
    {
        $html = $this->get('http://scuttle.dev/')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringNotContainsString('maps.google', $html);

        $contact = (string) $this->get('http://scuttle.dev/contact')->assertOk()->getContent();
        $this->assertStringContainsString('/scuttle.vcf', $contact);
        $this->assertStringContainsString('vendor/scuttle/qr/scuttle-vcard.svg', $contact);
        $this->assertStringContainsString('id="qr-modal"', $contact);
        $this->assertStringContainsString('id="pic-modal"', $contact);
        $this->assertStringContainsString('id="map-modal"', $contact);
        $this->assertStringNotContainsString('<iframe', $contact);
        $this->assertStringContainsString('kvk.nl/zoeken/?source=all&amp;q=96040947', $contact);
        $this->assertStringContainsString('id="cform"', $contact);
        $this->assertSame(1, substr_count($contact, '<h1'));
        $this->assertStringNotContainsString('maps.google', $html);
        $this->assertStringNotContainsString('<iframe', $html);
    }

    public function test_home_shows_only_confirmed_facts(): void
    {
        $html = $this->get('http://scuttle.dev/')->assertOk()->getContent();

        $this->assertStringContainsString('KvK 96040947', (string) $this->get('http://scuttle.dev/contact')->getContent());
        $this->assertStringContainsString('NL005184584B85', $html);
        $this->assertStringNotContainsStringIgnoringCase('draft', $html);
        $this->assertStringNotContainsStringIgnoringCase('hosintra', $html);
        $this->assertStringNotContainsString('hello@scuttle.dev', $html);
    }

    public function test_mobile_grids_cannot_grow_wider_than_the_phone(): void
    {
        $css = file_get_contents(__DIR__.'/../../public/site.css');

        $this->assertStringContainsString('grid-template-columns:minmax(0,1fr)', $css);
        $this->assertStringContainsString('overflow-x:clip', $css);
    }

    public function test_privacy_page_renders_and_is_linked(): void
    {
        $this->get('http://scuttle.dev/privacy')
            ->assertOk()
            ->assertSee('Privacyverklaring')
            ->assertSee('stas@scuttle.dev')
            ->assertSee('Afmelden');

        $this->get('http://scuttle.dev/')->assertSee('/privacy', false);
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/privacy', false);
    }

    public function test_robots_txt_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:')
            ->assertSee('Disallow: /clients');
    }

    public function test_the_mail_signature_logo_is_served_as_a_png(): void
    {
        $response = $this->get('http://scuttle.dev/mail/signature-logo.png')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->assertStringStartsWith("\x89PNG", (string) $response->getContent());
    }

    public function test_the_legal_pdfs_are_served_under_legal(): void
    {
        foreach (['ALGEMENE_VOORWAARDEN', 'TERMS_AND_CONDITIONS', 'GEHEIMHOUDINGSOVEREENKOMST', 'NDA'] as $name) {
            $response = $this->get('http://scuttle.dev/legal/'.$name.'.pdf')
                ->assertOk()
                ->assertHeader('Content-Type', 'application/pdf');
            $this->assertStringStartsWith('%PDF', (string) $response->getContent());
        }

        $this->get('http://scuttle.dev/legal/secret.pdf')->assertNotFound();
    }

    public function test_sitemap_xml_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_every_page_links_the_shared_stylesheet_script_and_logo(): void
    {
        foreach (['/', '/privacy', '/pricing', '/contact', '/work', '/case-studies', '/blog', '/company', '/projects', '/projects/manual/docs', '/health-check', '/services/modernization', '/case-studies/phalcon-to-laravel'] as $path) {
            $this->get('http://scuttle.dev'.$path)
                ->assertOk()
                ->assertSee('vendor/scuttle/site.css', false)
                ->assertSee('vendor/scuttle/site.js', false)
                ->assertSee('class="lg', false)
                ->assertSee('vendor/scuttle/icon.svg', false);
        }
    }

    public function test_the_health_check_page_renders_and_the_old_address_redirects(): void
    {
        $this->get('http://scuttle.dev/health-check')
            ->assertOk()
            ->assertSee('Health Check')
            ->assertDontSee('Legacy Health Check')
            ->assertSee('Legacy check')
            ->assertSee('EUR 1,080', false)
            ->assertSee('https://scuttle.dev/health-check', false);

        $this->get('http://scuttle.dev/legacy-health-check')->assertRedirect('/health-check')->assertStatus(301);
        $this->get('http://scuttle.dev/')->assertSee('/health-check', false);
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/health-check', false);
    }

    public function test_the_site_points_at_no_client_and_leads_with_automation(): void
    {
        foreach (['/', '/pricing', '/contact', '/health-check', '/services/ai-integration', '/services/modernization', '/case-studies/phalcon-to-laravel', '/case-studies/business-on-agents', '/work', '/company', '/blog', '/projects'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            foreach (['hotel', 'hosintra', 'rental-platform', 'webshop-legacy', 'erp-bridge', 'Legacy Health Check', 'Modernization in Steps', '<b>0</b>'] as $word) {
                $this->assertStringNotContainsStringIgnoringCase($word, $html, $path.' must not contain '.$word);
            }
        }
        $home = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringContainsString('Automate the work', $home);
        $this->assertStringContainsString('Automation · Security · Eindhoven', $home);
        $this->assertStringContainsString('server-setup', $home);
        $this->assertStringContainsString('8.4.26 → 8.4.27', $home);
        $this->assertSame(3, substr_count($home, 'data-th="'), 'three-way theme control: system, light, dark');
        $this->assertSame(12, substr_count($home, 'class="it'), 'twelve clickable items in the demo panel');
        $this->assertStringNotContainsString('end of life since', $home);
    }

    public function test_every_case_study_renders_without_zeros_and_is_in_the_sitemap(): void
    {
        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();
        foreach (Cases::all() as $c) {
            $page = (string) $this->get('http://scuttle.dev/case-studies/'.$c['slug'])->assertOk()->getContent();
            $this->assertStringContainsString($c['title'], $page);
            $this->assertStringContainsString('/services/', $page);
            $this->assertStringNotContainsString('<b>0</b>', $page);
            $this->assertStringContainsString('<loc>https://scuttle.dev/case-studies/'.$c['slug'].'</loc>', $sitemap);
        }
        $this->assertCount(4, Cases::all());
        $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->assertSee('60%', false);
        $this->get('http://scuttle.dev/case-studies/nope')->assertNotFound();
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/contact', false);
    }

    public function test_the_pricing_page_explains_the_rate_and_lists_every_product(): void
    {
        $html = (string) $this->get('http://scuttle.dev/pricing')->assertOk()->getContent();

        $this->assertStringContainsString('€90', $html);
        $this->assertStringContainsString('€1,080', $html);
        $this->assertStringContainsString('12 hours × EUR 90', $html);
        $this->assertStringContainsString('from €720', $html);
        $this->assertStringContainsString('excl. VAT', $html);
        $this->assertSame(8, substr_count($html, 'class="plan"') + substr_count($html, 'class="plan hi"'), 'two blocks of four');
        $this->assertSame(3, substr_count($html, 'class="badge"'), 'the promoted ones carry a badge');
        $this->assertStringContainsString('14 days', $html);
        foreach (Catalog::all() as $p) {
            $this->assertStringContainsString($p['name'], $html);
        }
        $this->assertStringNotContainsStringIgnoringCase('subscription', $html);
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/pricing', false);
    }

    public function test_every_product_has_a_page_in_the_menu_and_in_the_sitemap(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();
        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();

        foreach (Catalog::all() as $p) {
            $url = Catalog::url($p);
            $page = (string) $this->get($url)->assertOk()->getContent();
            $this->assertSame(1, substr_count($page, '<h1'), $url);
            $this->assertStringContainsString($p['price'], $page, $url);
            $this->assertStringContainsString('href="https://plan.scuttle.dev/book/stas"', $page, $url);
            $this->assertGreaterThanOrEqual(2, substr_count($home, 'href="'.$url.'"'), $url.' appears in the menu and the tiles');
            $this->assertStringContainsString('<loc>'.Catalog::canonical($p).'</loc>', $sitemap, $url);
        }
        $this->assertCount(12, Catalog::all());
        $this->get('http://scuttle.dev/services/legacy-health-check')->assertNotFound();
        $this->get('http://scuttle.dev/services/nope')->assertNotFound();
        $this->get('http://scuttle.dev/products/modernization')->assertRedirect('/services/modernization')->assertStatus(301);
    }

    public function test_home_copy_is_broad_and_has_no_reference_button(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();

        $this->assertStringNotContainsString('Ask for references', $home);
        $this->assertStringNotContainsString('run on PHP', $home);
        $this->assertStringContainsString('cannot switch off', $home);
        $this->assertStringContainsString('id="mega"', $home);
        $this->assertStringContainsString('data-dec', $home);
        $this->assertSame(0, substr_count($home, 'href="https://splotnikov.dev"'), 'splotnikov.dev is not in the footer');
        $this->assertSame(2, substr_count((string) $this->get('http://scuttle.dev/contact')->getContent(), 'href="https://splotnikov.dev"'), 'splotnikov.dev in the About text and its links on the contact page');
        $this->assertStringContainsString('id="car-track"', $home);
        $this->assertSame(4, substr_count($home, 'class="case"'), 'four case studies in the carousel');
        $this->assertStringNotContainsString('<b>0</b>', $home);
    }

    public function test_the_vcard_is_served_as_a_contact_file_and_the_qr_carries_it(): void
    {
        $r = $this->get('http://scuttle.dev/scuttle.vcf')->assertOk()
            ->assertHeader('Content-Type', 'text/vcard; charset=utf-8');
        $v = (string) $r->getContent();
        $this->assertStringStartsWith("BEGIN:VCARD\r\nVERSION:3.0\r\n", $v);
        $this->assertStringContainsString("\r\nFN:Stanislav Plotnikov\r\n", $v);
        $this->assertStringContainsString('TEL;TYPE=WORK,CELL,VOICE,PREF:+31618233175', $v);
        $this->assertStringContainsString('EMAIL;TYPE=INTERNET,WORK,PREF:info@scuttle.dev', $v);
        $this->assertStringContainsString('PHOTO;ENCODING=b;TYPE=JPEG:', $v);
        $this->assertStringNotContainsString('gmail', $v);
        $this->assertStringEndsWith("END:VCARD\r\n", $v);
        foreach (explode("\r\n", $v) as $line) {
            $this->assertLessThanOrEqual(76, strlen($line), 'vCard lines are folded at 75 bytes');
        }
        $this->assertFileExists(__DIR__.'/../../public/qr/scuttle-vcard.svg');
    }

    public function test_the_contact_form_sends_mail_and_blocks_bots(): void
    {
        Mail::fake();
        $ok = ['name' => 'Test Person', 'email' => 'test@example.com', 'about' => 'Health Check', 'message' => 'We run a Laravel shop and the exports take hours.'];
        $token = ContactController::token();

        // Too fast: the signed timestamp is newer than 3 seconds.
        $this->from('http://scuttle.dev/')->post('http://scuttle.dev/contact', $ok + ['t' => $token])
            ->assertRedirect('http://scuttle.dev/')->assertSessionHasErrors('message');
        Mail::assertNothingSent();

        $old = (string) (time() - 10);
        $oldToken = $old.'.'.hash_hmac('sha256', $old, (string) config('app.key'));

        // Honeypot filled: rejected.
        $this->from('http://scuttle.dev/')->post('http://scuttle.dev/contact', $ok + ['t' => $oldToken, 'website' => 'http://spam'])
            ->assertSessionHasErrors('website');

        // Forged signature: rejected.
        $this->from('http://scuttle.dev/')->post('http://scuttle.dev/contact', $ok + ['t' => $old.'.forged'])
            ->assertSessionHasErrors('message');
        Mail::assertNothingSent();

        // A real message: mailed to info@scuttle.dev with the sender as reply-to.
        $this->from('http://scuttle.dev/')->post('http://scuttle.dev/contact', $ok + ['t' => $oldToken])
            ->assertRedirect('http://scuttle.dev/')->assertSessionHas('contact_sent', true)->assertSessionHasNoErrors();
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->build();
            $rendered = $mail->render(); // the real bug of 2026-10-24: a view variable named message collides with Laravel's Message object

            return $mail->hasTo('info@scuttle.dev') && $mail->hasReplyTo('test@example.com') && $mail->about === 'Health Check' && str_contains($rendered, 'exports take hours');
        });
    }

    public function test_the_new_sections_render_and_the_menu_lists_them(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();
        foreach (['/work', '/case-studies', '/projects', '/blog', '/company', '/contact', '/pricing'] as $path) {
            $this->assertStringContainsString('href="http://scuttle.dev'.$path.'"', $home, $path.' in the menu');
        }
        $this->assertStringContainsString('>Services<', $home);
        $this->assertStringNotContainsString('>Products<', $home);
        $this->assertStringNotContainsString('#sectors"', $home, 'sectors are not in the menu');

        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();
        foreach (Posts::all() as $post) {
            $this->get('http://scuttle.dev/blog/'.$post['slug'])->assertOk()->assertSee($post['title']);
            $this->assertStringContainsString('<loc>https://scuttle.dev/blog/'.$post['slug'].'</loc>', $sitemap);
        }
        foreach (Projects::all() as $pr) {
            $this->get('http://scuttle.dev/projects/'.$pr['slug'])->assertOk()->assertSee($pr['name']);
            if (count($pr['docs'])) {
                $this->get('http://scuttle.dev/projects/'.$pr['slug'].'/docs')->assertOk()->assertSee('On this page');
            } else {
                $this->get('http://scuttle.dev/projects/'.$pr['slug'].'/docs')->assertNotFound();
            }
        }
        $this->get('http://scuttle.dev/blog/nope')->assertNotFound();
        $this->get('http://scuttle.dev/projects/nope')->assertNotFound();

        // Contact: the form comes first, then two cards, nothing in between.
        $contact = (string) $this->get('http://scuttle.dev/contact')->getContent();
        $this->assertLessThan(strpos($contact, 'class="cc rv"'), strpos($contact, 'id="cform"'));
        $this->assertSame(2, substr_count($contact, 'class="cc rv"'));
    }

    public function test_analytics_and_the_cookie_banner_only_exist_with_an_id(): void
    {
        $this->assertStringNotContainsString('id="cookie"', (string) $this->get('http://scuttle.dev/')->getContent());
        $this->assertStringNotContainsString('googletagmanager', (string) $this->get('http://scuttle.dev/')->getContent());

        config(['scuttle-dev.ga_id' => 'G-TEST1234']);
        $home = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringContainsString('id="cookie"', $home);
        $this->assertStringContainsString('window.SCUTTLE_GA = "G-TEST1234"', $home);
        $this->assertStringNotContainsString('googletagmanager.com/gtag/js?id=', $home, 'the analytics script is never in the HTML, only loaded after Agree');
        $this->get('http://scuttle.dev/privacy')->assertSee('Google Analytics');
    }

    public function test_the_new_pages_do_not_name_the_client(): void
    {
        $page = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->getContent();

        $this->assertStringNotContainsStringIgnoringCase('hosintra', $page);
        $this->assertStringNotContainsStringIgnoringCase('zero downtime', $page);
        $this->assertStringNotContainsString('0</b> minutes', $page);
    }
}
