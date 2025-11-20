<?php

declare(strict_types=1);

load_env(BASE_PATH . '/.env');

return [
    'name' => env('APP_NAME', 'BridgeBoard'),
    'env' => env('APP_ENV', 'local'),
    'debug' => filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'url' => env('APP_URL', 'http://localhost'),
];
