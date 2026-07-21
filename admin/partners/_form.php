<?php
/**
 * Shared partner create/edit form partial.
 */
$data = $partner ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
// ดึงรายการหมวดหมู่ทั้งหมดสำหรับใช้ใน Dropdown
$categories = db()->query('SELECT * FROM partner_categories ORDER BY sort_order ASC')->fetchAll();
$inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all';
?>
<div class="mx-auto max-w-5xl px-4 py-6">
    <form method="post"
        action="<?= e($formAction) ?>"
        enctype="multipart/form-data"
        id="unifiedForm"
        class="space-y-6">
        <?= csrf_field() ?>
        <?php if ($action === 'edit'): ?>
            <input type="hidden" name="id" value="<?= (int)($data['id'] ?? 0) ?>">
        <?php endif; ?>
        <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-slate-900">ข้อมูลบริษัทและโลโก้</h3>
                <p class="text-xs text-slate-500 mt-0.5">จัดการข้อมูลและรูปภาพโลโก้ของลูกค้าหรือพาร์ทเนอร์</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4 flex flex-col items-center justify-start border-b border-slate-100 pb-6 md:border-b-0 md:pb-0 md:border-r md:pr-6">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 block text-center">
                        รูปภาพโลโก้
                    </label>
                    <div id="imagePreviewContainer" class="w-full h-32 rounded-xl border-2 border-slate-200 bg-slate-50 p-2 flex items-center justify-center overflow-hidden relative shadow-inner group">
                        <?php if (!empty($data['image_url'])): ?>
                            <?php
                            $logoUrl = resolve_admin_image_url($data['image_url']);
                            ?>
                            <img src="<?= e($logoUrl) ?>" class="w-full h-full object-contain">
                        <?php else: ?>
                            <div class="text-center p-2 text-slate-400">
                                <svg class="w-6 h-6 mx-auto opacity-70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375 0 11-.75 0 .375 0 01.75 0z" />
                                </svg>
                                <span class="text-[10px] block mt-1 font-medium">ยังไม่มีรูปภาพโลโก้</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 w-full max-w-[200px]">
                        <div class="relative group border border-slate-200 rounded-xl bg-slate-50/50 p-2 hover:bg-slate-50 transition-colors text-center">
                            <label class="cursor-pointer text-xs font-semibold text-blue-700 hover:text-blue-800 block py-1">
                                <span>อัปโหลดโลโก้...</span>
                                <input type="file" name="image_file" accept="image/*" class="hidden">
                            </label>
                        </div>
                        <input type="hidden" name="image_url" value="<?= e($data['image_url'] ?? '') ?>">
                        <p class="text-[10px] text-slate-400 text-center mt-1.5">รองรับ JPEG, PNG, WEBP, SVG (ไม่เกิน 5MB)</p>
                    </div>
                </div>
                <div class="md:col-span-8 w-full space-y-5 lg:pl-6">
                    <div class="w-full">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                            ชื่อบริษัท / พาร์ทเนอร์ <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="name"
                            value="<?= e($data['name'] ?? '') ?>"
                            placeholder="ตัวอย่างเช่น Yamaha Motors"
                            class="<?= $inputClass ?> w-full block"
                            required>
                        <p class="text-xs text-slate-400 mt-1">กรอกชื่อบริษัทเพื่อใช้สำหรับอ้างอิงและการจัดการในระบบ</p>
                    </div>
                    <div class="w-full">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                            ข้อความอธิบายรูปภาพ (Alt Text สำหรับ SEO)
                        </label>
                        <input type="text"
                            name="image_alt"
                            value="<?= e($data['image_alt'] ?? '') ?>"
                            placeholder="เช่น โลโก้บริษัท Yamaha Motors หมวดยานยนต์"
                            class="<?= $inputClass ?> w-full block">
                        <p class="text-[10px] text-slate-400 mt-1">
                            ช่วยให้ Google ค้นพบรูปภาพนี้ได้ง่ายขึ้น หากเว้นว่างไว้ระบบจะใช้ชื่อบริษัทแทน
                        </p>
                    </div>
                    <div class="w-full">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                            หมวดหมู่ (Category) <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" class="<?= $inputClass ?> bg-white h-[46px] py-0 w-full block" required>
                            <option value="">เลือกหมวดหมู่...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id'] ?>" <?= ((int) ($data['category_id'] ?? 0) === (int) $category['id']) ? 'selected' : '' ?>>
                                    <?= e($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-slate-400 mt-1">เลือกหมวดหมู่ที่ต้องการให้โลโก้นี้แสดงผล (เช่น ยานยนต์, การเงิน)</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-slate-900">การแสดงผล</h3>
                <p class="text-xs text-slate-500 mt-0.5">ตั้งค่าการแสดงผลและจัดลำดับของโลโก้บนเว็บไซต์</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2 block">
                            ลำดับการแสดงผล (Sort Order)
                        </label>
                        <input type="number"
                            name="sort_order"
                            value="<?= (int)($data['sort_order'] ?? 0) ?>"
                            placeholder="ระบุตัวเลข เช่น 0, 1, 2 (ค่าน้อยจะแสดงผลก่อน)"
                            class="<?= $inputClass ?> h-[46px]"
                            min="0">
                        <p class="text-xs text-slate-400 mt-1">ตัวเลขน้อยจะถูกแสดงก่อนตัวเลขมาก</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="lg:col-span-12 pt-4">
            <section class="sticky bottom-0 bg-white/90 backdrop-blur-sm p-4 -m-4 rounded-2xl border border-slate-200 shadow-sm">
             <div class="flex items-center justify-between">
            <a href="index.php" class="px-6 h-11 flex items-center justify-center rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                ยกเลิก
            </a>
            <div class="flex items-center gap-3">
                <button type="submit" name="is_active" value="0"
                    class="px-6 h-11 rounded-xl border bg-amber-50 border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition">
                    บันทึกเป็นฉบับร่าง
                </button>
                <button type="submit" name="is_active" value="0" 
                    class="px-6 h-11 flex items-center justify-center gap-2 rounded-xl border bg-slate-50 border-slate-200 text-slate-600 font-semibold hover:bg-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    บันทึกและซ่อน
                </button>
                <button type="submit" name="is_active" value="1"
                    class="px-6 h-11 rounded-xl border bg-emerald-50 border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    บันทึกและเผยแพร่
                </button>
                 </div>
                    </div>
                </section>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="image_file"]');
        const previewContainer = document.getElementById('imagePreviewContainer');
        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(loadEvent) {
                        previewContainer.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = loadEvent.target.result;
                        // ปรับให้พรีวิวรูปภาพแสดงเป็นแบบ object-contain เพื่อให้แสดงโลโก้ได้พอดีกล่อง
                        img.className = 'w-full h-full object-contain';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>