<?php
/**
 * Admin contact settings list — bulk-edit contact group settings.
 */
$pageTitle = 'จัดการข้อมูลติดต่อ';
$page = 'contact';
require_once __DIR__ . '/../includes/header.php';
$settings = db()->query("SELECT * FROM settings WHERE `group` = 'contact' ORDER BY config_key ASC")->fetchAll();
?>
<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการข้อมูลติดต่อ</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการข้อมูลติดต่อทั้งหมดสำหรับหน้าเว็บไซต์</p>
        </div>
        <a href="create.php?group=contact" class="inline-flex h-9 items-center rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-sm shadow-blue-500/10">
            + เพิ่มรายการติดต่อใหม่
        </a>
    </header>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50/70">
                    <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 select-none">
                        <th class="px-4 py-3 text-left">รายละเอียด (Description)</th>
                        <th class="px-3 py-3 text-left">คีย์อ้างอิง (Key)</th>
                        <th class="px-3 py-3 text-left">ข้อมูล (Value)</th>
                        <th class="px-4 py-3 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($settings)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-xs text-slate-400 border-dashed">ไม่พบข้อมูลติดต่อในระบบ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($settings as $row): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900"><?= e($row['description']) ?></div>
                                </td>
                                <td class="px-3 py-3 text-[11px] text-slate-400 font-mono">
                                    <?= e($row['config_key']) ?>
                                </td>
                                <td class="px-3 py-3 text-slate-600">
                                    <div class="max-w-[260px] truncate"><?= e($row['config_value'] ?: '-') ?></div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                                        <a href="edit.php?key=<?= urlencode($row['config_key']) ?>"
                                            class="bg-white px-4 py-1.5 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">แก้ไข</a>
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
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
