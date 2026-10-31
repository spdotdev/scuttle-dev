<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Faq;
use Spdotdev\ScuttleDev\Http\Controllers\ContactController;
use Spdotdev\ScuttleDev\Links;
use Spdotdev\ScuttleDev\Mail\ContactMessage;
use Spdotdev\ScuttleDev\Models\ContactLead;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Projects;
use Spdotdev\ScuttleDev\Tests\TestCase;

class SiteTest extends TestCase
{
    public function test_book_a_call_opens_the_booking_page_on_every_page(): void
    {
        $home = $this->get('http://scuttle.dev/')->assertOk()->getContent();
        // The top bar button and the contact section both lead to the booking page in sd-plan.
        $this->assertSame(3, substr_count($home, 'href="https://plan.scuttle.dev/book/stas"'), 'the offer in the hero, the top bar and the contact section');
        $this->assertStringNotContainsString('subject=Call%20about', $home);

        $this->get('http://scuttle.dev/health-check')->assertOk()
            ->assertSee('href="https://plan.scuttle.dev/book/stas"', false);
    }

    public function test_search_snippets_fit_and_structured_data_is_valid(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/contact', '/services', '/case-studies', '/news', '/company', '/projects', '/privacy'];
        foreach (Catalog::all() as $p) {
            $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
        }
        foreach (Cases::all() as $c) {
            $paths[] = '/case-studies/'.$c['slug'];
        }
        foreach (Posts::all() as $post) {
            $paths[] = '/news/'.$post['slug'];
        }
        foreach ($paths as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match('/<title>(.*?)<\/title>/s', $html, $t);
            preg_match('/name="description" content="(.*?)"/s', $html, $d);
            $this->assertLessThanOrEqual(70, mb_strlen(html_entity_decode($t[1])), $path.' title length');
            $this->assertLessThanOrEqual(160, mb_strlen(html_entity_decode($d[1])), $path.' description length');
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $blocks);
            $this->assertNotEmpty($blocks[1], $path.' has structured data');
            $this->assertStringNotContainsString('fonts.googleapis.com', $html, $path.' loads no Google Fonts');
            preg_match_all('/<h([1-6])[ >]/', (string) preg_replace('#<script.*?</script>#s', '', $html), $levels);
            $prev = 0;
            foreach ($levels[1] as $level) {
                $this->assertLessThanOrEqual($prev + 1, (int) $level, $path.' skips a heading level');
                $prev = (int) $level;
            }
            $this->assertStringContainsString('class="skip"', $html, $path.' has a skip link');
            foreach ($blocks[1] as $json) {
                $data = json_decode($json, true);
                $this->assertIsArray($data, $path.' ld+json parses');
                $this->assertSame('https://schema.org', $data['@context'] ?? null, $path.' ld+json context');
            }
        }
        $this->assertStringContainsString('og:type" content="article"', (string) $this->get('http://scuttle.dev/news/'.array_values(Posts::all())[0]['slug'])->getContent());
        $this->assertStringContainsString('og:type" content="website"', (string) $this->get('http://scuttle.dev/pricing')->getContent());
    }

    public function test_dutch_twins_are_real_pages_with_hreflang(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services', '/contact', '/company', '/privacy', '/case-studies'];
        foreach (Catalog::all() as $p) {
            $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
        }
        foreach (Cases::all() as $c) {
            $paths[] = '/case-studies/'.$c['slug'];
        }
        foreach ($paths as $path) {
            $nlPath = '/nl'.($path === '/' ? '' : $path);
            $nl = (string) $this->get('http://scuttle.dev'.$nlPath)->assertOk()->getContent();
            $en = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('<html lang="nl"', $nl, $nlPath);
            $this->assertStringContainsString('<link rel="canonical" href="https://scuttle.dev'.$nlPath.'" />', $nl, $nlPath.' canonical');
            foreach ([$en, $nl] as $html) {
                $this->assertStringContainsString('hreflang="nl" href="https://scuttle.dev'.$nlPath.'"', $html, $path.' hreflang nl');
                $this->assertStringContainsString('hreflang="x-default" href="https://scuttle.dev'.($path === '/' ? '/' : $path).'"', $html, $path.' hreflang x-default');
            }
            preg_match('/<title>(.*?)<\/title>/s', $nl, $t);
            preg_match('/name="description" content="(.*?)"/s', $nl, $d);
            $this->assertLessThanOrEqual(70, mb_strlen(html_entity_decode($t[1])), $nlPath.' title');
            $this->assertLessThanOrEqual(160, mb_strlen(html_entity_decode($d[1])), $nlPath.' description');
            $this->assertNotSame(strip_tags($en), strip_tags($nl), $nlPath.' differs from English');
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $nl, $blocks);
            foreach ($blocks[1] as $json) {
                $this->assertIsArray(json_decode($json, true), $nlPath.' ld+json parses');
            }
            // paired links in the Dutch page stay Dutch; unpaired ones stay as they are
            $this->assertStringNotContainsString('href="https://scuttle.dev/pricing"', preg_replace('#<(link|a [^>]*hreflang)[^>]*>#', '', $nl), $nlPath.' links to /nl/pricing');
        }
        $this->assertStringContainsString('Automatiseer het werk', (string) $this->get('http://scuttle.dev/nl')->getContent());
        $this->get('http://scuttle.dev/nl/news')->assertNotFound();
        $this->get('http://scuttle.dev/nl/projects')->assertNotFound();
        $this->get('http://scuttle.dev/work')->assertRedirect('/services')->assertStatus(301);
        $this->get('http://scuttle.dev/nl/work')->assertRedirect('/nl/services')->assertStatus(301);
        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();
        $this->assertNotFalse(simplexml_load_string($sitemap), 'the sitemap is well-formed XML');
        $this->assertStringContainsString('<loc>https://scuttle.dev/nl</loc>', $sitemap);
        $this->assertStringContainsString('<loc>https://scuttle.dev/nl/services/modernization</loc>', $sitemap);
        $this->assertStringContainsString('hreflang="nl" href="https://scuttle.dev/nl/pricing"', $sitemap);
        $this->assertStringNotContainsString('/nl/news', $sitemap);
        // pages without a twin keep the in-place switch and carry no hreflang
        $news = (string) $this->get('http://scuttle.dev/news')->getContent();
        $this->assertStringNotContainsString('hreflang="nl"', $news);
        $this->assertStringContainsString('<button id="nl"', $news);
    }

    public function test_every_internal_link_and_social_image_resolves(): void
    {
        $pages = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services', '/contact', '/company', '/privacy', '/case-studies', '/news', '/projects', '/nl', '/nl/pricing', '/nl/services', '/nl/contact', '/nl/case-studies'];
        foreach (Catalog::all() as $p) {
            $pages[] = parse_url(Catalog::url($p), PHP_URL_PATH);
        }
        foreach (Cases::all() as $c) {
            $pages[] = '/case-studies/'.$c['slug'];
        }
        foreach (Posts::all() as $post) {
            $pages[] = '/news/'.$post['slug'];
        }
        foreach (Projects::all() as $pr) {
            $pages[] = '/projects/'.$pr['slug'];
            if (count($pr['docs'])) {
                $pages[] = '/projects/'.$pr['slug'].'/docs';
            }
        }
        $links = [];
        foreach ($pages as $page) {
            $html = (string) $this->get('http://scuttle.dev'.$page)->assertOk()->getContent();
            preg_match_all('#<a\b[^>]*\shref="(?:https://scuttle\.dev)?(/[^"\#]*)#', $html, $m);
            foreach ($m[1] as $href) {
                $links[$href] = $page;
            }
            if (preg_match('#og:image" content="https://scuttle\.dev/vendor/scuttle/(og/[a-z0-9-]+\.png)"#', $html, $img)) {
                $this->assertFileExists(__DIR__.'/../../public/'.$img[1], $page.' social image exists');
            }
        }
        foreach ($links as $href => $from) {
            if (preg_match('#\.(pdf|vcf|png|svg|jpg)$#', $href) && ! str_starts_with($href, '/legal')) {
                continue;
            }
            $status = $this->get('http://scuttle.dev'.$href)->getStatusCode();
            $this->assertContains($status, [200, 301], $href.' linked from '.$from.' answers '.$status);
        }
    }

    public function test_missing_pages_get_the_site_own_404(): void
    {
        foreach (['/nope', '/services/nope', '/news/nope', '/nl/news'] as $path) {
            $r = $this->get('http://scuttle.dev'.$path)->assertNotFound();
            $r->assertSee('That page does not exist', false);
            $r->assertSee('noindex', false);
        }
    }

    public function test_sitemap_urls_resolve_and_hreflang_pairs_are_reciprocal(): void
    {
        $host = 'scuttle.dev';
        $xml = simplexml_load_string((string) $this->get('http://scuttle.dev/sitemap.xml')->assertOk()->getContent());
        $this->assertNotFalse($xml, 'the sitemap is well-formed XML');
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $seen = [];
        $pairs = [];
        foreach ($xml->url as $url) {
            $loc = (string) $url->loc;
            $this->assertStringStartsWith('https://'.$host, $loc);
            $this->assertArrayNotHasKey($loc, $seen, $loc.' is listed twice');
            $seen[$loc] = true;
            $path = (string) parse_url($loc, PHP_URL_PATH) ?: '/';
            $html = (string) $this->get('http://'.$host.$path)->assertOk()->getContent();
            $this->assertStringContainsString('<link rel="canonical" href="'.$loc.'" />', $html, $loc.' names itself as canonical');
            $robots = preg_match('#<meta name="robots" content="([^"]*)"#', $html, $r) ? $r[1] : '';
            $this->assertStringNotContainsString('noindex', $robots, $loc.' is indexable');
            foreach ($url->children('xhtml', true) as $alt) {
                $attrs = $alt->attributes();
                $pairs[$loc][(string) $attrs['hreflang']] = (string) $attrs['href'];
            }
            preg_match_all('#<link rel="alternate" hreflang="([^"]+)" href="([^"]+)" />#', $html, $m, PREG_SET_ORDER);
            $inPage = [];
            foreach ($m as $row) {
                $inPage[$row[1]] = $row[2];
            }
            if (isset($pairs[$loc])) {
                $this->assertSame($pairs[$loc], $inPage, $loc.': the sitemap alternates and the page alternates are the same');
            }
        }
        foreach ($pairs as $loc => $alts) {
            foreach (['en', 'nl'] as $lang) {
                $this->assertArrayHasKey($lang, $alts, $loc.' lists '.$lang);
                $this->assertArrayHasKey($alts[$lang], $seen, $loc.' points at '.$alts[$lang].' which is not in the sitemap');
                // reciprocal: the other page lists this one under the same two languages
                $this->assertSame($alts, $pairs[$alts[$lang]] ?? null, $alts[$lang].' does not list the same pair back');
            }
            $this->assertSame($alts['en'], $alts['x-default'], $loc.' x-default is the English page');
        }
    }

    public function test_content_pages_set_no_cookie_and_the_contact_form_keeps_its_session(): void
    {
        foreach (['/', '/nl', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services/modernization', '/privacy', '/news', '/case-studies/api-meets-ai', '/robots.txt', '/sitemap.xml'] as $path) {
            $response = $this->get('http://scuttle.dev'.$path)->assertOk();
            $this->assertEmpty($response->headers->getCookies(), $path.' sets a cookie');
        }
        $contact = $this->get('http://scuttle.dev/contact')->assertOk();
        $this->assertNotEmpty($contact->headers->getCookies(), 'the contact page needs its session for the CSRF token');
        $this->assertStringContainsString('name="_token"', (string) $contact->getContent());
    }

    public function test_the_pricing_glance_row_matches_the_catalog(): void
    {
        $html = (string) $this->get('http://scuttle.dev/pricing')->assertOk()->getContent();
        $this->assertStringContainsString('class="glance"', $html);
        $this->assertStringContainsString('EUR 90', $html);
        $this->assertStringContainsString('Fixed price, EUR 1,080 excl. VAT (12 hours)', Catalog::get('legacy-health-check')['price']);
        $this->assertStringContainsString('<b data-i="pr_g2b">EUR 1,080</b>', $html);
        $this->assertStringContainsString('<b data-i="pr_g3b">from EUR 720</b>', $html);
        $this->assertStringContainsString('Vanaf EUR 720 per maand', Catalog::get('maintenance-retainer')['nl']['price']);
        $this->assertStringContainsString('<b data-i="pr_g2b">EUR 1.080</b>', (string) $this->get('http://scuttle.dev/nl/pricing')->getContent());
    }

    public function test_the_form_does_not_say_sent_when_the_mailer_only_logs(): void
    {
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        $page = $this->get('http://scuttle.dev/contact');
        $token = ContactController::token();
        $this->withSession([])->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', [
            'name' => 'A', 'email' => 'a@example.com', 'message' => 'A real message of enough length.', 't' => $this->agedToken(), 'website' => '',
        ])->assertSessionHas('contact_sent', 'stored')->assertSessionHasNoErrors();
        $lead = ContactLead::query()->sole();
        $this->assertSame('not_configured', $lead->mail_status);
        $this->assertSame('a@example.com', $lead->email);
    }

    public function test_a_contact_mail_dsn_makes_the_form_deliverable_even_when_the_default_mailer_logs(): void
    {
        Mail::fake();
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'log', 'scuttle-dev.mail_dsn' => 'smtp://user:secret@127.0.0.1:2525']);
        $this->get('http://scuttle.dev/contact');
        $this->withSession([])->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', [
            'name' => 'A', 'email' => 'a@example.com', 'message' => 'A real message of enough length.', 't' => $this->agedToken(), 'website' => '',
        ])->assertSessionHas('contact_sent', 'mailed');
        $this->assertSame('sent', ContactLead::query()->sole()->mail_status);
        $this->assertSame('smtp://user:secret@127.0.0.1:2525', config('mail.mailers.contact_form.url'));
    }

    public function test_the_contact_session_cookie_has_no_shared_domain(): void
    {
        config(['session.domain' => '.scuttle.dev']);
        $cookies = $this->get('http://scuttle.dev/contact')->assertOk()->headers->getCookies();
        $this->assertNotEmpty($cookies);
        foreach ($cookies as $cookie) {
            $this->assertNull($cookie->getDomain(), $cookie->getName().' must be a host-only cookie on scuttle.dev');
        }
    }

    public function test_landing_pages_carry_sourced_facts_and_say_what_is_illustrative(): void
    {
        $php = (string) $this->get('http://scuttle.dev/php-check')->assertOk()->getContent();
        // The four rows of php.net/supported-versions.php read on 2026-10-30; update the page and this test when php.net changes.
        foreach (['8.2', '8 Dec 2022', '31 Dec 2026', '8.3', '31 Dec 2027', '8.4', '31 Dec 2028', '8.5', '31 Dec 2029'] as $cell) {
            $this->assertMatchesRegularExpression('#<td[^>]*>'.preg_quote($cell, '#').'</td>#', $php, $cell);
        }
        $this->assertStringContainsString('>8 dec 2022</td>', (string) $this->get('http://scuttle.dev/nl/php-check')->getContent());
        $this->assertStringContainsString('href="https://www.php.net/supported-versions.php"', $php);
        $this->assertStringContainsString('EUR 1,080', $php);
        $this->assertStringContainsString('composer audit', $php);

        $sample = (string) $this->get('http://scuttle.dev/health-check/sample')->assertOk()->getContent();
        $this->assertStringContainsString('Illustration.', $sample);
        $this->assertStringContainsString('invented', $sample);
        $this->assertStringNotContainsString('hosintra', strtolower($sample));

        $nis = (string) $this->get('http://scuttle.dev/nis2')->assertOk()->getContent();
        foreach (['15 August 2026', '11 September 2026', '11 December 2027', 'Staatsblad 2026, 189', 'ncsc.nl/cyberbeveiligingswet-nis2', 'nctv.nl', 'digital-strategy.ec.europa.eu'] as $needle) {
            $this->assertStringContainsString($needle, $nis, $needle);
        }
        $this->assertStringContainsString('I am not a lawyer', $nis);

        $nl = (string) $this->get('http://scuttle.dev/nl/php-check')->assertOk()->getContent();
        $this->assertStringContainsString('Welke PHP-versie draait je systeem', $nl);
        $this->assertStringContainsString('<code>composer audit</code>', $nl);
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
        $this->assertStringContainsString('kvk.nl/zoeken/#/96040947000061394009/', $contact);
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
        $privacy = (string) $this->get('http://scuttle.dev/privacy')->assertOk()->getContent();
        foreach (['Privacy statement', 'Privacyverklaring', 'stas@scuttle.dev', 'Opting out', 'Afmelden', 'Anthropic', 'no tracking or analytics cookies', 'geen tracking- of analysecookies', '31 October 2026'] as $needle) {
            $this->assertStringContainsString($needle, $privacy);
        }
        $this->assertStringNotContainsString('Gemini', $privacy, 'the lead tooling runs on Anthropic since 2026-10-28');
        $article = substr($privacy, (int) strpos($privacy, '<article'), (int) strpos($privacy, '</article>') - (int) strpos($privacy, '<article'));
        $this->assertStringNotContainsString('Google Analytics', $article, 'no analytics on the site today');

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
        foreach (['/', '/privacy', '/pricing', '/contact', '/services', '/case-studies', '/news', '/company', '/projects', '/projects/manual/docs', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services/modernization', '/case-studies/phalcon-to-laravel'] as $path) {
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
        foreach (['/', '/pricing', '/contact', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services/ai-integration', '/services/modernization', '/case-studies/phalcon-to-laravel', '/case-studies/business-on-agents', '/services', '/company', '/news', '/projects'] as $path) {
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
        $this->assertSame(2, substr_count($html, 'class="badge"'), 'one recommended card per block carries a badge');
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
        $this->assertStringContainsString('Start with a Health Check', $home);
        $this->assertStringContainsString('EUR 1,080', $home);
        $this->assertStringNotContainsString('id="sectors"', $home, 'the sectors block is gone (council, 2026-10-30)');
        $this->assertStringContainsString('id="mega"', $home);
        $this->assertStringContainsString('data-dec', $home);
        $this->assertSame(0, substr_count($home, 'href="https://splotnikov.dev"'), 'splotnikov.dev is not in the footer');
        $this->assertSame(2, substr_count((string) $this->get('http://scuttle.dev/contact')->getContent(), 'href="https://splotnikov.dev"'), 'splotnikov.dev in the About text and its links on the contact page');
        $this->assertStringContainsString('id="car-track"', $home);
        $this->assertSame(4, substr_count($home, 'class="case"'), 'four case studies in the carousel');
        $this->assertStringNotContainsString('<b>0</b>', $home);
        $this->assertStringContainsString('id="trust"', $home, 'the trust block');
        $this->assertSame(4, substr_count($home, 'class="tt"'), 'four trust tiles');
        // The services loop (v0.7.31): five steps and the Ongoing hub, every service linked once in its step, the nine extra entries open the contact form with their name.
        $this->assertStringContainsString('id="loop"', $home, 'the loop');
        $this->assertSame(6, substr_count($home, '<section class="lp'), 'five steps and the hub');
        $this->assertSame(0, substr_count($home, 'class="tile rv"'), 'the tiles are gone');
        preg_match('/<div class="loop"(.*?)<p class="loop-hint"/s', $home, $lm);
        $loopHtml = $lm[1] ?? '';
        foreach (\Spdotdev\ScuttleDev\Catalog::all() as $p) {
            $this->assertSame(1, substr_count($loopHtml, 'href="'.\Spdotdev\ScuttleDev\Catalog::url($p).'"'), $p['name'].' once in the loop');
        }
        foreach (\Spdotdev\ScuttleDev\Catalog::EXTRA as $key => $e) {
            $this->assertStringContainsString('/contact?about='.rawurlencode($e[2]).'"', $loopHtml, $e[2].' opens the contact form');
            $this->assertStringContainsString('data-i="t_'.$key.'"', $loopHtml);
        }
        $this->assertSame(21, substr_count($loopHtml, '<li><a href='), 'twelve services and nine extra entries');
        $this->assertStringContainsString('data-i="lp_sub_ai"', $loopHtml, 'Build is split in General and AI and automation');
        $this->assertStringContainsString('Performance Test', (string) $this->get('http://scuttle.dev/contact?about=Performance+Test')->getContent(), 'the contact form knows the extra entries');
        foreach (['SOC 2', 'ISO 27001', 'certified', 'Certified'] as $claim) {
            $this->assertStringNotContainsString($claim, $home, 'no certificate we do not hold');
        }
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
            ->assertRedirect('http://scuttle.dev/')->assertSessionHas('contact_sent', 'mailed')->assertSessionHasNoErrors();
        $this->assertSame(1, ContactLead::query()->count(), 'only the real message is stored, not the rejected ones');
        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $mail->build();
            $rendered = $mail->render(); // the real bug of 2026-10-24: a view variable named message collides with Laravel's Message object

            return $mail->hasTo('info@scuttle.dev') && $mail->hasReplyTo('test@example.com') && $mail->about === 'Health Check' && str_contains($rendered, 'exports take hours');
        });
    }

    public function test_the_new_sections_render_and_the_menu_lists_them(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();
        foreach (['/services', '/case-studies', '/projects', '/news', '/company', '/contact', '/pricing'] as $path) {
            $this->assertStringContainsString('href="http://scuttle.dev'.$path.'"', $home, $path.' in the menu');
        }
        $this->assertStringContainsString('>Services<', $home);
        $this->assertStringNotContainsString('>Products<', $home);
        $this->assertStringNotContainsString('#sectors"', $home, 'sectors are not in the menu');

        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();
        foreach (Posts::all() as $post) {
            $this->get('http://scuttle.dev/news/'.$post['slug'])->assertOk()->assertSee($post['title']);
            $this->assertStringContainsString('<loc>https://scuttle.dev/news/'.$post['slug'].'</loc>', $sitemap);
        }
        foreach (Projects::all() as $pr) {
            $this->get('http://scuttle.dev/projects/'.$pr['slug'])->assertOk()->assertSee($pr['name']);
            if (count($pr['docs'])) {
                $this->get('http://scuttle.dev/projects/'.$pr['slug'].'/docs')->assertOk()->assertSee('On this page');
            } else {
                $this->get('http://scuttle.dev/projects/'.$pr['slug'].'/docs')->assertNotFound();
            }
        }
        $this->get('http://scuttle.dev/news/nope')->assertNotFound();
        $this->get('http://scuttle.dev/blog')->assertStatus(301)->assertRedirect('/news');
        foreach (Posts::all() as $post) {
            $this->get('http://scuttle.dev/blog/'.$post['slug'])->assertStatus(301)->assertRedirect('/news/'.$post['slug']);
        }
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
    }

    public function test_the_own_analytics_snippet_only_exists_with_a_key_and_the_statement_follows(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringNotContainsString('/_sda/', $home);
        $off = (string) $this->get('http://scuttle.dev/privacy')->getContent();
        $this->assertStringContainsString('Should I ever add visitor statistics', $off);
        $this->assertStringNotContainsString('pv_stats', substr($off, 0, (int) strpos($off, '<script>Object')), 'no counting text while the tool is off');

        config(['scuttle-dev.analytics_key' => 'abcDEF123456']);
        $response = $this->get('http://scuttle.dev/');
        $home = (string) $response->getContent();
        $this->assertStringContainsString('<script defer data-site="abcDEF123456" src="/_sda/s.js"></script>', $home);
        $this->assertStringContainsString('<img src="/_sda/px/abcDEF123456.gif"', $home);
        $this->assertStringContainsString("sda('Book a call click')", $home);
        $this->assertEmpty($response->headers->getCookies(), 'reading a page still sets no cookie');
        $this->assertStringNotContainsString('localStorage.setItem', $home);

        $on = (string) $this->get('http://scuttle.dev/privacy')->getContent();
        foreach (['I count visits with a small tool that runs on my own server', 'does not store your IP address', 'deleted after two days', 'Do Not Track', 'Single visits are kept for 30 days', 'Ik tel bezoeken met een klein programma', 'no tracking or analytics cookies', 'geen tracking- of analysecookies'] as $needle) {
            $this->assertStringContainsString($needle, $on);
        }
        $this->assertStringNotContainsString('Should I ever add visitor statistics', $on);
        $this->assertStringNotContainsString('Mocht ik ooit bezoekersstatistieken', $on);
        $article = substr($on, (int) strpos($on, '<article'), (int) strpos($on, '</article>') - (int) strpos($on, '<article'));
        $this->assertStringNotContainsString('Google Analytics', $article);
    }

    public function test_the_contact_success_event_is_only_added_with_a_key(): void
    {
        $this->withSession(['contact_sent' => 'mailed'])->get('http://scuttle.dev/contact')->assertDontSee("sda('Contact form sent')", false);

        config(['scuttle-dev.analytics_key' => 'abcDEF123456']);
        $this->withSession(['contact_sent' => 'mailed'])->get('http://scuttle.dev/contact')->assertSee("sda('Contact form sent')", false);
    }

    public function test_the_new_pages_do_not_name_the_client(): void
    {
        $page = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->getContent();

        $this->assertStringNotContainsStringIgnoringCase('hosintra', $page);
        $this->assertStringNotContainsStringIgnoringCase('zero downtime', $page);
        $this->assertStringNotContainsString('0</b> minutes', $page);
    }

    /**
     * Every page can be switched to Dutch: the switch is in the bar, and every
     * element marked data-i has a Dutch string on that page (shell keys live in
     * the layout, page keys are pushed into the nl stack).
     */
    public function test_every_page_is_translatable(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/contact', '/services', '/case-studies', '/case-studies/phalcon-to-laravel', '/news', '/company', '/projects', '/projects/manual', '/privacy'];
        foreach (Catalog::all() as $p) {
            $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
        }
        foreach (Posts::all() as $post) {
            $paths[] = '/news/'.$post['slug'];
        }
        // The shell's own keys, from the layout; a page must mark its own text on top of those.
        preg_match_all('/\b([a-z0-9_]+): "/', (string) file_get_contents(__DIR__.'/../../resources/views/layout.blade.php'), $shell);
        foreach ($paths as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('id="nl"', $html, $path.' has the language switch');
            $this->assertStringContainsString('Object.assign(window.SCUTTLE_NL.t', $html, $path.' pushes its Dutch strings');
            preg_match_all('/data-i="([A-Za-z0-9_]+)"/', $html, $m);
            $own = array_diff(array_unique($m[1]), $shell[1]);
            $this->assertGreaterThanOrEqual(8, count($own), $path.' marks its own text for translation (found '.count($own).' keys)');
            foreach (array_unique($m[1]) as $key) {
                $this->assertTrue(
                    (bool) preg_match('/[{,\s]"?'.preg_quote($key, '/').'"?\s*:/', $html),
                    $path.' has no Dutch string for data-i="'.$key.'"'
                );
            }
        }
    }

    public function test_an_sd_cms_article_maps_to_a_post_and_lands_in_the_sitemap(): void
    {
        $article = (object) ['slug' => 'from-cms', 'title' => 'From the CMS', 'summary' => 'Sum', 'body' => "First.\n\n## Head\n\n- one\n- two\n\nLast.", 'tags' => ['PHP', 'News'], 'published_at' => new \DateTimeImmutable('2026-11-02 10:00:00'), 'title_nl' => 'Uit het CMS', 'summary_nl' => null, 'sells' => 'maintenance-retainer'];
        $post = Posts::fromArticle($article);

        $this->assertSame('from-cms', $post['slug']);
        $this->assertSame('2026-11-02', $post['date']);
        $this->assertSame(['First.', '## Head', '- one', '- two', 'Last.'], $post['body']);
        $this->assertSame(['title' => 'Uit het CMS', 'summary' => 'Sum'], $post['nl']);
        $this->assertSame(['maintenance-retainer'], $post['products']);
        $this->assertSame('maintenance-retainer', $post['sells']);
        $this->assertArrayHasKey('php-security-release-october-2026', Posts::all(), 'built-in articles stay without sd-cms');
        $this->get('http://scuttle.dev/sitemap.xml')->assertOk()->assertSee('<urlset', false);
    }

    /** A form token that is old enough to pass the minimum fill time (3 seconds). */
    private function agedToken(): string
    {
        $t = (string) (time() - 10);

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    public function test_faq_blocks_match_their_faqpage_data_in_both_languages(): void
    {
        foreach (['/services/modernization', '/health-check', '/services/hardening', '/services/maintenance-retainer'] as $path) {
            foreach (['', '/nl'] as $prefix) {
                $nl = $prefix === '/nl';
                $html = (string) $this->get('http://scuttle.dev'.$prefix.$path)->assertOk()->getContent();
                preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
                $faq = null;
                foreach ($m[1] as $json) {
                    $d = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                    if (($d['@type'] ?? '') === 'FAQPage') {
                        $faq = $d;
                    }
                }
                $this->assertNotNull($faq, $prefix.$path.' has FAQPage data');
                $slug = $path === '/health-check' ? 'legacy-health-check' : basename($path);
                $items = Faq::for($slug);
                $this->assertCount(count($items), $faq['mainEntity']);
                foreach ($items as $i => $f) {
                    $q = $nl ? $f['qn'] : $f['q'];
                    $a = $nl ? $f['an'] : $f['a'];
                    $this->assertSame($q, $faq['mainEntity'][$i]['name']);
                    $this->assertSame($a, $faq['mainEntity'][$i]['acceptedAnswer']['text']);
                    $this->assertStringContainsString(htmlspecialchars($q, ENT_QUOTES | ENT_HTML5), $html, 'question visible: '.$q);
                    $this->assertStringContainsString(htmlspecialchars($a, ENT_QUOTES | ENT_HTML5), $html, 'answer visible: '.$a);
                }
            }
        }
    }

    public function test_faq_prices_match_the_catalog(): void
    {
        $hc = Faq::for('legacy-health-check')[0]['a'];
        $this->assertStringContainsString('EUR 1,080', $hc);
        $this->assertStringContainsString('EUR 1,080', Catalog::get('legacy-health-check')['price']);
        $this->assertStringContainsString('EUR 1.080', Faq::for('legacy-health-check')[0]['an']);
        $this->assertStringContainsString('EUR 720', Faq::for('maintenance-retainer')[0]['a']);
    }

    /** @return array<string, string> */
    private function validForm(array $over = []): array
    {
        return $over + ['name' => 'Anna', 'email' => 'anna@example.com', 'about' => 'Health Check', 'message' => 'We run an old PHP portal and need a plan.', 't' => $this->agedToken(), 'website' => ''];
    }

    public function test_a_stored_lead_survives_a_mail_failure(): void
    {
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'smtp', 'scuttle-dev.mail_dsn' => '']);
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('smtp down'));
        $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $this->validForm())
            ->assertSessionHas('contact_sent', 'stored')->assertSessionHasNoErrors();
        $lead = ContactLead::query()->sole();
        $this->assertSame('failed', $lead->mail_status);
        $this->assertSame('nl', $lead->language);
        $this->assertSame('/nl/contact', $lead->page);
    }

    public function test_the_page_says_saved_and_not_sent_when_only_stored(): void
    {
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm())->assertSessionHas('contact_sent', 'stored');
        $html = (string) $this->withSession(['contact_sent' => 'stored'])->get('http://scuttle.dev/contact')->getContent();
        $this->assertStringContainsString('Received and saved', $html);
        $this->assertStringNotContainsString('data-i="ctp_sent"', $html);
    }

    public function test_the_ip_is_stored_only_as_a_hash(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm());
        $lead = ContactLead::query()->sole();
        $this->assertSame(32, strlen((string) $lead->ip_hash));
        $this->assertStringNotContainsString('203.0.113.9', json_encode($lead->getAttributes()));
    }

    public function test_nothing_is_stored_when_the_message_cannot_be_stored(): void
    {
        Schema::drop('scuttle_contact_leads');
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm())
            ->assertSessionHasErrors('message')->assertSessionMissing('contact_sent');
    }

    public function test_the_leads_command_lists_and_purges_only_test_rows(): void
    {
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm());
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm(['name' => '[TEST] check']));
        $this->assertSame(1, ContactLead::query()->where('is_test', true)->count());
        $this->artisan('scuttle:contact-leads')->expectsOutputToContain('2 shown, 2 stored in total')->assertSuccessful();
        $this->artisan('scuttle:contact-leads', ['--purge-test' => true])->expectsOutputToContain('Deleted 1 test')->assertSuccessful();
        $this->assertSame(['Anna'], ContactLead::query()->pluck('name')->all());
    }

    public function test_a_failing_insert_logs_no_visitor_data(): void
    {
        Schema::drop('scuttle_contact_leads');
        Log::shouldReceive('error')->once()->withArgs(function (string $msg, array $ctx = []): bool {
            $all = $msg.json_encode($ctx);
            foreach (['Anna', 'anna@example.com', 'old PHP portal', 'insert into'] as $secret) {
                if (stripos($all, $secret) !== false) {
                    return false;
                }
            }

            return isset($ctx['exception']) && isset($ctx['sqlstate']) && ! isset($ctx['error']);
        });
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm())->assertSessionHasErrors('message');
    }

    public function test_control_characters_are_rejected_at_validation(): void
    {
        foreach ([['name' => "An\e[2Jna"], ['email' => "a\x00@example.com"], ['about' => "x\ry"], ['message' => "Hello there \e[31mred\e[0m please"], ['message' => "Hello there \x07 bell please"]] as $bad) {
            $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm($bad))->assertSessionHasErrors();
        }
        $this->assertSame(0, ContactLead::query()->count());
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm(['message' => "Line one of the message\nLine two\twith a tab."]))->assertSessionHasNoErrors();
        $this->assertSame(1, ContactLead::query()->count());
    }

    public function test_the_leads_command_prints_stored_text_without_escapes_or_tags(): void
    {
        $l = ContactLead::create(['name' => "Eve\e[2J<info>x</info>", 'email' => 'e@example.com', 'about' => "a\e[31m", 'message' => "Hello \e[2J<error>boom</error>\x07 there", 'language' => 'en', 'mail_status' => 'pending']);
        foreach ([[], ['--id' => $l->id]] as $opts) {
            Artisan::call('scuttle:contact-leads', $opts);
            $out = Artisan::output();
            $this->assertStringNotContainsString("\e", $out);
            $this->assertStringNotContainsString("\x07", $out);
            $this->assertStringContainsString('<info>', $out, 'a style tag from a visitor is printed literally, not applied');
        }
    }

    public function test_dutch_twins_have_a_dutch_title_and_description(): void
    {
        foreach (['/health-check/sample', '/php-check', '/nis2', '/company', '/health-check'] as $path) {
            $en = (string) $this->get('http://scuttle.dev'.$path)->getContent();
            $nl = (string) $this->get('http://scuttle.dev/nl'.$path)->getContent();
            preg_match('#<title>([^<]*)</title>#', $en, $te);
            preg_match('#<title>([^<]*)</title>#', $nl, $tn);
            preg_match('#name="description" content="([^"]*)"#', $en, $de);
            preg_match('#name="description" content="([^"]*)"#', $nl, $dn);
            $this->assertNotSame($te[1], $tn[1], '/nl'.$path.' needs its own title');
            $this->assertNotSame($de[1], $dn[1], '/nl'.$path.' needs its own description');
        }
    }

    public function test_a_lead_without_a_sent_mail_raises_a_critical_log_line_with_no_visitor_data(): void
    {
        config(['scuttle-dev.require_real_mailer' => true, 'mail.default' => 'log']);
        Log::shouldReceive('critical')->once()->withArgs(function (string $msg, array $ctx = []): bool {
            foreach (['Anna', 'anna@example.com', 'old PHP portal'] as $secret) {
                if (stripos($msg.json_encode($ctx), $secret) !== false) {
                    return false;
                }
            }

            return str_contains($msg, 'lead #1') && str_contains($msg, 'scuttle:contact-leads --unmailed');
        });
        $this->from('http://scuttle.dev/contact')->post('http://scuttle.dev/contact', $this->validForm())->assertSessionHas('contact_sent', 'stored');
    }

    public function test_money_pages_link_to_their_related_reading_in_both_languages(): void
    {
        foreach (Links::all() as $slug => $links) {
            $path = $slug === 'legacy-health-check' ? '/health-check' : '/services/'.$slug;
            $en = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $nl = (string) $this->get('http://scuttle.dev/nl'.$path)->assertOk()->getContent();
            foreach ($links as $l) {
                $this->assertStringContainsString('href="http://scuttle.dev'.$l['path'].'"', $en, $path.' links to '.$l['path']);
                $this->assertStringContainsString($l['nl'], html_entity_decode(strip_tags($nl), ENT_QUOTES | ENT_HTML5), $path.' Dutch text for '.$l['path']);
                $this->get('http://scuttle.dev'.$l['path'])->assertOk();
            }
        }
        $this->assertStringContainsString('href="http://scuttle.dev/news/what-two-days-of-health-check-buy"', (string) $this->get('http://scuttle.dev/health-check')->getContent());
        foreach (['/nis2' => ['/health-check', '/services/hardening', '/health-check/sample'], '/health-check/sample' => ['/php-check', '/services/hardening', '/pricing']] as $page => $targets) {
            $html = (string) $this->get('http://scuttle.dev'.$page)->getContent();
            foreach ($targets as $t) {
                $this->assertStringContainsString('href="http://scuttle.dev'.$t.'"', $html, $page.' links to '.$t);
            }
        }
    }
}
