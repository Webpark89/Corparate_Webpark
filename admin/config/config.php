<?php
/**
 * WEBPARK - Application Configuration
 */
// ---- Database ----
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'corparate_webpark');
define('DB_USER', 'root');
// define('DB_PASS', '');
define('DB_PASS', '');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');
// ---- Admin Login ----
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$XsSqtj8zXCoMnPhkOOdcDemQVrmnN09BnnuGoSRQv98RPalokOHza'); // Password is 'password'
// ---- Site ----
$host = (string) ($_SERVER['HTTP_HOST'] ?? '');
$isLocalhost = in_array($host, ['localhost', '127.0.0.1'], true)
    || str_starts_with($host, 'localhost:')
    || str_starts_with($host, '127.0.0.1:');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$siteUrl = $isLocalhost ? 'http://localhost/Corparate_Webpark' : $scheme . '://' . $host;

define('SITE_NAME', 'WEBPARK');
define('SITE_URL', $siteUrl);           // no trailing slash
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_URL', ADMIN_URL . '/uploads');
// ---- Security ----
define('SESSION_TIMEOUT', 3600); // 60 minutes
define('CSRF_TOKEN_NAME', '_csrf');
define('LOGIN_MAX_ATTEMPTS', 3); // Max login attempts before initial lockout
define('LOGIN_ATTEMPT_WINDOW', 600); // Base lockout duration (10 minutes in seconds, doubles on consecutive failures)
define('SESSION_REGENERATE_INTERVAL', 3600); // Regenerate session every hour
// ---- Errors ----
ini_set('display_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Bangkok');
mb_internal_encoding('UTF-8');