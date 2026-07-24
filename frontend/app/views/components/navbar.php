<?php
declare(strict_types=1);
$currentPage = $currentPage ?? 'home';
$navItems = [
    ['path' => '/', 'label' => t('common.nav_home'), 'page' => 'home'],
    ['path' => '/about', 'label' => t('common.nav_about'), 'page' => 'about'],
    ['path' => '/services', 'label' => t('common.nav_services'), 'page' => 'services'],
    ['path' => '/erp', 'label' => t('common.nav_erp'), 'page' => 'erp'],
    ['path' => '/article', 'label' => t('common.nav_articles'), 'page' => 'articles'],
    ['path' => '/contact', 'label' => t('common.nav_contact'), 'page' => 'contact'],
];
$currentLang = getCurrentLang();
?>
<header class="sticky top-0 z-[1000] border-b border-slate-200 bg-white backdrop-blur">
    <div class="mx-auto flex h-16 md:h-20 w-full items-center justify-between px-4 md:px-6 lg:px-12">
        <!-- Logo -->
        <a class="flex items-center gap-3" href="<?= e(route_url('/')) ?>">
            <img class="h-10 md:h-12 lg:h-14 w-auto object-contain" src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" style="border: none; outline: none;">
        </a>
        <!-- Desktop & Tablet Navigation -->
        <style>
            .desktop-nav-link {
                color: #022862;
                font-weight: 600;
            }
            .desktop-nav-link:hover {
                color: #0663F6;
            }
            .desktop-nav-link.active {
                color: #0663F6;
                font-weight: 700;
            }
            @media (min-width: 1024px) {
                .nav-desktop-container {
                    display: flex !important;
                }
                .lang-switcher-container {
                    display: flex !important;
                }
                #mobileMenuToggle {
                    display: none !important;
                }
                #mobileMenu {
                    display: none !important;
                }
            }
        </style>
        <nav class="nav-desktop-container hidden lg:flex items-center gap-3 lg:gap-4" aria-label="Primary Navigation">
            <?php foreach ($navItems as $index => $item): ?>
                <a href="<?= e(route_url($item['path'])) ?>"
                   class="desktop-nav-link relative py-2 px-1 lg:px-1.5 transition-colors <?= $currentPage === $item['page'] ? 'active' : '' ?> text-base lg:text-lg"
                   <?= $currentPage === $item['page'] ? 'aria-current="page"' : '' ?>>
                   <?= e($item['label']) ?>
                </a>
                <?php if ($index < count($navItems) - 1): ?>
                    <span class="mx-3 lg:mx-4 text-base opacity-40" style="color: #011431;">•</span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <!-- Right Section -->
        <div class="flex items-center gap-6 lg:gap-8">
            <!-- Language Switcher -->
            <div class="lang-switcher-container hidden lg:flex items-center text-lg lg:text-xl font-bold transition-colors">
                <a href="<?= e(current_url_with_lang('th')) ?>" style="<?= $currentLang === 'th' ? 'color: #0663F6;' : 'color: #011431;' ?>" class="hover:opacity-80">TH</a>
                <span style="color: #011431;" class="mx-2 opacity-40">|</span>
                <a href="<?= e(current_url_with_lang('en')) ?>" style="<?= $currentLang === 'en' ? 'color: #0663F6;' : 'color: #011431;' ?>" class="hover:opacity-80">EN</a>
            </div>
            <!-- CTA Button (Hidden on Desktop as per design) -->
            <a href="<?= e(route_url('/contact')) ?>"
               class="hidden items-center justify-center px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full shadow-md transition hover:bg-blue-700 hover:-translate-y-0.5">
               <?= e(t('common.nav_cta_advice')) ?>
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
        <div class="flex flex-col w-full gap-2">
            <!-- Mobile Language Switcher -->
            <div class="w-full flex justify-end">
                <div class="flex items-center gap-2 mb-2 pr-1 text-base font-bold text-slate-800 transition-colors">
                    <a href="<?= e(current_url_with_lang('th')) ?>" class="<?= $currentLang === 'en' ? 'opacity-40 hover:text-primary' : 'text-primary' ?>">TH</a>
                    <span class="opacity-40">|</span>
                    <a href="<?= e(current_url_with_lang('en')) ?>" class="<?= $currentLang === 'th' ? 'opacity-40 hover:text-primary' : 'text-primary' ?>">EN</a>
                </div>
            </div>
            <?php foreach ($navItems as $item): ?>
                <a href="<?= e(route_url($item['path'])) ?>"
                   class="rounded-xl px-4 py-3 transition hover:bg-slate-50 <?= $currentPage === $item['page'] ? 'bg-blue-50 text-primary font-semibold' : 'text-slate-700' ?>">
                   <?= e($item['label']) ?>
                </a>
            <?php endforeach; ?>
            <!-- CTA Button -->
            <a href="<?= e(route_url('/contact')) ?>"
               class="inline-flex self-start mt-2 items-center justify-center px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full shadow-md transition hover:bg-blue-700 hover:-translate-y-0.5">
               <?= e(t('common.nav_cta_advice')) ?>
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
    </script>
</header>
