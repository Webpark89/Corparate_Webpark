<?php

/**
 * Shared helper functions: security, uploads, CSRF, pagination, auth.
 */
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
    // Session hardening: regenerate ID periodically
    if (empty($_SESSION['_created'])) {
        $_SESSION['_created'] = time();
    } elseif (time() - $_SESSION['_created'] > 3600) { // Regenerate every hour
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }
}

// ----- XSS escape -----
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ----- HTML sanitizer for WYSIWYG content (basic whitelist) -----
function sanitize_html(string $html): string
{
    if ($html === '') return '';
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    // load with UTF-8
    $doc->loadHTML('<?xml encoding="utf-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $allowedTags = [
        'a',
        'p',
        'br',
        'strong',
        'b',
        'em',
        'i',
        'ul',
        'ol',
        'li',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'blockquote',
        'img',
        'figure',
        'figcaption',
        'pre',
        'code',
        'table',
        'thead',
        'tbody',
        'tr',
        'th',
        'td'
    ];
    $allowedAttrs = ['href', 'src', 'alt', 'title', 'width', 'height', 'class', 'id', 'style', 'rel', 'target'];

    $nodes = $doc->getElementsByTagName('*');
    for ($i = $nodes->length - 1; $i >= 0; $i--) {
        $node = $nodes->item($i);
        $name = $node->nodeName;
        if (!in_array($name, $allowedTags, true)) {
            $node->parentNode->removeChild($node);
            continue;
        }
        if ($node->hasAttributes()) {
            // collect attributes first to avoid modifying while iterating
            $attrs = [];
            foreach ($node->attributes as $a) $attrs[$a->name] = $a->value;
            foreach ($attrs as $an => $av) {
                if (!in_array($an, $allowedAttrs, true)) {
                    $node->removeAttribute($an);
                    continue;
                }
                // basic protection against javascript: URIs
                if (in_array($an, ['href', 'src'], true)) {
                    $low = strtolower(trim($av));
                    if (strpos($low, 'javascript:') === 0 || strpos($low, 'data:text/html') === 0) {
                        $node->removeAttribute($an);
                    }
                }
            }
        }
    }

    $body = $doc->saveHTML();
    // strip added html/body tags
    $body = preg_replace('/^<!DOCTYPE.+?>/', '', $body);
    $body = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $body);
    return trim($body);
}

function slugify(string $value): string
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-');
}

// ----- CSRF -----
function csrf_token(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}
function csrf_field(): string
{
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . e(csrf_token()) . '">';
}
function csrf_verify(): void
{
    $t = $_POST[CSRF_TOKEN_NAME] ?? '';
    if (!hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', (string)$t)) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

// ----- Auth -----
function require_login(): void
{
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
    if (
        isset($_SESSION['last_activity']) &&
        time() - $_SESSION['last_activity'] > SESSION_TIMEOUT
    ) {
        session_unset();
        session_destroy();
        header('Location: ' . ADMIN_URL . '/login.php?timeout=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

function current_admin(): array
{
    return [
        'username' => $_SESSION['admin_username'] ?? ADMIN_USERNAME,
        'full_name' => $_SESSION['admin_full_name'] ?? 'Administrator',
        'role' => $_SESSION['admin_role'] ?? 'admin',
    ];
}

// ----- File upload -----
function handle_upload(string $field, array $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif']): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload error.');
    if ($f['size'] > 8 * 1024 * 1024) throw new RuntimeException('File too large (max 8 MB).');

    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) throw new RuntimeException('File type not allowed.');

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($f['tmp_name']);
    $okMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($mime, $okMime, true)) throw new RuntimeException('Invalid MIME type.');

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    $name = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('Move failed.');
    return $name; // store filename only
}

function upload_url(?string $name): string
{
    return $name ? UPLOAD_URL . '/' . rawurlencode($name) : '';
}

// ----- Pagination helper -----
function paginate(int $total, int $perPage, int $current): array
{
    $pages = max(1, (int)ceil($total / $perPage));
    $current = max(1, min($pages, $current));
    return [
        'total'    => $total,
        'pages'    => $pages,
        'current'  => $current,
        'offset'   => ($current - 1) * $perPage,
        'perPage'  => $perPage,
    ];
}

// ----- Flash -----
function flash(string $key, ?string $msg = null)
{
    if ($msg === null) {
        $v = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }
    $_SESSION['_flash'][$key] = $msg;
}

// ----- Role & Permission Check -----
function require_admin_role(): void
{
    $me = current_admin();
    if (($me['role'] ?? null) !== 'admin') {
        http_response_code(403);
        exit('Forbidden: Admin access required.');
    }
}

function can_admin(): bool
{
    $me = current_admin();
    return ($me['role'] ?? null) === 'admin';
}

// ----- Rate Limiting -----
function check_rate_limit(string $key, int $maxAttempts = 5, int $windowSeconds = 600): bool
{
    $key = 'ratelimit_' . $key;
    $now = time();

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    // Remove old attempts outside the window
    $_SESSION[$key] = array_filter($_SESSION[$key], function ($time) use ($now, $windowSeconds) {
        return $now - $time < $windowSeconds;
    });

    if (count($_SESSION[$key]) >= $maxAttempts) {
        return false; // Rate limit exceeded
    }

    $_SESSION[$key][] = $now;
    return true; // OK to proceed
}

function get_rate_limit_remaining(string $key, int $maxAttempts = 5, int $windowSeconds = 600): int
{
    $key = 'ratelimit_' . $key;
    $now = time();

    if (!isset($_SESSION[$key])) {
        return $maxAttempts;
    }

    $_SESSION[$key] = array_filter($_SESSION[$key], function ($time) use ($now, $windowSeconds) {
        return $now - $time < $windowSeconds;
    });

    return max(0, $maxAttempts - count($_SESSION[$key]));
}
