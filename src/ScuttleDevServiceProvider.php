<?php

namespace Spdotdev\ScuttleDev;

use Illuminate\Support\ServiceProvider;

class ScuttleDevServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scuttle-dev.php', 'scuttle-dev');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'scuttle');

        $this->publishes([
            __DIR__.'/../config/scuttle-dev.php' => config_path('scuttle-dev.php'),
        ], 'scuttle-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/scuttle'),
        ], 'scuttle-dev-assets');
    }
}
