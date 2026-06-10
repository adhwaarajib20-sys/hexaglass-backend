<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',      // Local development frontend
        'http://localhost:8000',      // Local development backend
        'https://web-production-fc4fb.up.railway.app', // Production backend
        env('FRONTEND_URL'),          // Frontend URL from environment
    ],
    'allowed_origins_patterns' => [
        '/.*\.up\.railway\.app/',     // All Railway subdomains
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
