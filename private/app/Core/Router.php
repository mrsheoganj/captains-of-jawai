<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function add(string $method, string $pattern, callable|array $handler): void
    {
        $regex = preg_replace_callback('/\{(\w+)(?::([^}]+))?\}/', fn ($m) => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')', trim($pattern, '/'));
        $this->routes[] = [$method, '#^' . $regex . '$#', $handler];
    }

    /** Returns true when a route matched and ran. */
    public function dispatch(string $method, string $path): bool
    {
        $path = trim($path, '/');
        $method = $method === 'HEAD' ? 'GET' : $method;
        foreach ($this->routes as [$m, $regex, $handler]) {
            if ($m !== $method || !preg_match($regex, $path, $match)) {
                continue;
            }
            $params = array_filter($match, 'is_string', ARRAY_FILTER_USE_KEY);
            if (is_array($handler) && is_string($handler[0])) {
                $handler = [new $handler[0](), $handler[1]];
            }
            $result = $handler(...$params);
            if (is_string($result)) {
                echo $result;
            }
            return true;
        }
        return false;
    }
}
