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

// Fetch 10 articles for mock stats to replace dummy text, ordered by priority to match the admin list
$statsArticles = db()->query('SELECT meta_title FROM article ORDER BY priority ASC, created_at DESC LIMIT 10')->fetchAll();
$mockTop5 = [];
$mockBottom5 = [];
$topViews = [450, 320, 210, 150, 90];
$topPercents = [37, 26, 17, 12, 7];
$bottomViews = [12, 18, 25, 30, 45];
$bottomPercents = [9, 14, 19, 23, 35];
$topColors = ['#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6'];
$bottomColors = ['#ef4444', '#f97316', '#facc15', '#a8a29e', '#94a3b8'];

for ($i = 0; $i < 5; $i++) {
    $title = isset($statsArticles[$i]) ? $statsArticles[$i]['meta_title'] : 'Article ' . ($i+1);
    $mockTop5[] = ['title' => $title, 'views' => $topViews[$i], 'percent' => $topPercents[$i], 'color' => $topColors[$i]];
}
for ($i = 0; $i < 5; $i++) {
    $idx = $i + 5;
    $title = isset($statsArticles[$idx]) ? $statsArticles[$idx]['meta_title'] : 'Article ' . ($idx+1);
    $mockBottom5[] = ['title' => $title, 'views' => $bottomViews[$i], 'percent' => $bottomPercents[$i], 'color' => $bottomColors[$i]];
}
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

<!-- โซนกราฟสถิติ (จัดให้พอดีกับ 4 บล็อคด้านบน) -->
<style>
    @media (min-width: 1280px) {
        .xl-col-span-3 { grid-column: span 3 / span 3 !important; }
        .xl-col-span-1 { grid-column: span 1 / span 1 !important; }
    }
</style>
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    
    <!-- ฝั่งซ้าย: กราฟสถิติการเข้าชมเว็บไซต์ (Traffic Chart) กว้างเท่ากับ 3 บล็อค -->
    <section class="md:col-span-1 xl-col-span-3 bg-white border border-slate-200 rounded-2xl shadow-sm p-6 overflow-hidden flex flex-col min-w-0">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">สถิติการเข้าชมเว็บไซต์ (Traffic)</h3>
                <p class="text-xs text-slate-500">ข้อมูลจำลองการเข้าชม (Mock Data)</p>
            </div>
            <select id="timeRangeSelector" class="text-xs border-slate-200 rounded-md bg-slate-50 text-slate-600 px-3 py-1.5 focus:ring-primary focus:border-primary">
                <option value="all">ทั้งหมด</option>
                <option value="7">7 วันล่าสุด</option>
                <option value="30">30 วันล่าสุด</option>
                <option value="365">1 ปีล่าสุด</option>
            </select>
        </div>
        
        <div class="relative w-full flex-1" style="min-height: 350px;">
            <canvas id="trafficChart"></canvas>
        </div>
    </section>

    <!-- ฝั่งขวา: กราฟวงกลม Top 5 และ Bottom 5 กว้างเท่ากับ 1 บล็อค -->
    <section class="md:col-span-1 xl-col-span-1 flex flex-col gap-6 min-w-0">
        <!-- Top 5 -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex-1 flex flex-col justify-center">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-800">5 อันดับบทความยอดฮิต (Top 5)</h3>
                <p class="text-[10px] text-slate-400">บทความที่มีคนเข้าชมมากที่สุด</p>
            </div>
            
            <div class="flex items-center justify-between gap-4">
                <!-- คำอธิบาย (Legend) แบบ Custom อยู่ซ้าย -->
                <div class="flex-1 space-y-2 min-w-0">
                    <?php foreach ($mockTop5 as $stat): ?>
                    <div class="flex items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= e($stat['color']) ?>"></span>
                            <span class="text-slate-600 font-medium truncate"><?= e($stat['title']) ?></span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-bold text-slate-800"><?= $stat['views'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- ตัวกราฟวงกลม อยู่ขวา และมีขนาดเล็กลง -->
                <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px;">
                    <canvas id="top5Chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom 5 -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex-1 flex flex-col justify-center">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-slate-800">5 อันดับยอดชมน้อย (Bottom 5)</h3>
                <p class="text-[10px] text-slate-400">บทความที่ควรปรับปรุงเนื้อหา</p>
            </div>
            
            <div class="flex items-center justify-between gap-4">
                <!-- คำอธิบาย (Legend) แบบ Custom อยู่ซ้าย -->
                <div class="flex-1 space-y-2 min-w-0">
                    <?php foreach ($mockBottom5 as $stat): ?>
                    <div class="flex items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= e($stat['color']) ?>"></span>
                            <span class="text-slate-600 font-medium truncate"><?= e($stat['title']) ?></span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-bold text-slate-800"><?= $stat['views'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- ตัวกราฟวงกลม อยู่ขวา และมีขนาดเล็กลง -->
                <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px;">
                    <canvas id="bottom5Chart"></canvas>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- ดึงไฟล์ Chart.js จากในเครื่อง Server โดยตรง (แก้ปัญหาเน็ต/แอนตี้ไวรัสบล็อก CDN) -->
<script src="<?= ADMIN_URL ?>/assets/js/chart.min.js"></script>
<script>
    const ctx = document.getElementById('trafficChart').getContext('2d');
    
    // สร้าง Gradient สีน้ำเงิน
    const gradientBlue = ctx.createLinearGradient(0, 0, 0, 350);
    gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    // สร้าง Gradient สีฟ้าคราม (Teal)
    const gradientTeal = ctx.createLinearGradient(0, 0, 0, 350);
    gradientTeal.addColorStop(0, 'rgba(6, 182, 212, 0.4)');
    gradientTeal.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

    // สร้าง Gradient สีม่วง (Purple)
    const gradientPurple = ctx.createLinearGradient(0, 0, 0, 350);
    gradientPurple.addColorStop(0, 'rgba(139, 92, 246, 0.4)');
    gradientPurple.addColorStop(1, 'rgba(139, 92, 246, 0.0)');

    // ข้อมูล Mock Data 4 แบบ มีการคละตัวเลขให้สมจริง ไม่ให้เพิ่มขึ้นอย่างเดียว
    const mockData = {
        'all': {
            // ใช้คำว่า "ช่วงที่" แทนวันที่หรือเดือน เพื่อให้ครอบคลุมทั้ง วัน (7), สัปดาห์ (4), และเดือน (12)
            labels: ['ช่วงที่ 1', 'ช่วงที่ 2', 'ช่วงที่ 3', 'ช่วงที่ 4', 'ช่วงที่ 5', 'ช่วงที่ 6', 'ช่วงที่ 7', 'ช่วงที่ 8', 'ช่วงที่ 9', 'ช่วงที่ 10', 'ช่วงที่ 11', 'ช่วงที่ 12'],
            datasets: [
                {
                    label: '7 วันล่าสุด',
                    data: [320, 250, 480, 190, 510, 890, 720],
                    borderColor: '#3b82f6', // สีน้ำเงิน
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: '30 วันล่าสุด',
                    data: [3100, 1850, 4200, 2750],
                    borderColor: '#06b6d4', // สีฟ้าคราม
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#06b6d4',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: '1 ปีล่าสุด',
                    data: [1890, 5000, 3000, 8500, 4200, 9100, 6800, 12500, 7300, 10500, 8200, 14000],
                    borderColor: '#8b5cf6', // สีม่วง
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#8b5cf6',
                    tension: 0.4,
                    fill: false
                }
            ],
            max: 15000
        },
        '7': {
            labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสฯ', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
            datasets: [{
                label: 'ผู้เข้าชมเว็บไซต์ (7 วัน)',
                data: [320, 250, 480, 190, 510, 890, 720],
                borderColor: '#3b82f6', // สีน้ำเงิน
                backgroundColor: gradientBlue,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3b82f6',
                tension: 0.4,
                fill: true
            }],
            max: 1000
        },
        '30': {
            labels: ['สัปดาห์ที่ 1', 'สัปดาห์ที่ 2', 'สัปดาห์ที่ 3', 'สัปดาห์ที่ 4'],
            datasets: [{
                label: 'ผู้เข้าชมเว็บไซต์ (30 วัน)',
                data: [3100, 1850, 4200, 2750], 
                borderColor: '#06b6d4', // สีฟ้าคราม
                backgroundColor: gradientTeal,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#06b6d4',
                tension: 0.4,
                fill: true
            }],
            max: 5000
        },
        '365': {
            labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
            datasets: [{
                label: 'ผู้เข้าชมเว็บไซต์ (1 ปี)',
                data: [1890, 5000, 3000, 8500, 4200, 9100, 6800, 12500, 7300, 10500, 8200, 14000],
                borderColor: '#8b5cf6', // สีม่วง
                backgroundColor: gradientPurple,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#8b5cf6',
                tension: 0.4,
                fill: true
            }],
            max: 15000
        }
    };

    let trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: mockData['all'].labels,
            datasets: mockData['all'].datasets.map(ds => ({ ...ds, data: [...ds.data] }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: { boxWidth: 10, usePointStyle: true, font: { family: 'sans-serif', size: 12, weight: '500' }, padding: 20, color: '#475569' }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 14,
                    titleFont: { size: 13, family: 'sans-serif', weight: 'bold' },
                    bodyFont: { size: 13, family: 'sans-serif' },
                    bodySpacing: 6,
                    cornerRadius: 12,
                    mode: 'index',
                    intersect: false,
                    boxPadding: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: mockData['all'].max,
                    grid: { color: '#f1f5f9', drawBorder: false, borderDash: [5, 5] },
                    ticks: { color: '#94a3b8', font: { size: 11 }, padding: 10 }
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

    // จับเหตุการณ์เมื่อเปลี่ยน Dropdown
    document.getElementById('timeRangeSelector').addEventListener('change', function(e) {
        const type = e.target.value; 
        const data = mockData[type];
        
        // อัปเดตข้อมูลในกราฟ (ใช้ map เพื่อไม่ให้ object Gradient สีพัง)
        trafficChart.data.labels = data.labels;
        trafficChart.data.datasets = data.datasets.map(ds => ({
            ...ds,
            data: [...ds.data]
        }));
        
        // อัปเดตสเกล Y ให้สอดคล้องกับจำนวนข้อมูล
        trafficChart.options.scales.y.max = data.max;
        
        // สั่งให้กราฟวาดตัวเองใหม่
        trafficChart.update();
    });

    // กราฟวงกลม: 5 อันดับยอดฮิต (Top 5)
    new Chart(document.getElementById('top5Chart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['ทำเว็บไซต์องค์กร', 'กลยุทธ์การตลาด', 'ออกแบบโลโก้', 'ยิงแอด Google', 'ทำ SEO เบื้องต้น'],
            datasets: [{
                data: [450, 320, 210, 150, 90],
                backgroundColor: ['#3b82f6', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }, // ซ่อน legend เพราะจะกินพื้นที่ ให้ดูจาก tooltip แทน
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'sans-serif', size: 12 },
                    bodyFont: { family: 'sans-serif', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true
                }
            }
        }
    });

    // กราฟวงกลม: 5 อันดับยอดชมน้อย (Bottom 5)
    new Chart(document.getElementById('bottom5Chart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['การเขียนโค้ดเบื้องต้น', 'ประวัติบริษัทเก่า', 'รวมรูปกิจกรรม', 'นโยบายปี 2021', 'ประกาศรับสมัครงาน'],
            datasets: [{
                data: [12, 18, 25, 30, 45],
                backgroundColor: ['#ef4444', '#f97316', '#facc15', '#a8a29e', '#94a3b8'],
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
                    displayColors: true
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>