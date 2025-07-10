<?php

// config for Hubooai/Asaas
return [
    /*
    |--------------------------------------------------------------------------
    | Asaas API Key
    |--------------------------------------------------------------------------
    |
    | Your Asaas API key. You can obtain this key from the Asaas admin panel
    | at: https://www.asaas.com/
    |
    */
    'api_key' => env('ASAAS_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | The API environment. Use 'sandbox' for testing and 'production' for live.
    | Accepted values: 'sandbox', 'production'
    |
    */
    'environment' => env('ASAAS_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | Base URLs for Asaas APIs
    |
    */
    'api_urls' => [
        'sandbox' => 'https://sandbox.asaas.com/api/v3',
        'production' => 'https://www.asaas.com/api/v3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Default timeout for HTTP requests in seconds
    |
    */
    'timeout' => env('ASAAS_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Settings for request rate limiting
    |
    */
    'rate_limit' => [
        'enabled' => env('ASAAS_RATE_LIMIT_ENABLED', true),
        'max_requests' => env('ASAAS_RATE_LIMIT_MAX_REQUESTS', 100),
        'per_minute' => env('ASAAS_RATE_LIMIT_PER_MINUTE', 60),
    ],
];
