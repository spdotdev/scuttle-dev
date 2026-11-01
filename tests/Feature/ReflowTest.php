<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** Reflow at 320 px with enlarged text (WCAG 1.4.4, 1.4.10): the stylesheet rules that keep a page from widening. */
class ReflowTest extends TestCase
{
    public function test_grid_columns_never_size_to_their_content(): void
    {
        $css = (string) file_get_contents(__DIR__.'/../../public/site.css');
        $this->assertSame(0, preg_match('/grid-template-columns:\s*(1fr|1fr 1fr|repeat\(\d,\s*1fr\))\s*[;}]/', $css), 'a bare 1fr column grows to the widest word; write minmax(0,1fr)');
        $this->assertStringContainsString('overflow-wrap:anywhere', $css, 'long words wrap instead of widening the page');
    }
}
