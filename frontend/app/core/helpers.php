<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Return the shared PDO database connection.
 */
function db(): PDO
{
    return Database::getInstance();
}

/**
 * Read application config by dot-notation key (e.g. "app.name").
 *
 * @param string|null $key     Dot-separated path; null returns entire config.
 * @param mixed       $default Value when key is missing.
 */
function config(?string $key = null, mixed $default = null): mixed
{
    $config = defined('APP_CONFIG') && is_array(APP_CONFIG) ? APP_CONFIG : [];

    if ($key === null || $key === '') {
        return $config;
    }

    $value = $config;

    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

/**
 * Escape output for safe HTML rendering (XSS prevention).
 */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Strip tags and collapse whitespace for SEO meta fallbacks.
 */
function normalize_text(mixed $value): string
{
    $text = trim((string) $value);

    if ($text === '') {
        return '';
    }

    $text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text) ?? '';

    return trim($text);
}

/**
 * Extract clean plain text summary from article content (which can be HTML or JSON).
 */
function get_article_summary_text(string $content, string $lang = 'th'): string
{
    $clean = trim($content);
    if ($clean === '') {
        return '';
    }

    $decoded = json_decode(htmlspecialchars_decode($clean, ENT_QUOTES), true);
    if (!is_array($decoded)) {
        $decoded = json_decode($clean, true);
    }

    if (is_array($decoded)) {
        $texts = [];
        foreach ($decoded as $section) {
            $secLang = $section['lang'] ?? 'th';
            if ($secLang === $lang) {
                if (!empty($section['topic'])) {
                    $texts[] = $section['topic'];
                }
                if (!empty($section['body'])) {
                    $texts[] = $section['body'];
                }
            }
        }
        $combined = implode(' ', $texts);
        return normalize_text($combined);
    }

    // Bulletproof Fallback: extract topic/body text using regex if json_decode fails
    preg_match_all('/"(body|topic)"\s*:\s*"((?>[^"\\\\]++|\\\\.)*)"/s', $clean, $matches);
    if (!empty($matches[2])) {
        $texts = [];
        foreach ($matches[2] as $val) {
            $texts[] = stripslashes($val);
        }
        $combined = implode(' ', $texts);
        return normalize_text(strip_tags($combined));
    }

    return normalize_text(strip_tags($clean));
}

/**
 * Return the first non-empty normalized candidate, or the default.
 *
 * @param array<int, mixed> $candidates
 */
function seo_fallback(array $candidates, string $default = ''): string
{
    foreach ($candidates as $candidate) {
        $value = normalize_text($candidate);

        if ($value !== '') {
            return $value;
        }
    }

    return normalize_text($default);
}

/**
 * Build scheme + host from the current request (empty when CLI or missing host).
 */
function request_origin_url(): string
{
    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');

    if ($host === '') {
        return '';
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    return $scheme . '://' . $host;
}

/**
 * Full absolute URL for the current page request.
 */
function current_request_url(): string
{
    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');

    if ($requestUri === '') {
        return app_url('');
    }

    $origin = request_origin_url();

    if ($origin === '') {
        return app_url(ltrim($requestUri, '/'));
    }

    return $origin . $requestUri;
}

/**
 * Resolve a relative or absolute path to a full URL.
 */
function absolute_url(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $url) === 1 || str_starts_with($url, '//')) {
        return $url;
    }

    $origin = request_origin_url();

    if ($origin !== '') {
        return $origin . '/' . ltrim($url, '/');
    }

    return app_url(ltrim($url, '/'));
}

/**
 * Resolve an image path for Open Graph / schema (supports uploads, assets, absolute URLs).
 */
function seo_image_url(?string $path, string $fallbackPath = 'images/logo.png'): string
{
    $candidate = normalize_text($path ?? '');

    if ($candidate === '') {
        $candidate = normalize_text($fallbackPath);
    }

    if ($candidate === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $candidate) === 1 || str_starts_with($candidate, '//')) {
        return $candidate;
    }

    if (str_starts_with($candidate, '/')) {
        return absolute_url($candidate);
    }

    if (str_starts_with($candidate, 'assets/') || str_starts_with($candidate, 'images/') || str_starts_with($candidate, 'public/assets/')) {
        return absolute_url(asset_url($candidate));
    }

    return absolute_url(asset_url('images/' . ltrim($candidate, '/')));
}

/**
 * Application base path from config (no trailing slash).
 */
function app_base_url(): string
{
    return rtrim((string) config('app.base_url', ''), '/');
}

/**
 * Public asset root path from config (no trailing slash).
 */
function asset_base_url(): string
{
    return rtrim((string) config('app.asset_base_url', app_base_url()), '/');
}

/**
 * Build an application URL relative to base_url.
 */
function app_url(string $path = ''): string
{
    $baseUrl = app_base_url();
    $normalizedPath = ltrim($path, '/');

    if ($normalizedPath === '') {
        return $baseUrl !== '' ? $baseUrl : '/';
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/' . $normalizedPath;
}

/**
 * Build a front-controller URL using the ?url= query parameter (legacy routing style).
 *
 * @param array<string, scalar|null> $query
 */
/**
 * Maps English route paths to Thai route paths and vice versa.
 */
function translate_route_path(string $path, string $toLang): string
{
    $map = [
        'about' => 'เกี่ยวกับเรา',
        'services' => 'บริการของเรา',
        'erp' => 'ระบบ-erp',
        'article' => 'บทความ',
        'articles' => 'บทความ',
        'contact' => 'ติดต่อเรา',
    ];

    $parts = explode('/', trim($path, '/'));
    $translatedParts = [];

    foreach ($parts as $part) {
        $decodedPart = urldecode($part);
        if ($toLang === 'th') {
            $translatedParts[] = isset($map[$decodedPart]) ? $map[$decodedPart] : $part;
        } else {
            $flipped = array_flip($map);
            $flipped['บทความ'] = 'article';
            $translatedParts[] = isset($flipped[$decodedPart]) ? $flipped[$decodedPart] : $part;
        }
    }

    return implode('/', $translatedParts);
}

function route_url(string $path, array $query = []): string
{
    $hash = '';
    $hashPos = strpos($path, '#');
    if ($hashPos !== false) {
        $hash = substr($path, $hashPos);
        $path = substr($path, 0, $hashPos);
    }

    $normalizedPath = trim($path, '/');
    $baseUrl = rtrim(app_url(''), '/');
    
    $lang = function_exists('getCurrentLang') ? getCurrentLang() : 'th';

    if ($lang === 'en') {
        // Enforce English path names and append /en when language is English
        $translatedPath = translate_route_path($normalizedPath, 'en');
        if ($translatedPath === '' || $translatedPath === 'index') {
            $url = $baseUrl . '/en';
        } else {
            $url = $baseUrl . '/' . $translatedPath . '/en';
        }
    } else {
        // Enforce Thai path names when language is Thai
        $translatedPath = translate_route_path($normalizedPath, 'th');
        if ($translatedPath === '' || $translatedPath === 'index') {
            $url = $baseUrl . '/';
        } else {
            $url = $baseUrl . '/' . rawurldecode($translatedPath);
        }
    }

    if ($query !== []) {
        $url .= '?' . http_build_query($query);
    }

    return $url . $hash;
}

/**
 * Resolve a path under frontend/public/assets.
 *
 * Accepts: images/logo.png, assets/css/tailwind.css, public/assets/...
 */
function asset_url(string $path): string
{
    $normalizedPath = ltrim($path, '/');

    // baseUrl already points to .../frontend/public — strip redundant prefixes.
    if (str_starts_with($normalizedPath, 'public/assets/')) {
        $normalizedPath = substr($normalizedPath, strlen('public/assets/'));
    }

    if (str_starts_with($normalizedPath, 'assets/')) {
        $normalizedPath = substr($normalizedPath, strlen('assets/'));
    }

    $baseUrl = asset_base_url();

    if ($normalizedPath === '') {
        return ($baseUrl !== '' ? $baseUrl : '') . '/assets';
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/assets/' . $normalizedPath;
}

/**
 * Normalize an article image reference and return a trusted URL or fallback.
 *
 * @param string|null $imagePath  Value from the database or CMS (nullable).
 * @param string      $fallback   Already-resolved fallback URL (typically from asset_url()).
 * @return string
 */
function resolve_article_image_url(?string $imagePath, string $fallback = ''): string
{
    $candidate = trim((string) $imagePath);

    if ($fallback === '') {
        $fallback = asset_url('images/story.png');
    }

    if ($candidate === '') {
        return $fallback;
    }

    if (preg_match('#^https?://#i', $candidate) === 1) {
        return $candidate;
    }

    $cleanPath = ltrim($candidate, '/');

    // Determine project root directory path
    $projectRoot = dirname(__DIR__, 3);

    $relativeDiskPath = '';
    $webPath = '';

    if (str_starts_with($cleanPath, 'admin/uploads/')) {
        $relativeDiskPath = $cleanPath;
        $webPath = '/' . $cleanPath;
    } elseif (str_starts_with($cleanPath, 'uploads/')) {
        $relativeDiskPath = 'admin/' . $cleanPath;
        $webPath = '/admin/' . $cleanPath;
    } else {
        $relativeDiskPath = 'admin/uploads/' . $cleanPath;
        $webPath = '/admin/uploads/' . $cleanPath;
    }

    $fullDiskPath = $projectRoot . '/' . $relativeDiskPath;

    if (is_file($fullDiskPath)) {
        return app_base_url() . $webPath;
    }

    if (str_starts_with($cleanPath, 'uploads/')) {
        return app_base_url() . '/admin/' . $cleanPath;
    }

    // Check in public/assets/
    $assetDiskPath = $projectRoot . '/frontend/public/assets/' . $cleanPath;
    if (is_file($assetDiskPath)) {
        return asset_url($cleanPath);
    }

    // Check in public/assets/images/
    $imageAssetDiskPath = $projectRoot . '/frontend/public/assets/images/' . $cleanPath;
    if (is_file($imageAssetDiskPath)) {
        return asset_url('images/' . $cleanPath);
    }

    return $fallback;
}

/**
 * Resolve partner logo image path to a public URL.
 *
 * @param string|null $path
 * @return string
 */
function partner_logo_url(?string $path): string
{
    $path = trim((string) $path);
    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $path) === 1 || str_starts_with($path, '//')) {
        return $path;
    }

    if (str_contains($path, '/')) {
        return app_base_url() . '/admin/' . ltrim($path, '/');
    }

    return app_base_url() . '/admin/uploads/' . $path;
}

/**
 * Generate or get the existing CSRF token from the session.
 */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }

    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Return a hidden HTML input containing the CSRF token.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF token from POST request or HTTP header.
 */
function verify_csrf_token(?string $token = null): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }

    $sessionToken = $_SESSION['csrf_token'] ?? '';
    if (!is_string($sessionToken) || $sessionToken === '') {
        return false;
    }

    $providedToken = $token ?? ($_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''));
    if (!is_string($providedToken) || $providedToken === '') {
        return false;
    }

    return hash_equals($sessionToken, $providedToken);
}

/**
 * Send standard web security headers.
 */
function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
}
