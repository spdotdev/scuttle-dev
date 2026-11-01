<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function test_the_404_page_offers_home_services_pricing_and_the_contact_form_in_both_languages_without_a_cookie(): void
    {
        foreach (['/nope/x' => 'en', '/news/nope' => 'en', '/nl/nope' => 'nl', '/nl/services/nope' => 'nl'] as $path => $lang) {
            $r = $this->get('http://scuttle.dev'.$path);
            $this->assertSame(404, $r->getStatusCode(), $path);
            $this->assertCount(0, $r->headers->getCookies(), $path.' sets no cookie');
            $html = (string) $r->getContent();
            $this->assertStringContainsString('<html lang="'.$lang.'"', $html, $path);
            $this->assertMatchesRegularExpression('/<meta name="robots" content="noindex/', $html, $path);
            $p = $lang === 'nl' ? '/nl' : '';
            foreach (['', '/services', '/pricing', '/contact'] as $target) {
                $this->assertMatchesRegularExpression('#href="https://scuttle\.dev'.preg_quote($p.$target, '#').($p.$target === '' ? '/' : '').'"#', $html, $path.' links '.$target);
            }
            $this->assertStringContainsString('data-i="e4_b1">'.($lang === 'nl' ? 'Bekijk de diensten' : 'See the services').'<', $html, $path);
            $this->assertStringNotContainsString('mailto:', $html);
        }
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
    private function everyPath(): array
    {
        preg_match_all('#<loc>https://scuttle\.dev(/[^<]*)</loc>#', (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent(), $m);

        return array_values(array_unique(array_merge($m[1], ['/contact', '/nl/contact', '/feed.xml', '/robots.txt', '/news'])));
    }

    public function test_every_page_renders_when_the_database_connection_is_broken(): void
    {
        config(['database.connections.broken' => ['driver' => 'sqlite', 'database' => '/nonexistent-dir/none.sqlite', 'prefix' => ''], 'database.default' => 'broken']);
        DB::purge('broken');
        FakeCmsArticle::$rows = [];
        $this->assertTrue(class_exists('Spdotdev\\SdCms\\Models\\CmsArticle'));
        $paths = $this->everyPath();
        $this->assertGreaterThan(50, count($paths));
        foreach ($paths as $path) {
            $r = $this->get('http://scuttle.dev'.$path);
            $this->assertSame(200, $r->getStatusCode(), $path);
        }
        $this->assertStringContainsString('<h1', (string) $this->get('http://scuttle.dev/news/an-mcp-server-in-front-of-your-api')->getContent());
    }

    public function test_a_failing_cms_query_logs_one_warning_and_falls_back_to_the_built_in_news(): void
    {
        Schema::create('cms_articles', function ($t): void {
            $t->id();
        });
        FakeCmsArticle::$throw = true;
        $logged = [];
        Log::shouldReceive('warning')->andReturnUsing(function (string $m, array $c = []) use (&$logged) {
            $logged[] = $m.json_encode($c);
        });
        try {
            $r = $this->get('http://scuttle.dev/news/an-mcp-server-in-front-of-your-api');
            $this->assertSame(200, $r->getStatusCode());
            $this->assertSame(200, $this->get('http://scuttle.dev/sitemap.xml')->getStatusCode());
            $this->assertCount(2, $logged, 'one warning per request, two requests');
            $this->assertStringNotContainsString('SELECT', implode(' ', $logged));
        } finally {
            FakeCmsArticle::$throw = false;
        }
    }

    public function test_the_contact_page_renders_without_a_database_and_the_post_fails_in_both_languages(): void
    {
        config(['database.connections.broken' => ['driver' => 'sqlite', 'database' => '/nonexistent-dir/none.sqlite', 'prefix' => ''], 'database.default' => 'broken']);
        DB::purge('broken');
        $this->get('http://scuttle.dev/contact')->assertOk()->assertSee('name="name"', false);
        $t = (string) (time() - 10);
        $form = ['name' => 'Anna', 'email' => 'a@example.com', 'about' => '', 'message' => 'A message long enough.', 't' => $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'))];
        $r = $this->from('http://scuttle.dev/nl/contact')->post('http://scuttle.dev/contact', $form);
        $r->assertRedirect('http://scuttle.dev/nl/contact#cform')->assertSessionHasErrors('message');
        $this->assertStringContainsString('Je bericht kon nu niet worden bewaard', session('contact_errors_nl')[0]);
        $this->assertStringContainsString('could not be saved', session('contact_errors_en')[0]);
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

    public static bool $throw = false;

    /** @return Collection<int, object> */
    public static function published(string $site)
    {
        if (self::$throw) {
            throw new QueryException('testing', 'select * from cms_articles where secret = ?', ['anna@example.com'], new \PDOException('down'));
        }

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
