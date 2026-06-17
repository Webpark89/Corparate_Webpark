<?php

declare(strict_types=1);

$currentPage = $currentPage ?? 'home';
$navItems = [
    ['path' => '/', 'label' => 'หน้าแรก', 'page' => 'home'], 
    ['path' => '/about', 'label' => 'เกี่ยวกับเรา', 'page' => 'about'],
    ['path' => '/services', 'label' => 'บริการของเรา', 'page' => 'services'],
    ['path' => '/erp', 'label' => 'ระบบ ERP', 'page' => 'erp'],
    ['path' => '/article', 'label' => 'บทความ', 'page' => 'articles'],
    ['path' => '/contact', 'label' => 'ติดต่อเรา', 'page' => 'contact'],
];

?>

<header class="sticky top-0 z-[1000] border-b border-slate-200/80 bg-white/95 backdrop-blur">

    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between gap-4 px-4 sm:px-5 lg:px-6">

        <a class="flex items-center gap-3" href="<?= e(route_url('/')) ?>">
            <img
                class="h-9 w-9 object-contain"
                src="<?= e(asset_url('images/logo.png')) ?>"
                alt="WEBPARK logo">

            <span class="text-[15px] font-bold tracking-tight text-dark">
                WEBPARK
            </span>
        </a>

        <nav class="hidden items-center gap-5 lg:flex" aria-label="Primary Navigation">
            <?php foreach ($navItems as $item): ?>
                <div class="flex items-center gap-5">
                    <a
                        class="group relative py-2 text-[15px] transition-colors hover:text-primary <?= $currentPage === $item['page'] ? 'text-primary font-semibold' : 'text-slate-700 font-medium' ?>"
                        href="<?= e(route_url($item['path'])) ?>"
                        <?= $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                        <?= e($item['label']) ?>
                        
                        <span class="absolute bottom-1 left-0 h-[2px] w-full origin-left bg-primary transition-transform duration-300 ease-out <?= $currentPage === $item['page'] ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?>"></span>
                    </a>

                    <?php if ($item !== end($navItems)): ?>
                        <span class="text-[6px] text-slate-300">●</span>                    
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>

        <div class="flex items-center gap-2">
            <a
                class="inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-[13px] font-bold text-white transition hover:-translate-y-0.5 hover:opacity-90 shadow-md"
                href="<?= e(route_url('/contact')) ?>">
                ขอคำปรึกษา
            </a>

            <button
                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-transparent text-lg font-bold lg:hidden"
                id="mobileMenuToggle"
                aria-label="Toggle navigation"
                aria-expanded="false"
                aria-controls="mobileMenu">
                ☰
            </button>
        </div>

    </div>

    <div class="hidden border-t border-slate-200 bg-white px-4 py-4 lg:hidden" id="mobileMenu">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-2">
            <?php foreach ($navItems as $item): ?>
                <a
                    class="rounded-xl px-4 py-3 transition hover:bg-slate-50 <?= $currentPage === $item['page'] ? 'bg-blue-50/50 text-primary font-semibold' : 'text-slate-700' ?>"
                    href="<?= e(route_url($item['path'])) ?>">
                    <?= e($item['label']) ?>
                </a>
            <?php endforeach; ?>

            <a
                class="mt-2 inline-flex items-center justify-center rounded-xl bg-primary px-4 py-3 font-bold text-white shadow-md"
                href="<?= e(route_url('/contact')) ?>">
                ขอคำปรึกษา
            </a>
        </div>
    </div>
    
    <script>
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        toggleBtn?.addEventListener('click', () => {
            const isOpening = mobileMenu.classList.contains('hidden');

            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');

            toggleBtn.setAttribute('aria-expanded', isOpening ? 'true' : 'false');
            toggleBtn.innerHTML = isOpening ? '✕' : '☰';
        });
    </script>

</header>