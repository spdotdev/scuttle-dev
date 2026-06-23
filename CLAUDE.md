# CLAUDE.md — scuttle-dev

## What this is
A Laravel **library package** (not an app) serving the scuttle.dev single-page
site (plus legal PDFs, vCard, QR codes, SEO files) inside the `sd-admin` host
app via host-based routing.

## Constraints
- Host has no PHP/Composer. Run package commands via a one-off Docker image
  (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`) or the sd-admin
  `app` container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → `composer update` in sd-admin.

## Layout
- `src/ScuttleDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('scuttle-dev.domain'))` group: `/`, plus `/robots.txt` + `/sitemap.xml` at root.
- `resources/views/site.blade.php` — ported near-verbatim from the static site; asset refs rewritten to `vendor/scuttle/`.
- `config/scuttle-dev.php` — `domain` via `SCUTTLE_DOMAIN`.
- `public/` — all site assets (images, qr/, legal/*.pdf, vCard, manifest, robots, sitemap) published to the host's `public/vendor/scuttle`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover. See `docs/cutover-runbook.md`.
