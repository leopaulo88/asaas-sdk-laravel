<?php

// config for Leopaulo88/AsaasSdkLaravel
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
    |
    */
    'environment' => env('ASAAS_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | The base URLs for different environments.
    |
    */
    'api_urls' => [
        'sandbox' => 'https://sandbox.asaas.com/api/v3',
        'production' => 'https://www.asaas.com/api/v3',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for HTTP requests to the Asaas API.
    |
    */
    'timeout' => (int) env('ASAAS_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Rate Limits
    |--------------------------------------------------------------------------
    |
    | Rate limiting configurations for API requests.
    |
    */
    'rate_limit' => [
        'enabled' => (bool) env('ASAAS_RATE_LIMIT_ENABLED', true),
        'requests_per_minute' => (int) env('ASAAS_RATE_LIMIT_RPM', 500),
        'burst_limit' => (int) env('ASAAS_RATE_LIMIT_BURST', 100),
    ],
];
