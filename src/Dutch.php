<?php

namespace Spdotdev\ScuttleDev;

/**
 * The Dutch version of the site at /nl/...: the same views, rendered in English by the controllers, then
 * switched to Dutch on the server with the strings every page already carries for the JS switch.
 * Only pages that exist in full in both languages are paired (home, services, pricing, Health Check,
 * contact, company, privacy, case studies); news bodies and project docs stay English and have no /nl/ twin.
 */
final class Dutch
{
    public const HOST = 'https://scuttle.dev';

    /** @var list<string> */
    private const FIXED = ['/', '/pricing', '/health-check', '/services', '/contact', '/company', '/privacy', '/case-studies'];

    public static function paired(string $path): bool
    {
        $path = '/'.trim($path, '/');
        if (in_array($path, self::FIXED, true)) {
            return true;
        }
        if (preg_match('#^/services/([a-z0-9-]+)$#', $path, $m)) {
            return isset(Catalog::all()[$m[1]]);
        }
        if (preg_match('#^/case-studies/([a-z0-9-]+)$#', $path, $m)) {
            return isset(Cases::all()[$m[1]]);
        }

        return false;
    }

    /** The English path as the controllers know it, from a path under /nl. */
    public static function enPath(string $nlPath): string
    {
        $p = '/'.trim($nlPath, '/');
        $p = (string) preg_replace('#^/nl#', '', $p);

        return $p === '' ? '/' : $p;
    }

    public static function nlUrl(string $enPath): string
    {
        $enPath = '/'.trim($enPath, '/');

        return self::HOST.'/nl'.($enPath === '/' ? '' : $enPath);
    }

    public static function enUrl(string $enPath): string
    {
        $enPath = '/'.trim($enPath, '/');

        return self::HOST.($enPath === '/' ? '/' : $enPath);
    }

    /**
     * Dutch title and description for a paired page.
     *
     * @return array{0:string,1:string}
     */
    public static function meta(string $enPath): array
    {
        $p = '/'.trim($enPath, '/');
        $fixed = [
            '/' => ['Automatisering, AI en beveiliging voor je bedrijfssoftware', 'Automatiseer het werk dat je team herhaalt: AI-agents, koppelingen en veilige software die blijft draaien. Eén senior developer in Eindhoven.'],
            '/pricing' => ['Prijzen', 'Eén tarief, twee manieren om te kopen: EUR 90 per uur excl. btw, of een vaste prijs voor de Health Check, de security quick scan en de architectuurreview.'],
            '/health-check' => ['Health Check', 'Beoordeling in twee dagen: kansen voor automatisering, risico\'s en een stappenplan. Vaste prijs EUR 1.080 excl. btw (12 uur).'],
            '/services' => ['Diensten', 'De twaalf diensten en wat je daarna in handen hebt: een rapport, een draaiende server, een pipeline, een document in je repository. Open prijzen.'],
            '/contact' => ['Contact', 'Neem contact op met Scuttle Development in Eindhoven: het formulier, info@scuttle.dev, telefoon, vCard en adres. Ik reageer binnen één werkdag.'],
            '/company' => ['Het bedrijf', 'Scuttle Development: één senior developer en solution architect in Eindhoven, opgericht in 2024. Hoe ik werk, waar ik in geloof en hoe je met me werkt.'],
            '/privacy' => ['Privacyverklaring', 'Welke gegevens Scuttle Development gebruikt en waarom, welke verwerkers meekijken, dat deze site geen tracking- of analysecookies zet en hoe je je gegevens laat verwijderen.'],
            '/case-studies' => ['Casestudy\'s', 'Casestudy\'s van Scuttle Development: een migratie terwijl het platform in gebruik bleef, een API voor AI-assistenten, een klein model voor een wake word en een bedrijf op agents.'],
        ];
        if (isset($fixed[$p])) {
            return $fixed[$p];
        }
        if (preg_match('#^/services/([a-z0-9-]+)$#', $p, $m) && isset(Catalog::all()[$m[1]])) {
            $c = Catalog::all()[$m[1]];

            return [$c['name'], $c['nl']['short'].' '.$c['nl']['price'].'.'];
        }
        if (preg_match('#^/case-studies/([a-z0-9-]+)$#', $p, $m) && isset(Cases::all()[$m[1]])) {
            $c = Cases::all()[$m[1]];

            return [$c['nl']['title'], 'Casestudy: '.$c['nl']['lead']];
        }

        return ['', ''];
    }

    public static function fit(string $title, string $suffix = ' | Scuttle Development'): string
    {
        $t = mb_strlen($title.$suffix) <= 62 ? $title.$suffix : $title;
        if (mb_strlen($t) > 70) {
            $t = rtrim((string) preg_replace('/\s+\S*$/u', '', mb_substr($t, 0, 68)), ' ,.:;-').'…';
        }

        return $t;
    }

    public static function fitDescription(string $d): string
    {
        $d = trim((string) preg_replace('/\s+/', ' ', $d));
        if (mb_strlen($d) <= 160) {
            return $d;
        }
        $cut = mb_substr($d, 0, 160);
        if (preg_match('/^(.{80,}(?<!excl)(?<!e\.g)[.!?])\s/us', $cut, $m)) {
            return $m[1];
        }

        return rtrim((string) preg_replace('/\s+\S*$/u', '', mb_substr($d, 0, 157)), ' ,.:;-').'…';
    }

    /** The Dutch strings the page pushes for the JS switch, read from the rendered HTML. */
    public static function strings(string $html): array
    {
        $map = [];
        if (! preg_match_all('#<script>\s*(?:window\.SCUTTLE_NL|Object\.assign\(window\.SCUTTLE_NL\.t)(.*?)</script>#s', $html, $blocks)) {
            return $map;
        }
        foreach ($blocks[1] as $block) {
            if (preg_match_all('/(?:"([A-Za-z0-9_-]+)"|([A-Za-z0-9_]+))\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $block, $pairs, PREG_SET_ORDER)) {
                foreach ($pairs as $pair) {
                    $key = $pair[1] !== '' ? $pair[1] : $pair[2];
                    $val = json_decode('"'.$pair[3].'"');
                    if (is_string($val)) {
                        $map[$key] = $val;
                    }
                }
            }
        }

        return $map;
    }

    /** Replace the inside of every element with data-i="key" by its Dutch string, like the JS switch does. */
    public static function translate(string $html, array $map): string
    {
        $void = ['input', 'img', 'br', 'hr', 'meta', 'link'];
        $out = '';
        $pos = 0;
        $len = strlen($html);
        while ($pos < $len && preg_match('/<([a-z][a-z0-9]*)\b[^>]*?\sdata-i="([^"]+)"[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE, $pos)) {
            [$open, $start] = $m[0];
            $tag = strtolower($m[1][0]);
            $key = $m[2][0];
            $end = $start + strlen($open);
            $out .= substr($html, $pos, $end - $pos);
            $pos = $end;
            if (in_array($tag, $void, true) || str_ends_with($open, '/>') || ! isset($map[$key])) {
                continue;
            }
            // find the matching close tag
            $depth = 1;
            $scan = $end;
            $closeAt = null;
            while ($depth > 0 && preg_match('#<(/?)'.preg_quote($tag, '#').'\b[^>]*>#i', $html, $t, PREG_OFFSET_CAPTURE, $scan)) {
                $isClose = $t[1][0] === '/';
                $depth += $isClose ? -1 : (str_ends_with($t[0][0], '/>') ? 0 : 1);
                $scan = $t[0][1] + strlen($t[0][0]);
                if ($depth === 0) {
                    $closeAt = $t[0][1];
                }
            }
            if ($closeAt === null) {
                continue;
            }
            $out .= $map[$key];
            $pos = $closeAt;
        }

        return $out.substr($html, $pos);
    }

    public static function transform(string $html, string $enPath): string
    {
        $map = self::strings($html);
        $html = self::translate($html, $map);

        // links to paired pages stay in Dutch, whether written as a full address or as a plain path
        $html = (string) preg_replace_callback('#<a\b[^>]*>#', function (array $a): string {
            if (str_contains($a[0], 'hreflang=')) {
                return $a[0];
            }

            return (string) preg_replace_callback('#href="(?:https://scuttle\.dev)?(/[^"\#?]*)([\#?][^"]*)?"#', function (array $m): string {
                if (str_starts_with($m[1], '/nl') || ! self::paired($m[1])) {
                    return $m[0];
                }

                return 'href="'.self::nlUrl($m[1]).($m[2] ?? '').'"';
            }, $a[0]);
        }, $html);

        [$title, $desc] = self::meta($enPath);
        if ($title !== '') {
            $t = e(self::fit($title));
            $d = e(self::fitDescription($desc));
            $html = (string) preg_replace('#<title>.*?</title>#s', '<title>'.$t.'</title>', $html, 1);
            $html = (string) preg_replace('#(<meta (?:name|property)="(?:description|og:description|twitter:description)" content=")[^"]*(")#', '${1}'.$d.'${2}', $html);
            $html = (string) preg_replace('#(<meta (?:property="og:title"|name="twitter:title") content=")[^"]*(")#', '${1}'.$t.'${2}', $html);
        }
        $nl = self::nlUrl($enPath);
        $html = (string) preg_replace('#<html lang="en"#', '<html lang="nl"', $html, 1);
        $html = (string) preg_replace('#(<link rel="canonical" href=")[^"]*(")#', '${1}'.$nl.'${2}', $html, 1);
        $html = (string) preg_replace('#(<meta property="og:url" content=")[^"]*(")#', '${1}'.$nl.'${2}', $html, 1);
        $html = str_replace(['content="en_GB"', 'content="nl_NL" />'], ['content="NL_PRIMARY"', 'content="nl_NL" />'], $html);
        $html = str_replace('<meta property="og:locale:alternate" content="nl_NL" />', '<meta property="og:locale:alternate" content="en_GB" />', $html);
        $html = str_replace('content="NL_PRIMARY"', 'content="nl_NL"', $html);

        return $html;
    }
}
