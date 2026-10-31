<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** Scripts off, keyboard, reduced motion, print and the size of what a page loads. */
class ModesTest extends TestCase
{
    private function css(): string
    {
        return (string) file_get_contents(__DIR__.'/../../public/site.css');
    }

    public function test_content_is_visible_without_scripts(): void
    {
        $css = $this->css();
        // The scroll reveal hides content only once the head script has set the js class.
        $this->assertStringNotContainsString("\n.rv{opacity:0", $css);
        $this->assertStringContainsString('.js .rv{opacity:0', $css);
        $this->assertStringContainsString('html:not(.js) .demo-wrap', $css);

        $html = (string) $this->get('http://scuttle.dev/news')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('#<head>.*classList\.add\(\'js\'\).*</head>#s', $html);
        // A phone without scripts gets a plain Services link next to the other menu links.
        $this->assertMatchesRegularExpression('#<noscript><a class="ns-services" href="https?://scuttle\.dev/services"#', $html);
    }

    public function test_every_keyboard_stop_gets_a_ring_and_reduced_motion_stops_the_smooth_scroll(): void
    {
        $css = $this->css();
        $this->assertStringContainsString(':focus-visible{outline:2px solid var(--acc2)', $css);
        $this->assertStringContainsString('.loop .node:focus-visible{outline:2px solid', $css);
        $this->assertMatchesRegularExpression('/prefers-reduced-motion:reduce\)\{html\{scroll-behavior:auto\}/', $css);
    }

    public function test_print_has_light_tokens_no_menu_and_one_line_with_address_and_page_url(): void
    {
        $css = $this->css();
        $this->assertStringContainsString('@media print{', $css);
        $this->assertMatchesRegularExpression('/@media print\{.*nav\.top[^{]*\.btn[^{]*\{display:none!important\}/s', $css);
        $this->assertMatchesRegularExpression('/@media print\{.*:root:root:root\{--bg:#fff/s', $css);

        foreach (['/pricing' => 'scuttle.dev/pricing', '/nl/pricing' => 'scuttle.dev/nl/pricing', '/' => 'scuttle.dev'] as $path => $shown) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $this->assertMatchesRegularExpression('#<p class="print-id" aria-hidden="true"><b>Scuttle Development</b> · Dirigentplein 144, 5642 RK Eindhoven · '.preg_quote($shown, '#').'</p>#', $html, $path);
        }
    }

    public function test_no_image_in_the_package_is_over_150_kb(): void
    {
        $big = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__.'/../../public', \FilesystemIterator::SKIP_DOTS)) as $f) {
            if (preg_match('/\.(png|jpe?g|gif|webp|svg)$/i', $f->getFilename()) && $f->getSize() > 150 * 1024) {
                $big[] = $f->getPathname().' '.$f->getSize();
            }
        }
        $this->assertSame([], $big);
    }

    public function test_the_modals_move_focus_in_and_back(): void
    {
        $js = (string) file_get_contents(__DIR__.'/../../public/site.js');
        $this->assertStringContainsString('if (x) x.focus();', $js);
        $this->assertStringContainsString('openBtn.focus()', $js);
        $this->assertStringContainsString("e.key === 'Tab'", $js);
    }
}
