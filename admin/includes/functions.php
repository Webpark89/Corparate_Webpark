<?php

/**
 * Shared admin helpers: session, security, uploads, CSRF, auth, pagination.
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

    if (empty($_SESSION['_created'])) {
        $_SESSION['_created'] = time();
    } elseif (time() - $_SESSION['_created'] > SESSION_REGENERATE_INTERVAL) {
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }
}

/**
 * Escape output for HTML contexts (XSS prevention).
 */
function e(?string $string): string
{
    return htmlspecialchars((string) $string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Whitelist-based sanitizer for WYSIWYG HTML content.
 */
function sanitize_html(string $html): string
{
    if ($html === '') {
        return '';
    }

    libxml_use_internal_errors(true);
    $document = new DOMDocument();
    $document->loadHTML('<?xml encoding="utf-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $allowedTags = [
        'a', 'p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'img',
        'figure', 'figcaption', 'pre', 'code', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
    ];
    $allowedAttributes = ['href', 'src', 'alt', 'title', 'width', 'height', 'class', 'id', 'style', 'rel', 'target'];

    $nodes = $document->getElementsByTagName('*');
    for ($index = $nodes->length - 1; $index >= 0; $index--) {
        $node = $nodes->item($index);
        $nodeName = $node->nodeName;

        if (!in_array($nodeName, $allowedTags, true)) {
            $node->parentNode->removeChild($node);
            continue;
        }

        if ($node->hasAttributes()) {
            $attributes = [];
            foreach ($node->attributes as $attribute) {
                $attributes[$attribute->name] = $attribute->value;
            }

            foreach ($attributes as $attributeName => $attributeValue) {
                if (!in_array($attributeName, $allowedAttributes, true)) {
                    $node->removeAttribute($attributeName);
                    continue;
                }

                if (in_array($attributeName, ['href', 'src'], true)) {
                    $lowerValue = strtolower(trim($attributeValue));
                    if (str_starts_with($lowerValue, 'javascript:') || str_starts_with($lowerValue, 'data:text/html')) {
                        $node->removeAttribute($attributeName);
                    }
                }
            }
        }
    }

    $body = $document->saveHTML();
    $body = preg_replace('/^<!DOCTYPE.+?>/', '', $body) ?? $body;
    $body = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $body);

    return trim($body);
}

/**
 * Convert arbitrary text to a URL-safe slug.
 */
function slugify(string $value): string
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

    return trim($value, '-');
}

/**
 * Get or create the session CSRF token.
 */
function csrf_token(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Hidden input field containing the CSRF token for forms.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF token on POST requests; exits with 419 on mismatch.
 *
 * @throws never
 */
function csrf_verify(): void
{
    $submittedToken = $_POST[CSRF_TOKEN_NAME] ?? '';

    if (!hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', (string) $submittedToken)) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

/**
 * Redirect unauthenticated users to login; enforce session timeout.
 */
function require_login(): void
{
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }

    if (
        isset($_SESSION['last_activity'])
        && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT
    ) {
        session_unset();
        session_destroy();
        header('Location: ' . ADMIN_URL . '/login.php?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}

/**
 * @return array{username: string, full_name: string, role: string}
 */
function current_admin(): array
{
    return [
        'username' => $_SESSION['admin_username'] ?? ADMIN_USERNAME,
        'full_name' => $_SESSION['admin_full_name'] ?? 'Administrator',
        'role' => $_SESSION['admin_role'] ?? 'admin',
    ];
}

/**
 * Validate and store an uploaded image; returns stored filename or null when no file sent.
 *
 * @param array<int, string> $allowedExtensions
 * @throws RuntimeException On validation or move failure.
 */
function handle_upload(string $field, array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif']): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error.');
    }

    if ($file['size'] > 8 * 1024 * 1024) {
        throw new RuntimeException('File too large (max 8 MB).');
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('File type not allowed.');
    }

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($file['tmp_name']);
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        throw new RuntimeException('Invalid MIME type.');
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $destination = UPLOAD_DIR . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Move failed.');
    }

    return $filename;
}

/**
 * Build public URL for an uploaded filename stored in the database.
 */
function upload_url(?string $filename): string
{
    return $filename ? UPLOAD_URL . '/' . ltrim($filename, '/') : '';
}

/**
 * @return array{total: int, pages: int, current: int, offset: int, perPage: int}
 */
function paginate(int $total, int $perPage, int $current): array
{
    $pages = max(1, (int) ceil($total / $perPage));
    $current = max(1, min($pages, $current));

    return [
        'total' => $total,
        'pages' => $pages,
        'current' => $current,
        'offset' => ($current - 1) * $perPage,
        'perPage' => $perPage,
    ];
}

/**
 * Flash message storage — pass message to set, omit to read and clear.
 */
function flash(string $key, ?string $message = null): mixed
{
    if ($message === null) {
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    $_SESSION['_flash'][$key] = $message;

    return null;
}

/**
 * Restrict access to admin role only.
 */
function require_admin_role(): void
{
    $admin = current_admin();

    if (($admin['role'] ?? null) !== 'admin') {
        http_response_code(403);
        exit('Forbidden: Admin access required.');
    }
}

function can_admin(): bool
{
    $admin = current_admin();

    return ($admin['role'] ?? null) === 'admin';
}

/**
 * Sliding-window rate limiter stored in session.
 */
function check_rate_limit(string $key, int $maxAttempts = 5, int $windowSeconds = 600): bool
{
    $sessionKey = 'ratelimit_' . $key;
    $now = time();

    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = [];
    }

    $_SESSION[$sessionKey] = array_filter(
        $_SESSION[$sessionKey],
        static fn(int $timestamp) => $now - $timestamp < $windowSeconds
    );

    if (count($_SESSION[$sessionKey]) >= $maxAttempts) {
        return false;
    }

    $_SESSION[$sessionKey][] = $now;

    return true;
}

function get_rate_limit_remaining(string $key, int $maxAttempts = 5, int $windowSeconds = 600): int
{
    $sessionKey = 'ratelimit_' . $key;
    $now = time();

    if (!isset($_SESSION[$sessionKey])) {
        return $maxAttempts;
    }

    $_SESSION[$sessionKey] = array_filter(
        $_SESSION[$sessionKey],
        static fn(int $timestamp) => $now - $timestamp < $windowSeconds
    );

    return max(0, $maxAttempts - count($_SESSION[$sessionKey]));
}
