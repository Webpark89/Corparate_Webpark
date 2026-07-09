<?php

declare(strict_types=1);

$articles = is_array($articles ?? null) ? $articles : [];
$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-6.png');
$ctaImage = asset_url('images/bg-cta.jpg');
?>

<style>
    /* 1. แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง (Entrance) */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0; /* ซ่อนไว้ก่อนเริ่ม */
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 2. แอนิเมชันสำหรับตัวอักษรสีเหลือบ (Gradient Flow) */
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }

    /* คลาสหน่วงเวลา เพื่อให้เนื้อหาไล่ลำดับกันขึ้นมา */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover object-[100%_center] md:object-center opacity-100 mix-blend-screen">
            
        <!-- ปรับ Gradient สีขาวให้จางลง เพื่อให้เห็นพื้นหลังจอโน้ตบุ๊กบนมือถือชัดเจนตามความต้องการ -->
        <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/50 to-transparent md:from-white md:via-white/70 md:to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white/50 to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-3xl lg:max-w-none">
                <nav aria-label="Breadcrumb" class="hidden md:block animate-fade-up delay-100 mb-6">
                    <ol class="inline-flex items-center space-x-2 text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                หน้าแรก
                            </a>
                        </li>
                        
                        <li>
<<<<<<< Updated upstream
                            <span class="text-slate-400" style="margin: 0 4px;">/</span>
=======
                            <span class="text-slate-400 mx-2 md:mx-4">/</span>
>>>>>>> Stashed changes
                        </li>
                        
                        <li aria-current="page">
                            <span class="text-slate-400">บทความ</span>
                        </li>
                    </ol>
                </nav>
                
                <style>
                    .hero-title-text {
                        font-size: 2.75rem;
                        line-height: 1.25;
                    }
                    .hero-desc-text {
                        font-size: 17px;
                    }
                    @media (min-width: 768px) {
                        .hero-title-text { font-size: 4.5rem; line-height: 1.2; }
                        .hero-desc-text { font-size: 26px; }
                    }
                    @media (min-width: 1024px) {
                        .hero-title-text { font-size: 5.5rem; line-height: 1.2; }
                    }
                    @media (min-width: 1280px) {
                        .hero-title-text { font-size: 6.5rem; line-height: 1.2; }
                    }
                </style>
                <h1 class="animate-fade-up delay-200 tracking-tight mb-2">
                    <span class="hero-title-text font-black bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-2 whitespace-nowrap">
                        บทความความรู้
                    </span><br>
                    <span class="hero-title-text font-black bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block -mt-4 md:-mt-8 whitespace-nowrap" style="animation-delay: -3s;">
                        และอัพเดต
                    </span>
                </h1>

                <p class="hero-desc-text animate-fade-up delay-300 mt-4 md:mt-8 leading-relaxed w-full mb-10 font-medium" style="color: #054FC5;">
                    <!-- Mobile View (Exactly matching the reference image) -->
                    <span class="block md:hidden">
                        รวบรวมบทความความรู้ เทคโนโลยี นวัตกรรม<br>
                        และแนวทางการทำธุรกิจ ครอบคลุม ERP<br>
                        ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน<br>
                        ที่ช่วยพัฒนาองค์กรให้เติบโตอย่างยั่งยืน
                    </span>
                    <!-- Desktop View (Exactly 3 lines) -->
                    <span class="hidden md:block whitespace-nowrap">
                        รวบรวมบทความความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ <br>
                        ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน<br>
                        ที่ช่วยพัฒนาองค์กรให้เติบโตอย่างยั่งยืน
                    </span>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white" style="padding-top: 1.5rem; padding-bottom: 2.5rem;">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="relative flex-1">
                <div id="category-filters" class="article-filter-track flex gap-3 overflow-x-auto py-1 pr-4">
                    
                    <!-- ปุ่ม: ทั้งหมด -->
                    <button type="button"
                            data-filter="all"
                            class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors <?= $activeCategorySlug === 'all' ? 'border-transparent bg-blue-600 text-white' : 'border-blue-200 bg-white text-[#1a2b6d] hover:bg-blue-600 hover:text-white hover:border-transparent' ?>">
                        ทั้งหมด
                    </button>

                    <!-- ปุ่ม: หมวดหมู่ตาม Loop -->
                    <?php foreach ($categories as $category):
                        $slug = trim((string) ($category['slug'] ?? ''));
                        $name = $category['name'] ?? '';
                        if ($slug === '' || $name === '') {
                            continue;
                        }
                        $isActive = $activeCategorySlug === $slug;
                    ?>
                        <button type="button"
                                data-filter="<?= e($slug) ?>"
                                class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors <?= $isActive ? 'border-transparent bg-blue-600 text-white' : 'border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white' ?>">
                            <?= e($name) ?>
                        </button>
                    <?php endforeach; ?>

                </div>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                <button id="filter-scroll-left"
                        type="button"
                        class="article-filter-arrow flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition-colors hover:bg-slate-50"
                        aria-label="เลื่อนหมวดหมู่ไปทางซ้าย">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="filter-scroll-right"
                        type="button"
                        class="article-filter-arrow flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition-colors hover:bg-slate-50"
                        aria-label="เลื่อนหมวดหมู่ไปทางขวา">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="bg-white pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <style>
            .article-grid-container {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .article-card-slide {
                width: 100%;
            }
            @media (min-width: 1024px) {
                .article-grid-container {
                    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                }
            }
        </style>
        <div id="article-grid" class="article-grid article-grid-container gap-6 hide-scroll scroll-smooth" style="-ms-overflow-style: none; scrollbar-width: none;">
            <?php foreach ($articles as $article):
                $detailUrl = route_url('/article', ['id' => (int) ($article['id'] ?? 0)]);
                $categoryName = trim((string) ($article['category_name'] ?? ''));
                $categorySlug = trim((string) ($article['category_slug'] ?? ''));
                $summary = trim(strip_tags((string) ($article['summary'] ?? '')));
                $imageSrc = resolve_article_image_url((string) ($article['image_path'] ?? ''), $fallbackImage);
                ?>
                <article class="article-card article-card-slide snap-start group flex flex-col overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]"
                         data-category="<?= e($categorySlug !== '' ? $categorySlug : 'all') ?>">
                    
                    <!-- ส่วนรูปภาพ ปรับเป็น 4/3 ให้รูปดูเต็มขึ้น -->
                    <a href="<?= e($detailUrl) ?>" class="relative block aspect-[4/3] w-full overflow-hidden">
                        <img src="<?= e($imageSrc) ?>" alt="<?= e($article['title'] ?? '') ?>" class="article-card__image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                    
                    <!-- ส่วนเนื้อหา -->
                    <div class="flex h-full flex-col p-6">
                        
                        <!-- Badge หมวดหมู่ (พื้นหลังฟ้า ขอบมนแคปซูล) -->
                        <div class="mb-4">
                            <span class="inline-block rounded-full bg-blue-50 px-4 py-2 lg:px-3 lg:py-1.5 text-sm lg:text-xs font-semibold tracking-wide text-blue-700">
                                <?= e($categoryName !== '' ? $categoryName : 'หมวดหมู่') ?>
                            </span>
                        </div>
                        
                        <!-- หัวข้อบทความ -->
                        <a href="<?= e($detailUrl) ?>" class="block mb-3">
                            <h3 class="article-card__title text-2xl lg:text-lg font-bold text-[#1a2b6d] leading-snug line-clamp-2">
                                <?= e($article['title'] ?? 'บทความ') ?>
                            </h3>
                        </a>
                        
                        <!-- สรุปเนื้อหา (แสดง 3 บรรทัด) -->
                        <?php if ($summary !== ''): ?>
                            <p class="article-card__description text-lg lg:text-sm leading-relaxed text-slate-500 line-clamp-3">
                                <?= e($summary) ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- ปุ่มอ่านเพิ่มเติม (ดันลงล่างสุด และชิดขวา) -->
                        <div class="mt-auto pt-6 flex" style="justify-content: flex-end;">
                            <a href="<?= e($detailUrl) ?>" class="article-card__cta inline-flex items-center gap-1.5 text-lg lg:text-sm font-semibold text-blue-500 transition-all hover:gap-2 hover:text-blue-700">
                                อ่านเพิ่มเติม
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </article>
            <?php endforeach; ?>
        </div>

        <div id="no-results" class="article-no-results hidden py-14 text-center text-slate-600">
            <div class="mx-auto mb-4 h-16 w-16 rounded-full border border-slate-200 bg-slate-50 flex items-center justify-center">
                <svg class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5L18.5 6M15 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b6d] mb-2">ไม่พบบทความในหมวดหมู่นี้</h3>
            <p class="text-sm text-slate-500">ลองเลือกหมวดหมู่อื่นหรือลิงก์ "ทั้งหมด" เพื่อดูบทความทั้งหมด</p>
        </div>

        <nav id="pagination" class="article-pagination mt-8 flex items-center justify-center gap-2" aria-label="Article pagination"></nav>
    </div>
</section>

<!-- อันเก่าโค้ดสำหรับดูเป็นแนวทาง -->

<!-- 
<section class="bg-white px-4 py-16">
    
    <div class="mx-auto max-w-7xl overflow-hidden rounded-[2rem] p-8 lg:p-12 text-white shadow-[0_25px_70px_rgba(15,23,42,0.35)] relative"
         style="background-image: url('<?= e($ctaImage) ?>'); background-size: cover; background-position: center;">
        
        
        <div class="absolute inset-0 bg-[#0b1b42]/80 z-0"></div>

        
        <div class="relative z-10 grid gap-6 lg:grid-cols-2 lg:items-center">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-[0.4em] text-blue-200">พร้อมดูแลองค์กรของคุณ</p>
                <h2 class="text-3xl font-extrabold leading-tight lg:text-4xl">
                    พร้อมช่วยองค์กรของคุณ<br>
                    ก้าวสู่ดิจิทัลอย่างเต็มรูปแบบ
                </h2>
                <p class="text-sm leading-7 text-white/80">ทีมวิศวกร Webpark พร้อมให้คำปรึกษา วิเคราะห์ และออกแบบโซลูชันที่เหมาะกับธุรกิจของคุณในทุกมิติ</p>
                <a href="<?= e(route_url('/contact')) ?>" class="inline-flex w-fit items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-[#0b1b42] transition hover:bg-slate-100">
                    ติดต่อปรึกษาฟรี
                    <span class="text-base leading-none">→</span>
                </a>
            </div>
        </div>
    </div>
</section> 
-->

<!-- โค้ดใหม่สำหรับปรับปรุงแล้ว -->

<!-- <section class="bg-white px-4 py-16">
    <div class="mx-auto overflow-hidden rounded-[2rem] px-8 py-10 lg:px-12 lg:py-8 text-white shadow-[0_25px_70px_rgba(15,23,42,0.35)] relative flex items-center min-h-[400px]"
         style="max-width: 1216px; width: 100%; background-image: url('<?= e($ctaImage) ?>'); background-size: cover; background-position: right center;">
        
        <div class="absolute inset-0 z-0" style="background: linear-gradient(to right, #002868 0%, rgba(0, 51, 128, 0.95) 45%, rgba(0, 51, 128, 0) 100%);"></div>

        <div class="relative z-10 grid gap-6 lg:grid-cols-2 lg:items-center w-full">
            <div class="space-y-5">
                <p class="text-sm font-medium text-blue-200 lg:text-base">
                    ปรึกษาผู้เชี่ยวชาญ Webpark
                </p>
                
                <h2 class="text-3xl font-bold leading-[1.4] lg:text-5xl lg:leading-[1.3]">
                    พร้อมช่วยองค์กรของคุณ<br>
                    ก้าวสู่ดิจิทัลอย่างเต็มรูปแบบ
                </h2>
                
                <p class="text-base leading-relaxed text-white/90 lg:text-lg">
                    เราพร้อมให้คำปรึกษาและออกแบบโซลูชันที่เหมาะสมกับธุรกิจของคุณ
                </p>
                
                <div class="pt-2">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex w-fit items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-[#0b1b42] transition hover:bg-slate-100">
                        ติดต่อปรึกษาฟรี
                        <span class="text-base leading-none">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> -->

<style>
.article-pagination__btn {
    display: flex;
    height: 3rem;
    width: 3rem;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    font-size: 1rem;
    font-weight: 600;
    color: #2563eb;
    background: transparent;
    border: 1px solid #dbeafe;
    cursor: pointer;
    transition: all 0.2s;
}
.article-pagination__btn:hover {
    background-color: #eff6ff;
}
.article-pagination__btn--active,
.article-pagination__btn--active:hover {
    background-color: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.article-pagination__btn[disabled] {
    opacity: 0.4;
    cursor: not-allowed;
}
.article-pagination__dot {
    height: 8px;
    width: 8px;
    border-radius: 9999px;
    background-color: #022862;
    opacity: 0.2;
    cursor: pointer;
    transition: all 0.3s ease;
}
.article-pagination__dot--active {
    width: 32px;
    opacity: 1;
}
.article-filter-track::-webkit-scrollbar,
.hide-scroll::-webkit-scrollbar {
    display: none;
}
.article-filter-track,
.hide-scroll {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
@media (max-width: 1023px) {
    .article-pagination__btn {
        height: 42px;
        width: 42px;
        font-size: 1rem;
        border-radius: 0.5rem;
    }
    .article-pagination__btn:not(:first-child):not(:last-child) {
        border-color: transparent;
        background-color: transparent;
    }
    .article-pagination__btn--active,
    .article-pagination__btn--active:hover {
        background-color: transparent !important;
        color: #1a2b6d !important;
        border-color: transparent !important;
        font-weight: 900 !important;
        transform: scale(1.1);
    }
    .article-pagination__btn:first-child {
        margin-right: 0.5rem;
    }
    .article-pagination__btn:last-child {
        margin-left: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = Array.from(document.querySelectorAll('.article-filter-btn'));
    const filterTrack = document.getElementById('category-filters');
    const scrollLeftBtn = document.getElementById('filter-scroll-left');
    const scrollRightBtn = document.getElementById('filter-scroll-right');
    const articleGrid = document.getElementById('article-grid');
    const articleCards = Array.from(document.querySelectorAll('.article-card'));
    const paginationEl = document.getElementById('pagination');
    const noResults = document.getElementById('no-results');
    
    const PAGE_SIZE = 3;
    const DEFAULT_FILTER = 'all';

    let currentFilter = '<?= e($activeCategorySlug) ?>';
    let filteredCards = [];
    let currentPage = 1;
    let isDesktopMode = window.innerWidth >= 1024;

    const ACTIVE_CLASSES = ['border-transparent', 'bg-blue-600', 'text-white'];
    const INACTIVE_CLASSES = ['border-blue-200', 'bg-white', 'text-[#1a2b6d]'];

    const setActiveButton = (slug) => {
        filterBtns.forEach(btn => {
            const value = btn.dataset.filter || DEFAULT_FILTER;
            const isActive = value === slug;
            btn.classList.remove(...ACTIVE_CLASSES, ...INACTIVE_CLASSES);
            btn.classList.add(...(isActive ? ACTIVE_CLASSES : INACTIVE_CLASSES));
        });
    };

    const getFilteredCards = () => articleCards.filter(card => {
        const category = card.dataset.category || DEFAULT_FILTER;
        return currentFilter === DEFAULT_FILTER || category === currentFilter;
    });

    const updateDots = () => {
        if (isDesktopMode) return;
        const dots = Array.from(paginationEl.querySelectorAll('.article-pagination__dot'));
        if (dots.length === 0) return;
        
        const scrollLeft = articleGrid.scrollLeft;
        const width = articleGrid.offsetWidth;
        const index = Math.round(scrollLeft / width);
        
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('article-pagination__dot--active');
            } else {
                dot.classList.remove('article-pagination__dot--active');
            }
        });
    };

    const renderPagination = () => {
        paginationEl.innerHTML = '';
        if (filteredCards.length <= 1) return;

        const totalPages = Math.max(1, Math.ceil(filteredCards.length / PAGE_SIZE));
        if (totalPages <= 1) return;

        const createBtn = (label, target, active = false, disabled = false) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = label;
            btn.disabled = disabled;
            btn.className = 'article-pagination__btn';
            if (active) btn.classList.add('article-pagination__btn--active');
            if (disabled) btn.setAttribute('aria-disabled', 'true');
            btn.addEventListener('click', () => {
                if (disabled) return;
                currentPage = target;
                render();
                articleGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            return btn;
        };

        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'flex items-center overflow-hidden bg-white shadow-sm mx-auto';
        paginationContainer.style.borderRadius = '14px';
        paginationContainer.style.border = '1px solid #cbd5e1';
        
        const prevBtn = createBtn('', currentPage - 1, false, currentPage === 1);
        prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 24px; width: 24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>`;
        prevBtn.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        prevBtn.style.height = '68px';
        prevBtn.style.width = '72px';
        prevBtn.style.color = '#1e40af';
        prevBtn.style.borderRight = '1px solid #cbd5e1';
        if (currentPage === 1) prevBtn.style.opacity = '0.3';
        paginationContainer.appendChild(prevBtn);

        const infoText = document.createElement('span');
        infoText.className = 'flex items-center justify-center font-medium tracking-wide';
        infoText.style.height = '68px';
        infoText.style.padding = '0 40px';
        infoText.style.minWidth = '160px';
        infoText.style.color = '#1e40af';
        infoText.style.fontSize = '26px';
        infoText.textContent = `${currentPage} of ${totalPages}`;
        paginationContainer.appendChild(infoText);

        const nextBtn = createBtn('', currentPage + 1, false, currentPage === totalPages);
        nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 24px; width: 24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>`;
        nextBtn.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        nextBtn.style.height = '68px';
        nextBtn.style.width = '72px';
        nextBtn.style.color = '#1e40af';
        nextBtn.style.borderLeft = '1px solid #cbd5e1';
        if (currentPage === totalPages) nextBtn.style.opacity = '0.3';
        paginationContainer.appendChild(nextBtn);

        paginationEl.appendChild(paginationContainer);
    };

    const render = () => {
        filteredCards = getFilteredCards();
        articleCards.forEach(card => card.classList.add('hidden'));

        const totalPages = Math.max(1, Math.ceil(filteredCards.length / PAGE_SIZE));
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        
        const start = (currentPage - 1) * PAGE_SIZE;
        const visible = filteredCards.slice(start, start + PAGE_SIZE);
        visible.forEach(card => card.classList.remove('hidden'));

        noResults.classList.toggle('hidden', filteredCards.length > 0);
        setTimeout(renderPagination, 100); 
    };

    articleGrid.addEventListener('scroll', () => {
        if (!isDesktopMode) requestAnimationFrame(updateDots);
    });

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            currentFilter = btn.dataset.filter || DEFAULT_FILTER;
            currentPage = 1;
            setActiveButton(currentFilter);
            render();
            btn.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
        });
    });

    if (scrollLeftBtn && scrollRightBtn && filterTrack) {
        scrollLeftBtn.addEventListener('click', () => filterTrack.scrollBy({ left: -200, behavior: 'smooth' }));
        scrollRightBtn.addEventListener('click', () => filterTrack.scrollBy({ left: 200, behavior: 'smooth' }));
    }
    
    window.addEventListener('resize', () => {
        const currentIsDesktop = window.innerWidth >= 1024;
        if (currentIsDesktop !== isDesktopMode) {
            isDesktopMode = currentIsDesktop;
            currentPage = 1;
            render();
        } else if (!isDesktopMode) {
            renderPagination();
            updateDots();
        }
    });

    setActiveButton(currentFilter);
    render();
});
</script>