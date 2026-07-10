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
    ['id' => 1, 'title' => getCurrentLang() === 'th' ? 'ระบบ ERP คืออะไร? สรุปครบ จบในที่เดียว!' : 'What is ERP? A Complete Summary!', 'summary' => getCurrentLang() === 'th' ? 'ระบบที่รวบรวมองค์กรและกระบวนการทางธุรกิจเข้าด้วยกัน เพื่อการบริหารจัดการและประสานงานที่มีประสิทธิภาพสูงสุดในองค์กร...' : 'A system that integrates organization and business processes for the highest efficiency in management and coordination...', 'category' => 'ERP', 'image_path' => 'images/erp.png'],
    ['id' => 2, 'title' => getCurrentLang() === 'th' ? 'Gemini 3 กับยุคใหม่ของการทำงาน เมื่อ AI ไม่ได้แค่คิด แต่ลงมือทำแทนคน' : 'Gemini 3 and the New Era of Work: When AI Doesn\'t Just Think, But Acts', 'summary' => getCurrentLang() === 'th' ? 'พัฒนาการของระบบ AI อัจฉริยะที่เข้ามาช่วยเพิ่มขีดความสามารถและลดขั้นตอนการทำงานให้รวดเร็ว แม่นยำ และตอบโจทย์ธุรกิจ...' : 'The evolution of intelligent AI systems that help increase capabilities and reduce work steps to be fast, accurate, and meet business needs...', 'category' => 'AI', 'image_path' => 'images/bg-cta.jpg'],
    ['id' => 3, 'title' => getCurrentLang() === 'th' ? 'Cloud 2026 เก่งกว่าที่คุณคิด: องค์กรไหนรู้ก่อน ได้เปรียบก่อน' : 'Cloud 2026 is Smarter Than You Think: First to Know, First to Gain', 'summary' => getCurrentLang() === 'th' ? 'อัปเดตเทคโนโลยีคลาวด์อัจฉริยะในปี 2026 ที่จะช่วยพลิกโฉมการจัดเก็บฐานข้อมูลและการประมวลผลให้มีความปลอดภัยและยืดหยุ่น...' : 'Updating intelligent cloud technology in 2026 that will revolutionize database storage and processing to be secure and flexible...', 'category' => 'CLOUD', 'image_path' => 'images/bg-hand.jpg']
];
?>

<section class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
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
        /* Custom responsive styles to bypass missing Tailwind build step */
        .mobile-hero-woman { width: 65%; bottom: -100px; right: -12%; }
        @media (min-width: 768px) { .mobile-hero-woman { width: auto; bottom: 0; right: 0; } }
    </style>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-10 pb-16 md:pt-12 md:pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="max-w-3xl relative z-10">
                <div class="animate-entrance-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold hidden md:inline">+</span>
                    <span class="text-blue-500 font-bold md:hidden">•</span>
                    <span class="text-xs md:text-sm font-semibold text-primary tracking-wide">
                        <span class="md:hidden">Digital Solutions for Modern Business</span>
                        <span class="hidden md:inline uppercase">DIGITAL SOLUTIONS FOR MODERN BUSINESS</span>
                    </span>
                </div>

                <h1 class="animate-entrance-up delay-200 text-5xl md:text-6xl lg:text-8xl font-bold leading-[1.1] mb-2 tracking-tighter">
                    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block">WEBPARK</span><br>
                    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block">COMPANY</span>
                </h1>

                <p class="animate-entrance-up delay-300 mt-5 md:mt-6 text-[#022862] text-sm md:text-base lg:text-lg leading-relaxed max-w-lg mb-8 md:mb-10 font-medium">
                    <span class="md:hidden">
                        <?= getCurrentLang() === 'th' ? 'ผู้ให้บริการพัฒนา Digital Platform<br>และระบบ AI ที่ช่วยให้ธุรกิจไทย<br>ก้าวไปข้างหน้า ด้วยเทคโนโลยี<br>ที่ใช้งานได้จริง' : 'Digital Platform and AI system<br>development provider helping Thai businesses<br>move forward with<br>practical technology.' ?>
                    </span>
                    <span class="hidden md:inline">
                        <?= getCurrentLang() === 'th' ? 'ผู้ให้บริการพัฒนา Digital Platform<br class="hidden sm:block">และระบบ AI ที่ช่วยให้ธุรกิจไทยก้าวไปข้างหน้า<br class="hidden sm:block">ด้วยเทคโนโลยีที่ใช้งานได้จริง' : 'Digital Platform and AI system development provider<br class="hidden sm:block">helping Thai businesses move forward<br class="hidden sm:block">with practical technology.' ?>
                    </span>
                </p>

                <div class="animate-entrance-up delay-400 flex flex-col items-start md:flex-row md:items-center gap-4">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(t('common.cta_view_services')) ?>
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
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video')) ?></span>
                    </a>
                </div>
            </div>
            <div class="hidden lg:block lg:col-start-2"></div>
        </div>

        <div class="animate-entrance-left delay-500 absolute right-0 md:right-4 lg:right-8 z-0 pointer-events-none max-w-full transform md:-translate-y-2 flex justify-end mobile-hero-woman">
            <picture class="w-full md:w-auto flex justify-end">
                <source media="(min-width: 768px)" srcset="<?= e(asset_url('images/women.png')) ?>">
                <img src="<?= e(asset_url('images/women-mobile.svg')) ?>" alt="WEBPARK Presenter" class="w-full md:w-auto object-contain object-right-bottom h-auto md:h-[400px] lg:h-[600px] opacity-95 md:opacity-100">
            </picture>
        </div>
    </div>
</section>

<section class="relative z-20 mt-0 md:mt-0 lg:-mt-18 pb-6 lg:pb-16">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6">
        <div class="w-full flex flex-col lg:flex-row items-stretch lg:bg-white lg:rounded-[1rem] lg:shadow-[0_4px_25px_rgba(0,0,0,0.06)] lg:border lg:border-gray-100 lg:overflow-hidden gap-4 lg:gap-0">

            <div class="hidden lg:flex flex-1 lg:max-w-[180px] items-center justify-center p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white">
                <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" class="w-32 lg:w-full h-auto object-contain">
            </div>

            <div class="group flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border lg:border-none border-gray-100 lg:border-r shrink-0 bg-white rounded-t-none rounded-b-[2rem] lg:rounded-none shadow-sm lg:shadow-none transition-all duration-300 hover:bg-slate-50/50 cursor-pointer">
                <div class="grid grid-cols-2 lg:contents items-center gap-4 lg:gap-6 w-full">
                    
                    <div class="flex items-center justify-center lg:hidden">
                        <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" class="w-full max-w-[120px] md:max-w-[150px] h-auto object-contain">
                    </div>
                    
                    <div class="lg:contents text-left">
                        <div class="flex flex-col justify-between h-full lg:h-auto lg:contents">
                            <div>
                                <div class="text-left w-full">
                                    <span class="text-primary font-bold text-lg md:text-sm tracking-wide inline-block border-b-[3px] border-primary pb-0.5 mb-3 mx-0"><?= e(t('common.about_us_heading')) ?></span>
                                </div>
                                <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-3 transition-colors duration-300 group-hover:text-blue-700">
                                    <?= e(t('common.we_are_partner')) ?><br class="hidden lg:inline"><?= e(t('common.in_technology')) ?>
                                </h2>
                                <p class="text-gray-500 text-[0.8rem] md:text-sm leading-relaxed mb-4">
                                    <?= e(t('common.partner_description')) ?>
                                </p>
                            </div>
                            <a href="<?= e(route_url('/about')) ?>" class="inline-flex items-center text-primary text-sm font-semibold transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                                <?= e(t('common.cta_read_more')) ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="flex-[4] grid grid-cols-2 lg:grid-cols-4 w-full bg-white rounded-[2rem] lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none overflow-hidden">
                <?php
                $serviceCards = [
                    ['icon' => 'icon-3.png', 'title' => 'ERP / ERM',        'desc' => t('common.solution_org_control'), 'href' => route_url('/erp')],
                    ['icon' => 'icon-2.png', 'title' => 'Digital Platform', 'desc' => t('common.solution_digital_platform'),              'href' => '#'],
                    ['icon' => 'icon-4.png', 'title' => 'Online Marketing', 'desc' => t('common.solution_online_marketing'),   'href' => '#'],
                    ['icon' => 'icon-1.png', 'title' => 'Creative / Design','desc' => t('common.solution_brand_design'),    'href' => '#'],
                ];
                foreach ($serviceCards as $i => $card):
                    $borderClass = '';
                    if ($i === 0) {
                        $borderClass = 'border-r border-b lg:border-b-0';
                    } elseif ($i === 1) {
                        $borderClass = 'border-b lg:border-r lg:border-b-0';
                    } elseif ($i === 2) {
                        $borderClass = 'border-r';
                    }
                ?>
                    <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                    <div>
                        <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                            <img src="<?= e(asset_url('images/' . $card['icon'])) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-normal lg:whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                            <?= e($card['title']) ?>
                        </h2>
                        <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                            <?= e($card['desc']) ?>
                        </p>
                    </div>
                    <a href="<?= e($card['href']) ?>" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                        <?= e(t('common.cta_read_more')) ?>
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

<section class="bg-white pt-16 pb-6 lg:py-20 overflow-hidden">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">

        <div class="mb-10">
            <!-- Desktop Layout -->
            <div class="hidden md:block">
                <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-primary m-0">
                    <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
                </h2>
                <div class="flex flex-row items-center justify-between gap-4 mb-4">
                    <span class="text-base md:text-lg lg:text-xl font-bold leading-tight text-dark"><?= e(t('home.portfolio_subtitle') !== 'home.portfolio_subtitle' ? t('home.portfolio_subtitle') : (getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Our Portfolio')) ?></span>
                    <a href="<?= e(route_url('/services')) ?>" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(t('home.view_all_services') !== 'home.view_all_services' ? t('home.view_all_services') : (getCurrentLang() === 'th' ? 'ดูบริการทั้งหมด' : 'View All Services')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
                <p class="text-sm md:text-base leading-relaxed text-slate-500 max-w-xl">
                    <?= getCurrentLang() === 'th' ? 'รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล<br>ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน' : 'A collection of digital solutions we designed and developed<br>to help businesses grow sustainably.' ?>
                </p>
            </div>

            <!-- Mobile Layout -->
            <div class="block md:hidden text-left">
                <span class="text-primary font-bold text-xl tracking-wide inline-block border-b-2 border-primary pb-1 mb-3"><?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?></span>
                <h2 class="text-dark font-bold text-2xl leading-tight mb-3"><?= e(t('home.portfolio_subtitle') !== 'home.portfolio_subtitle' ? t('home.portfolio_subtitle') : (getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Our Portfolio')) ?></h2>
                <p class="text-sm leading-relaxed text-slate-500 mb-0">
                    <?= e(getCurrentLang() === 'th' ? 'รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน' : 'A collection of digital solutions we designed and developed to help businesses grow sustainably.') ?>
                </p>
            </div>
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
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md hidden sm:flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div id="portfolio-slider" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
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
                            <div class="h-[200px] sm:h-[180px] lg:h-[200px] w-full overflow-hidden bg-slate-100 shrink-0">
                                <img src="<?= e($projectImage) ?>" alt="<?= e($projectTitle) ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            </div>
                            <div class="p-5 sm:p-4 lg:p-5 flex flex-col flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex sm:hidden lg:flex w-9 h-9 rounded-full bg-slate-100 border border-slate-200 items-center justify-center shrink-0">
                                        <span class="text-[11px] font-bold text-slate-500"><?= e(mb_substr($projectTitle, 0, 2)) ?></span>
                                    </div>
                                    <h3 class="text-base sm:text-sm lg:text-base font-bold text-[#0b1b42] leading-snug line-clamp-1"><?= e($projectTitle) ?></h3>
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
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md hidden sm:flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Dots for Desktop -->
        <div id="portfolio-dots-container" class="hidden lg:flex justify-center items-center gap-2 mt-8"></div>

        <!-- Page Numbers for Mobile/Tablet -->
        <div id="portfolio-pagination-container" class="flex lg:hidden justify-center items-center gap-2 mt-8"></div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prevBtn = document.getElementById('portfolio-prev');
    const nextBtn = document.getElementById('portfolio-next');
    const dotsContainer = document.getElementById('portfolio-dots-container');
    const paginationContainer = document.getElementById('portfolio-pagination-container');
    const cards   = document.querySelectorAll('.portfolio-card');
    
    let cur = 0;
    
    const getVisibleCount = () => window.innerWidth < 1024 ? 2 : 4;
    
    function update() {
        const visible = getVisibleCount();
        const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
        
        cards.forEach((c, i) => c.classList.toggle('hidden', !(i >= cur * visible && i < (cur + 1) * visible)));
        
        // Update pagination rendering based on viewport
        if (window.innerWidth < 1024) {
            renderMobilePagination();
        } else {
            renderDesktopDots();
        }
        
        if (prevBtn) prevBtn.disabled = cur === 0;
        if (nextBtn) nextBtn.disabled = cur >= max;
    }
    
    function renderDesktopDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const visible = getVisibleCount();
        const pageCount = Math.ceil(cards.length / visible);
        
        if (pageCount <= 1) return;
        
        for (let i = 0; i < pageCount; i++) {
            const dot = document.createElement('span');
            dot.className = `portfolio-dot h-2 rounded-full cursor-pointer transition-all ${i === cur ? 'bg-primary w-6' : 'bg-slate-300 w-2'}`;
            dot.dataset.index = i;
            dot.addEventListener('click', () => {
                cur = i;
                update();
            });
            dotsContainer.appendChild(dot);
        }
    }
    
    function renderMobilePagination() {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';
        const visible = getVisibleCount();
        const pageCount = Math.ceil(cards.length / visible);
        
        if (pageCount <= 1) return;
        
        // 1. Prev Arrow [<]
        const prevBtnMobile = document.createElement('button');
        prevBtnMobile.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>`;
        prevBtnMobile.className = `w-9 h-9 rounded-full flex items-center justify-center border transition-all text-slate-400 hover:text-primary hover:border-primary bg-white disabled:opacity-30 disabled:cursor-not-allowed mr-4`;
        prevBtnMobile.disabled = cur === 0;
        prevBtnMobile.addEventListener('click', () => {
            if (cur > 0) {
                cur--;
                update();
            }
        });
        paginationContainer.appendChild(prevBtnMobile);
        
        // 2. Numeric page buttons (up to 3 buttons starting from cur)
        const maxButtons = 3;
        for (let i = 0; i < maxButtons; i++) {
            const pageIndex = cur + i;
            if (pageIndex >= pageCount) break;
            
            const pageBtn = document.createElement('button');
            pageBtn.textContent = pageIndex + 1;
            
            const isActive = i === 0; // The first button is the active one (cur)
            pageBtn.className = `portfolio-page-btn w-9 h-9 rounded-full text-sm font-semibold transition-all flex items-center justify-center ` +
                (isActive 
                    ? 'bg-primary text-white pointer-events-none' 
                    : 'bg-transparent text-slate-600 hover:text-primary');
            
            pageBtn.addEventListener('click', () => {
                cur = pageIndex;
                update();
            });
            paginationContainer.appendChild(pageBtn);
        }
        
        // 3. Next Arrow [>]
        const nextBtnMobile = document.createElement('button');
        nextBtnMobile.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>`;
        nextBtnMobile.className = `w-9 h-9 rounded-full flex items-center justify-center border transition-all text-slate-400 hover:text-primary hover:border-primary bg-white disabled:opacity-30 disabled:cursor-not-allowed ml-4`;
        nextBtnMobile.disabled = cur >= pageCount - 1;
        nextBtnMobile.addEventListener('click', () => {
            if (cur < pageCount - 1) {
                cur++;
                update();
            }
        });
        paginationContainer.appendChild(nextBtnMobile);
    }

    if (prevBtn) prevBtn.addEventListener('click', () => {
        if (cur > 0) {
            cur--;
            update();
        }
    });
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const visible = getVisibleCount();
            const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
            if (cur < max) {
                cur++;
                update();
            }
        });
    }
    
    // Listen to resize to recalculate pages dynamically
    let prevVisible = getVisibleCount();
    window.addEventListener('resize', () => {
        const currentVisible = getVisibleCount();
        if (currentVisible !== prevVisible) {
            prevVisible = currentVisible;
            const max = Math.max(0, Math.ceil(cards.length / currentVisible) - 1);
            if (cur > max) {
                cur = max;
            }
            update();
        }
    });

    update();
});
</script>

<?php
$totalReviews = count($reviews);
if ($totalReviews > 0):
?>
<section class="relative pt-6 pb-10 lg:py-20 overflow-hidden">
    <div class="absolute inset-0 -z-10 pointer-events-none">
        <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" alt="bg" class="w-full h-full object-cover object-center opacity-20 mix-blend-screen">
        <div class="absolute inset-0 bg-white/50"></div>
    </div>

    <div class="relative mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <div class="mb-8 lg:mb-12 text-center max-w-4xl mx-auto">
            <h2 class="hidden lg:block text-primary font-bold text-4xl md:text-3xl tracking-normal uppercase mb-3">
                REVIEW
            </h2>
            <span class="text-base md:text-lg lg:text-xl font-bold leading-tight text-dark">
                <?= getCurrentLang() === 'th' ? 'กว่า <span class="text-primary">120</span> องค์กร ที่เลือก <span class="text-primary">WEBPARK</span> เป็นพาร์ทเนอร์ด้านดิจิทัล' : 'Over <span class="text-primary">120</span> organizations trust <span class="text-primary">WEBPARK</span> as their digital partner' ?>
            </span>
        </div>

        <div class="lg:hidden mb-12">
            <div class="grid grid-cols-1 gap-4">

                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_2.svg" alt="120+ องค์กรชั้นนำ" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight">120+ <span class="text-xl"><?= e(getCurrentLang() === 'th' ? 'องค์กรชั้นนำ' : 'Top Orgs') ?></span></h3>
                        <p class="text-slate-600 text-sm font-medium"><?= e(getCurrentLang() === 'th' ? 'ที่ไว้วางใจ Webpark' : 'Trust Webpark') ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_1.svg" alt="15+ ปี" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight">15+ <span class="text-xl"><?= e(getCurrentLang() === 'th' ? 'ปี' : 'Years') ?></span></h3>
                        <p class="text-slate-600 text-sm font-medium"><?= e(getCurrentLang() === 'th' ? 'แห่งประสบการณ์ ด้านเทคโนโลยี' : 'Of Technology Experience') ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_3.svg" alt="50+" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight underline decoration-[4px] underline-offset-4 decoration-blue-500">50+</h3>
                        <p class="text-slate-600 text-sm font-medium mt-1"><?= e(getCurrentLang() === 'th' ? 'ระบบและโปรเจกต์ ที่ส่งมอบ' : 'Systems & Projects Delivered') ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_4.svg" alt="ครบวงจร" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight"><?= e(getCurrentLang() === 'th' ? 'ครบวงจร' : 'End-to-End') ?></h3>
                        <p class="text-slate-600 text-sm font-medium"><?= e(getCurrentLang() === 'th' ? 'ตั้งแต่วางแผนพัฒนา ถึงดูแลหลังบ้าน' : 'From Planning to Maintenance') ?></p>
                    </div>
                </div>

            </div>
        </div>

        <div class="lg:hidden text-center mb-6">
            <h2 class="text-primary font-bold text-2xl tracking-normal mb-2">
                <?= e(getCurrentLang() === 'th' ? 'เสียงจากลูกค้าของเรา' : 'Testimonials') ?>
            </h2>
        </div>

        <div class="flex items-center justify-between gap-4">
            <button id="review-prev" class="hidden lg:flex w-12 h-12 rounded-full border-2 border-slate-400 items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" disabled>
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

            <button id="review-next" class="hidden lg:flex w-12 h-12 rounded-full border-2 border-slate-400 items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" <?= $totalReviews <= 4 ? 'disabled' : '' ?>>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <div id="review-dots-container" class="flex justify-center items-center gap-2 mt-6 flex-wrap"></div>

        <div class="mx-auto w-full max-w-7xl py-8 mt-10">
            <h2 class="text-center text-primary font-bold text-2xl md:text-3xl tracking-normal uppercase mb-3 block">
                <?= e(getCurrentLang() === 'th' ? 'องค์กรชั้นนำที่ไว้วางใจ WEBPARK' : 'Leading Organizations that Trust WEBPARK') ?>
            </h2>
            <div class="overflow-hidden relative mt-10">
                <div class="grid grid-cols-3 lg:flex lg:justify-center lg:flex-wrap gap-y-8 gap-x-4 md:gap-16 opacity-80 justify-items-center items-center">
                    <?php foreach ($partnerLogos as $logo): ?>
                        <div class="flex shrink-0 items-center justify-center w-[100px] h-[50px]">
                            <img src="<?= e(asset_url('images/' . $logo)) ?>" alt="Partner" class="max-h-full max-w-full object-contain lg:grayscale lg:hover:grayscale-0 transition-all duration-300 cursor-pointer">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="text-center mt-6 text-xs text-slate-400 tracking-wide font-medium">
                <?= e(getCurrentLang() === 'th' ? 'ทั้งหมดมาจากธุรกิจ การเงิน อสังหาริมทรัพย์ โรงงาน วิศวกรรม สื่อ และอีกมากมาย' : 'Including finance, real estate, manufacturing, engineering, media, and more.') ?>
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prevBtn = document.getElementById('review-prev');
    const nextBtn = document.getElementById('review-next');
    const dotsContainer = document.getElementById('review-dots-container');
    const cards   = document.querySelectorAll('.review-card');
    
    let cur = 0;
    
    const getVisibleCount = () => {
        if (window.innerWidth < 640) {
            return 1; // Mobile (<640px): 1 card (1 column, 1 row)
        }
        if (window.innerWidth < 1024) {
            return 2; // Tablet (<1024px): 2 cards
        }
        return 4; // Desktop (>=1024px): 4 cards
    };
    
    function update() {
        const visible = getVisibleCount();
        const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
        
        cards.forEach((c, i) => {
            const isVisible = i >= cur * visible && i < (cur + 1) * visible;
            c.classList.toggle('hidden', !isVisible);
        });
        
        // Update dots styling
        const dots = document.querySelectorAll('.review-dot');
        dots.forEach((d, i) => {
            d.classList.toggle('bg-primary', i === cur);
            d.classList.toggle('w-6', i === cur);
            d.classList.toggle('bg-slate-300', i !== cur);
            d.classList.toggle('w-2', i !== cur);
        });
        
        if (prevBtn) prevBtn.disabled = cur === 0;
        if (nextBtn) nextBtn.disabled = cur >= max;
    }
    
    function renderDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const visible = getVisibleCount();
        const pageCount = Math.ceil(cards.length / visible);
        
        if (pageCount <= 1) return;
        
        for (let i = 0; i < pageCount; i++) {
            const dot = document.createElement('span');
            dot.className = `review-dot h-2 rounded-full cursor-pointer transition-all ${i === cur ? 'bg-primary w-6' : 'bg-slate-300 w-2'}`;
            dot.addEventListener('click', () => {
                cur = i;
                update();
            });
            dotsContainer.appendChild(dot);
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (cur > 0) {
                cur--;
                update();
            }
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const visible = getVisibleCount();
            const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
            if (cur < max) {
                cur++;
                update();
            }
        });
    }
    
    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    const slider = document.getElementById('review-slider');
    if (slider) {
        slider.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        slider.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
    }
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const visible = getVisibleCount();
        const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
        
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left -> Next page
            if (cur < max) {
                cur++;
                update();
            }
        } else if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right -> Prev page
            if (cur > 0) {
                cur--;
                update();
            }
        }
    }
    
    // Listen to resize to recalculate pages dynamically
    let prevVisible = getVisibleCount();
    window.addEventListener('resize', () => {
        const currentVisible = getVisibleCount();
        if (currentVisible !== prevVisible) {
            prevVisible = currentVisible;
            const max = Math.max(0, Math.ceil(cards.length / currentVisible) - 1);
            if (cur > max) {
                cur = max;
            }
            renderDots();
            update();
        }
    });

    renderDots();
    update();
});
</script>
<?php endif; ?>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<section class="bg-slate-50 pt-10 pb-20 lg:py-20 border-t border-slate-100">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <div class="hidden lg:flex items-end justify-between border-b border-slate-200 pb-5 mb-10">
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

        <div class="lg:hidden mb-6 flex flex-col items-start text-left pl-1">
            <h2 class="text-primary font-black text-2xl tracking-normal mb-2 pb-1 inline-block border-b-[3px] border-primary">
                บทความ
            </h2>
            <h3 class="text-dark font-black text-xl mb-3 mt-1 tracking-tight">
                สาระน่ารู้จาก WEBPARK
            </h3>
            <p class="text-slate-600 text-[0.8rem] leading-[1.6] font-medium">
                รวมบทความสาระน่ารู้ ที่จะช่วยต่อยอดแบรนด์<br>และพาธุรกิจสู่ดิจิทัลที่ช่วยให้ธุรกิจเติบโตได้อย่างยั่งยืน
            </p>
        </div>

        <?php $displayArticles = $mockArticles; // ใช้ข้อมูลจำลองบทความชั่วคราว ?>
        
        <?php if (count($displayArticles) > 0): ?>
        <div id="knowledge-slider" class="flex lg:grid overflow-x-auto lg:overflow-visible snap-x snap-mandatory flex-nowrap lg:flex-wrap lg:grid-cols-3 gap-8 pt-2 pb-6 hide-scrollbar">
            <?php foreach ($displayArticles as $art): ?>
                <?php
                $artId       = (int)($art['id'] ?? 0);
                $artTitle    = (string)($art['title'] ?? 'Article');
                $artSummary  = mb_strimwidth(strip_tags((string)($art['summary'] ?? '')), 0, 110, '...');
                $artCat      = (string)($art['category'] ?? 'Knowledge');
                $artImage    = asset_url($art['image_path'] ?? 'images/erp.png');
                ?>
                <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md group w-full lg:w-auto shrink-0 lg:shrink snap-center lg:snap-align-none">
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
        
        <div id="knowledge-dots" class="flex lg:hidden justify-center items-center gap-2 mt-4 flex-wrap"></div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('knowledge-slider');
    if (!slider) return;
    const cards = slider.querySelectorAll('article');
    const dotsContainer = document.getElementById('knowledge-dots');
    if (!dotsContainer) return;
    
    const totalItems = cards.length;
    
    function updateKnowledgeDots() {
        const scrollLeft = slider.scrollLeft;
        const cardWidth = cards[0]?.getBoundingClientRect().width || slider.clientWidth || 1;
        const index = Math.min(totalItems - 1, Math.max(0, Math.round(scrollLeft / cardWidth)));
        
        const dots = dotsContainer.querySelectorAll('.knowledge-dot');
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-primary', i === index);
            dot.classList.toggle('w-6', i === index);
            dot.classList.toggle('bg-slate-300', i !== index);
            dot.classList.toggle('w-2', i !== index);
        });
    }
    
    if (totalItems > 1) {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < totalItems; i++) {
            const dot = document.createElement('span');
            dot.className = `knowledge-dot h-2 rounded-full cursor-pointer transition-all ${i === 0 ? 'bg-primary w-6' : 'bg-slate-300 w-2'}`;
            dot.addEventListener('click', () => {
                const cardWidth = cards[0]?.getBoundingClientRect().width || slider.clientWidth;
                slider.scrollTo({
                    left: i * cardWidth,
                    behavior: 'smooth'
                });
            });
            dotsContainer.appendChild(dot);
        }
        
        slider.addEventListener('scroll', updateKnowledgeDots, { passive: true });
        window.addEventListener('resize', updateKnowledgeDots, { passive: true });
    }
});
</script>