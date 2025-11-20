<?php

declare(strict_types=1);

namespace BridgeBoard\Core;

use Closure;
use RuntimeException;

final class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array|Closure $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array|Closure $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function addRoute(string $method, string $path, array|Closure $handler): self
    {
        $method = strtoupper($method);
        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->compilePath($path),
        ];

        return $this;
    }

    public function dispatch(string $uri, string $method): mixed
    {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = $this->extractParams($matches);
                return $this->invoke($route['handler'], $params);
            }
        }

        http_response_code(404);
        render('pages/404', ['title' => 'Page not found']);
        return null;
    }

    private function compilePath(string $path): string
    {
        $pattern = preg_replace('#\{([^}/]+)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function extractParams(array $matches): array
    {
        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }
        return $params;
    }

    private function invoke(array|Closure $handler, array $params): mixed
    {
        if ($handler instanceof Closure) {
            return $handler(...array_values($params));
        }

        [$class, $method] = $handler;
        if (!class_exists($class)) {
            throw new RuntimeException("Controller {$class} not found.");
        }

        $instance = new $class();
        if (!method_exists($instance, $method)) {
            throw new RuntimeException("Method {$method} not found on {$class}.");
        }

        return $instance->{$method}(...array_values($params));
    }
}
