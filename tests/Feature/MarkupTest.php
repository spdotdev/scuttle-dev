<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Static checks on the rendered markup of every page, English and Dutch. */
class MarkupTest extends TestCase
{
    /** @return list<string> */
    private function paths(): array
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services', '/contact', '/company', '/privacy', '/case-studies', '/news', '/projects', '/projects/manual', '/projects/manual/docs', '/case-studies/phalcon-to-laravel'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = '/services/'.$p['slug'];
            }
        }
        foreach ($paths as $p) {
            if (Dutch::paired($p)) {
                $paths[] = '/nl'.($p === '/' ? '' : $p);
            }
        }

        return $paths;
    }

    public function test_every_image_has_alt_width_and_height(): void
    {
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('/<img\b[^>]*>/i', $html, $m);
            foreach ($m[0] as $img) {
                $this->assertMatchesRegularExpression('/\salt="/', $img, $path.' '.$img);
                $this->assertMatchesRegularExpression('/\swidth="\d+"/', $img, $path.' '.$img);
                $this->assertMatchesRegularExpression('/\sheight="\d+"/', $img, $path.' '.$img);
            }
        }
    }

    public function test_every_link_that_opens_a_new_tab_or_leaves_the_site_has_noopener(): void
    {
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('/<a\b[^>]*>/i', $html, $m);
            foreach ($m[0] as $a) {
                if (str_contains($a, 'target="_blank"')) {
                    $this->assertStringContainsString('noopener', $a, $path.' '.$a);
                }
            }
        }
    }

    public function test_no_page_has_a_duplicate_id(): void
    {
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $html = preg_replace('#<script\b.*?</script>#s', '', $html) ?? $html;
            preg_match_all('/\sid="([^"]+)"/', $html, $m);
            $dupes = array_keys(array_filter(array_count_values($m[1]), fn (int $n): bool => $n > 1));
            $this->assertSame([], $dupes, $path.' has duplicate ids');
        }
    }

    public function test_every_page_has_one_h1_and_no_skipped_heading_level(): void
    {
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $html = preg_replace('#<script\b.*?</script>#s', '', $html) ?? $html;
            preg_match_all('/<h([1-6])\b/i', $html, $m);
            $levels = array_map('intval', $m[1]);
            $this->assertSame(1, count(array_filter($levels, fn (int $l): bool => $l === 1)), $path.' needs exactly one h1');
            $prev = 0;
            foreach ($levels as $l) {
                $this->assertLessThanOrEqual($prev + 1, $l, $path.': heading h'.$l.' follows h'.$prev);
                $prev = $l;
            }
        }
    }

    public function test_every_footer_link_answers_200_or_301(): void
    {
        foreach (['/', '/nl'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertSame(1, preg_match('#<footer\b.*?</footer>#s', $html, $f), $path.' has a footer');
            preg_match_all('/<a\b[^>]*\shref="([^"]+)"/', $f[0], $m);
            $this->assertNotEmpty($m[1]);
            foreach (array_unique($m[1]) as $href) {
                if (! str_starts_with($href, 'https://scuttle.dev') && ! str_starts_with($href, '/')) {
                    continue;
                }
                $this->assertContains($this->get(str_replace('https://scuttle.dev', 'http://scuttle.dev', $href))->getStatusCode(), [200, 301], $path.' footer link '.$href);
            }
        }
    }

    public function test_every_page_ends_with_a_next_step_to_the_contact_form_or_the_booking_page(): void
    {
        foreach ($this->paths() as $path) {
            if ($path === '/contact' || $path === '/nl/contact') {
                continue; // the form itself
            }
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $html = preg_replace('#<script\b.*?</script>#s', '', explode('<footer', $html)[0]) ?? $html;
            $this->assertMatchesRegularExpression('#href="[^"]*(/contact|plan\.scuttle\.dev/book)#', substr($html, -2500), $path.' ends without a next step');
        }
    }

    public function test_no_search_snippet_is_cut_in_the_middle_of_a_sentence_in_either_language(): void
    {
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match('/name="description" content="(.*?)"/s', $html, $d);
            $desc = html_entity_decode($d[1]);
            $this->assertStringNotContainsString('…', $desc, $path.' description is cut: '.$desc);
            $this->assertGreaterThanOrEqual(90, mb_strlen($desc), $path.' description is too thin to say what the page gives');
        }
    }
}
