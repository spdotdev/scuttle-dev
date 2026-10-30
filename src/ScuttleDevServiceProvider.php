<?php

namespace Spdotdev\ScuttleDev;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        // A missing page on this host gets the site's own 404, not the framework's bare one. Other hosts of the
        // same app keep theirs.
        $handler = $this->app->make(ExceptionHandler::class);
        if (method_exists($handler, 'renderable')) {
            $handler->renderable(function (NotFoundHttpException $e, Request $request) {
                if ($request->getHost() !== config('scuttle-dev.domain') || $request->expectsJson()) {
                    return null;
                }

                return response()->view('scuttle::404', [], 404);
            });
        }

        $this->publishes([
            __DIR__.'/../config/scuttle-dev.php' => config_path('scuttle-dev.php'),
        ], 'scuttle-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/scuttle'),
        ], 'scuttle-dev-assets');
    }
}
