<?php

namespace Spdotdev\ScuttleDev;

use Illuminate\Http\Request;

/**
 * Decides whether a visitor gets the one-time offer "this page is also in Dutch".
 *
 * Signal: the browser's own language list (Accept-Language). A Dutch browser language first means a
 * Dutch-speaking visitor. IP country is not used: this server has no GeoIP lookup and no CDN country header.
 * The offer never forces a redirect (Google, "Localized versions": let the user switch), and it is
 * never shown once the visitor has chosen, because the choice is stored in the cookie scuttle_lang.
 */
final class LanguageOffer
{
    public const COOKIE = 'scuttle_lang';

    public static function shouldOffer(Request $request, bool $paired, bool $isNl): bool
    {
        if (! $paired || $isNl) {
            return false;
        }
        if ($request->cookie(self::COOKIE) !== null) {
            return false;
        }

        return $request->getPreferredLanguage(['nl', 'en']) === 'nl';
    }
}
