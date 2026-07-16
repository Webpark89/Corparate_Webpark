<?php

declare(strict_types=1);

$articles = is_array($articles ?? null) ? $articles : [];
$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-7.png');
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
    /* บังคับตำแหน่งรูปภาพและ Overlay ด้วย CSS โดยตรง เพื่อเลี่ยงปัญหา Tailwind ไม่คอมไพล์ */
    .hero-bg-img {
        /* ปรับ object-position เป็น 85% เพื่อดึงโน้ตบุ๊กกลับเข้ามาในจอ และ 0% คือชิดขอบบน */
        object-position: 85% 0% !important;
    }
    .hero-overlay-mobile {
        /* เฉดสีขาวเฉพาะฝั่งซ้ายและด้านบนที่ตัวหนังสืออยู่ ปล่อยฝั่งขวาให้โปร่งใสเพื่อให้เห็นรูปภาพ */
        background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.9) 45%, rgba(255, 255, 255, 0) 80%) !important;
    }
    .hero-overlay-gradient {
        background: transparent !important; /* ยกเลิก gradient ซ้ำซ้อนบนมือถือ */
    }
    @media (min-width: 768px) {
        .hero-bg-img {
            object-position: right center !important;
        }
        .hero-overlay-mobile {
            background: transparent !important;
        }
        .hero-overlay-gradient {
            background: linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.9) 60%, rgba(255, 255, 255, 0.1) 100%) !important;
        }
    }
</style>

<!-- นำขอบโค้งและ margin ออก เพื่อให้ชิดขอบจอด้านบนและด้านข้างแบบ Edge-to-Edge -->
<section class="relative overflow-hidden font-sans bg-white border-none">
    <div class="absolute inset-0 z-0">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover md:object-contain hero-bg-img opacity-100">
            
        <div class="absolute inset-0 hero-overlay-mobile"></div>
        <div class="absolute inset-0 hero-overlay-gradient"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white/50 to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-3xl lg:max-w-none">
                <nav aria-label="Breadcrumb" class="hidden md:block animate-fade-up delay-100 mb-6">
                    <ol class="inline-flex items-center text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('common.nav_home')) ?>
                            </a>
                        </li>
                        <li>
                            <span class="text-slate-400" style="margin: 0 4px;">/</span>
                        </li>
                        
                        <li aria-current="page">
                            <span class="text-slate-400"><?= e(t('article_list.page_title')) ?></span>
                        </li>
                    </ol>
                </nav>
                
                <style>
                    .hero-title-text {
                        font-size: 2.25rem;
                        line-height: 1.25;
                    }
                    .hero-desc-text {
                        font-size: 22px !important;
                        line-height: 1.65;
                    }
                    @media (min-width: 768px) {
                        .hero-title-text { font-size: 3.5rem; line-height: 1.2; }
                        .hero-desc-text { font-size: 24px !important; line-height: 1.7; }
                    }
                    @media (min-width: 1024px) {
                        .hero-title-text { font-size: 4.5rem; line-height: 1.2; }
                    }
                    @media (min-width: 1280px) {
                        .hero-title-text { font-size: 5rem; line-height: 1.2; }
                    }
                </style>
                <h1 class="animate-fade-up delay-200 tracking-tight mb-2">
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-1 md:pb-2 whitespace-nowrap">
                        <?= e(getCurrentLang() === 'th' ? 'บทความความรู้' : 'Knowledge Articles') ?>
                    </span><br>
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block -mt-2 md:-mt-8 whitespace-nowrap" style="animation-delay: -3s;">
                        <?= e(getCurrentLang() === 'th' ? 'และอัพเดต' : '& Updates') ?>
                    </span>
                </h1>

                <?php
                if (getCurrentLang() === 'th') {
                    $mobile_desc = "รวบรวมบทความรู้ เทคโนโลยี นวัตกรรม และแนวทาง<br>การทำธุรกิจ ครอบคลุม ERP ระบบธุรกิจดิจิทัล การ<br>ตลาดออนไลน์ AI และโซลูชัน ที่ช่วยพัฒนาองค์กรให้<br>เติบโตได้อย่างยั่งยืน";
                } else {
                    $mobile_desc = "Knowledge articles, tech, and innovation covering ERP systems, digital business, online marketing, AI, and solutions to sustainably grow your organization.";
                }
                ?>
                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-2xl mb-10 font-medium">
                    <span class="block md:hidden leading-[1.75]">
                        <?= $mobile_desc ?>
                    </span>
                    <span class="hidden md:block leading-relaxed">
                        <?php if (getCurrentLang() === 'th'): ?>
                            <?= e(t('common.articles_knowledge_summary')) ?> <br>
                            ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน<br>
                            <?= e(t('common.articles_growth_summary')) ?>
                        <?php else: ?>
                            A collection of articles on technology, innovation, and business<br>
                            strategy covering ERP systems, digital business, online marketing,<br>
                            AI, and solutions that help organizations grow sustainably.
                        <?php endif; ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white" style="padding-top: 1.5rem; padding-bottom: 2.5rem;">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center relative w-full">
            <div class="hidden items-center md:flex shrink-0 pr-4">
                <button id="filter-scroll-left"
                        type="button"
                        class="article-filter-arrow flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition-all duration-300 hover:bg-slate-50 opacity-0 pointer-events-none"
                        aria-label="<?= e(t('article_list.category_scroll_left')) ?>">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            <div class="relative flex-1 overflow-hidden">
                <div id="category-filters" class="article-filter-track flex gap-3 overflow-x-auto py-1 hide-scroll scroll-smooth" style="-ms-overflow-style: none; scrollbar-width: none;">
                    
                    <!-- ปุ่ม: ทั้งหมด -->
                    <button type="button"
                            data-filter="all"
                            class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors <?= $activeCategorySlug === 'all' ? 'border-transparent bg-blue-600 text-white' : 'border-blue-200 bg-white text-[#1a2b6d] hover:bg-blue-600 hover:text-white hover:border-transparent' ?>">
                        <?= e(t('common.cta_view_all')) ?>
                    </button>

                    <!-- ปุ่ม: หมวดหมู่ตาม Loop -->
                    <?php foreach ($categories as $category):
                        $slug = trim((string) ($category['slug'] ?? ''));
                        // You could use translated category names if they are dynamically loaded with current language
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
                    
                    <!-- Mock categories for testing scroll -->
                    <button type="button" data-filter="mock-1" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">Cloud Solutions</button>
                    <button type="button" data-filter="mock-2" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">Cyber Security</button>
                    <button type="button" data-filter="mock-3" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">UX/UI Design</button>
                    <button type="button" data-filter="mock-4" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">Machine Learning</button>
                    <button type="button" data-filter="mock-5" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">Business Intelligence</button>
                    <button type="button" data-filter="mock-6" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">Mobile Application</button>
                    <button type="button" data-filter="mock-7" class="article-filter-btn whitespace-nowrap rounded-md border px-5 py-2 text-sm font-medium transition-colors border-blue-200 bg-white text-[#1a2b6d] hover:border-transparent hover:bg-blue-600 hover:text-white">E-Commerce</button>

                </div>
            </div>

            <div class="hidden items-center md:flex shrink-0 pl-4">
                <button id="filter-scroll-right"
                        type="button"
                        class="article-filter-arrow flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition-all duration-300 hover:bg-slate-50"
                        aria-label="<?= e(t('article_list.category_scroll_right')) ?>">
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
            <?php 
            foreach ($articles as $article):
                $detailUrl = route_url('/article', ['id' => (int) ($article['id'] ?? 0)]);
                $categoryName = trim((string) ($article['category_name'] ?? ''));
                $categorySlug = trim((string) ($article['category_slug'] ?? ''));
                $summary = trim(strip_tags((string) ($article['summary'] ?? '')));
                $imageSrc = resolve_article_image_url((string) ($article['image_path'] ?? ''), $fallbackImage);
                $articleTitle = (string) ($article['title'] ?? t('article_list.page_title'));
                
                $linkToUse = (trim($articleTitle) === 'ทำไม SEO ถึงสำคัญสำหรับธุรกิจในปีนี้') 
                             ? '/Corparate_Webpark/article-detail-mockup' 
                             : $detailUrl;
                ?>
                <article class="article-card article-card-slide snap-start group h-fit flex flex-col overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]"
                         data-category="<?= e($categorySlug !== '' ? $categorySlug : 'all') ?>">
                    
                    <!-- ส่วนรูปภาพ ปรับสัดส่วนให้สมดุลกับส่วนเนื้อหาที่เล็กลง -->
                    <a href="<?= e($linkToUse) ?>" class="relative block aspect-[16/9] w-full overflow-hidden">
                        <img src="<?= e($imageSrc) ?>" alt="<?= e($articleTitle) ?>" class="article-card__image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                    
                    <!-- ส่วนเนื้อหา -->
                    <div class="flex flex-col p-4">
                        
                        <!-- Badge หมวดหมู่ (พื้นหลังฟ้า ขอบมนแคปซูล) -->
                        <div class="mb-3">
                            <span class="inline-block rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold tracking-wide text-blue-700">
                                <?= e($categoryName !== '' ? $categoryName : (getCurrentLang() === 'th' ? 'หมวดหมู่' : 'Category')) ?>
                            </span>
                        </div>
                        
                        <!-- หัวข้อบทความ -->
                        <a href="<?= e($linkToUse) ?>" class="block mb-2">
                            <h3 class="article-card__title text-xl lg:text-lg font-bold text-[#1a2b6d] leading-snug" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 3.25rem;">
                                <?= e($articleTitle) ?>
                            </h3>
                        </a>
                        
                        <!-- สรุปเนื้อหา (แสดง 3 บรรทัด) -->
                        <p class="article-card__description text-slate-500" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; font-size: 0.875rem; line-height: 1.5rem; height: 3rem; white-space: normal; word-break: break-word;">
                            <?= e($summary) ?>
                        </p>
                        
                        <!-- ปุ่มอ่านเพิ่มเติม (ดันลงล่างสุด และชิดขวา) -->
                        <div class="flex mt-1" style="justify-content: flex-end;">
                            <a href="<?= e($linkToUse) ?>" class="article-card__cta inline-flex items-center gap-1.5 text-lg lg:text-sm font-semibold text-blue-500 transition-all hover:gap-2 hover:text-blue-700">
                                <?= e(t('common.cta_read_more')) ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </article>
            <?php endforeach; ?>
        </div>

        <div id="no-results" class="article-no-results hidden py-14 text-center text-slate-600 flex flex-col items-center justify-center">
            <img src="<?= e(asset_url('images/Empty.gif')) ?>" alt="No results" class="w-64 h-auto max-w-full mb-4 object-contain">
            <h3 class="text-lg font-bold text-[#1a2b6d] mb-2"><?= e(t('article_list.empty_state_title')) ?></h3>
            <p class="text-sm text-slate-500"><?= e(t('article_list.empty_state_desc')) ?></p>
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
                <p class="text-xs uppercase tracking-[0.4em] text-blue-200"><?= e(t('article_list.cta_banner_ready_title')) ?></p>
                <h2 class="text-3xl font-extrabold leading-tight lg:text-4xl">
                    <?= e(t('article_list.cta_banner_ready_title2')) ?><br>
                    <?= e(t('article_list.cta_banner_digital_shift')) ?>
                </h2>
                <p class="text-sm leading-7 text-white/80"><?= e(t('article_list.cta_banner_desc')) ?></p>
                <a href="<?= e(route_url('/contact')) ?>" class="inline-flex w-fit items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-[#0b1b42] transition hover:bg-slate-100">
                    <?= e(t('article_list.cta_free_contact')) ?>
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
                    <?= e(t('article_list.cta_consult_webpark_expert')) ?>
                </p>
                
                <h2 class="text-3xl font-bold leading-[1.4] lg:text-5xl lg:leading-[1.3]">
                    <?= e(t('article_list.cta_banner_ready_title2')) ?><br>
                    <?= e(t('article_list.cta_banner_digital_shift')) ?>
                </h2>
                
                <p class="text-base leading-relaxed text-white/90 lg:text-lg">
                    <?= e(t('article_list.cta_banner_desc2')) ?>
                </p>
                
                <div class="pt-2">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex w-fit items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-[#0b1b42] transition hover:bg-slate-100">
                        <?= e(t('article_list.cta_free_contact')) ?>
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

    const scrollToGridTop = () => {
        const categoryFilters = document.getElementById('category-filters');
        if (categoryFilters) {
            const yOffset = -90; // Offset to clear the sticky header (height 64px) and add spacing
            const y = categoryFilters.getBoundingClientRect().top + (window.scrollY || window.pageYOffset) + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        } else {
            articleGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

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
                scrollToGridTop();
            });
            return btn;
        };

        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'flex items-center overflow-hidden bg-white shadow-sm mx-auto';
        paginationContainer.style.borderRadius = '8px';
        paginationContainer.style.border = '1px solid #cbd5e1';
        
        const prevBtn = createBtn('', currentPage - 1, false, currentPage === 1);
        prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 18px; width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>`;
        prevBtn.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        prevBtn.style.height = '44px';
        prevBtn.style.width = '48px';
        prevBtn.style.color = '#1e40af';
        prevBtn.style.borderRight = '1px solid #cbd5e1';
        if (currentPage === 1) prevBtn.style.opacity = '0.3';
        paginationContainer.appendChild(prevBtn);

        const infoText = document.createElement('span');
        infoText.className = 'flex items-center justify-center font-medium tracking-wide cursor-pointer hover:bg-slate-50 transition-colors select-none';
        infoText.style.height = '44px';
        infoText.style.padding = '0 20px';
        infoText.style.minWidth = '100px';
        infoText.style.color = '#1e40af';
        infoText.style.fontSize = '16px';
        infoText.title = 'คลิกเพื่อเลือกหน้า / Click to input page';
        infoText.textContent = `${currentPage} of ${totalPages}`;

        infoText.addEventListener('click', (e) => {
            if (e.target.tagName === 'INPUT') return;
            
            infoText.innerHTML = '';
            
            const input = document.createElement('input');
            input.type = 'number';
            input.min = '1';
            input.max = totalPages;
            input.value = currentPage;
            input.className = 'w-12 h-7 text-center font-bold text-[#1e40af] border border-blue-200 rounded focus:outline-none focus:border-blue-500 bg-slate-50 p-0 text-sm';
            input.addEventListener('click', (evt) => evt.stopPropagation());
            
            const label = document.createElement('span');
            label.className = 'ml-1.5 text-slate-500 font-medium text-sm';
            label.textContent = `of ${totalPages}`;
            
            infoText.appendChild(input);
            infoText.appendChild(label);
            
            input.focus();
            input.select();
            
            let submitted = false;
            const finish = () => {
                if (submitted) return;
                submitted = true;
                const val = parseInt(input.value, 10);
                if (!isNaN(val) && val >= 1 && val <= totalPages) {
                    if (val !== currentPage) {
                        currentPage = val;
                        render();
                        scrollToGridTop();
                    } else {
                        infoText.textContent = `${currentPage} of ${totalPages}`;
                    }
                } else {
                    infoText.textContent = `${currentPage} of ${totalPages}`;
                }
            };
            
            input.addEventListener('keydown', (evt) => {
                if (evt.key === 'Enter') {
                    evt.preventDefault();
                    finish();
                } else if (evt.key === 'Escape') {
                    evt.preventDefault();
                    submitted = true;
                    infoText.textContent = `${currentPage} of ${totalPages}`;
                }
            });
            
            input.addEventListener('blur', () => {
                setTimeout(finish, 100);
            });
        });

        paginationContainer.appendChild(infoText);

        const nextBtn = createBtn('', currentPage + 1, false, currentPage === totalPages);
        nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 18px; width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>`;
        nextBtn.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        nextBtn.style.height = '44px';
        nextBtn.style.width = '48px';
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
        const updateScrollButtons = () => {
            const scrollLeft = filterTrack.scrollLeft;
            const scrollWidth = filterTrack.scrollWidth;
            const clientWidth = filterTrack.clientWidth;
            
            // At the leftmost edge (or if not scrolled at all)
            if (scrollLeft <= 5) {
                scrollLeftBtn.classList.add('opacity-0', 'pointer-events-none');
                scrollRightBtn.classList.remove('opacity-0', 'pointer-events-none');
            } 
            // At the rightmost edge
            else if (Math.ceil(scrollLeft + clientWidth) >= scrollWidth - 5) {
                scrollLeftBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollRightBtn.classList.add('opacity-0', 'pointer-events-none');
            } 
            // In the middle
            else {
                scrollLeftBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollRightBtn.classList.remove('opacity-0', 'pointer-events-none');
            }
        };

        filterTrack.addEventListener('scroll', updateScrollButtons);
        updateScrollButtons();
        setTimeout(updateScrollButtons, 150);
        setTimeout(updateScrollButtons, 500);
        window.addEventListener('resize', updateScrollButtons);

        scrollLeftBtn.addEventListener('click', () => {
            filterTrack.scrollLeft -= 300;
            setTimeout(updateScrollButtons, 350);
        });
        scrollRightBtn.addEventListener('click', () => {
            filterTrack.scrollLeft += 300;
            setTimeout(updateScrollButtons, 350);
        });
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