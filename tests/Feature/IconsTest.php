<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** Every icon the head and the web manifest name exists at the size it states, and the colours agree with the site. */
class IconsTest extends TestCase
{
    private function publicFile(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $this->assertStringStartsWith('/vendor/scuttle/', $path, $url);

        return __DIR__.'/../../public/'.substr($path, strlen('/vendor/scuttle/'));
    }

    /** @return array{0:int,1:int} */
    private function pngSize(string $file): array
    {
        $this->assertFileExists($file);
        $h = (string) file_get_contents($file, false, null, 0, 24);
        $this->assertSame("\x89PNG\r\n\x1a\n", substr($h, 0, 8), $file.' is a PNG');

        return [(int) unpack('N', substr($h, 16, 4))[1], (int) unpack('N', substr($h, 20, 4))[1]];
    }

    public function test_head_icons_exist_at_the_stated_sizes_and_the_touch_icon_is_180(): void
    {
        foreach (['/', '/nl'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->getContent();
            preg_match_all('#<link rel="(icon|apple-touch-icon)"([^>]*)>#', $html, $m, PREG_SET_ORDER);
            $this->assertGreaterThanOrEqual(4, count($m), $path);
            $touch = 0;
            foreach ($m as [, $rel, $attrs]) {
                preg_match('#href="([^"]+)"#', $attrs, $href);
                $file = $this->publicFile($href[1]);
                if (preg_match('#sizes="(\d+)x(\d+)"#', $attrs, $s)) {
                    $this->assertSame([(int) $s[1], (int) $s[2]], $this->pngSize($file), $href[1]);
                } else {
                    $this->assertFileExists($file);
                }
                if ($rel === 'apple-touch-icon') {
                    $touch++;
                    $this->assertSame([180, 180], $this->pngSize($file));
                }
            }
            $this->assertSame(1, $touch, $path);
        }
    }

    public function test_the_manifest_icons_exist_at_the_stated_sizes_and_its_colours_are_the_site_background(): void
    {
        $manifest = json_decode((string) file_get_contents(__DIR__.'/../../public/site.webmanifest'), true);
        $this->assertNotEmpty($manifest['icons']);
        $sizes = [];
        foreach ($manifest['icons'] as $icon) {
            [$w, $h] = array_map('intval', explode('x', $icon['sizes']));
            $this->assertSame([$w, $h], $this->pngSize($this->publicFile($icon['src'])), $icon['src']);
            $sizes[] = $w;
        }
        $this->assertContains(192, $sizes);
        $this->assertContains(512, $sizes);

        $css = (string) file_get_contents(__DIR__.'/../../public/site.css');
        preg_match('~:root\{--bg:(#[0-9a-fA-F]{6});~', $css, $bg);
        $this->assertSame(strtolower($bg[1]), strtolower($manifest['background_color']));
        $this->assertSame(strtolower($bg[1]), strtolower($manifest['theme_color']));
        $html = (string) $this->get('http://scuttle.dev/')->getContent();
        $this->assertStringContainsString('<meta name="theme-color" content="'.$bg[1].'"', $html);
    }

    public function test_the_svg_icons_hold_no_script_or_event_handler(): void
    {
        foreach (glob(__DIR__.'/../../public/*.svg') ?: [] as $file) {
            $svg = (string) file_get_contents($file);
            $this->assertStringNotContainsString('<script', strtolower($svg), basename($file));
            $this->assertDoesNotMatchRegularExpression('/\son[a-z]+\s*=|javascript:|<foreignObject/i', $svg, basename($file));
        }
    }
}
