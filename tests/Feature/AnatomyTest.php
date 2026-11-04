<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Posts;
use Spdotdev\ScuttleDev\Tests\TestCase;

/** One anatomy per page type: the blocks of every service page, case study and article come in the same order. */
class AnatomyTest extends TestCase
{
    /**
     * @param  list<string>  $order  every marker of the page type, in the order they must appear
     * @param  list<string>  $required  the markers of $order that every page of the type has
     */
    private function assertAnatomy(string $path, array $order, array $required): void
    {
        $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
        $positions = [];
        foreach ($order as $marker) {
            $at = strpos($html, $marker);
            if ($at === false) {
                $this->assertNotContains($marker, $required, "$path misses the block $marker");
            } else {
                $positions[$marker] = $at;
            }
        }
        $found = array_keys($positions);
        $sorted = $positions;
        asort($sorted);
        $this->assertSame($found, array_keys($sorted), "$path has its blocks in another order: ".implode(', ', array_keys($sorted)));
    }

    public function test_service_pages_and_the_health_check_share_one_order_in_both_languages(): void
    {
        // lead, price line, buttons, what you get, steps, what it is not and the terms, the case study, FAQ, extras of the AI page, the services next to it, related reading, the closing call.
        $order = ['<h1', 'data-i="p_price"', 'class="cta"', 'data-i="p_what"', 'data-i="p_how"', 'data-i="p_notl"', 'data-i="p_terms"', 'data-i="p_case"', 'data-i="p_faq_h"', 'data-i="ax_1"', 'data-i="p_also"', 'data-i="p_rl_l"', 'data-i="p_cta1"'];
        $required = ['<h1', 'data-i="p_price"', 'class="cta"', 'data-i="p_what"', 'data-i="p_how"', 'data-i="p_notl"', 'data-i="p_terms"', 'data-i="p_case"', 'data-i="p_cta1"'];
        $paths = [];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = parse_url(Catalog::url($p), PHP_URL_PATH);
            }
        }
        $paths[] = '/health-plan';
        $this->assertGreaterThanOrEqual(12, count($paths));
        foreach ($paths as $path) {
            $this->assertAnatomy($path, $order, $required);
            $this->assertAnatomy('/nl'.$path, $order, $required);
        }
    }

    public function test_case_studies_share_one_order_in_both_languages(): void
    {
        // label, title, lead, facts, situation, approach, result, technology, products behind it, other case studies.
        $order = ['data-i="csp_lbl"', 'data-i="csp_h1"', 'data-i="csp_lead"', 'data-i="csp_f0"', 'data-i="csp_sit_h"', 'data-i="csp_app_h"', 'data-i="csp_res_h"', 'data-i="csp_tech_h"', 'data-i="csp_callout"', 'data-i="csp_more"'];
        $this->assertGreaterThanOrEqual(4, count(Cases::all()));
        foreach (Cases::all() as $c) {
            $this->assertAnatomy('/case-studies/'.$c['slug'], $order, $order);
            $this->assertAnatomy('/nl/case-studies/'.$c['slug'], $order, $order);
        }
    }

    public function test_news_articles_share_one_order(): void
    {
        // date, title, lead, body, "this article is about" line (when it names a service), related services and the call, more news.
        $order = ['data-i="blp_date"', 'data-i="blp_h1"', 'data-i="blp_lead"', 'data-i="blp_about"', 'class="callout"', 'data-i="blp_more"'];
        $required = array_values(array_diff($order, ['data-i="blp_about"']));
        $this->assertNotEmpty(Posts::all());
        foreach (Posts::all() as $post) {
            $this->assertAnatomy('/news/'.$post['slug'], $order, $required);
        }
    }
}
