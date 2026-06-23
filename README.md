# scuttle-dev

The scuttle.dev site, packaged as a Laravel library for the `sd-admin` host
application. Host-based routing serves the site on the configured domain.

## Install (in sd-admin)

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

Bump the git tag here (`vX.Y.Z`), then in sd-admin run
`make composer cmd="update spdotdev/scuttle-dev"`.
