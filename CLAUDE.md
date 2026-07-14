# CLAUDE.md — scuttle-dev

## What this is
A Laravel **library package** (not an app) serving the scuttle.dev single-page
site (plus legal PDFs, vCard, QR codes, SEO files) inside a host app via
host-based routing.

## Constraints
- Host has no PHP/Composer. Run package commands via a one-off Docker image
  (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`) or the host app's
  `app` container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → bump `spdotdev/scuttle-dev` in the host app's own `composer.lock`, commit + push (never run a bare `composer update` directly on the production server — that's invisible to the committed lock and gets silently reverted by the next unrelated deploy).

## Layout
- `src/ScuttleDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('scuttle-dev.domain'))` group: `/`, plus `/robots.txt` + `/sitemap.xml` at root.
- `resources/views/site.blade.php` — ported near-verbatim from the static site; asset refs rewritten to `vendor/scuttle/`.
- `config/scuttle-dev.php` — `domain` via `SCUTTLE_DOMAIN`.
- `public/` — all site assets (images, qr/, legal/*.pdf, vCard, manifest, robots, sitemap) published to the host's `public/vendor/scuttle`.
- `.env.example` — documents env vars this package reads (currently just `SCUTTLE_DOMAIN`); all have safe defaults, so it's optional, not required for install. Copy relevant lines into the host app's own `.env`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover. See `docs/cutover-runbook.md`.
