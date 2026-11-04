<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Funnel events for sd-analytics: no cookie, counts only. The page markup carries the attribute the script reads; nothing is sent without the key. */
class FunnelEventsTest extends TestCase
{
    private const NAMES = ['Contact button click', 'vCard download', 'Language switch'];

    /** @return list<string> */
    private function paths(): array
    {
        $paths = ['/', '/nl', '/contact', '/nl/contact', '/pricing', '/services', '/company', '/case-studies', '/news', '/projects', '/projects/manual', '/projects/manual/docs', '/health-plan', '/nl/health-plan'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
            }
        }

        return $paths;
    }

    public function test_every_contact_button_and_the_vcard_and_language_links_carry_an_event_name(): void
    {
        $seen = [];
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('#<a [^>]*>#', $html, $m);
            foreach ($m[0] as $tag) {
                $isContactButton = str_contains($tag, 'class="btn') && preg_match('#href="https://scuttle\.dev(/nl)?/contact(\?[^"]*)?"#', $tag);
                if ($isContactButton) {
                    $this->assertStringContainsString('data-sda-event="Contact button click"', $tag, "$path: a contact button without an event name: $tag");
                }
                if (str_contains($tag, '/scuttle.vcf')) {
                    $this->assertStringContainsString('data-sda-event="vCard download"', $tag, "$path: vCard link without an event name");
                }
                if (preg_match('#^<a id="(en|nl)" #', $tag)) {
                    $this->assertStringContainsString('data-sda-event="Language switch"', $tag, "$path: language link without an event name");
                }
                if (preg_match('#data-sda-event="([^"]*)"#', $tag, $e)) {
                    $seen[$e[1]] = true;
                }
            }
        }
        foreach (self::NAMES as $name) {
            $this->assertArrayHasKey($name, $seen, "no page carries the event $name");
        }
        foreach (array_keys($seen) as $name) {
            $this->assertContains($name, self::NAMES);
            $this->assertLessThan(5, str_word_count($name), 'event names stay under five words');
        }
    }

    public function test_the_home_offer_line_and_the_service_buttons_carry_the_event(): void
    {
        $home = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertMatchesRegularExpression('#<p class="offer[^"]*">.*?</p>\s*<div class="cta[^"]*"><a class="btn"[^>]*data-sda-event="Contact button click"#s', $home);
        $service = (string) $this->get('http://scuttle.dev/services/hardening')->getContent();
        $this->assertGreaterThanOrEqual(2, substr_count($service, 'data-sda-event="Contact button click"'), 'both "Ask about" buttons of a service page');
    }

    public function test_nothing_is_sent_without_the_key(): void
    {
        config(['scuttle-dev.analytics_key' => '']);
        $html = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringNotContainsString('/_sda/', $html);
        $this->assertStringNotContainsString('data-site=', $html);

        config(['scuttle-dev.analytics_key' => 'testkey']);
        $html = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringContainsString('<script defer data-site="testkey" src="/_sda/s.js"></script>', $html);
    }
}
