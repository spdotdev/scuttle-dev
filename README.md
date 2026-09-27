# scuttle-dev

The scuttle.dev site, packaged as a Laravel library for inclusion in a host
Laravel application. Host-based routing serves the site on the configured
domain alongside the host app's own routes.

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

The service provider is auto-discovered. It registers these routes on the
configured domain: `/` (the site), `/robots.txt` and `/sitemap.xml`. The
assets tag copies `public/` (images, CSS, JS, legal PDFs, QR codes, vCard,
manifest) to the host's `public/vendor/scuttle`.

## Configuration

This package is a library, not a standalone app, so it ships no `.env` of
its own — it reads config from whatever app installs it. See
[`.env.example`](.env.example) for the full list of variables it supports
and their defaults; copy the ones you want to override into the **host
application's** `.env`.

| Variable | Default | Purpose |
|---|---|---|
| `SCUTTLE_DOMAIN` | `scuttle.dev` | Host this package's routes answer on (`Route::domain(...)`). |

Every variable has a safe default baked into `config/scuttle-dev.php`, so
the package works out of the box even if none of these are set —
`.env.example` only documents the overrides available to you. If you want
the config file itself editable in the host app, publish it:

```bash
php artisan vendor:publish --tag=scuttle-dev-config
```

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

CI runs the same three checks on PHP 8.3 for pushes to `main` and pull requests.
`composer audit` runs in the security workflow (same triggers plus weekly). Pushing
a `vX.Y.Z` tag checks that the tag is on `main`, then runs all of it again plus
`composer validate --strict`.
