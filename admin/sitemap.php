<?php
/**
 * Public XML sitemap generator for static pages, articles, and portfolio entries.
 */
require_once __DIR__ . '/config/database.php';
header('Content-Type: application/xml; charset=utf-8');
function xml_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}
function sitemap_url(
    string $loc,
    ?string $lastmod = null,
    string $changefreq = 'weekly',
    string $priority = '0.8'
): void {
    echo '<url>';
    echo '<loc>' . xml_escape($loc) . '</loc>';
    if ($lastmod) {
        echo '<lastmod>' . date('c', strtotime($lastmod)) . '</lastmod>';
    }
    echo '<changefreq>' . $changefreq . '</changefreq>';
    echo '<priority>' . $priority . '</priority>';
    echo '</url>';
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php
    sitemap_url(
        SITE_URL . '/article.php',
        null,
        'daily',
        '1.0'
    );
    sitemap_url(
        SITE_URL . '/portfolio.php',
        null,
        'weekly',
        '0.9'
    );
    $articleQuery = Database::conn()->query('
        SELECT
            id,
            created_at,
            updated_at
        FROM article
        ORDER BY created_at DESC
    ');
    foreach ($articleQuery as $article) {
        $lastModified = $article['updated_at']
            ?: $article['created_at'];
        sitemap_url(
            SITE_URL . '/article-detail.php?id=' . (int) $article['id'],
            $lastModified,
            'weekly',
            '0.8'
        );
    }
    $portfolioQuery = Database::conn()->query('
        SELECT
            id,
            created_at,
            updated_at
        FROM portfolio
        ORDER BY created_at DESC
    ');
    foreach ($portfolioQuery as $portfolio) {
        $lastModified = $portfolio['updated_at']
            ?: $portfolio['created_at'];
        sitemap_url(
            SITE_URL . '/portfolio-detail.php?id=' . (int) $portfolio['id'],
            $lastModified,
            'monthly',
            '0.7'
        );
    }
    ?>
</urlset>
