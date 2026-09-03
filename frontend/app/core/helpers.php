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
 * แปลงบรรทัดหรือย่อหน้าที่ขึ้นต้นด้วยสัญลักษณ์ Bullet (•, ⁃, ▪, -, *) หรือตัวเลขรายการ (1., 1))
 * ให้เป็น <ul><li> หรือ <ol><li> แท้ เพื่อให้แสดงผลจุดสีฟ้าถูกต้อง
 */
function convert_plain_bullets_to_html(string $html): string
{
    if (trim($html) === '') {
        return '';
    }

    // Step 1: ตรวจจับและแปลงโค้ดพิเศษของ Microsoft Word
    $isWordList = (strpos($html, 'supportLists') !== false || strpos($html, 'mso-list') !== false || strpos($html, 'MsoListParagraph') !== false);
    if ($isWordList) {
        $html = preg_replace('/<!--\s*\[if\s+!supportLists\][\s\S]*?<!--\s*\[endif\]\s*-->/i', '• ', $html);
        $html = preg_replace('/<span[^>]*style="[^"]*mso-list:\s*Ignore[^"]*"[^>]*>[\s\S]*?<\/span>/i', '• ', $html);
    }

    // ลบ HTML comments ทั่วไป
    $html = preg_replace('/<!--[\s\S]*?-->/', '', $html);

    // ตรวจสอบเบื้องต้นว่ามีสัญลักษณ์ Bullet หรือ Number List หรือไม่
    $hasBullets = preg_match('/[•⁃▪▫◦·\x{2022}\x{2043}\x{25AA}\x{25AB}\x{25E6}\x{00B7}]|&bull;|&#8226;|&#x2022;/u', $html);
    $hasDashes = preg_match('/(?:^|<p[^>]*>|<br\s*\/?>)[\s\x{00A0}]*[-\*]\s+/u', $html);
    $hasNumbers = preg_match('/(?:^|<p[^>]*>|<br\s*\/?>)[\s\x{00A0}]*\d+[\.\)][\s\x{00A0}]+/u', $html);

    if (!$hasBullets && !$hasDashes && !$hasNumbers) {
        return $html;
    }

    $normalized = str_replace(['&bull;', '&#8226;', '&#x2022;'], '•', $html);

    // Normalize breaks and paragraph ends to newlines
    $str = preg_replace('/<br\s*\/?>/i', "\n", $normalized);
    $str = preg_replace('/<\/p>/i', "\n", $str);
    $str = preg_replace('/<\/div>/i', "\n", $str);
    $str = preg_replace('/<p[^>]*>/i', '', $str);
    $str = preg_replace('/<div[^>]*>/i', '', $str);

    $lines = preg_split('/\r?\n/', $str);
    $output = [];
    $currentList = [];
    $currentType = 'ul';

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || $trimmed === '&nbsp;') {
            if (!empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            continue;
        }

        // If line is already an HTML block element
        if (preg_match('/^<(h[1-6]|table|thead|tbody|tfoot|tr|th|td|blockquote|pre|figure|img|ul|ol|li)/i', $trimmed)) {
            if (!empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            $output[] = $trimmed;
            continue;
        }

        $cleanText = trim(strip_tags($trimmed));
        if ($cleanText === '') {
            continue;
        }

        // Check for bullet character •, ⁃, ▪, etc.
        if (preg_match('/^[•⁃▪▫◦·\x{2022}\x{2043}\x{25AA}\x{25AB}\x{25E6}\x{00B7}][\s\x{00A0}]*(.*)$/u', $cleanText)) {
            if ($currentType !== 'ul' && !empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            $currentType = 'ul';
            $currentList[] = preg_replace('/^(\s*<[^>]+>)*\s*[•⁃▪▫◦·\x{2022}\x{2043}\x{25AA}\x{25AB}\x{25E6}\x{00B7}][\s\x{00A0}]*/u', '', $trimmed);
        } elseif (preg_match('/^([-\*])[\s\x{00A0}]+(.*)$/u', $cleanText)) {
            if ($currentType !== 'ul' && !empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            $currentType = 'ul';
            $currentList[] = preg_replace('/^(\s*<[^>]+>)*\s*([-\*])[\s\x{00A0}]+/u', '', $trimmed);
        } elseif (preg_match('/^(\d+)[\.\)][\s\x{00A0}]+(.*)$/u', $cleanText)) {
            if ($currentType !== 'ol' && !empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            $currentType = 'ol';
            $currentList[] = preg_replace('/^(\s*<[^>]+>)*\s*(\d+)[\.\)][\s\x{00A0}]+/u', '', $trimmed);
        } else {
            if (!empty($currentList)) {
                $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
                $currentList = [];
            }
            $output[] = '<p>' . $trimmed . '</p>';
        }
    }

    if (!empty($currentList)) {
        $output[] = "<{$currentType}><li>" . implode('</li><li>', $currentList) . "</li></{$currentType}>";
    }

    return implode('', $output);
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
 * Send standard security headers.
 */
function send_security_headers(): void
{
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

/**
 * Check if the currently active session belongs to a Super-Admin.
 */
function is_super_admin_session(): bool
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if (empty($_SESSION['admin_logged_in'])) {
        return false;
    }

    $roleSlug = $_SESSION['admin_role_slug'] ?? ($_SESSION['admin_role'] ?? '');
    return $roleSlug === 'super_admin';
}

/**
 * Record traffic analytics for the site (Pageviews & Daily Unique Visitors).
 * Excludes Super-Admin sessions while counting all other visitors and roles.
 */
function track_site_traffic(): void
{
    if (is_super_admin_session()) {
        // Exclude Super-Admin from site traffic analytics
        return;
    }

    try {
        $pdo = Database::getInstance();
        $today = date('Y-m-d');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $visitorHash = hash('sha256', $ip . '|' . $ua . '|' . $today);

        // Check/Insert unique visitor log
        $stmt = $pdo->prepare('INSERT IGNORE INTO daily_visitor_logs (date, visitor_hash) VALUES (?, ?)');
        $stmt->execute([$today, $visitorHash]);
        $isNewVisitor = ($stmt->rowCount() > 0);

        if ($isNewVisitor) {
            $stmtTraffic = $pdo->prepare('
                INSERT INTO daily_traffic (date, pageviews, unique_visitors)
                VALUES (?, 1, 1)
                ON DUPLICATE KEY UPDATE
                    pageviews = pageviews + 1,
                    unique_visitors = unique_visitors + 1
            ');
        } else {
            $stmtTraffic = $pdo->prepare('
                INSERT INTO daily_traffic (date, pageviews, unique_visitors)
                VALUES (?, 1, 0)
                ON DUPLICATE KEY UPDATE
                    pageviews = pageviews + 1
            ');
        }
        $stmtTraffic->execute([$today]);
    } catch (\Throwable $e) {
        // Silently ignore to avoid interrupting page delivery
        error_log('Traffic tracking error: ' . $e->getMessage());
    }
}

/**
 * Generate or get existing CSRF token for the frontend session.
 */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    $tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
    if (empty($_SESSION[$tokenName])) {
        $_SESSION[$tokenName] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$tokenName];
}

/**
 * Render a hidden HTML input field for CSRF protection.
 */
function csrf_field(): string
{
    $tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
    return '<input type="hidden" name="' . e($tokenName) . '" value="' . e(csrf_token()) . '">';
}

/**
 * Validate submitted CSRF token against the session token.
 */
function verify_csrf_token(?string $token = null): bool
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    $tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
    $sessionToken = (string) ($_SESSION[$tokenName] ?? '');
    if ($token === null) {
        $token = (string) ($_POST[$tokenName] ?? '');
    }
    if ($sessionToken === '' || $token === '') {
        return false;
    }
    return hash_equals($sessionToken, $token);
}

/**
 * Regenerate CSRF token after successful sensitive operations (prevents replay/duplicate submits).
 */
function csrf_token_regenerate(): string
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
    $tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
    $_SESSION[$tokenName] = bin2hex(random_bytes(32));
    return $_SESSION[$tokenName];
}

/**
 * Get Google reCAPTCHA site key with environment and database fallbacks.
 */
function recaptcha_site_key(): string
{
    if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== '') {
        return RECAPTCHA_SITE_KEY;
    }
    $cfg = config('recaptcha.site_key');
    if (!empty($cfg)) {
        return (string) $cfg;
    }
    return '6Lcf_pAtAAAAAOVhatPPwrHSYXeb_0J4yXf5BrRO';
}

/**
 * Verify Google reCAPTCHA v2 response with Google's siteverify API.
 *
 * @param string|null $recaptchaResponse The response token from $_POST['g-recaptcha-response']
 * @param string|null $remoteIp Client IP address
 * @return array{success: bool, message: string}
 */
function verify_recaptcha(?string $recaptchaResponse = null, ?string $remoteIp = null): array
{
    if ($recaptchaResponse === null) {
        $recaptchaResponse = (string) ($_POST['g-recaptcha-response'] ?? '');
    }

    $token = trim($recaptchaResponse);
    if ($token === '') {
        return [
            'success' => false,
            'message' => 'กรุณายืนยันตัวตนว่าไม่ใช่โปรแกรมอัตโนมัติ (reCAPTCHA)',
        ];
    }

    // 1. Retrieve secret key from environment variable, config, or database settings
    $secretKey = getenv('RECAPTCHA_SECRET_KEY') ?: '';
    if ($secretKey === '' && defined('RECAPTCHA_SECRET_KEY')) {
        $secretKey = (string) RECAPTCHA_SECRET_KEY;
    }
    if ($secretKey === '') {
        $cfg = config('recaptcha.secret_key');
        if (!empty($cfg)) {
            $secretKey = (string) $cfg;
        }
    }
    if ($secretKey === '') {
        try {
            $settingModel = new Setting();
            $secretKey = (string) ($settingModel->getByKey('recaptcha_secret_key', '') ?? '');
        } catch (\Throwable $e) {
            $secretKey = '';
        }
    }

    // 2. Development / Localhost tolerance:
    // If secret key is not configured and server is localhost/127.0.0.1, log a warning and permit test
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocalhost = in_array(parse_url('http://' . $host, PHP_URL_HOST), ['localhost', '127.0.0.1', '::1'], true);

    if ($secretKey === '') {
        error_log('[reCAPTCHA Warning] Secret key is not set. Please configure RECAPTCHA_SECRET_KEY in server environment or admin config.');
        if ($isLocalhost) {
            return [
                'success' => true,
                'message' => 'reCAPTCHA bypassed on localhost because RECAPTCHA_SECRET_KEY is not configured yet.',
            ];
        }
        return [
            'success' => false,
            'message' => 'การยืนยันตัวตนไม่สำเร็จ: ยังไม่ได้กำหนด Secret Key บนเซิร์ฟเวอร์',
        ];
    }

    // 3. Connect to Google siteverify endpoint
    $remoteIp = $remoteIp ?? ($_SERVER['REMOTE_ADDR'] ?? '');
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $postData = http_build_query([
        'secret'   => $secretKey,
        'response' => $token,
        'remoteip' => $remoteIp,
    ]);

    $responseJson = false;

    // Prefer cURL
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $responseJson = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($responseJson === false && $curlErr !== '') {
            error_log('[reCAPTCHA cURL Error] ' . $curlErr);
        }
    }

    // Fallback to file_get_contents if cURL failed or unavailable
    if ($responseJson === false && ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($postData) . "\r\n",
                'content' => $postData,
                'timeout' => 8,
            ],
        ]);
        $responseJson = @file_get_contents($verifyUrl, false, $context);
    }

    if ($responseJson === false || $responseJson === '') {
        error_log('[reCAPTCHA Error] Could not connect to Google verification server.');
        return [
            'success' => false,
            'message' => 'ไม่สามารถเชื่อมต่อไปยังระบบตรวจสอบ reCAPTCHA ได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง',
        ];
    }

    $result = json_decode($responseJson, true);
    if (!is_array($result) || empty($result['success'])) {
        $codes = (array) ($result['error-codes'] ?? []);
        $errorCodes = !empty($codes) ? implode(', ', $codes) : 'unknown';
        error_log('[reCAPTCHA Failed] Google returned success=false. Error codes: ' . $errorCodes);

        if (in_array('timeout-or-duplicate', $codes, true)) {
            $msg = getCurrentLang() === 'th'
                ? 'การยืนยันตัวตน reCAPTCHA หมดอายุ (เกินเวลา) หรือมีการส่งซ้ำ กรุณาติ๊กช่องยืนยันตัวตนใหม่อีกครั้ง'
                : 'reCAPTCHA token expired or submitted twice. Please re-verify the checkbox.';
        } else {
            $msg = getCurrentLang() === 'th'
                ? 'การยืนยันตัวตนผ่าน reCAPTCHA ไม่ผ่าน กรุณากดช่องยืนยันตัวตนใหม่อีกครั้ง'
                : 'reCAPTCHA verification failed. Please try again.';
        }

        return [
            'success' => false,
            'message' => $msg,
        ];
    }

    return [
        'success' => true,
        'message' => 'reCAPTCHA verified successfully.',
    ];
}
