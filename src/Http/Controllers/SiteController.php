<?php

namespace Spdotdev\ScuttleDev\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;

class SiteController
{
    public function index(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::site');
    }

    public function robots(): Response
    {
        return $this->staticFile('robots.txt', 'text/plain');
    }

    public function sitemap(): Response
    {
        return $this->staticFile('sitemap.xml', 'application/xml');
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
