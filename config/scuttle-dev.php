<?php

return [
    // Host that this site answers on. Override per-environment with
    // SCUTTLE_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SCUTTLE_DOMAIN', 'scuttle.dev'),

    // The public booking page in sd-plan behind every "Book a call" button.
    'booking_url' => env('SCUTTLE_BOOKING_URL', 'https://plan.scuttle.dev/book/stas'),

    // Where the contact form delivers.
    'contact_to' => env('SCUTTLE_CONTACT_TO', 'info@scuttle.dev'),

    // Cloudflare Turnstile (the bot check on the contact form). Without keys the
    // form falls back to a honeypot plus a signed minimum fill time.
    'turnstile_site_key' => env('SCUTTLE_TURNSTILE_SITE_KEY', ''),
    'turnstile_secret' => env('SCUTTLE_TURNSTILE_SECRET', ''),

    // Google Analytics measurement id (G-XXXX). Empty means no analytics and no
    // cookie banner. With an id, the script loads only after the visitor agrees.
    'ga_id' => env('SCUTTLE_GA_ID', ''),
];
