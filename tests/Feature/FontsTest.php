<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Web fonts: swap while loading, preloads that match the stylesheet, no unused file, every glyph on the pages covered. */
class FontsTest extends TestCase
{
    private function css(): string
    {
        return (string) file_get_contents(__DIR__.'/../../public/site.css');
    }

    public function test_every_font_face_swaps_and_every_font_file_is_used(): void
    {
        preg_match_all('/@font-face\{([^}]*)\}/', $this->css(), $faces);
        $this->assertCount(4, $faces[1], 'Geist and Geist Mono, latin and latin-ext');
        $used = [];
        foreach ($faces[1] as $face) {
            $this->assertMatchesRegularExpression('/font-display:(swap|optional)/', $face, 'no invisible text while the font loads');
            $this->assertSame(1, preg_match('/url\(fonts\/([a-z-]+\.woff2)\)/', $face, $m));
            $used[] = $m[1];
            $this->assertFileExists(__DIR__.'/../../public/fonts/'.$m[1]);
        }
        $shipped = array_map('basename', glob(__DIR__.'/../../public/fonts/*.woff2'));
        sort($used);
        sort($shipped);
        $this->assertSame($used, $shipped, 'a font file that no @font-face names is shipped for nothing');
    }

    public function test_the_preloads_are_the_latin_files_the_stylesheet_names_first_for_body_text(): void
    {
        $html = (string) $this->get('http://scuttle.dev/')->getContent();
        preg_match_all('#<link rel="preload" href="[^"]*/fonts/([a-z-]+\.woff2)" as="font" type="font/woff2" crossorigin />#', $html, $m);
        sort($m[1]);
        $this->assertSame(['geist-latin.woff2', 'geist-mono-latin.woff2'], $m[1], 'the two faces the first screen draws with; latin-ext loads only when a page needs it');
        foreach ($m[1] as $file) {
            $this->assertStringContainsString('url(fonts/'.$file.')', $this->css());
        }
    }

    public function test_every_glyph_of_the_pages_is_in_the_font_ranges(): void
    {
        $ranges = [];
        preg_match_all('/font-family:"Geist";[^}]*unicode-range:([^;}]+)/', $this->css(), $faces);
        foreach ($faces[1] as $list) {
            foreach (explode(',', $list) as $r) {
                [$a, $b] = array_pad(explode('-', substr(trim($r), 2)), 2, null);
                $ranges[] = [hexdec($a), hexdec($b ?? $a)];
            }
        }
        $this->assertNotEmpty($ranges);

        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/contact', '/services', '/case-studies', '/news', '/company', '/projects', '/privacy', '/projects/manual', '/projects/manual/docs'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
            }
        }
        foreach (Cases::all() as $c) {
            $paths[] = '/case-studies/'.$c['slug'];
        }
        foreach (Posts::all() as $post) {
            $paths[] = '/news/'.$post['slug'];
        }
        foreach ($paths as $path) {
            $paths[] = Dutch::paired($path) ? '/nl'.($path === '/' ? '' : $path) : null;
        }

        // The arrows U+2190, U+2192 and U+2197 are in the latin files since Geist v1.9.0 (design/make-fonts.sh), so no glyph is allowed outside the ranges.
        foreach (['←', '↑', '→', '↓', '↗'] as $arrow) {
            $in = false;
            foreach ($ranges as [$a, $b]) {
                $in = $in || (mb_ord($arrow) >= $a && mb_ord($arrow) <= $b);
            }
            $this->assertTrue($in, $arrow.' is in the unicode-range of the latin Geist file');
        }
        $fallback = [];
        $outside = [];
        foreach (array_filter($paths) as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->getContent();
            $html = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', fn ($m) => mb_chr(hexdec($m[1])), html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            foreach (array_unique(mb_str_split((string) $html)) as $ch) {
                $cp = mb_ord($ch);
                if ($cp < 127 || $cp === 0xA0 || in_array($ch, $fallback, true)) {
                    continue;
                }
                $in = false;
                foreach ($ranges as [$a, $b]) {
                    $in = $in || ($cp >= $a && $cp <= $b);
                }
                if (! $in) {
                    $outside[$ch] = $path;
                }
            }
        }
        $this->assertSame([], $outside, 'glyphs outside the latin and latin-ext ranges (they fall back to a system font): '.json_encode($outside, JSON_UNESCAPED_UNICODE));
    }

    public function test_the_licence_text_ships_next_to_the_fonts(): void
    {
        $licence = (string) file_get_contents(__DIR__.'/../../public/fonts/LICENSE.txt');
        $this->assertStringContainsString('SIL OPEN FONT LICENSE Version 1.1', $licence);
        $this->assertStringContainsString('Geist Project Authors', $licence);
    }
}
