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
<!-- Top Reading Progress Bar -->
<div id="reading-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 z-[9999] transition-all duration-150 ease-out" style="width: 0%;"></div>

<section class="relative overflow-hidden font-sans bg-[#F4F7FB] pt-12 pb-6 lg:pt-20 lg:pb-8">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            
            <!-- Left Column: Text & Meta -->
            <div class="max-w-xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-8">
                    <ol class="inline-flex flex-wrap items-center text-sm md:text-base font-medium text-slate-400">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('article_detail.breadcrumb_home', ['default' => 'หน้าแรก'])) ?>
                            </a>
                        </li>
                        <li><span class="mx-4">/</span></li>
                        <li>
                            <a href="<?= e(route_url('/article')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('article_detail.breadcrumb_articles', ['default' => 'บทความ'])) ?>
                            </a>
                        </li>
                        <li><span class="mx-4">/</span></li>
                        <li aria-current="page">
                            <span class="text-slate-400 line-clamp-1"><?= e($category) ?></span>
                        </li>
                    </ol>
                </nav>
                
                <?php 
                    $isLongTitle = mb_strlen($title) > 50;
                    $titleClass = $isLongTitle 
                        ? 'text-2xl md:text-3xl lg:text-[28px] leading-[1.5] tracking-normal' 
                        : 'text-3xl md:text-4xl lg:text-[44px] leading-snug tracking-tight'; 
                ?>
                <h1 class="animate-fade-up delay-200 mb-6">
                    <span class="block <?= $titleClass ?> font-bold text-[#022862]">
                        <?= e($title) ?>
                    </span>
                </h1>
                
                <div class="animate-fade-up delay-300 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-[#0663F6] font-medium mb-6">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <?= e($formattedDate) ?>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        <?= e($readingMinutes) ?> นาทีในการอ่าน
                    </span>
                    <?php if ($author !== ''): ?>
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                            <?= e($author) ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <p class="animate-fade-up delay-400 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                    <?= e($summary) ?>
                </p>
            </div>
            
            <!-- Right Column: Image -->
            <div class="animate-fade-up delay-300 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e($coverImage) ?>" alt="<?= e($title) ?>" 
                    class="w-full h-auto object-cover aspect-[4/3] hover:scale-105 transition-transform duration-700" onerror="this.src='<?= e($fallbackImage) ?>'">
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
    .article-format h2 {
        color: #0663F6; /* Primary Blue */
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.4;
        font-size: 1.5rem;
    }
    .article-format h3 {
        color: #022862; /* Dark Navy */
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0;
        line-height: 1.4;
        font-size: 1.125rem;
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
</style>

<section class="py-12 bg-[#F7F9FC]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <article class="lg:col-span-8 bg-white rounded-[2rem] p-6 md:p-10 shadow-sm border border-slate-100">

                <div class="article-format">
                    
                    <?= $content ?>

                    </div>



            </article>

            <!-- Sidebar Right: Related Articles -->
            <div class="lg:col-span-4 relative h-full">
                <div class="bg-white rounded-[2rem] p-5 lg:p-6 border border-slate-100 shadow-sm sticky top-[100px] h-max z-20 max-h-[calc(100vh-140px)] overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" style="position: sticky; top: 100px;">
                    <h4 class="text-[19px] font-bold text-[#0663F6] mb-4">
                        <?= e(getCurrentLang() === 'th' ? 'บทความที่เกี่ยวข้อง' : 'Related Articles') ?>
                    </h4>
                    <div class="space-y-4">
                        <?php foreach($relatedArticles as $item): ?>
                            <a href="<?= route_url('/article', ['id' => (int)$item['id']]) ?>" class="block group bg-white border border-slate-100 rounded-[1.25rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <div class="relative w-full overflow-hidden" style="height: 160px;">
                                    <img src="<?= resolve_article_image_url($item['image_path'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" style="object-position: center 25%;" alt="<?= e($item['title']) ?>">
                                </div>
                                <div class="p-4 flex flex-col justify-between">
                                    <?php 
                                    $itemLang = getCurrentLang();
                                    $itemTitle = $itemLang === 'en' && !empty($item['meta_title_en']) ? $item['meta_title_en'] : ($item['title'] ?? '');
                                    $itemDesc = $itemLang === 'en' && !empty($item['meta_description_en']) ? $item['meta_description_en'] : ($item['description'] ?? '');
                                    ?>
                                    <h5 class="text-[14px] font-bold text-[#0663F6] mb-1.5 line-clamp-2 leading-snug group-hover:underline"><?= e($itemTitle) ?></h5>
                                    <p class="text-[11.5px] text-slate-500 mb-3 line-clamp-2 leading-relaxed"><?= e($itemDesc) ?></p>
                                    <div class="text-right">
                                        <span class="text-[#0663F6] text-[12px] font-bold"><?= e(getCurrentLang() === 'th' ? 'อ่านเพิ่มเติม' : 'Read more') ?> &rarr;</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reading Progress Bar
    const progressBar = document.getElementById('reading-progress');
    window.addEventListener('scroll', () => {
        const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (totalHeight > 0 && progressBar) {
            const progress = (window.scrollY / totalHeight) * 100;
            progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
        }
    }, { passive: true });

    // GSAP Related Articles Reveal
    gsap.registerPlugin(ScrollTrigger);
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    if (!prefersReducedMotion) {
        gsap.from(".lg\\:col-span-4 a", {
            scrollTrigger: {
                trigger: ".lg\\:col-span-4",
                start: "top 85%",
                toggleActions: "play none none reverse"
            },
            y: 20,
            opacity: 0,
            duration: 0.5,
            stagger: 0.1,
            ease: "power2.out"
        });
    }
});
</script>
