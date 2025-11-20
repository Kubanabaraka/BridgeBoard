<?php

declare(strict_types=1);

namespace BridgeBoard\Services;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;
    private static array $config = [];

    private function __construct()
    {
    }

    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        if (empty(self::$config)) {
            throw new RuntimeException('Database configuration missing.');
        }

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            self::$config['driver'] ?? 'mysql',
            self::$config['host'] ?? '127.0.0.1',
            self::$config['port'] ?? '3306',
            self::$config['database'] ?? '',
            self::$config['charset'] ?? 'utf8mb4'
        );

        try {
            self::$connection = new PDO(
                $dsn,
                self::$config['username'] ?? 'root',
                self::$config['password'] ?? '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('Unable to connect to database: ' . $exception->getMessage(), 0, $exception);
        }

        return self::$connection;
    }
}
