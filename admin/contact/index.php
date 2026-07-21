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
        <form method="post" action="_save.php" class="divide-y divide-slate-100">
            <?= csrf_field() ?>
            <?php foreach ($settings as $row): ?>
                <div class="grid grid-cols-1 gap-4 px-6 py-4 md:grid-cols-[1fr,1fr,2fr] items-center hover:bg-slate-50/50 transition">
                    <div class="text-sm font-semibold text-slate-800"><?= e($row['description']) ?></div>
                    <div class="text-xs font-mono text-slate-400">
                        <?= e($row['config_key']) ?>
                        <input type="hidden" name="keys[]" value="<?= e($row['config_key']) ?>">
                    </div>
                    <div>
                        <input type="text" name="values[]" value="<?= e($row['config_value']) ?>"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition">
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-8 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition shadow-lg">
                    บันทึก
                </button>
            </div>
        </form>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
