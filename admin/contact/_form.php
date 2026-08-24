<?php
/**
 * Shared contact setting create/edit form partial.
 */
$existingGroups = db()->query('SELECT DISTINCT `group` FROM settings ORDER BY `group` ASC')->fetchAll(PDO::FETCH_COLUMN);
$data = $setting ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
$isEdit = ($action === 'edit');
?>

<div class="mx-auto w-full max-w-4xl px-3 pb-12 pt-2 text-sm md:px-6">
    <!-- Breadcrumb / Back Link -->
    <div class="mb-4">
        <a href="index.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            กลับสู่หน้ารายการข้อมูลติดต่อ
        </a>
    </div>

    <!-- Main Card -->
    <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-sm">
        <!-- Card Header -->
        <div class="border-b border-slate-100 bg-slate-50/60 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        <?= $isEdit ? 'แก้ไขการตั้งค่า: ' . e($data['description'] ?: $data['config_key']) : 'เพิ่มรายการตั้งค่าใหม่' ?>
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        <?= $isEdit ? 'ปรับปรุงค่าตัวแปรและการกำหนดค่าในระบบ' : 'สร้างตัวแปรและการตั้งค่าใหม่สำหรับเว็บไซต์' ?>
                    </p>
                </div>
                <?php if ($isEdit): ?>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-mono font-bold text-blue-700 border border-blue-100">
                        <?= e($data['config_key'] ?? '') ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form Content -->
        <form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            <?= csrf_field() ?>

            <!-- Section 1: Basic Information (2 Columns) -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. ข้อมูลตัวแปรระบบ (System Keys)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Config Key -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            ชื่อตัวแปร (Config Key) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="config_key" value="<?= e($data['config_key'] ?? '') ?>"
                            placeholder="เช่น mail_to, contact_phone"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-mono text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 <?= $isEdit ? 'bg-slate-100 text-slate-500 cursor-not-allowed' : 'bg-white' ?>"
                            <?= $isEdit ? 'readonly' : 'required' ?>>
                        <p class="mt-1 text-[11px] text-slate-400">ใช้ตัวพิมพ์เล็ก ขีดล่าง หรือตัวเลข</p>
                    </div>

                    <!-- Group -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            หมวดหมู่ (Group) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="group" value="<?= e($data['group'] ?? 'contact') ?>" list="group-list"
                            placeholder="เช่น contact, general"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-medium text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            required>
                        <datalist id="group-list">
                            <?php foreach ($existingGroups as $grp): ?>
                                <option value="<?= e($grp) ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <p class="mt-1 text-[11px] text-slate-400">เลือกจากหมวดหมู่เดิม หรือพิมพ์ชื่อใหม่</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Value & Description -->
            <div class="space-y-4 pt-2">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. ข้อมูลและการแสดงผล (Value & Description)</h3>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        คำอธิบาย / หมายเหตุ (Description)
                    </label>
                    <input type="text" name="description" value="<?= e($data['description'] ?? '') ?>"
                        placeholder="เช่น Notification recipient email, เบอร์โทรศัพท์ฝ่ายขาย"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                    <p class="mt-1 text-[11px] text-slate-400">คำอธิบายเพื่อช่วยให้จดจำหน้าที่ของตัวแปรนี้ได้ง่าย</p>
                </div>

                <!-- Config Value -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        ข้อมูลค่าที่ตั้งไว้ (Config Value) <span class="text-red-500">*</span>
                    </label>
                    <textarea id="configValueArea" name="config_value" rows="3"
                        placeholder="ระบุข้อความ, อีเมล, ตัวเลข, URL หรือ JSON..."
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-xs font-mono text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 resize-y"><?= e($data['config_value'] ?? '') ?></textarea>
                    <p class="mt-1 text-[11px] text-slate-400">ค่าของตัวแปรที่จะนำไปใช้งานในเว็บไซต์</p>
                </div>
            </div>

            <!-- Section 3: Optional File Attachment -->
            <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-700">แนบไฟล์รูปภาพ / เอกสาร (ไม่บังคับ)</span>
                        <p class="text-[11px] text-slate-400">ใช้เฉพาะเมื่อต้องการให้ตัวแปรนี้เก็บเป็นไฟล์ (เช่น โลโก้, รูปภาพ, หรือ PDF)</p>
                    </div>
                </div>
                <div class="pt-1">
                    <input type="file" id="configFileInput" name="config_file"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-slate-700 file:border-slate-200 file:shadow-xs hover:file:bg-slate-100 cursor-pointer">
                </div>
                <div id="imagePreviewContainer" class="mt-2 hidden"></div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="index.php" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 shadow-sm whitespace-nowrap">
                    ยกเลิก
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-xs font-bold text-white transition hover:bg-blue-700 shadow-md shadow-blue-500/10 hover:shadow-lg hover:-translate-y-0.5 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    บันทึกการตั้งค่า
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('configFileInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const valueTextArea = document.getElementById('configValueArea');

    if (fileInput && previewContainer) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                valueTextArea.value = `[ระบบจะอัปโหลดไฟล์: ${file.name}]`;
                valueTextArea.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-300');
                
                if (file.type.startsWith('image/') || file.name.endsWith('.ico') || file.name.endsWith('.svg')) {
                    const reader = new FileReader();
                    reader.onload = function(loadEvent) {
                        previewContainer.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = loadEvent.target.result;
                        img.className = 'max-h-32 object-contain p-2 bg-white rounded-xl border border-slate-200 shadow-xs';
                        previewContainer.appendChild(img);
                        previewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            }
        });
    }
});
</script>