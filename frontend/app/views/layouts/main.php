<?php

declare(strict_types=1);

/**
 * Main HTML layout — SEO meta, Open Graph, JSON-LD, and page shell.
 *
 * Expects $content from Controller::view(); optional SEO variables from controller.
 */
$siteName = $siteName ?? config('app.name', 'WEBPARK');
$title = seo_fallback([$metaTitle ?? '', $title ?? '', $siteName], $siteName);
$metaDescription = seo_fallback([$metaDescription ?? '', config('app.description', ''), $siteName], $siteName);
$canonicalUrl = seo_fallback([$canonicalUrl ?? '', current_request_url()], current_request_url());
$imageUrl = seo_image_url($imageUrl ?? '', 'images/story.png');
$imageAlt = seo_fallback([$imageAlt ?? '', $title ?? '', $siteName], $siteName);
$type = seo_fallback([$type ?? '', 'article'], 'article');
$publishedTime = seo_fallback([$publishedTime ?? ''], '');
$modifiedTime = seo_fallback([$modifiedTime ?? ''], '');
$authorName = seo_fallback([$authorName ?? '', $siteName], $siteName);
$robots = seo_fallback([$robots ?? 'index, follow'], 'index, follow');
$jsonLd = isset($jsonLd) && is_array($jsonLd) ? $jsonLd : [];
$jsonGraph = [];
$tailwindCssFile = realpath(__DIR__ . '/../../../public/assets/css/tailwind.css');
$tailwindCssVersion = $tailwindCssFile !== false ? filemtime($tailwindCssFile) : time();

if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}

if ($jsonLd !== []) {
    if (isset($jsonLd['@graph']) && is_array($jsonLd['@graph'])) {
        $jsonGraph = $jsonLd;
    } else {
        $isList = array_keys($jsonLd) === range(0, count($jsonLd) - 1);

        $jsonGraph = [
            '@context' => 'https://schema.org',
            '@graph' => $isList ? $jsonLd : [$jsonLd],
        ];
    }
}

$currentPage = $currentPage ?? '';
$content = $content ?? '';
?>
<!DOCTYPE html>
<html lang="<?= e(function_exists('getCurrentLang') ? getCurrentLang() : 'th') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta property="og:type" content="<?= e($type) ?>">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:image" content="<?= e($imageUrl) ?>">
    <meta property="og:image:alt" content="<?= e($imageAlt) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <?php if ($publishedTime !== ''): ?>
        <meta property="article:published_time" content="<?= e($publishedTime) ?>">
    <?php endif; ?>
    <?php if ($modifiedTime !== ''): ?>
        <meta property="article:modified_time" content="<?= e($modifiedTime) ?>">
    <?php endif; ?>
    <?php if ($authorName !== ''): ?>
        <meta property="article:author" content="<?= e($authorName) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($title) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($imageUrl) ?>">
    <meta name="twitter:image:alt" content="<?= e($imageAlt) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Prioritize Inter for English characters to fix spacing and improve aesthetics */
        body, .font-sans {
            font-family: 'Inter', 'Noto Sans Thai', ui-sans-serif, system-ui, sans-serif !important;
        }
    </style>
    <link rel="stylesheet" href="<?= e(asset_url('assets/css/tailwind.css')) ?>?v=<?= e($tailwindCssVersion) ?>">
    <?php if ($jsonGraph !== []): ?>
        <script type="application/ld+json">
            <?= json_encode($jsonGraph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        </script>
    <?php endif; ?>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <?php require __DIR__ . '/../components/navbar.php'; ?>

    <main class="min-h-screen">
        <?= $content ?>
    </main>

    <?php if ($currentPage !== 'contact'): ?>
        <?php require __DIR__ . '/../components/cta.php'; ?>
    <?php endif; ?>
    
    <?php require __DIR__ . '/../components/footer.php'; ?>

    <script src="<?= e(asset_url('assets/js/main.js')) ?>"></script>
</body>

</html>
