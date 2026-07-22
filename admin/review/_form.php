<?php
/**
 * Shared review create/edit form partial.
 */
$data = $review ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
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
                <h3 class="text-sm font-semibold text-slate-900">ข้อมูลผู้ให้รีวิวและรูปโปรไฟล์</h3>
                <p class="text-xs text-slate-500 mt-0.5">จัดการข้อมูลส่วนตัวและรูปภาพของลูกค้าหรือผู้ให้คำนิยม</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4 flex flex-col items-center justify-start border-b border-slate-100 pb-6 md:border-b-0 md:pb-0 md:border-r md:pr-6">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 block text-center">
                        รูปโปรไฟล์ผู้รีวิว
                    </label>
                    <div id="imagePreviewContainer" class="w-28 h-28 rounded-full border-2 border-slate-200 bg-slate-50 p-1 flex items-center justify-center overflow-hidden relative shadow-inner group">
                        <?php if (!empty($data['reviewer_image_url'])): ?>
                            <img src="<?= e(resolve_admin_image_url($data['reviewer_image_url'])) ?>" class="w-full h-full object-cover rounded-full">
                        <?php else: ?>
                            <div class="text-center p-2 text-slate-400">
                                <svg class="w-6 h-6 mx-auto opacity-70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span class="text-[10px] block mt-1 font-medium">ไม่มีรูปภาพ</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 w-full max-w-[200px]">
                        <div class="relative group border border-slate-200 rounded-xl bg-slate-50/50 p-2 hover:bg-slate-50 transition-colors text-center">
                            <label class="cursor-pointer text-xs font-semibold text-blue-700 hover:text-blue-800 block py-1">
                                <span>อัปโหลดรูปโปรไฟล์...</span>
                                <input type="file" name="image_file" accept="image/*" class="hidden">
                            </label>
                        </div>
                        <input type="hidden" name="reviewer_image_url" value="<?= e($data['reviewer_image_url'] ?? '') ?>">
                        <p class="text-[10px] text-slate-400 text-center mt-1.5">รองรับ JPEG, PNG, WEBP (ไม่เกิน 5MB)</p>
                    </div>
                </div>
                <div class="md:col-span-8 w-full space-y-5 lg:pl-6">
                    <div class="w-full">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                            ชื่อ-นามสกุล ผู้รีวิว <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="reviewer_name"
                            value="<?= e($data['reviewer_name'] ?? '') ?>"
                            placeholder="ตัวอย่างเช่น คุณนภัส วงศ์ภัทร"
                            class="<?= $inputClass ?> w-full block"
                            required>
                        <p class="text-xs text-slate-400 mt-1">กรอกชื่อจริงและนามสกุลของผู้รีวิว เพื่อแสดงบนเว็บไซต์</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                        <div class="w-full">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                                ตำแหน่งงาน (Position)
                            </label>
                            <input type="text"
                                name="reviewer_position"
                                value="<?= e($data['reviewer_position'] ?? '') ?>"
                                placeholder="เช่น CTO หรือ ผู้จัดการฝ่ายผลิต"
                                class="<?= $inputClass ?> w-full block">
                            <p class="text-xs text-slate-400 mt-1">ระบุตำแหน่งงานของผู้รีวิว เช่น CEO, CTO หรือ Manager</p>
                        </div>
                        <div class="w-full">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 block">
                                ชื่อบริษัท / องค์กร (Company)
                            </label>
                            <input type="text"
                                name="reviewer_company"
                                value="<?= e($data['reviewer_company'] ?? '') ?>"
                                placeholder="ตัวอย่างเช่น Webpark Group"
                                class="<?= $inputClass ?> w-full block">
                            <p class="text-xs text-slate-400 mt-1">กรอกชื่อบริษัทหรือองค์กรของผู้รีวิว เพื่อให้ข้อมูลครบถ้วน</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-slate-900">คะแนนรีวิวและข้อความคำนิยม</h3>
                <p class="text-xs text-slate-500 mt-0.5">จัดการระดับความพึงพอใจและรายละเอียดคำนิยมจากลูกค้า</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2 block">
                            ลำดับการแสดงผล (Sort Order)
                        </label>
                        <input type="number"
                            name="sort_order"
                            value="<?= (int)($data['sort_order'] ?? 1) ?>"
                            placeholder="ระบุตัวเลข (ค่าน้อยจะแสดงผลก่อน)"
                            class="<?= $inputClass ?> h-[46px]"
                            min="1">
                        <p class="text-xs text-slate-400 mt-1">ตัวเลขน้อยจะถูกแสดงก่อนตัวเลขมาก</p>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5 block">
                        รีวิวหลัก / ข้อความคำนิยม<span class="text-red-500">*</span>
                    </label>
                    <textarea name="content"
                        rows="5"
                        placeholder="กรอกรายละเอียดความประทับใจของลูกค้า เช่น 'ระบบใหม่ลดเวลาทำงานของทีมเราลงกว่า 60%...'"
                        class="<?= $inputClass ?> resize-none"
                        required><?= e($data['content'] ?? '') ?></textarea>
                    <p class="text-xs text-slate-400 mt-1">ใส่รายละเอียดคำนิยมจากลูกค้าเพื่อแสดงบนหน้าเว็บไซต์</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2 block">
                        คะแนนความพึงพอใจ (Rating) <span class="text-red-500">*</span>
                    </label>
                    <?php $currentRating = (int)($data['rating'] ?? 5); ?>
                    <div class="grid grid-cols-5 gap-2 sm:gap-3">
                        <?php for ($ratingValue = 5; $ratingValue >= 1; $ratingValue--): ?>
                            <label class="cursor-pointer">
                                <input type="radio"
                                    name="rating"
                                    value="<?= $ratingValue ?>"
                                    class="peer sr-only"
                                    <?= $currentRating === $ratingValue ? 'checked' : '' ?>
                                    required>
                                <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200 bg-white py-2.5 transition-all hover:bg-slate-50 peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:ring-1 peer-checked:ring-amber-400 group">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-lg font-black text-slate-400 transition-colors group-hover:text-slate-600 peer-checked:text-amber-600">
                                            <?= $ratingValue ?>
                                        </span>
                                        <svg class="w-4 h-4 text-slate-200 transition-colors group-hover:text-slate-300 peer-checked:text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        <?php endfor; ?>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">เลือกจำนวนดาวเพื่อแสดงระดับความพึงพอใจของลูกค้า</p>
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
                        img.className = 'w-full h-full object-cover rounded-full';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>