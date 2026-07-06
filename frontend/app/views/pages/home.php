<?php

declare(strict_types=1);

/**
 * Home page view — hero, portfolio slider, reviews, and knowledge articles.
 */

$services = $services ?? [];
$activeTab = $activeTab ?? 'news';
$displayArticles = $latestArticles ?? $articles ?? $blogs ?? [];
$displayPortfolios = $displayPortfolios ?? [];
$reviews = $reviews ?? [];

$projectRoot = dirname(__DIR__, 3);

$resolveServiceImage = static function (string $imagePath) use ($projectRoot): string {
    $imagePath = trim($imagePath);
    if ($imagePath === '') return '';
    if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) return $imagePath;
    if (str_starts_with($imagePath, '/assets/') || str_starts_with($imagePath, 'assets/') || str_starts_with($imagePath, 'public/assets/')) {
        $normalizedPath = ltrim($imagePath, '/');
        if (str_starts_with($normalizedPath, 'assets/')) $filePath = $projectRoot . '/public/' . $normalizedPath;
        elseif (str_starts_with($normalizedPath, 'public/assets/')) $filePath = $projectRoot . '/' . $normalizedPath;
        else $filePath = $projectRoot . '/public/assets/' . $normalizedPath;
        return is_file($filePath) ? asset_url($imagePath) : '';
    }
    if (str_starts_with($imagePath, '/images/') || str_starts_with($imagePath, 'images/')) {
        $normalizedPath = ltrim($imagePath, '/');
        $filePath = $projectRoot . '/public/assets/' . $normalizedPath;
        return is_file($filePath) ? asset_url($normalizedPath) : '';
    }
    if (str_starts_with($imagePath, '/')) return app_url(ltrim($imagePath, '/'));
    return asset_url('images/' . ltrim($imagePath, '/'));
};

$resolveReviewImage = static function (string $imagePath) use ($resolveServiceImage): string {
    $imagePath = trim($imagePath);
    if (str_starts_with($imagePath, '//')) return $imagePath;
    $resolvedImage = $resolveServiceImage($imagePath);
    return $resolvedImage !== '' ? $resolvedImage : asset_url('images/women-office.jpg');
};
$partnerLogos = [
    'logo-scg.png', 
    'logo-ptt.png', 
    'logo-ais.png', 
    'logo-true.png', 
    'logo-bbl.png', 
    'logo-cpall.png'
];

$mockArticles = [
    ['id' => 1, 'title' => 'ระบบ ERP คืออะไร? สรุปครบ จบในที่เดียว!', 'summary' => 'ระบบที่รวบรวมองค์กรและกระบวนการทางธุรกิจเข้าด้วยกัน เพื่อการบริหารจัดการและประสานงานที่มีประสิทธิภาพสูงสุดในองค์กร...', 'category' => 'ERP', 'image_path' => 'images/erp.png'],
    ['id' => 2, 'title' => 'Gemini 3 กับยุคใหม่ของการทำงาน เมื่อ AI ไม่ได้แค่คิด แต่ลงมือทำแทนคน', 'summary' => 'พัฒนาการของระบบ AI อัจฉริยะที่เข้ามาช่วยเพิ่มขีดความสามารถและลดขั้นตอนการทำงานให้รวดเร็ว แม่นยำ และตอบโจทย์ธุรกิจ...', 'category' => 'AI', 'image_path' => 'images/bg-cta.jpg'],
    ['id' => 3, 'title' => 'Cloud 2026 เก่งกว่าที่คุณคิด: องค์กรไหนรู้ก่อน ได้เปรียบก่อน', 'summary' => 'อัปเดตเทคโนโลยีคลาวด์อัจฉริยะในปี 2026 ที่จะช่วยพลิกโฉมการจัดเก็บฐานข้อมูลและการประมวลผลให้มีความปลอดภัยและยืดหยุ่น...', 'category' => 'CLOUD', 'image_path' => 'images/bg-hand.jpg']
];
?>

<section class="relative font-sans bg-[#f7faff] overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="bg" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <style>
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideLeft {
            0% { opacity: 0; transform: translateX(50px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        .animate-entrance-up { opacity: 0; animation: fadeSlideUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-entrance-left { opacity: 0; animation: fadeSlideLeft 1s cubic-bezier(0.16,1,0.3,1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-scroll { animation: scroll 20s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }
    </style>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="max-w-3xl">
                <div class="animate-entrance-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">Digital Solutions for Modern Business</span>
                </div>

                <h1 class="animate-entrance-up delay-200 text-5xl md:text-6xl lg:text-8xl font-bold leading-[1.1] mb-2 tracking-tighter">
                    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block">WEBPARK</span><br>
                    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block">COMPANY</span>
                </h1>

                <p class="animate-entrance-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    ผู้ให้บริการพัฒนา Digital Platform<br class="hidden sm:block">
                    และระบบ AI ที่ช่วยให้ธุรกิจไทยก้าวไปข้างหน้า<br class="hidden sm:block">
                    ด้วยเทคโนโลยีที่ใช้งานได้จริง
                </p>

                <div class="animate-entrance-up delay-400 flex flex-wrap items-center gap-4">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ดูบริการของเรา
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#about" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors">ดูวิดีโอแนะนำ</span>
                    </a>
                </div>
            </div>
            <div class="hidden lg:block lg:col-start-2"></div>
        </div>

        <div class="animate-entrance-left delay-500 absolute bottom-0 right-0 md:right-4 lg:right-8 z-10 pointer-events-none max-w-full transform -translate-y-2">
            <img src="<?= e(asset_url('images/women.png')) ?>" alt="WEBPARK Presenter" class="w-auto object-contain h-[400px] md:h-[400px] lg:h-[600px] max-w-full">
        </div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16">
        <div class="w-full rounded-[1rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">

            <div class="flex-1 lg:max-w-[180px] flex items-center justify-center p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white">
                <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" class="w-32 lg:w-full h-auto object-contain">
            </div>

            <div class="group flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50 cursor-pointer">
                <div>
                    <span class="text-primary font-bold text-sm tracking-wide block mb-3">เกี่ยวกับเรา</span>
                    <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        เราคือ พาร์ทเนอร์<br>ด้านเทคโนโลยี
                    </h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        มุ่งมั่นพัฒนาโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจยุคใหม่ ด้วยทีมงานมืออาชีพพร้อมแนวคิดและเทคโนโลยีในการยกระดับการทำงานของคุณ
                    </p>
                </div>
                <a href="<?= e(route_url('/about')) ?>" class="inline-flex items-center text-primary text-sm font-semibold transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            <div class="flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php
                $serviceCards = [
                    ['icon' => 'icon-3.png', 'title' => 'ERP / ERM',        'desc' => 'ระบบบริหารจัดการองค์กรและควบคุมระบบ เพื่อเพิ่มทุกกระบวนการทำงานอย่างมีประสิทธิภาพ', 'href' => route_url('/erp')],
                    ['icon' => 'icon-2.png', 'title' => 'Digital Platform', 'desc' => 'พัฒนาแพลตฟอร์มดิจิทัลทั้งออนไลน์และออฟไลน์ รองรับการเติบโตและการขยายตัว',              'href' => '#'],
                    ['icon' => 'icon-4.png', 'title' => 'Online Marketing', 'desc' => 'วางกลยุทธ์และทำการตลาดออนไลน์ เพื่อการเข้าถึงกลุ่มเป้าหมาย และผลลัพธ์ที่วัดผลได้',   'href' => '#'],
                    ['icon' => 'icon-1.png', 'title' => 'Creative / Design','desc' => 'ออกแบบและสร้างสรรค์ภาพลักษณ์ของแบรนด์ให้โดดเด่น สร้างการจดจำและตอบโจทย์แคมเปญ',    'href' => '#'],
                ];
                $lastIdx = count($serviceCards) - 1;
                foreach ($serviceCards as $i => $card):
                    $borderClass = $i < $lastIdx ? 'border-b sm:border-b-0 sm:border-r' : '';
                ?>
                    <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                    <div>
                        <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                            <img src="<?= e(asset_url('images/' . $card['icon'])) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                            <?= e($card['title']) ?>
                        </h2>
                        <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                            <?= e($card['desc']) ?>
                        </p>
                    </div>
                    <a href="<?= e($card['href']) ?>" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                        อ่านเพิ่มเติม
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-primary m-0">
                บริการของเรา
            </h2>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <span class="text-base md:text-lg lg:text-xl font-bold leading-tight text-dark">ตัวอย่างผลงานของเรา</span>
                <a href="<?= e(route_url('/services')) ?>" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                    ดูบริการทั้งหมด
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <p class="text-sm md:text-base leading-relaxed text-slate-500 max-w-xl">
                รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล<br>ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน
            </p>
        </div>

        <?php
        $categoryColors = [
            'ERP / ERM'        => '#0066ff',
            'ERP'              => '#0066ff',
            'Digital Platform' => '#00b894',
            'Online Marketing' => '#e17055',
            'Creative / Design'=> '#6c5ce7',
            'Website Portfolio'=> '#0066ff',
        ];

        $totalPortfolios = count($displayPortfolios);
        $visibleCount    = 4;
        ?>

        <div class="relative">
            <button id="portfolio-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div id="portfolio-slider" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($displayPortfolios as $index => $project): ?>
                    <?php
                    $project      = (array)$project;
                    $isVisible    = $index < $visibleCount ? '' : 'hidden';
                    $projectId    = (int)($project['id'] ?? 0);
                    $projectTitle = (string)($project['title'] ?? 'Portfolio');
                    $projectDesc  = mb_strimwidth(strip_tags((string)($project['description'] ?? $project['summary'] ?? '')), 0, 80, '...');
                    $projectCat   = (string)($project['category'] ?? 'Portfolio');
                    $catColor     = $categoryColors[$projectCat] ?? '#0066ff';
                    $projectImage = asset_url($project['image_path'] ?? 'images/erp.png');
                    ?>
                    <article class="portfolio-card group rounded-[1.2rem] overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col <?= $isVisible ?>" data-index="<?= $index ?>">
                        <a href="<?= e($projectId > 0 ? route_url('/portfolio', ['id' => $projectId]) : route_url('/portfolio')) ?>" class="flex flex-col h-full">
                            <div class="h-[200px] w-full overflow-hidden bg-slate-100 shrink-0">
                                <img src="<?= e($projectImage) ?>" alt="<?= e($projectTitle) ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                        <span class="text-[11px] font-bold text-slate-500"><?= e(mb_substr($projectTitle, 0, 2)) ?></span>
                                    </div>
                                    <h3 class="text-base font-bold text-[#0b1b42] leading-snug line-clamp-1"><?= e($projectTitle) ?></h3>
                                </div>
                                <p class="text-[13px] text-slate-500 leading-relaxed line-clamp-2 mb-4 flex-1"><?= e($projectDesc) ?></p>
                                <div class="mt-auto">
                                    <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full border" style="color:<?= e($catColor) ?>;border-color:<?= e($catColor) ?>;">
                                        <?= e($projectCat) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <button id="portfolio-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <?php if ($totalPortfolios > $visibleCount): ?>
        <div class="flex justify-center items-center gap-2 mt-8">
            <?php for ($i = 0; $i < (int)ceil($totalPortfolios / $visibleCount); $i++): ?>
                <span class="portfolio-dot h-2 rounded-full cursor-pointer transition-all <?= $i === 0 ? 'bg-primary w-6' : 'bg-slate-300 w-2' ?>" data-index="<?= $i ?>"></span>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prevBtn = document.getElementById('portfolio-prev');
    const nextBtn = document.getElementById('portfolio-next');
    const dots    = document.querySelectorAll('.portfolio-dot');
    const cards   = document.querySelectorAll('.portfolio-card');
    const visible = 4;
    let cur = 0;
    const max = Math.max(0, Math.ceil(cards.length / visible) - 1);

    function update() {
        cards.forEach((c, i) => c.classList.toggle('hidden', !(i >= cur * visible && i < (cur + 1) * visible)));
        dots.forEach((d, i) => { d.classList.toggle('bg-primary', i === cur); d.classList.toggle('w-6', i === cur); d.classList.toggle('bg-slate-300', i !== cur); d.classList.toggle('w-2', i !== cur); });
        if (prevBtn) prevBtn.disabled = cur === 0;
        if (nextBtn) nextBtn.disabled = cur >= max;
    }

    if (prevBtn) prevBtn.addEventListener('click', () => { if (cur > 0) { cur--; update(); } });
    if (nextBtn) nextBtn.addEventListener('click', () => { if (cur < max) { cur++; update(); } });
    dots.forEach((d, i) => d.addEventListener('click', () => { cur = i; update(); }));
    update();
});
</script>

<?php
$totalReviews = count($reviews);
if ($totalReviews > 0):
?>
<section class="relative py-20 lg:py-20 overflow-hidden">
    <div class="absolute inset-0 -z-10 pointer-events-none">
        <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" alt="bg" class="w-full h-full object-cover object-center opacity-20 mix-blend-screen">
        <div class="absolute inset-0 bg-white/50"></div>
    </div>

    <div class="relative mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <div class="mb-12 text-center max-w-4xl mx-auto">
            <h2 class="text-primary font-bold text-4xl md:text-3xl tracking-normal uppercase mb-3 block">
                REVIEW
            </h2>
            <span class="text-base md:text-lg lg:text-xl font-bold leading-tight text-dark">
                กว่า <span class="text-primary">120</span> องค์กร ที่เลือก <span class="text-primary">WEBPARK</span> เป็นพาร์ทเนอร์ด้านดิจิทัล
            </span>

        </div>

        <div class="flex items-center justify-between gap-4">
            <button id="review-prev" class="w-12 h-12 rounded-full border-2 border-slate-400 flex items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="flex-1">
                <div id="review-slider" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($reviews as $index => $review): ?>
                        <?php
                        $reviewerName  = (string)($review['reviewer_name'] ?? '');
                        $reviewerMeta  = implode(', ', array_values(array_filter([(string)($review['reviewer_position'] ?? ''), (string)($review['reviewer_company'] ?? '')], static fn($v) => trim($v) !== '')));
                        $reviewerImage = $resolveReviewImage((string)($review['reviewer_image_url'] ?? ''));
                        $isVisible     = $index < 4 ? '' : 'hidden';
                        $rating        = max(0, min(5, isset($review['rating']) ? (int)$review['rating'] : 5));
                        ?>
                        <article class="review-card group shrink-0 bg-white rounded-[1.5rem] p-5 lg:p-6 shadow-sm border border-[#f3f4f6] flex flex-col justify-between hover:bg-primary hover:-translate-y-1 transition-all duration-300 h-[280px] <?= $isVisible ?>" data-index="<?= $index ?>">
                            <div class="flex items-center justify-center w-full mb-3 mt-1 shrink-0">
                                <div class="flex items-center gap-1.5">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-colors <?= $i <= $rating ? 'text-yellow-400 group-hover:text-yellow-300' : 'text-slate-200 group-hover:text-white/30' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="relative">
                                <span class="absolute -top-3 -left-2 text-4xl font-serif text-slate-200 group-hover:text-white/20 transition-colors select-none" aria-hidden="true">“</span>
                                <p class="text-sm leading-relaxed text-slate-600 group-hover:text-white transition-colors relative z-10 pl-2 line-clamp-4 overflow-hidden">
                                    <?= e($review['content'] ?? '') ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-3 mt-4 shrink-0 border-t border-slate-50 group-hover:border-white/10 pt-4">
                                <img class="h-10 w-10 lg:h-11 lg:w-11 rounded-full object-cover bg-slate-100 shrink-0" src="<?= e($reviewerImage) ?>" alt="<?= e($reviewerName ?: 'Customer') ?>">
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-dark group-hover:text-white transition-colors truncate"><?= e($reviewerName) ?></h4>
                                    <?php if ($reviewerMeta !== ''): ?>
                                        <p class="text-xs font-medium text-slate-400 group-hover:text-white/80 transition-colors truncate mt-0.5"><?= e($reviewerMeta) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <button id="review-next" class="w-12 h-12 rounded-full border-2 border-slate-400 flex items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" <?= $totalReviews <= 4 ? 'disabled' : '' ?>>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <?php if ($totalReviews > 4): ?>
        <div class="flex justify-center items-center gap-2 mt-6 flex-wrap">
            <?php for ($i = 0; $i < (int)ceil($totalReviews / 4); $i++): ?>
                <span class="review-dot h-2 rounded-full cursor-pointer hover:bg-primary transition-all <?= $i === 0 ? 'bg-primary w-6' : 'bg-slate-300 w-2' ?>" data-index="<?= $i ?>"></span>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <div class="mx-auto w-full max-w-7xl py-8 mt-10">
            <h2 class="text-center text-primary font-bold text-2xl md:text-3xl tracking-normal uppercase mb-3 block">
                พันธมิตร
            </h2>
            <span class="block mx-auto text-base md:text-lg lg:text-xl font-bold leading-tight text-dark text-center">
                องค์กรชั้นนำที่ไว้วางใจ <span class="text-primary">WEBPARK</span>
            </span>

            <div class="overflow-hidden relative mt-10">
                <div class="flex justify-center flex-wrap gap-8 md:gap-16 opacity-80">
                    <?php foreach ($partnerLogos as $logo): ?>
                        <div class="flex shrink-0 items-center justify-center w-[100px] h-[50px]">
                            <img src="<?= e(asset_url('images/' . $logo)) ?>" alt="Partner" class="max-h-full max-w-full object-contain grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="text-center mt-6 text-xs text-slate-400 tracking-wide font-medium">
                ทั้งหมดมาจากธุรกิจ การเงิน อสังหาริมทรัพย์ โรงงาน วิศวกรรม สื่อ และอีกมากมาย
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prevBtn = document.getElementById('review-prev');
    const nextBtn = document.getElementById('review-next');
    const dots    = document.querySelectorAll('.review-dot');
    const cards   = document.querySelectorAll('.review-card');
    const visible = 4;
    let cur = 0;
    const max = Math.max(0, Math.ceil(cards.length / visible) - 1);

    function update() {
        cards.forEach((c, i) => c.classList.toggle('hidden', !(i >= cur * visible && i < (cur + 1) * visible)));
        dots.forEach((d, i) => { d.classList.toggle('bg-primary', i === cur); d.classList.toggle('w-6', i === cur); d.classList.toggle('bg-slate-300', i !== cur); d.classList.toggle('w-2', i !== cur); });
        if (prevBtn) prevBtn.disabled = cur === 0;
        if (nextBtn) nextBtn.disabled = cur >= max;
    }

    if (prevBtn) prevBtn.addEventListener('click', () => { if (cur > 0) { cur--; update(); } });
    if (nextBtn) nextBtn.addEventListener('click', () => { if (cur < max) { cur++; update(); } });
    dots.forEach((d, i) => d.addEventListener('click', () => { cur = i; update(); }));
    update();
});
</script>
<?php endif; ?>

<section class="bg-slate-50 py-20 lg:py-20 border-t border-slate-100">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between border-b border-slate-200 pb-5 mb-10">
            <div>
                <h2 class="text-primary font-bold text-2xl md:text-3xl tracking-normal uppercase mb-1 block">
                KNOWLEDGE
                </h2>
                <span class="text-base md:text-lg lg:text-xl font-extrabold leading-none tracking-tight text-dark m-0">
                บทความและความรู้
                </span>
            </div>
            <a href="<?= e(route_url('/article')) ?>" class="group flex items-center gap-1.5 text-sm font-bold text-primary hover:text-blue-700 transition-colors">
                ดูทั้งหมด
                <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <?php $displayArticles = $mockArticles; // ใช้ข้อมูลจำลองบทความชั่วคราว ?>
        
        <?php if (count($displayArticles) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($displayArticles as $art): ?>
                <?php
                $artId       = (int)($art['id'] ?? 0);
                $artTitle    = (string)($art['title'] ?? 'Article');
                $artSummary  = mb_strimwidth(strip_tags((string)($art['summary'] ?? '')), 0, 110, '...');
                $artCat      = (string)($art['category'] ?? 'Knowledge');
                $artImage    = asset_url($art['image_path'] ?? 'images/erp.png');
                ?>
                <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md group">
                    <a href="<?= e($artId > 0 ? route_url('/article', ['id' => $artId]) : route_url('/article')) ?>" class="flex flex-col h-full">
                        <div class="relative aspect-[16/9] w-full bg-slate-900 overflow-hidden shrink-0">
                            <img src="<?= e($artImage) ?>" alt="<?= e($artTitle) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <span class="absolute bottom-3 left-3 rounded-md bg-primary/95 backdrop-blur-sm px-2.5 py-1 text-[10px] font-bold tracking-wider text-white uppercase shadow-sm">
                                <?= e($artCat) ?>
                            </span>
                        </div>
                        <div class="flex flex-col flex-1 p-6">
                            <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-primary transition-colors mb-3">
                                <?= e($artTitle) ?>
                            </h3>
                            <p class="text-[13px] text-slate-500 leading-relaxed line-clamp-3 mb-5 flex-1">
                                <?= e($artSummary) ?>
                            </p>
                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-1 text-xs font-bold text-primary">
                                อ่านเพิ่มเติม
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>