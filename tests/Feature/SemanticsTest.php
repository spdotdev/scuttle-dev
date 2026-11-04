<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** What a screen reader hears: landmarks, link names, picture descriptions, form messages and the language of the page. */
class SemanticsTest extends TestCase
{
    /** @return list<string> */
    private function paths(): array
    {
        $paths = ['/', '/pricing', '/health-plan', '/health-plan/sample', '/php-check', '/nis2', '/services', '/contact', '/company', '/privacy', '/case-studies', '/news', '/projects', '/projects/manual', '/projects/manual/docs', '/case-studies/phalcon-to-laravel', '/case-studies/api-meets-ai'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = '/services/'.$p['slug'];
            }
        }
        foreach (array_keys(Posts::all()) as $slug) {
            $paths[] = '/news/'.$slug;
        }
        foreach ($paths as $p) {
            if (Dutch::paired($p)) {
                $paths[] = '/nl'.($p === '/' ? '' : $p);
            }
        }

        return $paths;
    }

    /** @return array{0: DOMDocument, 1: DOMXPath} */
    private function dom(string $path): array
    {
        // https, as production serves it: the Dutch twin only localizes https addresses
        $html = (string) $this->get('https://scuttle.dev'.$path)->assertOk()->getContent();
        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="utf-8"?>'.$html);

        return [$dom, new DOMXPath($dom)];
    }

    private function linkName(DOMElement $a): string
    {
        $label = trim($a->getAttribute('aria-label'));
        if ($a->hasAttribute('aria-labelledby') && $a->ownerDocument !== null) {
            $parts = [];
            foreach (preg_split('/\s+/', trim($a->getAttribute('aria-labelledby'))) ?: [] as $id) {
                $el = $a->ownerDocument->getElementById($id);
                $parts[] = $el === null ? '' : trim((string) preg_replace('/\s+/u', ' ', $el->textContent));
            }
            $label = trim(implode(' ', $parts));
        }

        return mb_strtolower($label !== '' ? $label : (string) preg_replace('/\s+/u', ' ', trim($a->textContent)));
    }

    public function test_links_with_the_same_name_lead_to_the_same_place_or_carry_a_label_that_tells_them_apart(): void
    {
        $bad = [];
        foreach ($this->paths() as $path) {
            [, $x] = $this->dom($path);
            $seen = [];
            foreach ($x->query('//a[@href]') as $a) {
                /** @var DOMElement $a */
                $name = $this->linkName($a);
                if ($name === '') {
                    $bad[] = $path.': a link without a name: '.$a->getAttribute('href');

                    continue;
                }
                $seen[$name][(string) preg_replace('~^https?://scuttle\.dev~', '', $a->getAttribute('href'))] = true;
            }
            foreach ($seen as $name => $targets) {
                if (count($targets) > 1) {
                    $bad[] = $path.': "'.$name.'" names '.count($targets).' destinations: '.implode(' ', array_keys($targets));
                }
            }
        }
        $this->assertSame([], $bad, implode("\n", $bad));
    }

    public function test_images_say_what_they_show_and_decorative_ones_are_hidden_from_readers(): void
    {
        foreach ($this->paths() as $path) {
            [, $x] = $this->dom($path);
            foreach ($x->query('//img') as $img) {
                /** @var DOMElement $img */
                $alt = trim($img->getAttribute('alt'));
                $src = $img->getAttribute('src');
                if ($alt === '') {
                    $this->assertSame('presentation', $img->getAttribute('role') ?: 'presentation', $path.' '.$src);

                    continue;
                }
                $this->assertDoesNotMatchRegularExpression('/\.(png|jpe?g|svg|webp|gif)$/i', $alt, $path.' alt is a file name: '.$alt);
                $this->assertDoesNotMatchRegularExpression('/^(image|img|picture|photo|foto|afbeelding|plaatje)$/i', $alt, $path.' alt says nothing: '.$alt);
                $this->assertGreaterThanOrEqual(8, mb_strlen($alt), $path.' alt is too short to describe a picture: '.$alt);
            }
        }
    }

    public function test_every_page_has_one_main_and_every_navigation_has_its_own_label(): void
    {
        foreach ($this->paths() as $path) {
            [, $x] = $this->dom($path);
            $this->assertSame(1, $x->query('//main')->length, $path.': one main');
            $labels = [];
            foreach ($x->query('//nav') as $nav) {
                /** @var DOMElement $nav */
                $label = trim($nav->getAttribute('aria-label'));
                $this->assertNotSame('', $label, $path.': a nav without aria-label');
                $labels[] = $label;
            }
            $this->assertGreaterThanOrEqual(1, count($labels), $path);
            $this->assertSame(count($labels), count(array_unique($labels)), $path.': navigation labels are unique: '.implode(' | ', $labels));
        }
    }

    public function test_form_messages_are_announced_and_the_language_attribute_follows_the_page(): void
    {
        foreach (['/contact', '/nl/contact'] as $path) {
            [, $x] = $this->dom($path);
            foreach ($x->query('//*[contains(concat(" ", normalize-space(@class), " "), " sent ") or contains(concat(" ", normalize-space(@class), " "), " err ")]') as $el) {
                /** @var DOMElement $el */
                $this->assertContains($el->getAttribute('role'), ['status', 'alert'], $path.' '.$el->getAttribute('class'));
            }
        }
        $this->assertSame(1, $this->dom('/contact')[1]->query('//html[@lang="en"]')->length);
        // The English terms stay English on a Dutch page, so a screen reader must switch voice for that link.
        $terms = $this->dom('/nl/pricing')[1]->query('//a[contains(@href, "TERMS_AND_CONDITIONS")]');
        $this->assertGreaterThan(0, $terms->length);
        foreach ($terms as $a) {
            /** @var DOMElement $a */
            $this->assertSame('en', $a->getAttribute('lang'), 'English document link on a Dutch page: '.trim($a->textContent));
        }
        foreach ($this->paths() as $path) {
            if (str_starts_with($path, '/nl')) {
                $this->assertSame(1, $this->dom($path)[1]->query('//html[@lang="nl"]')->length, $path.': lang="nl" on the Dutch twin');
            }
        }
    }
}
