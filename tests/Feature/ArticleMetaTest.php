<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Articles say when they were published and modified and who wrote them, for link previews and search. */
class ArticleMetaTest extends TestCase
{
    public function test_every_article_carries_published_modified_and_author_in_the_head(): void
    {
        $this->assertNotEmpty(Posts::all());
        foreach (Posts::all() as $post) {
            $html = (string) $this->get('http://scuttle.dev/news/'.$post['slug'])->assertOk()->getContent();
            $head = substr($html, 0, (int) strpos($html, '</head>'));
            $this->assertStringContainsString('<meta property="og:type" content="article" />', $head);
            $this->assertStringContainsString('<meta property="article:published_time" content="'.$post['date'].'" />', $head);
            // equal to the published date until an edit date exists
            $this->assertStringContainsString('<meta property="article:modified_time" content="'.$post['date'].'" />', $head);
            $this->assertStringContainsString('<meta property="article:author" content="https://scuttle.dev/company" />', $head);
            $this->assertMatchesRegularExpression('/^\d{4}-\d\d-\d\d$/', $post['date']);
        }
    }

    public function test_pages_that_are_not_articles_carry_no_article_tags(): void
    {
        foreach (['/', '/news', '/pricing'] as $path) {
            $this->assertStringNotContainsString('article:published_time', (string) $this->get('http://scuttle.dev'.$path)->getContent(), $path);
        }
    }
}
