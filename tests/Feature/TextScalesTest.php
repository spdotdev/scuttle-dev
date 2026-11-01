<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * Text sizes are in rem, so the text size a visitor sets in the browser scales the site.
 */
class TextScalesTest extends TestCase
{
    public function test_no_font_size_or_line_height_is_written_in_px(): void
    {
        $css = (string) file_get_contents(dirname(__DIR__, 2).'/public/site.css');
        preg_match_all('/(?<![\w-])(?:font-size|line-height|font):[^;}]*\d(?:px)\b[^;}]*/', $css, $m);
        $this->assertSame([], $m[0], 'Write text sizes in rem on a 16 px root (13 px is 0.8125rem).');
    }

    public function test_the_page_does_not_set_a_root_font_size_in_px(): void
    {
        $css = (string) file_get_contents(dirname(__DIR__, 2).'/public/site.css');
        $this->assertDoesNotMatchRegularExpression('/(?:^|[,}\s])(?:html|:root)\s*\{[^}]*font-size:\s*\d+px/', $css);
    }
}
