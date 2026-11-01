<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** The config, the README and .env.example name the same settings; composer.json and the dist archive are in order. */
class PackageHygieneTest extends TestCase
{
    public function test_config_readme_and_env_example_name_the_same_variables(): void
    {
        $root = __DIR__.'/../..';
        preg_match_all("/env\\('(SCUTTLE_[A-Z_]+)'/", (string) file_get_contents($root.'/config/scuttle-dev.php'), $m);
        $vars = array_unique($m[1]);
        $this->assertGreaterThanOrEqual(10, count($vars));
        $readme = (string) file_get_contents($root.'/README.md');
        $env = (string) file_get_contents($root.'/.env.example');
        foreach ($vars as $v) {
            $this->assertStringContainsString('`'.$v.'`', $readme, $v.' is in the README');
            $this->assertMatchesRegularExpression('/^'.$v.'=/m', $env, $v.' is in .env.example');
        }
        preg_match_all('/^(SCUTTLE_[A-Z_]+)=/m', $env, $e);
        $this->assertEqualsCanonicalizing($vars, array_unique($e[1]), '.env.example holds no variable the config does not read');
    }

    public function test_composer_json_describes_the_package_and_the_licence_file_matches(): void
    {
        $root = __DIR__.'/../..';
        $c = json_decode((string) file_get_contents($root.'/composer.json'), true);
        $this->assertNotEmpty($c['description']);
        $this->assertNotEmpty($c['keywords']);
        $this->assertSame('proprietary', $c['license']);
        $this->assertStringContainsString('proprietary', strtolower((string) file_get_contents($root.'/LICENSE')));
        $this->assertStringStartsWith('https://github.com/spdotdev/scuttle-dev', $c['support']['issues']);
    }

    public function test_the_dist_archive_leaves_out_tests_docs_and_design_sources(): void
    {
        $attr = (string) file_get_contents(__DIR__.'/../../.gitattributes');
        foreach (['/tests', '/docs', '/design', '/scripts', '/.github'] as $p) {
            $this->assertStringContainsString($p.' export-ignore', $attr);
        }
        foreach (['/src', '/resources', '/public', '/routes', '/config', '/database'] as $p) {
            $this->assertStringNotContainsString($p.' export-ignore', $attr, $p.' is needed at runtime');
        }
    }
}
