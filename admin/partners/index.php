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

$sql = 'SELECT p.*, c.name AS category_name
        FROM partners p
        LEFT JOIN partner_categories c ON p.category_id = c.id
        WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND p.name LIKE ?';
    $params[] = "%$search%";
}

if ($categoryFilter !== '') {
    $sql .= ' AND p.category_id = ?';
    $params[] = (int) $categoryFilter;
}

if ($statusFilter !== '') {
    $sql .= ' AND p.is_active = ?';
    $params[] = (int) $statusFilter;
}

$sql .= ' ORDER BY p.sort_order ASC, p.created_at DESC';
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
                                        $logoUrl = '';
                                        if (preg_match('#^https?://#i', $row['image_url'])) {
                                            $logoUrl = $row['image_url'];
                                        } elseif (str_contains($row['image_url'], '/')) {
                                            $logoUrl = SITE_URL . '/admin/' . ltrim($row['image_url'], '/');
                                        } else {
                                            $logoUrl = upload_url($row['image_url']);
                                        }
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
                            <td colspan="6" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">
                                ไม่พบข้อมูลพาร์ทเนอร์ในระบบ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
