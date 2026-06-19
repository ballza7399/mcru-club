<?php
namespace App\Core;

class Router
{
    private string $basePath;

    // [method => [pattern => [controller, action]]]
    private array $routes = [];

    public function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes['GET'][$path] = [$controller, $action];
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->routes['POST'][$path] = [$controller, $action];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // strip base path prefix
        if ($this->basePath !== '' && str_starts_with($uri, $this->basePath)) {
            $uri = substr($uri, strlen($this->basePath));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') $uri = '/';

        // exact match first
        if (isset($this->routes[$method][$uri])) {
            [$ctrl, $action] = $this->routes[$method][$uri];
            (new $ctrl)->$action();
            return;
        }

        // parametric match: /foo/{id}
        foreach ($this->routes[$method] ?? [] as $pattern => [$ctrl, $action]) {
            $regex = preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
            if (preg_match('#^' . $regex . '$#', $uri, $m)) {
                array_shift($m); // remove full match
                (new $ctrl)->$action(...$m);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }
}
