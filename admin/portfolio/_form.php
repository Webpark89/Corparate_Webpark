<?php
/**
 * Shared portfolio create/edit form partial.
 */
$data = $portfolio ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$authors = db()->query('SELECT id, display_name FROM authors ORDER BY display_name')->fetchAll();
$content = $data['description'] ?? ($data['content'] ?? '');
$inputClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-4 text-lg text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition';
?>
<div class="mx-auto max-w-7xl px-4 py-6 lg:px-8">
    <form method="post"
        action="<?= e($formAction) ?>"
        enctype="multipart/form-data"
        id="unifiedForm"
        class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <?= csrf_field() ?>
        <?php if ($action === 'edit'): ?>
            <input type="hidden" name="id" value="<?= (int)($data['id'] ?? 0) ?>">
        <?php endif; ?>
        <div class="lg:col-span-12 space-y-6">
            <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">ตั้งค่ารูปภาพหน้าปกผลงาน</h3>
                    <p class="text-xs text-slate-500 mt-0.5">อัปโหลดและจัดการรูปภาพหลักสำหรับใช้แสดงผลในโครงการผลงานนี้</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                            ตัวอย่างรูปภาพปกผลงาน
                        </label>
                        <div class="w-full h-64 rounded-xl border border-slate-200 bg-slate-50 p-2 flex items-center justify-center overflow-hidden">
                            <?php if (!empty($data['cover_image'])): ?>
                                <img src="<?= e(resolve_admin_image_url($data['cover_image'])) ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    class="w-full h-full object-contain rounded-lg shadow-sm transition-transform duration-200 hover:scale-[1.01]">
                                <div class="hidden text-center p-6 space-y-2">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-red-500">ไม่พบไฟล์รูปภาพในระบบ</p>
                                    <p class="text-[10px] text-slate-400">กรุณาตรวจสอบที่อยู่ไฟล์: <?= e($data['cover_image']) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-6 space-y-2">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375 0 11-.75 0 .375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-400">ยังไม่ได้อัปโหลดรูปภาพหน้าปกผลงาน</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center space-y-5">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                อัปโหลดไฟล์ภาพปกใหม่
                                <?php if ($action === 'create'): ?>
                                    <span class="text-red-500 ml-0.5">*</span>
                                <?php endif; ?>
                            </label>
                            <div class="relative group border border-slate-200 rounded-xl bg-slate-50/50 p-3 hover:bg-slate-50 transition-colors">
                                <input type="file"
                                    name="image_file"
                                    <?php if ($action === 'create'): ?>required<?php endif; ?>
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                            </div>
                            <p class="text-[11px] text-slate-400 px-1">
                                * รองรับไฟล์นามสกุล: JPEG, JPG, PNG, WEBP, GIF (ขนาดสูงสุดไม่เกิน 8MB)
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                ข้อความอธิบายรูปภาพปก (SEO Alt Text) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input name="cover_image_alt"
                                value="<?= e($data['cover_image_alt'] ?? '') ?>"
                                placeholder="ตัวอย่างเช่น 'หน้าต่างระบบจัดการคลังสินค้า ERP' - ช่วยให้ Google ค้นพบรูปภาพนี้ง่ายขึ้น"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/5 outline-none transition-all duration-200"
                                required>
                        </div>
                    </div>
                </div>
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4">
                    <h3 class="text-sm font-semibold">การปรับแต่งประสิทธิภาพ SEO ของผลงาน</h3>
                    <p class="text-xs text-slate-500 mt-1">เพิ่มโอกาสในการติดอันดับการค้นหาโครงการผลงานที่ดีบน Google</p>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm font-medium text-slate-700">
                                หัวข้อโครงการผลงานสำหรับ SEO (Meta Title) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <span id="titleCount" class="text-xs text-slate-500">0 / 60</span>
                        </div>
                        <input id="mainTitle"
                            name="meta_title"
                            value="<?= e($data['meta_title'] ?? '') ?>"
                            placeholder="ระบุหัวข้อผลงานโครงการที่ต้องการให้แสดงบนหน้าค้นหาของ Google..."
                            class="<?= $inputClass ?>"
                            required>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="text-sm font-medium text-slate-700">
                                คำอธิบายสรุปโครงการผลงาน (Meta Description) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <span id="descCount" class="text-xs text-slate-500">0 / 155</span>
                        </div>
                        <textarea id="metaDesc"
                            name="meta_description"
                            rows="4"
                            placeholder="เขียนคำอธิบายสั้น ๆ เพื่อดึงดูดใจผู้ใช้งานเมื่อแสดงผลลัพธ์บนหน้า Google..."
                            class="<?= $inputClass ?>"
                            required><?= e($data['meta_description'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-2 block text-slate-700">
                            คำค้นหาสำคัญ (Keywords) <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input name="meta_keywords"
                            value="<?= e($data['meta_keywords'] ?? '') ?>"
                            placeholder="ระบุคำค้นหา เช่น พัฒนาระบบ ERP, ออกแบบเว็บไซต์องค์กร, พัฒนาแอปพลิเคชัน (คั่นด้วยเครื่องหมายจุลภาค , )"
                            class="<?= $inputClass ?>"
                            required>
                    </div>
                </div>
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">ข้อมูลรายละเอียดโครงการและการตั้งค่า</h3>
                    <p class="text-xs text-slate-500 mt-0.5">จัดการข้อมูลแบรนด์ลูกค้า เทคโนโลยีที่ใช้เขียนระบบ และสถานะโครงการ</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                ชื่อลูกค้า / บริษัทแบรนด์ผู้ว่าจ้าง
                            </label>
                            <input name="client_name"
                                value="<?= e($data['client_name'] ?? '') ?>"
                                placeholder="ตัวอย่างเช่น บริษัท เอสซีจี จำกัด (มหาชน)"
                                class="<?= $inputClass ?>">
                        </div>
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                เทคโนโลยีที่ใช้งาน (Tech Stack)
                            </label>
                            <input name="tech_stack"
                                value="<?= e($data['tech_stack'] ?? '') ?>"
                                placeholder="ตัวอย่างเช่น Node.js, React, Tailwind CSS, MySQL"
                                class="<?= $inputClass ?>">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                สถานะการเผยแพร่ผลงาน
                            </label>
                            <div class="grid grid-cols-1 xl:grid-cols-3 gap-3">
                                <label class="block cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="draft"
                                        class="peer sr-only"
                                        <?= (($data['status'] ?? 'draft') === 'draft') ? 'checked' : '' ?>>
                                    <div class="w-full flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-4 text-center text-lg font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-50 peer-checked:border-amber-400 peer-checked:bg-amber-50/60 peer-checked:text-amber-800">
                                        แบบร่าง (Draft)
                                    </div>
                                </label>
                                <label class="block cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="hidden"
                                        class="peer sr-only"
                                        <?= (($data['status'] ?? 'draft') === 'hidden') ? 'checked' : '' ?>>
                                    <div class="w-full flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-4 text-center text-lg font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-50 peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-800">
                                        ซ่อนอยู่ (Hidden)
                                    </div>
                                </label>
                                <label class="block cursor-pointer">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="published"
                                        class="peer sr-only"
                                        <?= (($data['status'] ?? 'draft') === 'published') ? 'checked' : '' ?>>
                                    <div class="w-full flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-4 text-center text-lg font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/60 peer-checked:text-emerald-800">
                                        เผยแพร่ (Published)
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                หมวดหมู่ผลงานโครงการ <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <select name="category_id" class="<?= $inputClass ?> bg-white border-slate-200" required>
                                <option value="">เลือกหมวดหมู่ผลงานโครงการ...</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>" <?= (int) ($data['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>>
                                        <?= e($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                            ลิงก์หน้าผลงาน (URL Slug) <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                            <span class="flex items-center px-4 bg-slate-100 text-sm text-slate-500 border-r border-slate-200 select-none font-mono">
                                /portfolio/
                            </span>
                            <input id="slug"
                                name="slug"
                                value="<?= e($data['slug'] ?? '') ?>"
                                placeholder="ชื่อโครงการภาษาอังกฤษเช่น scg-logistics-erp สำหรับใช้จัดทำลิงก์ถาวร"
                                class="flex-1 bg-transparent px-4 py-3 text-sm outline-none"
                                required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                            รายละเอียดเนื้อหาหลักของผลงานโครงการ <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/30 focus-within:border-blue-500 transition-all">
                            <div id="mainEditor" class="min-h-[420px] p-4 bg-white text-slate-800">
                                <?= $content ?>
                            </div>
                        </div>
                        <textarea id="mainEditorData" name="content" hidden><?= e($content) ?></textarea>
                    </div>
                </div>
            </section>
        </div>
        <div class="lg:col-span-12 pt-4">
            <section class="sticky bottom-0 bg-white/90 backdrop-blur-sm p-4 -m-4 rounded-2xl border border-slate-200 shadow-sm">
             <div class="flex items-center justify-between">
            <a href="index.php" class="px-6 h-11 flex items-center justify-center rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                ยกเลิก
            </a>
            <div class="flex items-center gap-3">
                <button type="submit" name="status" value="draft" 
                    class="px-6 h-11 rounded-xl border bg-amber-50 border-amber-300 text-amber-700 font-semibold hover:bg-amber-50 transition">
                    บันทึกเป็นฉบับร่าง
                </button>
                <button type="submit" name="status" value="hidden" 
                    class="px-6 h-11 flex items-center justify-center gap-2 rounded-xl border bg-slate-50 border-slate-200 text-slate-600 font-semibold hover:bg-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    บันทึกและซ่อน
                </button>
                <button type="submit" name="status" value="published" 
                    class="px-6 h-11 rounded-xl border bg-emerald-50 border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 transition">
                    บันทึกและเผยแพร่
                </button>
                 </div>
                    </div>
                </section>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script src="../assets/js/seo-editor.js"></script>
<script>
    window.WEBPARKSeoEditor.init({
        formSelector: '#unifiedForm',
        editorSelector: '#mainEditor',
        contentSelector: '#mainEditorData',
        titleSelector: '#mainTitle',
        descSelector: '#metaDesc',
        slugSelector: '#slug',
        titleCounterSelector: '#titleCount',
        descCounterSelector: '#descCount',
        placeholder: 'เริ่มต้นเขียนรายละเอียดโครงการ ผลงาน และขั้นตอนความสำเร็จตรงนี้ได้เลย...'
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="image_file"]');
        const previewContainer = document.querySelector('.w-full.h-64.rounded-xl.border');
        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(loadEvent) {
                        previewContainer.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = loadEvent.target.result;
                        img.className = 'w-full h-full object-contain rounded-lg';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>