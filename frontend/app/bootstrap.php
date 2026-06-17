<?php

declare(strict_types=1);

require_once __DIR__ . '/Autoloader.php';

$config = require __DIR__ . '/../config.php';
define('APP_CONFIG', $config);
define('BASE_URL', rtrim($config['app']['base_url'] ?? '', '/'));

require_once __DIR__ . '/Core/helpers.php';

$router = new Router(require __DIR__ . '/../routes.php');
$router->dispatch();
