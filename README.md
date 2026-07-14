# scuttle-dev

The scuttle.dev site, packaged as a Laravel library for inclusion in a host
Laravel application. Host-based routing serves the site on the configured
domain alongside the host app's own routes.

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
```
