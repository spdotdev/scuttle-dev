# scuttle.dev cutover runbook

Go-live: move scuttle.dev from GitHub Pages (static repo) to the sd-admin app
on the DigitalOcean server. Nothing here is automated yet.

1. Provision `d051` (DigitalOcean). See the `d0-admin` project.
2. Deploy sd-admin (with `spdotdev/scuttle-dev` required) to the server.
3. In the **server** `.env` (never git): set `SCUTTLE_DOMAIN=scuttle.dev`.
4. Run `php artisan vendor:publish --tag=scuttle-dev-assets` on the server.
5. Smoke-test against the droplet IP:
   `curl --resolve scuttle.dev:443:<DROPLET_IP> https://scuttle.dev/`.
6. Switch the `scuttle.dev` **DNS A record** from the GitHub Pages IPs to the
   droplet IP; verify TLS.
7. Once stable, archive the static repo `spdotdev/scuttledev`.
