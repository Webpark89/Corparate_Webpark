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

// Include admin config to ensure Frontend and Admin always share identical DB credentials
if (file_exists(__DIR__ . '/../../admin/config/config.php')) {
    require_once __DIR__ . '/../../admin/config/config.php';
}

// DB constants fallback for frontend models
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'corparate_webpark');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_PORT')) define('DB_PORT', '3306');
if (!defined('DB_CHARSET')) define('DB_CHARSET', 'utf8mb4');

require_once __DIR__ . '/core/helpers.php';
require_once __DIR__ . '/views/components/functions.php';

$router = new Router(require __DIR__ . '/../routes.php');
$router->dispatch();
