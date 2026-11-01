<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** Structured data and social tags on every page, in both languages, built only from facts that are on the page. */
class StructuredDataTest extends TestCase
{
    /** @return list<array<string, mixed>> */
    private function blocks(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);

        return array_map(fn (string $j): array => (array) json_decode($j, true, 512, JSON_THROW_ON_ERROR), $m[1]);
    }

    /** @return list<string> */
    private function paths(): array
    {
        preg_match_all('#<loc>https://scuttle\.dev(/[^<]*)</loc>#', (string) $this->get('http://scuttle.dev/sitemap.xml')->getContent(), $m);

        return array_values(array_unique($m[1]));
    }

    public function test_every_page_has_complete_social_tags_and_breadcrumbs_that_resolve(): void
    {
        $checked = [];
        foreach ($this->paths() as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            foreach (['og:image', 'og:image:width', 'og:image:height', 'og:image:alt', 'og:title', 'og:description'] as $p) {
                $this->assertStringContainsString('property="'.$p.'"', $html, $path.' '.$p);
            }
            foreach (['twitter:card', 'twitter:title', 'twitter:description', 'twitter:image'] as $n) {
                $this->assertStringContainsString('name="'.$n.'"', $html, $path.' '.$n);
            }
            if (str_starts_with($path, '/nl')) {
                preg_match('/property="og:title" content="([^"]*)"/', $html, $t);
                $en = (string) $this->get('http://scuttle.dev'.(preg_replace('#^/nl#', '', $path) ?: '/'))->getContent();
                preg_match('/property="og:title" content="([^"]*)"/', $en, $te);
                $this->assertNotSame($te[1] ?? '', $t[1] ?? '', $path.': Dutch og:title differs from the English one');
            }
            foreach ($this->blocks($html) as $b) {
                if (($b['@type'] ?? '') !== 'BreadcrumbList') {
                    continue;
                }
                foreach ($b['itemListElement'] as $item) {
                    $url = (string) $item['item'];
                    $this->assertStringStartsWith('https://scuttle.dev', $url);
                    $p = (string) parse_url($url, PHP_URL_PATH) ?: '/';
                    if (! isset($checked[$p])) {
                        $checked[$p] = $this->get('http://scuttle.dev'.$p)->getStatusCode();
                    }
                    $this->assertSame(200, $checked[$p], $path.' breadcrumb '.$url);
                }
            }
        }
        $this->assertGreaterThan(10, count($checked));
    }

    public function test_the_organization_block_has_url_logo_and_sameas_and_no_email_or_phone_outside_the_contact_page(): void
    {
        foreach (['/', '/nl'] as $path) {
            $org = collect($this->blocks((string) $this->get('http://scuttle.dev'.$path)->getContent()))->firstWhere('@type', 'ProfessionalService');
            $this->assertNotNull($org, $path);
            $this->assertSame('https://scuttle.dev', $org['url']);
            $this->assertNotEmpty($org['logo']);
            $this->assertNotEmpty($org['image']);
            $this->assertSame(['https://github.com/spdotdev', 'https://www.linkedin.com/in/stanislavplotnikov/', 'https://splotnikov.dev'], $org['sameAs']);
            $this->assertArrayNotHasKey('email', $org);
            $this->assertArrayNotHasKey('telephone', $org);
            $this->assertArrayNotHasKey('openingHours', $org, 'no opening hours are published, so none are claimed');
            $this->assertSame('PostalAddress', $org['address']['@type']);
            $this->assertSame(['Dirigentplein 144', 'Eindhoven', '5642 RK', 'NL'], [$org['address']['streetAddress'], $org['address']['addressLocality'], $org['address']['postalCode'], $org['address']['addressCountry']]);
            $this->assertSame('Eindhoven', $org['areaServed'][0]['name']);
            $this->assertSame('EUR 90 per hour', $org['priceRange']);
            $this->assertSame(['nl', 'en', 'ru'], $org['knowsLanguage']);
            $this->assertSame('Person', $org['founder']['@type']);
            $this->assertContains('https://www.linkedin.com/in/stanislavplotnikov/', $org['founder']['sameAs']);
            $this->assertStringContainsString('Eindhoven', (string) $this->get('http://scuttle.dev'.$path)->getContent());
        }
        foreach ($this->paths() as $path) {
            if (preg_match('#^(/nl)?/contact$#', $path)) {
                continue;
            }
            $json = (string) json_encode($this->blocks((string) $this->get('http://scuttle.dev'.$path)->getContent()));
            $this->assertStringNotContainsString('info@scuttle.dev', $json, $path);
            $this->assertStringNotContainsString('+3161823', $json, $path);
        }
    }

    public function test_the_contact_page_carries_the_contact_point(): void
    {
        foreach (['/contact', '/nl/contact'] as $path) {
            $org = collect($this->blocks((string) $this->get('http://scuttle.dev'.$path)->getContent()))->firstWhere('@type', 'ProfessionalService');
            $this->assertNotNull($org, $path);
            $cp = $org['contactPoint'][0];
            $this->assertSame('info@scuttle.dev', $cp['email']);
            $this->assertSame('+31618233175', $cp['telephone']);
        }
    }

    public function test_every_service_block_has_provider_area_and_an_offer_that_matches_the_visible_price(): void
    {
        foreach (Catalog::all() as $p) {
            foreach ([(string) parse_url(Catalog::canonical($p), PHP_URL_PATH), '/nl'.(string) parse_url(Catalog::canonical($p), PHP_URL_PATH)] as $path) {
                $svc = collect($this->blocks((string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent()))->firstWhere('@type', 'Service');
                $this->assertNotNull($svc, $path);
                $this->assertSame('Scuttle Development', $svc['provider']['name']);
                $this->assertNotEmpty($svc['areaServed'], $path);
                $offer = $svc['offers'];
                $this->assertSame('EUR', $offer['priceCurrency']);
                $price = (string) ($offer['price'] ?? $offer['priceSpecification']['price']);
                $this->assertStringContainsString(number_format((float) $price, 0, '.', ','), $p['price'], $path.': the offer price is the catalog price');
                $this->assertSame('EUR', ($offer['priceSpecification']['priceCurrency'] ?? 'EUR'));
            }
        }
    }

    public function test_news_articles_carry_both_dates_author_publisher_and_the_image(): void
    {
        foreach (Posts::all() as $post) {
            $a = collect($this->blocks((string) $this->get('http://scuttle.dev/news/'.$post['slug'])->assertOk()->getContent()))->firstWhere('@type', 'Article');
            $this->assertNotNull($a);
            $this->assertSame($post['date'], $a['datePublished']);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $a['dateModified']);
            $this->assertSame('Stanislav Plotnikov', $a['author']['name']);
            $this->assertStringEndsWith('.png', $a['image']);
            $this->assertNotEmpty($a['publisher']['name']);
        }
    }
}
