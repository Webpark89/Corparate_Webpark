<?php

declare(strict_types=1);

require_once __DIR__ . '/Autoloader.php';

$config = require __DIR__ . '/../config.php';
define('APP_CONFIG', $config);
define('BASE_URL', rtrim($config['app']['base_url'] ?? '', '/'));

// DB constants (required for frontend DB connection)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'corparate_webpark');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');

require_once __DIR__ . '/Core/helpers.php';


$router = new Router(require __DIR__ . '/../routes.php');
$router->dispatch();
