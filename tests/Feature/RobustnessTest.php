<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Odd requests must end in the site's own error page or a normal page, never a server error. */
class RobustnessTest extends TestCase
{
    /** @return array<string, array{0: string}> */
    public static function missing(): array
    {
        return [['/nl/does-not-exist'], ['/services/does-not-exist'], ['/nl/services/does-not-exist'], ['/case-studies/nope'], ['/nl/case-studies/nope'], ['/news/nope'], ['/projects/nope'], ['/projects/nope/docs'], ['/services/UPPER'], ['/legal/other.pdf'], ['/a/b/c/d/e/f'], ['/%00'], ['/..%2f..%2fetc%2fpasswd']];
    }

    #[DataProvider('missing')]
    public function test_unknown_addresses_answer_404_with_the_sites_own_page(string $path): void
    {
        $r = $this->get('http://scuttle.dev'.$path);
        $this->assertSame(404, $r->getStatusCode(), $path);
        $this->assertStringContainsString('<html', (string) $r->getContent());
        $this->assertStringContainsString('noindex', (string) $r->getContent());
    }

    public function test_head_requests_answer_like_get_without_a_body(): void
    {
        foreach (['/', '/contact', '/nl', '/pricing', '/robots.txt', '/sitemap.xml'] as $path) {
            $r = $this->call('HEAD', 'http://scuttle.dev'.$path);
            $this->assertSame(200, $r->getStatusCode(), $path);
            $this->assertSame('', (string) $r->getContent(), $path);
        }
    }

    public function test_a_post_to_a_read_only_route_is_405_not_500(): void
    {
        foreach (['/', '/pricing', '/nl', '/nl/contact', '/services/hardening', '/sitemap.xml'] as $path) {
            $this->assertContains($this->post('http://scuttle.dev'.$path)->getStatusCode(), [404, 405], $path);
        }
    }

    public function test_a_very_long_query_string_does_not_break_a_page(): void
    {
        $q = '?'.http_build_query(['about' => str_repeat('x', 6000), 'utm' => str_repeat('y', 6000)]);
        foreach (['/', '/contact', '/pricing', '/nl/contact'] as $path) {
            $this->assertSame(200, $this->get('http://scuttle.dev'.$path.$q)->getStatusCode(), $path);
        }
    }

    public function test_a_query_array_where_text_is_expected_does_not_break_the_contact_page(): void
    {
        $this->get('http://scuttle.dev/contact?about[]=a&about[x]=b')->assertOk();
        $this->get('http://scuttle.dev/nl/contact?about[]=a')->assertOk();
    }

    public function test_the_404_page_of_a_dutch_address_is_not_a_blank_page(): void
    {
        $this->get('http://scuttle.dev/nl/does-not-exist')->assertStatus(404)->assertSee('404', false);
    }

    /** @return list<object> */
    private function oddArticles(): array
    {
        $row = fn (array $o): object => (object) ($o + ['slug' => 'x', 'title' => 'T', 'summary' => 'S', 'body' => 'Body.', 'tags' => [], 'sells' => '', 'title_nl' => null, 'summary_nl' => null, 'published_at' => '2026-10-30 10:00:00']);

        return [
            $row(['slug' => 'html-title', 'title' => '</title><script>alert(1)</script> & "quotes" \'single\'', 'summary' => '</script><b>bold</b> "q"', 'body' => "## <img src=x onerror=alert(1)>\n<script>alert(2)</script>\n- <b>item</b>"]),
            $row(['slug' => 'long-title', 'title' => str_repeat('A very long title without end ', 40), 'summary' => str_repeat('Long summary. ', 80)]),
            $row(['slug' => 'empty-body', 'body' => '', 'summary' => '', 'title_nl' => '', 'summary_nl' => '']),
            $row(['slug' => 'bad-sells', 'sells' => 'no-such-service', 'tags' => ['<i>tag</i>']]),
            $row(['slug' => 'no-date', 'published_at' => null]),
        ];
    }

    public function test_cms_articles_with_odd_content_never_break_the_news_pages(): void
    {
        Schema::create('cms_articles', function ($t): void {
            $t->id();
        });
        FakeCmsArticle::$rows = $this->oddArticles();
        $this->assertTrue(class_exists('Spdotdev\\SdCms\\Models\\CmsArticle'));

        foreach (['/news', '/sitemap.xml'] as $path) {
            $r = $this->get('http://scuttle.dev'.$path);
            $this->assertSame(200, $r->getStatusCode(), $path);
        }
        $list = (string) $this->get('http://scuttle.dev/news')->getContent();
        $this->assertStringNotContainsString('<script>alert', $list, 'a title never closes a tag in the list');
        $this->assertSame(1, substr_count($list, '<h1'));

        foreach (['html-title', 'long-title', 'empty-body', 'bad-sells', 'no-date'] as $slug) {
            $this->withoutExceptionHandling();
            $r = $this->get('http://scuttle.dev/news/'.$slug);
            $this->assertSame(200, $r->getStatusCode(), $slug);
            $html = (string) $r->getContent();
            $this->assertStringNotContainsString('<script>alert', $html, $slug);
            $this->assertStringNotContainsString('<img src=x', $html, $slug);
            $this->assertSame(1, substr_count($html, '<h1'), $slug);
            foreach ($this->jsonLd($html) as $json) {
                $this->assertIsArray(json_decode($json, true), $slug.': JSON-LD parses');
            }
        }
    }

    /** @return list<string> */
    private function jsonLd(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);

        return $m[1];
    }
}

class FakeCmsArticle
{
    /** @var list<object> */
    public static array $rows = [];

    /** @return Collection<int, object> */
    public static function published(string $site)
    {
        return new class
        {
            public function get()
            {
                return collect(FakeCmsArticle::$rows);
            }
        };
    }
}

class_alias(FakeCmsArticle::class, 'Spdotdev\\SdCms\\Models\\CmsArticle');
