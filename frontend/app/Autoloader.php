<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $class = ltrim($class, '\\');
    $filename = str_replace('\\', '/', $class) . '.php';
    $paths = [
        __DIR__ . '/Controllers',
        __DIR__ . '/Core',
        __DIR__ . '/Models',
    ];

    foreach ($paths as $path) {
        $file = $path . '/' . $filename;
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});
