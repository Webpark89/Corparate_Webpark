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
            class="w-full h-full object-cover object-center opacity-100 mix-blend-screen">
            
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6">
                    <ol class="inline-flex items-center space-x-2 text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                หน้าแรก
                            </a>
                        </li>
                        
                        <li>
                            <span class="text-slate-400 mx-1">/</span>
                        </li>
                        
                        <li aria-current="page">
                            <span class="text-slate-400">บทความ</span>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">
                        บทความ
                    </span><br>

                    <span class="text-2xl md:text-3xl lg:text-5xl font-medium bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 py-3" style="animation-delay: -3s;">
                        ความรู้และอัปเดตจาก Webpark
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    รวบรวมบทความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ <br>
                    ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน<br>
                    ที่ช่วยพัฒนาองค์กรให้เติบโตได้อย่างยั่งยืน
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
        <div id="article-grid" class="article-grid grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($articles as $article):
                $detailUrl = route_url('/article', ['id' => (int) ($article['id'] ?? 0)]);
                $categoryName = trim((string) ($article['category_name'] ?? ''));
                $categorySlug = trim((string) ($article['category_slug'] ?? ''));
                $summary = trim(strip_tags((string) ($article['summary'] ?? '')));
                $imageSrc = resolve_article_image_url((string) ($article['image_path'] ?? ''), $fallbackImage);
                ?>
                <article class="article-card group flex h-full flex-col overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]"
                         data-category="<?= e($categorySlug !== '' ? $categorySlug : 'all') ?>">
                    
                    <!-- ส่วนรูปภาพ ปรับเป็น 4/3 ให้รูปดูเต็มขึ้น -->
                    <a href="<?= e($detailUrl) ?>" class="relative block aspect-[4/3] w-full overflow-hidden">
                        <img src="<?= e($imageSrc) ?>" alt="<?= e($article['title'] ?? '') ?>" class="article-card__image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                    
                    <!-- ส่วนเนื้อหา -->
                    <div class="flex h-full flex-col p-6">
                        
                        <!-- Badge หมวดหมู่ (พื้นหลังฟ้า ขอบมนแคปซูล) -->
                        <div class="mb-4">
                            <span class="inline-block rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-semibold tracking-wide text-blue-700">
                                <?= e($categoryName !== '' ? $categoryName : 'หมวดหมู่') ?>
                            </span>
                        </div>
                        
                        <!-- หัวข้อบทความ -->
                        <a href="<?= e($detailUrl) ?>" class="block mb-3">
                            <h3 class="article-card__title text-lg font-bold text-[#1a2b6d] leading-snug line-clamp-2">
                                <?= e($article['title'] ?? 'บทความ') ?>
                            </h3>
                        </a>
                        
                        <!-- สรุปเนื้อหา (แสดง 3 บรรทัด) -->
                        <?php if ($summary !== ''): ?>
                            <p class="article-card__description text-sm leading-relaxed text-slate-500 line-clamp-3">
                                <?= e($summary) ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- ปุ่มอ่านเพิ่มเติม (ดันลงล่างสุด และชิดขวา) -->
                        <div class="mt-auto pt-6 flex" style="justify-content: flex-end;">
                            <a href="<?= e($detailUrl) ?>" class="article-card__cta inline-flex items-center gap-1.5 text-sm font-semibold text-blue-500 transition-all hover:gap-2 hover:text-blue-700">
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
    height: 3rem; /* เพิ่มความสูง */
    width: 3rem;  /* เพิ่มความกว้าง */
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem; /* ปรับให้เป็นมุมโค้งมน */
    font-size: 1rem;
    font-weight: 600;
    color: #2563eb; /* สีตัวเลขปกติ */
    background: transparent;
    border: 1px solid #dbeafe; /* เพิ่มเส้นขอบสีฟ้าอ่อน */
    cursor: pointer;
    transition: all 0.2s;
}
.article-pagination__btn:hover {
    background-color: #eff6ff;
}
/* สถานะ Active (เลข 1 ที่ถูกเลือก) */
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
.article-filter-track::-webkit-scrollbar {
    display: none;
}
.article-filter-track {
    -ms-overflow-style: none;
    scrollbar-width: none;
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
    let currentPage = 1;

    // แก้ไขจุดนี้: ซิงค์คลาสให้ตรงกับดีไซน์ใหม่ เพื่อไม่ให้ JS ไปเขียนทับสีเดิม
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

    const slugMatchesFilter = (cardCategory, filter) => {
        if (filter === DEFAULT_FILTER) {
            return true;
        }
        return cardCategory === filter;
    };

    const getFilteredCards = () => articleCards.filter(card => {
        const category = card.dataset.category || DEFAULT_FILTER;
        return slugMatchesFilter(category, currentFilter);
    });

    const renderPagination = (totalPages) => {
        paginationEl.innerHTML = '';
        if (totalPages <= 1) {
            return;
        }

        const createBtn = (label, target, active = false, disabled = false) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = label;
            btn.disabled = disabled;
            btn.className = 'article-pagination__btn';
            if (active) {
                btn.classList.add('article-pagination__btn--active');
            }
            if (disabled) {
                btn.setAttribute('aria-disabled', 'true');
            }
            btn.addEventListener('click', () => {
                if (disabled) {
                    return;
                }
                currentPage = target;
                render();
                articleGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            return btn;
        };

        paginationEl.appendChild(createBtn('‹', currentPage - 1, false, currentPage === 1));

        // ปุ่มเลขหน้า 1 ถึง 5 (หรือตามจำนวนหน้าจริง)
        for (let i = 1; i <= totalPages; i += 1) {
            // จำกัดให้แสดงแค่ 5 หน้าตามที่คุณต้องการ
            if (i <= 5) { 
                paginationEl.appendChild(createBtn(String(i), i, currentPage === i, false));
            }
        }

        // ปุ่มถัดไป
        paginationEl.appendChild(createBtn('›', currentPage + 1, false, currentPage === totalPages));
    };

    const render = () => {
        const filtered = getFilteredCards();
        const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        articleCards.forEach(card => card.classList.add('hidden'));

        const start = (currentPage - 1) * PAGE_SIZE;
        const visible = filtered.slice(start, start + PAGE_SIZE);
        visible.forEach(card => card.classList.remove('hidden'));

        noResults.classList.toggle('hidden', filtered.length !== 0);
        renderPagination(totalPages);
    };

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter || DEFAULT_FILTER;
            currentFilter = filter;
            currentPage = 1;
            setActiveButton(filter);
            render();
            btn.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
        });
    });

    if (scrollLeftBtn && scrollRightBtn && filterTrack) {
        scrollLeftBtn.addEventListener('click', () => filterTrack.scrollBy({ left: -200, behavior: 'smooth' }));
        scrollRightBtn.addEventListener('click', () => filterTrack.scrollBy({ left: 200, behavior: 'smooth' }));
    }

    setActiveButton(currentFilter);
    render();
});
</script>