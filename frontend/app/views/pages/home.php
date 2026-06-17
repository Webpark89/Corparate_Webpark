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
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-30 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-200 bg-white mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">Digital Solutions for Modern Business</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-8xl font-semibold text-dark leading-[1.1] mb-2 tracking-tighter">
                    WEBPARK<br>
                    <span class="text-primary">COMPANY</span>
                </h1>

                <p class="mt-6 text-slate-500 text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    ผู้ให้บริการพัฒนา Digital Platform<br class="hidden sm:block">
                    และระบบ AI ที่ช่วยให้ธุรกิจไทยก้าวไปข้างหน้า<br class="hidden sm:block">
                    ด้วยเทคโนโลยีที่ใช้งานได้จริง
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="services.php" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ดูบริการของเรา
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="hidden lg:block lg:col-start-2"></div>
            
        </div>
        <div class="absolute bottom-0 right-0 md:right-4 lg:right-8 z-10 pointer-events-none max-w-full">
            <img src="<?= e(asset_url('images/women.png')) ?>" alt="WEBPARK Presenter" class="w-auto object-contain h-[400px] md:h-[400px] lg:h-[600px] max-w-full">
        </div>
    </div>
</section>

<section class="bg-[#ffffff] py-1">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="w-full rounded-[1rem] bg-white p-6 lg:p-8 flex flex-col lg:flex-row items-stretch gap-6 lg:gap-0 shadow-[0_4px_25px_rgba(0,0,0,0.03)] border border-gray-100">
            
            <div class="flex-1 lg:max-w-[180px] flex flex-col justify-center lg:pr-6 lg:border-r border-gray-100 shrink-0">
                <div class="w-32 lg:w-full">
                    <img src="/WEBPARK/frontend/public/assets/images/logo.png" alt="WEBPARK logo" class="w-full h-auto object-contain">
                </div>
            </div>

            <div class="flex-1 lg:max-w-[320px] flex flex-col justify-between lg:pl-6 lg:pr-8 lg:border-r border-gray-100 shrink-0">
                 <div>
                    <span class="text-primary font-bold text-sm tracking-wide block mb-2">เกี่ยวกับเรา</span>
                    <h2 class="text-dark text-2xl font-bold leading-tighter mb-4">
                    เราคือ พาร์ทเนอร์<br>ด้านเทคโนโลยี
                    </h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    มุ่งมั่นพัฒนาโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจยุคใหม่ ด้วยทีมงานมืออาชีพพร้อมแนวคิดและเทคโนโลยีในการยกระดับการทำงานของคุณ
                    </p>
                </div>
                <a href="/WEBPARK/?url=about" class="inline-flex items-center gap-1.5 text-primary text-3xs font-semibold hover:gap-2.5 transition-all w-max mb-4 lg:mb-0">
                 อ่านเพิ่มเติม
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
                </a>
            </div>

            <div class="flex-[4] grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 w-full">
                
                <div class="flex flex-col justify-between p-4 lg:px-6 lg:py-2 bg-white border-b sm:border-b-0 sm:border-r border-gray-100">
                    <div>
                        <div class="h-16 w-16 mx-auto mb-5 flex items-center justify-center">
                            <img src="/WEBPARK/frontend/public/assets/images/icon-3.png" alt="ERP / ERM" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-dark font-bold text-5.5 text-center mb-3">
                            ERP / ERM
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5 text-left min-h-card-sm">
                            ระบบบริหารจัดการองค์กรแบบครบวงจร เชื่อมโยงทุกกระบวนการทำงานอย่างมีประสิทธิภาพ
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center gap-1.5 text-primary text-3xs font-medium hover:gap-2.5 transition-all w-max mt-auto">
                        อ่านเพิ่มเติม
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="flex flex-col justify-between p-4 lg:px-6 lg:py-2 bg-white border-b sm:border-b-0 sm:border-r border-gray-100">
                    <div>
                        <div class="h-16 w-16 mx-auto mb-5 flex items-center justify-center">
                            <img src="/WEBPARK/frontend/public/assets/images/icon-2.png" alt="Digital Platform" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-dark font-bold text-5.5 text-center mb-3">
                            Digital Platform
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5 text-left min-h-card-sm">
                            พัฒนาแพลตฟอร์มดิจิทัลที่ตอบโจทย์ธุรกิจยุคใหม่ รองรับการเติบโต
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center gap-1.5 text-primary text-3xs font-medium hover:gap-2.5 transition-all w-max mt-auto">
                        อ่านเพิ่มเติม
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="flex flex-col justify-between p-4 lg:px-6 lg:py-2 bg-white border-b sm:border-b-0 sm:border-r border-gray-100">
                    <div>
                        <div class="h-16 w-16 mx-auto mb-5 flex items-center justify-center">
                            <img src="/WEBPARK/frontend/public/assets/images/icon-4.png" alt="Online Marketing" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-dark font-bold text-5.5 text-center mb-3">
                            Online Marketing
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5 text-left min-h-card-sm">
                            วางกลยุทธ์และทำการตลาดออนไลน์ เพิ่มการเข้าถึงและยอดขาย
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center gap-1.5 text-primary  text-3xs font-medium hover:gap-2.5 transition-all w-max mt-auto">
                        อ่านเพิ่มเติม
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="flex flex-col justify-between p-4 lg:px-6 lg:py-2 bg-white">
                    <div>
                        <div class="h-16 w-16 mx-auto mb-5 flex items-center justify-center">
                            <img src="/WEBPARK/frontend/public/assets/images/icon-1.png" alt="Creative / Design" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-dark font-bold text-5.5 text-center mb-3">
                            Creative / Design
                        </h2>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5 text-left min-h-card-sm">
                            ออกแบบแบรนด์ให้โดดเด่นและน่าเชื่อถือ
                        </p>
                    </div>
                    <a href="#" class="inline-flex items-center gap-1.5 text-primary  text-3xs font-medium hover:gap-2.5 transition-all w-max mt-auto">
                        อ่านเพิ่มเติม
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
        <div class="flex items-end justify-between mb-8">
            <div class="max-w-3xl">
                <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
                    OUR PORTFOLIO
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-dark uppercase mb-4">
                    ตัวอย่างผลงานของเรา
                </h2>

                <p class="text-sm md:text-base leading-relaxed text-slate-600">
                    รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล<br>ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน
                </p>
            </div>
            <a href="<?= e(route_url('/portfolios')) ?>"
                class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:border-primary hover:text-primary hover:shadow-[0_4px_20px_rgba(59,130,246,0.25)] transition-all duration-300 group shadow-sm">
                ดูบริการของเรา
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>

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
                    REVIEW
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

                            <article class="review-card group shrink-0 bg-white rounded-[1.5rem] p-6 lg:p-8 shadow-[0_4px_25px_rgba(0,0,0,0.02)] border border-[#f3f4f6] flex flex-col justify-between hover:bg-primary hover:shadow-[0_4px_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 <?= $isVisible ?>" data-index="<?= $index ?>">

                                <div class="mb-4 text-slate-200 group-hover:text-white/50 transition-colors">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.8 19.2H4.8V13.2L8.4 6H12L8.4 13.2H10.8V19.2ZM20.4 19.2H14.4V13.2L18 6H21.6L18 13.2H20.4V19.2Z"/>
                                    </svg>
                                </div>

                                <p class="text-sm md:text-sm lg:text-sm leading-relaxed text-slate-600 group-hover:text-white transition-colors mb-8 flex-grow">
                                    <?= e($review['content'] ?? '') ?>
                                </p>

                                <div class="flex items-center gap-3 mt-auto">
                                    <img class="h-10 w-10 lg:h-12 lg:w-12 rounded-full object-cover bg-slate-100 shrink-0"
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
                    OUR PARTNERS
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
                    KNOWLEDGE
            </span>
        <div class="flex items-end justify-between mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold leading-none tracking-tighter text-[#000] uppercase">
                บทความและความรู้
            </h2>
            <a href="<?= e(route_url('/articles')) ?>"
                class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:border-primary hover:text-primary hover:shadow-[0_4px_20px_rgba(59,130,246,0.25)] transition-all duration-300 group shadow-sm">
                ดูทั้งหมด
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

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