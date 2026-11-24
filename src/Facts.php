<?php

namespace Spdotdev\ScuttleDev;

/**
 * Facts that change with the outside world, in one place so a bump is one edit.
 *
 * The weekly workflow (scripts/check-php-facts.py, job "facts" in live-links.yml) reads php.net and fails when
 * PHP_LATEST is behind, naming the string to change. Version numbers in news articles (Posts.php) are a dated record
 * of one release and are not meant to move.
 */
final class Facts
{
    /** Latest security release per PHP series, as php.net lists it. The home page names the 8.4 one. */
    public const PHP_LATEST = ['8.5' => '8.5.13', '8.4' => '8.4.28', '8.3' => '8.3.37'];

    /** The 8.4 release before PHP_LATEST['8.4'], shown as "from → to" in the hero command line. */
    public const PHP_HERO_PREVIOUS = '8.4.27';

    /** Release date of PHP_LATEST['8.4'], English and Dutch (the Dutch pages are rendered from the same constant). */
    public const PHP_HERO_DATE = '19 Nov 2026';

    public const PHP_HERO_DATE_NL = '19 nov 2026';

    /**
     * What PHP_LATEST['8.4'] fixed, in the hero command line. Count it from the release notes (NEWS in php-src, the
     * entries that say "remote code execution") and reword it when a release has none.
     */
    public const PHP_HERO_FIX = 'three RCE bugs fixed';

    public const PHP_HERO_FIX_NL = 'drie RCE-lekken verholpen';
}
