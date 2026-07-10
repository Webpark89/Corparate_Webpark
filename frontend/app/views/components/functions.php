<?php
declare(strict_types=1);

/**
 * Validates and gets the current language from query parameter or cookie.
 * Saves to cookie if a valid language is passed via query parameter.
 * 
 * @return string Current language code ('th' or 'en')
 */
function getCurrentLang(): string {
    $allowedLangs = ['th', 'en'];
    $defaultLang = 'th';
    
    // 1. Check query parameter
    if (isset($_GET['lang']) && is_string($_GET['lang'])) {
        $lang = strtolower($_GET['lang']);
        if (in_array($lang, $allowedLangs, true)) {
            // Save to cookie for 1 year if headers haven't been sent yet
            if (!headers_sent()) {
                setcookie('lang', $lang, time() + (86400 * 365), '/');
            }
            return $lang;
        }
    }
    
    // 2. Check cookie
    if (isset($_COOKIE['lang']) && is_string($_COOKIE['lang'])) {
        $lang = strtolower($_COOKIE['lang']);
        if (in_array($lang, $allowedLangs, true)) {
            return $lang;
        }
    }
    
    // 3. Fallback to default
    return $defaultLang;
}

/**
 * Loads and caches language array based on requested language.
 * 
 * @param string $lang Language code
 * @return array Language translation array
 */
function loadLanguage(string $lang = 'th'): array {
    static $cache = [];
    
    $allowedLangs = ['th', 'en'];
    if (!in_array($lang, $allowedLangs, true)) {
        $lang = 'th';
    }
    
    if (isset($cache[$lang])) {
        return $cache[$lang];
    }
    
    $file = __DIR__ . "/lang_{$lang}.php";
    if (file_exists($file)) {
        $cache[$lang] = include $file;
    } else {
        $cache[$lang] = include __DIR__ . "/lang_th.php";
    }
    
    return $cache[$lang];
}

/**
 * Translates a key into the current language.
 * Supports dot notation (e.g., 'home.hero_title') and variable replacement.
 * 
 * @param string $key Dot notation key
 * @param array|null $replace Array of placeholders to replace, e.g., ['name' => 'John']
 * @return string Translated string or the original key if not found
 */
function t(string $key, ?array $replace = null): string {
    $lang = getCurrentLang();
    $translations = loadLanguage($lang);
    
    $keys = explode('.', $key);
    $value = $translations;
    
    // Find key in current language
    foreach ($keys as $k) {
        if (is_array($value) && isset($value[$k])) {
            $value = $value[$k];
        } else {
            $value = null;
            break;
        }
    }
    
    // If not found in current language, and current language is not 'th', fallback to 'th'
    if (!is_string($value) && $lang !== 'th') {
        $thTranslations = loadLanguage('th');
        $value = $thTranslations;
        foreach ($keys as $k) {
            if (is_array($value) && isset($value[$k])) {
                $value = $value[$k];
            } else {
                $value = null;
                break;
            }
        }
    }
    
    // Final fallback to the key itself
    if (!is_string($value)) {
        return $key;
    }
    
    // Replace placeholders if provided
    if ($replace !== null) {
        foreach ($replace as $search => $replacement) {
            $value = str_replace('{' . $search . '}', (string)$replacement, $value);
        }
    }
    
    return $value;
}

/**
 * Returns the current URL with the specified lang query parameter.
 * Preserves other existing query parameters.
 * 
 * @param string $lang Language code to set ('th' or 'en')
 * @return string The resulting URL
 */
function current_url_with_lang(string $lang): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '/';
    
    // Get existing query parameters
    $query = $_GET;
    // Update or add the lang parameter
    $query['lang'] = $lang;
    
    // Rebuild the query string
    $queryString = http_build_query($query);
    
    return $protocol . $host . $path . '?' . $queryString;
}
