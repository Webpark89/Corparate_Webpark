<?php
declare(strict_types=1);
/**
 * Single article detail page view with sidebar TOC, related articles, and share widgets.
 */
$article = is_array($article) ? $article : [];
$siteName = config('app.name', 'WEBPARK');
$fallbackImage = asset_url('images/story.png');
$coverImage = resolve_article_image_url(
    $article['cover_image'] ?? $article['image_path'] ?? $article['image'] ?? '',
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
if (!is_array($decodedSections)) {
    $decodedSections = json_decode(stripslashes($content), true);
}
if (!is_array($decodedSections)) {
    // Regex fallback for malformed JSON strings stored in the database
    $decodedSections = [];
    preg_match_all('/\{\s*"lang"\s*:\s*"(th|en)"\s*,\s*"topic"\s*:\s*"(.*?)"\s*,\s*"body"\s*:\s*"(.*?)"\s*\}/s', $content, $matches, PREG_SET_ORDER);
    if (!empty($matches)) {
        foreach ($matches as $m) {
            $decodedSections[] = [
                'lang' => $m[1],
                'topic' => str_replace(['\\"', '\\/'], ['"', '/'], $m[2]),
                'body' => str_replace(['\\"', '\\/'], ['"', '/'], $m[3]),
            ];
        }
    }
}

if (is_array($decodedSections) && !empty($decodedSections)) {
    $currentLang = getCurrentLang();
    $filteredSections = array_filter($decodedSections, function($sec) use ($currentLang) {
        return ($sec['lang'] ?? 'th') === $currentLang;
    });
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
// Clean up any literal escaped slashes, quotes, \r\n, and § artifacts from old database entries or Word pastes
$content = str_replace(['\r\n', '\n', '\r', '\t', '<\/', '\"', '\/', '§', '&sect;'], [' ', ' ', ' ', ' ', '</', '"', '/', '', ''], $content);
// Clean up MS Word &nbsp; indentation at start of paragraphs
$content = preg_replace('/<p>(\s|&nbsp;)+/i', '<p>', $content);
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
<div id="reading-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 z-[9999] transition-all duration-150 ease-out" style="width: 0%;"></div>
<section class="relative overflow-hidden font-sans bg-[#F4F7FB] pt-12 pb-6 lg:pt-20 lg:pb-8">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
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
                    
                    $titleParts = explode('? ', $title, 2);
                ?>
                <h1 class="animate-fade-up delay-200 mb-6">
                    <?php if (count($titleParts) === 2): ?>
                        <span class="block <?= $titleClass ?> font-bold text-slate-500 mb-2">
                            <?= e($titleParts[0] . '?') ?>
                        </span>
                        <span class="block <?= $titleClass ?> font-bold text-[#022862]">
                            <?= e($titleParts[1]) ?>
                        </span>
                    <?php else: ?>
                        <span class="block <?= $titleClass ?> font-bold text-[#022862]">
                            <?= e($title) ?>
                        </span>
                    <?php endif; ?>
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
            <div class="animate-fade-up delay-300 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e($coverImage) ?>" alt="<?= e($title) ?>" 
                    class="w-full h-auto object-cover aspect-[4/3] hover:scale-105 transition-transform duration-700" onerror="this.src='<?= e($fallbackImage) ?>'">
            </div>
        </div>
    </div>
</section>
<style>
    .article-format {
        color: #475569;
        font-size: 1rem;
        line-height: 1.75;
        max-width: 100% !important;
        overflow-x: hidden !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }
    .article-format, .article-format * {
        font-family: 'Noto Sans Thai', 'Inter', ui-sans-serif, system-ui, sans-serif !important;
        text-indent: 0 !important; /* Kill MS Word indentations */
        max-width: 100% !important;
        box-sizing: border-box !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }
    .article-format p, 
    .article-format span, 
    .article-format li, 
    .article-format div {
        font-size: 1rem !important;
        line-height: 1.75 !important;
    }
    .article-format h2, 
    .article-format h3 {
        color: #0d6efd; 
        font-weight: 700 !important;
        margin-top: 2.5rem !important;
        margin-bottom: 1rem !important;
        scroll-margin-top: 6rem;
        line-height: 1.4 !important;
    }
    .article-format h2 {
        font-size: 1.75rem !important;
    }
    .article-format h3 {
        font-size: 1.35rem !important;
    }
    .article-format p {
        margin-top: 0 !important; /* Override MS Word margins */
        margin-bottom: 1.25rem !important;
    }
    .article-format img {
        border-radius: 0.75rem;
        margin: 2rem auto;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        max-width: 100%;
        height: auto;
    }
    /* CKEditor Image Alignments */
    .article-format figure.image-style-align-left,
    .article-format img.image-style-align-left {
        float: left;
        margin-right: 1.5rem;
        margin-bottom: 1rem;
        margin-top: 0.5rem;
    }
    .article-format figure.image-style-align-right,
    .article-format img.image-style-align-right,
    .article-format figure.image-style-side,
    .article-format img.image-style-side {
        float: right;
        margin-left: 1.5rem;
        margin-bottom: 1rem;
        margin-top: 0.5rem;
    }
    .article-format ul, .article-format ol {
        margin-bottom: 1.5rem;
        padding-left: 0;
        list-style-position: inside;
    }
    .article-format li {
        margin-bottom: 0;
        line-height: 1.8;
    }
    .article-format ul {
        list-style-type: disc !important;
    }
    .article-format ul ul {
        list-style-type: circle !important;
    }
    .article-format ul ul ul {
        list-style-type: square !important;
    }
    .article-format ul li::marker {
        color: #0d6efd;
    }
    .article-format ol {
        list-style-type: decimal;
        font-weight: 700;
        color: #022862;
    }
    .article-format ol p, 
    .article-format ol span {
        font-weight: 400;
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
    
    @media (max-width: 1023px) {
        .mobile-collapsed {
            max-height: 450px !important;
            overflow: hidden !important;
        }
    }
</style>
<section class="py-12 bg-[#F7F9FC]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <article class="lg:col-span-8 bg-white rounded-[2rem] p-6 md:p-10 shadow-sm border border-slate-100 relative">
                <div id="article-content-container" class="relative mobile-collapsed transition-[max-height] duration-500 ease-in-out">
                    <div class="article-format pb-12 lg:pb-0" id="article-format-content">
                        <?= $content ?>
                    </div>
                    <!-- Read More Overlay (Mobile Only) -->
                    <div id="read-more-overlay" onclick="expandArticle()" class="absolute bottom-0 left-0 right-0 h-48 bg-gradient-to-t from-white via-white/95 to-transparent flex items-center justify-center pt-16 lg:hidden transition-opacity duration-300 z-20 cursor-pointer">
                        <button type="button" class="text-white px-8 py-3.5 rounded-full font-bold shadow-xl active:scale-95 transition-all flex items-center gap-2 text-[15px] z-30" style="background-color: #0663F6;">
                            <?= e(getCurrentLang() === 'th' ? 'อ่านเพิ่มเติม' : 'Read more') ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>
                    <!-- Show Less Button (Mobile Only) -->
                    <div id="show-less-container" class="hidden lg:hidden justify-center mt-8 mb-2">
                        <button onclick="collapseArticle()" type="button" class="bg-white border-2 border-[#0663F6] text-[#0663F6] px-8 py-3 rounded-full font-bold shadow-sm active:scale-95 transition-all flex items-center gap-2 text-[15px] cursor-pointer">
                            <?= e(getCurrentLang() === 'th' ? 'ย่อเนื้อหา' : 'Show less') ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </article>
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
function expandArticle() {
    const container = document.getElementById('article-content-container');
    const overlay = document.getElementById('read-more-overlay');
    const showLess = document.getElementById('show-less-container');
    if (container) {
        container.style.maxHeight = '450px';
        container.classList.remove('mobile-collapsed');
        // Trigger reflow
        void container.offsetHeight;
        container.style.maxHeight = container.scrollHeight + 150 + 'px';
        setTimeout(() => {
            container.style.maxHeight = 'none';
        }, 500);
    }
    if (overlay) {
        overlay.style.opacity = '0';
        setTimeout(() => overlay.style.display = 'none', 300);
    }
    if (showLess) {
        showLess.classList.remove('hidden');
        showLess.classList.add('flex');
    }
}

function collapseArticle() {
    const container = document.getElementById('article-content-container');
    const overlay = document.getElementById('read-more-overlay');
    const showLess = document.getElementById('show-less-container');
    
    if (container) {
        container.style.maxHeight = container.scrollHeight + 'px';
        void container.offsetHeight;
        container.classList.add('mobile-collapsed');
        container.style.maxHeight = '';
        
        const yOffset = -80; 
        const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
        window.scrollTo({top: y, behavior: 'smooth'});
    }
    if (overlay) {
        overlay.style.display = 'flex';
        setTimeout(() => {
            overlay.style.opacity = '1';
        }, 10);
    }
    if (showLess) {
        showLess.classList.add('hidden');
        showLess.classList.remove('flex');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // Check mobile content height for read more logic
    if (window.innerWidth < 1024) {
        const container = document.getElementById('article-content-container');
        const overlay = document.getElementById('read-more-overlay');
        const formatDiv = document.getElementById('article-format-content');
        
        // Add a slight delay to ensure fonts/images are rendered before calculating height
        setTimeout(() => {
            if (formatDiv && formatDiv.scrollHeight <= 450) {
                container.classList.remove('mobile-collapsed');
                if (overlay) overlay.style.display = 'none';
            }
        }, 150);
    }

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
