<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Crawler and download files: media type with charset, an hour of cache at least, honest dates. */
class HeadersTest extends TestCase
{
    /** @return array<string, array{0: string, 1: string}> */
    public static function files(): array
    {
        return [
            'feed' => ['/feed.xml', 'application/atom+xml; charset=utf-8'],
            'sitemap' => ['/sitemap.xml', 'application/xml; charset=utf-8'],
            'robots' => ['/robots.txt', 'text/plain; charset=utf-8'],
            'vcard' => ['/scuttle.vcf', 'text/vcard; charset=utf-8'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('files')]
    public function test_each_file_has_its_media_type_with_charset_and_caches_for_an_hour_at_least(string $path, string $type): void
    {
        $r = $this->get('http://scuttle.dev'.$path)->assertOk();
        $this->assertSame($type, strtolower((string) $r->headers->get('Content-Type')), $path);
        $cc = (string) $r->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cc, $path);
        preg_match('/max-age=(\d+)/', $cc, $m);
        $this->assertGreaterThanOrEqual(3600, (int) ($m[1] ?? 0), $path.' '.$cc);
    }

    public function test_the_news_index_lastmod_is_the_date_of_the_newest_article_and_every_lastmod_is_a_real_date(): void
    {
        $xml = (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent();
        preg_match_all('#<lastmod>([^<]*)</lastmod>#', $xml, $m);
        $this->assertNotEmpty($m[1]);
        foreach ($m[1] as $d) {
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $d);
            $this->assertNotFalse(\DateTime::createFromFormat('Y-m-d', $d), $d);
            $this->assertLessThanOrEqual(date('Y-m-d', strtotime('+1 day')), $d, 'no date in the future');
        }
        $newest = max(array_column(Posts::all(), 'date'));
        $this->assertMatchesRegularExpression('#<loc>https://scuttle\.dev/news</loc>\s*<lastmod>'.$newest.'</lastmod>#', $xml);
    }

    public function test_the_feed_updated_is_the_newest_entry(): void
    {
        $xml = simplexml_load_string((string) $this->get('http://scuttle.dev/feed.xml')->getContent());
        $this->assertNotFalse($xml);
        $newest = 0;
        foreach ($xml->entry as $e) {
            $newest = max($newest, strtotime((string) $e->updated));
        }
        $this->assertSame($newest, strtotime((string) $xml->updated));
    }
}
