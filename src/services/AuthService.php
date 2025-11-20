<?php

declare(strict_types=1);

namespace BridgeBoard\Services;

use BridgeBoard\Models\User;

final class AuthService
{
    private static ?array $cachedUser = null;

    public static function bootstrap(): void
    {
        if (!isset($_SESSION['auth'])) {
            $_SESSION['auth'] = [
                'user_id' => null,
            ];
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['auth']['user_id'] = (int) $user['id'];
        session_regenerate_id(true);
        self::$cachedUser = $user;

        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']);
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION['auth']['user_id'] : null;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        if (self::$cachedUser && (int) self::$cachedUser['id'] === self::id()) {
            return self::$cachedUser;
        }

        $user = User::findById(self::id());
        self::$cachedUser = $user ?: null;
        return $user ?: null;
    }

    public static function logout(): void
    {
        $_SESSION['auth']['user_id'] = null;
        self::$cachedUser = null;
        session_regenerate_id(true);
    }
}
