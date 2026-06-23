<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\ScuttleDev\Http\Controllers\SiteController;

Route::domain(config('scuttle-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('scuttle.home');

        // Crawler files served at the site root.
        Route::get('/robots.txt', [SiteController::class, 'robots'])->name('scuttle.robots');
        Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('scuttle.sitemap');
    });
