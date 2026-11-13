<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Archive;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * The news archive has an end. On 2026-11-13 a crawler followed the calendar's "next month" and week links of
 * /news through every month from 2010 to 2036 (3242 different addresses in ten minutes, each a full page), which
 * kept the one-core server busy. Months and periods without an article are not linked and do not exist.
 */
class ArchiveBoundsTest extends TestCase
{
    private const TREE = ['2026' => ['11' => ['10' => 1, '03' => 2], '09' => ['28' => 1]]];

    public function test_bounds_are_the_first_and_last_month_with_an_article(): void
    {
        $this->assertSame(['2026-09', '2026-11'], Archive::bounds(self::TREE));
        $this->assertSame([date('Y-m'), date('Y-m')], Archive::bounds([]));
    }

    public function test_a_month_is_in_the_archive_only_between_the_bounds(): void
    {
        $this->assertTrue(Archive::hasMonth(self::TREE, '2026-10'));
        $this->assertTrue(Archive::hasMonth(self::TREE, '2026-09'));
        $this->assertFalse(Archive::hasMonth(self::TREE, '2026-08'));
        $this->assertFalse(Archive::hasMonth(self::TREE, '2036-03'));
        $this->assertFalse(Archive::hasMonth(self::TREE, '2026-13'));
        $this->assertFalse(Archive::hasMonth(self::TREE, 'nonsense'));
    }

    public function test_a_period_exists_only_when_it_has_an_article(): void
    {
        $posts = [['date' => '2026-11-10'], ['date' => '2026-09-28']];
        foreach (['2026', '2026-11', '2026-11-10', '2026-W46', '2026-W40'] as $period) {
            $this->assertTrue(Archive::hasPeriod($posts, $period), $period);
        }
        foreach (['2010-W16', '2036', '2026-10', '2026-11-11', '2026-W45', 'x'] as $period) {
            $this->assertFalse(Archive::hasPeriod($posts, $period), $period);
        }
    }

    public function test_the_calendar_marks_only_weeks_that_have_an_article(): void
    {
        $rows = Archive::calendar(self::TREE, '2026-11');
        $with = array_column(array_filter($rows, fn (array $row): bool => $row['posts'] > 0), 'week');
        $this->assertSame(['2026-W45', '2026-W46'], array_values($with));
    }

    public function test_the_news_page_links_no_month_or_week_outside_the_archive(): void
    {
        $html = (string) $this->get('http://scuttle.dev/news')->assertOk()->getContent();
        $tree = Archive::tree(Posts::all());
        preg_match_all('~href="[^"]*/news\?([^"]*)"~', $html, $found);
        $this->assertNotEmpty($found[1]);
        foreach ($found[1] as $query) {
            parse_str(html_entity_decode($query), $args);
            if (isset($args['m'])) {
                $this->assertTrue(Archive::hasMonth($tree, (string) $args['m']), 'month link '.$query);
            }
            if (isset($args['p'])) {
                $this->assertTrue(Archive::hasPeriod(Posts::all(), (string) $args['p']), 'period link '.$query);
            }
        }
    }

    public function test_a_month_or_period_without_articles_is_not_found(): void
    {
        $this->get('http://scuttle.dev/news?m=2036-03&p=2010-W16')->assertNotFound();
        $this->get('http://scuttle.dev/news?m=2036-03')->assertNotFound();
        $this->get('http://scuttle.dev/news?p=2010-W16')->assertNotFound();
        $this->get('http://scuttle.dev/news?p=1999')->assertNotFound();
    }

    public function test_a_filtered_page_still_works_and_asks_not_to_be_indexed(): void
    {
        $post = array_values(Posts::all())[0];
        $month = substr($post['date'], 0, 7);
        $this->get('http://scuttle.dev/news?p='.$month)->assertOk()->assertSee('<meta name="robots" content="noindex, follow"', false);
        $this->get('http://scuttle.dev/news?m='.$month)->assertOk()->assertSee('<meta name="robots" content="noindex, follow"', false);
        $this->get('http://scuttle.dev/news')->assertOk()->assertDontSee('noindex', false);
    }

    public function test_robots_keeps_crawlers_out_of_the_filtered_archive(): void
    {
        $robots = (string) $this->get('http://scuttle.dev/robots.txt')->assertOk()->getContent();
        foreach (['Disallow: /news?m=', 'Disallow: /news?p='] as $line) {
            $this->assertStringContainsString($line, $robots);
        }
        $this->assertStringContainsString("User-agent: *\nAllow: /", $robots);
    }
}
