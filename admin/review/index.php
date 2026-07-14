<?php

/**
 * Admin review list — search, filter, and manage customer reviews.
 */
$pageTitle = 'Reviews Management';
$page = 'review';
require_once __DIR__ . '/../includes/header.php';

$search = trim($_GET['search'] ?? '');
$ratingFilter = $_GET['rating'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$sql = 'SELECT * FROM review WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (reviewer_name LIKE ? OR reviewer_company LIKE ? OR content LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($ratingFilter !== '') {
    $sql .= ' AND rating = ?';
    $params[] = (int) $ratingFilter;
}

if ($statusFilter !== '') {
    $sql .= ' AND is_active = ?';
    $params[] = (int) $statusFilter;
}

$sql .= ' ORDER BY sort_order ASC, created_at DESC';
$statement = db()->prepare($sql);
$statement->execute($params);

$reviews = $statement->fetchAll();
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">

    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการรีวิว (Reviews)</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการคำนิยมและรีวิวจากลูกค้าทั้งหมด</p>
        </div>

        <a href="create.php"
            class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white transition hover:bg-blue-700 shadow-sm shadow-blue-500/10">
            + เพิ่มรีวิวใหม่
        </a>
    </header>

    <section class="mb-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            <form method="get" class="grid grid-cols-1 gap-4 md:grid-cols-12 items-center">
                <div class="md:col-span-4">
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50/50 focus-within:bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                        <span class="inline-flex items-center border-r border-slate-200 px-3 text-xs font-medium text-slate-500 select-none bg-slate-50">ค้นหา</span>
                        <input type="text" name="search" placeholder="ชื่อ, บริษัท หรือเนื้อหารีวิว..." value="<?= e($search) ?>"
                            class="w-full border-0 bg-transparent px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                    </div>
                </div>

                <div class="md:col-span-3">
                    <select name="rating" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกคะแนนรีวิว</option>
                        <option value="5" <?= $ratingFilter === '5' ? 'selected' : '' ?>>5 ดาว</option>
                        <option value="4" <?= $ratingFilter === '4' ? 'selected' : '' ?>>4 ดาว</option>
                        <option value="3" <?= $ratingFilter === '3' ? 'selected' : '' ?>>3 ดาว</option>
                        <option value="2" <?= $ratingFilter === '2' ? 'selected' : '' ?>>2 ดาว</option>
                        <option value="1" <?= $ratingFilter === '1' ? 'selected' : '' ?>>1 ดาว</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-sm text-slate-700 focus:bg-white focus:border-blue-500 focus:outline-none transition-all">
                        <option value="">ทุกสถานะ</option>
                        <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>เผยแพร่ (Published)</option>
                        <option value="0" <?= $statusFilter === '0' ? 'selected' : '' ?>>ไม่เผยแพร่ (Hidden)</option>
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
            <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                <thead class="bg-slate-50/80">
                    <tr class="text-xs font-semibold uppercase tracking-wider text-slate-500 select-none">
                        <th class="w-16 px-4 py-4 text-center">รูปภาพ</th>
                        <th class="px-4 py-4">รายละเอียดผู้รีวิว</th>
                        <th class="px-4 py-4 whitespace-nowrap">คะแนน / ลำดับ</th>
                        <th class="px-4 py-4 whitespace-nowrap">สถานะ</th>
                        <th class="px-4 py-4 whitespace-nowrap">วันที่สร้าง</th>
                        <th class="px-4 py-4 whitespace-nowrap">วันที่แก้ไข</th>
                        <th class="px-4 py-4 text-right whitespace-nowrap">การจัดการ</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach ($reviews as $row): ?>
                        <tr class="hover:bg-slate-50/60 transition-colors cursor-pointer js-clickable-row group"
                            data-href="edit.php?id=<?= (int) $row['id'] ?>">

                            <td class="px-4 py-3 text-center">
                                <?php $avatarUrl = !empty($row['reviewer_image_url']) ? $row['reviewer_image_url'] : 'https://ui-avatars.com/api/?name=' . urlencode($row['reviewer_name']) . '&background=random'; ?>
                                <img src="<?= e($avatarUrl) ?>"
                                    class="h-10 w-10 rounded-full border border-slate-200 object-cover shadow-sm ring-2 ring-transparent group-hover:ring-blue-100 transition-all mx-auto"
                                    alt="<?= e($row['reviewer_name']) ?>">
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900 text-sm">
                                    <?= e($row['reviewer_name']) ?>
                                </div>
                                <div class="mt-0.5 max-w-[280px] truncate text-xs text-slate-500">
                                    <?= e($row['reviewer_position'] ?: 'ไม่มีตำแหน่ง') ?> • <?= e($row['reviewer_company'] ?: 'ไม่ระบุบริษัท') ?>
                                </div>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                                    <?= str_repeat('★', (int) $row['rating']) ?><?= str_repeat('☆', 5 - (int) $row['rating']) ?>
                                </div>
                                <div class="mt-1 text-xs text-slate-400">
                                    ลำดับแสดงผล: <?= (int) $row['sort_order'] ?>
                                </div>
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php if ((int) $row['is_active'] === 1): ?>
                                    <span class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        เผยแพร่
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                        ซ่อนอยู่
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3 text-xs text-slate-500 font-mono whitespace-nowrap">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>

                            <td class="px-4 py-3 text-xs text-slate-500 font-mono whitespace-nowrap">
                                <?= date('d/m/Y H:i', strtotime($row['updated_at'])) ?>
                            </td>

                            <td class="px-4 py-3 text-right whitespace-nowrap" onclick="event.stopPropagation();">
                                <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                    <a href="edit.php?id=<?= (int) $row['id'] ?>"
                                        class="bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-blue-600">
                                        แก้ไข
                                    </a>
                                    <form action="toggle_status.php" method="post" class="js-toggle-form">
                                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                        <input type="hidden" name="status" value="<?= (int) $row['is_active'] ?>">
                                        <?= csrf_field() ?>
                                        <?php if ((int) $row['is_active'] === 0): ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50 cursor-pointer"
                                            title="แสดงรีวิวนี้บนหน้าเว็บ">
                                            แสดง
                                        </button>
                                        <?php else: ?>
                                        <button type="submit"
                                            class="border-l border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-slate-100 cursor-pointer"
                                            title="ซ่อนรีวิวนี้จากหน้าเว็บ">
                                            ซ่อน
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                    <button type="button"
                                        onclick="if(confirm('ยืนยันการลบรีวิวนี้?')) window.location.href='delete.php?id=<?= (int) $row['id'] ?>'"
                                        class="border-l border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 cursor-pointer">
                                        ลบ
                                    </button>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$reviews): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center text-sm text-slate-500 border-dashed">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                                </svg>
                                ไม่พบข้อมูลรีวิวในระบบ
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
                const action = currentStatus === 0 ? 'แสดงรีวิวนี้บนหน้าเว็บ' : 'ซ่อนรีวิวนี้จากหน้าเว็บ';
                if (!confirm(action + '?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
