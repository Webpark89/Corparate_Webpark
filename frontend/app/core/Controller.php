<?php

declare(strict_types=1);

/**
 * Thin view renderer — captures page content and wraps it in the main layout.
 */
class Controller
{
    /**
     * Render a view partial inside the main layout.
     *
     * @param array<string, mixed> $data Variables extracted into the view scope.
     */
    public function view(string $path, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . ltrim($path, '/');

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found: ' . e($path);

            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean() ?: '';

        require __DIR__ . '/../views/layouts/main.php';
    }
}
