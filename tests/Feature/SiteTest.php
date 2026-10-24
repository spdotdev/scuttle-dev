<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
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
        foreach (['/', '/privacy', '/pricing', '/health-check', '/products/modernization', '/case-studies/phalcon-to-laravel'] as $path) {
            $this->get('http://scuttle.dev'.$path)
                ->assertOk()
                ->assertSee('vendor/scuttle/site.css', false)
                ->assertSee('vendor/scuttle/site.js', false)
                ->assertSee('vendor/scuttle/logo.svg', false)
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
            ->assertSee('from EUR 1,600', false)
            ->assertSee('https://scuttle.dev/health-check', false);

        $this->get('http://scuttle.dev/legacy-health-check')->assertRedirect('/health-check')->assertStatus(301);
        $this->get('http://scuttle.dev/')->assertSee('/health-check', false);
        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/health-check', false);
    }

    public function test_the_site_points_at_no_client_and_leads_with_automation(): void
    {
        foreach (['/', '/pricing', '/health-check', '/products/ai-integration', '/case-studies/phalcon-to-laravel'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            foreach (['hotel', 'hosintra', 'rental-platform', 'webshop-legacy', 'erp-bridge', 'Legacy Health Check'] as $word) {
                $this->assertStringNotContainsStringIgnoringCase($word, $html, $path.' must not contain '.$word);
            }
        }
        $home = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringContainsString('Automate the work', $home);
        $this->assertStringContainsString('Automation · Security · Eindhoven', $home);
        $this->assertStringContainsString('invoice-intake', $home);
        $this->assertStringContainsString('8.4.26 → 8.4.27', $home);
        $this->assertSame(3, substr_count($home, 'data-th="'), 'three-way theme control: system, light, dark');
        $this->assertSame(11, substr_count($home, 'class="it'), 'eleven clickable items in the demo panel');
        $this->assertStringNotContainsString('end of life since', $home);
    }

    public function test_the_case_study_page_renders_and_links_to_the_health_check(): void
    {
        $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')
            ->assertOk()
            ->assertSee('Phalcon to Laravel, module by module')
            ->assertSee('60%', false)
            ->assertSee('/health-check', false);

        $this->get('http://scuttle.dev/sitemap.xml')->assertSee('https://scuttle.dev/case-studies/phalcon-to-laravel', false);
    }

    public function test_the_pricing_page_explains_the_rate_and_lists_every_product(): void
    {
        $html = (string) $this->get('http://scuttle.dev/pricing')->assertOk()->getContent();

        $this->assertStringContainsString('€90', $html);
        $this->assertStringContainsString('from €1,600', $html);
        $this->assertStringContainsString('excl. VAT', $html);
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
        $this->get('http://scuttle.dev/products/legacy-health-check')->assertNotFound();
        $this->get('http://scuttle.dev/products/nope')->assertNotFound();
    }

    public function test_home_copy_is_broad_and_has_no_reference_button(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();

        $this->assertStringNotContainsString('Ask for references', $home);
        $this->assertStringNotContainsString('run on PHP', $home);
        $this->assertStringContainsString('cannot switch off', $home);
        $this->assertStringContainsString('id="mega"', $home);
        $this->assertStringContainsString('data-dec', $home);
    }

    public function test_the_new_pages_do_not_name_the_client(): void
    {
        $page = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->getContent();

        $this->assertStringNotContainsStringIgnoringCase('hosintra', $page);
        $this->assertStringNotContainsStringIgnoringCase('zero downtime', $page);
        $this->assertStringNotContainsString('0</b> minutes', $page);
    }
}
