<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * The browser switch on pages without a Dutch twin changes more than the visible text: aria-label, title,
 * the legal document links and the lang attribute. The map and the data attributes must be in the HTML.
 */
class LanguageSwitchTest extends TestCase
{
    private const PAGES = ['/', '/pricing', '/contact', '/company', '/news', '/news/an-mcp-server-in-front-of-your-api', '/projects', '/projects/manual', '/projects/manual/docs', '/health-plan/sample', '/php-check', '/services/ai-integration'];

    public function test_a_page_without_a_twin_carries_the_dutch_attribute_map_and_the_dutch_nda_link(): void
    {
        foreach (['/news', '/news/an-mcp-server-in-front-of-your-api', '/projects', '/projects/manual/docs'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('window.SCUTTLE_ATTR_NL = {', $html, $path);
            $this->assertStringContainsString('"Main navigation":"Hoofdmenu"', $html, $path);
            $this->assertMatchesRegularExpression('#<a href="[^"]*NDA[^"]*" data-href-nl="[^"]*GEHEIMHOUDINGSOVEREENKOMST[^"]*">NDA</a>#', $html, $path);
        }
    }

    public function test_the_attribute_map_is_not_read_as_page_strings(): void
    {
        $html = (string) $this->get('http://scuttle.dev/news')->assertOk()->getContent();
        $this->assertArrayNotHasKey('Main navigation', Dutch::strings($html));
        $this->assertArrayNotHasKey('Menu', Dutch::strings($html));
    }

    public function test_the_dutch_twins_carry_dutch_labels_and_the_dutch_nda(): void
    {
        $html = (string) $this->get('http://scuttle.dev/nl/pricing')->assertOk()->getContent();
        $this->assertStringContainsString('aria-label="Hoofdmenu"', $html);
        $this->assertStringContainsString('aria-label="Taal"', $html);
        $this->assertStringContainsString('title="Donker"', $html);
        $this->assertStringNotContainsString('aria-label="Main navigation"', $html);
        $this->assertStringContainsString('GEHEIMHOUDINGSOVEREENKOMST', $html);
    }

    public function test_every_label_and_title_on_the_pages_has_a_dutch_text_unless_it_is_made_from_data(): void
    {
        $missing = [];
        foreach (self::PAGES as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('#\s(?:aria-label|title)="([^"]*)"#', $html, $m);
            foreach ($m[1] as $v) {
                $v = html_entity_decode($v, ENT_QUOTES);
                if ($v === '' || str_contains($v, '{{') || preg_match('#^(Case \d+|Link to the section |[\dW-]+$)#', $v)) {
                    continue;
                }
                if (! isset(Dutch::ATTRIBUTES[$v])) {
                    $missing[$path.' | '.$v] = true;
                }
            }
        }
        $this->assertSame([], array_keys($missing), 'Add the Dutch text to Dutch::ATTRIBUTES.');
    }
}
