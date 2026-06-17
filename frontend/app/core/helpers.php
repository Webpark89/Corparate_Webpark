<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

function db(): PDO
{
    return Database::getInstance();
}

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

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

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

function request_origin_url(): string
{
    $host = (string) ($_SERVER['HTTP_HOST'] ?? '');

    if ($host === '') {
        return '';
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    return $scheme . '://' . $host;
}

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

function app_base_url(): string
{
    return rtrim((string) config('app.base_url', ''), '/');
}

function asset_base_url(): string
{
    return rtrim((string) config('app.asset_base_url', app_base_url()), '/');
}

function app_url(string $path = ''): string
{
    $baseUrl = app_base_url();
    $normalizedPath = ltrim($path, '/');

    if ($normalizedPath === '') {
        return $baseUrl !== '' ? $baseUrl : '/';
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/' . $normalizedPath;
}

function route_url(string $path, array $query = []): string
{
    $normalizedPath = trim($path, '/');
    $baseUrl = app_url('');

    if ($normalizedPath === '' || $normalizedPath === 'index') {
        return rtrim($baseUrl, '/') . '/';
    }

    $url = rtrim($baseUrl, '/') . '/?url=' . rawurlencode($normalizedPath);

    if ($query !== []) {
        $url .= '&' . http_build_query($query);
    }

    return $url;
}

function asset_url(string $path): string
{
    $normalizedPath = ltrim($path, '/');

    if (str_starts_with($normalizedPath, 'assets/')) {
        $normalizedPath = substr($normalizedPath, strlen('assets/'));
    }

    if (str_starts_with($normalizedPath, 'public/assets/')) {
        $normalizedPath = substr($normalizedPath, strlen('public/assets/'));
    }

    $baseUrl = asset_base_url();

    if ($normalizedPath === '') {
        return ($baseUrl !== '' ? $baseUrl : '') . '/assets';
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/assets/' . $normalizedPath;
}
