# scuttle-dev

The scuttle.dev site, packaged as a Laravel library for inclusion in a host
Laravel application. Host-based routing serves the site on the configured
domain alongside the host app's own routes.

What it serves: the home page, twelve service pages with prices, pricing, the
Health Check and its sample report, case studies, news (built in, plus articles
from the optional sd-cms package) with an Atom feed, projects with their
documentation, a company page, the contact form (stored in the database, then
mailed), the privacy statement, the vCard, the legal PDFs, `robots.txt` and the
sitemap. Every paired page also exists in Dutch under `/nl`. Content pages set
no cookie. The source is public to be read, the licence is proprietary (see
`LICENSE`).

## Requirements

- PHP 8.3+
- A host app on Laravel 13 (`illuminate/support ^13.0`)

## Install

```jsonc
// composer.json
"repositories": [
    { "type": "vcs", "url": "https://github.com/spdotdev/scuttle-dev" }
],
"require": {
    "spdotdev/scuttle-dev": "^0.1"
}
```

```bash
composer update spdotdev/scuttle-dev
php artisan vendor:publish --tag=scuttle-dev-assets
```

The service provider is auto-discovered. It registers the routes of the site
on the configured domain (see `routes/web.php`), loads its migration for the
contact leads table (`php artisan migrate`) and gives the host's 404 on that
domain the site's own page. The assets tag copies `public/` (images, CSS, JS, legal PDFs, QR codes, vCard,
manifest) to the host's `public/vendor/scuttle`.

## Configuration

This package is a library, not a standalone app, so it ships no `.env` of
its own, it reads config from whatever app installs it. See
[`.env.example`](.env.example) for the full list of variables it supports
and their defaults; copy the ones you want to override into the **host
application's** `.env`.

| Variable | Default | Purpose |
|---|---|---|
| `SCUTTLE_DOMAIN` | `scuttle.dev` | Host this package's routes answer on (`Route::domain(...)`). |
| `SCUTTLE_BOOKING_URL` | `https://plan.scuttle.dev/book/stas` | The booking page behind every "Book a call" button. |
| `SCUTTLE_CONTACT_TO` | `info@scuttle.dev` | Where the contact form delivers. |
| `SCUTTLE_CONTACT_MAIL_DSN` | empty | Own Symfony mailer DSN for the contact form (for example `smtp://user:app-password@smtp.gmail.com:587`), for a host whose default mailer is `log`. Keep it in the host's `.env`. |
| `SCUTTLE_CONTACT_MAIL_FROM` | empty | The address the contact mail is sent from (must be one the SMTP account may send as). |
| `SCUTTLE_REQUIRE_REAL_MAILER` | `true` | The form refuses to say "Sent" when the default mailer is `log` or `array`. Set `false` only in tests. |
| `SCUTTLE_TURNSTILE_SITE_KEY` | empty | Cloudflare Turnstile site key. Empty: honeypot plus a signed minimum fill time. |
| `SCUTTLE_TURNSTILE_SECRET` | empty | Cloudflare Turnstile secret. Needs `frame-src https://challenges.cloudflare.com` in the host's CSP. |
| `SCUTTLE_GA_ID` | empty | Google Analytics id. Empty: no analytics and no cookie banner. |
| `SCUTTLE_ANALYTICS_KEY` | empty | Site key in sd-analytics (cookieless counting). Empty: no counting, and the privacy statement says so. |
| `SCUTTLE_GEOIP_DB` | empty | Path to the free DB-IP Lite country file (`dbip-country-lite-YYYY-MM.mmdb`, CC BY 4.0, no account). Empty: no IP country, the Dutch offer uses the browser language only. |

Every variable has a safe default baked into `config/scuttle-dev.php`, so
the package works out of the box even if none of these are set:
`.env.example` only documents the overrides available to you. If you want
the config file itself editable in the host app, publish it:

```bash
php artisan vendor:publish --tag=scuttle-dev-config
```

## Changes

See [`CHANGELOG.md`](CHANGELOG.md).

## Upgrading

Bump the git tag here (`vX.Y.Z`), then in the host application:

```bash
composer update spdotdev/scuttle-dev
php artisan vendor:publish --tag=scuttle-dev-assets --force
```

Commit the host app's updated `composer.lock`. `--force` is needed so changed
assets overwrite the previously published copies.

## Development

```bash
composer install
composer test                 # phpunit
./vendor/bin/pint --test      # code style
./vendor/bin/phpstan analyse  # static analysis
```

Live checks, run against a dump of the pages or the live site (Playwright and a
Chromium needed for the browser ones):

```bash
SCUTTLE_DUMP=/tmp/scuttle-dump vendor/bin/phpunit tests/Feature/PreviewDumpTest.php   # every page as static HTML
python3 scripts/check-live.py https://scuttle.dev        # sitemap, hreflang, internal and outbound links (stdlib only)
python3 scripts/html-check.py /tmp/scuttle-dump          # html5lib and tag balance
python3 scripts/axe-check.py https://scuttle.dev paths.txt        # axe-core, light and dark, 390 px
python3 scripts/overflow-check.py https://scuttle.dev paths.txt   # sideways scroll at 360 px
python3 scripts/nojs-check.py https://scuttle.dev paths.txt       # no scripts
python3 scripts/keyboard-check.py https://scuttle.dev paths.txt   # keyboard stops and rings
```

`paths.txt` has one address path per line. CI runs the same three checks on PHP 8.3 for pushes to `main` and pull requests.
`composer audit` runs in the security workflow (same triggers plus weekly). Pushing
a `vX.Y.Z` tag checks that the tag is on `main`, then runs all of it again plus
`composer validate --strict`.
