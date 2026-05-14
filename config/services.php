<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ============================================================
    // SERVICE 1: GITHUB API
    // ============================================================
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI', 'http://127.0.0.1:8000/auth/github/callback'),
        'api_url' => 'https://api.github.com',
    ],

    // ============================================================
    // SERVICE 2: GATEWAY LOSS API
    // ============================================================
    'gateway' => [
        'api_url' => env('GATEWAY_API_URL', 'http://127.0.0.1:8000/api'),
        'timeout' => 30,
        'retry_times' => 3,
    ],

];