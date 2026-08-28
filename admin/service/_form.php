<?php

/**
 * Shared service create/edit form partial.
 */
$data = $setting ?? [];
$inputClass = 'w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all';

$features = [];
if (!empty($data['id'])) {
    $stmt = db()->prepare('SELECT title FROM service_features WHERE service_id = ? ORDER BY id ASC');
    $stmt->execute([$data['id']]);
    $features = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fallback to legacy details_json if service_features is empty (migration)
if (empty($features) && !empty($data['details_json'])) {
    $decoded = json_decode($data['details_json'], true);
    if (is_array($decoded) && !empty($decoded['features'])) {
        $features = $decoded['features'];
    }
}
?>

<section class="mx-auto max-w-4xl px-4 py-8">
    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="px-6 py-5 border-b">
            <h3 class="font-semibold text-slate-900 border-l-4 border-blue-500 pl-4">จัดการบริการ</h3>
            <p class="text-xs text-slate-500 mt-1">ฟอร์มเพิ่ม/แก้ไขข้อมูลบริการ</p>
        </div>
        <div class="p-6">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= e($data['id'] ?? '') ?>">

                <!-- ชื่อบริการและ Slug -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-bold text-slate-700 block mb-1">ชื่อบริการ</label>
                        <input type="text" name="title" value="<?= e($data['title'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="เช่น การตลาดดิจิทัล" required>
                        <p class="text-xs text-slate-400 mt-1">ชื่อบริการที่จะแสดงบนเว็บไซต์</p>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-700 block mb-1">Slug (URL)</label>
                        <input type="text" name="slug" value="<?= e($data['slug'] ?? '') ?>" class="<?= $inputClass ?> font-mono" placeholder="เช่น online-marketing" required>
                        <p class="text-xs text-slate-400 mt-1">ใช้สำหรับลิงก์ URL ของบริการ</p>
                    </div>
                </div>

                <!-- สรุปบริการ -->
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-1">สรุปบริการ</label>
                    <textarea name="summary" rows="3" class="<?= $inputClass ?>" placeholder="อธิบายสั้น ๆ เกี่ยวกับบริการ"><?= e($data['summary'] ?? '') ?></textarea>
                    <p class="text-xs text-slate-400 mt-1">คำอธิบายสั้น ๆ แสดงบนหน้าแรกหรือหน้ารายละเอียด</p>
                </div>

                <!-- หัวข้อ Dropdown -->
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-1">หัวข้อ Dropdown (แสดงบนหน้าเว็บ)</label>
                    <?php $dropdownTitle = (!empty($data['details_json']) ? (json_decode($data['details_json'], true)['dropdown_title'] ?? '') : ''); ?>
                    <input type="text" name="dropdown_title" value="<?= e($dropdownTitle) ?>" class="<?= $inputClass ?>" placeholder="เช่น ERP / ERM / HR">
                    <p class="text-xs text-slate-400 mt-1">ข้อความที่จะแสดงบนปุ่ม Dropdown ในหน้าบริการของเรา</p>
                </div>

                <!-- คุณสมบัติ / Features -->
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-2">คุณสมบัติ / Features</label>
                    <div id="features-container" class="flex flex-wrap gap-2 mb-3 p-3 bg-slate-50 rounded-lg border border-slate-200 min-h-12">
                        <?php foreach ($features as $feature): ?>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-2 hover:bg-blue-200 transition cursor-pointer group">
                                <span><?= e($feature) ?></span>
                                <button type="button" class="text-blue-600 hover:text-blue-900 font-bold opacity-60 group-hover:opacity-100 ml-1">×</button>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="feature-input" placeholder="พิมพ์คุณสมบัติ..." class="<?= $inputClass ?> flex-1">
                        <button type="button" id="feature-add-btn" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">เพิ่ม</button>
                    </div>
                    <input type="hidden" name="features" id="features-hidden" value="<?= e(implode(',', $features)) ?>">
                    <p class="text-xs text-slate-400 mt-2">พิมพ์แล้วกด Enter หรือคลิก "เพิ่ม" เพื่อเพิ่มคุณสมบัติ • คลิก × หรือคลิกที่การ์ดเพื่อลบ</p>
                </div>

                <!-- สถานะ Active -->
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_active" value="1" <?= ($data['is_active'] ?? 1) ? 'checked' : '' ?> class="w-4 h-4">
                    <label class="text-sm font-semibold">เปิดใช้งานบริการ (แสดงบนหน้าเว็บ)</label>
                </div>

                <!-- อัปโหลดรูปภาพ -->
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-1">รูปภาพประกอบ</label>
                    <?php if (!empty($data['image'])): ?>
                        <div class="mb-2"><img src="<?= e(resolve_admin_image_url($data['image'])) ?>" class="w-24 h-24 rounded-xl object-cover border"></div>
                        <input type="hidden" name="old_image" value="<?= e($data['image']) ?>">
                    <?php endif; ?>
                    <input type="file" name="image" class="w-full text-sm">
                    <p class="text-xs text-slate-400 mt-1">ขนาดไฟล์ที่แนะนำ: 800x800px</p>
                </div>

                <!-- ปุ่มบันทึก -->
                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">บันทึกข้อมูลบริการ</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- JS สำหรับ Tag Card Feature -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('feature-input');
        const addBtn = document.getElementById('feature-add-btn');
        const container = document.getElementById('features-container');
        const hiddenInput = document.getElementById('features-hidden');

        function updateHidden() {
            // ดึง text จากทุก span.bg-blue-100
            const features = Array.from(container.querySelectorAll('span.bg-blue-100')).map(span => {
                const textSpan = span.querySelector('span');
                return textSpan ? textSpan.textContent.trim() : '';
            }).filter(text => text !== '');
            hiddenInput.value = features.join(',');
        }

        function attachDeleteHandlers() {
            // Attach event listener ให้กับปุ่มลบทั้งหมด (existing + new)
            container.querySelectorAll('button[type="button"]').forEach(btn => {
                // Remove old listeners ถ้ามี
                btn.replaceWith(btn.cloneNode(true));
            });

            container.querySelectorAll('button[type="button"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    btn.closest('span.bg-blue-100').remove();
                    updateHidden();
                });
            });
        }

        function addFeature(text) {
            if (text.trim() === '') return;

            const span = document.createElement('span');
            span.className = 'bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-2 rounded-full flex items-center gap-2 hover:bg-blue-200 transition cursor-pointer group';

            const textSpan = document.createElement('span');
            textSpan.textContent = text.trim();

            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'text-blue-600 hover:text-blue-900 font-bold opacity-60 group-hover:opacity-100 ml-1';
            deleteBtn.textContent = '×';

            span.appendChild(textSpan);
            span.appendChild(deleteBtn);
            container.appendChild(span);

            input.value = '';
            attachDeleteHandlers();
            updateHidden();
        }

        // Attach handlers ตอน page load (สำหรับ existing features)
        attachDeleteHandlers();

        addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            addFeature(input.value);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addFeature(input.value);
            }
        });
    });
</script>