<?php
declare(strict_types=1);

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

<header class="sticky top-0 z-[1000] border-b border-slate-200 bg-white backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

        <!-- Logo -->
        <a class="flex items-center gap-3" href="<?= e(route_url('/')) ?>">
            <img class="h-9 w-9 object-contain" src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo">
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-6" aria-label="Primary Navigation">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= e(route_url($item['path'])) ?>"
                   class="relative py-2 text-sm transition-colors hover:text-primary <?= $currentPage === $item['page'] ? 'text-primary font-semibold' : 'text-slate-700 font-medium' ?>"
                   data-th="<?= e($item['label_th']) ?>"
                   data-en="<?= e($item['label_en']) ?>"
                   <?= $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                   <?= e($item['label_th']) ?>
                   <span class="absolute bottom-0 left-0 h-[2px] w-full origin-left bg-primary transition-transform duration-300 ease-out <?= $currentPage === $item['page'] ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?>"></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Language Switcher -->
            <button id="langSwitcher"
                    class="hidden lg:flex items-center gap-1 text-sm font-semibold text-slate-700 hover:text-primary transition-colors"
                    aria-label="Switch language">
                <span id="langTH">TH</span>
                <span class="opacity-40">|</span>
                <span id="langEN" class="opacity-40">EN</span>
            </button>

            <!-- CTA Button -->
            <a href="<?= e(route_url('/contact')) ?>"
               class="inline-flex items-center justify-center px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full shadow-md transition hover:bg-blue-700 hover:-translate-y-0.5"
               data-th="ขอคำปรึกษา"
               data-en="Get Advice">
               ขอคำปรึกษา
            </a>

            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuToggle"
                    class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-lg font-bold"
                    aria-label="Toggle navigation"
                    aria-expanded="false"
                    aria-controls="mobileMenu">
                ☰
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden border-t border-slate-200 bg-white px-4 py-4 lg:hidden">
        <div class="flex flex-col gap-2">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= e(route_url($item['path'])) ?>"
                   class="rounded-xl px-4 py-3 transition hover:bg-slate-50 <?= $currentPage === $item['page'] ? 'bg-blue-50 text-primary font-semibold' : 'text-slate-700' ?>"
                   data-th="<?= e($item['label_th']) ?>"
                   data-en="<?= e($item['label_en']) ?>">
                   <?= e($item['label_th']) ?>
                </a>
            <?php endforeach; ?>

            <!-- Mobile Language Switcher -->
            <button id="langSwitcherMobile"
                    class="flex items-center gap-1 text-sm font-semibold text-slate-700 hover:text-primary transition-colors"
                    aria-label="Switch language">
                <span id="langTH_m">TH</span>
                <span class="opacity-40">|</span>
                <span id="langEN_m" class="opacity-40">EN</span>
            </button>

            <!-- CTA Button -->
            <a href="<?= e(route_url('/contact')) ?>"
               class="inline-flex items-center justify-center px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full shadow-md transition hover:bg-blue-700 hover:-translate-y-0.5"
               data-th="ขอคำปรึกษา"
               data-en="Get Advice">
               ขอคำปรึกษา
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const toggleBtn  = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        toggleBtn?.addEventListener('click', () => {
            const isOpening = mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
            toggleBtn.setAttribute('aria-expanded', isOpening ? 'true' : 'false');
            toggleBtn.innerHTML = isOpening ? '✕' : '☰';
        });

        // Language switcher
        const LANG_KEY = 'wp_lang';
        function applyLang(lang) {
            document.querySelectorAll('[data-th],[data-en]').forEach(el => {
                el.textContent = lang === 'en' ? el.dataset.en : el.dataset.th;
            });
            document.getElementById('langTH')?.classList.toggle('opacity-40', lang === 'en');
            document.getElementById('langEN')?.classList.toggle('opacity-40', lang !== 'en');
            document.getElementById('langTH_m')?.classList.toggle('opacity-40', lang === 'en');
            document.getElementById('langEN_m')?.classList.toggle('opacity-40', lang !== 'en');
            localStorage.setItem(LANG_KEY, lang);
        }
        function toggleLang() {
            const current = localStorage.getItem(LANG_KEY) || 'th';
            applyLang(current === 'th' ? 'en' : 'th');
        }
        document.getElementById('langSwitcher')?.addEventListener('click', toggleLang);
        document.getElementById('langSwitcherMobile')?.addEventListener('click', toggleLang);
        applyLang(localStorage.getItem(LANG_KEY) || 'th');
    </script>
</header>
