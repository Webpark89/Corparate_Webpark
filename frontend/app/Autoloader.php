<?php

declare(strict_types=1);

/**
 * PSR-4-style class autoloader for frontend app classes.
 *
 * Searches controllers/, core/, and Models/ relative to this file.
 */
spl_autoload_register(static function (string $class): void {
    $class = ltrim($class, '\\');
    $filename = str_replace('\\', '/', $class) . '.php';
    $paths = [
        __DIR__ . '/controllers',
        __DIR__ . '/core',
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
