<?php

declare(strict_types=1);

namespace BridgeBoard\Models;

use BridgeBoard\Services\Database;
use PDO;

final class Message
{
    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('INSERT INTO messages (sender_id, recipient_id, skill_post_id, content, is_read) VALUES (:sender_id, :recipient_id, :skill_post_id, :content, :is_read)');
        $statement->execute([
            'sender_id' => $data['sender_id'],
            'recipient_id' => $data['recipient_id'],
            'skill_post_id' => $data['skill_post_id'] ?? null,
            'content' => $data['content'],
            'is_read' => $data['is_read'] ?? 0,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function forUser(int $userId, int $limit = 10): array
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare('SELECT m.*, u.name AS sender_name, sp.title AS skill_title FROM messages m LEFT JOIN users u ON u.id = m.sender_id LEFT JOIN skill_posts sp ON sp.id = m.skill_post_id WHERE m.recipient_id = :recipient_id ORDER BY m.created_at DESC LIMIT :limit');
        $statement->bindValue(':recipient_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
