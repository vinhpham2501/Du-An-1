<?php

return [
    'app_name' => 'Restaurant Order System',
    'app_env' => $_ENV['APP_ENV'] ?? 'production',
    'app_debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    'app_url' => $_ENV['APP_URL'] ?? 'http://localhost',
    
    'database' => require __DIR__ . '/database.php',
    
    'session' => [
        'name' => 'restaurant_session',
        'lifetime' => 7200, // 2 hours
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'path' => '/public/uploads/'
    ]
];
