<?php
/**
 * Admin service list — browse and manage website service entries.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
$pageTitle = 'การจัดการบริการ';
$page = 'services';
$services = db()->query('SELECT * FROM service ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/../includes/header.php';
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
                                data-href="edit.php?id=<?= (int) $row['id'] ?>">
                                <td class="px-4 py-3">
                                    <img src="<?= e(resolve_admin_image_url($row['image'])) ?>" class="h-10 w-[60px] rounded-lg border border-slate-200 object-cover shadow-sm" alt="<?= e($row['title']) ?>">
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
                                        <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">เผยแพร่</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-600">
                                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                            ซ่อนอยู่
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right" onclick="event.stopPropagation();">
                                    <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                        <a href="edit.php?id=<?= (int) $row['id'] ?>"
                                            class="bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">แก้ไข</a>
                                        <form action="toggle_status.php" method="post" class="js-toggle-form">
                                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                            <input type="hidden" name="status" value="<?= (int) $row['is_active'] ?>">
                                            <?= csrf_field() ?>
                                            <?php if ((int) $row['is_active'] === 0): ?>
                                            <button type="submit"
                                                class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-emerald-600 transition hover:bg-emerald-50 cursor-pointer"
                                                title="เปิดใช้งานบริการนี้">
                                                แสดง
                                            </button>
                                            <?php else: ?>
                                            <button type="submit"
                                                class="border-l border-slate-200 bg-white px-3 py-1.5 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-100 cursor-pointer"
                                                title="ซ่อนบริการนี้จากหน้าเว็บ">
                                                ซ่อน
                                            </button>
                                            <?php endif; ?>
                                        </form>
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
                const action = currentStatus === 0 ? 'แสดงบริการนี้บนหน้าเว็บ' : 'ซ่อนบริการนี้จากหน้าเว็บ';
                if (!confirm(action + '?')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
