<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

class FeedTest extends TestCase
{
    public function test_the_feed_is_valid_atom_with_every_article_and_no_html_in_titles(): void
    {
        $response = $this->get('http://scuttle.dev/feed.xml')->assertOk();
        $this->assertStringStartsWith('application/atom+xml', (string) $response->headers->get('Content-Type'));
        $xml = simplexml_load_string((string) $response->getContent());
        $this->assertNotFalse($xml, 'the feed parses as XML');
        $this->assertSame('http://www.w3.org/2005/Atom', $xml->getNamespaces()['']);

        $urls = [];
        foreach ($xml->entry as $entry) {
            $urls[] = (string) $entry->id;
            $this->assertDoesNotMatchRegularExpression('/[<>]/', (string) $entry->title);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', (string) $entry->updated);
        }
        foreach (Posts::all() as $post) {
            $this->assertContains('https://scuttle.dev/news/'.$post['slug'], $urls, $post['slug']);
        }
        $this->assertSame(count(array_unique($urls)), count($urls));
        $this->assertStringNotContainsString("\u{2014}", (string) $response->getContent());
    }

    public function test_news_pages_advertise_the_feed(): void
    {
        foreach (['/news', '/news/'.array_key_first(Posts::all())] as $path) {
            $this->get('http://scuttle.dev'.$path)->assertOk()->assertSee('<link rel="alternate" type="application/atom+xml"', false);
        }
    }
}
