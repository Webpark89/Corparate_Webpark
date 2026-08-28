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
 * Find an admin user by username or email from the database.
 *
 * @return array|null  The admin row or null if not found.
 */
function find_admin_by_login(string $login): ?array
{
    $stmt = db()->prepare(
        'SELECT * FROM admins WHERE username = :login_user OR email = :login_email LIMIT 1'
    );
    $stmt->execute(['login_user' => $login, 'login_email' => $login]);
    $user = $stmt->fetch();
    return $user ?: null;
}

/**
 * Find an admin user by ID from the database.
 *
 * @return array|null  The admin row or null if not found.
 */
function find_admin_by_id(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM admins WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

/**
 * Populate session variables from an admin database row.
 */
function set_admin_session(array $user): void
{
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = (int) $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_email'] = $user['email'] ?? '';
    $_SESSION['admin_full_name'] = $user['full_name'] ?: $user['username'];
    $_SESSION['admin_role'] = $user['role'] ?? 'admin';
    $_SESSION['last_activity'] = time();
}

/**
 * Set secure HMAC remember-me cookie (7 days default).
 */
function set_remember_me_cookie(int $adminId): void
{
    $duration = defined('REMEMBER_ME_DURATION') ? REMEMBER_ME_DURATION : (7 * 86400);
    $expiry = time() + $duration;
    $signature = hash_hmac('sha256', $adminId . '|' . $expiry, AUTH_SECRET_KEY);
    $token = base64_encode($adminId . '|' . $expiry . '|' . $signature);

    setcookie('admin_remember', $token, [
        'expires'  => $expiry,
        'path'     => '/',
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']),
        'samesite' => 'Strict',
    ]);
}

/**
 * Clear and invalidate remember-me cookie.
 */
function clear_remember_me_cookie(): void
{
    if (isset($_COOKIE['admin_remember'])) {
        setcookie('admin_remember', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'httponly' => true,
            'secure'   => !empty($_SERVER['HTTPS']),
            'samesite' => 'Strict',
        ]);
        unset($_COOKIE['admin_remember']);
    }
}

/**
 * Verify remember-me cookie and restore session from database if valid.
 */
function check_remember_me_cookie(): bool
{
    if (empty($_COOKIE['admin_remember'])) {
        return false;
    }

    $raw = base64_decode((string) $_COOKIE['admin_remember'], true);
    if ($raw === false) {
        clear_remember_me_cookie();
        return false;
    }

    $parts = explode('|', $raw);
    if (count($parts) !== 3) {
        clear_remember_me_cookie();
        return false;
    }

    [$adminId, $expiry, $signature] = $parts;

    if ((int) $expiry <= time()) {
        clear_remember_me_cookie();
        return false;
    }

    $expectedSignature = hash_hmac('sha256', $adminId . '|' . $expiry, AUTH_SECRET_KEY);
    if (!hash_equals($expectedSignature, $signature)) {
        clear_remember_me_cookie();
        return false;
    }

    // Look up user in database
    $user = find_admin_by_id((int) $adminId);
    if (!$user) {
        clear_remember_me_cookie();
        return false;
    }

    // Valid token! Restore session from DB
    session_regenerate_id(true);
    set_admin_session($user);
    $_SESSION['is_remembered'] = true;

    return true;
}

/**
 * Redirect unauthenticated users to login; enforce session timeout or restore from remember cookie.
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
 * @return array{id: int, username: string, email: string, full_name: string, role: string}
 */
function current_admin(): array
{
    return [
        'id' => $_SESSION['admin_id'] ?? 0,
        'username' => $_SESSION['admin_username'] ?? '',
        'email' => $_SESSION['admin_email'] ?? '',
        'full_name' => $_SESSION['admin_full_name'] ?? '',
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
    $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg+xml',
        'image/svg'
    ];

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
 * Resolve an image URL from the database. 
 * If it contains a slash, it is assumed to be an absolute path or external URL.
 * Otherwise, it is treated as an uploaded filename.
 */
function resolve_admin_image_url(?string $filename): string
{
    if (empty($filename)) {
        return '';
    }
    if (strpos($filename, '/') !== false || strpos($filename, 'http') === 0) {
        if (strpos($filename, 'http') === 0) {
            return $filename;
        }
        $webPath = rtrim(SITE_URL, '/') . '/admin/' . ltrim($filename, '/');
        // Check if it exists on disk, otherwise return placeholder
        $diskPath = __DIR__ . '/../' . ltrim($filename, '/');
        if (!file_exists($diskPath)) {
            return 'https://placehold.co/400x300/e2e8f0/64748b?text=No+Image';
        }
        return $webPath;
    }
    
    // Check if uploaded file exists
    $diskPath = UPLOAD_DIR . '/' . $filename;
    if (!file_exists($diskPath)) {
        return 'https://placehold.co/400x300/e2e8f0/64748b?text=No+Image';
    }
    return upload_url($filename);
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
 * Restrict access to admin roles (admin or super_admin).
 */
function require_admin_role(): void
{
    $admin = current_admin();
    $allowedRoles = ['admin', 'super_admin'];
    if (!in_array($admin['role'] ?? '', $allowedRoles, true)) {
        http_response_code(403);
        exit('Forbidden: Admin access required.');
    }
}

function can_admin(): bool
{
    $admin = current_admin();
    $allowedRoles = ['admin', 'super_admin'];
    return in_array($admin['role'] ?? '', $allowedRoles, true);
}

/**
 * Check if the current logged-in user is a Super Admin.
 */
function is_super_admin(): bool
{
    $admin = current_admin();
    return ($admin['role'] ?? '') === 'super_admin';
}

/**
 * Restrict access to Super Admin role only.
 */
function require_super_admin(): void
{
    require_login();
    if (!is_super_admin()) {
        http_response_code(403);
        exit('Forbidden: Super Admin access required.');
    }
}

/**
 * Rate Limiter Storage (Session + Temp File) to prevent bypassing via cookies/incognito.
 */
function get_rate_limit_file_path(string $key): string
{
    $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'webpark_ratelimit';
    if (!is_dir($tempDir)) {
        @mkdir($tempDir, 0755, true);
    }
    return $tempDir . DIRECTORY_SEPARATOR . md5($key) . '.json';
}

function get_rate_limit_data(string $key): array
{
    $sessionKey = 'ratelimit_' . $key;
    $filePath = get_rate_limit_file_path($key);

    $fileData = [];
    if (file_exists($filePath)) {
        $content = @file_get_contents($filePath);
        if ($content) {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $fileData = $decoded;
            }
        }
    }

    $sessionData = $_SESSION[$sessionKey] ?? [];

    $failedCount = max((int)($fileData['failed_count'] ?? 0), (int)($sessionData['failed_count'] ?? 0));
    $lockedUntil = max((int)($fileData['locked_until'] ?? 0), (int)($sessionData['locked_until'] ?? 0));

    // If lock period has expired, reset
    if ($lockedUntil > 0 && time() >= $lockedUntil) {
        $failedCount = 0;
        $lockedUntil = 0;
        reset_rate_limit($key);
    }

    return [
        'failed_count' => $failedCount,
        'locked_until' => $lockedUntil,
    ];
}

function save_rate_limit_data(string $key, array $data): void
{
    $sessionKey = 'ratelimit_' . $key;
    $_SESSION[$sessionKey] = $data;

    $filePath = get_rate_limit_file_path($key);
    @file_put_contents($filePath, json_encode($data));
}

function is_rate_limited(string $key): bool
{
    $data = get_rate_limit_data($key);
    return time() < ($data['locked_until'] ?? 0);
}

function get_rate_limit_lockout_remaining(string $key): int
{
    $data = get_rate_limit_data($key);
    return max(0, ($data['locked_until'] ?? 0) - time());
}

function get_rate_limit_attempts_left(string $key, int $maxAttempts = LOGIN_MAX_ATTEMPTS): int
{
    $data = get_rate_limit_data($key);
    return max(0, $maxAttempts - ($data['failed_count'] ?? 0));
}

function record_failed_attempt(string $key, int $maxAttempts = LOGIN_MAX_ATTEMPTS, int $baseWindowSeconds = LOGIN_ATTEMPT_WINDOW): array
{
    $data = get_rate_limit_data($key);
    $data['failed_count'] = ($data['failed_count'] ?? 0) + 1;
    $failedCount = $data['failed_count'];

    if ($failedCount >= $maxAttempts) {
        $exceededCount = $failedCount - $maxAttempts;
        $multiplier = (int) pow(2, $exceededCount);
        $lockoutSeconds = $baseWindowSeconds * $multiplier;
        $data['locked_until'] = time() + $lockoutSeconds;
    }

    save_rate_limit_data($key, $data);
    return $data;
}

function reset_rate_limit(string $key): void
{
    $sessionKey = 'ratelimit_' . $key;
    unset($_SESSION[$sessionKey]);

    $filePath = get_rate_limit_file_path($key);
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}

/**
 * Backward compatibility wrappers.
 */
function check_rate_limit(string $key, int $maxAttempts = LOGIN_MAX_ATTEMPTS, int $windowSeconds = LOGIN_ATTEMPT_WINDOW): bool
{
    return !is_rate_limited($key);
}

function get_rate_limit_remaining(string $key, int $maxAttempts = LOGIN_MAX_ATTEMPTS, int $windowSeconds = LOGIN_ATTEMPT_WINDOW): int
{
    return get_rate_limit_attempts_left($key, $maxAttempts);
}


