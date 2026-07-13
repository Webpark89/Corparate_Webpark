<?php

declare(strict_types=1);

/**
 * Single article detail page view with sidebar TOC, related articles, and share widgets.
 */

$article = is_array($article) ? $article : [];
$siteName = config('app.name', 'WEBPARK');
$fallbackImage = asset_url('images/story.png');
$coverImage = resolve_article_image_url(
    $article['image_path'] ?? $article['image'] ?? '',
    $fallbackImage
);

$months = [
    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
    7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.',
];

$title = normalize_text($article['title'] ?? 'ระบบ ERP คืออะไร? สรุปครบ จบที่เดียว!');
$author = normalize_text($article['author'] ?? 'Webpark Team');
$content = (string) ($article['content'] ?? $article['summary'] ?? '');

$decodedSections = json_decode($content, true);
if (is_array($decodedSections)) {
    $currentLang = getCurrentLang();
    $filteredSections = array_filter($decodedSections, function($sec) use ($currentLang) {
        return ($sec['lang'] ?? 'th') === $currentLang;
    });
    if (empty($filteredSections)) {
        $filteredSections = array_filter($decodedSections, function($sec) {
            return ($sec['lang'] ?? 'th') === 'th';
        });
    }
    $htmlParts = [];
    foreach ($filteredSections as $sec) {
        if (!empty($sec['topic'])) {
            $htmlParts[] = '<h2>' . e($sec['topic']) . '</h2>';
        }
        if (!empty($sec['body'])) {
            $htmlParts[] = '<div>' . $sec['body'] . '</div>';
        }
    }
    $content = implode("\n", $htmlParts);
}

$summary = normalize_text($article['summary'] ?? '');
$category = normalize_text($article['category'] ?? 'ERP System');
$relatedArticles = $relatedArticles ?? [];

$date = $article['created_at'] ?? '';
$ts = !empty($date) ? strtotime($date) : false;
$formattedDate = $ts ? sprintf('%d %s %d', date('j', $ts), $months[(int) date('n', $ts)] ?? '', date('Y', $ts) + 543) : '24 พฤษภาคม 2567';

// Reading time estimate
$wordCount = mb_strlen(strip_tags($content));
$readingMinutes = $wordCount > 0 ? max(1, (int) ceil($wordCount / 350)) : 12;

// Popular tags
$popularCategories = $popularCategories ?? [];

/**
 * สร้างสารบัญบทความ (Table of Contents) จากหัวข้อ h2/h3 ภายในเนื้อหา
 * พร้อมใส่ id ให้กับหัวข้อแต่ละหัวข้อในเนื้อหาเพื่อให้ลิงก์เลื่อนไปยังตำแหน่งได้
 */
function build_table_of_contents(string $html): array
{
    $toc = [];

    if (trim($html) === '') {
        return [$html, $toc];
    }

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
    libxml_clear_errors();

    $xpath = new DOMXPath($doc);
    $headings = $xpath->query('//h2 | //h3');

    if ($headings === false || $headings->length === 0) {
        return [$html, $toc];
    }

    $usedSlugs = [];
    foreach ($headings as $index => $heading) {
        /** @var DOMElement $heading */
        $text = trim($heading->textContent);
        if ($text === '') {
            continue;
        }

        $slug = 'toc-section-' . ($index + 1);
        $usedSlugs[$slug] = true;
        $heading->setAttribute('id', $slug);

        $toc[] = [
            'id' => $slug,
            'text' => $text,
            'level' => strtolower($heading->nodeName) === 'h3' ? 3 : 2,
        ];
    }

    $root = $doc->getElementById('__root');
    $innerHtml = '';
    if ($root !== null) {
        foreach ($root->childNodes as $child) {
            $innerHtml .= $doc->saveHTML($child);
        }
    } else {
        $innerHtml = $html;
    }

    return [$innerHtml, $toc];
}

[$content, $tableOfContents] = build_table_of_contents($content);

$shareUrl = urlencode(request_origin_url() . ($_SERVER['REQUEST_URI'] ?? ''));

?>

<style>
    /* แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง (Entrance) — ใช้ชุดเดียวกับหน้า article list */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($coverImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover object-center opacity-100 mix-blend-screen">
            
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6">
                    <ol class="inline-flex flex-wrap items-center text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('article_detail.breadcrumb_home')) ?>
                            </a>
                        </li>

                        <li>
                            <span class="text-slate-400" style="margin: 0 4px;">/</span>
                        </li>

                        <li>
                            <a href="<?= e(route_url('/article')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('article_detail.breadcrumb_articles')) ?>
                            </a>
                        </li>

                        <li>
                            <span class="text-slate-400" style="margin: 0 4px;">/</span>
                        </li>

                        <li aria-current="page">
                            <span class="text-slate-400 line-clamp-1"><?= e($title) ?></span>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                    <span class="text-3xl md:text-4xl lg:text-6xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">
                        <?= e($title) ?>
                    </span>
                </h1>
                <div class="animate-fade-up delay-300 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <?= e($formattedDate) ?>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        <?= e($readingMinutes) ?> นาทีในการอ่าน
                    </span>
                    <?php if ($author !== ''): ?>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                            <?= e($author) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                    
                </p>
            </div>
        </div>
    </div>
</section>
<style>
    /* Article Typography */
    .article-format {
        color: #475569; /* slate-600 */
        font-size: 1rem;
        line-height: 1.8;
    }
    .article-format h2, 
    .article-format h3 {
        color: #0d6efd; /* สีน้ำเงินตามภาพ (ปรับเป็นสี Primary ของคุณได้) */
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    .article-format h2 {
        font-size: 1.5rem;
    }
    .article-format h3 {
        font-size: 1.25rem;
    }
    .article-format p {
        margin-bottom: 1.25rem;
    }
    .article-format ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .article-format ul li {
        margin-bottom: 0.5rem;
    }
    .article-format ul li::marker {
        color: #0d6efd; /* สีจุด Bullet */
    }
    .article-format ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700; /* ทำให้ตัวเลขและหัวข้อหนาตามภาพ */
        color: #022862;
    }
    .article-format ol p, 
    .article-format ol span {
        font-weight: 400; /* เนื้อหาในข้อไม่ต้องหนา */
        color: #475569;
        display: block;
        margin-top: 0.25rem;
    }
</style>

<section class="py-12 bg-[#F7F9FC]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <article class="lg:col-span-8 bg-white rounded-[2rem] p-6 md:p-10 shadow-sm border border-slate-100">

                <div class="article-format">
                    
                    <?= $content ?>

                    </div>

                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <h2 class="text-2xl md:text-3xl font-extrabold text-center text-[#022862] tracking-tight py-10">
                            ERP ที่ช่วยยกระดับธุรกิจของคุณ
                        </h2>

                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
                            <?php
                            $erpBenefits = [
                                [
                                    'title' => 'ข้อมูลครบถ้วน',
                                    'desc' => 'รวมทุกแผนกไว้ในระบบเดียว',
                                    'icon' => asset_url('images/ERP_5.svg'),
                                ],
                                [
                                    'title' => 'ลดงานซ้ำซ้อน',
                                    'desc' => 'เพิ่มประสิทธิภาพการทำงาน',
                                    'icon' => asset_url('images/ERP_6.svg'),
                                ],
                                [
                                    'title' => 'ข้อมูลเรียลไทม์',
                                    'desc' => 'ตัดสินใจได้แม่นยำและรวดเร็ว',
                                    'icon' => asset_url('images/ERP_7.svg'),
                                ],
                                [
                                    'title' => 'ควบคุมความเสี่ยง',
                                    'desc' => 'ตรวจสอบและติดตามได้ทุกขั้นตอน',
                                    'icon' => asset_url('images/ERP_8.svg'),
                                ],
                                [
                                    'title' => 'ขยายได้ตามธุรกิจ',
                                    'desc' => 'รองรับการเติบโตในอนาคต',
                                    'icon' => asset_url('images/ERP_9.svg'),
                                ],
                            ];
                            ?>
                            <?php foreach ($erpBenefits as $benefit): ?>
                                <div class="bg-white rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div class="w-14 h-14 mx-auto bg-blue-50/70 rounded-full flex items-center justify-center mb-4">
                                        <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain">
                                    </div>
                                    <h4 class="text-sm font-bold text-[#043B94] mb-1"><?= e($benefit['title']) ?></h4>
                                    <p class="text-xs text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

            </article>

            <aside class="lg:col-span-4">

                <div class="sticky top-24 bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">

                    <h3 class="text-xl font-bold text-[#0d6efd] mb-6 px-1">
                        บทความที่เกี่ยวข้อง
                    </h3>

                    <div class="flex flex-col gap-6">
                        <?php foreach($relatedArticles as $item): ?>

                        <a href="<?= route_url('/article/'.$item['slug']) ?>"
                           class="group block overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">

                            <img
                                src="<?= resolve_article_image_url($item['image_path']) ?>"
                                class="h-48 w-full object-cover transition duration-300 group-hover:scale-105"
                                alt="<?= e($item['title']) ?>"
                            >

                            <div class="p-5 relative bg-white">

                                <h4 class="line-clamp-2 font-bold text-[#022862] group-hover:text-[#0d6efd] transition-colors text-lg leading-snug">
                                    <?= e($item['title']) ?>
                                </h4>

                                <p class="mt-3 line-clamp-2 text-sm text-slate-500 leading-relaxed">
                                    <?= e($item['summary']) ?>
                                </p>

                                <div class="mt-4 flex items-center text-[#0d6efd] font-semibold text-sm group-hover:gap-2 transition-all">
                                    อ่านเพิ่มเติม 
                                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>

                            </div>

                        </a>

                        <?php endforeach; ?>
                    </div>

                </div>

            </aside>
        </div>

    </div>
</section>
