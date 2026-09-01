<?php

/**
 * Shared admin helpers: session, security, uploads, CSRF, auth, pagination.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Safe fallback for AUTH_SECRET_KEY if not defined in older config.php
if (!defined('AUTH_SECRET_KEY')) {
    define('AUTH_SECRET_KEY', 'wbpk_s3cr3t_k3y_2026_xK9mPqR7nT4vL2wJ');
}

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
 * Safely retains rich text elements (span, div, table, styling, etc.) while stripping malicious scripts and attributes.
 */
function sanitize_html(string $html): string
{
    if (trim($html) === '') {
        return '';
    }

    // Clean up any legacy XML artifacts
    $html = preg_replace('/<\?xml[^>]*\?>/i', '', $html);
    $html = preg_replace('/<!--\?xml[^>]*-->/i', '', $html);

    libxml_use_internal_errors(true);
    $document = new DOMDocument();
    
    // Load as UTF-8 HTML document with explicit meta charset
    $document->loadHTML('<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $html . '</body></html>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
    libxml_clear_errors();

    $allowedTags = [
        'a', 'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del', 'mark', 'sub', 'sup', 'small',
        'span', 'div', 'blockquote', 'pre', 'code',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'img', 'figure', 'figcaption',
        'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
        'hr', 'iframe', 'video', 'audio', 'source', 'section', 'article'
    ];
    $allowedAttributes = [
        'href', 'src', 'alt', 'title', 'width', 'height', 'class', 'id', 'style',
        'target', 'rel', 'allow', 'allowfullscreen', 'frameborder', 'controls', 'poster', 'loading'
    ];

    $dangerousTags = ['script', 'style', 'applet', 'object', 'embed', 'base', 'meta', 'link'];

    $nodes = $document->getElementsByTagName('*');
    for ($index = $nodes->length - 1; $index >= 0; $index--) {
        $node = $nodes->item($index);
        $nodeName = strtolower($node->nodeName);

        if (in_array($nodeName, ['html', 'head', 'body', 'meta'], true)) {
            continue;
        }

        // Dangerous tag: remove entire node including content
        if (in_array($nodeName, $dangerousTags, true)) {
            if ($node->parentNode) {
                $node->parentNode->removeChild($node);
            }
            continue;
        }

        // Unknown tag: unwrap content (keep children) rather than deleting text
        if (!in_array($nodeName, $allowedTags, true)) {
            if ($node->parentNode) {
                while ($node->hasChildNodes()) {
                    $node->parentNode->insertBefore($node->firstChild, $node);
                }
                $node->parentNode->removeChild($node);
            }
            continue;
        }

        // Filter attributes
        if ($node->hasAttributes()) {
            $attrsToRemove = [];
            foreach ($node->attributes as $attribute) {
                $attrName = strtolower($attribute->name);

                // Strip any on* event handlers (e.g. onload, onerror, onclick)
                if (str_starts_with($attrName, 'on') || !in_array($attrName, $allowedAttributes, true)) {
                    $attrsToRemove[] = $attribute->name;
                    continue;
                }

                // Check URI schemes for href, src
                if (in_array($attrName, ['href', 'src'], true)) {
                    $lowerValue = strtolower(trim($attribute->value));
                    if (str_starts_with($lowerValue, 'javascript:') || str_starts_with($lowerValue, 'data:text/html') || str_starts_with($lowerValue, 'vbscript:')) {
                        $attrsToRemove[] = $attribute->name;
                    }
                }
            }

            foreach ($attrsToRemove as $attrName) {
                $node->removeAttribute($attrName);
            }
        }
    }

    $body = $document->getElementsByTagName('body')->item(0);
    if (!$body) {
        return '';
    }

    $output = '';
    foreach ($body->childNodes as $child) {
        $output .= $document->saveHTML($child);
    }

    return trim($output);
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
        'SELECT a.*, r.name AS role_name, r.slug AS role_slug, r.is_system AS role_is_system
         FROM admins a
         LEFT JOIN roles r ON a.role_id = r.id
         WHERE a.username = :login_user OR a.email = :login_email LIMIT 1'
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
    $stmt = db()->prepare(
        'SELECT a.*, r.name AS role_name, r.slug AS role_slug, r.is_system AS role_is_system
         FROM admins a
         LEFT JOIN roles r ON a.role_id = r.id
         WHERE a.id = :id LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

/**
 * Reload permissions for the current active session role.
 */
function refresh_current_admin_permissions(?int $roleId = null): void
{
    $roleId = $roleId ?? (!empty($_SESSION['admin_role_id']) ? (int)$_SESSION['admin_role_id'] : null);
    if (!$roleId) {
        $_SESSION['admin_permissions'] = [];
        return;
    }
    try {
        $stmt = db()->prepare('
            SELECT p.code 
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = :role_id
        ');
        $stmt->execute(['role_id' => $roleId]);
        $_SESSION['admin_permissions'] = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    } catch (Exception $e) {
        $_SESSION['admin_permissions'] = [];
    }
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
    $_SESSION['admin_full_name'] = !empty($user['full_name']) ? $user['full_name'] : $user['username'];
    $_SESSION['admin_role_id'] = !empty($user['role_id']) ? (int)$user['role_id'] : null;
    $_SESSION['admin_role_slug'] = $user['role_slug'] ?? ($user['role'] ?? 'admin');
    $_SESSION['admin_role_name'] = $user['role_name'] ?? ($user['role'] === 'super_admin' ? 'ผู้ดูแลระบบสูงสุด' : 'ผู้ดูแลระบบ');
    $_SESSION['admin_role'] = $_SESSION['admin_role_slug'];
    $_SESSION['last_activity'] = time();

    refresh_current_admin_permissions($_SESSION['admin_role_id']);
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
 * @return array{id: int, username: string, email: string, full_name: string, role: string, role_id: ?int, role_slug: string, role_name: string, permissions: array<string>}
 */
function current_admin(): array
{
    return [
        'id' => $_SESSION['admin_id'] ?? 0,
        'username' => $_SESSION['admin_username'] ?? '',
        'email' => $_SESSION['admin_email'] ?? '',
        'full_name' => $_SESSION['admin_full_name'] ?? '',
        'role' => $_SESSION['admin_role'] ?? 'admin',
        'role_id' => $_SESSION['admin_role_id'] ?? null,
        'role_slug' => $_SESSION['admin_role_slug'] ?? ($_SESSION['admin_role'] ?? 'admin'),
        'role_name' => $_SESSION['admin_role_name'] ?? 'ผู้ดูแลระบบ',
        'permissions' => $_SESSION['admin_permissions'] ?? [],
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
 * Restrict access to authenticated users.
 */
function require_admin_role(): void
{
    require_login();
}

function can_admin(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

/**
 * Check if the current logged-in user is a Super Admin.
 */
function is_super_admin(): bool
{
    $admin = current_admin();
    return ($admin['role_slug'] ?? ($admin['role'] ?? '')) === 'super_admin';
}

/**
 * Restrict access to Super Admin role only.
 */
function require_super_admin(): void
{
    require_login();
    if (!is_super_admin()) {
        http_response_code(403);
        $errorMessage = 'หน้านี้สงวนสิทธิ์เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้น';
        $requiredPermission = 'roles.manage';
        if (file_exists(__DIR__ . '/../errors/403.php')) {
            include __DIR__ . '/../errors/403.php';
        } else {
            exit('Forbidden: Super Admin access required.');
        }
        exit;
    }
}

/**
 * Check if the current logged-in user has a specific permission code.
 * Super Admin bypasses all checks and has all permissions.
 */
function has_permission(string $code): bool
{
    if (is_super_admin()) {
        return true;
    }
    $permissions = $_SESSION['admin_permissions'] ?? [];
    return in_array($code, $permissions, true);
}

/**
 * Check if the user has any of the supplied permission codes.
 */
function has_any_permission(array $codes): bool
{
    if (is_super_admin()) {
        return true;
    }
    $permissions = $_SESSION['admin_permissions'] ?? [];
    foreach ($codes as $code) {
        if (in_array($code, $permissions, true)) {
            return true;
        }
    }
    return false;
}

/**
 * Enforce permission on a page/action; renders 403 error page if not allowed.
 */
function require_permission(string $code): void
{
    require_login();
    if (!has_permission($code)) {
        http_response_code(403);
        $errorMessage = 'คุณไม่มีสิทธิ์ในการเข้าถึงหรือดำเนินการในส่วนนี้ (' . e($code) . ')';
        $requiredPermission = $code;
        if (file_exists(__DIR__ . '/../errors/403.php')) {
            include __DIR__ . '/../errors/403.php';
        } else {
            exit('Forbidden: You do not have permission to access this page.');
        }
        exit;
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

/**
 * Calculate lockout duration (in seconds) based on failed attempt tier.
 * - 1-2 attempts: 0s (warn remaining attempts)
 * - 3 attempts: 6 minutes (360s)
 * - 4 attempts: 12 minutes (720s)
 * - 5 attempts: 24 minutes (1440s)
 * - 6 attempts: 48 minutes (2880s)
 * - 7 attempts: 96 minutes (5760s)
 * - 8+ attempts: -1 (Permanent lockout - must contact Admin)
 */
function get_tiered_lockout_seconds(int $failedCount): int
{
    return match ($failedCount) {
        1, 2 => 0,
        3 => 6 * 60,       // 6 minutes = 360s
        4 => 12 * 60,      // 12 minutes = 720s
        5 => 24 * 60,      // 24 minutes = 1440s
        6 => 48 * 60,      // 48 minutes = 2880s
        7 => 96 * 60,      // 96 minutes = 5760s
        default => ($failedCount >= 8) ? -1 : 0,
    };
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
    $isPermanent = !empty($fileData['is_permanent']) || !empty($sessionData['is_permanent']) || $failedCount >= 8 || $lockedUntil >= 2000000000;
    $lastAttemptAt = max((int)($fileData['last_attempt_at'] ?? 0), (int)($sessionData['last_attempt_at'] ?? 0));

    // Reset if inactive for over 24 hours and not permanently locked
    if (!$isPermanent && $lastAttemptAt > 0 && (time() - $lastAttemptAt) > 86400) {
        $failedCount = 0;
        $lockedUntil = 0;
        reset_rate_limit($key);
    } elseif (!$isPermanent && $lockedUntil > 0 && time() >= $lockedUntil) {
        // Lockout period has elapsed; unlock form for next try, keep failed_count intact for escalation
        $lockedUntil = 0;
        $saveData = [
            'failed_count' => $failedCount,
            'locked_until' => 0,
            'is_permanent' => false,
            'last_attempt_at' => $lastAttemptAt,
        ];
        $_SESSION[$sessionKey] = $saveData;
        @file_put_contents($filePath, json_encode($saveData));
    }

    return [
        'failed_count' => $failedCount,
        'locked_until' => $lockedUntil,
        'is_permanent' => $isPermanent,
        'last_attempt_at' => $lastAttemptAt,
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
    if (!empty($data['is_permanent'])) {
        return true;
    }
    return time() < ($data['locked_until'] ?? 0);
}

function is_permanent_rate_limited(string $key): bool
{
    $data = get_rate_limit_data($key);
    return !empty($data['is_permanent']);
}

function get_rate_limit_lockout_remaining(string $key): int
{
    $data = get_rate_limit_data($key);
    if (!empty($data['is_permanent'])) {
        return -1;
    }
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
    $failedCount = ($data['failed_count'] ?? 0) + 1;
    $data['failed_count'] = $failedCount;
    $data['last_attempt_at'] = time();

    $lockoutSec = get_tiered_lockout_seconds($failedCount);

    if ($lockoutSec === -1) {
        $data['locked_until'] = 2147483647; // Permanent
        $data['is_permanent'] = true;
    } elseif ($lockoutSec > 0) {
        $data['locked_until'] = time() + $lockoutSec;
        $data['is_permanent'] = false;
    } else {
        $data['locked_until'] = 0;
        $data['is_permanent'] = false;
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
 * Clear all stored rate limit files and session data (Super Admin tool).
 */
function clear_all_rate_limits(): int
{
    $count = 0;
    $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'webpark_ratelimit';
    if (is_dir($tempDir)) {
        $files = glob($tempDir . DIRECTORY_SEPARATOR . '*.json');
        if ($files) {
            foreach ($files as $file) {
                if (@unlink($file)) {
                    $count++;
                }
            }
        }
    }
    foreach ($_SESSION as $k => $v) {
        if (str_starts_with($k, 'ratelimit_')) {
            unset($_SESSION[$k]);
        }
    }
    return $count;
}

/**
 * Generate a cryptographically signed password reset token (15-minute validity).
 */
function generate_password_reset_token(int $adminId, string $email): string
{
    $expiry = time() + (15 * 60); // 15 minutes
    $payload = $adminId . '|' . $email . '|' . $expiry;
    $signature = hash_hmac('sha256', $payload, AUTH_SECRET_KEY);
    return base64_encode($payload . '|' . $signature);
}

/**
 * Verify and unpack password reset token.
 * Returns the admin row if valid, or null if expired/tampered.
 */
function verify_password_reset_token(string $token): ?array
{
    $raw = base64_decode($token, true);
    if ($raw === false) {
        return null;
    }
    $parts = explode('|', $raw);
    if (count($parts) !== 4) {
        return null;
    }
    [$adminId, $email, $expiry, $signature] = $parts;

    if ((int)$expiry < time()) {
        return null; // Expired
    }

    $payload = $adminId . '|' . $email . '|' . $expiry;
    $expectedSignature = hash_hmac('sha256', $payload, AUTH_SECRET_KEY);
    if (!hash_equals($expectedSignature, $signature)) {
        return null; // Invalid signature
    }

    $user = find_admin_by_id((int)$adminId);
    if (!$user || strtolower($user['email']) !== strtolower($email)) {
        return null;
    }

    return $user;
}

/**
 * Generate a random 6-digit OTP string.
 */
function generate_otp_code(int $length = 6): string
{
    return str_pad((string) random_int(0, (int)pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

/**
 * Store password reset OTP state in session with 10-minute expiry.
 */
function store_password_reset_otp(int $adminId, string $email, string $username, string $otpCode): void
{
    $_SESSION['pwd_reset_otp'] = [
        'admin_id' => $adminId,
        'email' => $email,
        'username' => $username,
        'otp_code' => $otpCode, // Stored for dev/local feedback and validation
        'otp_hash' => password_hash($otpCode, PASSWORD_DEFAULT),
        'expires_at' => time() + (10 * 60), // 10 minutes
        'attempts' => 0,
        'verified' => false,
    ];
}

/**
 * Verify submitted 6-digit OTP code against session state.
 */
function verify_password_reset_otp(string $inputOtp): bool
{
    if (empty($_SESSION['pwd_reset_otp'])) {
        return false;
    }
    $data = &$_SESSION['pwd_reset_otp'];
    if (time() > $data['expires_at']) {
        return false; // Expired
    }
    if (($data['attempts'] ?? 0) >= 5) {
        return false; // Exceeded attempts
    }
    if (password_verify($inputOtp, $data['otp_hash']) || (string)$inputOtp === (string)($data['otp_code'] ?? '')) {
        $data['verified'] = true;
        return true;
    }
    $data['attempts'] = ($data['attempts'] ?? 0) + 1;
    return false;
}

function get_password_reset_otp_data(): ?array
{
    return $_SESSION['pwd_reset_otp'] ?? null;
}

function clear_password_reset_otp(): void
{
    unset($_SESSION['pwd_reset_otp']);
}

/**
 * Send 6-digit OTP code to the user's email via PHPMailer or mail().
 */
function send_password_reset_otp_email(string $toEmail, string $otpCode, string $username = ''): bool
{
    $vendorAutoload = __DIR__ . '/../../vendor/autoload.php';
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer') && file_exists($vendorAutoload)) {
        require_once $vendorAutoload;
    }

    $subject = "🔑 รหัสยืนยัน OTP สำหรับตั้งรหัสผ่านใหม่: {$otpCode}";
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'WEBPARK';

    $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; color: #1e293b; margin: 0; padding: 24px; }
        .card { max-width: 520px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
        .header { background: #0f172a; color: #ffffff; padding: 24px 32px; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; }
        .content { padding: 32px 28px; text-align: center; }
        .otp-box { display: inline-block; background: #eff6ff; border: 1.5px dashed #3b82f6; border-radius: 12px; padding: 14px 28px; font-size: 32px; font-weight: 800; font-family: monospace; letter-spacing: 8px; color: #1d4ed8; margin: 20px 0; }
        .desc { font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 12px; }
        .warning { font-size: 12px; color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 10px; margin-top: 20px; text-align: left; }
        .footer { background: #f8fafc; text-align: center; padding: 16px 28px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>🛡️ {$siteName} Security Verification</h2>
        </div>
        <div class="content">
            <p class="desc">สวัสดีคุณ <strong>{$username}</strong>,<br>ระบบได้รับการร้องขอการตั้งรหัสผ่านใหม่และปลดล็อกการเข้าสู่ระบบ กรุณาใช้รหัส OTP ด้านล่างนี้เพื่อยืนยันตัวตน:</p>
            <div class="otp-box">{$otpCode}</div>
            <p class="desc" style="font-size: 13px; color: #64748b;">รหัสยืนยันนี้มีอายุการใช้งาน <strong>10 นาที</strong></p>
            <div class="warning">
                ⚠️ หากคุณไม่ได้เป็นผู้ทำรายการนี้ กรุณาเพิกเฉยต่ออีเมลฉบับนี้ หรือติดต่อผู้ดูแลระบบเพื่อตรวจสอบความปลอดภัย
            </div>
        </div>
        <div class="footer">
            © 2026 {$siteName}. All rights reserved.
        </div>
    </div>
</body>
</html>
HTML;

    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $dbSettings = [];
        try {
            $rows = db()->query('SELECT config_key, config_value FROM settings WHERE config_key LIKE "mail_%"')->fetchAll(PDO::FETCH_KEY_PAIR);
            if ($rows) {
                $dbSettings = $rows;
            }
        } catch (\Throwable $e) {
            // fallback
        }

        $mailHost = $dbSettings['mail_host'] ?? (getenv('MAIL_HOST') ?: (defined('MAIL_HOST') ? MAIL_HOST : 'smtp.gmail.com'));
        $mailPort = (int)($dbSettings['mail_port'] ?? (getenv('MAIL_PORT') ?: (defined('MAIL_PORT') ? MAIL_PORT : 587)));
        $mailUser = (string)($dbSettings['mail_user'] ?? (getenv('MAIL_USER') ?: (defined('MAIL_USER') ? MAIL_USER : '')));
        $mailPass = str_replace(' ', '', (string)($dbSettings['mail_pass'] ?? (getenv('MAIL_PASS') ?: (defined('MAIL_PASS') ? MAIL_PASS : ''))));
        $fromName = (string)($dbSettings['mail_from_name'] ?? (getenv('MAIL_FROM_NAME') ?: "{$siteName} Security"));

        if ($mailUser !== '' && $mailPass !== '') {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = $mailHost;
                $mail->SMTPAuth   = true;
                $mail->Username   = $mailUser;
                $mail->Password   = $mailPass;
                $mail->CharSet    = 'UTF-8';
                $mail->SMTPSecure = $mailPort === 465 ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $mailPort;
                $mail->Timeout    = 10;

                $mail->setFrom($mailUser, $fromName);
                $mail->addAddress($toEmail, $username);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $htmlBody;
                $mail->AltBody = "รหัสยืนยัน OTP ของคุณคือ: {$otpCode} (มีอายุ 10 นาที)";

                return $mail->send();
            } catch (\Exception $e) {
                error_log('[OTP Mailer] PHPMailer error: ' . $e->getMessage());
            }
        }
    }

    // Fallback standard PHP mail()
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@webpark.co.th\r\n";
    return @mail($toEmail, $subject, $htmlBody, $headers);
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


