<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

/** Numbers on the proof pages are a public fact with its source on the page, or are marked as the client's own or as an example. */
class ProofNumbersTest extends TestCase
{
    public function test_the_client_case_says_its_figures_cannot_be_checked_from_outside(): void
    {
        $en = (string) $this->get('http://scuttle.dev/case-studies/phalcon-to-laravel')->assertOk()->getContent();
        $this->assertStringContainsString('60 percent', $en);
        $this->assertStringContainsString('cannot be checked from outside', $en);
        $nl = (string) $this->get('http://scuttle.dev/nl/case-studies/phalcon-to-laravel')->assertOk()->getContent();
        $this->assertStringContainsString('niet van buiten te controleren', $nl);
    }

    public function test_the_sample_report_marks_its_numbers_as_made_up_and_sources_the_real_ones(): void
    {
        $html = (string) $this->get('http://scuttle.dev/health-plan/sample')->assertOk()->getContent();
        $this->assertStringContainsString('are invented, so that no client is shown', $html);
        $this->assertStringContainsString('php.net', $html);
    }

    public function test_the_company_block_names_where_each_number_can_be_checked(): void
    {
        $html = (string) $this->get('http://scuttle.dev/company')->assertOk()->getContent();
        $this->assertStringContainsString('Sixteen repositories are public on <a href="https://github.com/spdotdev"', $html);
        $this->assertStringContainsString('On 30 October 2026', $html, 'the Lighthouse run carries its date');
        $this->assertStringContainsString('KvK 96040947', $html);
    }
}
