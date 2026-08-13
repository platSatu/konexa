<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Teleios Backend — outbound
    |--------------------------------------------------------------------------
    |
    | Base URL and shared secret for the Teleios backend's public
    | /api/frontend/* catalog endpoints (category-applications, packages
    | — see App\Services\TeleiosApiService). The key must match
    | FRONTEND_API_KEY in the Teleios app's own .env exactly. Both apps
    | run as separate `php artisan serve` processes on localhost during
    | development, on different ports (see this app's SERVER_PORT vs
    | Teleios' SERVER_PORT) since they can't both bind to the default
    | :8000.
    |
    */

    'teleios' => [
        'url' => env('TELEIOS_API_URL', 'http://localhost:8000'),
        'key' => env('TELEIOS_API_KEY'),
    ],

];
