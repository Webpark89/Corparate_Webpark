<?php

declare(strict_types=1);

class Controller
{
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
