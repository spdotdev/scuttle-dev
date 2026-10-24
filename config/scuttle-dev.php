<?php

return [
    // Host that this site answers on. Override per-environment with
    // SCUTTLE_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SCUTTLE_DOMAIN', 'scuttle.dev'),

    // The public booking page in sd-plan behind every "Book a call" button.
    'booking_url' => env('SCUTTLE_BOOKING_URL', 'https://plan.scuttle.dev/book/stas'),
];
