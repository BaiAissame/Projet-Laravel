<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour optimiser les performances de l'application
    |
    */

    'cache' => [
        'views' => env('CACHE_VIEWS', true),
        'config' => env('CACHE_CONFIG', true),
        'routes' => env('CACHE_ROUTES', true),
        'database' => env('CACHE_DATABASE', true),
    ],

    'database' => [
        'strict' => env('DB_STRICT', false),
        'engine' => env('DB_ENGINE', 'InnoDB'),
        'query_log' => env('DB_QUERY_LOG', false),
    ],

    'assets' => [
        'minify' => env('MINIFY_ASSETS', true),
        'version' => env('ASSETS_VERSION', false),
        'cdn' => env('USE_CDN', false),
    ],

    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120),
        'secure' => env('SESSION_SECURE_COOKIE', false),
    ],

    'optimizations' => [
        'eager_loading' => true,
        'query_optimization' => true,
        'index_optimization' => true,
        'cache_optimization' => true,
    ],
]; 