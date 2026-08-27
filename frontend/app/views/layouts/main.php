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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

        /* Global Article Format Styling for Tables */
        .article-format table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            display: block;
            overflow-x: auto;
        }
        .article-format th, 
        .article-format td {
            border: 1px solid #cbd5e1; /* slate-300 */
            padding: 0.75rem 1rem;
            text-align: left;
            vertical-align: top;
            min-width: 120px;
        }
        .article-format th {
            background-color: #f8fafc; /* slate-50 */
            font-weight: 700;
            color: #022862;
        }

        /* =========================================================================
           Master Fixed Typography & Spacing System (Locked across all pages)
           ========================================================================= */
        @media (min-width: 1025px) {
            /* 1. Main Hero Titles H1 (Locked to exactly 5.5rem, line-height 1.1, weight 900) */
            .desktop-home-hero-h1,
            .desktop-about-hero-h1,
            .desktop-services-hero-h1,
            .desktop-erp-hero-h1,
            .desktop-article-hero-h1,
            .desktop-contact-hero-h1,
            .desktop-portfolio-hero-h1,
            .desktop-subpage-hero-h1 {
                font-size: 5.5rem !important;
                line-height: 1.1 !important;
                font-weight: 900 !important;
                letter-spacing: -0.05em !important;
            }

            /* 2. Hero Subtitle / Description Paragraphs P (Locked to 1.25rem, line-height 1.75, max-width 34rem) */
            .desktop-home-hero-p,
            .desktop-about-hero-p,
            .desktop-services-hero-p,
            .desktop-erp-hero-p,
            .desktop-article-hero-p,
            .desktop-contact-hero-p,
            .desktop-portfolio-hero-p,
            .desktop-subpage-hero-p {
                font-size: 1.25rem !important;
                line-height: 1.75 !important;
                max-width: 34rem !important;
                font-weight: 600 !important;
                color: #0b1b42 !important;
                margin-top: 1.5rem !important;
                margin-bottom: 2.5rem !important;
            }

            /* 3. Hero Wide Containers (Locked to 1720px width & 2.5rem padding) */
            .desktop-wide-container-about,
            .desktop-wide-container-contact,
            .desktop-wide-container-article,
            .desktop-wide-container-cta {
                max-width: 1720px !important;
                padding-left: 2.5rem !important;
                padding-right: 2.5rem !important;
            }

            /* 4. Section Main Titles H2 (Locked to 2.75rem, line-height 1.2) */
            .desktop-section-h2 {
                font-size: 2.75rem !important;
                line-height: 1.2 !important;
                font-weight: 800 !important;
                letter-spacing: -0.03em !important;
                margin-bottom: 1rem !important;
            }

            /* 5. Section Subtitle Description P (Locked to 1.125rem, line-height 1.7) */
            .desktop-section-p {
                font-size: 1.125rem !important;
                line-height: 1.7 !important;
                color: #475569 !important;
                margin-bottom: 2rem !important;
            }
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

    <!-- Scroll to Top Button (Pure CSS to avoid Tailwind JIT issues) -->
    <style>
        #scrollToTopBtn {
            position: fixed;
            bottom: 40px; /* Positioned at bottom right, outside the hero image */
            right: 40px; /* Fully to the right */
            z-index: 99999;
            width: 50px;
            height: 50px;
            background-color: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            /* Always visible */
            opacity: 1;
            visibility: visible;
        }
        #scrollToTopBtn:hover {
            color: #1d4ed8;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }
        #scrollToTopBtn svg {
            width: 22px;
            height: 22px;
            transition: transform 0.3s ease;
        }
        #scrollToTopBtn:hover svg {
            transform: translateY(-3px);
        }
        @media (max-width: 767px) {
            #scrollToTopBtn {
                bottom: 16px;
                right: 16px;
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }
            #scrollToTopBtn svg {
                width: 18px;
                height: 18px;
            }
        }
    </style>

    <button id="scrollToTopBtn" aria-label="Scroll to top">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollBtn = document.getElementById('scrollToTopBtn');
            if (scrollBtn) {
                scrollBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>

    <script src="<?= e(asset_url('assets/js/main.js')) ?>"></script>
</body>

</html>
