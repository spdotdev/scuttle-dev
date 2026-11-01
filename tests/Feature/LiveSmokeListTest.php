<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/** The list the six-hourly live watch reads (scripts/smoke-urls.txt) must stay true: addresses that exist, markers that are on the page. */
class LiveSmokeListTest extends TestCase
{
    /** @return list<array{int, string, string}> */
    private function rows(): array
    {
        $rows = [];
        foreach (file(__DIR__.'/../../scripts/smoke-urls.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with($line, '#')) {
                continue;
            }
            $parts = explode("\t", $line);
            $this->assertCount(3, $parts, "three tab separated fields: $line");
            $rows[] = [(int) $parts[0], $parts[1], $parts[2]];
        }

        return $rows;
    }

    public function test_every_address_is_in_the_sitemap_or_a_crawler_file_and_every_marker_is_on_the_page(): void
    {
        $sitemap = (string) $this->get('http://scuttle.dev/sitemap.xml')->assertOk()->getContent();
        preg_match_all('#<loc>https://scuttle\.dev([^<]*)</loc>#', $sitemap, $m);
        $inSitemap = array_map(fn ($p) => rtrim($p, '/') ?: '/', $m[1]);
        $fixed = ['/feed.xml', '/sitemap.xml', '/robots.txt', '/scuttle.vcf'];

        $rows = $this->rows();
        $this->assertGreaterThanOrEqual(12, count($rows));
        $has404 = false;
        $hasDutch = false;
        $external = [];
        foreach ($rows as [$status, $path, $marker]) {
            if (str_starts_with($path, 'https://')) {
                // an address outside this site: fetched live as written, so only its shape is checked here
                $this->assertSame(200, $status, "$path");
                $this->assertNotSame('', $marker);
                $external[] = $path;

                continue;
            }
            $key = rtrim($path, '/') ?: '/';
            if ($status === 404) {
                $has404 = true;
                $this->assertNotContains($key, $inSitemap, "$path must not exist");
            } else {
                $this->assertTrue(in_array($key, $inSitemap, true) || in_array($key, $fixed, true), "$path is neither in the sitemap nor a crawler file");
            }
            $hasDutch = $hasDutch || str_starts_with($path, '/nl');
            $response = $this->get('http://scuttle.dev'.$path);
            $this->assertSame($status, $response->getStatusCode(), "$path status");
            $body = $response->getContent() ?: ($response->baseResponse instanceof BinaryFileResponse ? (string) file_get_contents($response->baseResponse->getFile()->getPathname()) : '');
            $this->assertStringContainsString($marker, $body, "marker of $path is not on the page");
        }
        $this->assertContains(config('scuttle-dev.booking_url'), $external, 'the booking page every call button leads to is watched');
        $this->assertTrue($has404, 'one address must answer 404');
        $this->assertTrue($hasDutch, 'the Dutch twins are watched');
    }
}
