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
$currentArticleId = (int)($data['id'] ?? 0);
$pinnedArticle = null;
try {
    $stmt = db()->prepare('SELECT id, meta_title FROM article WHERE is_pinned = 1 AND id != ? LIMIT 1');
    $stmt->execute([$currentArticleId]);
    $pinnedArticle = $stmt->fetch();
} catch (Exception $e) {
    $pinnedArticle = null;
}
$isPinned = !empty($data['is_pinned']);
$sections = [];
if (!empty($data['content'])) {
    $rawContent = (string)$data['content'];
    $decoded = json_decode($rawContent, true);
    if (!is_array($decoded)) {
        $decoded = json_decode(stripslashes($rawContent), true);
    }
    if (!is_array($decoded)) {
        // Regex fallback for malformed JSON strings in database
        $decoded = [];
        preg_match_all('/\{\s*"lang"\s*:\s*"(th|en)"\s*,\s*"topic"\s*:\s*"(.*?)"\s*,\s*"body"\s*:\s*"(.*?)"\s*\}/s', $rawContent, $matches, PREG_SET_ORDER);
        if (!empty($matches)) {
            foreach ($matches as $m) {
                $decoded[] = [
                    'lang' => $m[1],
                    'topic' => str_replace(['\\"', '\\/'], ['"', '/'], $m[2]),
                    'body' => str_replace(['\\"', '\\/'], ['"', '/'], $m[3]),
                ];
            }
        }
    }
    if (is_array($decoded) && !empty($decoded)) {
        foreach ($decoded as &$sec) {
            if (isset($sec['body'])) {
                $sec['body'] = str_replace(['\r\n', '\n', '\r', '\t', '<\/', '\"', '\/', '<\span>', '<\p>', '<\strong>', '<\h2>', '<\h3>', '<\div>', '§', '&sect;'], [' ', ' ', ' ', ' ', '</', '"', '/', '</span>', '</p>', '</strong>', '</h2>', '</h3>', '</div>', '', ''], $sec['body']);
            }
            if (isset($sec['topic'])) {
                $sec['topic'] = str_replace(['\r\n', '\n', '\r', '\t', '<\/', '\"', '\/', '§', '&sect;'], [' ', ' ', ' ', ' ', '</', '"', '/', '', ''], $sec['topic']);
            }
        }
        unset($sec);
        $sections = $decoded;
    } else {
        $cleanBody = str_replace(['\r\n', '\n', '\r', '\t', '<\/', '\"', '\/', '<\span>', '<\p>', '<\strong>', '<\h2>', '<\h3>', '<\div>', '§', '&sect;'], [' ', ' ', ' ', ' ', '</', '"', '/', '</span>', '</p>', '</strong>', '</h2>', '</h3>', '</div>', '', ''], $rawContent);
        $sections = [
            ['lang' => 'th', 'topic' => 'เนื้อหาบทความ', 'body' => $cleanBody]
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
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">ตั้งค่ารูปภาพหน้าปกบทความ</h3>
                        <p class="text-xs text-slate-500 mt-0.5">อัปโหลดและจัดการรูปภาพหลัก (Hero Image) สำหรับบทความและแชร์ลงโซเชียลมีเดีย</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                        ⚡ Core Web Vitals Optimized
                    </span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; padding: 1.5rem; width: 100%;">
                    <!-- Left: Preview -->
                    <div class="flex flex-col" style="width: 100%; min-width: 0; gap: 0.75rem;">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block">
                                ตัวอย่างรูปภาพ
                            </label>
                            <span class="text-xs text-slate-400">Live Preview</span>
                        </div>
                        <div id="article-img-preview-box" class="w-full rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden relative" style="height: 260px; padding: 0.75rem;">
                            <?php if (!empty($data['cover_image'])): ?>
                                <img id="article-img-preview" src="<?= e(resolve_admin_image_url($data['cover_image'])) ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                    class="max-h-full max-w-full w-auto h-auto object-contain rounded-lg shadow-sm transition-transform duration-200 hover:scale-[1.01]">
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
                                <img id="article-img-preview" src="" alt="Preview" class="max-h-full max-w-full w-auto h-auto object-contain rounded-lg hidden">
                                <div id="article-img-placeholder" class="text-center p-6 space-y-2">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375 0 11-.75 0 .375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-400">ยังไม่ได้อัปโหลดรูปภาพหน้าปก</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($data['cover_image'])): ?>
                            <input type="hidden" name="cover_image" value="<?= e($data['cover_image']) ?>">
                        <?php endif; ?>
                        <div id="articleImageValidationStatus" class="mt-2 hidden"></div>
                    </div>

                    <!-- Right: Upload and Guidelines -->
                    <div class="flex flex-col" style="width: 100%; min-width: 0; gap: 1.25rem;">
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block" style="margin-bottom: 0.5rem;">
                                เลือกไฟล์รูปภาพใหม่
                                <?php if ($action === 'create' && empty($data['cover_image'])): ?>
                                    <span class="text-red-500 ml-0.5">*</span>
                                <?php endif; ?>
                            </label>
                            <div class="border border-slate-200 rounded-xl bg-slate-50 transition-colors" style="padding: 0.75rem;">
                                <input type="file"
                                    id="article-image-input"
                                    name="image_file"
                                    accept=".webp,image/webp"
                                    class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                            </div>
                        </div>

                        <!-- Requirements Guide Card -->
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40" style="padding: 1.25rem;">
                            <div class="flex items-center font-bold text-blue-900 text-xs uppercase tracking-wider" style="gap: 0.5rem; margin-bottom: 0.75rem;">
                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>ข้อกำหนดและขนาดภาพหน้าปกที่แนะนำ (SEO & Speed)</span>
                            </div>

                            <div class="flex flex-col" style="gap: 0.75rem; font-size: 0.8125rem;">
                                <div>
                                    <span class="text-slate-700 font-semibold block" style="margin-bottom: 0.25rem;">นามสกุลไฟล์ที่รองรับ:</span>
                                    <p class="text-xs text-slate-700">
                                        รองรับเฉพาะไฟล์ <strong class="text-blue-700 font-bold">.webp</strong> เท่านั้น
                                    </p>
                                </div>

                                <div class="flex items-center justify-between border-t border-blue-100" style="padding-top: 0.5rem;">
                                    <span class="text-slate-700 font-semibold">ขนาดไฟล์:</span>
                                    <span class="rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 font-bold px-2.5 py-0.5 text-xs">ไม่เกิน 1 MB (แนะนำ 150 – 350 KB)</span>
                                </div>

                                <div class="border-t border-blue-100" style="padding-top: 0.5rem;">
                                    <span class="text-slate-700 font-semibold block" style="margin-bottom: 0.25rem;">สัดส่วนและขนาดภาพที่เหมาะสม:</span>
                                    <ul class="text-slate-600 list-disc list-inside space-y-1 text-xs leading-relaxed">
                                        <li><strong class="text-slate-800">แนะนำ: 1280 × 720 px</strong> (สัดส่วน 16:9)</li>
                                        <li>ขนาดขั้นต่ำ: 800 × 450 px</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Alt text input -->
                        <div class="flex flex-col" style="gap: 0.5rem;">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block">
                                    ข้อความอธิบายรูปภาพ (SEO Alt Text) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="altTextBadge" class="text-xs font-medium text-slate-400"></span>
                            </div>
                            <input id="coverImageAlt"
                                name="cover_image_alt"
                                value="<?= e($data['cover_image_alt'] ?? '') ?>"
                                placeholder="ตัวอย่าง: 'หน้าจอระบบบริหารจัดการ ERP บัญชีสำหรับองค์กรธุรกิจและโรงงาน'"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/5 outline-none transition-all duration-200"
                                required>
                            <p class="text-[11px] text-slate-500" style="margin-top: 0.15rem;">
                                💡 <strong>คำแนะนำ SEO:</strong> อธิบายสิ่งที่อยู่ในภาพ + ใส่คำค้นหา เพื่อให้ติดอันดับ <strong>Google Image Search</strong> (⚠️ <em>หลีกเลี่ยงการใส่ตัวเลขสั้นๆ เช่น 01 หรือ image1</em>)
                            </p>
                            <div id="altTextWarning" class="text-xs font-medium text-rose-600 hidden p-2 rounded-lg bg-rose-50 border border-rose-200"></div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold">การปรับแต่งประสิทธิภาพ SEO</h3>
                        <p class="text-xs text-slate-500 mt-0.5">เพิ่มโอกาสในการติดอันดับการค้นหาที่ดีบน Google Search</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 border border-blue-200">
                        ⚡ Google SEO Checklist
                    </span>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Thai SEO Fields -->
                    <div class="lang-group lang-th-group space-y-5">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    หัวข้อบทความ (Article Title) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCount" class="text-xs font-medium text-slate-500">0 / 120 (แนะนำ 50-60 ตัวอักษร)</span>
                            </div>
                            <input id="mainTitle"
                                name="meta_title"
                                value="<?= e($data['meta_title'] ?? '') ?>"
                                placeholder="ระบุหัวข้อบทความหลัก..."
                                class="<?= $inputClass ?>"
                                required>
                            <p id="titleSeoHint" class="text-[11px] text-slate-500 mt-1">
                                💡 <strong>เคล็ดลับ Title:</strong> วางคำค้นหาสำคัญ (Keyword) ไว้ <strong>50–60 ตัวอักษรแรก</strong> เพื่อให้แสดงผลครบถ้วนบนหน้าค้นหา Google
                            </p>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    คำอธิบายสรุปบทความ (Article Summary) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCount" class="text-xs font-medium text-slate-500">0 / 200 (แนะนำ 120-160 ตัวอักษร)</span>
                            </div>
                            <textarea id="metaDesc"
                                name="meta_description"
                                rows="4"
                                placeholder="เขียนคำอธิบายสั้น ๆ สรุปเนื้อหาบทความ..."
                                class="<?= $inputClass ?>"
                                required><?= e($data['meta_description'] ?? '') ?></textarea>
                            <p id="descSeoHint" class="text-[11px] text-slate-500 mt-1">
                                💡 <strong>เคล็ดลับ Meta Description:</strong> ความยาวที่เหมาะสมที่สุดคือ <strong>120–160 ตัวอักษร</strong> สรุปเนื้อหาและมีคำกระตุ้นให้คลิก
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block text-slate-700">
                                คำค้นหาสำคัญ (Keywords) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input name="meta_keywords"
                                value="<?= e($data['meta_keywords'] ?? '') ?>"
                                placeholder="ระบุคำค้นหา เช่น บริการพัฒนาระบบ ERP บัญชี, ERP สำหรับธุรกิจ (คั่นด้วยเครื่องหมายจุลภาค , )"
                                class="<?= $inputClass ?>"
                                required>
                        </div>
                    </div>
                    <!-- English SEO Fields -->
                    <div class="lang-group lang-en-group space-y-5 hidden">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    หัวข้อบทความ (EN) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCountEn" class="text-xs font-medium text-slate-500">0 / 120</span>
                            </div>
                            <input id="mainTitleEn"
                                name="meta_title_en"
                                value="<?= e($data['meta_title_en'] ?? '') ?>"
                                placeholder="Enter English SEO Title..."
                                class="<?= $inputClass ?>">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    SEO Description (English) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCountEn" class="text-xs font-medium text-slate-500">0 / 200</span>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <div class="w-full">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    หมวดหมู่บทความ <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <button type="button" onclick="openCategoryModal()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 underline cursor-pointer">
                                    ⚙️ จัดการหมวดหมู่
                                </button>
                            </div>
                            <select name="category_id" id="category_select" class="<?= $inputClass ?> bg-white border-slate-200 h-[46px] py-0" required>
                                <option value="">เลือกหมวดหมู่ที่ต้องการ...</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>"
                                        <?= (int) ($data['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>>
                                        <?= e($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <option disabled>──────────</option>
                                <option value="__manage__" class="font-bold text-blue-600 bg-blue-50 py-1">⚙️ จัดการหมวดหมู่ (เพิ่ม / แก้ไข / ลบ)...</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                วันที่เผยแพร่บทความ <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input type="date" name="created_at"
                                value="<?= isset($data['created_at']) ? date('Y-m-d', strtotime($data['created_at'])) : date('Y-m-d') ?>"
                                class="<?= $inputClass ?> bg-white h-[46px]" required>
                        </div>
                        <div class="w-full">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    การปักหมุดบทความ
                                </label>
                                <span class="text-[11px] text-amber-600 font-medium">จำกัด 1 บทความ</span>
                            </div>
                            <label class="flex items-center gap-3 px-4 rounded-xl border border-slate-300 bg-slate-50 hover:bg-slate-100/80 cursor-pointer h-[46px] transition select-none">
                                <input type="hidden" name="is_pinned" value="0">
                                <input type="checkbox" id="is_pinned_input" name="is_pinned" value="1"
                                    <?= $isPinned ? 'checked' : '' ?>
                                    class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                <span class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                                    <span>📌</span>
                                    <span>ปักหมุดแสดงเป็นบทความแรกสุด</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="lang-group lang-th-group space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    ลิงก์บทความ (URL Slug) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span class="text-[11px] text-slate-400">ภาษาอังกฤษตัวพิมพ์เล็ก คั่นด้วยขีดกลาง (-)</span>
                            </div>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                                <span class="flex items-center px-4 bg-slate-100 text-sm text-slate-500 border-r border-slate-200 select-none">
                                    /article/
                                </span>
                                <input id="slug"
                                    name="slug"
                                    value="<?= e($data['slug'] ?? '') ?>"
                                    placeholder="เช่น erp-accounting-guide-2026"
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
                                    placeholder="English URL slug e.g. erp-accounting-guide-2026"
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

<!-- Category Manager Modal Popup -->
<div id="quickCategoryModal" style="position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px); padding: 16px;">
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 860px; width: 100%; margin: auto; max-height: 88vh; display: flex; flex-direction: column; overflow: hidden; border: 1px solid #e2e8f0;">
        <!-- Header -->
        <div style="padding: 18px 26px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #f8fafc; flex-shrink: 0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; border: 1px solid #dbeafe;">
                    📁
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: bold; color: #1e293b; margin: 0; line-height: 1.3;">การจัดการหมวดหมู่บทความ</h4>
                    <p style="font-size: 11px; color: #64748b; margin: 0; line-height: 1.2;">เพิ่ม แก้ไขชื่อ หรือลบหมวดหมู่ของบทความ</p>
                </div>
            </div>
            <button type="button" onclick="closeCategoryModal()" style="background: transparent; border: none; padding: 6px; border-radius: 8px; color: #94a3b8; cursor: pointer;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body with Scroll -->
        <div style="padding: 22px 26px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; flex: 1;">
            <!-- Add New Category Sub-form -->
            <div style="padding: 16px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 12px;">
                <div style="font-size: 11px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                    <span>➕</span>
                    <span>เพิ่มหมวดหมู่ใหม่</span>
                </div>
                <div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; width: 100%;">
                    <div style="flex: 1 1 280px; min-width: 200px;">
                        <label style="font-size: 11px; font-weight: 600; color: #334155; display: block; margin-bottom: 4px;">
                            ชื่อหมวดหมู่ <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="newCategoryName" placeholder="เช่น AI & Automation, Cloud..." 
                            style="width: 100%; border-radius: 10px; border: 1px solid #cbd5e1; background: #fff; padding: 8px 14px; font-size: 12px; height: 40px; outline: none; box-sizing: border-box;">
                    </div>
                    <div style="flex: 1 1 240px; min-width: 180px;">
                        <label style="font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">URL Slug</label>
                        <input type="text" id="newCategorySlug" placeholder="เช่น ai-automation" 
                            style="width: 100%; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; padding: 8px 14px; font-size: 12px; height: 40px; outline: none; box-sizing: border-box;">
                    </div>
                    <div style="flex: 0 0 auto; min-width: 130px;">
                        <button type="button" id="btnSaveCategory" onclick="submitNewCategory()" 
                            style="width: 100%; height: 40px; border-radius: 10px; background: #2563eb; color: #fff; font-size: 12px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 0 20px; box-sizing: border-box;">
                            <span id="btnSaveCategoryText">+ เพิ่ม</span>
                            <svg id="btnSaveCategorySpinner" class="hidden animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="categoryModalError" class="hidden" style="padding: 8px 12px; border-radius: 8px; background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; font-size: 11px; font-weight: 500;"></div>
            </div>

            <!-- Categories List Table -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-size: 11px; font-weight: bold; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">หมวดหมู่ทั้งหมดในระบบ</span>
                    <span id="modalCatCountBadge" style="font-size: 11px; color: #64748b; font-weight: 500;">กำลังโหลด...</span>
                </div>
                <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                    <table style="width: 100%; text-align: left; font-size: 12px; border-collapse: collapse;">
                        <thead style="background: #f8fafc; font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0;">
                            <tr>
                                <th style="padding: 12px 16px; width: 35%;">ชื่อหมวดหมู่</th>
                                <th style="padding: 12px 16px; width: 30%;">Slug</th>
                                <th style="padding: 12px 16px; width: 15%; text-align: center;">บทความ</th>
                                <th style="padding: 12px 16px; width: 20%; text-align: right;">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="modalCategoryListBody" style="background: #fff;">
                            <tr>
                                <td colspan="4" style="padding: 24px; text-align: center; color: #94a3b8;">กำลังโหลดรายการหมวดหมู่...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="padding: 14px 26px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; flex-shrink: 0;">
            <button type="button" onclick="closeCategoryModal()" style="padding: 8px 20px; font-size: 12px; font-weight: 600; color: #334155; background: #fff; border: 1px solid #cbd5e1; border-radius: 10px; cursor: pointer;">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script src="../assets/js/seo-editor.js?v=1.0.3"></script>
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
                titleCountEn.textContent = `${len} / 120`;
                titleCountEn.className = `text-xs font-medium ${len > 120 ? 'text-rose-600' : 'text-slate-500'}`;
                if (len > 120) {
                    titleEn.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                    titleEn.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
                } else {
                    titleEn.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                    titleEn.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
                }
            }
            if (descEn && descCountEn) {
                const len = descEn.value.length;
                descCountEn.textContent = `${len} / 200`;
                descCountEn.className = `text-xs font-medium ${len > 200 ? 'text-rose-600' : 'text-slate-500'}`;
                if (len > 200) {
                    descEn.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                    descEn.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
                } else {
                    descEn.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                    descEn.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
                }
            }
        }
        if (titleEn) titleEn.addEventListener('input', updateEnCounters);
        if (descEn) descEn.addEventListener('input', updateEnCounters);
        updateEnCounters();

        // Prevent Form Submission if over limits
        const form = document.getElementById('unifiedForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                const titleTh = document.getElementById('mainTitle');
                const descTh = document.getElementById('metaDesc');

                let hasError = false;
                let firstErrorElement = null;

                if (titleTh && titleTh.value.length > 120) {
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = titleTh;
                }
                if (descTh && descTh.value.length > 200) {
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = descTh;
                }
                if (titleEn && titleEn.value.length > 120) {
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = titleEn;
                }
                if (descEn && descEn.value.length > 200) {
                    hasError = true;
                    if (!firstErrorElement) firstErrorElement = descEn;
                }

                if (hasError) {
                    e.preventDefault();
                    // Reset the loading spinner on submit button if form submission is blocked
                    const submitBtn = document.getElementById('submit-btn');
                    const btnText = document.getElementById('btn-text');
                    const btnSpinner = document.getElementById('btn-spinner');
                    const robotBodies = document.querySelectorAll('.robot-body-track');
                    if (btnText && btnSpinner && submitBtn) {
                        btnText.style.display = 'inline-block';
                        btnSpinner.style.display = 'none';
                        submitBtn.disabled = false;
                        submitBtn.style.opacity = '1';
                        submitBtn.style.cursor = 'pointer';
                    }
                    if (robotBodies) {
                        robotBodies.forEach(b => b.classList.remove('jump-animation'));
                    }

                    alert('ไม่สามารถบันทึกได้: เนื่องจากความยาวตัวอักษรของหัวข้อบทความเกิน 120 ตัวอักษร หรือคำอธิบายสรุปบทความเกิน 200 ตัวอักษร กรุณาแก้ไขข้อความให้ไม่เกินกำหนดก่อนกดบันทึกครับ');
                    
                    if (firstErrorElement) {
                        const isEnField = firstErrorElement.id.endsWith('En');
                        if (isEnField) {
                            switchGlobalLanguage('en');
                        } else {
                            switchGlobalLanguage('th');
                        }
                        firstErrorElement.focus();
                        firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
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
    function tinymceImageUploadHandler(blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_image.php');
            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };
            xhr.onload = () => {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.url != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                resolve(json.url);
            };
            xhr.onerror = () => {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };
            const formData = new FormData();
            formData.append('upload', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
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
        tinymce.init({
            selector: `#${id}`,
            plugins: 'autoresize lists link image code table wordcount',
            toolbar: 'blocks | bold italic forecolor backcolor | bullist numlist | alignleft aligncenter alignright alignjustify | link image | removeformat',
            menubar: false,
            color_map: [
                '0663F6', 'Primary Blue',
                '022862', 'Dark Blue',
                '475569', 'Slate',
                '000000', 'Black',
                'FFFFFF', 'White',
                'FF0000', 'Red',
                '00FF00', 'Green',
                '0000FF', 'Blue',
                'FFFF00', 'Yellow',
                'FF9900', 'Orange'
            ],
            custom_colors: true,
            min_height: 250,
            max_height: 800,
            autoresize_bottom_margin: 20,
            content_style: 'body { font-family: "Noto Sans Thai", Inter, ui-sans-serif, system-ui, sans-serif; font-size: 16px; line-height: 1.75; color: #475569; } p, span, li, div { font-size: 16px !important; line-height: 1.75 !important; }',
            images_upload_handler: tinymceImageUploadHandler,
            setup: function (editor) {
                editors[id] = editor;
                editor.addShortcut('ctrl+q', 'Apply Primary Color', function () {
                    editor.execCommand('ForeColor', false, '#0663F6');
                });
            }
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
            editors[textarea.id].remove();
            delete editors[textarea.id];
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
                        if (textarea && editors[id] && typeof editors[id].getContent === 'function') {
                            textarea.value = editors[id].getContent();
                        }
                    }
                }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('article-image-input');
        const statusBox = document.getElementById('articleImageValidationStatus');
        const maxSizeBytes = 1024 * 1024; // 1 MB limit

        if (fileInput) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) {
                    if (statusBox) statusBox.classList.add('hidden');
                    return;
                }

                // 1. Validate File Size (Max 1 MB)
                if (file.size > maxSizeBytes) {
                    const fileSizeKb = Math.round(file.size / 1024);
                    statusBox.className = 'mt-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center gap-2';
                    statusBox.innerHTML = `<span>❌ <strong>ขนาดไฟล์เกิน 1 MB</strong> (ไฟล์ของคุณมีขนาด ${fileSizeKb} KB) กรุณาบีบอัดรูปภาพให้ไม่เกิน 1 MB (แนะนำ 150 - 350 KB)</span>`;
                    statusBox.classList.remove('hidden');
                    fileInput.value = ''; // Reset input
                    return;
                }

                // 2. Validate Extension
                const fileExt = file.name.split('.').pop().toLowerCase();
                const isAllowedExt = ['webp'].includes(fileExt);

                if (!isAllowedExt) {
                    statusBox.className = 'mt-2 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-center gap-2';
                    statusBox.innerHTML = `<span>❌ <strong>นามสกุลไฟล์ไม่ถูกต้อง</strong> รองรับเฉพาะไฟล์รูปภาพ .webp เท่านั้น</span>`;
                    statusBox.classList.remove('hidden');
                    fileInput.value = ''; // Reset input
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
                            ratioText = 'สัดส่วน 16:9 แนวนอน (เหมาะสมที่สุด ⭐)';
                        } else if (width > height) {
                            ratioText = 'แนวนอน';
                        } else {
                            ratioText = 'แนวตั้ง';
                        }

                        const previewImg = document.getElementById('article-img-preview');
                        const placeholder = document.getElementById('article-img-placeholder');
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
                                <span>✅ ไฟล์ผ่านเกณฑ์:</span>
                                <span>${fileSizeKb} KB</span>
                                ${fileSizeKb <= 350 ? '<span class="text-[10px] bg-emerald-100 text-emerald-800 px-1 rounded">PageSpeed เร็วมาก ⚡</span>' : ''}
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

        // Pin Article Single-Constraint Confirmation
        const isPinnedInput = document.getElementById('is_pinned_input');
        const currentlyPinnedTitle = <?= json_encode($pinnedArticle['meta_title'] ?? '', JSON_UNESCAPED_UNICODE) ?>;
        const hasOtherPinned = <?= !empty($pinnedArticle) ? 'true' : 'false' ?>;

        // Category Manager Modal Logic
        const categorySelect = document.getElementById('category_select');
        const quickCatModal = document.getElementById('quickCategoryModal');
        const newCatNameInput = document.getElementById('newCategoryName');
        const newCatSlugInput = document.getElementById('newCategorySlug');
        const catModalError = document.getElementById('categoryModalError');
        const btnSaveCategory = document.getElementById('btnSaveCategory');
        const btnSaveCategoryText = document.getElementById('btnSaveCategoryText');
        const btnSaveCategorySpinner = document.getElementById('btnSaveCategorySpinner');
        const modalCatCountBadge = document.getElementById('modalCatCountBadge');
        const modalCategoryListBody = document.getElementById('modalCategoryListBody');
        const CATEGORY_API_URL = 'ajax_category_actions.php';
        let lastSelectedCategory = categorySelect ? categorySelect.value : '';

        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                if (this.value === '__manage__' || this.value === '__new__') {
                    openCategoryModal();
                } else {
                    lastSelectedCategory = this.value;
                }
            });
        }

        window.openCategoryModal = function() {
            if (!quickCatModal) return;
            if (newCatNameInput) newCatNameInput.value = '';
            if (newCatSlugInput) newCatSlugInput.value = '';
            if (catModalError) {
                catModalError.classList.add('hidden');
                catModalError.style.display = 'none';
                catModalError.textContent = '';
            }
            quickCatModal.style.display = 'flex';
            loadCategoryListInModal();
            setTimeout(() => {
                if (newCatNameInput) newCatNameInput.focus();
            }, 60);
        };

        window.closeCategoryModal = function() {
            if (!quickCatModal) return;
            quickCatModal.style.display = 'none';
            if (categorySelect && (categorySelect.value === '__manage__' || categorySelect.value === '__new__')) {
                categorySelect.value = lastSelectedCategory;
            }
        };

        function getCsrfToken() {
            const csrfTokenInput = document.querySelector('input[name="_csrf"]') || document.querySelector('input[name="csrf_token"]');
            return csrfTokenInput ? csrfTokenInput.value : '<?= csrf_token() ?>';
        }

        // Fetch and Render Category Table in Modal
        window.loadCategoryListInModal = async function() {
            if (!modalCategoryListBody) return;
            try {
                const res = await fetch(CATEGORY_API_URL + '?action=list');
                const data = await res.json();
                if (data.success && Array.isArray(data.categories)) {
                    renderModalCategoryTable(data.categories);
                    syncCategorySelectDropdown(data.categories);
                } else {
                    modalCategoryListBody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-rose-500 text-xs">ไม่สามารถโหลดรายการหมวดหมู่ได้</td></tr>';
                }
            } catch (e) {
                modalCategoryListBody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-rose-500 text-xs">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
            }
        };

        function renderModalCategoryTable(categories) {
            if (!modalCategoryListBody) return;
            if (modalCatCountBadge) {
                modalCatCountBadge.textContent = `${categories.length} หมวดหมู่`;
            }

            if (categories.length === 0) {
                modalCategoryListBody.innerHTML = '<tr><td colspan="4" class="px-4 py-6 text-center text-slate-400 text-xs">ยังไม่มีหมวดหมู่ในระบบ</td></tr>';
                return;
            }

            let html = '';
            categories.forEach(cat => {
                const id = cat.id;
                const name = cat.name.replace(/"/g, '&quot;');
                const slug = cat.slug.replace(/"/g, '&quot;');
                const count = parseInt(cat.article_count) || 0;

                html += `
                <tr id="modal-cat-row-${id}" class="hover:bg-slate-50 transition">
                    <td class="px-4 py-2.5 font-semibold text-slate-900">
                        <div class="mcat-name-view">${name}</div>
                        <div class="mcat-name-edit hidden">
                            <input type="text" class="mcat-input-name w-full rounded-lg border border-blue-400 px-2 py-1 text-xs text-slate-900 focus:outline-none" value="${name}">
                        </div>
                    </td>
                    <td class="px-4 py-2.5 text-slate-500 font-mono text-[11px]">
                        <div class="mcat-slug-view">${slug}</div>
                        <div class="mcat-slug-edit hidden">
                            <input type="text" class="mcat-input-slug w-full rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 focus:outline-none" value="${slug}">
                        </div>
                    </td>
                    <td class="px-4 py-2.5 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold ${count > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-500'}">
                            ${count} บทความ
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap" style="padding: 12px 16px; text-align: right;">
                        <div class="mcat-action-view" style="display: inline-flex; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                            <button type="button" onclick="startEditCategoryModal(${id})" style="padding: 6px 15px; font-size: 12px; font-weight: 600; color: #2563eb; background: #fff; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <span>✏️ แก้ไข</span>
                            </button>
                            <button type="button" onclick="deleteCategoryModal(${id}, '${name.replace(/'/g, "\\'")}', ${count})" style="padding: 6px 15px; font-size: 12px; font-weight: 600; color: #e11d48; background: #fff; border: none; border-left: 1px solid #e2e8f0; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <span>🗑️ ลบ</span>
                            </button>
                        </div>
                        <div class="mcat-action-edit hidden" style="display: none; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                            <button type="button" onclick="saveEditCategoryModal(${id})" style="padding: 6px 15px; font-size: 12px; font-weight: 600; color: #fff; background: #059669; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <span>💾 บันทึก</span>
                            </button>
                            <button type="button" onclick="cancelEditCategoryModal(${id})" style="padding: 6px 15px; font-size: 12px; font-weight: 600; color: #475569; background: #fff; border: none; border-left: 1px solid #e2e8f0; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                <span>ยกเลิก</span>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });

            modalCategoryListBody.innerHTML = html;
        }

        // Synchronize <select id="category_select"> with latest categories
        function syncCategorySelectDropdown(categories, selectId = null) {
            if (!categorySelect) return;
            const currentVal = selectId !== null ? String(selectId) : categorySelect.value;

            let html = '<option value="">เลือกหมวดหมู่ที่ต้องการ...</option>';
            categories.forEach(cat => {
                const isSelected = String(cat.id) === String(currentVal) ? 'selected' : '';
                html += `<option value="${cat.id}" ${isSelected}>${cat.name}</option>`;
            });
            html += '<option disabled>──────────</option>';
            html += '<option value="__manage__" class="font-bold text-blue-600 bg-blue-50 py-1">⚙️ จัดการหมวดหมู่ (เพิ่ม / แก้ไข / ลบ)...</option>';

            categorySelect.innerHTML = html;
            if (selectId !== null) {
                categorySelect.value = String(selectId);
                lastSelectedCategory = String(selectId);
            }
        }

        // Add Category
        window.submitNewCategory = async function() {
            if (!newCatNameInput) return;
            const name = newCatNameInput.value.trim();
            const slug = newCatSlugInput ? newCatSlugInput.value.trim() : '';

            if (!name) {
                if (catModalError) {
                    catModalError.textContent = 'กรุณากรอกชื่อหมวดหมู่';
                    catModalError.classList.remove('hidden');
                    catModalError.style.display = 'block';
                }
                newCatNameInput.focus();
                return;
            }

            if (catModalError) {
                catModalError.classList.add('hidden');
                catModalError.style.display = 'none';
            }
            if (btnSaveCategory) btnSaveCategory.disabled = true;
            if (btnSaveCategoryText) btnSaveCategoryText.textContent = 'กำลังเพิ่ม...';
            if (btnSaveCategorySpinner) btnSaveCategorySpinner.classList.remove('hidden');

            try {
                const formData = new FormData();
                formData.append('action', 'create');
                formData.append('name', name);
                formData.append('slug', slug);
                formData.append('_csrf', getCsrfToken());

                const response = await fetch(CATEGORY_API_URL, { method: 'POST', body: formData });
                const result = await response.json();

                if (result && result.success) {
                    newCatNameInput.value = '';
                    if (newCatSlugInput) newCatSlugInput.value = '';
                    await loadCategoryListInModal();
                    if (result.id) {
                        syncCategorySelectDropdown([], result.id);
                    }
                } else {
                    if (catModalError) {
                        catModalError.textContent = result.message || 'ไม่สามารถเพิ่มหมวดหมู่ได้';
                        catModalError.classList.remove('hidden');
                        catModalError.style.display = 'block';
                    }
                }
            } catch (err) {
                if (catModalError) {
                    catModalError.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์';
                    catModalError.classList.remove('hidden');
                    catModalError.style.display = 'block';
                }
            } finally {
                if (btnSaveCategory) btnSaveCategory.disabled = false;
                if (btnSaveCategoryText) btnSaveCategoryText.textContent = '+ เพิ่ม';
                if (btnSaveCategorySpinner) btnSaveCategorySpinner.classList.add('hidden');
            }
        };

        // Edit Category inline
        window.startEditCategoryModal = function(id) {
            const row = document.getElementById('modal-cat-row-' + id);
            if (!row) return;
            row.querySelector('.mcat-name-view').classList.add('hidden');
            row.querySelector('.mcat-name-edit').classList.remove('hidden');
            row.querySelector('.mcat-slug-view').classList.add('hidden');
            row.querySelector('.mcat-slug-edit').classList.remove('hidden');
            row.querySelector('.mcat-action-view').style.display = 'none';
            row.querySelector('.mcat-action-edit').style.display = 'inline-flex';
            row.querySelector('.mcat-action-edit').classList.remove('hidden');
            row.querySelector('.mcat-input-name').focus();
        };

        window.cancelEditCategoryModal = function(id) {
            const row = document.getElementById('modal-cat-row-' + id);
            if (!row) return;
            row.querySelector('.mcat-name-view').classList.remove('hidden');
            row.querySelector('.mcat-name-edit').classList.add('hidden');
            row.querySelector('.mcat-slug-view').classList.remove('hidden');
            row.querySelector('.mcat-slug-edit').classList.add('hidden');
            row.querySelector('.mcat-action-view').style.display = 'inline-flex';
            row.querySelector('.mcat-action-edit').style.display = 'none';
            row.querySelector('.mcat-action-edit').classList.add('hidden');
        };

        window.saveEditCategoryModal = async function(id) {
            const row = document.getElementById('modal-cat-row-' + id);
            if (!row) return;
            const name = row.querySelector('.mcat-input-name').value.trim();
            const slug = row.querySelector('.mcat-input-slug').value.trim();

            if (!name) {
                alert('กรุณากรอกชื่อหมวดหมู่');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'update');
                formData.append('id', id);
                formData.append('name', name);
                formData.append('slug', slug);
                formData.append('_csrf', getCsrfToken());

                const res = await fetch(CATEGORY_API_URL, { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    await loadCategoryListInModal();
                } else {
                    alert(data.message || 'ไม่สามารถแก้ไขได้');
                }
            } catch (e) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            }
        };

        // Delete Category
        window.deleteCategoryModal = async function(id, name, count) {
            let msg = `คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่ "${name}"?`;
            if (count > 0) {
                msg += `\n\n⚠️ มีบทความ ${count} เรื่องอยู่ในหมวดหมู่นี้ (บทความจะไม่ถูกลบ แต่จะถูกเปลี่ยนเป็น "ไม่มีหมวดหมู่")`;
            }

            if (!confirm(msg)) return;

            try {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                formData.append('_csrf', getCsrfToken());

                const res = await fetch(CATEGORY_API_URL, { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    if (String(categorySelect.value) === String(id)) {
                        lastSelectedCategory = '';
                    }
                    await loadCategoryListInModal();
                } else {
                    alert(data.message || 'ไม่สามารถลบได้');
                }
            } catch (e) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            }
        };

        if (newCatNameInput) {
            newCatNameInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitNewCategory();
                }
            });
        }
        if (newCatSlugInput) {
            newCatSlugInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitNewCategory();
                }
            });
        }
    });
</script>