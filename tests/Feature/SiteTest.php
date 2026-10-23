<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

class SiteTest extends TestCase
{
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
        $this->assertStringContainsString('vendor/scuttle/scuttledev.vcf', $html);
        $this->assertStringContainsString('vendor/scuttle/qr/svg/My_QR_Code_1-4096.svg', $html);
        $this->assertStringContainsString('id="qr-modal"', $html);
        $this->assertStringNotContainsString('maps.google', $html);
        $this->assertStringNotContainsString('<iframe', $html);
        $this->assertStringNotContainsString('/services/', $html);
    }

    public function test_home_shows_only_confirmed_facts(): void
    {
        $html = $this->get('http://scuttle.dev/')->assertOk()->getContent();

        $this->assertStringContainsString('KvK 96040947', $html);
        $this->assertStringContainsString('NL005184584B85', $html);
        $this->assertStringContainsString('from €1,600', $html);
        $this->assertStringContainsString('€90 per hour', $html);
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
            ->assertSee('Sitemap:');
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
        foreach (['/', '/privacy', '/legacy-health-check', '/case-studies/phalcon-to-laravel'] as $path) {
            $this->get('http://scuttle.dev'.$path)
                ->assertOk()
                ->assertSee('vendor/scuttle/site.css', false)
                ->assertSee('vendor/scuttle/site.js', false)
                ->assertSee('vendor/scuttle/logo.svg', false)
                ->assertSee('vendor/scuttle/icon.svg', false);
        }
    }

    public function test_the_legacy_health_check_page_renders_and_is_linked(): void
    {
        $this->get('http://scuttle.dev/legacy-health-check')
            ->assertOk()
            ->assertSee('Legacy Health Check')
            ->assertSee('from EUR 1,600', false)
            ->assertSee('https://scuttle.dev/legacy-health-check', false);

        $this->get('http://scuttle.dev/')->assertSee('/legacy-health-check', false);
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/legacy-health-check', false);
    }

    public function test_the_case_study_page_renders_and_links_to_the_health_check(): void
    {
        $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')
            ->assertOk()
            ->assertSee('Phalcon to Laravel, module by module')
            ->assertSee('60%', false)
            ->assertSee('/legacy-health-check', false);

        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/case-studies/phalcon-to-laravel', false);
    }

    public function test_the_new_pages_do_not_name_the_client(): void
    {
        $page = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->getContent();

        $this->assertStringNotContainsStringIgnoringCase('hosintra', $page);
        $this->assertStringNotContainsStringIgnoringCase('zero downtime', $page);
        $this->assertStringNotContainsString('0</b> minutes', $page);
    }
}
