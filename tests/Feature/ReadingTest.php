<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Reading experience of the long pages: a reading time under the date, a line length of 55 to 80 characters, text of 17 px or more. */
class ReadingTest extends TestCase
{
    /** @return array{summary: string, body: list<string>} */
    private function words(int $n): array
    {
        return ['summary' => '', 'body' => [trim(str_repeat('word ', $n))]];
    }

    public function test_reading_time_is_the_word_count_at_220_a_minute_rounded_up(): void
    {
        $this->assertSame(220, Posts::WORDS_PER_MINUTE);
        $this->assertSame(1, Posts::readingMinutes($this->words(0)));
        $this->assertSame(1, Posts::readingMinutes($this->words(220)));
        $this->assertSame(2, Posts::readingMinutes($this->words(221)));
        $this->assertSame(3, Posts::readingMinutes($this->words(660)));
        $this->assertSame(2, Posts::readingMinutes(['summary' => str_repeat('a ', 100), 'body' => [str_repeat('b ', 121)]]), 'the lead counts too');
        $this->assertSame(1, Posts::readingMinutes(['summary' => '<b>one</b> two', 'body' => ['## head', '- item']]), 'markup characters are not words');
    }

    public function test_every_article_shows_its_reading_time_in_both_languages(): void
    {
        $this->assertNotEmpty(Posts::all());
        foreach (Posts::all() as $post) {
            $mins = Posts::readingMinutes($post);
            $html = (string) $this->get('http://scuttle.dev/news/'.$post['slug'])->assertOk()->getContent();
            $this->assertStringContainsString('<span data-i="blp_read">'.$mins.' min read</span>', $html);
            $this->assertLessThan(strpos($html, '<h1'), strpos($html, 'blp_read'), 'the line sits above the title with the date');
            $this->assertStringContainsString('"blp_read":"'.$mins.' min lezen"', $html, 'Dutch string for the reading time');
        }
    }

    public function test_long_text_is_17_px_or_more_and_no_wider_than_80_characters(): void
    {
        $css = (string) file_get_contents(__DIR__.'/../../public/site.css');
        // Geist averages 0.457 em per character (measured with Playwright on the rendered pages): 34 em is about 74 characters.
        $this->assertSame(1, preg_match('/\.page p,\.page li\{[^}]*font-size:(\d+(?:\.\d+)?)rem;line-height:(1\.[5-7])[^}]*max-width:(\d+)em/', $css, $m), 'the body text rule of .page');
        $this->assertGreaterThanOrEqual(17, (float) $m[1] * 16);
        $this->assertLessThanOrEqual(36, (int) $m[3], 'at most about 80 characters a line');
        $this->assertSame(1, preg_match('/\.docs-main p,\.docs-main li\{[^}]*font-size:(\d+(?:\.\d+)?)rem[^}]*max-width:(\d+)em/', $css, $d), 'the body text rule of the docs');
        $this->assertGreaterThanOrEqual(17, (float) $d[1] * 16);
        $this->assertLessThanOrEqual(36, (int) $d[2]);
    }
}
