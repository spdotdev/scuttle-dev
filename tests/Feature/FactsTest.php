<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Facts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** The PHP release facts live in src/Facts.php only; the pages and the weekly php.net check read them from there. */
class FactsTest extends TestCase
{
    public function test_the_hero_command_line_uses_the_constants_in_both_languages(): void
    {
        $latest = Facts::PHP_LATEST['8.4'];
        $this->assertSame(1, preg_match('/^8\.4\.\d+$/', $latest));
        $this->assertSame(1, preg_match('/^8\.4\.\d+$/', Facts::PHP_HERO_PREVIOUS));
        $this->assertLessThan(0, version_compare(Facts::PHP_HERO_PREVIOUS, $latest), 'the previous release is older than the latest');

        $en = (string) $this->get('http://scuttle.dev/')->assertOk()->getContent();
        $this->assertStringContainsString('PHP '.$latest.'</span>', $en);
        $this->assertStringContainsString('security release '.Facts::PHP_HERO_DATE.'</span><span>'.Facts::PHP_HERO_PREVIOUS.' → '.$latest.'</span>', $en);

        $nl = (string) $this->get('http://scuttle.dev/nl')->assertOk()->getContent();
        $this->assertStringContainsString('PHP '.$latest.'</span>', $nl);
        $this->assertStringContainsString('beveiligingsrelease '.Facts::PHP_HERO_DATE_NL.'</span><span>'.Facts::PHP_HERO_PREVIOUS.' → '.$latest.'</span>', $nl);
    }

    public function test_no_view_hard_codes_a_php_patch_version(): void
    {
        foreach (glob(__DIR__.'/../../resources/views/{,partials/}*.blade.php', GLOB_BRACE) as $file) {
            $this->assertSame(0, preg_match('/\b8\.[2-5]\.\d{1,2}\b/', (string) file_get_contents($file)), basename($file).' names a PHP patch version: use Spdotdev\ScuttleDev\Facts');
        }
    }

    public function test_the_weekly_check_reads_the_same_constants(): void
    {
        $script = (string) file_get_contents(__DIR__.'/../../scripts/check-php-facts.py');
        $this->assertStringContainsString('src/Facts.php', $script);
        $workflow = (string) file_get_contents(__DIR__.'/../../.github/workflows/live-links.yml');
        $this->assertStringContainsString('scripts/check-php-facts.py', $workflow);
    }
}
