<?php
/**
 * Admin article list — search, filter, and manage articles.
 */
$pageTitle = 'Article Management';
$page = 'article';
require_once __DIR__ . '/../includes/header.php';
$search = trim($_GET['search'] ?? '');
$categoryFilter = $_GET['category_id'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;

// Auto-migrate priority column
try {
    db()->exec('ALTER TABLE article ADD COLUMN priority INT DEFAULT 999 AFTER category_id');
} catch (Exception $e) {
    // Column already exists or other error
}

$whereSql = '';
$params = [];
if ($search !== '') {
    $whereSql .= ' AND (a.meta_title LIKE ? OR a.meta_description LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($categoryFilter !== '') {
    $whereSql .= ' AND a.category_id = ?';
    $params[] = (int) $categoryFilter;
}
if (in_array($statusFilter, ['draft', 'published', 'hidden'], true)) {
    $whereSql .= ' AND a.status = ?';
    $params[] = $statusFilter;
}

// Total count
$countStmt = db()->prepare('SELECT COUNT(*) FROM article a LEFT JOIN categories c ON a.category_id = c.id LEFT JOIN authors aut ON a.author_id = aut.id WHERE 1=1' . $whereSql);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

$sql = 'SELECT a.*, c.name AS category_name, aut.display_name AS author_name
        FROM article a
        LEFT JOIN categories c ON a.category_id = c.id
        LEFT JOIN authors aut ON a.author_id = aut.id
        WHERE 1=1' . $whereSql . '
        ORDER BY a.priority ASC, a.created_at DESC
        LIMIT ' . (int)$pagination['perPage'] . ' OFFSET ' . (int)$pagination['offset'];
$statement = db()->prepare($sql);
$statement->execute($params);
$articles = $statement->fetchAll();
$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
// --- One-time database migration for categories ---
if (count($categories) !== 4 || $categories[0]['name'] !== 'ERP / ERM') {
    try {
        db()->exec('SET FOREIGN_KEY_CHECKS = 0');
        db()->exec('TRUNCATE TABLE categories');
        $stmt = db()->prepare('INSERT INTO categories (id, name, slug) VALUES (?, ?, ?)');
        $stmt->execute([1, 'ERP / ERM', 'erp-erm']);
        $stmt->execute([2, 'Digital Platform', 'digital-platform']);
        $stmt->execute([3, 'Online Marketing', 'online-marketing']);
        $stmt->execute([4, 'Creative / Design', 'creative-design']);
        db()->exec('SET FOREIGN_KEY_CHECKS = 1');
        // Refresh after insert
        $categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
    } catch (Throwable $e) {}
}
// ------------------------------------------------
?>
<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการบทความ</h2>
            <p class="mt-1 text-xs text-slate-500">รายการบทความทั้งหมดในระบบ อัปเดตล่าสุดปี 2026</p>
        </div>
        <a href="create.php"
            class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-300 shadow-sm shadow-blue-500/10">
            + สร้างบทความใหม่
        </a>
    </header>
    <section class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            <form method="get" class="grid grid-cols-1 gap-3 md:grid-cols-12 items-center">
                <div class="md:col-span-4">
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 focus-within:bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                        <span class="inline-flex items-center border-r border-slate-200 px-3 text-xs text-slate-500 select-none">ค้นหา</span>
                        <input type="text" name="search" placeholder="ค้นหาหัวข้อบทความ..." value="<?= e($search) ?>"
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
                        <th class="px-3 py-3 text-left">รายละเอียดบทความ</th>
                        <th class="px-3 py-3 text-left">หมวดหมู่</th>
                        <th class="px-3 py-3 text-left">สถานะ</th>
                        <th class="px-3 py-3 text-left">วันที่สร้าง</th>
                        <th class="px-3 py-3 text-left">แก้ไขล่าสุด</th>
                        <th class="px-4 py-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach ($articles as $row): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer js-clickable-row"
                            data-href="edit.php?id=<?= (int) $row['id'] ?>">
                            <td class="px-4 py-3">
                                <img src="<?= e(resolve_admin_image_url($row['cover_image']) ?: 'https://picsum.photos/seed/' . $row['id'] . '/120/80') ?>"
                                    class="h-10 w-[60px] rounded-lg border border-slate-200 object-cover shadow-sm"
                                    alt="<?= e($row['cover_image_alt']) ?>">
                            </td>
                            <td class="px-3 py-3">
                                <div class="max-w-[280px] truncate font-semibold text-slate-900">
                                    <?= e($row['meta_title'] ?: 'ไม่มีหัวข้อ') ?>
                                </div>
                                <div class="mt-1 max-w-[280px] truncate text-[11px] text-slate-400 font-mono">
                                    /article/<?= e($row['slug']) ?>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                    <?= e($row['category_name']) ?>
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
                                <?php if (isset($row['priority']) && $row['priority'] !== 999): ?>
                                    <span class="inline-flex rounded-lg border border-purple-200 bg-purple-50 px-2.5 py-0.5 text-[11px] font-semibold text-purple-700 ml-1" title="Priority: <?= $row['priority'] ?>">
                                        ★ <?= str_pad((string)$row['priority'], 2, '0', STR_PAD_LEFT) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>
                            <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                <?php if (!empty($row['updated_at'])): ?>
                                    <?= date('d/m/Y H:i', strtotime($row['updated_at'])) ?> น.
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
                                            title="แสดงบทความนี้ต่อสาธารณะ">
                                            แสดง
                                        </button>
                                        <?php else: ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-100 cursor-pointer"
                                            title="ซ่อนบทความจากหน้าเว็บ">
                                            ซ่อน
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                    <form action="delete.php" method="post" class="js-delete-form">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-rose-600 transition hover:bg-rose-50 cursor-pointer">
                                            ลบ
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$articles): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">
                                ไม่พบข้อมูลบทความในระบบ
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
                    Showing <?= count($articles) ?> of <?= number_format($totalRows) ?> results
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
                if (!event.target.closest('a') && !event.target.closest('button')) {
                    const url = this.getAttribute('data-href');
                    if (url) {
                        window.location.href = url;
                    }
                }
            });
        });
        const deleteForms = document.querySelectorAll('.js-delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!confirm('ยืนยันการลบบทความนี้?')) {
                    event.preventDefault();
                }
            });
        });
        const toggleForms = document.querySelectorAll('.js-toggle-form');
        toggleForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                const currentStatus = form.querySelector('input[name="status"]').value;
                const action = currentStatus === 'hidden' ? 'แสดงบทความนี้ให้สาธารณะเห็น' : 'ซ่อนบทความนี้จากหน้าเว็บ';
                if (!confirm(action + '?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>