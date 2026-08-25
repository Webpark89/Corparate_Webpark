<?php
/**
 * Admin partners list — search, filter, and manage partner logos.
 */
$pageTitle = 'Partners Management';
$page = 'partners';
require_once __DIR__ . '/../includes/header.php';
$categories = db()->query('SELECT * FROM partner_categories ORDER BY sort_order ASC')->fetchAll();
$search = trim($_GET['search'] ?? '');
$categoryFilter = $_GET['category_id'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;

$whereSql = '';
$params = [];
if ($search !== '') {
    $whereSql .= ' AND p.name LIKE ?';
    $params[] = "%$search%";
}
if ($categoryFilter !== '') {
    $whereSql .= ' AND p.category_id = ?';
    $params[] = (int) $categoryFilter;
}
if ($statusFilter !== '') {
    $whereSql .= ' AND p.is_active = ?';
    $params[] = (int) $statusFilter;
}

// Total count
$countStmt = db()->prepare('SELECT COUNT(*) FROM partners p LEFT JOIN partner_categories c ON p.category_id = c.id WHERE 1=1' . $whereSql);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

$sql = 'SELECT p.*, c.name AS category_name
        FROM partners p
        LEFT JOIN partner_categories c ON p.category_id = c.id
        WHERE 1=1' . $whereSql . '
        ORDER BY p.sort_order ASC, p.created_at DESC
        LIMIT ' . (int)$pagination['perPage'] . ' OFFSET ' . (int)$pagination['offset'];
$statement = db()->prepare($sql);
$statement->execute($params);
$partners = $statement->fetchAll();
?>
<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการพันธมิตร (Partners)</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการข้อมูลและโลโก้บริษัทของลูกค้าหรือพาร์ทเนอร์</p>
        </div>
        <a href="create.php"
            class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-sm shadow-blue-500/10">
            + เพิ่มโลโก้พาร์ทเนอร์
        </a>
    </header>
    <section class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            <form method="get" class="grid grid-cols-1 gap-3 md:grid-cols-12 items-center">
                <div class="md:col-span-4">
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 focus-within:bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                        <span class="inline-flex items-center border-r border-slate-200 px-3 text-xs text-slate-500 select-none">ค้นหา</span>
                        <input type="text" name="search" placeholder="ค้นหาชื่อบริษัท..." value="<?= e($search) ?>"
                            class="w-full border-0 bg-transparent px-3 py-2 text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                    </div>
                </div>
                <div class="md:col-span-3">
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกหมวดหมู่</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>" <?= $categoryFilter == $category['id'] ? 'selected' : '' ?>>
                                <?= e($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>แสดงผล (Published)</option>
                        <option value="0" <?= $statusFilter === '0' ? 'selected' : '' ?>>ซ่อน (Hidden)</option>
                    </select>
                </div>
                <div class="flex gap-2 md:col-span-2">
                    <button type="submit" class="flex-1 h-8 rounded-xl bg-slate-900 text-xs font-semibold text-white transition hover:bg-slate-800">กรอง</button>
                    <a href="index.php" class="inline-flex flex-1 items-center justify-center h-8 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-600 transition hover:bg-slate-50">ล้าง</a>
                </div>
            </form>
        </div>
    </section>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50/70">
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 select-none">
                        <th class="w-24 px-4 py-3 text-left">รูปโลโก้</th>
                        <th class="px-3 py-3 text-left">คำอธิบายรูปภาพ</th>
                        <th class="px-3 py-3 text-left">ชื่อบริษัท</th>
                        <th class="px-3 py-3 text-left">หมวดหมู่ / ลำดับ</th>
                        <th class="px-3 py-3 text-left">สถานะ</th>
                        <th class="px-3 py-3 text-left">วันที่สร้าง</th>
                        <th class="px-3 py-3 text-left">แก้ไขล่าสุด</th>
                        <th class="px-4 py-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach ($partners as $row): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer js-clickable-row"
                            data-href="edit.php?id=<?= (int) $row['id'] ?>">
                            <td class="px-4 py-3">
                                <div class="h-10 w-20 rounded border border-slate-200 bg-slate-50 flex items-center justify-center p-1 overflow-hidden">
                                    <?php if (!empty($row['image_url'])): ?>
                                        <?php
                                            $logoUrl = resolve_admin_image_url($row['image_url']);
                                        ?>
                                        <img src="<?= e($logoUrl) ?>"
                                             class="w-full h-full object-contain"
                                             alt="<?= e($row['name']) ?>">
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-400">ไม่มีรูป</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-slate-500 text-[11px]">
                                    <?= e($row['image_alt'] ?: '-') ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="font-semibold text-slate-900">
                                    <?= e($row['name']) ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="inline-flex rounded-md border border-slate-200 bg-white px-2 py-0.5 text-[11px] font-medium text-slate-600 shadow-sm">
                                    <?= e($row['category_name'] ?: 'ไม่มีหมวดหมู่') ?>
                                </div>
                                <div class="mt-1 text-[11px] text-slate-400">
                                    ลำดับแสดงผล: <?= (int) $row['sort_order'] ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                        เผยแพร่
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-semibold text-slate-500">
                                        ไม่เผยแพร่
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?= date('d/m/Y', strtotime($row['updated_at'])) ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3 text-right" onclick="event.stopPropagation();">
                                <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                    <a href="edit.php?id=<?= (int) $row['id'] ?>"
                                        class="bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">
                                        แก้ไข
                                    </a>
                                    <form action="toggle_status.php" method="post" class="js-toggle-form">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <input type="hidden" name="status" value="<?= (int) $row['is_active'] ?>">
                                        <?= csrf_field() ?>
                                        <?php if ((int) $row['is_active'] === 0): ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-emerald-600 transition hover:bg-emerald-50 cursor-pointer"
                                            title="แสดงโลโก้นี้บนหน้าเว็บ">
                                            แสดง
                                        </button>
                                        <?php else: ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-100 cursor-pointer"
                                            title="ซ่อนโลโก้นี้จากหน้าเว็บ">
                                            ซ่อน
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                    <button type="button"
                                        onclick="if(confirm('ยืนยันการลบโลโก้พาร์ทเนอร์นี้?')) window.location.href='delete.php?id=<?= (int) $row['id'] ?>'"
                                        class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-rose-600 transition hover:bg-rose-50 cursor-pointer">
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$partners): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">
                                ไม่พบข้อมูลพาร์ทเนอร์ในระบบ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if ($totalRows > 0): 
            $totalPages = (int)$pagination['pages'];
            $currPage = (int)$pagination['current'];
            if ($totalPages <= 7) {
                $pageRange = range(1, $totalPages);
            } elseif ($currPage <= 4) {
                $pageRange = [1, 2, 3, 4, 5, '...', $totalPages];
            } elseif ($currPage >= $totalPages - 3) {
                $pageRange = [1, '...', $totalPages - 4, $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages];
            } else {
                $pageRange = [1, '...', $currPage - 1, $currPage, $currPage + 1, '...', $totalPages];
            }

            $buildPageUrl = function($pageNum) use ($categoryFilter, $statusFilter, $search) {
                $q = ['p' => $pageNum];
                if ($categoryFilter !== '') $q['category_id'] = $categoryFilter;
                if ($statusFilter !== '') $q['status'] = $statusFilter;
                if ($search !== '') $q['search'] = $search;
                return '?' . http_build_query($q);
            };
        ?>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 bg-white px-6 py-4 text-sm text-slate-700 select-none">
                <!-- Pagination Controls -->
                <div class="flex flex-wrap items-center gap-1 sm:gap-1.5">
                    <!-- Previous Button -->
                    <?php if ($currPage > 1): ?>
                        <a href="<?= $buildPageUrl($currPage - 1) ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600 transition px-2 py-1 mr-1">
                            <svg class="w-4 h-4 mr-1 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                    <?php else: ?>
                        <span class="inline-flex items-center text-sm font-medium text-slate-300 pointer-events-none px-2 py-1 mr-1">
                            <svg class="w-4 h-4 mr-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </span>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php foreach ($pageRange as $pItem): ?>
                        <?php if ($pItem === '...'): ?>
                            <span class="inline-flex items-center justify-center w-8 h-8 text-sm text-slate-400 font-medium">...</span>
                        <?php elseif ($pItem === $currPage): ?>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-white text-sm font-semibold shadow-sm" style="background-color: #5046e5; box-shadow: 0 0 0 4px #e0e7ff;">
                                <?= $pItem ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= $buildPageUrl($pItem) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-slate-700 hover:bg-slate-100 hover:text-slate-900 text-sm font-medium transition">
                                <?= $pItem ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Next Button -->
                    <?php if ($currPage < $totalPages): ?>
                        <a href="<?= $buildPageUrl($currPage + 1) ?>" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600 transition px-2 py-1 ml-1">
                            Next
                            <svg class="w-4 h-4 ml-1 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <span class="inline-flex items-center text-sm font-medium text-slate-300 pointer-events-none px-2 py-1 ml-1">
                            Next
                            <svg class="w-4 h-4 ml-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Right Summary -->
                <div class="text-sm text-slate-600 font-normal">
                    Showing <?= count($partners) ?> of <?= number_format($totalRows) ?> results
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.js-clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function(event) {
                if (!event.target.closest('a') && !event.target.closest('button') && !event.target.closest('form')) {
                    const url = this.getAttribute('data-href');
                    if (url) {
                        window.location.href = url;
                    }
                }
            });
        });
        const toggleForms = document.querySelectorAll('.js-toggle-form');
        toggleForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const currentStatus = parseInt(form.querySelector('input[name="status"]').value);
                const action = currentStatus === 0 ? 'แสดงโลโก้พันธมิตรนี้บนหน้าเว็บ' : 'ซ่อนโลโก้พันธมิตรนี้จากหน้าเว็บ';
                if (!confirm(action + '?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
