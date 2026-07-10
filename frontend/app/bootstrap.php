<?php

declare(strict_types=1);

/**
 * Frontend application bootstrap — config, DB constants, autoload, routing.
 *
 * @todo Align DB credentials with admin/config/config.php via shared env config.
 */
require_once __DIR__ . '/Autoloader.php';

$config = require __DIR__ . '/../config.php';
define('APP_CONFIG', $config);
define('BASE_URL', rtrim($config['app']['base_url'] ?? '', '/'));

// DB constants for frontend models (must match active MySQL instance).
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'corparate_webpark');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/views/components/functions.php';

$router = new Router(require __DIR__ . '/../routes.php');
$router->dispatch();
