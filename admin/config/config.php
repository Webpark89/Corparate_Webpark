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

// ---- Auth ----
// Secret key for HMAC signing (Remember-Me cookies). Change this to a random string in production.
define('AUTH_SECRET_KEY', 'wbpk_s3cr3t_k3y_2026_xK9mPqR7nT4vL2wJ');

// ---- Site ----
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_NAME', 'WEBPARK');
define('SITE_URL', $protocol . '://' . $host . '/Corparate_Webpark');           // no trailing slash
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_URL', ADMIN_URL . '/uploads');

// ---- Security ----
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('CSRF_TOKEN_NAME', '_csrf');
define('LOGIN_MAX_ATTEMPTS', 3); // Max login attempts before initial lockout (3 attempts)
define('LOGIN_ATTEMPT_WINDOW', 360); // 6 minutes base lockout in seconds
define('SESSION_REGENERATE_INTERVAL', 3600); // Regenerate session every hour

// ---- Mail / SMTP Configuration (Production Ready) ----
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.gmail.com');
define('MAIL_PORT', (int)(getenv('MAIL_PORT') ?: 587));
define('MAIL_USER', getenv('MAIL_USER') ?: '');
define('MAIL_PASS', getenv('MAIL_PASS') ?: '');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: (defined('SITE_NAME') ? SITE_NAME . ' Security' : 'WEBPARK Security'));

// ---- Google reCAPTCHA Configuration (Production Ready) ----
define('RECAPTCHA_SITE_KEY', getenv('RECAPTCHA_SITE_KEY') ?: '6Lcf_pAtAAAAAOVhatPPwrHSYXeb_0J4yXf5BrRO');
define('RECAPTCHA_SECRET_KEY', getenv('RECAPTCHA_SECRET_KEY') ?: '');

// ---- Errors ----
ini_set('display_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Bangkok');
mb_internal_encoding('UTF-8');