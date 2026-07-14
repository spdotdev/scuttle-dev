# scuttle.dev cutover runbook

> This file is the scuttle.dev-specific slice of the cutover to the
> Laravel-served version of the site. Nothing is live yet — DNS still points
> at GitHub Pages.

## Site facts

| | value |
|--|--|
| Domain | `scuttle.dev` |
| Static repo (live on Pages until cutover) | [`spdotdev/scuttledev`](https://github.com/spdotdev/scuttledev) |
| Site package (this repo) | `spdotdev/scuttle-dev` |
| Domain env var (host app `.env`) | `SCUTTLE_DOMAIN=scuttle.dev` |
| Assets path | `/vendor/scuttle/` (page, CSS `style.css`, JS `main.js`, legal PDFs, QR, vCard) |
| Publish tag | `scuttle-dev-assets` |
| Current DNS apex `A` | GitHub Pages `185.199.108–111.153` → change to `<DROPLET_IP>` |

## Cutover checklist (after the server + host app are up)

1. Confirm `spdotdev/scuttle-dev` is required in the host app and `SCUTTLE_DOMAIN=scuttle.dev` is set in the server `.env`.
2. `php artisan vendor:publish --tag=scuttle-dev-assets --force` on the server.
3. Verify against the droplet IP without changing DNS:
   `curl -s --resolve scuttle.dev:443:<DROPLET_IP> https://scuttle.dev/ | grep -o 'Scuttle Development'`
   plus `…/robots.txt`, `…/vendor/scuttle/style.css`, `…/vendor/scuttle/main.js`,
   `…/vendor/scuttle/legal/TERMS_AND_CONDITIONS.pdf` → all 200.
4. Lower the apex `A` TTL to 300s; remove the custom domain from the static repo's Pages settings.
5. Repoint the `scuttle.dev` apex `A` record from the Pages IPs to `<DROPLET_IP>`; remove Pages `AAAA`/`CNAME`.
6. Let TLS issue (Caddy auto / certbot), then verify `https://scuttle.dev/` in the open (theme toggle, QR/service modals depend on `main.js` loading).
7. Once stable, archive [`spdotdev/scuttledev`](https://github.com/spdotdev/scuttledev).

**Rollback:** revert the apex `A` to `185.199.108–111.153` and re-add the custom domain in Pages settings; the static repo is untouched and resumes within one TTL.
