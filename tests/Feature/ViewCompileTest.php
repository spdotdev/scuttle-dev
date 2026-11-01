<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\View\Compilers\BladeCompiler;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spdotdev\ScuttleDev\Tests\TestCase;

class ViewCompileTest extends TestCase
{
    /**
     * Compiles every Blade view and lints the PHP the compiler produces, with short_open_tag on and off. The
     * production image runs with the setting on, a laptop PHP often has it off, and the difference once turned a
     * literal "<?xml" into a syntax error that only the server showed (/feed.xml answered 500 on 2026-11-01).
     */
    public function test_every_blade_view_compiles_to_valid_php_with_and_without_short_open_tag(): void
    {
        $compiler = $this->app->make('blade.compiler');
        $this->assertInstanceOf(BladeCompiler::class, $compiler);

        $dir = realpath(__DIR__.'/../../resources/views');
        $this->assertNotFalse($dir);
        $views = 0;
        $failures = [];
        $tmp = sys_get_temp_dir().'/scuttle-view-lint-'.getmypid().'.php';

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)) as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            $views++;
            file_put_contents($tmp, $compiler->compileString((string) file_get_contents($file->getPathname())));
            foreach (['0', '1'] as $short) {
                $out = [];
                exec(escapeshellarg(PHP_BINARY).' -d short_open_tag='.$short.' -l '.escapeshellarg($tmp).' 2>&1', $out, $code);
                if ($code !== 0) {
                    $failures[] = substr($file->getPathname(), strlen($dir) + 1).' (short_open_tag='.$short.'): '.implode(' ', $out);
                }
            }
        }
        @unlink($tmp);

        $this->assertGreaterThan(20, $views, 'the view folder was found');
        $this->assertSame([], $failures, implode("\n", $failures));
    }
}
