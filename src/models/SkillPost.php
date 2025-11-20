<?php

declare(strict_types=1);

namespace BridgeBoard\Models;

use BridgeBoard\Services\Database;
use PDO;

final class SkillPost
{
    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('INSERT INTO skill_posts (user_id, category_id, title, description, location, price_min, price_max, images, status) VALUES (:user_id, :category_id, :title, :description, :location, :price_min, :price_max, :images, :status)');
        $statement->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null,
            'price_min' => $data['price_min'] ?? null,
            'price_max' => $data['price_max'] ?? null,
            'images' => $data['images'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('UPDATE skill_posts SET category_id = :category_id, title = :title, description = :description, location = :location, price_min = :price_min, price_max = :price_max, images = :images, status = :status WHERE id = :id');
        return $statement->execute([
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null,
            'price_min' => $data['price_min'] ?? null,
            'price_max' => $data['price_max'] ?? null,
            'images' => $data['images'] ?? null,
            'status' => $data['status'] ?? 'active',
            'id' => $id,
        ]);
    }

    public static function delete(int $id, int $userId): bool
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('DELETE FROM skill_posts WHERE id = :id AND user_id = :user_id');
        return $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT sp.*, u.name AS user_name, u.avatar_path, c.name AS category_name FROM skill_posts sp LEFT JOIN users u ON u.id = sp.user_id LEFT JOIN categories c ON c.id = sp.category_id WHERE sp.id = :id');
        $statement->execute(['id' => $id]);
        $post = $statement->fetch(PDO::FETCH_ASSOC);
        return $post ?: null;
    }

    public static function forUser(int $userId): array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT * FROM skill_posts WHERE user_id = :user_id ORDER BY created_at DESC');
        $statement->execute(['user_id' => $userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function latest(int $limit = 6): array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT sp.*, u.name AS user_name, c.name AS category_name FROM skill_posts sp LEFT JOIN users u ON u.id = sp.user_id LEFT JOIN categories c ON c.id = sp.category_id ORDER BY sp.created_at DESC LIMIT :limit');
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search(?string $term, ?int $categoryId = null, ?string $location = null): array
    {
        $pdo = Database::connection();
        $sql = 'SELECT sp.*, u.name AS user_name, c.name AS category_name FROM skill_posts sp LEFT JOIN users u ON u.id = sp.user_id LEFT JOIN categories c ON c.id = sp.category_id WHERE sp.status = \"active\"';
        $params = [];

        if ($term) {
            $sql .= ' AND (sp.title LIKE :term OR sp.description LIKE :term)';
            $params['term'] = '%' . $term . '%';
        }

        if ($categoryId) {
            $sql .= ' AND sp.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        if ($location) {
            $sql .= ' AND sp.location LIKE :location';
            $params['location'] = '%' . $location . '%';
        }

        $sql .= ' ORDER BY sp.created_at DESC';

        $statement = $pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
