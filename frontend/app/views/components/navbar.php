<?php

declare(strict_types=1);

/**
 * Site header navigation component.
 */

$currentPage = $currentPage ?? 'home';
$navItems = [
    ['path' => '/', 'label_th' => 'หน้าแรก',    'label_en' => 'Home',     'page' => 'home'],
    ['path' => '/about',    'label_th' => 'เกี่ยวกับเรา',  'label_en' => 'About Us',  'page' => 'about'],
    ['path' => '/services', 'label_th' => 'บริการของเรา',  'label_en' => 'Services',  'page' => 'services'],
    ['path' => '/erp',      'label_th' => 'ระบบ ERP',       'label_en' => 'ERP System','page' => 'erp'],
    ['path' => '/article',  'label_th' => 'บทความ',         'label_en' => 'Articles',  'page' => 'articles'],
    ['path' => '/contact',  'label_th' => 'ติดต่อเรา',      'label_en' => 'Contact',   'page' => 'contact'],
];

?>

<header class="sticky top-0 z-[1000] border-b border-slate-200/80 bg-white/95 backdrop-blur">

    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between gap-4 px-4 sm:px-5 lg:px-6">

        <a class="flex items-center gap-3" href="<?= e(route_url('/')) ?>">
            <img
                class="h-9 w-9 object-contain"
                src="<?= e(asset_url('images/logo.png')) ?>"
                alt="WEBPARK logo">
        </a>

        <nav class="hidden items-center gap-5 lg:flex" aria-label="Primary Navigation">
            <?php foreach ($navItems as $item): ?>
                <div class="flex items-center gap-5">
                    <a
                        class="group relative py-2 text-[15px] transition-colors hover:text-primary <?= $currentPage === $item['page'] ? 'text-primary font-semibold' : 'text-[#022862] font-medium' ?>"
                        href="<?= e(route_url($item['path'])) ?>"
                        data-th="<?= e($item['label_th']) ?>"
                        data-en="<?= e($item['label_en']) ?>"
                        <?= $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                        <?= e($item['label_th']) ?>
                        <span class="absolute bottom-1 left-0 h-[2px] w-full origin-left bg-primary transition-transform duration-300 ease-out <?= $currentPage === $item['page'] ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?>"></span>
                    </a>

                    <?php if ($item !== end($navItems)): ?>
                        <span class="text-[6px] text-[#022862]">●</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>

        <div class="flex items-center gap-3">

            <!-- Language Switcher -->
            <div class="hidden items-center lg:flex">
                <button
                    id="langSwitcher"
                    class="flex items-center gap-1 text-sm font-semibold text-[#022862] hover:text-primary transition-colors select-none"
                    aria-label="Switch language">
                    <span id="langTH" class="transition-opacity">TH</span>
                    <span class="opacity-40 font-normal">|</span>
                    <span id="langEN" class="transition-opacity opacity-40">EN</span>
                </button>
            </div>

            <a
                class="inline-flex items-center justify-center px-7 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5"
                data-th="ขอคำปรึกษา"
                data-en="Get Advice"
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
                    href="<?= e(route_url($item['path'])) ?>"
                    data-th="<?= e($item['label_th']) ?>"
                    data-en="<?= e($item['label_en']) ?>">
                    <?= e($item['label_th']) ?>
                </a>
            <?php endforeach; ?>

            <!-- Mobile Language Switcher -->
            <div class="flex items-center gap-2 px-4 py-2">
                <button
                    id="langSwitcherMobile"
                    class="flex items-center gap-1 text-sm font-semibold text-[#022862] hover:text-primary transition-colors select-none"
                    aria-label="Switch language">
                    <span id="langTH_m" class="transition-opacity">TH</span>
                    <span class="opacity-40 font-normal">|</span>
                    <span id="langEN_m" class="transition-opacity opacity-40">EN</span>
                </button>
            </div>

            <a
                class="inline-flex items-center justify-center px-7 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5"
                data-th="ขอคำปรึกษา"
                data-en="Get Advice"
                href="<?= e(route_url('/contact')) ?>">
                ขอคำปรึกษา
            </a>
        </div>
    </div>

    <script>
        // ─── Mobile menu toggle ───────────────────────────────────────────────
        const toggleBtn  = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        toggleBtn?.addEventListener('click', () => {
            const isOpening = mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
            toggleBtn.setAttribute('aria-expanded', isOpening ? 'true' : 'false');
            toggleBtn.innerHTML = isOpening ? '✕' : '☰';
        });

        // ─── Language switcher ────────────────────────────────────────────────
        const LANG_KEY = 'wp_lang'; // localStorage key

        /**
         * Swap all [data-th] / [data-en] elements on the page.
         * Works for any element: <a>, <button>, <span>, <p>, etc.
         */
        function applyLang(lang) {
            document.querySelectorAll('[data-th], [data-en]').forEach(el => {
                const text = lang === 'en' ? el.dataset.en : el.dataset.th;
                if (text !== undefined) el.textContent = text;
            });

            // Update TH|EN indicator — desktop
            const thD = document.getElementById('langTH');
            const enD = document.getElementById('langEN');
            if (thD && enD) {
                thD.classList.toggle('opacity-40', lang === 'en');
                enD.classList.toggle('opacity-40', lang !== 'en');
            }

            // Update TH|EN indicator — mobile
            const thM = document.getElementById('langTH_m');
            const enM = document.getElementById('langEN_m');
            if (thM && enM) {
                thM.classList.toggle('opacity-40', lang === 'en');
                enM.classList.toggle('opacity-40', lang !== 'en');
            }

            // Store choice
            localStorage.setItem(LANG_KEY, lang);

            // Notify other scripts on the page (optional, for future use)
            document.dispatchEvent(new CustomEvent('langchange', { detail: { lang } }));
        }

        function toggleLang() {
            const current = localStorage.getItem(LANG_KEY) || 'th';
            applyLang(current === 'th' ? 'en' : 'th');
        }

        // Bind both desktop and mobile buttons
        document.getElementById('langSwitcher')?.addEventListener('click', toggleLang);
        document.getElementById('langSwitcherMobile')?.addEventListener('click', toggleLang);

        // Apply saved language on page load (runs before paint on modern browsers)
        applyLang(localStorage.getItem(LANG_KEY) || 'th');
    </script>

</header>