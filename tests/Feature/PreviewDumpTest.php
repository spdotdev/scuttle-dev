<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * Not a test of behaviour: with SCUTTLE_DUMP=<dir> it writes every page as a
 * static HTML file (asset links made relative) so the theme can be screenshotted
 * without a PHP host. Skipped otherwise.
 */
class PreviewDumpTest extends TestCase
{
    public function test_dump_every_page_as_static_html(): void
    {
        $dir = getenv('SCUTTLE_DUMP');
        if (! $dir) {
            $this->markTestSkipped('Set SCUTTLE_DUMP=<dir> to write the preview files.');
        }
        is_dir($dir) || mkdir($dir, 0755, true);

        $pages = ['/' => 'index', '/pricing' => 'pricing', '/health-check' => 'legacy-health-check', '/contact' => 'contact', '/case-studies/phalcon-to-laravel' => 'case', '/case-studies/api-meets-ai' => 'case-mcp', '/privacy' => 'privacy'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $pages['/products/'.$p['slug']] = 'p-'.$p['slug'];
            }
        }
        $map = [];
        foreach ($pages as $path => $file) {
            $map['href="https://scuttle.dev'.$path.'"'] = 'href="'.$file.'.html"';
            $map['href="https://scuttle.dev'.$path.'#'] = 'href="'.$file.'.html#';
        }
        $map['https://scuttle.dev/vendor/scuttle/'] = 'vendor/scuttle/';
        foreach ($pages as $path => $file) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            file_put_contents($dir.'/'.$file.'.html', strtr($html, $map));
        }
        $this->assertFileExists($dir.'/index.html');
    }
}
