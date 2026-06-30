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

<section class="bg-[#f8fbff] pt-10 pb-6">
    <div class="mx-auto max-w-6xl px-4">
        <nav class="text-[13px] flex items-center gap-2 font-medium text-slate-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>
            </svg>
            <a href="<?= e(route_url('/')) ?>" class="hover:text-[#1a2b6d] transition-colors">หน้าหลัก</a>
            <span>/</span>
            <a href="<?= e(route_url('/article')) ?>" class="hover:text-[#1a2b6d] transition-colors">บทความ</a>
            <span>/</span>
            <span class="text-[#1a2b6d]"><?= e($title) ?></span>
        </nav>
    </div>
</section>

<section class="bg-[#f8fbff] pb-10">
    <div class="mx-auto max-w-6xl px-4 space-y-5">
        <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-semibold text-[#1a2b6d] shadow-sm">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
            <?= e($category) ?> / Knowledge Article
        </span>

        <h1 class="text-3xl font-extrabold leading-snug text-[#0b1b42] lg:text-[2.5rem] lg:leading-tight max-w-3xl">
            <?= e($title) ?>
        </h1>

        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
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
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41L11 23l-9-9 9.59-9.59A2 2 0 0113 4h6a1 1 0 011 1v6a2 2 0 01-.41 1.41z"/></svg>
                <?= e($category) ?>
            </span>
        </div>

        <?php if ($summary !== ''): ?>
            <p class="max-w-3xl text-base leading-relaxed text-slate-600"><?= e($summary) ?></p>
        <?php endif; ?>

        <div class="overflow-hidden rounded-[1.75rem] shadow-[0_25px_60px_rgba(15,23,42,0.15)]">
            <img src="<?= e($coverImage) ?>" alt="<?= e($title) ?>" class="h-[260px] w-full object-cover lg:h-[360px]">
        </div>
    </div>
</section>

<section class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid gap-10 lg:grid-cols-[1fr_320px]">

            <article class="min-w-0 space-y-10">
                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed prose-h2:text-xl prose-h2:font-bold prose-h2:text-[#0b1b42] prose-h3:text-lg prose-h3:font-bold prose-h3:text-[#1a2b6d] prose-a:text-[#1a2b6d]">
                    <?= $content ?>
                </div>

                <div class="rounded-r-3xl border-l-4 border-[#1a2b6d] bg-[#f4f9ff] p-6">
                    <p class="text-sm italic leading-relaxed text-slate-800">
                        " เทคโนโลยีทั้งสองส่วนคือหัวใจสำคัญที่ช่วยให้ ERP และ CRM ทำงานร่วมกันอย่างมีประสิทธิภาพ เพื่อพาองค์กรก้าวสู่การเป็น Data-Driven Organization อย่างแท้จริง "
                    </p>
                </div>
            </article>

            <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <?php if (!empty($tableOfContents)): ?>
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-base font-bold text-[#0b1b42]">สารบัญบทความ</h3>
                        <ul class="space-y-3 text-sm">
                            <?php foreach ($tableOfContents as $i => $item): ?>
                                <li class="<?= $item['level'] === 3 ? 'pl-4' : '' ?>">
                                    <a href="#<?= e($item['id']) ?>"
                                       class="flex items-start gap-2 text-slate-500 transition hover:text-[#1a2b6d] <?= $i === 0 ? 'font-semibold text-[#1a2b6d]' : '' ?>">
                                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full <?= $i === 0 ? 'bg-[#1a2b6d]' : 'bg-slate-300' ?>"></span>
                                        <span><?= e($item['text']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($popularCategories)): ?>
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-base font-bold text-[#0b1b42]">หมวดหมู่ยอดนิยม</h3>
                        <ul class="space-y-3 text-sm">
                            <?php foreach ($popularCategories as $categoryItem): ?>
                                <?php $catName = (string) ($categoryItem['name'] ?? ''); ?>
                                <?php $catSlug = (string) ($categoryItem['slug'] ?? ''); ?>
                                <?php $count = (int) ($categoryItem['article_count'] ?? 0); ?>
                                <?php if ($catName === '' || $count === 0) continue; ?>
                                <li>
                                    <a href="<?= e(route_url('/article', ['category' => $catSlug])) ?>"
                                       class="flex items-center justify-between gap-3 text-slate-600 transition hover:text-[#1a2b6d]">
                                        <span><?= e($catName) ?></span>
                                        <span class="text-xs font-semibold text-slate-400"><?= e($count) ?> บทความ</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($relatedArticles)): ?>
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-base font-bold text-[#0b1b42]">บทความที่เกี่ยวข้อง</h3>
                        <div class="space-y-4">
                            <?php foreach (array_slice($relatedArticles, 0, 3) as $item):
                                $rTitle = normalize_text($item['title'] ?? 'Untitled Article');
                                $rImage = resolve_article_image_url(
                                    $item['image_path'] ?? $item['image'] ?? '',
                                    $fallbackImage
                                );
                                $rTs = !empty($item['created_at']) ? strtotime($item['created_at']) : false;
                                $rDate = $rTs ? sprintf('%d %s %d', date('j', $rTs), $months[(int) date('n', $rTs)] ?? '', date('Y', $rTs) + 543) : '';
                                ?>
                                <a href="<?= e(route_url('/article', ['id' => (int) ($item['id'] ?? 0)])) ?>"
                                   class="group flex items-center gap-3">
                                    <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl">
                                        <img src="<?= e($rImage) ?>" alt="<?= e($rTitle) ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    </div>
                                    <div class="min-w-0 space-y-0.5">
                                        <h4 class="line-clamp-2 text-sm font-bold leading-snug text-[#0b1b42] group-hover:text-[#1a2b6d]"><?= e($rTitle) ?></h4>
                                        <?php if ($rDate !== ''): ?>
                                            <p class="text-xs text-slate-400"><?= e($rDate) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= e(route_url('/article')) ?>" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-[#1a2b6d]">
                            ดูบทความทั้งหมด
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-base font-bold text-[#0b1b42]">แชร์บทความนี้</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= e($shareUrl) ?>" target="_blank" rel="noopener"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1877F2] text-white transition hover:scale-110">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= e($shareUrl) ?>" target="_blank" rel="noopener"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0A66C2] text-white transition hover:scale-110">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="https://social-plugins.line.me/lineit/share?url=<?= e($shareUrl) ?>" target="_blank" rel="noopener"
                           class="flex h-10 w-10 items-center justify-center rounded-full bg-[#06C755] text-white transition hover:scale-110">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 5.58 2 10.02c0 2.5 1.41 4.7 3.63 6.04-.24.87-.87 2.45-.9 2.54-.05.15.04.14.09.11.05-.03 1.94-1.32 2.7-1.84.75.21 1.54.32 2.38.32 5.52 0 10-3.58 10-8.02S17.52 2 12 2z"/>
                            </svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>{this.title='คัดลอกแล้ว!'})"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:scale-110"
                                title="คัดลอกลิงก์">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden rounded-3xl bg-[#1a2b6d] p-6 text-white">
                    <h3 class="text-base font-bold">ปรึกษาผู้เชี่ยวชาญ ERP</h3>
                    <p class="mt-2 text-sm leading-relaxed text-white/80">
                        ให้ทีมผู้เชี่ยวชาญของ Webpark ช่วยออกแบบโซลูชันที่เหมาะกับธุรกิจคุณ
                    </p>
                    <a href="<?= e(route_url('/contact')) ?>"
                       class="mt-5 inline-flex items-center gap-1 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-[#1a2b6d] transition hover:bg-blue-50">
                        ขอคำปรึกษาฟรี
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="bg-white pb-16">
    <div class="mx-auto max-w-6xl px-4">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#0b1b6d] to-[#1a3fa0] px-8 py-12 text-white lg:px-14">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.3em]">
                Get In Touch
            </span>
            <h2 class="mt-4 max-w-xl text-2xl font-extrabold leading-snug lg:text-3xl">
                พร้อมยกระดับธุรกิจของคุณแล้วหรือยัง?
            </h2>
            <p class="mt-3 max-w-lg text-sm leading-relaxed text-white/80">
                ให้ Webpark ช่วยวางระบบ ERP ที่เหมาะสมกับองค์กรของคุณ
                เพื่อเพิ่มประสิทธิภาพ ลดต้นทุน และขับเคลื่อนการเติบโตอย่างยั่งยืน
            </p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="<?= e(route_url('/contact')) ?>"
                   class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#1a2b6d] transition hover:bg-blue-50">
                    ขอคำปรึกษาฟรี
                </a>
                <a href="<?= e(route_url('/service')) ?>"
                   class="inline-flex items-center gap-1 rounded-full border border-white/40 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    ดูบริการของเรา
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>