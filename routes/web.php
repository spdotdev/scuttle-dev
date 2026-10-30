<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\ScuttleDev\Http\Controllers\ContactController;
use Spdotdev\ScuttleDev\Http\Controllers\SiteController;
use Spdotdev\ScuttleDev\Http\Middleware\DutchVersion;

Route::domain(config('scuttle-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('scuttle.home');
        Route::get('/privacy', [SiteController::class, 'privacy'])->name('scuttle.privacy');
        Route::get('/pricing', [SiteController::class, 'pricing'])->name('scuttle.pricing');
        Route::get('/health-check', [SiteController::class, 'healthCheck'])->name('scuttle.health-check');
        Route::redirect('/legacy-health-check', '/health-check', 301);
        Route::get('/services/{slug}', [SiteController::class, 'product'])
            ->where('slug', '[a-z0-9-]+')
            ->name('scuttle.product');
        Route::get('/products/{slug}', fn (string $slug) => redirect('/services/'.$slug, 301))->where('slug', '[a-z0-9-]+');
        Route::get('/services', [SiteController::class, 'work'])->name('scuttle.work');
        Route::redirect('/work', '/services', 301);
        Route::get('/case-studies', [SiteController::class, 'cases'])->name('scuttle.cases');
        Route::get('/news', [SiteController::class, 'news'])->name('scuttle.news');
        Route::get('/news/{slug}', [SiteController::class, 'newsPost'])->where('slug', '[a-z0-9-]+')->name('scuttle.news.post');
        Route::redirect('/blog', '/news', 301);
        Route::get('/blog/{slug}', fn (string $slug) => redirect('/news/'.$slug, 301))->where('slug', '[a-z0-9-]+');
        Route::get('/company', [SiteController::class, 'company'])->name('scuttle.company');
        Route::get('/projects', [SiteController::class, 'projects'])->name('scuttle.projects');
        Route::get('/projects/{slug}', [SiteController::class, 'project'])->where('slug', '[a-z0-9-]+')->name('scuttle.project');
        Route::get('/projects/{slug}/docs', [SiteController::class, 'projectDocs'])->where('slug', '[a-z0-9-]+')->name('scuttle.project.docs');
        Route::get('/contact', [SiteController::class, 'contact'])->name('scuttle.contact.page');
        Route::get('/case-studies/{slug}', [SiteController::class, 'caseStudy'])->where('slug', '[a-z0-9-]+')->name('scuttle.case');
        Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:6,1')->name('scuttle.contact');

        // The Dutch version of the pages that exist in full in both languages (see Dutch.php).
        Route::prefix('nl')->middleware(DutchVersion::class)->group(function () {
            Route::get('/', [SiteController::class, 'index'])->name('scuttle.nl.home');
            Route::get('/privacy', [SiteController::class, 'privacy']);
            Route::get('/pricing', [SiteController::class, 'pricing']);
            Route::get('/health-check', [SiteController::class, 'healthCheck']);
            Route::get('/services', [SiteController::class, 'work']);
            Route::get('/services/{slug}', [SiteController::class, 'product'])->where('slug', '[a-z0-9-]+');
            Route::get('/case-studies', [SiteController::class, 'cases']);
            Route::get('/case-studies/{slug}', [SiteController::class, 'caseStudy'])->where('slug', '[a-z0-9-]+');
            Route::get('/company', [SiteController::class, 'company']);
            Route::get('/contact', [SiteController::class, 'contact']);
            Route::redirect('/work', '/nl/services', 301);
        });

        // The vCard at the root with the right media type, so phones open it as a contact.
        Route::get('/scuttle.vcf', [SiteController::class, 'vcard'])->name('scuttle.vcard');

        // Crawler files served at the site root.
        Route::get('/robots.txt', [SiteController::class, 'robots'])->name('scuttle.robots');
        Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('scuttle.sitemap');

        // General terms and NDA, both languages, at scuttle.dev/legal/<NAME>.pdf.
        Route::get('/legal/{name}.pdf', [SiteController::class, 'legal'])
            ->where('name', 'ALGEMENE_VOORWAARDEN|TERMS_AND_CONDITIONS|GEHEIMHOUDINGSOVEREENKOMST|NDA')
            ->name('scuttle.legal');

        // The small logo in the CRM mail signature (sd-crm #612). Routed so it
        // needs no asset publish on the server.
        Route::get('/mail/signature-logo.png', [SiteController::class, 'signatureLogo'])->name('scuttle.mail.signature-logo');
    });
