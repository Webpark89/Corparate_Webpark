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
    <div class="mx-auto flex h-16 w-full items-center justify-between px-4 md:px-8 lg:px-12">

        <!-- Logo -->
        <a class="flex items-center gap-3" href="<?= e(route_url('/')) ?>">
            <img class="h-10 w-auto object-contain" src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" style="border: none; outline: none;">
        </a>

        <!-- Desktop Navigation -->
        <style>
            .desktop-nav-link {
                color: #022862;
            }
            .desktop-nav-link:hover {
                color: #0663F6;
            }
            .desktop-nav-link.active {
                color: #0663F6;
                font-weight: 600;
            }
        </style>
        <nav class="hidden lg:flex items-center gap-2" aria-label="Primary Navigation">
            <?php foreach ($navItems as $index => $item): ?>
                <a href="<?= e(route_url($item['path'])) ?>"
                   class="desktop-nav-link relative py-2 text-sm transition-colors <?= $currentPage === $item['page'] ? 'active' : 'font-medium' ?>"
                   data-th="<?= e($item['label_th']) ?>"
                   data-en="<?= e($item['label_en']) ?>"
                   <?= $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                   <?= e($item['label_th']) ?>
                </a>
                <?php if ($index < count($navItems) - 1): ?>
                    <span class="mx-2 text-xs opacity-60" style="color: #011431;">•</span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Language Switcher -->
            <button id="langSwitcher"
                    class="hidden lg:flex items-center text-[15px] font-bold transition-colors"
                    aria-label="Switch language">
                <span id="langTH" style="color: #0663F6;">TH</span>
                <span id="langEN" style="color: #011431;" class="ml-1">| EN</span>
            </button>

            <!-- CTA Button (Hidden on Desktop as per design) -->
            <a href="<?= e(route_url('/contact')) ?>"
               class="hidden items-center justify-center px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full shadow-md transition hover:bg-blue-700 hover:-translate-y-0.5"
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
            
            // Desktop Language Switcher (Color Swap instead of opacity)
            const deskTH = document.getElementById('langTH');
            const deskEN = document.getElementById('langEN');
            if (deskTH && deskEN) {
                deskTH.style.color = lang === 'en' ? '#011431' : '#0663F6';
                deskEN.style.color = lang === 'en' ? '#0663F6' : '#011431';
                deskEN.innerHTML = lang === 'en' ? '<span style="color: #011431;">|</span> EN' : '| EN';
            }

            // Mobile Language Switcher (Keep opacity toggle as requested)
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
