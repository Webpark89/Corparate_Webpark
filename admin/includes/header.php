<?php
/**
 * Admin layout header — auth gate, sidebar navigation, flash messages, and page shell.
 */
require_once __DIR__ . '/functions.php';
require_login();
require_admin_role();
$me = current_admin();
$page = $page ?? 'dashboard';

// Count unread inbox messages if user has permission
// Count unread inbox messages if user has permission
$unreadInboxCount = 0;
try {
    if (has_permission('inbox.view')) {
        $unreadInboxCount = (int) db()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
    }
} catch (Throwable $e) {
    $unreadInboxCount = 0;
}

$rawNavItems = [
    [
        'type' => 'link',
        'name' => 'Dashboard',
        'url' => '/index.php',
        'page' => 'dashboard',
        'perm' => null,
        'icon' => '<svg width="18" height="18" style="width: 18px; height: 18px; min-width: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
    ],
    [
        'type' => 'dropdown',
        'id' => 'content',
        'name' => 'Content',
        'icon' => '<svg width="18" height="18" style="width: 18px; height: 18px; min-width: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
        'pages' => ['article', 'portfolio', 'service', 'services', 'review', 'partners'],
        'items' => [
            ['name' => 'Articles', 'url' => '/article/index.php', 'page' => 'article', 'perm' => 'article.view'],
            ['name' => 'Portfolio', 'url' => '/portfolio/index.php', 'page' => 'portfolio', 'perm' => 'portfolio.view'],
            ['name' => 'Services', 'url' => '/service/index.php', 'page' => 'service', 'perm' => 'service.view'],
            ['name' => 'Reviews', 'url' => '/review/index.php', 'page' => 'review', 'perm' => 'review.view'],
            ['name' => 'Partners', 'url' => '/partners/index.php', 'page' => 'partners', 'perm' => 'partners.view'],
        ],
    ],
    [
        'type' => 'link',
        'name' => 'Inbox',
        'url' => '/contact_inbox/index.php',
        'page' => 'contact_inbox',
        'perm' => 'inbox.view',
        'badge' => $unreadInboxCount,
        'icon' => '<svg width="18" height="18" style="width: 18px; height: 18px; min-width: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>',
    ],
    [
        'type' => 'dropdown',
        'id' => 'settings',
        'name' => 'Settings',
        'icon' => '<svg width="18" height="18" style="width: 18px; height: 18px; min-width: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'pages' => ['category', 'contact', 'settings', 'users', 'roles', 'change_password'],
        'items' => [
            ['name' => 'Categories', 'url' => '/category/index.php', 'page' => 'category', 'perm' => 'category.view'],
            ['name' => 'Contact Settings', 'url' => '/contact/index.php', 'page' => 'contact', 'perm' => 'contact.view'],
            ['name' => 'Users', 'url' => '/users/index.php', 'page' => 'users', 'perm' => 'users.view'],
            ['name' => 'Roles & Permissions', 'url' => '/roles/index.php', 'page' => 'roles', 'perm' => 'roles.view'],
            ['name' => 'Change Password', 'url' => '/change_password.php', 'page' => 'change_password', 'perm' => null],
        ],
    ],
];

// Filter navigation items based on permissions
$navItems = [];
foreach ($rawNavItems as $entry) {
    if ($entry['type'] === 'link') {
        if (empty($entry['perm']) || has_permission($entry['perm'])) {
            $navItems[] = $entry;
        }
    } elseif ($entry['type'] === 'dropdown') {
        $allowedSubItems = array_filter($entry['items'], function ($sub) {
            return empty($sub['perm']) || has_permission($sub['perm']);
        });
        if (!empty($allowedSubItems)) {
            $entry['items'] = array_values($allowedSubItems);
            $navItems[] = $entry;
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <script>
        (function() {
            function enforceMobileTargetViewport() {
                var targetWidth = 390;
                var sw = (window.innerWidth && window.innerWidth < 768 && window.screen.width >= 768) ? window.innerWidth : window.screen.width;
                var vp = document.querySelector('meta[name="viewport"]');
                if (!vp) return;

                if (sw > 0 && sw < 768) {
                    var scale = sw / targetWidth;
                    vp.setAttribute('content', 'width=' + targetWidth + ', initial-scale=' + scale + ', maximum-scale=' + scale + ', user-scalable=no, viewport-fit=cover');
                } else {
                    vp.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover');
                }
            }
            enforceMobileTargetViewport();
            window.addEventListener('resize', enforceMobileTargetViewport);
            window.addEventListener('orientationchange', enforceMobileTargetViewport);
        })();
    </script>
    <title><?= e($pageTitle ?? 'Admin') ?> | <?= e(SITE_NAME) ?></title>
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

        /* Responsive Form Bottom Bar */
        .form-sticky-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.375rem;
            width: 100%;
        }
        .form-sticky-bar svg {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0 !important;
        }
        @media (max-width: 639px) {
            .form-sticky-bar .btn-cancel {
                flex: 1 1 0% !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                height: 40px !important;
                font-size: 0.75rem !important;
                white-space: nowrap !important;
            }
            .form-sticky-bar .actions-group {
                display: flex !important;
                align-items: center !important;
                gap: 0.375rem !important;
                flex: 3 1 0% !important;
                justify-content: flex-end !important;
            }
            .form-sticky-bar .actions-group button {
                flex: 1 1 0% !important;
                padding-left: 0.25rem !important;
                padding-right: 0.25rem !important;
                height: 40px !important;
                font-size: 0.75rem !important;
                white-space: nowrap !important;
            }
        }
        @media (min-width: 640px) {
            .form-sticky-bar svg {
                width: 16px !important;
                height: 16px !important;
                min-width: 16px !important;
                max-width: 16px !important;
                max-height: 16px !important;
            }
            .form-sticky-bar .btn-cancel {
                flex: 0 0 auto !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
                height: 44px !important;
                font-size: 0.875rem !important;
            }
            .form-sticky-bar .actions-group {
                display: flex !important;
                align-items: center !important;
                gap: 0.75rem !important;
                flex: 0 0 auto !important;
            }
            .form-sticky-bar .actions-group button {
                flex: 0 0 auto !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
                height: 44px !important;
                font-size: 0.875rem !important;
            }
        }

        /* Inbox Filter Grid & Container Spacing */
        @media (max-width: 767px) {
            .filter-pills-container {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.875rem !important;
                width: 100% !important;
            }
            .filter-pills-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.625rem !important;
                width: 100% !important;
            }
            .filter-pills-grid > a {
                width: 100% !important;
                display: inline-flex !important;
                justify-content: space-between !important;
                padding-left: 1.125rem !important;
                padding-right: 1.125rem !important;
                min-height: 40px !important;
            }
        }
        @media (min-width: 768px) {
            .filter-pills-container {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                gap: 0.75rem !important;
                width: auto !important;
            }
            .filter-pills-grid {
                display: flex !important;
                flex-wrap: wrap !important;
                align-items: center !important;
                gap: 0.625rem !important;
                width: auto !important;
            }
            .filter-pills-grid > a {
                width: auto !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
        }

        /* Active filter pill solid colors */
        .filter-btn-active-new {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
        }
        .filter-btn-active-new * {
            color: #ffffff !important;
        }

        .filter-btn-active-read {
            background-color: #d97706 !important;
            color: #ffffff !important;
            border-color: #d97706 !important;
        }
        .filter-btn-active-read * {
            color: #ffffff !important;
        }

        .filter-btn-active-replied {
            background-color: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }
        .filter-btn-active-replied * {
            color: #ffffff !important;
        }

        .filter-btn-active-archived {
            background-color: #dc2626 !important;
            color: #ffffff !important;
            border-color: #dc2626 !important;
        }
        .filter-btn-active-archived * {
            color: #ffffff !important;
        }

        /* User Role Section Card on Mobile */
        @media (max-width: 767px) {
            .user-role-section-card {
                padding: 1.25rem 1rem !important;
            }
        }

        /* Top Navbar User Profile & Role Badge: Visible on Desktop & Laptop, compact on Mobile */
        .top-nav-role-badge {
            display: inline-flex !important;
            align-items: center !important;
        }
        .top-nav-full-name {
            display: inline !important;
        }
        @media (max-width: 767px) {
            .top-nav-role-badge,
            .top-nav-full-name {
                display: none !important;
            }
        }

        /* Category Page - Quick Add Card & Action Buttons */
        .cat-quick-add-card {
            padding: 1.5rem !important;
        }
        .cat-action-view.hidden,
        .cat-action-edit.hidden {
            display: none !important;
        }
        .cat-btn-save {
            background-color: #059669 !important;
            color: #ffffff !important;
        }
        .cat-btn-save:hover {
            background-color: #047857 !important;
        }

        /* Category Page - Mobile Only Layout Adjustments (Desktop is 100% Untouched) */
        @media (max-width: 767px) {
            .cat-page-container {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .cat-quick-add-card {
                padding: 1.25rem !important;
            }
            #createCategoryForm {
                display: flex !important;
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }
            .cat-form-col-1, .cat-form-col-2, .cat-form-col-btn {
                flex: none !important;
                width: 100% !important;
                min-width: 100% !important;
            }
            #btnCreateCat {
                width: 100% !important;
                height: 44px !important;
                padding: 0 1rem !important;
                justify-content: center !important;
                margin-top: 0.25rem !important;
            }

            /* Category Table to Responsive Card Rows on Mobile */
            .cat-table thead {
                display: none !important;
            }
            .cat-table, 
            .cat-table tbody, 
            .cat-table tr {
                display: block !important;
                width: 100% !important;
            }
            .cat-table tr {
                padding: 1.25rem 1rem !important;
                border-bottom: 1px solid #f1f5f9 !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            .cat-table td {
                display: block !important;
                padding: 0 !important;
                border: none !important;
                width: 100% !important;
                text-align: left !important;
            }
            .cat-col-name {
                font-size: 0.9375rem !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }
            .cat-col-slug {
                margin-top: -2px !important;
            }
            .cat-col-slug .cat-slug-view {
                font-size: 11px !important;
                padding: 4px 9px !important;
                background-color: #f1f5f9 !important;
                border-radius: 6px !important;
                display: inline-block !important;
                word-break: break-all !important;
            }
            .cat-col-count {
                margin-top: 2px !important;
            }
            .cat-col-actions {
                margin-top: 8px !important;
                display: flex !important;
                justify-content: flex-start !important;
                width: 100% !important;
            }
            .cat-action-view:not(.hidden),
            .cat-action-edit:not(.hidden) {
                display: inline-flex !important;
                width: auto !important;
                border-radius: 0.75rem !important;
                border: 1px solid #e2e8f0 !important;
                overflow: hidden !important;
            }
            .cat-action-view button,
            .cat-action-edit button {
                padding: 7px 16px !important;
                font-size: 0.75rem !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 0.375rem !important;
                white-space: nowrap !important;
            }
        }
    </style>
</head>
<body class="admin-body bg-slate-50 font-sans antialiased text-slate-800 overflow-x-hidden relative w-full max-w-full">
    <div class="admin-wrapper overflow-x-hidden relative w-full max-w-full min-h-screen">
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" style="cursor: pointer; backdrop-filter: blur(2px);"></div>
    <div class="admin-layout">
        <aside id="adminSidebar" class="fixed left-0 top-0 h-screen w-[260px] bg-white border-r border-gray-200 flex flex-col z-50 transition-transform duration-300 -translate-x-full md:translate-x-0">
            <div class="flex items-center h-16 px-6 font-bold text-lg border-b border-gray-200 flex-shrink-0 text-[#0663F6]">
                <img src="<?= ADMIN_URL ?>/assets/images/logo.png" alt="Logo" class="h-8 mr-3">
            </div>
            <!-- Mobile User Info Banner inside Sidebar Drawer -->
            <div class="md:hidden px-6 py-3 border-b border-gray-100 flex items-center gap-3 bg-slate-50/50">
                <div style="width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; <?= ($me['role'] ?? '') === 'super_admin' ? 'background-color: #ede9fe; color: #7c3aed;' : 'background-color: #eff6ff; color: #2563eb;' ?>">
                    <?= mb_substr($me['username'] ?? 'AD', 0, 2) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-xs text-slate-800 truncate"><?= e($me['username'] ?? 'Admin') ?></div>
                    <div class="text-[11px] text-purple-600 font-semibold truncate"><?= e($me['role_name'] ?? 'Super Admin') ?></div>
                </div>
            </div>
            <nav class="flex-1 py-4 overflow-y-auto">
                <?php foreach ($navItems as $entry): ?>
                    <?php if ($entry['type'] === 'link'): 
                        $isActive = ($page === $entry['page'] || ($entry['page'] === 'contact' && $page === 'settings'));
                    ?>
                        <a href="<?= ADMIN_URL . $entry['url'] ?>" 
                           class="flex items-center gap-3 px-6 py-2.5 text-[15px] font-medium transition-colors"
                           style="width: 100%; display: flex; align-items: center; justify-content: flex-start; <?= $isActive ? 'background-color: rgba(6, 99, 246, 0.08); color: #0663F6; border-left: 4px solid #0663F6; padding-left: 1.25rem; font-weight: 600;' : 'color: #334155;' ?>"
                           onmouseover="if (!<?= $isActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'rgba(6, 99, 246, 0.04)'; this.style.color = '#0663F6'; }"
                           onmouseout="if (!<?= $isActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'transparent'; this.style.color = '#334155'; }">
                            <span style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; min-width: 18px; flex-shrink: 0; color: <?= $isActive ? '#0663F6' : '#64748b' ?>;">
                                <?= $entry['icon'] ?>
                            </span>
                            <span style="white-space: nowrap;"><?= e($entry['name']) ?></span>
                            <?php if (!empty($entry['badge']) && $entry['badge'] > 0): ?>
                                <span style="margin-left: auto; display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 6px; font-size: 11px; font-weight: 700; border-radius: 9999px; background-color: #0663F6; color: #ffffff; line-height: 1; flex-shrink: 0; box-shadow: 0 1px 2px rgba(6, 99, 246, 0.2);">
                                    <?= $entry['badge'] ?>
                                </span>
                            <?php endif; ?>
                        </a>

                    <?php elseif ($entry['type'] === 'dropdown'): 
                        $isChildActive = in_array($page, $entry['pages'], true) || ($page === 'services' && in_array('service', $entry['pages'], true)) || ($page === 'settings' && in_array('contact', $entry['pages'], true));
                        $menuId = 'menu-' . $entry['id'];
                        $arrowId = 'arrow-' . $entry['id'];
                    ?>
                        <div class="sidebar-dropdown-group">
                            <button type="button" 
                                    onclick="toggleSidebarMenu('<?= $menuId ?>', '<?= $arrowId ?>')"
                                    class="flex items-center gap-3 px-6 py-2.5 text-[15px] font-medium transition-colors w-full text-left"
                                    style="width: 100%; display: flex; align-items: center; cursor: pointer; border: none; background: transparent; <?= $isChildActive ? 'color: #0663F6; font-weight: 600;' : 'color: #334155;' ?>"
                                    onmouseover="this.style.backgroundColor = 'rgba(6, 99, 246, 0.04)'; this.style.color = '#0663F6';"
                                    onmouseout="this.style.backgroundColor = 'transparent'; this.style.color = '<?= $isChildActive ? '#0663F6' : '#334155' ?>';">
                                <span style="display: flex; align-items: center; justify-content: center; width: 18px; height: 18px; min-width: 18px; flex-shrink: 0; color: <?= $isChildActive ? '#0663F6' : '#64748b' ?>;">
                                    <?= $entry['icon'] ?>
                                </span>
                                <span style="white-space: nowrap;"><?= e($entry['name']) ?></span>
                                <svg id="<?= $arrowId ?>" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px; flex-shrink: 0; margin-left: auto; transition: transform 0.25s ease; transform: <?= $isChildActive ? 'rotate(180deg)' : 'rotate(0deg)' ?>; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="<?= $menuId ?>" data-menu-id="<?= $menuId ?>" data-active="<?= $isChildActive ? 'true' : 'false' ?>" style="<?= $isChildActive ? 'display: block;' : 'display: none;' ?> background-color: rgba(248, 250, 252, 0.7); padding: 0.25rem 0;">
                                <?php foreach ($entry['items'] as $sub): 
                                    $isSubActive = ($page === $sub['page'] || ($sub['page'] === 'service' && $page === 'services') || ($sub['page'] === 'contact' && $page === 'settings'));
                                ?>
                                    <a href="<?= ADMIN_URL . $sub['url'] ?>" 
                                       class="flex items-center py-2 text-[14px] transition-colors"
                                       style="display: flex; align-items: center; width: 100%; padding-left: 3rem; padding-right: 1.5rem; <?= $isSubActive ? 'background-color: rgba(6, 99, 246, 0.08); color: #0663F6; font-weight: 600; border-left: 3px solid #0663F6; padding-left: calc(3rem - 3px);' : 'color: #475569; font-weight: 400;' ?>"
                                       onmouseover="if (!<?= $isSubActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'rgba(6, 99, 246, 0.04)'; this.style.color = '#0663F6'; }"
                                       onmouseout="if (!<?= $isSubActive ? 'true' : 'false' ?>) { this.style.backgroundColor = 'transparent'; this.style.color = '#475569'; }">
                                        <span style="white-space: nowrap;"><?= e($sub['name']) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
            <div class="border-t border-gray-200 py-3 flex-shrink-0" style="padding-top: 0.75rem; padding-bottom: 0.75rem; border-top: 1px solid #e2e8f0;">
                <a href="<?= ADMIN_URL ?>/logout.php" 
                   class="flex items-center gap-3 px-6 py-2 text-[15px] font-medium transition-colors"
                   style="color: #ef4444; width: 100%; display: flex; align-items: center;"
                   onmouseover="this.style.backgroundColor = '#fef2f2'; this.style.color = '#dc2626';"
                   onmouseout="this.style.backgroundColor = 'transparent'; this.style.color = '#ef4444';">
                    <svg width="18" height="18" style="width: 18px; height: 18px; min-width: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span style="white-space: nowrap;">Sign Out</span>
                </a>
            </div>
            <script>
                (function() {
                    var dropdowns = document.querySelectorAll('[data-menu-id]');
                    dropdowns.forEach(function(panel) {
                        var menuId = panel.getAttribute('data-menu-id');
                        var arrowId = menuId.replace('menu-', 'arrow-');
                        var arrow = document.getElementById(arrowId);
                        var isServerActive = panel.getAttribute('data-active') === 'true';
                        var savedState = localStorage.getItem('sidebar_' + menuId);

                        if (isServerActive) {
                            panel.style.display = 'block';
                            if (arrow) arrow.style.transform = 'rotate(180deg)';
                            localStorage.setItem('sidebar_' + menuId, 'open');
                        } else if (savedState === 'open') {
                            panel.style.display = 'block';
                            if (arrow) arrow.style.transform = 'rotate(180deg)';
                        } else if (savedState === 'closed') {
                            panel.style.display = 'none';
                            if (arrow) arrow.style.transform = 'rotate(0deg)';
                        }
                    });
                })();

                function toggleSidebarMenu(menuId, arrowId) {
                    var menu = document.getElementById(menuId);
                    var arrow = document.getElementById(arrowId);
                    if (!menu) return;
                    var isClosed = (menu.style.display === 'none' || window.getComputedStyle(menu).display === 'none');
                    if (isClosed) {
                        menu.style.display = 'block';
                        if (arrow) arrow.style.transform = 'rotate(180deg)';
                        localStorage.setItem('sidebar_' + menuId, 'open');
                    } else {
                        menu.style.display = 'none';
                        if (arrow) arrow.style.transform = 'rotate(0deg)';
                        localStorage.setItem('sidebar_' + menuId, 'closed');
                    }
                }
            </script>
        </aside>
        <main id="adminMain" class="md:ml-[260px] flex-1 min-w-0 transition-all duration-300">
            <header class="sticky top-0 h-16 bg-white/80 backdrop-blur-sm shadow-sm px-4 md:px-6 flex items-center justify-between gap-4 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="sidebarToggle" class="md:hidden p-2 rounded-md text-slate-600 hover:bg-slate-100">
                        <svg class="h-6 w-6" style="width: 1.5rem; height: 1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-base md:text-lg font-bold text-slate-800 truncate"><?= e($pageTitle ?? 'Dashboard') ?></h1>
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
                                <span class="top-nav-full-name" style="font-size: 0.6875rem; color: #64748b; line-height: 1.2;">
                                    <?= e($me['full_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Role Badge (Compact on mobile, full on desktop) -->
                    <?php if (is_super_admin()): ?>
                        <span class="top-nav-role-badge" style="align-items: center; gap: 0.35rem; border-radius: 9999px; background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); color: #7c3aed; padding: 0.25rem 0.65rem; font-size: 0.6875rem; font-weight: 700; border: 1px solid #ddd6fe; box-shadow: 0 1px 2px rgba(124,58,237,0.08);">
                            <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #9333ea;"></span>
                            <span><?= e($me['role_name'] ?? 'Super Admin') ?></span>
                        </span>
                    <?php else: ?>
                        <span class="top-nav-role-badge" style="align-items: center; gap: 0.35rem; border-radius: 9999px; background-color: #eff6ff; color: #2563eb; padding: 0.25rem 0.65rem; font-size: 0.6875rem; font-weight: 600; border: 1px solid #bfdbfe;">
                            <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #2563eb;"></span>
                            <span><?= e($me['role_name'] ?? 'ผู้ดูแลระบบ') ?></span>
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
