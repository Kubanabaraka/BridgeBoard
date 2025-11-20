<?php

declare(strict_types=1);

namespace BridgeBoard\Models;

use BridgeBoard\Services\Database;
use PDO;

final class Category
{
    public static function all(): array
    {
        $pdo = Database::connection();
        $statement = $pdo->query('SELECT * FROM categories ORDER BY name');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);
        $category = $statement->fetch(PDO::FETCH_ASSOC);
        return $category ?: null;
    }
}
