<?php

declare(strict_types=1);

$project = $project ?? [];
$relatedPortfolio = $relatedPortfolio ?? [];
$siteName = config('app.name', 'WEBPARK');
$temporaryImage = asset_url('images/story.png');

$title = normalize_text($project['title'] ?? '');
$clientName = normalize_text($project['client_name'] ?? $project['industry'] ?? '');
$categoryName = normalize_text($project['category'] ?? '');
$summary = normalize_text($project['summary'] ?? '');
$metaTitle = normalize_text($project['meta_title'] ?? '');
$metaDescription = normalize_text($project['meta_description'] ?? '');
$metaKeywords = normalize_text($project['meta_keywords'] ?? '');
$description = (string) ($project['description'] ?? '');
$tech = normalize_text($project['tech_stack'] ?? $project['metric'] ?? '');
$web = trim((string) ($project['web_path'] ?? ''));
$imageAlt = seo_fallback([
    $project['cover_image_alt'] ?? '',
    $title,
    $clientName,
    $siteName,
], $siteName);
$imageSrc = $temporaryImage;
$title = seo_fallback([$metaTitle, $title, $clientName, $siteName], $siteName);

$excerpt = static function (string $text, int $limit = 180): string {
    $text = normalize_text($text);

    if ($text === '') {
        return '';
    }

    if (function_exists('mb_substr')) {
        return normalize_text(mb_substr($text, 0, $limit));
    }

    return normalize_text(substr($text, 0, $limit));
};

$metaDescription = seo_fallback([
    $metaDescription,
    $summary,
    $excerpt($description),
    $title,
    $siteName,
], $siteName);
$metaKeywords = seo_fallback([
    $metaKeywords,
    implode(', ', array_filter([$title, $clientName, $categoryName, $tech, $siteName])),
], $siteName);
$authorName = seo_fallback([$clientName, $siteName], $siteName);
$canonicalUrl = absolute_url(route_url('/portfolio', ['id' => (int) ($project['id'] ?? 0)]));
$imageUrl = absolute_url($temporaryImage);
$imageAlt = $imageAlt;
$type = 'article';

$months = [1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'];
$date = $project['created_at'] ?? '';
$ts = $date === '' ? false : strtotime($date);
if ($ts === false || $ts <= 0) {
    $formattedDate = '';
} else {
    $day = date('j', $ts);
    $month = $months[(int) date('n', $ts)] ?? '';
    $year = date('Y', $ts);
    $formattedDate = trim("$day $month $year");
}

$publishedTime = $ts ? date('c', $ts) : date('c');
$modifiedTime = $publishedTime;

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $title,
    'description' => $metaDescription,
    'url' => $canonicalUrl,
    'image' => [$imageUrl],
    'author' => [
        '@type' => 'Organization',
        'name' => $authorName,
    ],
    'creator' => [
        '@type' => 'Organization',
        'name' => $siteName,
    ],
    'datePublished' => $publishedTime,
    'dateModified' => $modifiedTime,
    'keywords' => $metaKeywords,
];

$renderContent = static function (string $text): string {
    $text = trim($text);
    if ($text === '') {
        return '';
    }

    if (preg_match('/<[^>]+>/', $text) === 1) {
        return $text;
    }

    return nl2br(e($text));
};

?>


<section class="bg-slate-950 text-white">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <a class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white/90 transition hover:bg-white/10" href="<?= e(route_url('/portfolio')) ?>">
            <span aria-hidden="true">‹</span>
            ย้อนกลับ
        </a>

        <div class="mt-10 max-w-4xl">
            <p class="text-sm font-semibold uppercase tracking-[0.32em] text-cyan-300"><?= e($categoryName) ?></p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl"><?= e($title) ?></h1>
            <div class="mt-5 flex flex-wrap gap-3 text-sm font-semibold text-slate-200">
                <?php if ($clientName): ?><span class="rounded-full bg-white/10 px-3 py-1"><?= e($clientName) ?></span><?php endif; ?>
                <?php if ($formattedDate): ?><span class="rounded-full bg-white/10 px-3 py-1"><?= e($formattedDate) ?></span><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-16 sm:py-20">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <?php if ($imageSrc !== ''): ?>
                <figure class="relative aspect-[16/9] overflow-hidden bg-slate-100">
                    <img src="<?= e($imageSrc) ?>" alt="<?= e($imageAlt !== '' ? $imageAlt : $title) ?>" loading="lazy" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                </figure>
            <?php endif; ?>

            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <p class="text-base leading-8 text-slate-600 sm:text-lg">
                    <?= e($summary) ?>
                </p>

                <?php if ($description !== ''): ?>
                    <div class="prose prose-slate mt-8 max-w-none prose-p:leading-8 prose-p:text-slate-600 prose-headings:text-slate-900">
                        <?= $renderContent($description) ?>
                    </div>
                <?php endif; ?>

                <?php if ($tech !== ''): ?>
                    <div class="mt-8 rounded-2xl bg-slate-50 px-5 py-4 text-sm font-medium text-slate-700">
                        <span class="font-semibold text-slate-900">Tech Stack:</span> <?= e($tech) ?>
                    </div>
                <?php endif; ?>

                <?php if ($web !== ''): ?>
                    <div class="mt-8">
                        <a class="inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700" href="<?= e($web) ?>" target="_blank" rel="noopener noreferrer">
                            ดูโปรเจกต์
                            <span aria-hidden="true">›</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>

<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="flex items-center justify-between gap-4">
            <span class="text-sm font-semibold uppercase tracking-[0.28em] text-sky-700">ผลงานที่เกี่ยวข้อง</span>
            <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sky-200 hover:text-sky-700" href="<?= e(route_url('/portfolio')) ?>">
                View All
                <span aria-hidden="true">›</span>
            </a>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <?php foreach (array_slice($relatedPortfolio, 0, 4) as $item): ?>
                <?php
                $relatedImageSrc = $temporaryImage;

                $relatedDate = (string) ($item['created_at'] ?? '');
                $relatedTs = $relatedDate !== '' ? strtotime($relatedDate) : false;
                $relatedFormattedDate = '';
                if ($relatedTs !== false && $relatedTs > 0) {
                    $relatedFormattedDate = sprintf('%d %s %d', date('j', $relatedTs), $months[(int) date('n', $relatedTs)] ?? '', date('Y', $relatedTs));
                }
                $relatedId = (int) ($item['id'] ?? 0);
                ?>
                <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <a href="<?= e(route_url('/portfolio', ['id' => $relatedId])) ?>" class="block">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            <?php if ($relatedImageSrc !== ''): ?>
                                <img src="<?= e($relatedImageSrc) ?>" alt="<?= e($item['title'] ?? '') ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <?php endif; ?>
                        </div>
                        <div class="p-5">
                            <div class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-700"><?= e($relatedFormattedDate) ?></div>
                            <h3 class="mt-3 text-lg font-bold leading-tight text-slate-900"><?= e($item['title'] ?? '') ?></h3>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
