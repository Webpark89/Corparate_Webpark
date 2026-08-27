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
// Fetch Real Top 5 and Bottom 5 Articles based on views
$topColors = ['#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6'];
$bottomColors = ['#ef4444', '#f97316', '#facc15', '#a8a29e', '#94a3b8'];

$top5Rows = db()->query(
    "SELECT id, meta_title, views 
     FROM article 
     WHERE (status = 'published' OR status IS NULL) AND deleted_at IS NULL 
     ORDER BY views DESC, priority ASC, created_at DESC 
     LIMIT 5"
)->fetchAll();

$sumTop5Views = array_sum(array_column($top5Rows, 'views'));
$top5Stats = [];
foreach ($top5Rows as $i => $row) {
    $views = (int) ($row['views'] ?? 0);
    $percent = $sumTop5Views > 0 ? round(($views / $sumTop5Views) * 100) : 20;
    $top5Stats[] = [
        'title' => $row['meta_title'] ?: 'บทความ #' . ($row['id'] ?? ($i + 1)),
        'views' => $views,
        'percent' => $percent,
        'color' => $topColors[$i] ?? '#94a3b8',
    ];
}

$bottom5Rows = db()->query(
    "SELECT id, meta_title, views 
     FROM article 
     WHERE (status = 'published' OR status IS NULL) AND deleted_at IS NULL 
     ORDER BY views ASC, priority ASC, created_at ASC 
     LIMIT 5"
)->fetchAll();

$sumBottom5Views = array_sum(array_column($bottom5Rows, 'views'));
$bottom5Stats = [];
foreach ($bottom5Rows as $i => $row) {
    $views = (int) ($row['views'] ?? 0);
    $percent = $sumBottom5Views > 0 ? round(($views / $sumBottom5Views) * 100) : 20;
    $bottom5Stats[] = [
        'title' => $row['meta_title'] ?: 'บทความ #' . ($row['id'] ?? ($i + 1)),
        'views' => $views,
        'percent' => $percent,
        'color' => $bottomColors[$i] ?? '#94a3b8',
    ];
}

// Fetch Real Traffic Data (7 Days, 30 Days, 1 Year)
$thaiDays = ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];
$thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];

// 7 Days
$last7Days = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $w = (int) date('w', strtotime($d));
    $last7Days[$d] = [
        'label' => ($thaiDays[$w] ?? '') . ' (' . date('d/m', strtotime($d)) . ')',
        'views' => 0,
        'unique' => 0,
    ];
}
$rows7 = db()->query("SELECT date, pageviews, unique_visitors FROM daily_traffic WHERE date >= CURDATE() - INTERVAL 6 DAY ORDER BY date ASC")->fetchAll();
foreach ($rows7 as $r) {
    if (isset($last7Days[$r['date']])) {
        $last7Days[$r['date']]['views'] = (int) $r['pageviews'];
        $last7Days[$r['date']]['unique'] = (int) ($r['unique_visitors'] ?? 0);
    }
}
$traffic7Labels = array_column(array_values($last7Days), 'label');
$traffic7Pageviews = array_column(array_values($last7Days), 'views');
$traffic7Unique = array_column(array_values($last7Days), 'unique');

// 30 Days (4 Weeks)
$weeks = [
    'สัปดาห์ที่ 1' => ['views' => 0, 'unique' => 0],
    'สัปดาห์ที่ 2' => ['views' => 0, 'unique' => 0],
    'สัปดาห์ที่ 3' => ['views' => 0, 'unique' => 0],
    'สัปดาห์ที่ 4' => ['views' => 0, 'unique' => 0],
];
$rows30 = db()->query("SELECT date, pageviews, unique_visitors FROM daily_traffic WHERE date >= CURDATE() - INTERVAL 28 DAY ORDER BY date ASC")->fetchAll();
$todayTs = strtotime(date('Y-m-d'));
foreach ($rows30 as $r) {
    $diffDays = (int) floor(($todayTs - strtotime($r['date'])) / 86400);
    $weekIdx = 4 - min(3, (int) floor($diffDays / 7));
    $weekKey = 'สัปดาห์ที่ ' . $weekIdx;
    if (isset($weeks[$weekKey])) {
        $weeks[$weekKey]['views'] += (int) $r['pageviews'];
        $weeks[$weekKey]['unique'] += (int) ($r['unique_visitors'] ?? 0);
    }
}
$traffic30Labels = array_keys($weeks);
$traffic30Pageviews = array_column(array_values($weeks), 'views');
$traffic30Unique = array_column(array_values($weeks), 'unique');

// 1 Year (12 Months)
$last12Months = [];
for ($i = 11; $i >= 0; $i--) {
    $ym = date('Y-m', strtotime("-$i months"));
    $mIndex = (int) date('n', strtotime($ym . '-01')) - 1;
    $last12Months[$ym] = ['label' => $thaiMonths[$mIndex], 'views' => 0, 'unique' => 0];
}
$rows12m = db()->query("SELECT DATE_FORMAT(date, '%Y-%m') AS ym, SUM(pageviews) AS total_views, SUM(unique_visitors) AS total_unique FROM daily_traffic WHERE date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) GROUP BY ym ORDER BY ym ASC")->fetchAll();
foreach ($rows12m as $r) {
    if (isset($last12Months[$r['ym']])) {
        $last12Months[$r['ym']]['views'] = (int) $r['total_views'];
        $last12Months[$r['ym']]['unique'] = (int) ($r['total_unique'] ?? 0);
    }
}
$traffic365Labels = array_column(array_values($last12Months), 'label');
$traffic365Pageviews = array_column(array_values($last12Months), 'views');
$traffic365Unique = array_column(array_values($last12Months), 'unique');
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
<style>
    @media (min-width: 1280px) {
        .xl-col-span-3 { grid-column: span 3 / span 3 !important; }
        .xl-col-span-1 { grid-column: span 1 / span 1 !important; }
    }
</style>
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    <section class="md:col-span-1 xl-col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm p-6 overflow-hidden flex flex-col min-w-0">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">สถิติการเข้าชมเว็บไซต์ (Traffic)</h3>
                <p class="text-xs text-slate-500">สถิติการเข้าชมจริงจากระบบ (Live Analytics)</p>
            </div>
            <select id="timeRangeSelector" class="text-xs border-slate-200 rounded-md bg-slate-50 text-slate-600 px-3 py-1.5 focus:ring-primary focus:border-primary">
                <option value="7">7 วันล่าสุด</option>
                <option value="30">30 วันล่าสุด</option>
                <option value="365">1 ปีล่าสุด</option>
            </select>
        </div>
        <div class="relative w-full flex-1" style="min-height: 350px;">
            <canvas id="trafficChart"></canvas>
        </div>
    </section>
    <section class="md:col-span-1 xl-col-span-1 flex flex-col gap-6 min-w-0">
        <!-- Top 5 -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex-1 flex flex-col justify-center">
            <div class="mb-4 ml-2">
                <h3 class="text-sm font-bold text-slate-800">5 อันดับบทความยอดฮิต (Top 5)</h3>
                <p class="text-[10px] text-slate-400">บทความที่มีคนเข้าชมมากที่สุด</p>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1 space-y-2 min-w-0">
                    <?php if (empty($top5Stats)): ?>
                        <p class="text-xs text-slate-400">ยังไม่มีข้อมูลบทความ</p>
                    <?php else: ?>
                        <?php foreach ($top5Stats as $stat): ?>
                        <div class="flex items-center justify-between text-xs gap-2">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= e($stat['color']) ?>"></span>
                                <span class="text-slate-600 font-medium truncate" title="<?= e($stat['title']) ?>"><?= e($stat['title']) ?></span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-slate-800"><?= number_format($stat['views']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px;">
                    <canvas id="top5Chart"></canvas>
                </div>
            </div>
        </div>
        <!-- Bottom 5 -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex-1 flex flex-col justify-center">
            <div class="mb-4 ml-2">
                <h3 class="text-sm font-bold text-slate-800">5 อันดับยอดชมน้อย (Bottom 5)</h3>
                <p class="text-[10px] text-slate-400">บทความที่ควรปรับปรุงเนื้อหา</p>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1 space-y-2 min-w-0">
                    <?php if (empty($bottom5Stats)): ?>
                        <p class="text-xs text-slate-400">ยังไม่มีข้อมูลบทความ</p>
                    <?php else: ?>
                        <?php foreach ($bottom5Stats as $stat): ?>
                        <div class="flex items-center justify-between text-xs gap-2">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= e($stat['color']) ?>"></span>
                                <span class="text-slate-600 font-medium truncate" title="<?= e($stat['title']) ?>"><?= e($stat['title']) ?></span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-slate-800"><?= number_format($stat['views']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px;">
                    <canvas id="bottom5Chart"></canvas>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="<?= ADMIN_URL ?>/assets/js/chart.min.js"></script>
<script>
    const ctx = document.getElementById('trafficChart').getContext('2d');
    
    // Gradients
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 350);
    gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    const gradientEmerald = ctx.createLinearGradient(0, 0, 0, 350);
    gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    // Real Traffic Data from PHP (Pageviews & Unique Visitors)
    const realTraffic = {
        '7': {
            labels: <?= json_encode($traffic7Labels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [
                {
                    label: 'ยอดเปิดหน้าเว็บรวม (Pageviews)',
                    data: <?= json_encode($traffic7Pageviews) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: gradientBlue,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'ผู้เข้าชมจริง (Unique Visitors)',
                    data: <?= json_encode($traffic7Unique) ?>,
                    borderColor: '#10b981',
                    backgroundColor: gradientEmerald,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointHoverBackgroundColor: '#10b981',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                }
            ]
        },
        '30': {
            labels: <?= json_encode($traffic30Labels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [
                {
                    label: 'ยอดเปิดหน้าเว็บรวม (Pageviews)',
                    data: <?= json_encode($traffic30Pageviews) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: gradientBlue,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'ผู้เข้าชมจริง (Unique Visitors)',
                    data: <?= json_encode($traffic30Unique) ?>,
                    borderColor: '#10b981',
                    backgroundColor: gradientEmerald,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointHoverBackgroundColor: '#10b981',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                }
            ]
        },
        '365': {
            labels: <?= json_encode($traffic365Labels, JSON_UNESCAPED_UNICODE) ?>,
            datasets: [
                {
                    label: 'ยอดเปิดหน้าเว็บรวม (Pageviews)',
                    data: <?= json_encode($traffic365Pageviews) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: gradientBlue,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                },
                {
                    label: 'ผู้เข้าชมจริง (Unique Visitors)',
                    data: <?= json_encode($traffic365Unique) ?>,
                    borderColor: '#10b981',
                    backgroundColor: gradientEmerald,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointHoverBackgroundColor: '#10b981',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.35,
                    fill: true
                }
            ]
        }
    };

    const getOptimalMax = (datasets) => {
        let maxVal = 0;
        if (Array.isArray(datasets)) {
            datasets.forEach(ds => {
                if (Array.isArray(ds.data)) {
                    const m = Math.max(...ds.data, 0);
                    if (m > maxVal) maxVal = m;
                }
            });
        }
        return maxVal > 0 ? Math.ceil(maxVal * 1.25) : 10;
    };

    let trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: realTraffic['7'].labels,
            datasets: realTraffic['7'].datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { family: 'sans-serif', size: 12, weight: '500' },
                        padding: 16,
                        color: '#475569'
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 14,
                    titleFont: { size: 13, family: 'sans-serif', weight: 'bold' },
                    bodyFont: { size: 12, family: 'sans-serif' },
                    bodySpacing: 8,
                    cornerRadius: 12,
                    mode: 'index',
                    intersect: false,
                    boxPadding: 6,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.dataset.label + ': ' + Number(context.parsed.y).toLocaleString() + ' ครั้ง';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: getOptimalMax(realTraffic['7'].datasets),
                    grid: { color: '#f1f5f9', drawBorder: false, borderDash: [5, 5] },
                    ticks: { color: '#94a3b8', font: { size: 11 }, padding: 10, precision: 0 }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#64748b', font: { size: 11 }, padding: 10 }
                }
            },
            interaction: { mode: 'index', intersect: false },
            elements: { 
                line: { borderJoinStyle: 'round' },
                point: { pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 }
            }
        }
    });

    document.getElementById('timeRangeSelector').addEventListener('change', function(e) {
        const type = e.target.value; 
        const selected = realTraffic[type];
        if (!selected) return;
        trafficChart.data.labels = selected.labels;
        trafficChart.data.datasets = selected.datasets.map(ds => ({ ...ds }));
        trafficChart.options.scales.y.suggestedMax = getOptimalMax(selected.datasets);
        trafficChart.update();
    });

    // Top 5 Real Data
    const top5Labels = <?= json_encode(array_column($top5Stats, 'title'), JSON_UNESCAPED_UNICODE) ?>;
    const top5Data = <?= json_encode(array_column($top5Stats, 'views')) ?>;
    const top5Colors = <?= json_encode(array_column($top5Stats, 'color')) ?>;
    const isTop5Empty = top5Data.length === 0 || top5Data.every(v => v === 0);

    new Chart(document.getElementById('top5Chart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: isTop5Empty ? ['ยังไม่มีผู้เข้าชม'] : top5Labels,
            datasets: [{
                data: isTop5Empty ? [1] : top5Data,
                backgroundColor: isTop5Empty ? ['#e2e8f0'] : top5Colors,
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'sans-serif', size: 12 },
                    bodyFont: { family: 'sans-serif', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            if (isTop5Empty) return 'ยังไม่มีผู้เข้าชม';
                            return context.label + ': ' + context.parsed + ' ครั้ง';
                        }
                    }
                }
            }
        }
    });

    // Bottom 5 Real Data
    const bottom5Labels = <?= json_encode(array_column($bottom5Stats, 'title'), JSON_UNESCAPED_UNICODE) ?>;
    const bottom5Data = <?= json_encode(array_column($bottom5Stats, 'views')) ?>;
    const bottom5Colors = <?= json_encode(array_column($bottom5Stats, 'color')) ?>;
    const isBottom5Empty = bottom5Data.length === 0 || bottom5Data.every(v => v === 0);

    new Chart(document.getElementById('bottom5Chart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: isBottom5Empty ? ['ยังไม่มีผู้เข้าชม'] : bottom5Labels,
            datasets: [{
                data: isBottom5Empty ? [1] : bottom5Data,
                backgroundColor: isBottom5Empty ? ['#e2e8f0'] : bottom5Colors,
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'sans-serif', size: 12 },
                    bodyFont: { family: 'sans-serif', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            if (isBottom5Empty) return 'ยังไม่มีผู้เข้าชม';
                            return context.label + ': ' + context.parsed + ' ครั้ง';
                        }
                    }
                }
            }
        }
    });
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>