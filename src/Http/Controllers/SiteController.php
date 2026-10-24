<?php

namespace Spdotdev\ScuttleDev\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Spdotdev\ScuttleDev\Catalog;

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

    public function caseStudy(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::case-study');
    }

    public function robots(): Response
    {
        return $this->staticFile('robots.txt', 'text/plain');
    }

    public function sitemap(): Response
    {
        return $this->staticFile('sitemap.xml', 'application/xml');
    }

    public function legal(string $name): Response
    {
        return $this->staticFile('legal/'.$name.'.pdf', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$name.'.pdf"');
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
