<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

class SiteTest extends TestCase
{
    public function test_homepage_renders_on_the_configured_host(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertOk()
            ->assertSee('Scuttle Development');
    }

    public function test_robots_txt_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');
    }

    public function test_sitemap_xml_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_page_references_namespaced_assets(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertSee('vendor/scuttle/', false);
    }
}
