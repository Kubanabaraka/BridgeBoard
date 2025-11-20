<?php
declare(strict_types=1);

const BASE_PATH = __DIR__;

require BASE_PATH . '/src/core/helpers.php';

$appConfig = require BASE_PATH . '/config/app.php';
$dbConfig = require BASE_PATH . '/config/database.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'BridgeBoard\\';
    $baseDir = BASE_PATH . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    $sessionName = env('SESSION_NAME', 'bridgeboard_session');
    if ($sessionName) {
        session_name($sessionName);
    }
    session_start();
}

BridgeBoard\Services\Database::configure($dbConfig);
BridgeBoard\Services\AuthService::bootstrap();

return [
    'app' => $appConfig,
    'db' => $dbConfig,
];
