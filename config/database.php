<?php

return [
    'host' => $_ENV['PGHOST'] ?? 'localhost',
    'port' => $_ENV['PGPORT'] ?? '5432',
    'database' => $_ENV['PGDATABASE'] ?? 'restaurant_db',
    'username' => $_ENV['PGUSER'] ?? 'postgres',
    'password' => $_ENV['PGPASSWORD'] ?? '',
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
