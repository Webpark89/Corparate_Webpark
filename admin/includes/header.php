<?php
/**
 * Admin layout header — auth gate, sidebar navigation, flash messages, and page shell.
 */
require_once __DIR__ . '/functions.php';
require_login();
require_admin_role();
$me = current_admin();
$page = $page ?? 'dashboard';
$navItems = [
    ['name' => 'แดชบอร์ด', 'url' => '/index.php', 'page' => 'dashboard'],
    ['name' => 'การจัดการบทความ', 'url' => '/article/index.php', 'page' => 'article'],
    ['name' => 'หมวดหมู่บทความ', 'url' => '/category/index.php', 'page' => 'category'],
    ['name' => 'การจัดการผลงาน', 'url' => '/portfolio/index.php', 'page' => 'portfolio'],
    ['name' => 'การจัดการรีวิว', 'url' => '/review/index.php', 'page' => 'review'],
    ['name' => 'การจัดการลูกค้า', 'url' => '/partners/index.php', 'page' => 'partners'],
    ['name' => 'การจัดการบริการ', 'url' => '/service/index.php', 'page' => 'service'],
    ['name' => 'ข้อความจากลูกค้า', 'url' => '/contact_inbox/index.php', 'page' => 'contact_inbox'],
];

if (is_super_admin()) {
    $navItems[] = ['name' => 'การตั้งค่าการติดต่อ', 'url' => '/contact/index.php', 'page' => 'contact'];
    $navItems[] = ['name' => 'จัดการผู้ดูแลระบบ', 'url' => '/users/index.php', 'page' => 'users'];
}

$navItems[] = ['name' => 'เปลี่ยนรหัสผ่าน', 'url' => '/change_password.php', 'page' => 'change_password'];
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?= e($pageTitle ?? 'ผู้ดูแลระบบ') ?> | <?= e(SITE_NAME) ?></title>
    <link rel="icon" type="image/png" href="<?= ADMIN_URL ?>/assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?= ADMIN_URL ?>/assets/images/logo.png">
    <link href="<?= ADMIN_URL ?>/assets/css/dist/tailwind.css?v=<?= file_exists(__DIR__ . '/../assets/css/dist/tailwind.css') ? filemtime(__DIR__ . '/../assets/css/dist/tailwind.css') : '1.0' ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        html, body {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            position: relative;
            margin: 0;
            padding: 0;
            touch-action: pan-y;
        }
        .admin-layout, #adminMain, section.content {
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        * {
            box-sizing: border-box !important;
        }
    </style>
</head>
<body class="admin-body bg-slate-50 font-sans antialiased text-slate-800 overflow-x-hidden relative w-full max-w-full">
    <div class="admin-wrapper overflow-x-hidden relative w-full max-w-full min-h-screen">
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>
    <div class="admin-layout">
        <aside id="adminSidebar" class="fixed left-0 top-0 h-screen w-[260px] bg-white border-r border-gray-200 flex flex-col z-50 transition-transform duration-300 -translate-x-full md:translate-x-0">
            <div class="flex items-center h-16 px-6 font-bold text-lg border-b border-gray-200 flex-shrink-0 text-[#0663F6]">
                <img src="<?= ADMIN_URL ?>/assets/images/logo.png" alt="Logo" class="h-8 mr-3">
            </div>
            <nav class="flex-1 py-6 overflow-y-auto">
                <?php foreach ($navItems as $item):
                    $isActive = ($page === $item['page']);
                    $baseClasses = 'px-6 py-3 text-base flex items-center gap-3 transition-colors';
                    if ($isActive) {
                        $classes = $baseClasses . ' border-l-4 pl-5 font-semibold';
                        $style = 'background-color: rgba(6, 99, 246, 0.1); color: #0663F6; border-color: #0663F6;';
                    } else {
                        $classes = $baseClasses . ' text-black/70 hover:font-semibold';
                        $style = '';
                    }
                ?>
                    <a href="<?= ADMIN_URL . $item['url'] ?>" class="<?= $classes ?>" style="<?= $style ?>" 
                       onmouseover="if(!<?= $isActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'rgba(6, 99, 246, 0.05)'; this.style.color = '#0663F6'; }" 
                       onmouseout="if(!<?= $isActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'transparent'; this.style.color = 'inherit'; }">
                        <?= e($item['name']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="border-t border-gray-200 py-4 flex-shrink-0">
                <a href="<?= ADMIN_URL ?>/logout.php" class="px-6 py-3 text-base text-red-500 hover:bg-red-50 hover:text-red-600 flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" style="width: 1rem; height: 1rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    ออกจากระบบ
                </a>
            </div>
        </aside>
        <main id="adminMain" class="md:ml-[260px] flex-1 min-w-0 transition-all duration-300">
            <header class="sticky top-0 h-16 bg-white/80 backdrop-blur-sm shadow-sm px-4 md:px-6 flex items-center justify-between gap-4 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="sidebarToggle" class="md:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100">
                        <svg class="h-6 w-6" style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-base md:text-lg font-bold text-slate-800 truncate"><?= e($pageTitle ?? 'แผงควบคุม') ?></h1>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0;">
                    <!-- User Avatar & Username -->
                    <div style="display: flex; align-items: center; gap: 0.625rem;">
                        <div style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; <?= ($me['role'] ?? '') === 'super_admin' ? 'background-color: #ede9fe; color: #7c3aed; border: 1px solid #ddd6fe;' : 'background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;' ?>">
                            <?= mb_substr($me['username'] ?? 'AD', 0, 2) ?>
                        </div>
                        <div style="display: flex; flex-direction: column; text-align: left;">
                            <span style="font-size: 0.8125rem; font-weight: 700; color: #1e293b; line-height: 1.2;">
                                <?= e($me['username'] ?? 'Admin') ?>
                            </span>
                            <?php if (!empty($me['full_name'])): ?>
                                <span style="font-size: 0.6875rem; color: #64748b; line-height: 1.2;">
                                    <?= e($me['full_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Role Badge -->
                    <?php if (($me['role'] ?? '') === 'super_admin'): ?>
                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; border-radius: 9999px; background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); color: #7c3aed; padding: 0.25rem 0.625rem; font-size: 0.6875rem; font-weight: 700; border: 1px solid #ddd6fe; box-shadow: 0 1px 2px rgba(124,58,237,0.08);">
                            <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #9333ea;"></span>
                            <span>Super Admin</span>
                        </span>
                    <?php else: ?>
                        <span style="display: inline-flex; align-items: center; gap: 0.25rem; border-radius: 9999px; background-color: #eff6ff; color: #2563eb; padding: 0.25rem 0.625rem; font-size: 0.6875rem; font-weight: 600; border: 1px solid #bfdbfe;">
                            <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #2563eb;"></span>
                            <span>Admin</span>
                        </span>
                    <?php endif; ?>
                </div>
            </header>
            <section class="content p-4 md:p-6">
                <?php if ($msg = flash('success')): ?>
                    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200"><?= e($msg) ?></div>
                <?php endif; ?>
                <?php if ($msg = flash('error')): ?>
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200"><?= e($msg) ?></div>
                <?php endif; ?>
                <script>
                    const toggle = document.getElementById('sidebarToggle');
                    const sidebar = document.getElementById('adminSidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    function toggleMenu() {
                        sidebar.classList.toggle('-translate-x-full');
                        sidebar.classList.toggle('translate-x-0');
                        overlay.classList.toggle('hidden');
                    }
                    toggle.addEventListener('click', toggleMenu);
                    overlay.addEventListener('click', toggleMenu);
                </script>
