<?php

declare(strict_types=1);

/**
 * Portfolio listing page view with category tabs and client-side grid switching.
 */

/**
 * Received from controller
 */
$filters = $filters ?? ['All'];
$portfolioRows = $portfolioRows ?? [];
$activeFilter = $activeFilter ?? 'All';
$temporaryImage = asset_url('images/story.png');

/**
 * Truncate text to fit ~3 lines
 */
$truncateText = static function (string $text, int $length = 140): string {
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    if (mb_strlen($text, 'UTF-8') <= $length) {
        return $text;
    }
    return mb_strimwidth($text, 0, $length, '...', 'UTF-8');
};

// Prepare portfolio items grouped by filter for client-side switching
$portfolioTabs = [];
foreach ($filters as $filter) {
    $list = array_values(array_filter($portfolioRows, static function ($p) use ($filter) {
        return (string)($p['category'] ?? '') === (string)$filter;
    }));

    $portfolioTabs[$filter] = array_map(static function ($item) use ($temporaryImage, $truncateText) {
        $titleText = !empty($item['meta_title']) ? $item['meta_title'] : ($item['title'] ?? 'Untitled Project');
        $descText = !empty($item['meta_description']) ? $item['meta_description'] : ($item['description'] ?? $item['summary'] ?? 'รายละเอียดโครงการ...');

        return [
            'id' => (int)($item['id'] ?? 0),
            'url' => ($item['id'] ?? 0) > 0 ? route_url('/portfolio', ['id' => (int)$item['id']]) : '',
            'title' => (string)$titleText,
            'industry' => (string)($item['client_name'] ?? ''),
            'description' => $truncateText((string)$descText),
            'image' => $temporaryImage,
            'category' => (string)($item['category'] ?? ''),
        ];
    }, $list);
}

// All group
$portfolioTabs['All'] = array_map(static function ($item) use ($temporaryImage, $truncateText) {
    $titleText = !empty($item['meta_title']) ? $item['meta_title'] : ($item['title'] ?? 'Untitled Project');
    $descText = !empty($item['meta_description']) ? $item['meta_description'] : ($item['description'] ?? $item['summary'] ?? 'รายละเอียดโครงการ...');

    return [
        'id' => (int)($item['id'] ?? 0),
        'url' => ($item['id'] ?? 0) > 0 ? route_url('/portfolio', ['id' => (int)$item['id']]) : '',
        'title' => (string)$titleText,
        'industry' => (string)($item['client_name'] ?? ''),
        'description' => $truncateText((string)$descText),
        'image' => $temporaryImage,
        'category' => (string)($item['category'] ?? ''),
    ];
}, $portfolioRows ?: []);

$portfolioTabsJson = json_encode($portfolioTabs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
$initialProjects = $portfolioTabs[$activeFilter] ?? $portfolioTabs['All'] ?? [];
?>

<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <p class="text-sm font-semibold uppercase tracking-[0.32em] text-sky-700">Selected Work</p>
        <h1 class="mt-4 text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">ผลงานที่วัดผลได้จริงในทุกอุตสาหกรรม</h1>
        <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-slate-600 sm:text-lg">คัดสรรเคสที่สะท้อนแนวทางของเรา ตั้งแต่ปัญหาธุรกิจไปจนถึงผลลัพธ์เชิงตัวเลข</p>
    </div>
</section>

<section class="bg-slate-50 py-16 sm:py-20">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="relative mx-auto flex max-w-4xl flex-wrap justify-center gap-3 rounded-full border border-slate-200 bg-white/90 p-2 shadow-sm backdrop-blur" id="portfolioSwitcher">
            <span class="pointer-events-none absolute inset-y-1 left-0 w-0 rounded-full bg-sky-600/20 transition-all duration-300 ease-out" id="portfolioIndicator"></span>

            <a href="?category=All"
                class="relative z-10 rounded-full px-4 py-2 text-sm font-semibold transition-colors duration-200 <?= $activeFilter === 'All' ? 'text-sky-700' : 'text-slate-600 hover:text-sky-700' ?>"
                data-tab="All"
                data-active="<?= $activeFilter === 'All' ? 'true' : 'false' ?>">
                ทั้งหมด
            </a>

            <?php foreach ($filters as $filter): ?>
                <a href="?category=<?= urlencode($filter) ?>"
                    class="relative z-10 rounded-full px-4 py-2 text-sm font-semibold transition-colors duration-200 <?= $activeFilter === $filter ? 'text-sky-700' : 'text-slate-600 hover:text-sky-700' ?>"
                    data-tab="<?= e($filter) ?>"
                    data-active="<?= $activeFilter === $filter ? 'true' : 'false' ?>">
                    <?= e($filter) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const wrapper = document.getElementById('portfolioSwitcher');
                const indicator = document.getElementById('portfolioIndicator');
                const links = wrapper.querySelectorAll('[data-tab]');
                const grid = document.getElementById('portfolioGrid');
                const portfolioTabs = JSON.parse('<?= $portfolioTabsJson ?>');

                const updateIndicator = (activeLink) => {
                    if (!indicator || !activeLink) return;
                    indicator.style.width = `${activeLink.offsetWidth}px`;
                    indicator.style.transform = `translateX(${activeLink.offsetLeft}px)`;
                };

                const syncLinks = (activeLink) => {
                    links.forEach(l => {
                        l.classList.remove('text-sky-700');
                        l.classList.add('text-slate-600');
                    });
                    if (!activeLink) return;
                    activeLink.classList.remove('text-slate-600');
                    activeLink.classList.add('text-sky-700');
                    updateIndicator(activeLink);
                };

                const renderProjects = (category) => {
                    const list = portfolioTabs[category] || [];
                    grid.innerHTML = '';
                    if (list.length === 0) {
                        grid.innerHTML = `<div class="col-span-3 text-center py-12 text-slate-400 text-sm">ไม่พบข้อมูลผลงานในระบบ</div>`;
                        return;
                    }

                    list.forEach(item => {
                        const article = document.createElement('article');
                        article.className = 'group relative overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl h-[480px] flex flex-col cursor-pointer';
                        if (item.url) {
                            article.dataset.href = item.url;
                            article.tabIndex = 0;
                        }

                        const media = document.createElement('div');
                        media.className = 'relative aspect-[4/3] w-full overflow-hidden bg-slate-100 shrink-0';
                        if (item.image) {
                            const img = document.createElement('img');
                            img.src = item.image;
                            img.alt = item.title || '';
                            img.className = 'h-full w-full object-cover transition duration-500 group-hover:scale-105';
                            media.appendChild(img);
                        } else {
                            const fallback = document.createElement('div');
                            fallback.className = 'h-full w-full bg-gradient-to-br from-sky-600 to-indigo-700';
                            media.appendChild(fallback);
                        }

                        const overlay = document.createElement('div');
                        overlay.className = 'absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-900/20 to-transparent';
                        media.appendChild(overlay);

                        const badge = document.createElement('div');
                        badge.className = 'absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm backdrop-blur';
                        badge.textContent = item.category || 'General';
                        media.appendChild(badge);

                        const body = document.createElement('div');
                        body.className = 'flex flex-col flex-grow p-6 justify-between overflow-hidden';

                        const textContainer = document.createElement('div');
                        textContainer.className = 'overflow-hidden';

                        const industry = document.createElement('span');
                        industry.className = 'text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 block mb-2';
                        industry.textContent = item.industry || 'Project';

                        const title = document.createElement('h3');
                        title.className = 'text-lg font-bold leading-tight text-slate-900 line-clamp-2 mb-2 group-hover:text-[#00b0d8] transition-colors duration-200';
                        title.textContent = item.title || '';

                        const desc = document.createElement('p');
                        desc.className = 'text-sm leading-7 text-slate-600 line-clamp-3';
                        desc.textContent = item.description || '';

                        textContainer.appendChild(industry);
                        textContainer.appendChild(title);
                        textContainer.appendChild(desc);

                        const footer = document.createElement('div');
                        footer.className = 'mt-auto flex items-center justify-between gap-4 border-t border-slate-100 pt-4 bg-white';
                        const placeholder = document.createElement('span');
                        placeholder.className = 'text-sm font-medium text-slate-500';
                        placeholder.textContent = '';
                        const readMore = document.createElement('span');
                        readMore.className = 'inline-flex items-center gap-1 text-sm font-semibold text-sky-700 transition group-hover:text-sky-800';
                        readMore.innerHTML = 'อ่านต่อ<span class="text-lg leading-none">›</span>';
                        footer.append(placeholder, readMore);

                        body.appendChild(textContainer);
                        body.appendChild(footer);

                        article.appendChild(media);
                        article.appendChild(body);
                        grid.appendChild(article);
                    });
                };

                const active = wrapper.querySelector('[data-active="true"]');
                if (active) {
                    updateIndicator(active);
                    syncLinks(active);
                }

                links.forEach(link => {
                    link.addEventListener('mouseenter', () => updateIndicator(link));
                    link.addEventListener('focus', () => updateIndicator(link));
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        const category = link.dataset.tab || 'All';
                        links.forEach(l => l.dataset.active = l === link ? 'true' : 'false');
                        syncLinks(link);
                        renderProjects(category);

                        const params = new URLSearchParams(window.location.search);
                        params.set('category', category);
                        window.history.pushState({}, '', `${window.location.pathname}?${params}`);
                    });
                });

                wrapper.addEventListener('mouseleave', () => {
                    const current = wrapper.querySelector('[data-active="true"]');
                    if (current) updateIndicator(current);
                });

                window.addEventListener('resize', () => {
                    const current = wrapper.querySelector('[data-active="true"]');
                    if (current) updateIndicator(current);
                });

                if (grid) {
                    grid.addEventListener('click', e => {
                        const card = e.target.closest('[data-href]');
                        if (card && !e.target.closest('a')) window.location.href = card.dataset.href;
                    });
                }
            });
        </script>

        <div id="portfolioGrid" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3 mt-12">
            <?php if (!empty($initialProjects)): ?>
                <?php foreach ($initialProjects as $project): ?>
                    <article class="group relative overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1 hover:shadow-xl h-[480px] flex flex-col cursor-pointer"
                        <?php if ($project['url'] !== ''): ?>data-href="<?= e($project['url']) ?>" tabindex="0" <?php endif; ?>>

                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 shrink-0">
                            <?php if ($project['image'] !== ''): ?>
                                <img src="<?= e($project['image']) ?>" alt="<?= e($project['title']) ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <?php else: ?>
                                <div class="h-full w-full bg-gradient-to-br from-sky-600 to-indigo-700"></div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-900/20 to-transparent"></div>
                            <div class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm backdrop-blur">
                                <?= e($project['category'] !== '' ? $project['category'] : 'General') ?>
                            </div>
                        </div>

                        <div class="flex flex-col flex-grow p-6 justify-between overflow-hidden">
                            <div class="overflow-hidden">
                                <span class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 block mb-2">
                                    <?= e($project['industry'] !== '' ? $project['industry'] : 'Project') ?>
                                </span>

                                <h3 class="text-lg font-bold leading-tight text-slate-900 line-clamp-2 mb-2 group-hover:text-[#00b0d8] transition-colors duration-200">
                                    <?= e($project['title']) ?>
                                </h3>

                                <p class="mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                                    <?= e($project['description']) ?>
                                </p>
                            </div>

                            <div class="mt-auto flex items-center justify-between gap-4 border-t border-slate-100 pt-4 bg-white">
                                <span class="text-sm font-medium text-slate-500">
                                </span>
                                <span class="inline-flex items-center gap-1 text-sm font-semibold text-sky-700 transition group-hover:text-sky-800">
                                    อ่านต่อ<span class="text-lg leading-none">›</span>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-slate-400 text-sm">
                    ไม่พบข้อมูลผลงานในระบบ
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
