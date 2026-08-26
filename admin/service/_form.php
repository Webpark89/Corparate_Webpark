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
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-1">สรุปบริการ</label>
                    <textarea name="summary" rows="3" class="<?= $inputClass ?>" placeholder="อธิบายสั้น ๆ เกี่ยวกับบริการ"><?= e($data['summary'] ?? '') ?></textarea>
                    <p class="text-xs text-slate-400 mt-1">คำอธิบายสั้น ๆ แสดงบนหน้าแรกหรือหน้ารายละเอียด</p>
                </div>
                <div>
                    <label class="text-sm font-bold text-slate-700 block mb-1">หัวข้อ Dropdown (แสดงบนหน้าเว็บ)</label>
                    <?php $dropdownTitle = (!empty($data['details_json']) ? (json_decode($data['details_json'], true)['dropdown_title'] ?? '') : ''); ?>
                    <input type="text" name="dropdown_title" value="<?= e($dropdownTitle) ?>" class="<?= $inputClass ?>" placeholder="เช่น ERP / ERM / HR">
                    <p class="text-xs text-slate-400 mt-1">ข้อความที่จะแสดงบนปุ่ม Dropdown ในหน้าบริการของเรา</p>
                </div>
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
                <div class="rounded-2xl border border-slate-200 bg-slate-50" style="padding: 1.25rem;">
                    <label class="flex items-center cursor-pointer select-none" style="gap: 0.75rem;">
                        <input type="checkbox" name="is_active" value="1" <?= ($data['is_active'] ?? 1) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500 transition cursor-pointer">
                        <div>
                            <span class="text-sm font-bold text-slate-800 block">เปิดใช้งานบริการ (แสดงบนหน้าเว็บ)</span>
                            <span class="text-xs text-slate-500 block" style="margin-top: 0.2rem;">หากเปิดใช้งาน บริการนี้จะแสดงผลบนหน้าเว็บไซต์ทันที</span>
                        </div>
                    </label>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" style="margin-top: 1.5rem;">
                    <div class="border-b border-slate-100 bg-slate-50" style="padding: 1.25rem 1.5rem;">
                        <h4 class="text-sm font-bold text-slate-900" style="margin: 0; font-size: 0.95rem;">รูปภาพประกอบบริการ</h4>
                        <p class="text-xs text-slate-500" style="margin-top: 0.25rem;">อัปโหลดภาพสำหรับใช้แสดงผลในการ์ดบริการและหน้ารายละเอียด</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2" style="padding: 1.5rem; gap: 1.5rem;">
                        <!-- Left: Preview Box -->
                        <div class="flex flex-col" style="gap: 0.75rem;">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    ตัวอย่างรูปภาพ
                                </label>
                                <span class="text-xs text-slate-400">Live Preview</span>
                            </div>
                            <div id="service-img-preview-box" class="w-full rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden relative" style="height: 240px; padding: 1rem;">
                                <?php if (!empty($data['image'])): ?>
                                    <img id="service-img-preview" src="<?= e(resolve_admin_image_url($data['image'])) ?>" alt="Service Preview" class="max-h-full max-w-full w-auto h-auto object-contain rounded-lg shadow-sm transition-transform duration-200 hover:scale-105" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="hidden flex-col items-center justify-center text-center text-slate-400" style="padding: 1.5rem; gap: 0.5rem;">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium">ยังไม่มีรูปภาพ</span>
                                    </div>
                                <?php else: ?>
                                    <img id="service-img-preview" src="" alt="Service Preview" class="max-h-full max-w-full w-auto h-auto object-contain rounded-lg shadow-sm hidden">
                                    <div id="service-img-placeholder" class="flex flex-col items-center justify-center text-center text-slate-400" style="padding: 1.5rem; gap: 0.5rem;">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium">ยังไม่ได้เลือกรูปภาพ</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($data['image'])): ?>
                                <input type="hidden" name="old_image" value="<?= e($data['image']) ?>">
                            <?php endif; ?>
                            <div id="image-validation-status" class="mt-2 hidden"></div>
                        </div>

                        <!-- Right: File Selection & Requirements Card -->
                        <div class="flex flex-col" style="gap: 1.25rem;">
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block" style="margin-bottom: 0.5rem;">
                                    เลือกไฟล์รูปภาพใหม่
                                </label>
                                <div class="border border-slate-200 rounded-xl bg-slate-50 transition-colors" style="padding: 0.75rem;">
                                    <input type="file" id="service-image-input" name="image" accept=".webp,.png,.jpg,.jpeg,image/webp,image/png,image/jpeg" class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                                </div>
                            </div>

                            <div class="rounded-xl border border-blue-100 bg-blue-50/40" style="padding: 1.25rem;">
                                <div class="flex items-center font-bold text-blue-900 text-xs uppercase tracking-wider" style="gap: 0.5rem; margin-bottom: 1rem;">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>ข้อกำหนดและขนาดภาพที่แนะนำ</span>
                                </div>

                                <div class="flex flex-col" style="gap: 0.85rem; font-size: 0.8125rem;">
                                    <div>
                                        <span class="text-slate-700 block font-semibold" style="margin-bottom: 0.4rem;">นามสกุลไฟล์ที่รองรับ:</span>
                                        <div class="flex flex-wrap" style="gap: 0.4rem;">
                                            <span class="rounded-lg bg-blue-600 text-white font-bold" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">⭐ WEBP (แนะนำ)</span>
                                            <span class="rounded-lg bg-white border border-slate-200 text-slate-700 font-bold" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">PNG</span>
                                            <span class="rounded-lg bg-white border border-slate-200 text-slate-700 font-bold" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">JPG</span>
                                            <span class="rounded-lg bg-white border border-slate-200 text-slate-700 font-bold" style="padding: 0.2rem 0.6rem; font-size: 0.75rem;">JPEG</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between border-t border-blue-100" style="padding-top: 0.6rem;">
                                        <span class="text-slate-700 font-semibold">ขนาดไฟล์สูงสุด:</span>
                                        <span class="rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 font-bold" style="padding: 0.2rem 0.75rem; font-size: 0.75rem;">ไม่เกิน 500 KB</span>
                                    </div>
                                    <div class="text-slate-500 text-[11px]" style="margin-top: -0.4rem;">
                                        (แนะนำขนาด 100 – 300 KB เพื่อความเร็วสูงสุดในการโหลดหน้าเว็บ)
                                    </div>

                                    <div class="border-t border-blue-100" style="padding-top: 0.6rem;">
                                        <span class="text-slate-700 font-semibold block" style="margin-bottom: 0.25rem;">สัดส่วนและขนาดภาพที่แนะนำ:</span>
                                        <ul class="text-slate-600 list-disc list-inside space-y-1" style="font-size: 0.75rem; line-height: 1.5; margin: 0;">
                                            <li><strong class="text-slate-800">แนะนำ: 1280 × 720 px</strong> หรือ 1920 × 1080 px (สัดส่วน 16:9 แนวนอน)</li>
                                            <li>ขนาดขั้นต่ำ: 800 × 450 px</li>
                                            <li>กรณีเป็นไอคอน / กราฟิกจัตุรัส: 800 × 800 px (สัดส่วน 1:1)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm hover:shadow">บันทึกข้อมูลบริการ</button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('feature-input');
        const addBtn = document.getElementById('feature-add-btn');
        const container = document.getElementById('features-container');
        const hiddenInput = document.getElementById('features-hidden');
        function updateHidden() {
            const features = Array.from(container.querySelectorAll('span.bg-blue-100')).map(span => {
                const textSpan = span.querySelector('span');
                return textSpan ? textSpan.textContent.trim() : '';
            }).filter(text => text !== '');
            hiddenInput.value = features.join(',');
        }
        function attachDeleteHandlers() {
            container.querySelectorAll('button[type="button"]').forEach(btn => {
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

        // Instant Image Preview & Validation on Select
        const serviceImageInput = document.getElementById('service-image-input');
        const statusBox = document.getElementById('image-validation-status');
        const maxSizeBytes = 500 * 1024; // 500 KB

        if (serviceImageInput) {
            serviceImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) {
                    if (statusBox) statusBox.classList.add('hidden');
                    return;
                }

                // 1. Validate File Size (Max 500 KB)
                if (file.size > maxSizeBytes) {
                    const fileSizeKb = Math.round(file.size / 1024);
                    statusBox.className = 'mt-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center gap-2';
                    statusBox.innerHTML = `<span>❌ <strong>ขนาดไฟล์เกิน 500 KB</strong> (ไฟล์ของคุณมีขนาด ${fileSizeKb} KB) กรุณาบีบอัดรูปภาพให้ไม่เกิน 500 KB ก่อนอัปโหลด</span>`;
                    statusBox.classList.remove('hidden');
                    serviceImageInput.value = ''; // Reset input
                    return;
                }

                // 2. Validate Extension
                const allowedExts = ['image/webp', 'image/png', 'image/jpeg', 'image/jpg'];
                const fileExt = file.name.split('.').pop().toLowerCase();
                const isAllowedExt = ['webp', 'png', 'jpg', 'jpeg'].includes(fileExt);

                if (!isAllowedExt) {
                    statusBox.className = 'mt-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center gap-2';
                    statusBox.innerHTML = `<span>❌ <strong>นามสกุลไฟล์ไม่ถูกต้อง</strong> รองรับเฉพาะ .webp, .png, .jpg, .jpeg</span>`;
                    statusBox.classList.remove('hidden');
                    serviceImageInput.value = ''; // Reset input
                    return;
                }

                // 3. Load Image & Read Dimensions
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const width = img.naturalWidth;
                        const height = img.naturalHeight;
                        const ratio = (width / height).toFixed(2);
                        const fileSizeKb = Math.round(file.size / 1024);

                        let ratioText = 'สัดส่วนอื่น ๆ';
                        if (Math.abs(ratio - (16/9).toFixed(2)) < 0.05) {
                            ratioText = 'สัดส่วน 16:9 แนวนอน (ดีเยี่ยม ⭐)';
                        } else if (Math.abs(ratio - 1.0) < 0.05) {
                            ratioText = 'สัดส่วน 1:1 จัตุรัส';
                        } else if (width > height) {
                            ratioText = 'แนวนอน';
                        } else {
                            ratioText = 'แนวตั้ง';
                        }

                        const previewImg = document.getElementById('service-img-preview');
                        const placeholder = document.getElementById('service-img-placeholder');
                        if (previewImg) {
                            previewImg.src = e.target.result;
                            previewImg.style.display = 'block';
                            previewImg.classList.remove('hidden');
                        }
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }

                        // Display Success Status Badge
                        statusBox.className = 'mt-2 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex flex-col gap-0.5';
                        statusBox.innerHTML = `
                            <div class="font-bold flex items-center gap-1">
                                <span>✅ ไฟล์ผ่านเกณฑ์ข้อกำหนด:</span>
                                <span>${fileSizeKb} KB</span>
                            </div>
                            <div class="text-[11px] text-emerald-700">
                                ขนาดรูป: <strong>${width} × ${height} px</strong> (${ratioText})
                            </div>
                        `;
                        statusBox.classList.remove('hidden');
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>