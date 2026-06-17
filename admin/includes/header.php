<?php
require_once __DIR__ . '/functions.php';
require_login();
require_admin_role();

$me = current_admin();
$page = $page ?? 'dashboard';

// นิยามเมนูที่นี่ที่เดียว ง่ายต่อการแก้ไขในอนาคต
$navItems = [
  ['name' => 'แดชบอร์ด', 'url' => '/index.php', 'page' => 'dashboard'],
  ['name' => 'การจัดการบทความ', 'url' => '/article/index.php', 'page' => 'article'],
  ['name' => 'การจัดการผลงาน', 'url' => '/portfolio/index.php', 'page' => 'portfolio'],
  ['name' => 'การจัดการรีวิว', 'url' => '/review/index.php', 'page' => 'review'],
  ['name' => 'การจัดการพันธมิตร', 'url' => '/partners/index.php', 'page' => 'partners'],
  ['name' => 'การจัดการบริการ', 'url' => '/service/index.php', 'page' => 'service'],
  ['name' => 'การตั้งค่าการติดต่อ', 'url' => '/contact/index.php', 'page' => 'contact'],
];
?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'ผู้ดูแลระบบ') ?> | <?= e(SITE_NAME) ?></title>
  <link href="<?= ADMIN_URL ?>/assets/css/dist/tailwind.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  </head>

<body class="admin-body bg-slate-50 font-sans antialiased text-slate-800">
  <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>
  
  <div class="admin-layout">
    <aside id="adminSidebar" class="fixed left-0 top-0 h-screen w-[260px] bg-white border-r border-gray-200 flex flex-col z-50 transition-transform duration-300 -translate-x-full md:translate-x-0">
      <div class="flex items-center h-16 px-6 font-bold text-lg border-b border-gray-200 flex-shrink-0 text-[#48cae4]">
        <img src="<?= ADMIN_URL ?>/assets/images/logo.png" alt="Logo" class="h-8 mr-3">
      </div>

      <nav class="flex-1 py-6 overflow-y-auto">
        <?php foreach ($navItems as $item):
          $isActive = ($page === $item['page']);
          $classes = $isActive
            ? 'bg-[rgba(72,202,228,0.1)] text-black border-l-4 border-cyan-400 pl-5 font-semibold'
            : 'text-black/70 hover:bg-[rgba(72,202,228,0.08)] hover:text-black';
        ?>
          <a href="<?= ADMIN_URL . $item['url'] ?>" class="px-6 py-3 text-sm flex items-center gap-3 <?= $classes ?>">
            <?= $item['name'] ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="border-t border-gray-200 py-4 flex-shrink-0">
        <a href="<?= ADMIN_URL ?>/logout.php" class="px-6 py-3 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 flex items-center gap-3 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          ออกจากระบบ
        </a>
      </div>
    </aside>

    <main id="adminMain" class="md:ml-[260px] flex-1 min-w-0 transition-all duration-300">
      <header class="sticky top-0 h-16 bg-white/80 backdrop-blur-sm shadow-sm px-4 md:px-6 flex items-center gap-4 z-30">
        <button id="sidebarToggle" class="md:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h1 class="text-lg md:text-xl font-semibold"><?= e($pageTitle ?? 'แผงควบคุม') ?></h1>
      </header>

      <section class="content p-4 md:p-6">
        <?php if ($msg = flash('success')): ?>
          <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
          <div class="p-4 mb-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200"><?= e($msg) ?></div>
        <?php endif; ?>

        <script>
          // JavaScript สำหรับเปิด-ปิด Sidebar
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