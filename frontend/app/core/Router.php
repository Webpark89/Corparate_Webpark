<?php

declare(strict_types=1);
class Router
{
    public function __construct(private array $routes) {}
    public function dispatch(?string $requestUri = null): void
    {
        $queryRoute = trim((string) ($_GET['url'] ?? ''), '/');
        if ($queryRoute !== '') {
            $requestPath = '/' . $queryRoute;
        } else {
            $requestPath = parse_url($requestUri ?? ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
            $basePath = app_base_url();

            if ($basePath !== '' && str_starts_with($requestPath, $basePath)) {
                $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
            }

            $requestPath = '/' . trim($requestPath, '/');
            $requestPath = $requestPath === '/' ? '/' : rtrim($requestPath, '/');
        }

        if (isset($this->routes[$requestPath])) {
            [$controllerClass, $method] = $this->routes[$requestPath];
            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                http_response_code(500);
                echo 'Controller method not found.';
                return;
            }

            $controller->{$method}();
            return;
        }

        foreach ($this->routes as $routePath => $target) {
            if (strpos($routePath, '{') === false) {
                continue;
            }

            $pattern = preg_replace_callback(
                '/\{([a-zA-Z0-9_]+)\}/',
                static fn(array $matches): string => '(?<' . $matches[1] . '>[^/]+)',
                $routePath
            );

            if ($pattern === null) {
                continue;
            }

            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $requestPath, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                [$controllerClass, $method] = $target;
                $controller = new $controllerClass();

                if (!method_exists($controller, $method)) {
                    http_response_code(500);
                    echo 'Controller method not found.';
                    return;
                }

                $controller->{$method}(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        $controller = new HomeController();
        $controller->notFound();
    }
}
