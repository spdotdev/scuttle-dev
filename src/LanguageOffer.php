<?php

namespace Spdotdev\ScuttleDev;

use Illuminate\Http\Request;
use MaxMind\Db\Reader;
use Throwable;

/**
 * Decides whether a visitor gets the one-time offer "this page is also in Dutch".
 *
 * Two signals, either one is enough: the visitor's IP is in the Netherlands (free DB-IP Lite country file,
 * see config geoip_db), or the browser's own language list puts Dutch first. The offer never forces a
 * redirect (Google, "Localized versions": let the user switch), and it is never shown again once the visitor
 * has chosen, because the choice is stored in the cookie scuttle_lang.
 */
final class LanguageOffer
{
    public const COOKIE = 'scuttle_lang';

    private static ?Reader $reader = null;

    private static ?string $readerPath = null;

    public static function shouldOffer(Request $request, bool $paired, bool $isNl, ?string $country = null): bool
    {
        if (! $paired || $isNl) {
            return false;
        }
        if ($request->cookie(self::COOKIE) !== null) {
            return false;
        }
        if ($country === 'NL') {
            return true;
        }

        // No Accept-Language header means no evidence: Symfony would answer 'nl' (the first locale), so require the header.
        // nginx passes an empty value when the browser sends no header, so check the value, not only the key.
        if (trim((string) $request->header('Accept-Language', '')) === '') {
            return false;
        }

        return $request->getPreferredLanguage(['nl', 'en']) === 'nl';
    }

    /** ISO country code of the visitor's IP, or null when unknown or no database is configured. */
    public static function countryOf(Request $request): ?string
    {
        $path = (string) config('scuttle-dev.geoip_db', '');
        if ($path === '' || ! is_file($path)) {
            return null;
        }
        try {
            if (self::$reader === null || self::$readerPath !== $path) {
                self::$reader = new Reader($path);
                self::$readerPath = $path;
            }
            $record = self::$reader->get((string) $request->ip());
        } catch (Throwable) {
            return null;
        }
        $iso = is_array($record) ? ($record['country']['iso_code'] ?? null) : null;

        return is_string($iso) && $iso !== '' ? strtoupper($iso) : null;
    }
}
