<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * Round 15 measured the Largest Contentful Paint element of every page at 390 and 1366 px: it is always a
 * heading or a paragraph, never an image. So no image needs priority, and every image decodes off the main
 * thread and loads lazily, except the QR thumbnail on the contact page, which is inside the first screen on a desk.
 */
class ImageLoadingTest extends TestCase
{
    public function test_every_image_decodes_async_and_loads_lazily_except_the_first_screen_qr(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/contact', '/company', '/services', '/case-studies', '/news', '/projects', '/privacy', '/nl/contact', '/nl/pricing'];
        $count = 0;
        foreach ($paths as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('#<img\b[^>]*>#', $html, $m);
            foreach ($m[0] as $img) {
                if (str_contains($img, '/_sda/px/')) {
                    continue; // the no-JS counting pixel inside noscript
                }
                $count++;
                $this->assertStringContainsString('decoding="async"', $img, $path.' '.$img);
                $this->assertStringContainsString('width=', $img);
                $this->assertStringContainsString('height=', $img);
                $this->assertStringNotContainsString('fetchpriority', $img, 'no image is the LCP element, so none claims priority');
                if (! str_contains($img, 'QR code with my contact details (vCard)"')) {
                    $this->assertStringContainsString('loading="lazy"', $img, $path.' '.$img);
                }
            }
        }
        $this->assertGreaterThan(10, $count);
    }
}
