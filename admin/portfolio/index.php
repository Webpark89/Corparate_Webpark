<?php
/**
 * Admin portfolio list — search, filter, paginate, and manage portfolio entries.
 */
$pageTitle = 'Portfolio Management';
$page = 'portfolio';
require_once __DIR__ . '/../includes/header.php';
$search = trim($_GET['search'] ?? '');
$categoryFilter = $_GET['category_id'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$current = max(1, (int) ($_GET['p'] ?? 1));
$perPage = 10;
$where = [];
$params = [];
if ($search !== '') {
    $searchLike = "%{$search}%";
    $where[] = '(p.meta_title LIKE ? OR p.client_name LIKE ? OR p.tech_stack LIKE ?)';
    $params[] = $searchLike;
    $params[] = $searchLike;
    $params[] = $searchLike;
}
if ($categoryFilter !== '') {
    $where[] = 'p.category_id = ?';
    $params[] = (int) $categoryFilter;
}
if (in_array($statusFilter, ['draft', 'published', 'hidden'], true)) {
    $where[] = 'p.status = ?';
    $params[] = $statusFilter;
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$totalStatement = db()->prepare("SELECT COUNT(*) FROM portfolio p $whereSql");
$totalStatement->execute($params);
$total = (int) $totalStatement->fetchColumn();
$pagination = paginate($total, $perPage, $current);
$sql = "SELECT p.*, c.name AS category_name, aut.display_name AS author_name
        FROM portfolio p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN authors aut ON p.author_id = aut.id
        $whereSql
        ORDER BY p.created_at DESC
        LIMIT {$pagination['perPage']} OFFSET {$pagination['offset']}";
$statement = db()->prepare($sql);
$statement->execute($params);
$portfolios = $statement->fetchAll();
$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
?>
<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการผลงาน (Portfolio)</h2>
            <p class="mt-1 text-xs text-slate-500">รายการผลงานโครงการทั้งหมดของบริษัท อัปเดตล่าสุดปี 2026</p>
        </div>
        <a href="create.php"
            class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-sm shadow-blue-500/10">
            + สร้างผลงานใหม่
        </a>
    </header>
    <section class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            <form method="get" class="grid grid-cols-1 gap-3 md:grid-cols-12 items-center">
                <div class="md:col-span-4">
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 focus-within:bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                        <span class="inline-flex items-center border-r border-slate-200 px-3 text-xs text-slate-500 select-none">ค้นหา</span>
                        <input type="text" name="search" placeholder="ชื่อผลงาน, ลูกค้า, เทคโนโลยี..." value="<?= e($search) ?>"
                            class="w-full border-0 bg-transparent px-3 py-2 text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                    </div>
                </div>
                <div class="md:col-span-3">
                    <select name="category_id" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกหมวดหมู่</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>" <?= $categoryFilter == $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกสถานะ</option>
                        <option value="published" <?= $statusFilter === 'published' ? 'selected' : '' ?>>เผยแพร่แล้ว</option>
                        <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : '' ?>>ฉบับร่าง</option>
                        <option value="hidden" <?= $statusFilter === 'hidden' ? 'selected' : '' ?>>ซ่อนอยู่</option>
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
                        <th class="w-20 px-4 py-3 text-left">รูปภาพ</th>
                        <th class="px-3 py-3 text-left">รายละเอียดผลงาน</th>
                        <th class="px-3 py-3 text-left">ลูกค้า / เทคโนโลยี</th>
                        <th class="px-3 py-3 text-left">หมวดหมู่</th>
                        <th class="px-3 py-3 text-left">สถานะ</th>
                        <th class="px-3 py-3 text-left">วันที่สร้าง</th>
                        <th class="px-3 py-3 text-left">วันที่อัปเดต</th>
                        <th class="px-4 py-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach ($portfolios as $row): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer js-clickable-row"
                            data-href="edit.php?id=<?= (int) $row['id'] ?>">
                            <td class="px-4 py-3">
                                <img src="<?= e(resolve_admin_image_url($row['cover_image']) ?: 'https://picsum.photos/seed/p' . $row['id'] . '/120/80') ?>"
                                    class="h-10 w-[60px] rounded-lg border border-slate-200 object-cover shadow-sm"
                                    alt="<?= e($row['cover_image_alt'] ?? '') ?>">
                            </td>
                            <td class="px-3 py-3">
                                <div class="max-w-[200px] truncate font-semibold text-slate-900">
                                    <?= e($row['meta_title'] ?: 'ไม่มีชื่อผลงาน') ?>
                                </div>
                                <div class="mt-1 max-w-[200px] truncate text-[11px] text-slate-400 font-mono">
                                    /portfolio/<?= e($row['slug']) ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="max-w-[150px] truncate font-medium text-slate-700">
                                    <?= e($row['client_name'] ?: 'ไม่ระบุลูกค้า') ?>
                                </div>
                                <div class="mt-1 max-w-[150px] truncate text-[10px] text-slate-500">
                                    <?= e($row['tech_stack'] ?: '-') ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                    <?= e($row['category_name'] ?? 'ไม่มีหมวดหมู่') ?>
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <?php if ($row['status'] === 'published'): ?>
                                    <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                        เผยแพร่
                                    </span>
                                <?php elseif ($row['status'] === 'hidden'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        ซ่อนอยู่
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700">
                                        ฉบับร่าง
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?php if (!empty($row['updated_at'])): ?>
                                    <?= date('d/m/Y H:i', strtotime($row['updated_at'])) ?>
                                <?php else: ?>
                                    <span class="text-slate-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right" onclick="event.stopPropagation();">
                                <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                    <a href="edit.php?id=<?= (int) $row['id'] ?>"
                                        class="bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">
                                        แก้ไข
                                    </a>
                                    <form action="toggle_status.php" method="post" class="js-toggle-form">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <input type="hidden" name="status" value="<?= e($row['status']) ?>">
                                        <?= csrf_field() ?>
                                        <?php if ($row['status'] === 'hidden'): ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-emerald-600 transition hover:bg-emerald-50 cursor-pointer"
                                            title="แสดงผลงานนี้ต่อสาธารณะ">
                                            แสดง
                                        </button>
                                        <?php else: ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-100 cursor-pointer"
                                            title="ซ่อนผลงานนี้จากหน้าเว็บ">
                                            ซ่อน
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                    <button type="button"
                                        onclick="if(confirm('ยืนยันการลบผลงานนี้?')) window.location.href='delete.php?id=<?= (int) $row['id'] ?>'"
                                        class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-rose-600 transition hover:bg-rose-50 cursor-pointer">
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$portfolios): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">
                                ไม่พบข้อมูลผลงานในระบบ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php if ($pagination['pages'] > 1): ?>
        <nav class="mt-4 flex justify-center" aria-label="Pagination">
            <ul class="inline-flex items-center gap-1">
                <?php for ($pageNumber = 1; $pageNumber <= $pagination['pages']; $pageNumber++):
                    $queryString = http_build_query(array_filter([
                        'search' => $search,
                        'category_id' => $categoryFilter,
                        'status' => $statusFilter,
                        'p' => $pageNumber,
                    ], static fn($value) => $value !== null && $value !== '')); ?>
                    <li>
                        <a href="?<?= e($queryString) ?>"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition-colors <?= $pageNumber === $pagination['current'] ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $pageNumber ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
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
                const currentStatus = form.querySelector('input[name="status"]').value;
                const action = currentStatus === 'hidden' ? 'แสดงผลงานนี้ให้สาธารณะเห็น' : 'ซ่อนผลงานนี้จากหน้าเว็บ';
                if (!confirm(action + '?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
