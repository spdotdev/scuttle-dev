# scuttle-dev Laravel Package Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development. Steps use checkbox (`- [ ]`) syntax.

**Goal:** Build `spdotdev/scuttle-dev`, a versioned Laravel package that serves the scuttle.dev single-page site (plus its legal PDFs, vCard, QR codes, and SEO files), and wire it into `the host app` via host-based routing — mirroring the proven `spdotdev/splotnikov-dev` pattern.

**Architecture:** A Composer `library` package (PSR-4 `Spdotdev\ScuttleDev\`) whose auto-discovered provider registers a `Route::domain(config('scuttle-dev.domain'))` group rendering the ported `index.html`. All site assets are namespaced under `public/vendor/scuttle/` (multi-site host: a shared `public/` root would collide with other site packages). `robots.txt` + `sitemap.xml` are served at the domain root via controller (crawler convention). the host app installs it via GitHub VCS + git tag.

**Tech Stack:** PHP 8.3+, Laravel 13 (`illuminate/support ^13`), Composer (VCS + tags), Docker (the host app `app` container; one-off `composer:2` for package commands), Pint, Larastan L5, PHPUnit via orchestra/testbench.

**Reference:** This mirrors `~/splotnikov-dev` (spec at `~/splotnikov-dev/docs/superpowers/specs/2026-06-22-splotnikov-dev-laravel-package-design.md`). All the `splotnikov-dev` v0.1.1 learnings are folded in here so scuttle-dev is correct on its first tag: testbench for Larastan bootstrap, package PHPUnit suite, SEO root routes, host-scoped landing already in the host app, no global static SEO files in the host.

## Global Constraints

- Package **`spdotdev/scuttle-dev`**, `type: library`, namespace **`Spdotdev\ScuttleDev\`**, PSR-4 root `src/`.
- PHP **`^8.3`**; **`illuminate/support: ^13.0`** (match the host app's Laravel 13).
- Distribution **GitHub VCS + git tags only — no Packagist**. First tag **`v0.1.0`**. Repo **public**, owner `spdotdev`.
- **Host has no PHP/Composer.** Use the the host app container (`make composer`, `make art`, `docker compose exec app ...` from `/home/dev/<host-app>`) or one-off: `docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 <cmd>`.
- The static repo **`/home/dev/scuttledev` is never modified or committed to**. Copy assets FROM it.
- Package working dir: **`/home/dev/scuttle-dev`** (exists; contains only `docs/`, `.superpowers/`).
- Asset namespace: all site assets published to **`public/vendor/scuttle/`**; views reference them via **`{{ asset('vendor/scuttle/...') }}`** (host-adaptive).
- Domain config: `config('scuttle-dev.domain')` ← `env('SCUTTLE_DOMAIN', 'scuttle.dev')`.
- Marker string for assertions: **`Scuttle Development`**.
- Ship **all generated artifacts** (per user): all root images + favicons + og-image, `site.webmanifest`, `robots.txt`, `sitemap.xml`, the vCard, all `public/legal/*.pdf`, and the entire `qr/` tree. Do NOT carry the markdown sources or `scripts/generate-pdfs.js` (sources, not artifacts).

---

### Task 1: Package skeleton + tooling

**Files:** Create `composer.json`, `.gitignore`, `pint.json`, `phpstan.neon`, `phpunit.xml` in `/home/dev/scuttle-dev/`.

- [ ] **Step 1: `composer.json`**

```json
{
    "name": "spdotdev/scuttle-dev",
    "description": "scuttle.dev site, packaged for the the host app host app.",
    "type": "library",
    "license": "proprietary",
    "require": {
        "php": "^8.3",
        "illuminate/support": "^13.0"
    },
    "require-dev": {
        "larastan/larastan": "^3.0",
        "laravel/pint": "^1.27",
        "orchestra/testbench": "^11.1"
    },
    "autoload": {
        "psr-4": {
            "Spdotdev\\ScuttleDev\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Spdotdev\\ScuttleDev\\Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": "@php vendor/bin/phpunit"
    },
    "extra": {
        "laravel": {
            "providers": [
                "Spdotdev\\ScuttleDev\\ScuttleDevServiceProvider"
            ]
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true,
    "config": {
        "sort-packages": true,
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

- [ ] **Step 2: `.gitignore`**

```
/vendor/
composer.lock
.phpunit.result.cache
.DS_Store
/.idea
/.vscode
```

- [ ] **Step 3: `pint.json`** → `{ "preset": "laravel" }`

- [ ] **Step 4: `phpstan.neon`**

```
includes:
    - vendor/larastan/larastan/extension.neon
parameters:
    level: 5
    paths:
        - src
```

- [ ] **Step 5: `phpunit.xml`**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

- [ ] **Step 6: Validate** — `docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 validate --no-check-publish` → "valid" (missing-lock warning OK).

- [ ] **Step 7: git init + commit**
```bash
cd /home/dev/scuttle-dev
git init -q
git add composer.json .gitignore pint.json phpstan.neon phpunit.xml docs
git commit -q -m "chore: package skeleton and tooling config"
```
Do NOT add `.superpowers/`.

---

### Task 2: Collect assets and port the page

**Files:** Create `resources/views/site.blade.php` (from `/home/dev/scuttledev/index.html`) and populate `public/`.

- [ ] **Step 1: Copy the page**
```bash
cd /home/dev/scuttle-dev
mkdir -p resources/views public
cp /home/dev/scuttledev/index.html resources/views/site.blade.php
```

- [ ] **Step 2: Rewrite asset references to the vendor namespace** (root-absolute asset paths → host-adaptive `asset()`):
```bash
cd /home/dev/scuttle-dev
sed -i -E "s#(href|src|content)=\"/(profile\.png|logo-bw\.png|logo\.png|og-image\.png|hero-bg\.png|founder_portrait\.png|favicon-16x16\.png|favicon-32x32\.png|android-chrome-192x192\.png|android-chrome-512x512\.png|apple-touch-icon\.png|site\.webmanifest|qr/[^\"]+|legal/[^\"]+)\"#\1=\"{{ asset('vendor/scuttle/\2') }}\"#g" resources/views/site.blade.php
sed -i -E "s#href=\"scuttledev\.vcf\"#href=\"{{ asset('vendor/scuttle/scuttledev.vcf') }}\"#g" resources/views/site.blade.php
```

- [ ] **Step 3: Verify the rewrite**
```bash
cd /home/dev/scuttle-dev
echo "marker:" $(grep -c 'Scuttle Development' resources/views/site.blade.php)
echo "remaining root-absolute asset refs (expect none):"
grep -noE '(href|src|content)="/(profile|logo|og-image|hero-bg|founder|favicon|android-chrome|apple-touch|site\.webmanifest|qr/|legal/)[^"]*"' resources/views/site.blade.php || echo NONE
echo "raw scuttledev.vcf (expect none):"; grep -n 'href="scuttledev.vcf"' resources/views/site.blade.php || echo NONE
echo "asset() count:" $(grep -c "asset('vendor/scuttle/" resources/views/site.blade.php)
```
Expected: marker ≥1; "NONE" for both remaining-ref checks; asset() count ≥ 10. If marker is 0, STOP (BLOCKED — wrong source copied).

- [ ] **Step 4: Collect all generated assets into `public/`** (preserve `legal/` and `qr/` subtrees):
```bash
cd /home/dev/scuttle-dev
SRC=/home/dev/scuttledev
# root images, favicons, og, manifest, seo
cp "$SRC"/profile.png "$SRC"/logo-bw.png "$SRC"/logo.png "$SRC"/og-image.png \
   "$SRC"/hero-bg.png "$SRC"/founder_portrait.png \
   "$SRC"/favicon-16x16.png "$SRC"/favicon-32x32.png \
   "$SRC"/android-chrome-192x192.png "$SRC"/android-chrome-512x512.png \
   "$SRC"/apple-touch-icon.png "$SRC"/site.webmanifest "$SRC"/robots.txt "$SRC"/sitemap.xml public/
# vcard + legal pdfs
cp "$SRC"/public/scuttledev.vcf public/
mkdir -p public/legal && cp "$SRC"/public/legal/*.pdf public/legal/
# full qr tree
cp -r "$SRC"/qr public/qr
```

- [ ] **Step 5: Verify assets present**
```bash
cd /home/dev/scuttle-dev
echo "legal pdfs:" $(ls public/legal/*.pdf | wc -l)
echo "qr files:" $(find public/qr -type f | wc -l)
ls public/*.png public/*.webmanifest public/*.txt public/*.xml public/*.vcf
```
Expected: 4 legal pdfs, qr files > 0, all listed files present. Note: `site.webmanifest` ships with empty `icons: []` (no internal paths to rewrite — verified in design).

- [ ] **Step 6: Commit**
```bash
cd /home/dev/scuttle-dev
git add resources public
git commit -q -m "feat: port scuttle.dev page and collect site assets"
```

---

### Task 3: Service provider, config, routes, controller

**Files:** Create `config/scuttle-dev.php`, `routes/web.php`, `src/Http/Controllers/SiteController.php`, `src/ScuttleDevServiceProvider.php`.

- [ ] **Step 1: `config/scuttle-dev.php`**
```php
<?php

return [
    // Host that this site answers on. Override per-environment with
    // SCUTTLE_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SCUTTLE_DOMAIN', 'scuttle.dev'),
];
```

- [ ] **Step 2: `src/Http/Controllers/SiteController.php`**
```php
<?php

namespace Spdotdev\ScuttleDev\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;

class SiteController
{
    public function index(): View
    {
        // @phpstan-ignore argument.type (the scuttle:: namespace is registered at runtime via loadViewsFrom, so it is not resolvable during package-only static analysis)
        return view('scuttle::site');
    }

    public function robots(): Response
    {
        return $this->staticFile('robots.txt', 'text/plain');
    }

    public function sitemap(): Response
    {
        return $this->staticFile('sitemap.xml', 'application/xml');
    }

    /**
     * Serve a shipped static file from the package's public/ directory at the
     * site root (the published vendor path is not at the web root, so crawler
     * files must be routed explicitly).
     */
    private function staticFile(string $name, string $contentType): Response
    {
        $path = __DIR__.'/../../../public/'.$name;
        abort_unless(is_file($path), 404);

        return response((string) file_get_contents($path), 200, ['Content-Type' => $contentType]);
    }
}
```

- [ ] **Step 3: `routes/web.php`**
```php
<?php

use Illuminate\Support\Facades\Route;
use Spdotdev\ScuttleDev\Http\Controllers\SiteController;

Route::domain(config('scuttle-dev.domain'))
    ->middleware('web')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('scuttle.home');

        // Crawler files served at the site root.
        Route::get('/robots.txt', [SiteController::class, 'robots'])->name('scuttle.robots');
        Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('scuttle.sitemap');
    });
```

- [ ] **Step 4: `src/ScuttleDevServiceProvider.php`**
```php
<?php

namespace Spdotdev\ScuttleDev;

use Illuminate\Support\ServiceProvider;

class ScuttleDevServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scuttle-dev.php', 'scuttle-dev');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'scuttle');

        $this->publishes([
            __DIR__.'/../config/scuttle-dev.php' => config_path('scuttle-dev.php'),
        ], 'scuttle-dev-config');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/scuttle'),
        ], 'scuttle-dev-assets');
    }
}
```

- [ ] **Step 5: Lint** — `docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 sh -c "find src config routes -name '*.php' -print0 | xargs -0 -n1 php -l"` → all "No syntax errors detected".

- [ ] **Step 6: Commit**
```bash
cd /home/dev/scuttle-dev
git add src config routes
git commit -q -m "feat: service provider, config, domain routes, controller"
```

---

### Task 4: CI, docs, and tests

**Files:** Create `.github/workflows/ci.yml`, `README.md`, `CLAUDE.md`, `docs/cutover-runbook.md`, `tests/TestCase.php`, `tests/Feature/SiteTest.php`.

- [ ] **Step 1: `.github/workflows/ci.yml`**
```yaml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:

jobs:
  quality:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, dom, xml
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist

      - name: Pint (style check)
        run: ./vendor/bin/pint --test

      - name: Larastan (static analysis)
        run: ./vendor/bin/phpstan analyse --no-progress --memory-limit=512M

      - name: Tests
        run: ./vendor/bin/phpunit
```

- [ ] **Step 2: `tests/TestCase.php`**
```php
<?php

namespace Spdotdev\ScuttleDev\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spdotdev\ScuttleDev\ScuttleDevServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [ScuttleDevServiceProvider::class];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // The package routes run in the `web` group, whose cookie encryption
        // requires an application key. Set a deterministic one for tests.
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }
}
```

- [ ] **Step 3: `tests/Feature/SiteTest.php`**
```php
<?php

namespace Spdotdev\ScuttleDev\Tests\Feature;

use Spdotdev\ScuttleDev\Tests\TestCase;

class SiteTest extends TestCase
{
    public function test_homepage_renders_on_the_configured_host(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertOk()
            ->assertSee('Scuttle Development');
    }

    public function test_robots_txt_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');
    }

    public function test_sitemap_xml_is_served_at_the_root(): void
    {
        $this->get('http://scuttle.dev/sitemap.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_page_references_namespaced_assets(): void
    {
        $this->get('http://scuttle.dev/')
            ->assertSee('vendor/scuttle/', false);
    }
}
```

- [ ] **Step 4: `README.md`** (install into the host app)
````markdown
# scuttle-dev

The scuttle.dev site, packaged as a Laravel library for the `the host app` host
application. Host-based routing serves the site on the configured domain.

## Install (in the host app)

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
make composer cmd="update spdotdev/scuttle-dev"
make art cmd="vendor:publish --tag=scuttle-dev-assets"
```

Set the host it answers on (defaults to `scuttle.dev`):

```dotenv
SCUTTLE_DOMAIN=scuttle.dev
```

## Upgrading

Bump the git tag here (`vX.Y.Z`), then in the host app run
`make composer cmd="update spdotdev/scuttle-dev"`.
````

- [ ] **Step 5: `CLAUDE.md`**
```markdown
# CLAUDE.md — scuttle-dev

## What this is
A Laravel **library package** (not an app) serving the scuttle.dev single-page
site (plus legal PDFs, vCard, QR codes, SEO files) inside the `the host app` host
app via host-based routing.

## Constraints
- Host has no PHP/Composer. Run package commands via a one-off Docker image
  (`docker run --rm -v "$PWD":/app -w /app composer:2 ...`) or the the host app
  `app` container once installed.
- Distribution is GitHub VCS + git tags only. No Packagist.
- Versioned: change behaviour → bump tag (`vX.Y.Z`) → `composer update` in the host app.

## Layout
- `src/ScuttleDevServiceProvider.php` — auto-discovered; loads routes + views, publishes config/assets.
- `routes/web.php` — `Route::domain(config('scuttle-dev.domain'))` group: `/`, plus `/robots.txt` + `/sitemap.xml` at root.
- `resources/views/site.blade.php` — ported near-verbatim from the static site; asset refs rewritten to `vendor/scuttle/`.
- `config/scuttle-dev.php` — `domain` via `SCUTTLE_DOMAIN`.
- `public/` — all site assets (images, qr/, legal/*.pdf, vCard, manifest, robots, sitemap) published to the host's `public/vendor/scuttle`.

## Deferred
DigitalOcean provisioning, live deploy, and the DNS A-record cutover. See `docs/cutover-runbook.md`.
```

- [ ] **Step 6: `docs/cutover-runbook.md`**
```markdown
# scuttle.dev cutover runbook

Go-live: move scuttle.dev from GitHub Pages (static repo) to the the host app app
on the DigitalOcean server. Nothing here is automated yet.

1. Provision `the server` (DigitalOcean). See the `d0-admin` project.
2. Deploy the host app (with `spdotdev/scuttle-dev` required) to the server.
3. In the **server** `.env` (never git): set `SCUTTLE_DOMAIN=scuttle.dev`.
4. Run `php artisan vendor:publish --tag=scuttle-dev-assets` on the server.
5. Smoke-test against the droplet IP:
   `curl --resolve scuttle.dev:443:<DROPLET_IP> https://scuttle.dev/`.
6. Switch the `scuttle.dev` **DNS A record** from the GitHub Pages IPs to the
   droplet IP; verify TLS.
7. Once stable, archive the static repo `spdotdev/scuttledev`.
```

- [ ] **Step 7: Run the full local gate**
```bash
cd /home/dev/scuttle-dev
docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 install --no-interaction --no-progress
docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 ./vendor/bin/pint --test
docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 ./vendor/bin/phpstan analyse --no-progress --memory-limit=512M
docker run --rm -v /home/dev/scuttle-dev:/app -w /app composer:2 php -d memory_limit=512M vendor/bin/phpunit
```
Expected: Pint PASS, Larastan "No errors", PHPUnit all green. If Pint flags style, run it in fix mode (`./vendor/bin/pint`) and re-commit. If a route 500s, inspect the Blade error (CSS `@`-rule) and escape narrowly.

- [ ] **Step 8: Commit**
```bash
cd /home/dev/scuttle-dev
git add .github README.md CLAUDE.md docs/cutover-runbook.md phpunit.xml tests
git commit -q -m "docs+ci+tests: CI, README, CLAUDE.md, runbook, testbench suite"
```

---

### Task 5: Publish to GitHub and tag v0.1.0 — OUTWARD-FACING (pause for user OK)

- [ ] **Step 1:** `cd /home/dev/scuttle-dev && gh repo create spdotdev/scuttle-dev --public --source=. --remote=origin` then rename branch to `main` if needed (`git branch -m master main`) and `git push -u origin main`.
- [ ] **Step 2:** `git tag -a v0.1.0 -m "v0.1.0 — initial scuttle.dev site package" && git push origin v0.1.0`.
- [ ] **Step 3:** Verify `git ls-remote --tags origin` shows `v0.1.0` and `gh repo view spdotdev/scuttle-dev --json visibility` is PUBLIC.

---

### Task 6: Wire into the host app and verify — OUTWARD-FACING (pause for user OK)

**Files (in `/home/dev/<host-app>`, on a feature branch):** modify `composer.json`, `.env.example`/`.env`; add `tests/Feature/ScuttleSiteTest.php`. (Landing route already host-scoped; host already drops global static SEO files — no further routing changes needed.)

- [ ] **Step 1:** `cd /home/dev/<host-app> && git checkout -b feat/scuttle-dev-site-package`.
- [ ] **Step 2: failing test** — create `tests/Feature/ScuttleSiteTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ScuttleSiteTest extends TestCase
{
    public function test_homepage_renders_on_the_scuttle_host(): void
    {
        $this->get('http://scuttle.dev/')->assertOk()->assertSee('Scuttle Development');
    }

    public function test_seo_files_served_at_scuttle_root(): void
    {
        $this->get('http://scuttle.dev/robots.txt')->assertOk()->assertSee('Sitemap:');
        $this->get('http://scuttle.dev/sitemap.xml')->assertOk()->assertSee('<urlset', false);
    }
}
```
Run `make art cmd="test --filter=ScuttleSiteTest"` → FAIL (404, package not required yet).

- [ ] **Step 3: require** — add the VCS repo + require:
```bash
cd /home/dev/<host-app>
make composer cmd="config repositories.scuttle-dev vcs https://github.com/spdotdev/scuttle-dev"
make composer cmd="require spdotdev/scuttle-dev:^0.1"
```

- [ ] **Step 4: publish assets** — `make art cmd="vendor:publish --tag=scuttle-dev-assets --force"`; confirm `public/vendor/scuttle/` populated (legal/, qr/, images).

- [ ] **Step 5: env** — append to `.env.example` and ensure in `.env`:
```dotenv

# Host that the scuttle-dev site package answers on.
SCUTTLE_DOMAIN=scuttle.dev
```
Then `make art cmd="config:clear"`.

- [ ] **Step 6: run tests** — `make art cmd="test --filter=ScuttleSiteTest"` → PASS. Then full suite `make art cmd="test"` → all pass (splotnikov + landing + scuttle).

- [ ] **Step 7: e2e via nginx**
```bash
cd /home/dev/<host-app>
curl -s -H 'Host: scuttle.dev' http://localhost:8080/ | grep -c 'Scuttle Development'
curl -s -o /dev/null -w '%{http_code} %{content_type}\n' -H 'Host: scuttle.dev' http://localhost:8080/robots.txt
curl -s -o /dev/null -w '%{http_code}\n' -H 'Host: scuttle.dev' http://localhost:8080/vendor/scuttle/profile.png
curl -s -o /dev/null -w '%{http_code}\n' -H 'Host: scuttle.dev' http://localhost:8080/vendor/scuttle/legal/TERMS_AND_CONDITIONS.pdf
# splotnikov + the host app unaffected:
curl -s -H 'Host: splotnikov.dev' http://localhost:8080/ | grep -c 'Stanislav Plotnikov'
curl -s http://localhost:8080/ | grep -c 'the host app is live'
```
Expected: scuttle homepage marker ≥1; robots 200 text/plain; profile.png + legal pdf 200; splotnikov + the host app still correct.

- [ ] **Step 8: Pint + Larastan + commit**
```bash
cd /home/dev/<host-app>
docker compose exec app ./vendor/bin/pint
docker compose exec app ./vendor/bin/phpstan analyse --memory-limit=512M
git add composer.json composer.lock .env.example tests/Feature/ScuttleSiteTest.php
git commit -m "feat: mount scuttle-dev site package via host-based routing"
```
Do NOT commit `.env` or `public/vendor/scuttle` (gitignored / republished on deploy).

---

## Self-Review

- Versioned package, library, namespace → Task 1. Content parity (single page + assets) → Task 2. Host-based routing + SEO root → Task 3. CI + tests + docs → Task 4. Publish → Task 5. the host app wiring + verify → Task 6.
- Multi-site: assets namespaced under `vendor/scuttle`; only robots/sitemap at root; landing already host-scoped; no global static SEO in host. ✓
- All `splotnikov-dev` v0.1.1 learnings folded in (testbench, tests, SEO routes, host-adaptive `asset()`). ✓
- Static `scuttledev` repo untouched. ✓
- Names consistent: `spdotdev/scuttle-dev`, `Spdotdev\ScuttleDev\ScuttleDevServiceProvider`, `SiteController::{index,robots,sitemap}`, view ns `scuttle::`, config key `scuttle-dev`, tags `scuttle-dev-config`/`scuttle-dev-assets`, asset path `vendor/scuttle`, env `SCUTTLE_DOMAIN`, marker `Scuttle Development`. ✓
