<?php

declare(strict_types=1);

if (!function_exists('load_env')) {
    function load_env(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            [$name, $value] = array_map('trim', explode('=', $line, 2) + [1 => '']);
            $value = trim($value, "\"' ");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv(sprintf('%s=%s', $name, $value));
        }
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? $default;
    }
}

if (!function_exists('view_path')) {
    function view_path(string $view): string
    {
        return BASE_PATH . '/src/views/' . $view . '.php';
    }
}

if (!function_exists('render')) {
    function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        include view_path($view);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value = null): mixed
    {
        if ($value === null) {
            $stored = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $stored;
        }
        $_SESSION['flash'][$key] = $value;
        return null;
    }
}

if (!function_exists('flash_peek')) {
    function flash_peek(string $key): mixed
    {
        return $_SESSION['flash'][$key] ?? null;
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('set_old_input')) {
    function set_old_input(array $input): void
    {
        $_SESSION['old'] = $input;
    }
}

if (!function_exists('clear_old_input')) {
    function clear_old_input(): void
    {
        unset($_SESSION['old']);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $baseUrl = rtrim(env('APP_URL', ''), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}
