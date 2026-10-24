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

## Marketing rules
Read `docs/marketing-rules.md` before writing or changing any page text, product, case study or call to action. It is Stas's list of what never goes on the site (zeros, overconfidence, client traces, old scare facts) and what always does (open prices, straight answers, what a product stands for). Add his new rules there with the date.

## Layout
- `src/ScuttleDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('scuttle-dev.domain'))` group: `/`, `/pricing`, `/legacy-health-check`, `/products/{slug}`, `/case-studies/phalcon-to-laravel`, `/privacy`, plus `/robots.txt` + `/sitemap.xml` at root.
- `src/Cases.php` — the case studies (one anonymized client case, three own products), rendered by `case-study.blade.php` at `/case-studies/{slug}` and in the home carousel. `src/Http/Controllers/ContactController.php` + `src/Mail/ContactMessage.php` — the contact form on `/contact` (POST `/contact`, throttle 6 a minute, honeypot plus a signed 3-second minimum, Cloudflare Turnstile when `SCUTTLE_TURNSTILE_SITE_KEY` and `SCUTTLE_TURNSTILE_SECRET` are set in the host's .env, mail to `SCUTTLE_CONTACT_TO`). `/scuttle.vcf` serves the vCard as text/vcard; `public/qr/scuttle-vcard.svg` is a QR code that carries the vCard itself (rebuild both with the script in the commit history of 2026-10-24 or by hand: vCard 3.0, CRLF, folded at 75 bytes, base64 photo).
- `src/Catalog.php` — the 12 products in three groups (Assess, Build, Run): names, leads, prices, features, steps. One source for the products mega menu, the home cards and tiles, the pricing page, the product pages and the footer. Prices: Health Check EUR 1,080 (12 h), Architecture Review EUR 1,440 (16 h), security quick scan EUR 720 (8 h), Maintenance Retainer from EUR 720 a month (8 h), everything else quoted per step at EUR 90 per hour excl. VAT; the pricing page shows two blocks of four. Every product carries its own closing call to action (`cta`). The Health Check keeps its own address (`route` key) and renders through the same `product` view. Adding a product: add it here and to `public/sitemap.xml`; the tests check both.
  Also `/mail/signature-logo.png`, the small logo in the CRM mail signature (white circle, 96 px), routed so it needs no asset publish on the server.
- `src/Http/Controllers/SiteController.php`: renders `scuttle::site` and serves `robots.txt` / `sitemap.xml` straight from the package's `public/`.
- `resources/views/layout.blade.php` — shared shell (nav with the Products mega menu, EN/NL switch on the home page, theme button, dark footer block, Dutch strings for the shell); every page extends it. `resources/views/site.blade.php` — the home page (light first, grey plates, Geist, logo 37 "layered squares", decrypt headline, belts, 12 tiles with the line illustrations from `partials/illus.blade.php`); its Dutch strings are pushed into the `nl` stack. `product.blade.php` renders every catalog product, `pricing.blade.php` the pricing page. `case-study` and `privacy` use the `.page` article styles. Sub pages are English only; the NL switch covers the home page and the shell.
- `public/site.css` + `public/site.js` — the only stylesheet and script. Accent token `--acc` (Claude orange `#CF5A26` light, `#E3835E` dark since v0.3.0; the comparison with indigo and grey is `design/drafts/accent-options.png`). `public/logo.svg` (wide mark), `public/icon.svg` (square mark, source of the favicons); regenerate every logo asset in a new colour with `design/make-logo.sh '<accent>' '<accent light>'` (needs rsvg-convert and ImageMagick). `public/portrait.jpg`, `public/og-image.png`. Logo and theme drafts live in `design/drafts/`.
- The demo panel on the home page (`#demo`) is interactive: the sidebar buttons in `site.blade.php` (data-k) select an entry of the `D` object in `site.js` (automation runs with a typed log, report pages with two readable pages and the rest blurred as confidential, anonymized cases with numbers). Runs and reports are examples with made-up names; the cases are anonymized real work, the cost-saving one is a worked example with illustrative numbers and says so. Panel content is English only.
- Theme: three-way control System / Light / Dark (`.seg.th`, System default; dark tokens apply through `prefers-color-scheme` when nothing is chosen and through `[data-theme="dark"]` when chosen).
- Hero command line in `site.blade.php` (`data-i="cmd_b"`, EN and the NL string) names the latest PHP security release (8.4.26 → 8.4.27, 21 Oct 2026, from php.net). Refresh it at every PHP security release; the test pins the current text.
- `tests/Feature/PreviewDumpTest.php` writes every page as static HTML when `SCUTTLE_DUMP=<dir>` is set (skipped otherwise), so the theme can be screenshotted without a PHP host.
- `config/scuttle-dev.php` — `domain` via `SCUTTLE_DOMAIN`.
- `public/` — all site assets (images, qr/, legal/*.pdf, vCard, manifest, robots, sitemap) published to the host's `public/vendor/scuttle`.
- `.env.example` — documents env vars this package reads (currently just `SCUTTLE_DOMAIN`); all have safe defaults, so it's optional, not required for install. Copy relevant lines into the host app's own `.env`.

## Cutover (done)
DigitalOcean provisioning, live deploy, and the DNS A-record cutover are done: scuttle.dev is served by the host app from this package (checked 2026-09-27, `/` returns 200 with `vendor/scuttle/` assets). `docs/cutover-runbook.md` is the record of how it was done.

## Deploy (set 2026-10-13, HARD RULE, user decision)
Production on d051 always runs the latest `main` of this project. When the user asks for a change and it is merged to main, deploy it yourself right away, without asking: push or merge, watch CI and the deploy to the end, and check the server runs that commit. If this project uses tags, deploy the latest tag every time. Anything not in main is local testing and is never deployed. A failing deploy is reported first as broken, with what it blocks. Never open PRs unless asked.
