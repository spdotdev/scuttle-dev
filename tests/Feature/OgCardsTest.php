<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** The social cards in public/og are rendered by hand from the page data; this fails when the data moved on and the cards did not. */
class OgCardsTest extends TestCase
{
    public function test_the_cards_were_rendered_from_the_current_data(): void
    {
        $root = __DIR__.'/../..';
        $data = (string) shell_exec(escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/design/og-data.php'));
        $this->assertStringStartsWith('{', $data, 'design/og-data.php prints the card data as JSON');
        $expected = hash('sha256', $data.file_get_contents($root.'/design/make-og.py'));

        $this->assertFileExists($root.'/public/og/.source-hash', 'the cards carry no source hash: run the rebuild');
        $this->assertSame(
            $expected,
            trim((string) file_get_contents($root.'/public/og/.source-hash')),
            'The social cards are out of date with the page data (a title, price or line changed, or design/make-og.py did). Rebuild: php design/og-data.php > /tmp/og.json && python3 design/make-og.py /tmp/og.json (Playwright), then commit public/og.',
        );
    }

    public function test_every_card_in_the_data_has_a_png(): void
    {
        $root = __DIR__.'/../..';
        $cards = json_decode((string) shell_exec(escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/design/og-data.php')), true);
        $this->assertGreaterThan(30, count($cards));
        foreach (array_keys($cards) as $slug) {
            $this->assertFileExists($root.'/public/og/'.$slug.'.png');
        }
    }
}
