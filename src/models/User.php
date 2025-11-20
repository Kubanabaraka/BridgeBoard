<?php

declare(strict_types=1);

namespace BridgeBoard\Models;

use BridgeBoard\Services\Database;
use PDO;

final class User
{
    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash, bio, location, avatar_path) VALUES (:name, :email, :password_hash, :bio, :location, :avatar_path)');
        $statement->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'bio' => $data['bio'] ?? null,
            'location' => $data['location'] ?? null,
            'avatar_path' => $data['avatar_path'] ?? null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findById(?int $id): ?array
    {
        if ($id === null) {
            return null;
        }
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function updateProfile(int $id, array $data): bool
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('UPDATE users SET name = :name, bio = :bio, location = :location, avatar_path = :avatar_path WHERE id = :id');
        return $statement->execute([
            'name' => $data['name'],
            'bio' => $data['bio'] ?? null,
            'location' => $data['location'] ?? null,
            'avatar_path' => $data['avatar_path'] ?? null,
            'id' => $id,
        ]);
    }
}
