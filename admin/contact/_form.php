<?php
/**
 * Shared contact setting create/edit form partial.
 */
$existingGroups = db()->query('SELECT DISTINCT `group` FROM settings ORDER BY `group` ASC')->fetchAll(PDO::FETCH_COLUMN);
$data = $setting ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
$inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all';
?>
<section class="mx-auto max-w-4xl px-4 py-8">
    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="px-6 py-5 border-b">
            <h3 class="font-semibold text-slate-900 border-l-4 border-blue-500 pl-4">จัดการการตั้งค่าการติดต่อ</h3>
            <p class="text-xs text-slate-500 mt-1">เพิ่มหรือแก้ไขคอนฟิกการติดต่อ</p>
        </div>
        <div class="p-6">
            <form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data" class="space-y-8">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <h4 class="font-bold text-slate-900">ข้อมูลพื้นฐาน</h4>
                        <p class="text-xs text-slate-500 mt-1">ตั้งค่าชื่อและประเภท</p>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-2">ชื่อตัวแปร</label>
                            <input type="text" name="config_key" value="<?= e($data['config_key'] ?? '') ?>"
                                class="<?= $inputClass ?> font-mono <?= ($action === 'edit') ? 'bg-slate-100' : '' ?>"
                                <?= ($action === 'edit') ? 'readonly' : 'required' ?>>
                            <p class="text-[10px] text-slate-400 mt-1">ใช้ตัวอักษรเล็ก ขีด หรือตัวเลข เช่น contact_phone, contact_email</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-2">ประเภท</label>
                            <input type="text" name="group"
                                value="<?= e($data['group'] ?? 'general') ?>"
                                list="group-list"
                                class="<?= $inputClass ?>"
                                required>
                            <datalist id="group-list">
                                <?php foreach ($existingGroups as $group): ?>
                                    <option value="<?= e($group) ?>">
                                    <?php endforeach; ?>
                            </datalist>
                            <p class="text-[10px] text-slate-400 mt-1">พิมพ์เพื่อสร้างหมวดหมู่ใหม่ หรือเลือกจากรายการที่มี</p>
                        </div>
                    </div>
                </div>
                <hr class="border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <h4 class="font-bold text-slate-900">เนื้อหา</h4>
                        <p class="text-xs text-slate-500 mt-1">ระบุรายละเอียดและข้อมูล</p>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-2">หมายเหตุ</label>
                            <input type="text" name="description" value="<?= e($data['description'] ?? '') ?>" class="<?= $inputClass ?>">
                            <p class="text-[10px] text-slate-400 mt-1">คำอธิบายสั้นๆ เกี่ยวกับรายการนี้ (ไม่บังคับ)</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-700 block mb-2">ข้อมูล</label>
                            <textarea name="config_value" rows="5" class="<?= $inputClass ?> font-mono"><?= e($data['config_value'] ?? '') ?></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">เบอร์โทร, อีเมล, URL, JSON, หรือข้อความอื่นๆ</p>
                        </div>
                        <div id="imagePreviewContainer" class="mt-2"></div>
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <label class="text-xs font-bold text-blue-700 block mb-2">ไฟล์แนบ</label>
                            <input type="file" name="config_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="index.php" class="px-6 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900">ยกเลิก</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="config_file"]');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const valueTextArea = document.querySelector('textarea[name="config_value"]');
        // ตรวจสอบว่า element มีอยู่จริงก่อนรัน เพื่อป้องกัน Error
        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    valueTextArea.value = `[ระบบจะอัปโหลดไฟล์: ${file.name} เพื่อแทนที่ค่าเดิม]`;
                    valueTextArea.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-300');
                    valueTextArea.readOnly = true;
                    if (file.type.startsWith('image/') || file.name.endsWith('.ico')) {
                        const reader = new FileReader();
                        reader.onload = function(loadEvent) {
                            previewContainer.innerHTML = '';
                            const img = document.createElement('img');
                            img.src = loadEvent.target.result;
                            img.className = 'w-full h-full object-contain p-2 z-10 bg-white/50 backdrop-blur-sm rounded-xl';
                            previewContainer.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            });
        }
    });
</script>