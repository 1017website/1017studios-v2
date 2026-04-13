<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ── Google Places API ─────────────────────────────────────────────────
    'google' => [
        'places_api_key'        => env('GOOGLE_PLACES_API_KEY'),
        'place_id'              => env('GOOGLE_PLACE_ID'),
        'cache_ttl'             => env('GOOGLE_REVIEWS_CACHE_TTL', 60),
        'monthly_request_limit' => env('GOOGLE_REVIEWS_MONTHLY_LIMIT', 500),
    ],

];