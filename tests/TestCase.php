<?php

namespace Spdotdev\ScuttleDev\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spdotdev\ScuttleDev\ScuttleDevServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [ScuttleDevServiceProvider::class];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // The package routes run in the `web` group, whose cookie encryption
        // requires an application key. Set a deterministic one for tests.
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));

        // The testbench skeleton lives inside vendor/, which may be read-only
        // (e.g. installed by a different user or a locked-down CI cache).
        // Compile Blade views into a writable temp directory and log to
        // errorlog so tests don't fail on filesystem permissions.
        $compiled = sys_get_temp_dir().'/scuttle-dev-tests/views';
        if (! is_dir($compiled)) {
            mkdir($compiled, 0755, true);
        }
        $app['config']->set('view.compiled', $compiled);
        $app['config']->set('logging.default', 'errorlog');
    }
}
