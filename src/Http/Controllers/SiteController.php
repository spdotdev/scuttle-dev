<?php

namespace Spdotdev\ScuttleDev\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Projects;

class SiteController
{
    public function index(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::site');
    }

    public function privacy(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::privacy');
    }

    public function pricing(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::pricing');
    }

    public function healthCheck(): View
    {
        return $this->productView('legacy-health-check');
    }

    public function product(string $slug): View
    {
        // The Health Check keeps its own address.
        abort_if($slug === 'legacy-health-check', 404);

        return $this->productView($slug);
    }

    public function caseStudy(string $slug): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::case-study', ['c' => Cases::get($slug)]);
    }

    public function work(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::work');
    }

    public function cases(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::cases');
    }

    public function news(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::news');
    }

    public function newsPost(string $slug): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::news-post', ['post' => Posts::get($slug)]);
    }

    public function company(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::company');
    }

    public function projects(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::projects');
    }

    public function project(string $slug): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::project', ['pr' => Projects::get($slug)]);
    }

    public function projectDocs(string $slug): View
    {
        $pr = Projects::get($slug);
        abort_unless(count($pr['docs']) > 0, 404);

        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::project-docs', ['pr' => $pr]);
    }

    public function contact(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::contact');
    }

    public function robots(): Response
    {
        return $this->staticFile('robots.txt', 'text/plain');
    }

    public function sitemap(): Response
    {
        // The static file plus every article that only exists in sd-cms.
        $xml = (string) file_get_contents(__DIR__.'/../../../public/sitemap.xml');
        $extra = '';
        foreach (Posts::all() as $post) {
            // Only well-formed slugs and dates reach the XML; the rest is skipped.
            if (! preg_match('/^[a-z0-9-]+$/', $post['slug']) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $post['date'])) {
                continue;
            }
            $loc = 'https://scuttle.dev/news/'.$post['slug'];
            if (! str_contains($xml, '<loc>'.$loc.'</loc>')) {
                $extra .= "    <url>\n        <loc>".$loc."</loc>\n        <lastmod>".$post['date']."</lastmod>\n        <changefreq>yearly</changefreq>\n        <priority>0.6</priority>\n    </url>\n";
            }
        }
        $xml = str_replace('</urlset>', $extra.'</urlset>', $xml);

        // Pages that exist in both languages: every address lists both twins, and the Dutch one is a url of its own.
        $xml = str_replace('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">', $xml);
        $xml = (string) preg_replace_callback('#<url>\s*<loc>(https://scuttle\.dev(/[^<]*))</loc>(.*?)</url>#s', function (array $m): string {
            $path = $m[2];
            if (! Dutch::paired($path)) {
                return $m[0];
            }
            $en = Dutch::enUrl($path);
            $nl = Dutch::nlUrl($path);
            $alt = "\n    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"$en\"/>\n    <xhtml:link rel=\"alternate\" hreflang=\"nl\" href=\"$nl\"/>\n    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"$en\"/>";

            return "<url>\n    <loc>$en</loc>".$m[3].$alt."\n  </url>\n  <url>\n    <loc>$nl</loc>".$m[3].$alt."\n  </url>";
        }, $xml);

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function legal(string $name): Response
    {
        return $this->staticFile('legal/'.$name.'.pdf', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$name.'.pdf"');
    }

    public function vcard(): Response
    {
        return $this->staticFile('scuttledev.vcf', 'text/vcard; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="scuttle-development.vcf"')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function signatureLogo(): Response
    {
        return $this->staticFile('mail/signature-logo.png', 'image/png')
            ->header('Cache-Control', 'public, max-age=604800');
    }

    private function productView(string $slug): View
    {
        $p = Catalog::get($slug);
        $related = array_values(array_filter(Catalog::all(), fn (array $o) => $o['group'] === $p['group'] && $o['slug'] !== $slug));

        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::product', ['p' => $p, 'related' => $related]);
    }

    /**
     * Serve a shipped static file from the package's public/ directory at the
     * site root (the published vendor path is not at the web root, so crawler
     * files must be routed explicitly).
     */
    private function staticFile(string $name, string $contentType): Response
    {
        $path = __DIR__.'/../../../public/'.$name;
        abort_unless(is_file($path), 404);

        return response((string) file_get_contents($path), 200, ['Content-Type' => $contentType]);
    }
}
