<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Illuminate\Http\Request;
use Spdotdev\ScuttleDev\LanguageOffer;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** The Dutch offer: shown once to Dutch-browser visitors on paired English pages, never after a choice. */
class LanguageOfferTest extends TestCase
{
    private function req(string $acceptLanguage = '', array $cookies = []): Request
    {
        $server = $acceptLanguage === '' ? [] : ['HTTP_ACCEPT_LANGUAGE' => $acceptLanguage];

        return Request::create('/', 'GET', [], $cookies, [], $server);
    }

    public function test_dutch_browser_on_a_paired_english_page_gets_the_offer(): void
    {
        $this->assertTrue(LanguageOffer::shouldOffer($this->req('nl-NL,nl;q=0.9,en;q=0.8'), true, false));
    }

    public function test_english_browser_gets_no_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('en-GB,en;q=0.9'), true, false));
    }

    public function test_dutch_only_after_english_in_the_list_gets_no_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('en-US,en;q=0.9,nl;q=0.5'), true, false));
    }

    public function test_no_offer_on_unpaired_pages_or_on_the_dutch_version(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL'), false, false));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL'), true, true));
    }

    public function test_a_stored_choice_stops_the_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL', [LanguageOffer::COOKIE => 'en']), true, false));
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('nl-NL', [LanguageOffer::COOKIE => 'nl']), true, false));
    }

    public function test_a_dutch_ip_gets_the_offer_even_with_an_english_browser(): void
    {
        $this->assertTrue(LanguageOffer::shouldOffer($this->req('en-GB,en;q=0.9'), true, false, 'NL'));
    }

    public function test_a_foreign_ip_with_an_english_browser_gets_no_offer(): void
    {
        $this->assertFalse(LanguageOffer::shouldOffer($this->req('en-GB,en;q=0.9'), true, false, 'US'));
    }

    public function test_no_country_database_means_no_country(): void
    {
        config(['scuttle-dev.geoip_db' => '']);
        $this->assertNull(LanguageOffer::countryOf($this->req()));
        config(['scuttle-dev.geoip_db' => '/nonexistent/file.mmdb']);
        $this->assertNull(LanguageOffer::countryOf($this->req()));
    }
}
