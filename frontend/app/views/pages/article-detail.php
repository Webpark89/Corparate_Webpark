<?php

declare(strict_types=1);

$article = is_array($article) ? $article : [];
$siteName = config('app.name', 'WEBPARK');
$temporaryImage = asset_url('images/story.png');
$fallbackImage = asset_url('images/bg-3.png');

// Check if the image path from database actually exists on server
$dbImagePath = $article['image_path'] ?? '';
$coverImage = '';
if ($dbImagePath !== '') {
    $fullImageUrl = asset_url($dbImagePath);
    // Extract the file path from URL for file_exists check
    $parsed = parse_url($fullImageUrl, PHP_URL_PATH);
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $parsed;
    // Use the image if file exists, otherwise use fallback
    $coverImage = file_exists($filePath) ? $fullImageUrl : $fallbackImage;
} else {
    $coverImage = $fallbackImage;
}

$months = [
    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
    7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
];

$title = normalize_text($article['title'] ?? '');
$author = normalize_text($article['author'] ?? 'Webpark Team');
$content = (string) ($article['content'] ?? '');
$summary = normalize_text($article['summary'] ?? '');
$category = normalize_text($article['category'] ?? 'ERP System');
$relatedArticles = $relatedArticles ?? [];

// คำนวณเวลาอ่านบทความโดยประมาณ (Reading Time)
$wordCount = mb_strlen(strip_tags($content), 'UTF-8');
$readingTime = max(1, (int) ceil($wordCount / 400));

$date = $article['created_at'] ?? '';
$ts = !empty($date) ? strtotime($date) : false;
$formattedDate = $ts ? sprintf('%d %s %d', date('j', $ts), $months[(int) date('n', $ts)] ?? '', date('Y', $ts) + 543) : '';

?>

<section class="bg-white py-10 md:py-16 font-sans text-slate-800">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8  pb-24 lg:pb-32 relative z-10">
        
        <header class="mb-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-7">
                    <nav class="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-wider mb-4">                                  
                        <span class="text-slate-400">Knowledge Article</span>
                        <span class="text-slate-300">/</span>
                        <span><?= e($category) ?></span>
                    </nav>
                    <h1 class="text-3xl sm:text-4xl lg:text-[2.5rem] font-bold text-dark leading-tight tracking-tight mb-6">
                        <?= e($title) ?>
                    </h1>
                </div>
                <div class="lg:col-span-5 relative overflow-hidden rounded-3xl bg-slate-50 aspect-[16/10] shadow-sm">
                    <img src="<?= e($coverImage) ?>" alt="<?= e($title) ?>" class="h-full w-full object-cover">
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 py-4 border-y border-slate-100 text-slate-500 text-[13px] font-medium">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <?= e($formattedDate) ?>
                </span>
                <span class="text-slate-200">|</span>
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    เวลาอ่านประมาณ <?= $readingTime ?> นาที
                </span>
                <span class="text-slate-200">|</span>
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <?= e($author) ?>
                </span>
                <span class="text-slate-200">|</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 text-primary text-xs font-bold uppercase">
                    <?= e($category) ?>
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">
            
            <main class="lg:col-span-8">
                
                <?php if ($summary !== ''): ?>
                    <p class="text-slate-600 font-medium text-base md:text-lg leading-relaxed mb-8">
                        <?= e($summary) ?>
                    </p>
                <?php endif; ?>

                <article class="prose prose-slate max-w-none text-slate-600 leading-relaxed prose-headings:text-dark prose-headings:font-bold prose-strong:text-slate-900 prose-a:text-primary prose-a:no-underline hover:prose-a:underline">
                    <?= $content ?>

                    <div class="my-8 bg-[#f4f9ff] border-l-4 border-primary rounded-r-2xl p-6 shadow-sm">
                        <p class="text-[14px] md:text-base font-medium text-dark leading-relaxed italic mb-0">
                            " เทคโนโลยีที่ดีที่สุดคือเทคโนโลยีที่ตอบโจทย์และสามารถผสานเข้ากับกระบวนการทำงานเดิมได้อย่างไร้รอยต่อ ช่วยลดภาระของบุคลากรและขับเคลื่อนองค์กรสู่ Data-Driven Organization อย่างแท้จริง "
                        </p>
                    </div>
                </article>

            </main>

            <aside class="lg:col-span-4 space-y-10 sticky top-6">
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] rounded-3xl p-6 lg:p-8">
                    <h3 class="text-base font-bold text-dark tracking-wide mb-5 pb-3 border-b border-slate-100 uppercase">
                        สารบัญบทความ
                    </h3>
                    <ul class="space-y-4 text-[13px] font-medium text-slate-600">
                        <li class="flex items-center gap-3 hover:text-primary cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span> ทำความรู้จักระบบ ERP</li>
                        <li class="flex items-center gap-3 hover:text-primary cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span> สัญญาณเตือนที่บอกว่าควรใช้ ERP</li>
                        <li class="flex items-center gap-3 hover:text-primary cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span> โมดูลมาตรฐานภายในระบบ</li>
                        <li class="flex items-center gap-3 hover:text-primary cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span> ความแตกต่างระหว่าง ERP และ CRM</li>
                        <li class="flex items-center gap-3 hover:text-primary cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span> สรุปคำแนะนำส่งท้าย</li>
                    </ul>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] rounded-3xl p-6 lg:p-8">
                    <h3 class="text-base font-bold text-dark tracking-wide mb-5 pb-3 border-b border-slate-100 uppercase">
                        บทความที่เกี่ยวข้อง
                    </h3>
                    <div class="space-y-4">
                        <?php foreach (array_slice($relatedArticles, 0, 3) as $item): ?>
                            <?php
                            $rTitle = $item['title'] ?? 'Untitled Article';
                            $rImagePath = $item['image_path'] ?? '';
                            // Check if image actually exists
                            if ($rImagePath !== '') {
                                $rFullUrl = asset_url($rImagePath);
                                $rParsed = parse_url($rFullUrl, PHP_URL_PATH);
                                $rFilePath = $_SERVER['DOCUMENT_ROOT'] . $rParsed;
                                $rImage = file_exists($rFilePath) ? $rFullUrl : $temporaryImage;
                            } else {
                                $rImage = $temporaryImage;
                            }
                            $rDate = !empty($item['created_at']) ? date('j M Y', strtotime($item['created_at'])) : '';
                            ?>
                            <a href="<?= e(route_url('/article', ['id' => (int)($item['id'] ?? 0)])) ?>" class="flex gap-4 items-center group">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden shrink-0 relative">
                                    <img src="<?= e($rImage) ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-[13px] font-bold text-dark line-clamp-2 leading-tight group-hover:text-primary transition-colors mb-1">
                                        <?= e($rTitle) ?>
                                    </h4>
                                    <span class="text-[11px] text-slate-400 font-medium"><?= e($rDate) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= e(route_url('/article')) ?>" class="mt-6 flex justify-center items-center gap-1 text-xs font-bold text-primary hover:underline">
                        ดูบทความทั้งหมด <span class="text-sm">→</span>
                    </a>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] rounded-3xl p-6 text-center">
                    <span class="text-xs font-bold text-dark tracking-wider block mb-4 uppercase">แชร์บทความนี้</span>
                    <div class="flex justify-center items-center gap-3">
                        <button class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 transition-transform cursor-pointer"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></button>
                        <button class="w-9 h-9 rounded-full bg-[#06C755] text-white flex items-center justify-center hover:scale-110 transition-transform cursor-pointer"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 5.58 2 10.02c0 2.5 1.41 4.7 3.63 6.04-.24.87-.87 2.45-.9 2.54-.05.15.04.14.09.11.05-.03 1.94-1.32 2.7-1.84.75.21 1.54.32 2.38.32 5.52 0 10-3.58 10-8.02S17.52 2 12 2z"/></svg></button>
                        <button onclick="navigator.clipboard.writeText(window.location.href)" class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:scale-110 transition-transform cursor-pointer" title="คัดลอกลิงก์"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg></button>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-dark to-[#0b162c] rounded-3xl p-6 lg:p-8 text-white relative overflow-hidden shadow-xl group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400 block mb-2">ปรึกษาผู้เชี่ยวชาญ ERP</span>
                    <h4 class="text-lg font-bold leading-snug mb-3">ให้ทีมผู้เชี่ยวชาญของ WEBPARK ช่วยออกเเบบระบบที่ตอบโจทย์คุณ</h4>
                    <p class="text-slate-300 text-xs leading-relaxed font-medium mb-6">รับคำปรึกษาประเมินหน้างานเบื้องต้น วางโครงสร้างสถาปัตยกรรมดิจิทัลฟรีก่อนเริ่มต้นพัฒนา</p>
                    <a href="<?= e(route_url('/contact')) ?>" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-primary text-white text-xs font-bold rounded-xl hover:bg-blue-600 transition-all shadow-md">
                        ขอคำปรึกษาฟรี <span>→</span>
                    </a>
                </div>

            </aside>
            
        </div>
        
    </div>
</section>
