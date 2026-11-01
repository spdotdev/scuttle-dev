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

    /**
     * The production image runs PHP with short_open_tag on, so a literal "<?xml" in a Blade source is read as a PHP
     * open tag and the compiled view dies with a syntax error (seen live on 2026-11-01, /feed.xml answered 500 while
     * the suite was green on a PHP without that setting). Views build the prolog from pieces instead.
     */
    public function test_no_blade_view_holds_a_literal_xml_or_short_open_tag(): void
    {
        $bad = [];
        foreach (glob(__DIR__.'/../../resources/views/{,*/}*.blade.php', GLOB_BRACE) as $file) {
            $src = (string) file_get_contents($file);
            if (preg_match('/<\?(?!php\b|=)/', $src) === 1) {
                $bad[] = basename($file);
            }
        }
        $this->assertSame([], $bad, 'Blade views with a "<?" that is not "<?php": '.implode(', ', $bad));
    }
}
