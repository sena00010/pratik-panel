<?php
declare(strict_types=1);

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][] = [$path, $handler];
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][] = [$path, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $uri = '/' . trim($uri, '/');
        if ($uri !== '/' && str_starts_with($uri, '/index.php/')) {
            $uri = substr($uri, 10);
        }

        foreach ($this->routes[$method] ?? [] as [$path, $handler]) {
            $params = $this->match($path, $uri);
            if ($params !== null) {
                [$class, $action] = $handler;
                (new $class())->$action(...$params);
                return;
            }
        }

        http_response_code(404);
        View::render('public/404', ['title' => 'Sayfa bulunamadı']);
    }

    private function match(string $path, string $uri): ?array
    {
        $pattern = preg_replace('#\{([a-z_]+)\}#', '([^/]+)', '/' . trim($path, '/')) ?: '';
        $pattern = '#^' . ($pattern === '' ? '/' : $pattern) . '$#';

        if (!preg_match($pattern, $uri, $matches)) {
            return null;
        }

        array_shift($matches);
        return $matches;
    }
}
