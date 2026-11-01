<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** The terms and NDA PDFs: linked from the pages, served as PDF, named in their own metadata, Dutch pages linking Dutch files. */
class LegalDocumentsTest extends TestCase
{
    private const FILES = ['TERMS_AND_CONDITIONS', 'ALGEMENE_VOORWAARDEN', 'NDA', 'GEHEIMHOUDINGSOVEREENKOMST'];

    public function test_every_pdf_is_served_starts_with_pdf_and_has_a_real_title(): void
    {
        foreach (self::FILES as $name) {
            $file = __DIR__.'/../../public/legal/'.$name.'.pdf';
            $bytes = (string) file_get_contents($file);
            $this->assertStringStartsWith('%PDF-', $bytes, $name);
            $response = $this->get('http://scuttle.dev/legal/'.$name.'.pdf');
            $response->assertOk();
            $this->assertStringContainsString('application/pdf', (string) $response->headers->get('Content-Type'));
            // The title in the PDF's information dictionary is what a browser tab and a download list show. It was once the address of the page the PDF was printed from.
            $this->assertSame(1, preg_match('#/Title\s*\(([^)]*)\)#', $bytes, $m), $name.' has no plain /Title');
            $title = $m[1];
            $this->assertStringContainsString('Scuttle Development', $title, "$name title: $title");
            $this->assertStringNotContainsString('localhost', $title);
            $this->assertStringNotContainsString('.md', $title);
        }
    }

    public function test_dutch_pages_link_the_dutch_files_and_english_pages_the_english_ones(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/services', '/company', '/privacy', '/contact', '/health-check/sample'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
            }
        }
        foreach ($paths as $path) {
            $nl = (string) $this->get('http://scuttle.dev/nl'.($path === '/' ? '' : $path))->assertOk()->getContent();
            preg_match_all('#<a ([^>]*)href="https?://scuttle\.dev/legal/([A-Z_]+)\.pdf"([^>]*)>#', $nl, $links, PREG_SET_ORDER);
            $this->assertNotEmpty($links, "/nl$path links no legal PDF");
            foreach ($links as $l) {
                if (in_array($l[2], ['NDA', 'TERMS_AND_CONDITIONS'], true)) {
                    $this->assertStringContainsString('lang="en"', $l[1].$l[3], "/nl$path links the English $l[2] without marking it lang=en");
                }
                $this->assertNotSame('NDA', $l[2], "/nl$path links the English NDA: use the Dutch Geheimhoudingsovereenkomst");
            }
            $names = array_column($links, 2);
            $this->assertContains('ALGEMENE_VOORWAARDEN', $names, "/nl$path");
            $this->assertContains('GEHEIMHOUDINGSOVEREENKOMST', $names, "/nl$path");

            $en = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('#legal/([A-Z_]+)\.pdf#', $en, $m);
            $this->assertContains('NDA', $m[1], $path);
            $this->assertContains('TERMS_AND_CONDITIONS', $m[1], $path);
        }
    }
}
