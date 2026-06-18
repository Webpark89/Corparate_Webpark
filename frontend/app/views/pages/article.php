<?php

declare(strict_types=1);

// Fetch articles from database
$article = new Article();
$articles = $article->getPublished();

// Extract unique categories from articles
$categories = array_values(array_unique(array_map(static function ($item) {
    return (string) ($item['category'] ?? '');
}, array_filter($articles, static function ($item) {
    return !empty($item['category'] ?? '');
})), SORT_STRING));
sort($categories);

$activeCategory = $_GET['category'] ?? 'All';
$temporaryImage = asset_url('images/story.png');
$fallbackImage = asset_url('images/story.png');

/**
 * Get article image with file existence check
 */
$getArticleImage = static function (string $imagePath) use ($temporaryImage, $fallbackImage): string {
    if (empty($imagePath)) {
        return $temporaryImage;
    }
    $fullImageUrl = asset_url($imagePath);
    $parsed = parse_url($fullImageUrl, PHP_URL_PATH);
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $parsed;
    return file_exists($filePath) ? $fullImageUrl : $fallbackImage;
};

/**
 * Truncate text to fit ~3 lines
 */
$truncateText = static function (string $text, int $length = 130): string {
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }
    return mb_strimwidth($text, 0, $length, '...', 'UTF-8');
};

$formatThaiDate = static function ($createdAtDate): string {
    $date = $createdAtDate ?? '';
    $ts = $date === '' ? false : strtotime($date);
    if ($ts === false || $ts <= 0) return '';

    $months = [1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'];
    $day = date('j', $ts);
    $month = $months[(int) date('n', $ts)] ?? '';
    $year = date('Y', $ts);
    return "$day $month $year";
};

// จัดกลุ่มบทความแยกแท็บ
$articleTabs = [];
foreach ($categories as $category) {
    $list = array_values(array_filter($articles, static function ($a) use ($category) {
        return (string)($a['category'] ?? '') === (string)$category;
    }));

    $articleTabs[$category] = array_map(static function ($item) use ($getArticleImage, $truncateText, $formatThaiDate) {
        $titleText = !empty($item['meta_title']) ? $item['meta_title'] : ($item['title'] ?? 'Untitled Article');
        $descText = !empty($item['meta_description']) ? $item['meta_description'] : ($item['content'] ?? 'รายละเอียดบทความ...');

        return [
            'id' => (int)($item['id'] ?? 0),
            'url' => ($item['id'] ?? 0) > 0 ? route_url('/article', ['id' => (int)$item['id']]) : '',
            'title' => (string)$titleText,
            'author' => (string)($item['author'] ?? ''),
            'description' => $truncateText((string)$descText),
            'image' => $getArticleImage($item['image_path'] ?? ''),
            'category' => (string)($item['category'] ?? ''),
            'date' => $formatThaiDate($item['created_at'] ?? ''),
        ];
    }, $list);
}

// ชุดข้อมูลแท็บรวม "ทั้งหมด (All)"
$articleTabs['All'] = array_map(static function ($item) use ($getArticleImage, $truncateText, $formatThaiDate) {
    $titleText = !empty($item['meta_title']) ? $item['meta_title'] : ($item['title'] ?? 'Untitled Article');
    $descText = !empty($item['meta_description']) ? $item['meta_description'] : ($item['content'] ?? 'รายละเอียดบทความ...');

    return [
        'id' => (int)($item['id'] ?? 0),
        'url' => ($item['id'] ?? 0) > 0 ? route_url('/article', ['id' => (int)$item['id']]) : '',
        'title' => (string)$titleText,
        'author' => (string)($item['author'] ?? ''),
        'description' => $truncateText((string)$descText),
        'image' => $getArticleImage($item['image_path'] ?? ''),
        'category' => (string)($item['category'] ?? ''),
        'date' => $formatThaiDate($item['created_at'] ?? ''),
    ];
}, $articles ?: []);

$articleTabsJson = json_encode($articleTabs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
$initialArticles = $articleTabs[$activeCategory] ?? $articleTabs['All'] ?? [];
?>
<style>
    /* คงไว้แค่แอนิเมชันตอนโหลดหน้าสไลด์ขึ้น */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-6.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-3xl">
                <div class="animate-fade-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">ARTICLE</span>
                </div>

                <h1 class="animate-fade-up delay-200 text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block py-2">บทความและความรู้</span><br>
    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block py-2">จาก WEBPARK</span>
</h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    รวมบทความและความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน <br>ที่ช่วยพัฒนาองค์กรให้เติบโตอย่างยั่งยืน
                </p>

                <div class="animate-fade-up delay-400 flex flex-wrap items-center gap-4">
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาผู้เชี่ยวชาญ
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#about" class="group inline-flex items-center gap-4 transition-all hover:-translate-y-0.5">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all duration-300 group-hover:bg-slate-50 group-hover:scale-105 group-hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold transition-colors duration-300 group-hover:text-primary">
                            ดูวิดีโอแนะนำ
                        </span>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>

<section class="bg-white py-12 sm:py-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <div class="mb-6 flex flex-wrap justify-center items-center gap-2.5 max-w-5xl mx-auto" id="articleSwitcher">
            <a href="?category=All"
                class="rounded-3xl px-5 py-2 text-xs md:text-sm font-bold tracking-wide border transition-all duration-200 <?= $activeCategory === 'All' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' ?>"                data-tab="All"
                data-active="<?= $activeCategory === 'All' ? 'true' : 'false' ?>">
                ทั้งหมด
            </a>


            <?php foreach ($categories as $category): ?>
                <a href="?category=<?= urlencode($category) ?>"
                    class="rounded-3xl px-5 py-2 text-xs md:text-sm font-bold tracking-wide border transition-all duration-200 <?= $activeCategory === $category ? 'bg-primary text-white border-primary shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' ?>"                    data-tab="<?= e($category) ?>"
                    data-active="<?= $activeCategory === $category ? 'true' : 'false' ?>">
                    <?= e($category) ?>
                </a>
            <?php endforeach; ?>

        </div>


        <div id="articleGrid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-14">
            <?php if (!empty($initialArticles)): ?>
                <?php foreach ($initialArticles as $post): ?>
                    <article class="group bg-white rounded-3xl border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-[490px] cursor-pointer"
                        <?php if ($post['url'] !== ''): ?>data-href="<?= e($post['url']) ?>"<?php endif; ?>>

                        <div class="w-full aspect-[16/10] bg-slate-50 overflow-hidden shrink-0 relative">
                            <img src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>

                        <div class="flex flex-col flex-grow p-6 justify-between">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-3xl bg-blue-50 text-primary text-[10px] font-bold uppercase tracking-wider mb-3">
                                    <?= e($post['category'] !== '' ? $post['category'] : 'General') ?>
                                </span>

                                <h3 class="text-base font-bold text-dark leading-snug line-clamp-2 mb-2 group-hover:text-primary transition-colors duration-200">
                                    <?= e($post['title']) ?>
                                </h3>

                                <p class="text-slate-500 text-[13px] leading-relaxed line-clamp-3">
                                    <?= e($post['description']) ?>
                                </p>
                            </div>

                            <div class="flex items-center justify-end pt-2 mt-auto">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:underline">
                                    อ่านเพิ่มเติม 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-2xl">
                    ไม่พบข้อมูลบทความในระบบ
                </div>
            <?php endif; ?>
        </div>
</section>
<script id="articleTabsData" type="application/json"><?= htmlspecialchars($articleTabsJson, ENT_QUOTES, 'UTF-8') ?></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('articleSwitcher');
        if (!wrapper) return;

        const links = wrapper.querySelectorAll('[data-tab]');
        const grid = document.getElementById('articleGrid');
        const articleTabs = JSON.parse('<?= $articleTabsJson ?>');
        let currentCategory = '<?= $activeCategory ?>';
        let currentPage = 1;
        const itemsPerPage = 9;

        const createLoadMoreButton = () => {
            const btn = document.createElement('button');
            btn.id = 'loadMoreBtn';
            btn.className = 'col-span-3 mt-10 mx-auto px-8 py-3 bg-primary text-white text-sm font-bold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5';
            btn.textContent = 'โหลดเพิ่มเติม';

            btn.addEventListener('click', () => {
                currentPage++;
                loadMoreItems(currentCategory, currentPage);
            });
            return btn;
        };

        const createArticleCard = (item) => {
            const article = document.createElement('article');
            article.className = 'group bg-white rounded-[1.8rem] border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-[490px] cursor-pointer';
            if (item.url) {
                article.dataset.href = item.url;
            }

            const media = document.createElement('div');
            media.className = 'w-full aspect-[16/10] bg-slate-50 overflow-hidden shrink-0 relative';

            const img = document.createElement('img');
            img.src = item.image;
            img.alt = item.title || '';
            img.className = 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105';
            media.appendChild(img);

            const body = document.createElement('div');
            body.className = 'flex flex-col flex-grow p-6 justify-between';

            const topArea = document.createElement('div');

            const badge = document.createElement('span');
            badge.className = 'inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-primary text-[10px] font-bold uppercase tracking-wider mb-3';
            badge.textContent = item.category || 'General';

            const title = document.createElement('h3');
            title.className = 'text-base font-bold text-dark leading-snug line-clamp-2 mb-2 group-hover:text-primary transition-colors duration-200';
            title.textContent = item.title || '';

            const desc = document.createElement('p');
            desc.className = 'text-slate-500 text-[13px] leading-relaxed line-clamp-3';
            desc.textContent = item.description || '';

            topArea.appendChild(badge);
            topArea.appendChild(title);
            topArea.appendChild(desc);

            const footer = document.createElement('div');
            footer.className = 'flex items-center justify-end pt-2 mt-auto';

            const readMore = document.createElement('span');
            readMore.className = 'inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:underline';
            readMore.innerHTML = 'อ่านเพิ่มเติม <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>';

            footer.appendChild(readMore);
            body.appendChild(topArea);
            body.appendChild(footer);

            article.appendChild(media);
            article.appendChild(body);
            return article;
        };

        const loadMoreItems = (category, page) => {
            if (!grid) return;

            const list = articleTabs[category] || [];
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedList = list.slice(startIndex, endIndex);

            const existingBtn = document.getElementById('loadMoreBtn');
            if (existingBtn) {
                existingBtn.remove();
            }

            if (paginatedList.length > 0) {
                paginatedList.forEach((item) => {
                    grid.appendChild(createArticleCard(item));
                });
            }

            if (endIndex < list.length) {
                grid.appendChild(createLoadMoreButton());
            }
        };

        const renderCategory = (category) => {
            if (!grid) return;

            const list = articleTabs[category] || [];
            currentPage = 1;
            grid.innerHTML = '';

            if (list.length === 0) {
                grid.innerHTML = '<div class="col-span-3 text-center py-12 text-slate-400 text-sm border-2 border-dashed border-slate-100 rounded-2xl">ไม่พบข้อมูลบทความในระบบ</div>';
                return;
            }

            const firstPageItems = list.slice(0, itemsPerPage);
            firstPageItems.forEach((item) => {
                grid.appendChild(createArticleCard(item));
            });

            if (list.length > itemsPerPage) {
                grid.appendChild(createLoadMoreButton());
            }
        };

        const syncActiveState = (activeLink) => {
            links.forEach((link) => {
                link.className = "rounded-3xl px-5 py-2 text-xs md:text-sm font-bold tracking-wide border transition-all duration-200 bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:-translate-y-0.5";
            });

            if (!activeLink) return;
            activeLink.className = "rounded-3xl px-5 py-2 text-xs md:text-sm font-bold tracking-wide border transition-all duration-200 bg-primary text-white border-primary shadow-sm hover:-translate-y-0.5";
        };

        links.forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const category = link.getAttribute('data-tab') || 'All';
                currentCategory = category;

                syncActiveState(link);
                renderCategory(category);

                try {
                    const params = new URLSearchParams(window.location.search);
                    params.set('category', category);
                    window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
                    
                    // ลบโค้ดส่วน targetSection.scrollIntoView ออกแล้ว
                    
                } catch (error) {}
            });
        });

        if (grid) {
            grid.addEventListener('click', (e) => {
                const card = e.target.closest('[data-href]');
                if (!card) return;
                const href = card.dataset.href || '';
                if (href) window.location.href = href;
            });
        }
    });
</script>