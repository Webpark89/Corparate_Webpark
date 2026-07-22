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
define('ADMIN_PASSWORD_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); // Password is 'password'
// ---- Site ----
define('SITE_NAME', 'WEBPARK');
define('SITE_URL', 'http://localhost/Corparate_Webpark');           // no trailing slash
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_URL', ADMIN_URL . '/uploads');
// ---- Security ----
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('CSRF_TOKEN_NAME', '_csrf');
define('LOGIN_MAX_ATTEMPTS', 5); // Max login attempts
define('LOGIN_ATTEMPT_WINDOW', 600); // 10 minutes in seconds
define('SESSION_REGENERATE_INTERVAL', 3600); // Regenerate session every hour
// ---- Errors ----
ini_set('display_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Bangkok');
mb_internal_encoding('UTF-8');