<?php

/**
 * Admin dashboard — overview stats and recent articles/portfolio activity.
 */
$pageTitle = 'Dashboard';
$page = 'dashboard';
require_once __DIR__ . '/includes/header.php';

$counts = [
    'article' => [
        'total' => (int) db()->query('SELECT COUNT(*) FROM article')->fetchColumn(),
        'published' => (int) db()->query('SELECT COUNT(*) FROM article WHERE status = \'published\'')->fetchColumn(),
        'draft' => (int) db()->query('SELECT COUNT(*) FROM article WHERE status = \'draft\'')->fetchColumn(),
    ],

    'portfolio' => [
        'total' => (int) db()->query('SELECT COUNT(*) FROM portfolio')->fetchColumn(),
        'published' => (int) db()->query('SELECT COUNT(*) FROM portfolio WHERE status = \'published\'')->fetchColumn(),
        'draft' => (int) db()->query('SELECT COUNT(*) FROM portfolio WHERE status = \'draft\'')->fetchColumn(),
    ],

    'partners' => [
        'total' => (int) db()->query('SELECT COUNT(*) FROM partners')->fetchColumn(),
        'active' => (int) db()->query('SELECT COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) FROM partners')->fetchColumn(),
        'inactive' => (int) db()->query('SELECT COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) FROM partners')->fetchColumn(),
    ],

    'review' => [
        'total' => (int) db()->query('SELECT COUNT(*) FROM review')->fetchColumn(),
        'active' => (int) db()->query('SELECT COALESCE(SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END), 0) FROM review')->fetchColumn(),
        'inactive' => (int) db()->query('SELECT COALESCE(SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END), 0) FROM review')->fetchColumn(),
    ],
];

$dashboardCards = [
    [
        'key' => 'article',
        'title' => 'บทความทั้งหมด',
        'unit' => 'เรื่อง',
        'primaryLabel' => 'เผยแพร่แล้ว (Published)',
        'secondaryLabel' => 'แบบร่าง (Draft)',
        'primaryColor' => '#10b981',
        'secondaryColor' => '#fbbf24',
        'primaryTextColor' => 'text-emerald-600',
        'totalTextColor' => 'text-slate-900',
        'chartLabel' => 'คิดเป็น',
        'primaryKey' => 'published',
        'secondaryKey' => 'draft',
    ],
    [
        'key' => 'portfolio',
        'title' => 'ผลงานทั้งหมด',
        'unit' => 'โปรเจกต์',
        'primaryLabel' => 'เผยแพร่แล้ว (Published)',
        'secondaryLabel' => 'แบบร่าง (Draft)',
        'primaryColor' => '#10b981',
        'secondaryColor' => '#fbbf24',
        'primaryTextColor' => 'text-emerald-600',
        'totalTextColor' => 'text-slate-900',
        'chartLabel' => 'คิดเป็น',
        'primaryKey' => 'published',
        'secondaryKey' => 'draft',
    ],
    [
        'key' => 'partners',
        'title' => 'พันธมิตรทั้งหมด',
        'unit' => 'รายการ',
        'primaryLabel' => 'แสดงผล (Active)',
        'secondaryLabel' => 'ซ่อน (Hidden)',
        'primaryColor' => '#2563eb',
        'secondaryColor' => '#cbd5e1',
        'primaryTextColor' => 'text-blue-600',
        'totalTextColor' => 'text-slate-900',
        'chartLabel' => 'คิดเป็น',
        'primaryKey' => 'active',
        'secondaryKey' => 'inactive',
    ],
    [
        'key' => 'review',
        'title' => 'รีวิวทั้งหมด',
        'unit' => 'รายการ',
        'primaryLabel' => 'แสดงผล (Active)',
        'secondaryLabel' => 'ซ่อน (Hidden)',
        'primaryColor' => '#8b5cf6',
        'secondaryColor' => '#d1d5db',
        'primaryTextColor' => 'text-violet-600',
        'totalTextColor' => 'text-slate-900',
        'chartLabel' => 'คิดเป็น',
        'primaryKey' => 'active',
        'secondaryKey' => 'inactive',
    ],
];

$recentPortfolio = db()->query(
    'SELECT p.id, p.meta_title, p.client_name, p.created_at
     FROM portfolio p
     ORDER BY p.created_at DESC LIMIT 5'
)->fetchAll();

$recentArticle = db()->query(
    'SELECT a.id, a.meta_title, c.name AS category, a.created_at
     FROM article a
     LEFT JOIN categories c ON c.id = a.category_id
     ORDER BY a.created_at DESC LIMIT 5'
)->fetchAll();
?>

<section class="space-y-4" aria-labelledby="dashboardOverviewTitle">
    <header class="section-header">
        <div>
            <h2 class="section-title text-lg font-semibold" id="dashboardOverviewTitle">ภาพรวมระบบ</h2>
            <p class="section-note text-xs text-slate-500">ภาพรวมสถิติข้อมูลในระบบจัดการเนื้อหา</p>
        </div>
    </header>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($dashboardCards as $card): ?>
            <?php
            $data = $counts[$card['key']];
            $total = (int) $data['total'];
            $primaryValue = (int) $data[$card['primaryKey']];
            $secondaryValue = (int) $data[$card['secondaryKey']];
            $offset = $total > 0 ? 314 - (($primaryValue / $total) * 314) : 314;
            $percent = $total > 0 ? round(($primaryValue / $total) * 100) : 0;
            ?>
            <article class="p-6 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between gap-4">
                <div class="space-y-3 min-w-0">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block"><?= e($card['title']) ?></span>
                        <div class="text-3xl font-extrabold font-mono <?= e($card['totalTextColor']) ?> mt-0.5"><?= $total ?> <span class="text-xs font-normal text-slate-500"><?= e($card['unit']) ?></span></div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-md" style="background-color: <?= e($card['primaryColor']) ?>"></span>
                            <span><?= e($card['primaryLabel']) ?>: <span class="font-bold font-mono text-slate-900"><?= $primaryValue ?></span></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-md" style="background-color: <?= e($card['secondaryColor']) ?>"></span>
                            <span><?= e($card['secondaryLabel']) ?>: <span class="font-bold font-mono text-slate-900"><?= $secondaryValue ?></span></span>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center justify-center w-28 h-28 shrink-0">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" stroke-width="12" stroke="<?= e($card['secondaryColor']) ?>" fill="transparent" />
                        <circle cx="60" cy="60" r="50" stroke-width="12" stroke="<?= e($card['primaryColor']) ?>" fill="transparent"
                            stroke-dasharray="314" stroke-dashoffset="<?= $offset ?>" stroke-linecap="round" class="transition-all duration-500" />
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xs font-bold text-slate-400 block uppercase tracking-tight"><?= e($card['chartLabel']) ?></span>
                        <span class="text-base font-black font-mono <?= e($card['primaryTextColor']) ?>">
                            <?= $percent ?>%
                        </span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>