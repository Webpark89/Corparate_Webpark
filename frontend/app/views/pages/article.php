<?php

declare(strict_types=1);

$articles = is_array($articles ?? null) ? $articles : [];
$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/computer-laptop-password-data-cyber-security-login-account-personal-3drender-illustration.jpg');
$ctaImage = asset_url('images/bg-cta.jpg');
?>

<section class="article-hero bg-gradient-to-b from-[#f5f6fb] to-white py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
            <div class="space-y-5">
                <p class="tracking-[0.5em] text-xs font-semibold text-[#1a2b6d] uppercase">บทความ</p>
                <h1 class="text-4xl font-extrabold text-[#0b1b42] sm:text-5xl lg:text-6xl">บทความ</h1>
                <h2 class="text-2xl font-bold leading-tight text-slate-700">
                    <span class="text-slate-700">ความรู้และอัปเดตจาก </span>
                    <span class="text-[#1a2b6d]">Webpark</span>
                </h2>
                <p class="text-base leading-relaxed text-slate-600 max-w-xl">
                    รวมบทความเทคโนโลยีและแนวทางองค์กรดิจิทัลที่ทีม Webpark อัปเดตอยู่เสมอ ทั้ง ERP, AI,
                    และโซลูชันภาคธุรกิจ ช่วยให้เปลี่ยนผ่านสู่ยุคดิจิทัลได้รวดเร็วและมั่นคง
                </p>
            </div>

            <div class="flex items-center justify-center">
                <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white/80 p-6 shadow-[0_35px_75px_rgba(15,23,42,0.18)]">
                    <img src="<?= e($heroImage) ?>"
                         alt="Illustration แสดงบทความและแนวคิดดิจิทัล"
                         class="h-72 w-72 object-contain">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white pb-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="relative flex-1">
                <div id="category-filters" class="article-filter-track flex gap-3 overflow-x-auto py-1 pr-4">
                    <button type="button"
                            data-filter="all"
                            class="article-filter-btn whitespace-nowrap rounded-full border px-5 py-2 text-sm font-medium transition-colors <?= $activeCategorySlug === 'all' ? 'active border-transparent bg-[#1a2b6d] text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-transparent hover:bg-[#1a2b6d] hover:text-white' ?>">
                        ทั้งหมด
                    </button>
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
                                class="article-filter-btn whitespace-nowrap rounded-full border px-5 py-2 text-sm font-medium transition-colors <?= $isActive ? 'active border-transparent bg-[#1a2b6d] text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-transparent hover:bg-[#1a2b6d] hover:text-white' ?>">
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
                <article class="article-card group flex h-full flex-col overflow-hidden rounded-[1.4rem] border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                         data-category="<?= e($categorySlug !== '' ? $categorySlug : 'all') ?>">
                    <a href="<?= e($detailUrl) ?>" class="relative block aspect-[16/9] w-full overflow-hidden">
                        <img src="<?= e($imageSrc) ?>" alt="<?= e($article['title'] ?? '') ?>" class="article-card__image h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                    <div class="flex h-full flex-col gap-3 px-5 py-6">
                        <span class="article-card__badge text-xs font-semibold text-[#1a2b6d]"><?= e($categoryName !== '' ? $categoryName : 'หมวดหมู่') ?></span>
                        <a href="<?= e($detailUrl) ?>" class="block">
                            <h3 class="article-card__title text-lg font-bold text-[#0b1b42] line-clamp-2">
                                <?= e($article['title'] ?? 'บทความ') ?>
                            </h3>
                        </a>
                        <?php if ($summary !== ''): ?>
                            <p class="article-card__description text-sm text-slate-500 line-clamp-2"><?= e($summary) ?></p>
                        <?php endif; ?>
                        <div class="mt-auto">
                            <a href="<?= e($detailUrl) ?>" class="article-card__cta inline-flex items-center gap-1 text-sm font-semibold text-[#1a2b6d] transition-all hover:gap-2">
                                อ่านเพิ่มเติม
                                <svg xmlns="http://www.w3.org/2000/svg" class="article-card__cta-icon h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 5l7 7-7 7M5 12h16"/>
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

<section class="article-cta-section px-4 py-16">
    <div class="mx-auto max-w-6xl overflow-hidden rounded-[2rem] p-8 text-white shadow-[0_25px_70px_rgba(15,23,42,0.35)]"
         style="background: linear-gradient(to right, #0b1b42, #12224a, #1a2b6d);">
        <div class="grid gap-6 lg:grid-cols-2 lg:items-center">
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
            <div class="relative">
                <img src="<?= e($ctaImage) ?>" alt="ภาพประกอบเมืองดิจิทัล" class="article-cta__image h-72 w-full object-cover object-right">
            </div>
        </div>
    </div>
</section>

<style>
.article-pagination__btn {
    display: flex;
    height: 2.25rem;
    width: 2.25rem;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
}
.article-pagination__btn:hover {
    background-color: #f1f5f9;
}
.article-pagination__btn--active,
.article-pagination__btn--active:hover {
    background-color: #1a2b6d;
    color: #ffffff;
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
    const PAGE_SIZE = 6;
    const DEFAULT_FILTER = 'all';

    let currentFilter = '<?= e($activeCategorySlug) ?>';
    let currentPage = 1;

    const ACTIVE_CLASSES = ['active', 'border-transparent', 'bg-[#1a2b6d]', 'text-white'];
    const INACTIVE_CLASSES = ['border-slate-200', 'bg-white', 'text-slate-700'];

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
        for (let i = 1; i <= totalPages; i += 1) {
            paginationEl.appendChild(createBtn(String(i), i, currentPage === i, false));
        }
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