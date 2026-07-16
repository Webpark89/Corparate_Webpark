<?php

/**
 * Shared article create/edit form partial.
 */
$data = $article ?? [];
$action = $action ?? 'create';
$formAction = $formAction ?? 'create.php';
$status = isset($_POST['status']) && in_array($_POST['status'], ['published', 'draft', 'hidden'], true)
    ? $_POST['status']
    : ($data['status'] ?? 'draft');

$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$authors = db()->query('SELECT id, display_name FROM authors ORDER BY display_name')->fetchAll();

$sections = [];
if (!empty($data['content'])) {
    $decoded = json_decode($data['content'], true);
    if (is_array($decoded)) {
        $sections = $decoded;
    } else {
        $sections = [
            ['lang' => 'th', 'topic' => 'เนื้อหาเดิม', 'body' => $data['content']]
        ];
    }
}

$inputClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition';
?>

<style>
    /* Make CKEditor sit flush inside our own rounded/bordered frame */
    .editor-frame .ck.ck-toolbar {
        border: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-radius: 0 !important;
        background: #f8fafc !important;
    }
    .editor-frame .ck.ck-content {
        border: none !important;
        border-radius: 0 !important;
        min-height: 150px;
    }
    .editor-frame .ck.ck-content:focus,
    .editor-frame .ck.ck-focused {
        box-shadow: none !important;
    }
</style>

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

            <!-- Language Toggle Tabs (Global for Form) -->
            <div class="inline-flex items-center gap-2 mb-2">
                <button type="button" id="global-tab-th" onclick="switchGlobalLanguage('th')"
                    style="padding-left:1.25rem;padding-right:1.25rem;"
                    class="py-2 text-sm font-semibold rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 focus:outline-none transition-all">
                    ภาษาไทย (0/5)
                </button>
                <button type="button" id="global-tab-en" onclick="switchGlobalLanguage('en')"
                    style="padding-left:1.25rem;padding-right:1.25rem;"
                    class="py-2 text-sm font-semibold rounded-lg bg-transparent text-slate-500 border border-slate-200 hover:bg-slate-50 hover:text-slate-800 focus:outline-none transition-all">
                    English (0/5)
                </button>
            </div>

            <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">

                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">ตั้งค่ารูปภาพหน้าปก</h3>
                    <p class="text-xs text-slate-500 mt-0.5">อัปโหลดและจัดการรูปภาพหลักสำหรับใช้แสดงผลในบทความนี้</p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                            ตัวอย่างรูปภาพ
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
                                    <p class="text-sm font-medium text-slate-400">ยังไม่ได้อัปโหลดรูปภาพหน้าปก</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center space-y-5">

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                อัปโหลดไฟล์ใหม่
                                <?php if ($action === 'create'): ?>
                                    <span class="text-red-500 ml-0.5">*</span>
                                <?php endif; ?>
                            </label>
                            <div class="relative group border border-slate-200 rounded-xl bg-slate-50/50 p-3 hover:bg-slate-50 transition-colors">
                                <input type="file"
                                    name="image_file"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                            </div>
                            <p class="text-[11px] text-slate-400 px-1">
                                * รองรับไฟล์นามสกุล: JPEG, JPG, PNG, WEBP, GIF (ขนาดสูงสุดไม่เกิน 8MB)
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                ข้อความอธิบายรูปภาพ (SEO Alt Text) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input name="cover_image_alt"
                                value="<?= e($data['cover_image_alt'] ?? '') ?>"
                                placeholder="ตัวอย่างเช่น 'โต๊ะทำงานดีไซน์มินิมอล' - ข้อความนี้ช่วยให้ Google Images ค้นพบเซกชันนี้ได้ง่ายขึ้น"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/5 outline-none transition-all duration-200"
                                required>
                        </div>

                    </div>

                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="border-b px-6 py-4">
                    <h3 class="text-sm font-semibold">การปรับแต่งประสิทธิภาพ SEO</h3>
                    <p class="text-xs text-slate-500 mt-1">เพิ่มโอกาสในการติดอันดับการค้นหาที่ดีบน Google</p>
                </div>

                <div class="p-6 space-y-5">

                    <!-- Thai SEO Fields -->
                    <div class="lang-group lang-th-group space-y-5">
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    หัวข้อสำหรับ SEO (Meta Title) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCount" class="text-xs text-slate-500">0 / 150</span>
                            </div>

                            <input id="mainTitle"
                                name="meta_title"
                                value="<?= e($data['meta_title'] ?? '') ?>"
                                placeholder="ระบุหัวข้อที่ต้องการให้แสดงบนหน้าค้นหาของ Google..."
                                class="<?= $inputClass ?>"
                                required>
                        </div>

                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    คำอธิบายสรุปบทความ (Meta Description) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCount" class="text-xs text-slate-500">0 / 500</span>
                            </div>

                            <textarea id="metaDesc"
                                name="meta_description"
                                rows="4"
                                placeholder="เขียนคำอธิบายสั้น ๆ เพื่อดึงดูดใจผู้ใช้งานเมื่อแสดงเป็นสเนปเป็ตบนหน้า Google..."
                                class="<?= $inputClass ?>"
                                required><?= e($data['meta_description'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-2 block text-slate-700">
                                คำค้นหาสำคัญ (Keywords) <span class="text-red-500 ml-0.5">*</span>
                            </label>

                            <input name="meta_keywords"
                                value="<?= e($data['meta_keywords'] ?? '') ?>"
                                placeholder="ระบุคำค้นหา เช่น เว็บดีไซน์, ความรู้คู่ระบบ, เทคโนโลยี (คั่นด้วยเครื่องหมายจุลภาค , )"
                                class="<?= $inputClass ?>"
                                required>
                        </div>
                    </div>

                    <!-- English SEO Fields -->
                    <div class="lang-group lang-en-group space-y-5 hidden">
                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    SEO Title (English) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCountEn" class="text-xs text-slate-500">0 / 150</span>
                            </div>

                            <input id="mainTitleEn"
                                name="meta_title_en"
                                value="<?= e($data['meta_title_en'] ?? '') ?>"
                                placeholder="Enter English SEO Title..."
                                class="<?= $inputClass ?>">
                        </div>

                        <div>
                            <div class="flex justify-between mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    SEO Description (English) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCountEn" class="text-xs text-slate-500">0 / 500</span>
                            </div>

                            <textarea id="metaDescEn"
                                name="meta_description_en"
                                rows="4"
                                placeholder="Enter English SEO Description..."
                                class="<?= $inputClass ?>"><?= e($data['meta_description_en'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-2 block text-slate-700">
                                SEO Keywords (English) <span class="text-red-500 ml-0.5">*</span>
                            </label>

                            <input name="meta_keywords_en"
                                value="<?= e($data['meta_keywords_en'] ?? '') ?>"
                                placeholder="Enter keywords separated by commas..."
                                class="<?= $inputClass ?>">
                        </div>
                    </div>

                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">เนื้อหาบทความและการตั้งค่า</h3>
                    <p class="text-xs text-slate-500 mt-0.5">จัดการเขียนบทความหลักและกำหนดสถานะการเปิดเผยข้อมูลบนเว็บไซต์</p>
                </div>

                <div class="p-6 space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                หมวดหมู่บทความ <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <select name="category_id" class="<?= $inputClass ?> bg-white border-slate-200 h-[46px] py-0" required>
                                <option value="">เลือกหมวดหมู่ที่ต้องการ...</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>"
                                        <?= (int) ($data['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>>
                                        <?= e($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                ลำดับความสำคัญ (Priority) <span class="text-slate-400 ml-0.5">(ไม่บังคับ)</span>
                            </label>
                            <input type="number" name="priority"
                                value="<?= e(isset($data['priority']) && $data['priority'] !== 999 ? str_pad((string)$data['priority'], 2, '0', STR_PAD_LEFT) : '') ?>"
                                placeholder="เช่น 01, 02 (เว้นว่างไว้หากไม่ระบุ)"
                                class="<?= $inputClass ?> bg-white h-[46px]">
                        </div>

                    </div>

                    <div class="lang-group lang-th-group space-y-6">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                ลิงก์บทความ (URL Slug) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                                <span class="flex items-center px-4 bg-slate-100 text-sm text-slate-500 border-r border-slate-200 select-none">
                                    /article/
                                </span>
                                <input id="slug"
                                    name="slug"
                                    value="<?= e($data['slug'] ?? '') ?>"
                                    placeholder="ชื่อเนื้อหาภาษาอังกฤษหรือไทยเพื่อเป็นลิงก์ถาวร"
                                    class="flex-1 bg-transparent px-4 py-3 text-sm outline-none"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="lang-group lang-en-group space-y-6 hidden">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                URL Slug (English)
                            </label>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                                <span class="flex items-center px-4 bg-slate-100 text-sm text-slate-500 border-r border-slate-200 select-none">
                                    /en/article/
                                </span>
                                <input id="slug_en"
                                    name="slug_en"
                                    value="<?= e($data['slug_en'] ?? '') ?>"
                                    placeholder="English URL slug"
                                    class="flex-1 bg-transparent px-4 py-3 text-sm outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Source URL Field (Not language specific, but shown for both) -->
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                            ที่มาของบทความ (Source / Reference URL)
                        </label>
                        <input name="source_url"
                            value="<?= e($data['source_url'] ?? '') ?>"
                            placeholder="เช่น https://www.example.com/original-post หรือ ชื่อผู้แต่ง/หนังสืออ้างอิง"
                            class="<?= $inputClass ?>">
                    </div>

                    <div class="space-y-4">
                        <!-- Dummy fields to keep WEBPARKSeoEditor happy without modifying shared assets -->
                        <div id="mainEditor" class="hidden"></div>
                        <textarea id="mainEditorData" name="dummy_content" class="hidden"></textarea>

                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                            เนื้อหาหลักของบทความ (แบ่งแท็บตามภาษา)
                        </label>

                        <!-- Tab Content: Thai (TH) -->
                        <div class="lang-group lang-th-group pt-2">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 md:p-5 space-y-5">
                                <div id="th-sections-container" class="space-y-5">
                                    <!-- Dynamic Thai sections will go here -->
                                </div>
                                <button type="button" id="add-btn-th" onclick="addSection('th')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white border border-dashed border-blue-300 text-blue-700 hover:bg-blue-50 hover:border-blue-400 rounded-xl text-sm font-semibold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    เพิ่มช่องเนื้อหาภาษาไทย
                                </button>
                            </div>
                        </div>

                        <!-- Tab Content: English (EN) -->
                        <div class="lang-group lang-en-group pt-2 hidden">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 md:p-5 space-y-5">
                                <div id="en-sections-container" class="space-y-5">
                                    <!-- Dynamic English sections will go here -->
                                </div>
                                <button type="button" id="add-btn-en" onclick="addSection('en')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white border border-dashed border-blue-300 text-blue-700 hover:bg-blue-50 hover:border-blue-400 rounded-xl text-sm font-semibold transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    เพิ่มช่องเนื้อหาภาษาอังกฤษ
                                </button>
                            </div>
                        </div>
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
                    class="px-6 h-11 rounded-xl border bg-slate-100 border-slate-300 text-slate-600 font-semibold hover:bg-slate-200 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
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
        placeholder: 'เริ่มต้นเขียนเนื้อหาที่น่าสนใจของคุณตรงนี้ได้เลย...'
    });

    // English SEO Counters (Standalone)
    document.addEventListener('DOMContentLoaded', () => {
        const titleEn = document.getElementById('mainTitleEn');
        const descEn = document.getElementById('metaDescEn');
        const titleCountEn = document.getElementById('titleCountEn');
        const descCountEn = document.getElementById('descCountEn');

        function updateEnCounters() {
            if (titleEn && titleCountEn) {
                const len = titleEn.value.length;
                titleCountEn.textContent = `${len} / 150`;
                titleCountEn.className = `text-xs font-medium ${len > 150 ? 'text-rose-600' : 'text-slate-500'}`;
            }
            if (descEn && descCountEn) {
                const len = descEn.value.length;
                descCountEn.textContent = `${len} / 500`;
                descCountEn.className = `text-xs font-medium ${len > 500 ? 'text-rose-600' : 'text-slate-500'}`;
            }
        }

        if (titleEn) titleEn.addEventListener('input', updateEnCounters);
        if (descEn) descEn.addEventListener('input', updateEnCounters);
        updateEnCounters();
    });
</script>

<script>
    const preloadedSections = <?= json_encode($sections, JSON_UNESCAPED_UNICODE) ?>;
    const editors = {};
    let thCount = 0;
    let enCount = 0;

    function escapeHtml(string) {
        return String(string)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function createSectionElement(lang, index, topicVal = '', bodyVal = '') {
        const container = document.getElementById(lang + '-sections-container');
        const id = `editor-${lang}-${index}`;
        
        const div = document.createElement('div');
        div.className = 'section-item bg-white border border-slate-300 rounded-2xl shadow-sm overflow-hidden';
        div.dataset.lang = lang;
        div.dataset.index = index;
        
        div.innerHTML = `
            <div class="flex items-center justify-between gap-3 px-6 py-4 bg-slate-50 border-b border-slate-200">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">หัวข้อย่อยบทความ (${lang.toUpperCase()}) <span class="text-red-500">*</span></label>
                <button type="button" class="shrink-0 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-lg transition" style="transition:background-color .15s;" onmouseover="this.style.backgroundColor='#fecdd3'" onmouseout="this.style.backgroundColor='#fff1f2'" onclick="removeSection(this, '${lang}')">ลบช่องนี้</button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <input type="text" name="sections[${lang}][${index}][topic]" value="${escapeHtml(topicVal)}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1.5">เนื้อหาหลักของบทความ (${lang.toUpperCase()}) <span class="text-red-500">*</span></label>
                    <div class="editor-frame rounded-xl border border-slate-300 overflow-hidden">
                        <textarea id="${id}" name="sections[${lang}][${index}][body]" class="w-full min-h-[150px]">${escapeHtml(bodyVal)}</textarea>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(div);
        
        ClassicEditor.create(document.querySelector(`#${id}`), {
            licenseKey: 'GPL',
            toolbar: {
                items: [ 'heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ],
                shouldNotGroupWhenFull: true
            }
        }).then(editor => {
            editors[id] = editor;
        }).catch(error => {
            console.error('Error initializing editor:', error);
        });

        if (lang === 'th') {
            thCount++;
        } else {
            enCount++;
        }
        updateCounterDisplay(lang);
    }

    function addSection(lang) {
        const count = lang === 'th' ? thCount : enCount;
        if (lang === 'th' && count >= 5) {
            alert('เพิ่มหัวข้อภาษาไทยได้สูงสุด 5 ช่อง');
            return;
        }
        if (lang === 'en' && count >= 5) {
            alert('เพิ่มหัวข้อภาษาอังกฤษได้สูงสุด 5 ช่อง');
            return;
        }
        createSectionElement(lang, count, '', '');
    }

    function removeSection(button, lang) {
        const item = button.closest('.section-item');
        const textarea = item.querySelector('textarea');
        if (textarea && editors[textarea.id]) {
            editors[textarea.id].destroy().then(() => {
                delete editors[textarea.id];
            });
        }
        item.remove();
        reindexSections(lang);
    }

    function reindexSections(lang) {
        const container = document.getElementById(lang + '-sections-container');
        const items = container.querySelectorAll('.section-item');
        let count = 0;
        
        items.forEach((item, index) => {
            item.dataset.index = index;
            const input = item.querySelector('input[type="text"]');
            if (input) {
                input.name = `sections[${lang}][${index}][topic]`;
            }
            const textarea = item.querySelector('textarea');
            if (textarea) {
                textarea.name = `sections[${lang}][${index}][body]`;
            }
            count++;
        });
        
        if (lang === 'th') {
            thCount = count;
        } else {
            enCount = count;
        }
        updateCounterDisplay(lang);
    }

    function updateCounterDisplay(lang) {
        const count = lang === 'th' ? thCount : enCount;
        
        const btn = document.getElementById(`global-tab-${lang}`);
        if (btn) {
            const label = lang === 'th' ? 'ภาษาไทย' : 'English';
            btn.textContent = `${label} (${count}/5)`;
        }
        
        const addBtn = document.getElementById(`add-btn-${lang}`);
        if (addBtn) {
            if (count >= 5) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
                addBtn.classList.remove('hover:bg-blue-100');
            } else {
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                addBtn.classList.add('hover:bg-blue-100');
            }
        }
    }

    function switchGlobalLanguage(lang) {
        const thGroups = document.querySelectorAll('.lang-th-group');
        const enGroups = document.querySelectorAll('.lang-en-group');
        
        const btnTh = document.getElementById('global-tab-th');
        const btnEn = document.getElementById('global-tab-en');

        if (lang === 'th') {
            thGroups.forEach(el => el.classList.remove('hidden'));
            enGroups.forEach(el => el.classList.add('hidden'));

            btnTh.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200', 'hover:bg-blue-100');
            btnTh.classList.remove('bg-transparent', 'text-slate-500', 'border-slate-200', 'hover:bg-slate-50', 'hover:text-slate-800');
            
            btnEn.classList.add('bg-transparent', 'text-slate-500', 'border-slate-200', 'hover:bg-slate-50', 'hover:text-slate-800');
            btnEn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200', 'hover:bg-blue-100');
        } else {
            thGroups.forEach(el => el.classList.add('hidden'));
            enGroups.forEach(el => el.classList.remove('hidden'));

            btnEn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200', 'hover:bg-blue-100');
            btnEn.classList.remove('bg-transparent', 'text-slate-500', 'border-slate-200', 'hover:bg-slate-50', 'hover:text-slate-800');
            
            btnTh.classList.add('bg-transparent', 'text-slate-500', 'border-slate-200', 'hover:bg-slate-50', 'hover:text-slate-800');
            btnTh.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200', 'hover:bg-blue-100');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const thSections = preloadedSections.filter(s => (s.lang || 'th') === 'th');
        const enSections = preloadedSections.filter(s => s.lang === 'en');
        
        thSections.forEach((s, idx) => createSectionElement('th', idx, s.topic || '', s.body || ''));
        enSections.forEach((s, idx) => createSectionElement('en', idx, s.topic || '', s.body || ''));
        
        const form = document.querySelector('#unifiedForm');
        if (form) {
            form.addEventListener('submit', () => {
                for (const id in editors) {
                    if (editors.hasOwnProperty(id)) {
                        const textarea = document.getElementById(id);
                        if (textarea) {
                            textarea.value = editors[id].getData();
                        }
                    }
                }
            });
        }
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
                    };

                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>