<?php
/**
 * Admin front controller — routes ?url= to dashboard or 404.
 */
require_once __DIR__ . '/config/config.php';
$route = trim((string) ($_GET['url'] ?? ''), '/');
if ($route === '' || $route === 'dashboard') {
    require __DIR__ . '/dashboard.php';
    exit;
}
http_response_code(404);
echo 'Admin page not found.';
