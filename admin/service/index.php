<?php
$pageTitle = 'การจัดการบริการ';
$page = 'services';
require_once __DIR__ . '/../includes/header.php';

// ดึงข้อมูลเรียงตามวันที่ล่าสุด (ไม่ต้องใช้ sort_order หรือ category)
$services = db()->query("SELECT * FROM service ORDER BY created_at DESC")->fetchAll();
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">

    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการบริการ</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการบริการต่างๆ ที่แสดงบนหน้าเว็บไซต์</p>
        </div>

        <a href="create.php"
            class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-sm shadow-blue-500/10">
            + เพิ่มบริการใหม่
        </a>
    </header>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50/70">
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 select-none">
                        <th class="w-20 px-4 py-3 text-left">รูปภาพ</th>
                        <th class="px-3 py-3 text-left">รายละเอียดบริการ</th>
                        <th class="px-3 py-3 text-left">จำนวนฟีเจอร์</th>
                        <th class="px-3 py-3 text-left">สถานะ</th>
                        <th class="px-4 py-3 text-right">จัดการ</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($services)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">ไม่พบข้อมูลบริการในระบบ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($services as $row):
                            $details = json_decode($row['details_json'], true);
                            $features = $details['features'] ?? [];
                        ?>
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer js-clickable-row"
                                data-href="edit.php?id=<?= (int)$row['id'] ?>">

                                <td class="px-4 py-3">
                                    <img src="<?= e($row['image']) ?>" class="h-10 w-[60px] rounded-lg border border-slate-200 object-cover shadow-sm" alt="<?= e($row['title']) ?>">
                                </td>

                                <td class="px-3 py-3">
                                    <div class="max-w-[260px] truncate font-semibold text-slate-900"><?= e($row['title'] ?: 'ไม่มีชื่อบริการ') ?></div>
                                    <div class="mt-1 max-w-[260px] truncate text-[11px] text-slate-400 font-mono">/service/<?= e($row['slug']) ?></div>
                                </td>

                                <td class="px-3 py-3 text-[11px] text-slate-500 font-mono">
                                    <?= count($features) ?> รายการ
                                </td>

                                <td class="px-3 py-3">
                                    <?php if ($row['is_active']): ?>
                                        <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">แสดง</span>
                                    <?php else: ?>
                                        <span class="inline-flex rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-500">ซ่อน</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 text-right" onclick="event.stopPropagation();">
                                    <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                            class="bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">แก้ไข</a>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-clickable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('a') && !e.target.closest('button')) {
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