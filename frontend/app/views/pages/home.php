<?php

declare(strict_types=1);
                $weDoList = [
                    [
                        'title' => 'ERP / ERM',
                        'desc' => 'ระบบบริหารจัดการองค์กรแบบครบวงจร เชื่อมโยงทุกกระบวนการทำงานอย่างมีประสิทธิภาพ',
                        'icon' => 'images/icon-1.png'
                    ],
                    [
                        'title' => 'Digital Platform',
                        'desc' => 'พัฒนาแพลตฟอร์มดิจิทัลที่ตอบโจทย์ธุรกิจยุคใหม่ รองรับการเติบโต',
                        'icon' => 'images/icon-1.png'
                    ],
                    [
                        'title' => 'Online Marketing',
                        'desc' => 'วางกลยุทธ์และทำการตลาดออนไลน์ เพิ่มการเข้าถึงและยอดขาย',
                        'icon' => 'images/icon-2.png'
                    ],
                    [
                        'title' => 'Creative / Design',
                        'desc' => 'ออกแบบแบรนด์ให้โดดเด่นและน่าเชื่อถือ',
                        'icon' => 'images/icon-1.png'
                    ],
                ];

// ระบบดักจับตัวแปรบทความจาก Database ให้กว้างขึ้น
$services = $services ?? [];
$activeTab = $activeTab ?? 'news';
$displayArticles = $latestArticles ?? $articles ?? $blogs ?? [];
$reviews = $reviews ?? [];

$projectRoot = dirname(__DIR__, 3);

$resolveServiceImage = static function (string $imagePath) use ($projectRoot): string {
    $imagePath = trim($imagePath);

    if ($imagePath === '') {
        return '';
    }

    if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
        return $imagePath;
    }

    if (str_starts_with($imagePath, '/assets/') || str_starts_with($imagePath, 'assets/') || str_starts_with($imagePath, 'public/assets/')) {
        $normalizedPath = ltrim($imagePath, '/');

        if (str_starts_with($normalizedPath, 'assets/')) {
            $filePath = $projectRoot . '/public/' . $normalizedPath;
        } elseif (str_starts_with($normalizedPath, 'public/assets/')) {
            $filePath = $projectRoot . '/' . $normalizedPath;
        } else {
            $filePath = $projectRoot . '/public/assets/' . $normalizedPath;
        }

        if (is_file($filePath)) {
            return asset_url($imagePath);
        }

        return '';
    }

    if (str_starts_with($imagePath, '/images/') || str_starts_with($imagePath, 'images/')) {
        $normalizedPath = ltrim($imagePath, '/');
        $filePath = $projectRoot . '/public/assets/' . $normalizedPath;

        if (is_file($filePath)) {
            return asset_url($normalizedPath);
        }

        return '';
    }

    if (str_starts_with($imagePath, '/')) {
        return app_url(ltrim($imagePath, '/'));
    }

    return asset_url('images/' . ltrim($imagePath, '/'));
};

$resolveReviewImage = static function (string $imagePath) use ($resolveServiceImage): string {
    $imagePath = trim($imagePath);

    if (str_starts_with($imagePath, '//')) {
        return $imagePath;
    }

    $resolvedImage = $resolveServiceImage($imagePath);

    return $resolvedImage !== '' ? $resolvedImage : asset_url('images/women-office.jpg');
};
?>
<section class="relative font-sans bg-[#f7faff]">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

<style>
    /* แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง */
    @keyframes fadeSlideUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* แอนิเมชันสำหรับรูปภาพสไลด์จากด้านขวา */
    @keyframes fadeSlideLeft {
        0% {
            opacity: 0;
            transform: translateX(50px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* คลาสหลักที่ใช้เรียกแอนิเมชัน (ใช้ cubic-bezier เพื่อความนุ่มนวลแบบพรีเมียม) */
    .animate-entrance-up {
        opacity: 0; /* ซ่อนไว้ก่อนเริ่มแอนิเมชัน */
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .animate-entrance-left {
        opacity: 0;
        animation: fadeSlideLeft 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* คลาสสำหรับหน่วงเวลา (Delay) เพื่อให้เกิดการไล่ลำดับ (Staggered) */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .delay-500 { animation-delay: 500ms; }
</style>

<div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10 overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        
        <div class="max-w-3xl">
            <div class="animate-entrance-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                <span class="text-blue-500 font-bold">+</span>
                <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">Digital Solutions for Modern Business</span>
            </div>

            <h1 class="animate-entrance-up delay-200 text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
                <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block">WEBPARK</span><br>
                <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block">COMPANY</span>
            </h1>

            <p class="animate-entrance-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                ผู้ให้บริการพัฒนา Digital Platform<br class="hidden sm:block">
                และระบบ AI ที่ช่วยให้ธุรกิจไทยก้าวไปข้างหน้า<br class="hidden sm:block">
                ด้วยเทคโนโลยีที่ใช้งานได้จริง
            </p>

            <div class="animate-entrance-up delay-400 flex flex-wrap items-center gap-4">
                <a href="<?= e(route_url('/service')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                    ดูบริการของเรา
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                <a href="#about" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                    <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                    <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors">
                        ดูวิดีโอแนะนำ
                    </span>
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
    <div class="w-full rounded-[1rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden relative">
    
        <div class="flex-1 lg:max-w-[180px] flex items-center justify-center p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white">
            <img src="/WEBPARK/frontend/public/assets/images/logo.png" alt="WEBPARK logo" class="w-32 lg:w-full h-auto object-contain">
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
            <a href="/WEBPARK/?url=about" class="inline-flex items-center text-primary text-sm font-semibold transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                อ่านเพิ่มเติม
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>

        <div class="flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
            
            <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 border-b sm:border-b-0 sm:border-r border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                <div>
                    <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                        <img src="/WEBPARK/frontend/public/assets/images/icon-3.png" alt="ERP / ERM" class="h-full w-full object-contain">
                    </div>
                    <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                        ERP / ERM
                    </h2>
                    <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                        ระบบบริหารจัดการองค์กรและควบคุมระบบ เพื่อเพิ่มทุกกระบวนการทำงานอย่างมีประสิทธิภาพ
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 border-b sm:border-b-0 sm:border-r border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                <div>
                    <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                        <img src="/WEBPARK/frontend/public/assets/images/icon-2.png" alt="Digital Platform" class="h-full w-full object-contain">
                    </div>
                    <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                        Digital Platform
                    </h2>
                    <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                        พัฒนาแพลตฟอร์มดิจิทัลทั้งออนไลน์และออฟไลน์ รองรับการเติบโตและการขยายตัว
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 border-b sm:border-b-0 sm:border-r border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                <div>
                    <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                        <img src="/WEBPARK/frontend/public/assets/images/icon-4.png" alt="Online Marketing" class="h-full w-full object-contain">
                    </div>
                    <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                        Online Marketing
                    </h2>
                    <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                        วางกลยุทธ์และทำการตลาดออนไลน์ เพื่อการเข้าถึงกลุ่มเป้าหมาย และผลลัพธ์ที่วัดผลได้
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                <div>
                    <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                        <img src="/WEBPARK/frontend/public/assets/images/icon-1.png" alt="Creative / Design" class="h-full w-full object-contain">
                    </div>
                    <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                        Creative / Design
                    </h2>
                    <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                        ออกแบบและสร้างสรรค์ภาพลักษณ์ของแบรนด์ให้โดดเด่น สร้างการจดจำและตอบโจทย์แคมเปญ
                    </p>
                </div>
                <a href="#" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

        </div> 
    </div>
</div>
</section>

<section class="bg-white py-20 lg:py-20">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <div class="mb-8">
    <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        ผลงานของเรา
    </span>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-primary uppercase m-0 max-w-3xl">
            ตัวอย่างผลงานของเรา
        </h2>
        
        <a href="services.php" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
            ดูบริการของเรา
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>

    <p class="text-sm md:text-base leading-relaxed text-slate-600 max-w-3xl">
        รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล<br>ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน
    </p>
</div>

        <?php
        // Get all portfolios from database
        $portfolioModel = new Portfolio();
        $allPortfolios = $portfolioModel->getPublished();
        $displayPortfolios = $allPortfolios;
        $totalPortfolios = count($displayPortfolios);
        ?>
        <div class="flex-1">
         <div id="portfolio-slider" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php if (!empty($displayPortfolios)): ?>
            <?php foreach ($displayPortfolios as $index => $project): ?>
                <?php $project = (array) $project; ?>
                <?php $isVisible = $index < 4 ? '' : 'hidden'; ?>

                <article class="portfolio-card group h-[420px] rounded-[1.2rem] overflow-hidden border border-[#f3f4f6] bg-white shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-[0_4px_30px_rgba(0,0,0,0.15)] transition-all duration-500 <?= $isVisible ?>" data-index="<?= $index ?>">

                    <?php $projectId = (int) ($project['id'] ?? 0); ?>
                    <a href="<?= e($projectId > 0 ? route_url('/portfolio', ['id' => $projectId]) : route_url('/portfolio')) ?>" class="flex flex-col h-full block w-full">

                        <div class="h-[60%] group-hover:h-[40%] w-full overflow-hidden bg-slate-100 relative shrink-0 transition-all duration-500 ease-in-out">
                            <?php
                            $projImgPath = $project['image_path'] ?? '';
                            $projectImage = asset_url('images/erp.png');
                            if ($projImgPath !== '') {
                                $projFullUrl = asset_url($projImgPath);
                                $projParsed = parse_url($projFullUrl, PHP_URL_PATH);
                                $projFilePath = $_SERVER['DOCUMENT_ROOT'] . $projParsed;
                                $projectImage = file_exists($projFilePath) ? $projFullUrl : asset_url('images/erp.png');
                            }
                            ?>
                            <img src="<?= e($projectImage) ?>" alt="<?= e($project['cover_image_alt'] ?? $project['title'] ?? 'Portfolio') ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        </div>

                        <div class="h-[40%] group-hover:h-[60%] p-5 sm:p-6 w-full flex flex-col justify-between bg-white group-hover:bg-primary transition-all duration-500 ease-in-out">
                            
                            <div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden group-hover:border-white/50 transition-colors">
                                        <span class="text-[10px] font-bold text-slate-400 group-hover:text-white">Icon</span>
                                    </div>

                                    <h3 class="text-base sm:text-lg font-bold text-[#0b1b42] group-hover:text-white transition-colors duration-500 leading-snug line-clamp-1">
                                        <?= e(!empty($project['title']) ? $project['title'] : 'Untitled Project') ?>
                                    </h3>
                                </div>

                                <div class="grid grid-rows-[0fr] group-hover:grid-rows-[1fr] transition-all duration-500 ease-in-out mt-1">
                                    <div class="overflow-hidden">
                                        <p class="pt-3 text-[13px] leading-relaxed text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 line-clamp-3">
                                            <?php
                                            $rawDescription = !empty($project['description'])
                                                ? $project['description']
                                                : (!empty($project['summary']) ? $project['summary'] : 'รายละเอียดผลงาน...');

                                            echo e(mb_strimwidth(strip_tags((string)$rawDescription), 0, 100, "..."));
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto text-left pt-3">
                                <span class="inline-block text-[11px] sm:text-xs font-medium px-4 py-1.5 rounded-full border border-primary/50 transition-all duration-500 
                                     text-primary bg-transparent 
                                     group-hover:border-white group-hover:text-white">
                                    <?= e($project['category'] ?? 'Portfolio') ?>
                                </span>
                            </div>
                        </div>

                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-12 text-slate-400 text-sm">
                ไม่พบข้อมูลผลงานในระบบ
            </div>
        <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('portfolio-prev');
        const nextBtn = document.getElementById('portfolio-next');
        const dots = document.querySelectorAll('.portfolio-dot');
        const visibleCards = 4;
        let currentIndex = 0;

        // Get all portfolio cards
        const cards = document.querySelectorAll('.portfolio-card');
        const totalCards = cards.length;
        const maxIndex = Math.max(0, Math.ceil(totalCards / visibleCards) - 1);

        if (prevBtn && nextBtn && cards.length > 0) {
            function updateVisibility() {
                cards.forEach((card, i) => {
                    const startIndex = currentIndex * visibleCards;
                    const endIndex = startIndex + visibleCards;
                    if (i >= startIndex && i < endIndex) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            function updateDots() {
                dots.forEach((dot, i) => {
                    if (i === currentIndex) {
                        dot.classList.remove('bg-slate-300', 'w-2');
                        dot.classList.add('bg-primary', 'w-6');
                    } else {
                        dot.classList.remove('bg-primary', 'w-6');
                        dot.classList.add('bg-slate-300', 'w-2');
                    }
                });
            }

            function updateButtons() {
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
            }

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            // Click on dots
            dots.forEach((dot, i) => {
                dot.addEventListener('click', function() {
                    currentIndex = i;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                });
            });
        }
    });
</script>

<?php
$totalReviews = count($reviews);
?>

<?php if ($totalReviews > 0): ?>

<section class="relative py-20 lg:py-20 overflow-hidden">
    
    <div class="absolute inset-0 -z-10 pointer-events-none">
    <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" 
         alt="WEBPARK Solutions Background" 
         class="w-full h-full object-cover object-center opacity-20 mix-blend-screen">
         
    <div class="absolute inset-0 bg-white/50"></div>
</div>
    <div class="relative mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="mb-12 text-center max-w-4xl mx-auto">
                <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
                    รีวิว
                </span>
                <h2 class="text-2xl md:text-3xl lg:text-[2.2rem] font-bold leading-tight text-dark">
                    กว่า <span class="text-primary">120</span> องค์กร ที่เลือก <span class="text-primary">WEBPARK</span> เป็นพาร์ทเนอร์ด้านดิจิทัล
                </h2>
            </div>

            <div class="flex items-center justify-between gap-4">
                <button id="review-prev" class="w-12 h-12 rounded-full border-2 border-slate-400 flex items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="flex-1">
                    <div id="review-slider" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php foreach ($reviews as $index => $review): ?>
                            <?php
                            $reviewerName = (string) ($review['reviewer_name'] ?? '');
                            $reviewerMeta = implode(', ', array_values(array_filter([
                                (string) ($review['reviewer_position'] ?? ''),
                                (string) ($review['reviewer_company'] ?? ''),
                            ], static fn(string $value): bool => trim($value) !== '')));
                            $reviewerImage = asset_url('images/women-office.jpg');
                            ?>
                            <?php $isVisible = $index < 4 ? '' : 'hidden'; ?>
<article class="review-card group shrink-0 bg-white rounded-[1.5rem] p-5 lg:p-6 shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-[#f3f4f6] flex flex-col justify-between hover:bg-primary hover:shadow-[0_4px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 h-[280px] <?= $isVisible ?>" data-index="<?= $index ?>">

    <div class="flex items-center justify-center w-full mb-3 mt-1 shrink-0">
        <div class="flex items-center gap-1.5">
            <?php 
                // ดึงค่า rating ถ้าไม่มีให้กำหนดค่าเริ่มต้นเป็น 5
                $rating = isset($review['rating']) ? (int)$review['rating'] : 5; 
                for ($i = 1; $i <= 5; $i++): 
            ?>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-colors <?= $i <= $rating ? 'text-yellow-400 group-hover:text-yellow-300' : 'text-slate-200 group-hover:text-white/30' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                </svg>
            <?php endfor; ?>
        </div>
    </div>

    <p class="text-sm md:text-sm lg:text-sm leading-relaxed text-slate-600 group-hover:text-white transition-colors mb-1 line-clamp-4 overflow-hidden">
        <?= e($review['content'] ?? '') ?>
    </p>

    <div class="flex items-center gap-3 mt-3 shrink-0">
        <img class="h-10 w-10 lg:h-11 lg:w-11 rounded-full object-cover bg-slate-100 shrink-0"
             src="<?= e($reviewerImage) ?>"
             alt="<?= e($reviewerName !== '' ? $reviewerName : 'Customer') ?>" />
        <div class="overflow-hidden">
            <h4 class="text-sm lg:text-sm font-bold text-dark group-hover:text-white transition-colors truncate">
                <?= e($reviewerName) ?>
            </h4>
            <?php if ($reviewerMeta !== ''): ?>
                <p class="text-2xs text-slate-500 group-hover:text-white/80 transition-colors truncate mt-0.5">
                    <?= e($reviewerMeta) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

</article>
                        <?php endforeach; ?>
                    </div>
                </div> 
                <button id="review-next" class="w-12 h-12 rounded-full border-2 border-slate-400 flex items-center justify-center text-slate-400 hover:bg-white hover:text-primary transition-colors shrink-0 disabled:opacity-30 disabled:cursor-not-allowed" <?= $totalReviews <= 4 ? 'disabled' : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <?php if ($totalReviews > 4): ?>
            <div class="flex justify-center items-center gap-2 mt-6 flex-wrap">
                <?php
                $totalDots = ceil($totalReviews / 4);
                for ($i = 0; $i < $totalDots; $i++): ?>
                    <span class="review-dot h-2 rounded-full cursor-pointer hover:bg-primary transition-all <?= $i === 0 ? 'bg-primary w-6' : 'bg-slate-300 w-2' ?>" data-index="<?= $i ?>"></span>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        </div>
        <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8 py-8 mt-10">
        <span class="text-center text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
                    พันธมิตร
        </span>
        
        <h2 class="text-center text-2xl md:text-3xl lg:text-[2.2rem] font-bold leading-tight text-dark ">
                    องค์กรชั้นนำที่ไว้วางใจ <span class="text-primary">WEBPARK</span> 
        </h2>

        <div class="overflow-hidden relative mt-10">
            <div class="flex animate-scroll gap-14 md:gap-16 lg:gap-16">
                <?php
                // ปรับให้เป็น yamaha.png ทั้งหมดชั่วคราว เพื่อทดสอบหน้าตาและการเลื่อน
                $partnerLogos = ['yamaha.png', 'yamaha.png', 'yamaha.png', 'yamaha.png', 'yamaha.png'];
                // Two sets for seamless infinite scroll
                $allLogos = array_merge($partnerLogos, $partnerLogos);
                foreach ($allLogos as $logo): ?>
                    <div class="flex shrink-0 items-center justify-center">
                        <img src="<?= e(asset_url('images/' . $logo)) ?>"
                             alt="Partner Logo"
                             class="max-h-5 md:max-h-7 lg:max-h-8 w-auto object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-pointer">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
    </div>
</section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('review-prev');
        const nextBtn = document.getElementById('review-next');
        const dots = document.querySelectorAll('.review-dot');
        const visibleCards = 4;
        let currentIndex = 0;

        const cards = document.querySelectorAll('.review-card');
        const totalCards = cards.length;
        const maxIndex = Math.max(0, Math.ceil(totalCards / visibleCards) - 1);

        if (prevBtn && nextBtn && cards.length > 0) {
            function updateVisibility() {
                cards.forEach((card, i) => {
                    const startIndex = currentIndex * visibleCards;
                    const endIndex = startIndex + visibleCards;
                    if (i >= startIndex && i < endIndex) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            function updateDots() {
                dots.forEach((dot, i) => {
                    if (i === currentIndex) {
                        dot.classList.remove('bg-slate-300', 'w-2');
                        dot.classList.add('bg-primary', 'w-6');
                    } else {
                        dot.classList.remove('bg-primary', 'w-6');
                        dot.classList.add('bg-slate-300', 'w-2');
                    }
                });
            }

            function updateButtons() {
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
            }

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            dots.forEach((dot, i) => {
                dot.addEventListener('click', function() {
                    currentIndex = i;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                });
            });
        }
    });
    </script>
<?php endif; ?>


<section class="bg-white py-20 lg:py-20">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
                    บทความ
            </span>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-primary uppercase m-0 max-w-3xl">
            สาระน่ารู้จาก <span>WEBPARK</span>
        </h2>
        
        <a href="services.php" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
            ดูบริการของเรา
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
    <p class="mb-4 text-sm md:text-base leading-relaxed text-slate-600 max-w-3xl">
รวมบทความสาระหน้ารู้ ที่จะช่วยต่อยอดแบรนด์
<br>และพาธุรกิจสู่ดิจิทัลที่ช่วยให้ธุรกิจเติบโตได้อย่างยั่งยืน    </p>

        <?php
        // Fetch articles from database
        $article = new Article();
        $allArticles = $article->getPublished();
        $totalArticles = count($allArticles);
        ?>
        <?php if ($totalArticles > 0): ?>
        <div class="flex items-center justify-between gap-4">
    
    <div class="flex-1">
        <div id="article-slider" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($allArticles as $index => $post): ?>
                <?php $post = (array) $post; ?>
                
                <?php $isVisible = $index < 3 ? '' : 'hidden'; ?>

                <article class="article-card group h-[460px] rounded-[1.2rem] overflow-hidden border border-[#f3f4f6] bg-white shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-[0_4px_30px_rgba(0,0,0,0.1)] transition-all duration-500 <?= $isVisible ?>" data-index="<?= $index ?>">

                    <?php $articleId = (int) ($post['id'] ?? 0); ?>
                    <a href="<?= e($articleId > 0 ? route_url('/article', ['id' => $articleId]) : route_url('/article')) ?>" class="flex flex-col h-full block w-full">

                        <div class="h-[50%] group-hover:h-[35%] w-full overflow-hidden bg-slate-100 relative shrink-0 transition-all duration-500 ease-in-out">
                            <?php
                            $postImgPath = $post['image_path'] ?? '';
                            $postImage = asset_url('images/story.png');
                            if ($postImgPath !== '') {
                                $postFullUrl = asset_url($postImgPath);
                                $postParsed = parse_url($postFullUrl, PHP_URL_PATH);
                                $postFilePath = $_SERVER['DOCUMENT_ROOT'] . $postParsed;
                                $postImage = file_exists($postFilePath) ? $postFullUrl : asset_url('images/story.png');
                            }
                            ?>
                            <img src="<?= e($postImage) ?>" alt="<?= e($post['cover_image_alt'] ?? $post['meta_title'] ?? 'Article') ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        </div>

                        <div class="h-[50%] group-hover:h-[65%] p-5 sm:p-6 w-full flex flex-col bg-white transition-all duration-500 ease-in-out">
                            
                            <h3 class="text-base sm:text-lg font-bold text-primary leading-snug line-clamp-2">
                                <?= e(!empty($post['meta_title']) ? $post['meta_title'] : 'Untitled Article') ?>
                            </h3>

                            <div class="mt-3 overflow-hidden">
                                <p class="text-[13px] md:text-sm leading-relaxed text-[#6b7280] line-clamp-3 group-hover:line-clamp-[7] transition-all duration-500">
                                    <?php
                                    $rawDescription = !empty($post['meta_description'])
                                        ? $post['meta_description']
                                        : (!empty($post['content']) ? $post['content'] : 'รายละเอียดบทความ...');

                                    echo e(mb_strimwidth(strip_tags((string)$rawDescription), 0, 300, "..."));
                                    ?>
                                </p>
                            </div>

                            <div class="mt-auto text-right pt-4">
                                <span class="inline-flex items-center gap-1 text-[13px] font-semibold text-primary hover:text-primary transition-colors">
                                    อ่านเพิ่มเติม
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                            
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
        <?php else: ?>
        <div class="text-center py-12 text-slate-400 text-sm">
            ไม่พบข้อมูลบทความในระบบ
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('article-prev');
        const nextBtn = document.getElementById('article-next');
        const dots = document.querySelectorAll('.article-dot');
        const visibleCards = 3;
        let currentIndex = 0;

        const cards = document.querySelectorAll('.article-card');
        const totalCards = cards.length;
        const maxIndex = Math.max(0, Math.ceil(totalCards / visibleCards) - 1);

        if (prevBtn && nextBtn && cards.length > 0) {
            function updateVisibility() {
                cards.forEach((card, i) => {
                    const startIndex = currentIndex * visibleCards;
                    const endIndex = startIndex + visibleCards;
                    if (i >= startIndex && i < endIndex) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            function updateDots() {
                dots.forEach((dot, i) => {
                    if (i === currentIndex) {
                        dot.classList.remove('bg-slate-300', 'w-2');
                        dot.classList.add('bg-primary', 'w-6');
                    } else {
                        dot.classList.remove('bg-primary', 'w-6');
                        dot.classList.add('bg-slate-300', 'w-2');
                    }
                });
            }

            function updateButtons() {
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex;
            }

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                }
            });

            dots.forEach((dot, i) => {
                dot.addEventListener('click', function() {
                    currentIndex = i;
                    updateVisibility();
                    updateDots();
                    updateButtons();
                });
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.js-filter-btn');
        const logoItems = document.querySelectorAll('.js-logo-item');

        const activeClasses = ['border-brand-cyan', 'bg-cyan-50', 'text-brand-cyan', 'font-bold'];
        const inactiveClasses = ['border-slate-200', 'bg-white', 'text-slate-500', 'font-medium'];

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...inactiveClasses);
                });
                this.classList.remove(...inactiveClasses);
                this.classList.add(...activeClasses);

                const filterValue = this.getAttribute('data-filter');
                logoItems.forEach(item => {
                    if (filterValue === 'all' || filterValue === item.getAttribute('data-category')) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>