<?php

declare(strict_types=1);

/**
 * Simple path-based router for the frontend MVC application.
 *
 * Supports exact routes and dynamic segments ({param}) defined in routes.php.
 */
class Router
{
    /**
     * @param array<string, array{0: class-string, 1: string}> $routes
     */
    public function __construct(private array $routes)
    {
    }

    /**
     * Resolve the request path and invoke the matching controller action.
     *
     * @param string|null $requestUri Override for testing; defaults to REQUEST_URI.
     */
    public function dispatch(?string $requestUri = null): void
    {
        $requestPath = $this->resolveRequestPath($requestUri);

        if ($this->dispatchExactRoute($requestPath)) {
            return;
        }

        if ($this->dispatchDynamicRoute($requestPath)) {
            return;
        }

        http_response_code(404);
        (new HomeController())->notFound();
    }

    /**
     * Build the normalized request path from ?url= query or REQUEST_URI.
     */
    private function resolveRequestPath(?string $requestUri): string
    {
        $queryRoute = trim((string) ($_GET['url'] ?? ''), '/');

        if ($queryRoute !== '') {
            return '/' . $queryRoute;
        }

        $requestPath = parse_url($requestUri ?? ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
        $basePath = app_base_url();

        if ($basePath !== '' && str_starts_with($requestPath, $basePath)) {
            $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
        }

        $requestPath = '/' . trim($requestPath, '/');

        return $requestPath === '/' ? '/' : rtrim($requestPath, '/');
    }

    /**
     * Match a static route and invoke its controller method.
     */
    private function dispatchExactRoute(string $requestPath): bool
    {
        if (!isset($this->routes[$requestPath])) {
            return false;
        }

        [$controllerClass, $method] = $this->routes[$requestPath];

        return $this->invokeController($controllerClass, $method);
    }

    /**
     * Match a route with {param} segments and pass captured values to the action.
     */
    private function dispatchDynamicRoute(string $requestPath): bool
    {
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

            if (!preg_match($pattern, $requestPath, $matches)) {
                continue;
            }

            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }

            [$controllerClass, $method] = $target;

            return $this->invokeController($controllerClass, $method, array_values($params));
        }

        return false;
    }

    /**
     * Instantiate a controller and call the given method with optional route params.
     *
     * @param class-string $controllerClass
     * @param string       $method
     * @param array<int, string> $params
     */
    private function invokeController(string $controllerClass, string $method, array $params = []): bool
    {
        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo 'Controller method not found.';

            return true;
        }

        $controller->{$method}(...$params);

        return true;
    }
}
