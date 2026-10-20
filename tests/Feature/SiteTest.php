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

    public function test_sitemap_xml_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_page_links_namespaced_css_and_js(): void
    {
        // The static site bundled style.css + main.js via Vite; the package
        // serves them as assets. Guard against dropping the stylesheet/script.
        $this->get('http://scuttle.dev/')
            ->assertSee('vendor/scuttle/style.css', false)
            ->assertSee('vendor/scuttle/main.js', false);
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
            ->assertSee('Phalcon to Laravel without downtime')
            ->assertSee('60%', false)
            ->assertSee('/legacy-health-check', false);

        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/case-studies/phalcon-to-laravel', false);
    }

    public function test_the_new_pages_do_not_name_the_client(): void
    {
        $page = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->getContent();

        $this->assertStringNotContainsStringIgnoringCase('hosintra', $page);
    }
}
