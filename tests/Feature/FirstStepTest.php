<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** The index pages carry one offer line and one button right under the lead, so the button is on the first screen of a phone. */
class FirstStepTest extends TestCase
{
    private function lead(string $html): string
    {
        $this->assertSame(1, preg_match('#<p class="lead"[^>]*>(.*?)</p>#s', $html, $m));

        return trim(html_entity_decode(strip_tags($m[1])));
    }

    public function test_six_index_pages_put_the_first_button_straight_under_the_lead(): void
    {
        $health = ['/services', '/company', '/case-studies', '/health-check/sample'];
        foreach ($health as $path) {
            foreach (['', '/nl'] as $prefix) {
                $html = (string) $this->get('http://scuttle.dev'.$prefix.$path)->assertOk()->getContent();
                $this->assertMatchesRegularExpression('#</p>\s*<p class="offer">.*?</p>\s*<div class="cta"><a class="btn" href="[^"]*"#s', $html, $prefix.$path);
                $this->assertStringContainsString($prefix === '' ? 'Start with a Health Check.' : 'Begin met een Health Check.', $html, $prefix.$path);
                $this->assertStringContainsString($prefix === '' ? 'Book a call about a Health Check' : 'Plan een gesprek over een Health Check', $html, $prefix.$path);
            }
        }
        // Both buttons of a page carry the same words, so they lead to the same place (a screen reader lists them side by side).
        foreach (['/news' => 'Something%20else', '/projects' => 'AI%20Integration'] as $path => $topic) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertStringContainsString('Tell me what you are running.', $html, $path);
            $this->assertStringContainsString('?about='.$topic, $html, $path);
            $this->assertStringNotContainsString('mailto:', $html, $path);
            // the Dutch strings travel with the page for the EN/NL switch
            $this->assertStringContainsString('Vertel me wat je draait.', $html, $path);
        }
    }

    public function test_the_offer_is_not_shown_twice_and_leads_stay_short(): void
    {
        $projects = (string) $this->get('http://scuttle.dev/projects')->assertOk()->getContent();
        $this->assertSame(1, substr_count($projects, 'class="offer"'));
        $this->assertLessThanOrEqual(35, count(preg_split('/\s+/', $this->lead($projects))));

        foreach (['/services/ai-integration', '/nl/services/ai-integration'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertLessThanOrEqual(35, count(preg_split('/\s+/', $this->lead($html))), $path);
        }
    }
}
