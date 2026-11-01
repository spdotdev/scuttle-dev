<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Cases;
use Spdotdev\ScuttleDev\Catalog;
use Spdotdev\ScuttleDev\Dutch;
use Spdotdev\ScuttleDev\Tests\TestCase;

/**
 * The Dutch strings read as Dutch: calques and English leftovers that a Dutch reader stumbles on stay out
 * (round 15 read all of them; this keeps them out).
 */
class DutchReadingTest extends TestCase
{
    private const BANNED = [
        'end-of-life' => 'einddatum van support',
        'kwaliteitslat' => 'norm',
        'menselijke tik' => 'akkoord',
        'zonder rewrite' => 'zonder te herschrijven',
        'webhook vuurt' => 'webhook gaat af',
        'geback-upt' => 'met back-ups',
        'teampasvorm' => 'pasvorm voor je team',
        'wake word-model' => 'wake-wordmodel',
        'Security Assessment' => 'Security Test (the product name)',
        'na het te hebben' => 'nadat ik',
        'aan het eind van de dag' => 'uiteindelijk',
        'voor nu' => 'voorlopig',
        'maak een afspraak' => 'plan een gesprek',
        'impact maken' => 'effect hebben',
    ];

    public function test_no_dutch_string_holds_a_calque_or_a_stale_product_name(): void
    {
        $paths = ['/', '/pricing', '/health-check', '/health-check/sample', '/php-check', '/nis2', '/services', '/contact', '/company', '/privacy', '/case-studies', '/news', '/projects', '/projects/manual', '/projects/manual/docs'];
        foreach (Catalog::all() as $p) {
            if (! isset($p['route'])) {
                $paths[] = '/services/'.$p['slug'];
            }
        }
        foreach (Cases::all() as $c) {
            $paths[] = '/case-studies/'.$c['slug'];
        }
        $found = [];
        foreach ($paths as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            $nl = Dutch::strings($html);
            $meta = Dutch::paired($path) ? Dutch::meta($path) : [];
            foreach (array_merge($nl, $meta) as $key => $text) {
                foreach (self::BANNED as $bad => $use) {
                    if (is_string($text) && strpos($text, $bad) !== false) {
                        $found[$path.' '.$key.': "'.$bad.'" (use '.$use.')'] = true;
                    }
                }
            }
        }
        $this->assertSame([], array_keys($found));
    }

    public function test_dutch_headings_capitalise_only_the_first_word(): void
    {
        foreach (['/nl/pricing', '/nl/company', '/nl/case-studies', '/nl/contact'] as $path) {
            $html = (string) $this->get('http://scuttle.dev'.$path)->assertOk()->getContent();
            preg_match_all('#<h2[^>]*>(.*?)</h2>#s', $html, $m);
            foreach ($m[1] as $h) {
                $text = trim(strip_tags($h));
                // a Dutch function or question word with a capital after the first word is an English headline habit
                $this->assertDoesNotMatchRegularExpression('/(?<=\s)(De|Het|Een|Voor|Van|Naar|Met|Hoe|Wat|Waar|Wie|Waarom|En|Of|Je|Jouw|Ik|Alle|Elk|Elke)\b/', $text, $path.': "'.$text.'"');
            }
        }
    }
}
