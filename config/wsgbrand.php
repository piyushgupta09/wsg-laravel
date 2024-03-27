<?php

return [

    // Base URL for API requests,
    'url' => env('WSG_BRAND_URL', 'http://localhost:9000'),

    // API Token for secure access
    'token' => '1234567890',

    // Base URL for API requests,
    'base_url' => env('WSG_BRAND_URL', 'http://localhost:9000') . '/api/sync',
    
    // WHAT I TAKE FROM MONAAL (using Actions)
    // API Endpoints for synchronization operations
    'api' => [
        'sync' => [
            'products_count' => '/products_count',
            'products' => '/products', 
        ],
    ],
];
