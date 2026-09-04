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
    $cleanRaw = preg_replace('/<\?xml[^>]*\?>/i', '', $rawContent);
    $cleanRaw = preg_replace('/<!--\?xml[^>]*-->/i', '', $cleanRaw);

    $decoded = json_decode($cleanRaw, true);
    if (!is_array($decoded)) {
        $decoded = json_decode(stripslashes($cleanRaw), true);
    }
    if (!is_array($decoded)) {
        // Regex fallback for malformed JSON strings in database
        $decoded = [];
        preg_match_all('/\{\s*"lang"\s*:\s*"(th|en)"\s*,\s*"topic"\s*:\s*"(.*?)"\s*,\s*"body"\s*:\s*"(.*?)"\s*\}/s', $cleanRaw, $matches, PREG_SET_ORDER);
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
                $sec['body'] = preg_replace('/<\?xml[^>]*\?>/i', '', (string)$sec['body']);
                $sec['body'] = preg_replace('/<!--\?xml[^>]*-->/i', '', (string)$sec['body']);
                $sec['body'] = str_replace(['<\/', '\"', '\/', '<\span>', '<\p>', '<\strong>', '<\h2>', '<\h3>', '<\div>', '§', '&sect;'], ['</', '"', '/', '</span>', '</p>', '</strong>', '</h2>', '</h3>', '</div>', '', ''], $sec['body']);
                $sec['body'] = trim($sec['body']);
            }
            if (isset($sec['topic'])) {
                $sec['topic'] = trim((string)$sec['topic']);
            }
        }
        unset($sec);
        $sections = $decoded;
    } else {
        $sections = [
            ['lang' => 'th', 'topic' => 'เนื้อหาบทความ', 'body' => trim($cleanRaw)]
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
        font-family: 'Noto Sans Thai', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 15px !important;
        line-height: 1.8 !important;
    }
    .editor-frame .ck.ck-content * {
        font-family: 'Noto Sans Thai', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
    }
    .editor-frame .ck.ck-content:focus,
    .editor-frame .ck.ck-focused {
        box-shadow: none !important;
    }
    /* Make TinyMCE sit flush inside our rounded/bordered frame */
    .editor-frame .tox-tinymce {
        border: none !important;
        border-radius: 0 !important;
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
            <!-- Draft Recovery Banner (Auto-save) -->
            <div id="draftRecoveryAlert" class="hidden rounded-2xl border border-amber-300 bg-amber-50/90 p-4 sm:p-5 shadow-sm transition-all">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-700 shrink-0 text-lg">
                            💾
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900 flex items-center gap-2">
                                <span>พบข้อมูลบทความฉบับร่างที่บันทึกไว้ในเครื่องของคุณ</span>
                                <span id="draftSavedTime" class="text-xs font-normal text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full"></span>
                            </h4>
                            <p class="text-xs text-amber-800 mt-0.5">
                                คุณมีการพิมพ์บทความทิ้งไว้ก่อนหน้านี้ ต้องการกู้คืนเนื้อหากลับมาพิมพ์ต่อหรือไม่?
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" id="btnRestoreDraft" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>กู้คืนเนื้อหา</span>
                        </button>
                        <button type="button" id="btnDiscardDraft" class="px-3 py-2 bg-white hover:bg-amber-100/50 text-amber-800 border border-amber-200 text-xs font-medium rounded-xl transition-colors">
                            ไม่ต้องการ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Auto-save Status Indicator -->
            <div class="flex items-center justify-between">
                <!-- Language Toggle Tabs (Global for Form) -->
                <div class="inline-flex items-center gap-2">
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
                <!-- Local auto-save indicator badge -->
                <div id="autoSaveBadge" class="text-xs text-slate-400 flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                    <span id="autoSaveText">ระบบบันทึกร่างอัตโนมัติเปิดทำงาน</span>
                </div>
            </div>
            <section class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">ตั้งค่ารูปภาพหน้าปก</h3>
                    <p class="text-xs text-slate-500 mt-0.5">อัปโหลดและจัดการรูปภาพหลักสำหรับใช้แสดงผลในบทความนี้</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                            ตัวอย่างรูปภาพหน้าปก
                        </label>
                        <div class="w-full h-64 rounded-xl border border-slate-200 bg-slate-50 p-2 flex items-center justify-center overflow-hidden" id="coverImagePreviewContainer">
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
                    <div class="flex flex-col justify-center space-y-4">
                        <div class="space-y-3">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                เลือกไฟล์รูปภาพใหม่ <?php if ($action === 'create' && empty($data['cover_image'])): ?><span class="text-red-500 ml-0.5">*</span><?php endif; ?>
                            </label>
                            <style>
                                #coverImageInput::file-selector-button,
                                #coverImageInput::-webkit-file-upload-button {
                                    background-color: #f1f5f9 !important;
                                    color: #0f172a !important;
                                    border: 1px solid #cbd5e1 !important;
                                    font-weight: 600 !important;
                                    font-size: 13px !important;
                                    padding: 8px 16px !important;
                                    border-radius: 10px !important;
                                    cursor: pointer !important;
                                    margin-right: 12px !important;
                                    transition: all 0.15s ease-in-out !important;
                                }
                                #coverImageInput::file-selector-button:hover,
                                #coverImageInput::-webkit-file-upload-button:hover {
                                    background-color: #e2e8f0 !important;
                                    color: #000000 !important;
                                    border-color: #94a3b8 !important;
                                }
                            </style>
                            <div style="border: 1px solid #e2e8f0; border-radius: 12px; background-color: #f8faff; padding: 12px 14px;">
                                <input type="file"
                                    id="coverImageInput"
                                    name="image_file"
                                    accept=".webp,image/webp"
                                    class="w-full text-sm text-slate-600 transition-all cursor-pointer">
                            </div>
                            <!-- Guidance Box exactly matching Target Design -->
                            <div style="border: 1px solid #dbeafe; border-radius: 16px; background-color: #ffffff; padding: 20px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02);" class="space-y-3.5">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13px; color: #0f172a;">
                                    <svg style="width: 18px; height: 18px; color: #2563eb; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span>ข้อกำหนดและขนาดภาพหน้าปกที่แนะนำ (SEO & SPEED)</span>
                                </div>
                                <div style="font-size: 12px; line-height: 1.6;">
                                    <div style="font-weight: 700; color: #1e293b;">นามสกุลไฟล์ที่รองรับ:</div>
                                    <div style="color: #64748b;">รองรับเฉพาะไฟล์ <strong style="color: #1e293b; font-weight: 700;">.webp</strong> เท่านั้น</div>
                                </div>
                                <div style="border-top: 1px solid #f1f5f9; margin-top: 10px; margin-bottom: 10px;"></div>
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                                    <span style="font-weight: 700; color: #1e293b;">ขนาดไฟล์:</span>
                                    <span style="background-color: #d1fae5; color: #065f46; padding: 4px 14px; border-radius: 9999px; font-size: 11px; font-weight: 700; letter-spacing: -0.01em;">
                                        ไม่เกิน 1 MB (แนะนำ 150 – 350 KB)
                                    </span>
                                </div>
                                <div style="border-top: 1px solid #f1f5f9; margin-top: 10px; margin-bottom: 10px;"></div>
                                <div style="font-size: 12px; line-height: 1.6;">
                                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">สัดส่วนและขนาดภาพที่เหมาะสม:</div>
                                    <div style="color: #1e293b;"><strong style="font-weight: 700;">แนะนำ: 1280 × 720 px</strong> <span style="color: #64748b;">(สัดส่วน 16:9)</span></div>
                                    <div style="color: #64748b;">ขนาดขั้นต่ำ: 800 × 450 px</div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                ข้อความอธิบายรูปภาพ (SEO ALT TEXT) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input name="cover_image_alt"
                                value="<?= e($data['cover_image_alt'] ?? '') ?>"
                                placeholder="ตัวอย่าง: 'หน้าจอระบบบริหารจัดการ ERP บัญชีสำหรับองค์กรธุรกิจและโรงงาน'"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/5 outline-none transition-all duration-200"
                                required>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>คำแนะนำ SEO:</strong> อธิบายสิ่งที่อยู่ในภาพ + ใส่คำค้นหา เพื่อให้ติดอันดับ <strong>Google Image Search</strong> ( <span class="text-amber-600 font-medium">⚠️ หลีกเลี่ยงการใส่ตัวเลขสั้นๆ เช่น 01 หรือ image1</span> )
                            </p>
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
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    หัวข้อบทความ (Article Title) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCount" class="text-xs font-semibold text-slate-500 px-2 py-0.5 rounded transition-all duration-200">0 / 120 (แนะนำ 50-60 ตัวอักษร)</span>
                            </div>
                            <input id="mainTitle"
                                name="meta_title"
                                maxlength="120"
                                value="<?= e($data['meta_title'] ?? '') ?>"
                                placeholder="ระบุหัวข้อบทความหลัก..."
                                class="<?= $inputClass ?>"
                                required>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>เคล็ดลับ Title:</strong> วางคำค้นหาสำคัญ (Keyword) ไว้ <strong>50–60 ตัวอักษรแรก</strong> เพื่อให้แสดงผลครบถ้วนบนหน้าค้นหา Google
                            </p>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    คำอธิบายสรุปบทความ (Article Summary) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCount" class="text-xs font-semibold text-slate-500 px-2 py-0.5 rounded transition-all duration-200">0 / 200 (แนะนำ 120-160 ตัวอักษร)</span>
                            </div>
                            <textarea id="metaDesc"
                                name="meta_description"
                                maxlength="200"
                                rows="4"
                                placeholder="เขียนคำอธิบายสั้น ๆ สรุปเนื้อหาบทความ..."
                                class="<?= $inputClass ?>"
                                required><?= e($data['meta_description'] ?? '') ?></textarea>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>เคล็ดลับ Meta Description:</strong> ความยาวที่เหมาะสมที่สุดคือ <strong>120–160 ตัวอักษร</strong> สรุปเนื้อหาและมีคำกระตุ้นให้คลิก ช่วยเพิ่มอัตราการคลิกเข้าชม (CTR)
                            </p>
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
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>คำแนะนำ Keywords:</strong> ระบุคำค้นหาหลักที่เกี่ยวข้อง คั่นด้วยเครื่องหมายจุลภาค <code>,</code> (แท็ก <code>&lt;meta name="keywords"&gt;</code> ในโค้ดหน้าเว็บ)
                            </p>
                        </div>
                    </div>
                    <!-- English SEO Fields -->
                    <div class="lang-group lang-en-group space-y-5 hidden">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    หัวข้อบทความ (EN) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="titleCountEn" class="text-xs font-semibold text-slate-500 px-2 py-0.5 rounded transition-all duration-200">0 / 120 (Recommended 50-60 chars)</span>
                            </div>
                            <input id="mainTitleEn"
                                name="meta_title_en"
                                maxlength="120"
                                value="<?= e($data['meta_title_en'] ?? '') ?>"
                                placeholder="Enter English SEO Title..."
                                class="<?= $inputClass ?>">
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>SEO Title (EN):</strong> Place main keywords within the first <strong>50–60 characters</strong> for complete visibility on Google Search.
                            </p>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-medium text-slate-700">
                                    SEO Description (English) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span id="descCountEn" class="text-xs font-semibold text-slate-500 px-2 py-0.5 rounded transition-all duration-200">0 / 200 (Recommended 120-160 chars)</span>
                            </div>
                            <textarea id="metaDescEn"
                                name="meta_description_en"
                                maxlength="200"
                                rows="4"
                                placeholder="Enter English SEO Description..."
                                class="<?= $inputClass ?>"><?= e($data['meta_description_en'] ?? '') ?></textarea>
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>Meta Description Tip:</strong> Optimal length is <strong>120–160 characters</strong> to summarize content and improve CTR.
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block text-slate-700">
                                SEO Keywords (English) <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input name="meta_keywords_en"
                                value="<?= e($data['meta_keywords_en'] ?? '') ?>"
                                placeholder="Enter keywords separated by commas..."
                                class="<?= $inputClass ?>">
                            <p class="text-[11px] text-slate-500 leading-relaxed mt-1.5">
                                💡 <strong>SEO Keywords (EN):</strong> Enter relevant search terms separated by commas <code>,</code>
                            </p>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <div class="w-full">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    หมวดหมู่บทความ <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <button type="button" onclick="openCategoryModal()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 underline cursor-pointer inline-flex items-center gap-1">
                                    <span>⚙️</span> จัดการหมวดหมู่
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
                            <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                                <span>💡</span>
                                <strong>คำแนะนำ:</strong> เลือกหมวดหมู่ที่ตรงกับเนื้อหา หรือคลิก <strong>จัดการหมวดหมู่</strong> เพื่อสร้างใหม่
                            </p>
                        </div>
                        <div class="w-full">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">
                                วันที่เผยแพร่บทความ <span class="text-red-500 ml-0.5">*</span>
                            </label>
                            <input type="date" name="created_at"
                                value="<?= isset($data['created_at']) ? date('Y-m-d', strtotime($data['created_at'])) : date('Y-m-d') ?>"
                                class="<?= $inputClass ?> bg-white h-[46px]" required>
                            <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                                <span>💡</span>
                                <strong>คำแนะนำ:</strong> กำหนดวันที่ที่ต้องการให้แสดงผลเป็นวันเผยแพร่บนเว็บไซต์
                            </p>
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
                            <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                                <span>💡</span>
                                <strong>คำแนะนำ:</strong> ปักหมุดบทความนี้ให้อยู่ลำดับแรกสุดในหน้าเว็บ (ระบบจำกัด 1 บทความ)
                            </p>
                        </div>
                    </div>
                    <div class="lang-group lang-th-group space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    ลิงก์บทความ (URL SLUG) <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <span class="text-xs text-slate-400">ภาษาอังกฤษตัวพิมพ์เล็ก คั่นด้วยขีดกลาง (-)</span>
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
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">
                                    URL SLUG (ENGLISH)
                                </label>
                                <span class="text-xs text-slate-400">Lowercase English letters separated by hyphens (-)</span>
                            </div>
                            <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-slate-50 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/5 transition-all">
                                <span class="flex items-center px-4 bg-slate-100 text-sm text-slate-500 border-r border-slate-200 select-none">
                                    /en/article/
                                </span>
                                <input id="slug_en"
                                    name="slug_en"
                                    value="<?= e($data['slug_en'] ?? '') ?>"
                                    placeholder="e.g. erp-accounting-guide-2026"
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
                        <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                            <span>💡</span>
                            <strong>คำแนะนำ:</strong> ระบุลิงก์แหล่งที่มาของข้อมูล หรือชื่อผู้แต่ง/เอกสารอ้างอิง (ถ้ามี)
                        </p>
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
            <section class="sticky bottom-0 bg-white/95 backdrop-blur-sm p-3 sm:p-4 -m-4 rounded-2xl border border-slate-200 shadow-sm z-20">
                <div class="form-sticky-bar">
                    <a href="index.php" class="btn-cancel flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium transition">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 sm:hidden" style="width: 13px; height: 13px;"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        <span>ยกเลิก</span>
                    </a>
                    <div class="actions-group">
                        <button type="submit" name="status" value="draft" 
                            class="rounded-xl border bg-amber-50 border-amber-300 text-amber-700 font-semibold hover:bg-amber-100 transition flex items-center justify-center gap-1 sm:gap-2">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0 sm:hidden" style="width: 13px; height: 13px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                            <span class="hidden sm:inline">บันทึกเป็น</span>ฉบับร่าง
                        </button>
                        <button type="submit" name="status" value="hidden" 
                            class="rounded-xl border bg-slate-100 border-slate-300 text-slate-600 font-semibold hover:bg-slate-200 transition flex items-center justify-center gap-1 sm:gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="shrink-0" style="width: 14px; height: 14px; min-width: 14px; max-width: 14px; max-height: 14px;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            <span class="hidden sm:inline">บันทึกและ</span>ซ่อน
                        </button>
                        <button type="submit" name="status" value="published" 
                            class="rounded-xl border bg-emerald-50 border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-100 transition flex items-center justify-center gap-1 sm:gap-2">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="shrink-0 sm:hidden" style="width: 13px; height: 13px;"><polyline points="20 6 9 17 4 12"/></svg>
                            <span class="hidden sm:inline">บันทึกและ</span>เผยแพร่
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
<script src="../assets/js/seo-editor.js?v=<?= time() ?>"></script>
<script>
    function updateLiveSeoFieldStatus(input, counter, len, minOpt, maxOpt, maxLimit, optLabel, isThai = true) {
        if (!input || !counter) return;

        if (len === 0) {
            counter.textContent = `0 / ${maxLimit} (${optLabel})`;
            counter.style.cssText = 'color: #64748b; background: transparent; border: none; font-weight: 500; padding: 2px 4px;';
            input.style.borderColor = '';
            input.style.backgroundColor = '';
            input.style.boxShadow = '';
            return;
        }

        if (len >= maxLimit) {
            const msg = isThai ? `⛔ ครบกำหนดสูงสุด ${maxLimit} ตัวอักษรแล้ว` : `⛔ Max limit ${maxLimit} reached`;
            counter.textContent = `${len} / ${maxLimit} (${msg})`;
            counter.style.cssText = 'color: #b91c1c !important; background-color: #fee2e2 !important; border: 1px solid #f87171 !important; font-weight: 700 !important; padding: 2px 8px; border-radius: 6px;';
            input.style.cssText = 'border-color: #ef4444 !important; background-color: #fef2f2 !important; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.25) !important;';
        } else if (len > maxOpt) {
            const msg = isThai ? `⚠️ เกินคำแนะนำ ${minOpt}-${maxOpt} ตัวอักษร` : `⚠️ Exceeds recommended ${minOpt}-${maxOpt} chars`;
            counter.textContent = `${len} / ${maxLimit} (${msg})`;
            counter.style.cssText = 'color: #92400e !important; background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; font-weight: 700 !important; padding: 2px 8px; border-radius: 6px;';
            input.style.cssText = 'border-color: #f59e0b !important; background-color: #fffdf5 !important; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.25) !important;';
        } else if (len >= minOpt && len <= maxOpt) {
            const msg = isThai ? `✅ เหมาะสมที่สุด` : `✅ Optimal length`;
            counter.textContent = `${len} / ${maxLimit} (${optLabel}) ${msg}`;
            counter.style.cssText = 'color: #047857 !important; background-color: #ecfdf5 !important; border: 1px solid #6ee7b7 !important; font-weight: 700 !important; padding: 2px 8px; border-radius: 6px;';
            input.style.cssText = 'border-color: #10b981 !important; background-color: #f0fdf4 !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2) !important;';
        } else {
            counter.textContent = `${len} / ${maxLimit} (${optLabel})`;
            counter.style.cssText = 'color: #334155; background: #f1f5f9; border: 1px solid #cbd5e1; font-weight: 600; padding: 2px 8px; border-radius: 6px;';
            input.style.borderColor = '';
            input.style.backgroundColor = '';
            input.style.boxShadow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Thai Fields
        const titleTh = document.getElementById('mainTitle');
        const descTh = document.getElementById('metaDesc');
        const titleCountTh = document.getElementById('titleCount');
        const descCountTh = document.getElementById('descCount');
        const slugTh = document.getElementById('slug');

        function updateThaiCounters() {
            if (titleTh && titleCountTh) {
                updateLiveSeoFieldStatus(titleTh, titleCountTh, titleTh.value.length, 50, 60, 120, 'แนะนำ 50-60 ตัวอักษร', true);
            }
            if (descTh && descCountTh) {
                updateLiveSeoFieldStatus(descTh, descCountTh, descTh.value.length, 120, 160, 200, 'แนะนำ 120-160 ตัวอักษร', true);
            }
        }

        if (titleTh) {
            ['input', 'keyup', 'change', 'paste', 'focus', 'blur'].forEach(evt => {
                titleTh.addEventListener(evt, () => {
                    updateThaiCounters();
                    if (slugTh && (!slugTh.value || slugTh.dataset.autoSlug !== 'false')) {
                        slugTh.value = titleTh.value.toLowerCase().replace(/[^a-z0-9\u0E00-\u0E7F]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
                    }
                });
            });
        }
        if (descTh) {
            ['input', 'keyup', 'change', 'paste', 'focus', 'blur'].forEach(evt => {
                descTh.addEventListener(evt, updateThaiCounters);
            });
        }
        if (slugTh) {
            slugTh.addEventListener('input', () => {
                slugTh.dataset.autoSlug = 'false';
            });
        }
        updateThaiCounters();

        // English Fields
        const titleEn = document.getElementById('mainTitleEn');
        const descEn = document.getElementById('metaDescEn');
        const titleCountEn = document.getElementById('titleCountEn');
        const descCountEn = document.getElementById('descCountEn');
        const slugEn = document.getElementById('slug_en');

        function updateEnCounters() {
            if (titleEn && titleCountEn) {
                updateLiveSeoFieldStatus(titleEn, titleCountEn, titleEn.value.length, 50, 60, 120, 'Recommended 50-60 chars', false);
            }
            if (descEn && descCountEn) {
                updateLiveSeoFieldStatus(descEn, descCountEn, descEn.value.length, 120, 160, 200, 'Recommended 120-160 chars', false);
            }
        }

        if (titleEn) {
            ['input', 'keyup', 'change', 'paste', 'focus', 'blur'].forEach(evt => {
                titleEn.addEventListener(evt, () => {
                    updateEnCounters();
                    if (slugEn && (!slugEn.value || slugEn.dataset.autoSlug !== 'false')) {
                        slugEn.value = titleEn.value.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
                    }
                });
            });
        }
        if (descEn) {
            ['input', 'keyup', 'change', 'paste', 'focus', 'blur'].forEach(evt => {
                descEn.addEventListener(evt, updateEnCounters);
            });
        }
        if (slugEn) {
            slugEn.addEventListener('input', () => {
                slugEn.dataset.autoSlug = 'false';
            });
        }
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

    /**
     * คลีนสไตล์ขยะของ Microsoft Word โดยตัดเฉพาะ mso-*, line-height บีบแคบ, font-family
     * แต่คงค่าสำคัญ: color, background-color, font-weight: bold, text-decoration ไว้ครบถ้วน
     */
    function cleanWordStyle(styleContent) {
        if (!styleContent) return '';
        const decls = styleContent.split(';').map(d => d.trim()).filter(Boolean);
        const kept = [];

        for (const decl of decls) {
            const colonIdx = decl.indexOf(':');
            if (colonIdx === -1) continue;
            const prop = decl.substring(0, colonIdx).trim().toLowerCase();
            const val = decl.substring(colonIdx + 1).trim();

            // ตัดฟังก์ชันเฉพาะของ Microsoft Office (mso-*)
            if (prop.startsWith('mso-')) continue;
            // ตัด line-height บีบแคบของ Word (เช่น 115%) ที่ทำให้สระ/วรรณยุกต์ภาษาไทยซ้อนทับกัน
            if (prop === 'line-height' && val.includes('%')) continue;
            // ตัด font-family เพื่อให้เนื้อหาแสดงผลด้วยฟอนต์ Noto Sans Thai / Inter มาตรฐานของเว็บไซต์
            if (prop === 'font-family') continue;
            // ตัดขนาดตัวอักษร pt คงที่ของ Word เพื่อให้ระบบจัดการ Responsive แสดงผลสวยงามทุกหน้าจอ
            if (prop === 'font-size' && (val.includes('pt') || val === '16px' || val === '14px' || val === '12px')) continue;

            // text-indent ของ Word ถูกแปลงเป็น &emsp;&emsp; ในข้อความแล้ว จึงตัด style นี้ออกเพื่อไม่ให้เกิดการย่อหน้าซ้ำซ้อน
            if (prop === 'text-indent') {
                continue;
            }

            // แปลงระยะเยื้องซ้ายทั้งย่อหน้าของ Word (เช่น margin-left: 36pt -> 2rem)
            if (prop === 'margin-left') {
                const num = parseFloat(val);
                if (num >= 20) {
                    kept.push('margin-left: 2rem');
                }
                continue;
            }

            kept.push(`${prop}: ${val}`);
        }

        return kept.join('; ');
    }

    /**
     * แปลงและทำความสะอาดเนื้อหาจาก Microsoft Word อย่างสมบูรณ์:
     * 1. สมานรอยต่อแท็ก HTML ที่โดน Word ตัดขึ้นบรรทัดใหม่ (Hard Line-Wrap) ป้องกันโค้ดแปลกปลอมหลุด (ภาพ 1)
     * 2. รักษาย่อหน้า (Indent / Tab / 4+ spaces) ให้เป็น &emsp;&emsp; หรือ text-indent: 2rem เหมือนใน Word 100%
     * 3. กรองโค้ดขยะเฉพาะ Word (mso-*, <o:p>, Comments, lang=TH/EN)
     * 4. รักษาย่อหน้าให้เป็นก้อนเดียวกัน (ไม่ผ่าเนื้อหาใน <p> ออกเป็นหลายบรรทัด)
     * 5. คงตัวหนา (Bold), ตัวเอียง (Italic), ขีดเส้นใต้ (Underline), สีข้อความ (Color) ไว้ 100%
     * 6. แก้ปัญหาตัวเลขหัวข้อรีเซ็ตเป็น 1-1-1 โดยคงตัวเลขเดิมไว้ (ภาพ 2)
     * 7. จัดกลุ่ม Bullet ให้เป็นแท็ก <ul><li> ที่สวยงามและเยื้องระยะตรงตาม Word (ภาพ 3)
     */
    function autoConvertBullets(input) {
        if (!input || typeof input !== 'string') return input;

        let str = input;

        // 1. สมานรอยต่อแท็ก HTML ที่ถูก Word ตัดขึ้นบรรทัดใหม่ (Hard Line Folding) ผ่ากลางแท็ก
        str = str.replace(/<[^>]+>/g, function (tag) {
            return tag.replace(/[\r\n\t]+/g, ' ');
        });

        // 2. แปลง text-indent ของ Word บนแท็ก <p> ให้เป็นย่อหน้า &emsp;&emsp; ทันที
        str = str.replace(/<p\b([^>]*)style=(['"])([\s\S]*?)\2([^>]*)>([\s\S]*?)<\/p>/gi, function(match, pre, q, style, post, inner) {
            const indentMatch = style.match(/text-indent:\s*([^;]+)/i);
            if (indentMatch && parseFloat(indentMatch[1]) > 0) {
                let cleanInner = inner.trim();
                if (!cleanInner.startsWith('&emsp;')) {
                    cleanInner = '&emsp;&emsp;' + cleanInner;
                }
                return `<p${pre}style=${q}${style}${q}${post}>${cleanInner}</p>`;
            }
            return match;
        });

        // 3. แปลง Tab ของ Word: <span style='mso-tab-count:...'> ให้เป็นย่อหน้า &emsp;&emsp;
        str = str.replace(/<span[^>]*style=['"][^'"]*mso-tab-count:[^'"]*['"][^>]*>[\s\S]*?<\/span>/gi, '&emsp;&emsp;');

        // 4. แปลงชุดเคาะ Spacebar หลายครั้งของ Word: <span style='mso-spacerun:yes'>
        str = str.replace(/<span[^>]*style=['"][^'"]*mso-spacerun:[^'"]*['"][^>]*>([\s\S]*?)<\/span>/gi, function(match, spaces) {
            const raw = spaces.replace(/&nbsp;|\u00A0/gi, ' ');
            if (raw.length >= 2) {
                return '&emsp;&emsp;';
            }
            return spaces;
        });

        // 5. แปลงแท็บอักขระจริง \t ให้เป็นย่อหน้า &emsp;&emsp;
        str = str.replace(/\t+/g, '&emsp;&emsp;');

        // 6. ดึงข้อความ/ตัวเลข/bullet จากโค้ดเฉพาะ supportLists ของ Word
        str = str.replace(/<!--\s*\[if\s+!supportLists\]([\s\S]*?)<!--\s*\[endif\]\s*-->/gi, function (match, p1) {
            const clean = p1.replace(/<[^>]+>/g, '').trim();
            if (/^\d+[\.\)]/.test(clean)) {
                return clean + ' ';
            }
            return '• ';
        });

        // ลบ HTML comments ทั่วไป
        str = str.replace(/<!--[\s\S]*?-->/g, '');

        // ลบ Word XML namespaces เช่น <o:p>, <w:WordDocument>
        str = str.replace(/<\/?\w+:[^>]*>/gi, '');

        // คลีนสแปน ignore ของ Word
        str = str.replace(/<span[^>]*style=['"][^'"]*mso-list:\s*Ignore[^'"]*['"][^>]*>([\s\S]*?)<\/span>/gi, function (match, p1) {
            const clean = p1.replace(/<[^>]+>/g, '').trim();
            if (/^\d+[\.\)]/.test(clean)) return clean + ' ';
            return '• ';
        });

        // ลบแอตทริบิวต์ภาษาของ Word (lang=TH / lang=EN)
        str = str.replace(/\s*lang=['"]?[a-z0-9\-_]+['"]?/gi, '');

        // ลบ class ของ Word (MsoNormal, MsoListParagraph ฯลฯ)
        str = str.replace(/\s*class=['"]?Mso\w*['"]?/gi, '');

        // คลีน style attributes โดยจับคู่ quotes อย่างถูกต้อง (แก้ปัญหา inner quotes ใน font-family)
        str = str.replace(/\s*style=(['"])([\s\S]*?)\1/gi, function (match, quote, styleContent) {
            const cleaned = cleanWordStyle(styleContent);
            return cleaned ? ` style="${cleaned}"` : '';
        });

        // ลบแท็ก <span> ที่ว่างเปล่าหรือไม่มีแอตทริบิวต์หลงเหลือ
        str = str.replace(/<span\s*>([\s\S]*?)<\/span>/gi, '$1');

        // 7. รวมบรรทัด \r\n ภายใน <li>...</li> ให้เป็นบรรทัดเดียวกัน ป้องกันคำในวงเล็บแตกบรรทัด
        str = str.replace(/<li\b([^>]*)>([\s\S]*?)<\/li>/gi, function (match, attrs, innerText) {
            const normalized = innerText.replace(/[\r\n]+/g, ' ').trim();
            return `<li${attrs}>${normalized}</li>`;
        });

        // 8. รวมบรรทัด \r\n ภายใน <p>...</p> ให้เป็นข้อความต่อเนื่อง พร้อมรักษาย่อหน้า
        str = str.replace(/<p\b([^>]*)>([\s\S]*?)<\/p>/gi, function (match, attrs, innerText) {
            let normalizedInner = innerText.replace(/[\r\n]+/g, ' ');
            // ตรวจจับย่อหน้า: ไม่ว่าจะเริ่มด้วยช่องว่าง/nbsp ทันที หรือมีแท็ก span คั่นอยู่ข้างหน้า
            normalizedInner = normalizedInner.replace(/(^|(?:<[a-z0-9]+[^>]*>)+)((?:&nbsp;|\s|\u00A0|\t|&emsp;){2,})/i, function(m, p1, p2) {
                return p1 + '&emsp;&emsp;';
            });
            // ถ้าใน attrs มี text-indent ของ Word ให้แปลงเป็น &emsp;&emsp; นำหน้าข้อความทันที
            if (/text-indent/i.test(attrs)) {
                const indentVal = (attrs.match(/text-indent:\s*([^;"']+)/i) || [])[1] || '';
                if (parseFloat(indentVal) > 0) {
                    if (!normalizedInner.startsWith('&emsp;')) {
                        normalizedInner = '&emsp;&emsp;' + normalizedInner;
                    }
                }
            }
            // ตัด whitespace ส่วนเกินที่ต้นและท้าย
            normalizedInner = normalizedInner.replace(/^[ \t\r\n]+|[ \t\r\n]+$/g, '');
            return `<p${attrs}>${normalizedInner}</p>`;
        });

        // ตรวจสอบเบื้องต้นว่ามีสัญลักษณ์ Bullet หรือ Number List ที่ต้องแปลงหรือไม่
        const hasBulletPattern = /[•⁃▪▫◦·\u2022\u2043\u25AA\u25AB\u25E6\u00B7]|&bull;|&#8226;|&#x2022;|(?:^|[\r\n>]|&gt;)[\s\u00A0]*[-\*]\s+/i;
        const hasNumberPattern = /(?:^|[\r\n>]|&gt;)[\s\u00A0]*\d+[\.\)][\s\u00A0]+/i;

        // แปลง Entities ของ Bullet ให้อยู่ในรูปตัวอักษรมาตรฐาน (ไม่แตะต้อง &nbsp; ของเนื้อหา)
        str = str.replace(/&bull;|&#8226;|&#x2022;/gi, '•');

        // ถ้าไม่มี Bullet ในรูปแท็ก <p> หรือข้อความดิบ ให้ return ทันที (ไม่ไปยุ่งกับ <ul><li> ที่มีอยู่แล้ว)
        if (!hasBulletPattern.test(str) && !hasNumberPattern.test(str)) {
            return str;
        }

        // ประมวลผลบล็อก HTML โดยแยกแท็กระดับบล็อก (p, ul, ol, h1-h6, table) ไม่ให้พัง
        const blocks = [];
        const blockRegex = /<(p|ul|ol|h[1-6]|table)\b([^>]*)>([\s\S]*?)<\/\1>/gi;
        let lastIndex = 0;
        let match;

        function processRawLine(rawLine) {
            let line = rawLine.replace(/[\r\n]+/g, '');
            line = line.replace(/^(?:&nbsp;|\u00A0|\t|[ ]){2,}/, '&emsp;&emsp;');
            return line.trimStart().startsWith('&emsp;') ? line.trimEnd() : line.trim();
        }

        while ((match = blockRegex.exec(str)) !== null) {
            const before = str.substring(lastIndex, match.index).trim();
            if (before) {
                before.split(/\r?\n/).forEach(line => {
                    const content = processRawLine(line);
                    if (content) blocks.push({ tag: 'p', attrs: '', content: content });
                });
            }
            blocks.push({ tag: match[1].toLowerCase(), attrs: match[2], content: match[3].trim(), full: match[0] });
            lastIndex = match.index + match[0].length;
        }
        const remainder = str.substring(lastIndex).trim();
        if (remainder) {
            remainder.split(/\r?\n/).forEach(line => {
                const content = processRawLine(line);
                if (content) blocks.push({ tag: 'p', attrs: '', content: content });
            });
        }

        if (blocks.length === 0) {
            str.split(/\r?\n/).forEach(line => {
                const content = processRawLine(line);
                if (content) blocks.push({ tag: 'p', attrs: '', content: content });
            });
        }

        const resultBlocks = [];
        let currentUl = [];
        let currentOl = [];

        function flushUl() {
            if (currentUl.length > 0) {
                resultBlocks.push(`<ul>${currentUl.map(c => `<li>${c}</li>`).join('')}</ul>`);
                currentUl = [];
            }
        }
        function flushOl() {
            if (currentOl.length > 0) {
                resultBlocks.push(`<ol>${currentOl.map(c => `<li>${c}</li>`).join('')}</ol>`);
                currentOl = [];
            }
        }

        for (let i = 0; i < blocks.length; i++) {
            const b = blocks[i];

            // ถ้าเป็นบล็อก <ul> หรือ <ol> หรือ <table> หรือ heading อยู่แล้ว ให้รักษาโครงสร้างเดิมไว้สมบูรณ์ 100%
            if (b.tag === 'ul' || b.tag === 'ol' || b.tag === 'table' || /^h[1-6]$/.test(b.tag)) {
                flushUl();
                flushOl();
                // คลีนเนื้อหาภายใน <li> ให้ไม่มีการตัดบรรทัดแปลกๆ
                let cleanList = b.full.replace(/<li\b([^>]*)>([\s\S]*?)<\/li>/gi, (m, a, txt) => {
                    return `<li${a}>${txt.replace(/[\r\n]+/g, ' ').trim()}</li>`;
                });
                resultBlocks.push(cleanList);
                continue;
            }

            const cleanText = b.content.replace(/&emsp;|&nbsp;|\u00A0/gi, ' ').replace(/<[^>]+>/g, '').trim();

            // ตรวจสอบว่าบล็อกนี้เป็น Bullet หรือไม่
            const isBullet = /^[•⁃▪▫◦·\u2022\u2043\u25AA\u25AB\u25E6\u00B7]/.test(cleanText) || /^([-\*])[\s\u00A0]+/.test(cleanText);
            if (isBullet) {
                flushOl();
                const content = b.content.replace(/^(\s*<[^>]+>)*\s*([•⁃▪▫◦·\u2022\u2043\u25AA\u25AB\u25E6\u00B7]|[-\*])[\s\u00A0]*/i, '');
                currentUl.push(content);
                continue;
            }

            // ตรวจสอบว่าบล็อกนี้เป็นรายการตัวเลขลำดับหรือไม่
            const numMatch = cleanText.match(/^(\d+)[\.\)][\s\u00A0]+/);
            if (numMatch) {
                const nextClean = (i + 1 < blocks.length) ? blocks[i + 1].content.replace(/&emsp;|&nbsp;|\u00A0/gi, ' ').replace(/<[^>]+>/g, '').trim() : '';
                const nextIsNum = /^\d+[\.\)][\s\u00A0]+/.test(nextClean);

                if (nextIsNum || currentOl.length > 0) {
                    flushUl();
                    const content = b.content.replace(/^(\s*<[^>]+>)*\s*(\d+)[\.\)][\s\u00A0]+/i, '');
                    currentOl.push(content);
                    continue;
                } else {
                    // หากเป็นหัวข้อเดี่ยวๆ ที่มีเนื้อหา/bullet คั่น (เช่น 1. Accounts Receivable)
                    // ให้คงตัวเลขเดิมไว้เสมอ และไม่ครอบ <ol> แยก ป้องกันปัญหาตัวเลขกลายเป็น 1-1-1
                    flushUl();
                    flushOl();
                    resultBlocks.push(`<p${b.attrs}>${b.content}</p>`);
                    continue;
                }
            }

            // ย่อหน้าปกติ หรือ บล็อก HTML มาตรฐาน (heading, table ฯลฯ)
            flushUl();
            flushOl();

            if (/^<(h[1-6]|table|thead|tbody|tfoot|tr|th|td|blockquote|pre|figure|img|ul|ol|li)/i.test(b.content)) {
                resultBlocks.push(b.content);
            } else {
                resultBlocks.push(`<p${b.attrs}>${b.content}</p>`);
            }
        }

        flushUl();
        flushOl();

        return resultBlocks.join('');
    }
    const cleanAndConvertWordContent = autoConvertBullets;

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
                    <input type="text" name="sections[${lang}][${index}][topic]" value="${escapeHtml(topicVal)}" placeholder="ระบุชื่อหัวข้อย่อย..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" required>
                    <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                        <span>💡</span>
                        <strong>คำแนะนำ:</strong> ชื่อหัวข้อย่อยหรือประเด็นหลักของแต่ละ Section (หัวข้อรอง &lt;h2&gt; ภายในหน้าบทความ)
                    </p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1.5">เนื้อหาหลักของบทความ (${lang.toUpperCase()}) <span class="text-red-500">*</span></label>
                    <div class="editor-frame rounded-xl border border-slate-300 overflow-hidden">
                        <textarea id="${id}" name="sections[${lang}][${index}][body]" class="w-full min-h-[150px]">${escapeHtml(autoConvertBullets(bodyVal))}</textarea>
                    </div>
                    <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1.5">
                        <span>💡</span>
                        <strong>คำแนะนำ:</strong> พิมพ์เนื้อหาบทความ จัดตัวหนา ลิสต์รายการ ใส่รูปภาพ หรือแปะลิงก์ เป็นส่วนที่ผู้อ่านและ Google ใช้ประเมินคุณภาพของเนื้อหา
                    </p>
                </div>
            </div>
        `;
        container.appendChild(div);
        tinymce.init({
            selector: `#${id}`,
            plugins: 'autoresize lists link image code table wordcount',
            toolbar: 'blocks | bold italic forecolor backcolor | bullist numlist | outdent indent | alignleft aligncenter alignright alignjustify | link image | removeformat',
            menubar: false,
            extended_valid_elements: 'span[*],p[*],ul[*],ol[*],li[*],strong[*],em[*],b[*],i[*],h1[*],h2[*],h3[*],h4[*]',
            valid_styles: {
                '*': 'color,background-color,text-align,text-indent,margin,margin-left,margin-right,margin-top,margin-bottom,padding,padding-left,padding-right,font-weight,font-style,text-decoration'
            },
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
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_style: 'body { font-family: "Noto Sans Thai", Inter, ui-sans-serif, system-ui, sans-serif; font-size: 16px; line-height: 1.75; color: #475569; } p { margin-bottom: 1rem; } h1, h2, h3, h4, h5, h6 { color: #0f172a; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; font-family: "Noto Sans Thai", Inter, sans-serif !important; } h1 { font-size: 2rem; line-height: 1.3; } h2 { font-size: 1.5rem; line-height: 1.35; color: #022862; } h3 { font-size: 1.25rem; line-height: 1.4; color: #0663F6; } ul { list-style: none !important; padding-left: 2rem !important; margin-bottom: 1.5rem; } ul li { position: relative; padding-left: 1.5rem; margin-bottom: 0.65rem; line-height: 1.8; } ul li::before { content: ""; position: absolute; left: 0.35rem; top: 0.72rem; width: 7px; height: 7px; border-radius: 50%; background-color: #0663F6; } ol { list-style-type: decimal; padding-left: 2.5rem !important; margin-bottom: 1.5rem; } ol li { margin-bottom: 0.65rem; line-height: 1.8; } strong, b { font-weight: 700; }',
            images_upload_handler: tinymceImageUploadHandler,
            paste_preprocess: function (plugin, args) {
                args.content = autoConvertBullets(args.content);
            },
            setup: function (editor) {
                editors[id] = editor;

                let isPastingWord = false;
                function handleWordPaste(e) {
                    if (isPastingWord) return;
                    const clipboardData = e.clipboardData || (e.originalEvent && e.originalEvent.clipboardData) || window.clipboardData;
                    if (!clipboardData) return;

                    const html = clipboardData.getData('text/html');
                    if (!html) return;

                    // ตรวจจับว่าเป็นเนื้อหาจาก Microsoft Word หรือมี text-indent ของ Word
                    const isWord = html.includes('urn:schemas-microsoft-com:office') ||
                                   html.includes('mso-') ||
                                   html.includes('MsoNormal') ||
                                   /class=['"]?Mso/i.test(html) ||
                                   /text-indent\s*:/i.test(html);

                    if (isWord) {
                        isPastingWord = true;
                        setTimeout(() => { isPastingWord = false; }, 300);

                        e.preventDefault();
                        if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                        if (e.stopPropagation) e.stopPropagation();

                        const converted = autoConvertBullets(html);
                        editor.insertContent(converted);
                        return false;
                    }
                }

                // ดักจับ paste ในระดับ TinyMCE event
                editor.on('paste', handleWordPaste);

                // ดักจับ paste ในระดับ Native DOM Event (Capture Phase) ของ iframe document
                editor.on('init', function () {
                    const doc = editor.getDoc();
                    if (doc) {
                        doc.addEventListener('paste', handleWordPaste, true);
                    }
                    const body = editor.getBody();
                    if (body) {
                        body.addEventListener('paste', handleWordPaste, true);
                    }
                });

                // Fallback สำหรับการวางข้อความทั่วไป หรือข้อความดิบ (Plain Text)
                editor.on('PastePreProcess', function (e) {
                    e.content = autoConvertBullets(e.content);
                });
                editor.on('keydown', function (e) {
                    if (e.key === 'Tab' && !e.shiftKey) {
                        const node = editor.selection.getNode();
                        if (node && node.closest('li')) {
                            editor.execCommand('Indent');
                        } else {
                            e.preventDefault();
                            editor.insertContent('&emsp;&emsp;');
                        }
                    }
                });
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
                if (window.tinymce && typeof tinymce.triggerSave === 'function') {
                    tinymce.triggerSave();
                }
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
        const fileInput = document.getElementById('coverImageInput') || document.querySelector('input[name="image_file"]');
        const previewContainer = document.getElementById('coverImagePreviewContainer') || document.querySelector('.w-full.h-64.rounded-xl.border');
        const initialPreviewHtml = previewContainer ? previewContainer.innerHTML : '';

        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files && event.target.files[0];
                if (!file) {
                    return;
                }

                // Check file extension: Must be .webp
                const fileName = file.name.toLowerCase();
                const isWebp = fileName.endsWith('.webp') || file.type === 'image/webp';
                if (!isWebp) {
                    alert('⚠️ รูปแบบไฟล์ไม่ถูกต้อง:\n\nระบบรองรับเฉพาะไฟล์รูปภาพนามสกุล .webp เท่านั้นครับ กรุณาแปลงไฟล์รูปภาพเป็น .webp ก่อนทำการอัปโหลด');
                    fileInput.value = '';
                    previewContainer.innerHTML = initialPreviewHtml;
                    return;
                }

                // Check file size: Maximum 1 MB (1024 * 1024 bytes)
                const maxBytes = 1024 * 1024;
                if (file.size > maxBytes) {
                    const sizeKb = Math.round(file.size / 1024);
                    alert(`⚠️ ขนาดไฟล์เกินกำหนด:\n\nขนาดไฟล์ของคุณคือ ${sizeKb} KB (เกิน 1,024 KB)\nกรุณาใช้รูปภาพ .webp ขนาดไม่เกิน 1 MB (แนะนำขนาด 150 – 350 KB เพื่อให้เว็บไซต์โหลดเร็วที่สุดครับ)`);
                    fileInput.value = '';
                    previewContainer.innerHTML = initialPreviewHtml;
                    return;
                }

                // Valid .webp file -> Generate instant image preview
                const reader = new FileReader();
                reader.onload = function(loadEvent) {
                    previewContainer.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = loadEvent.target.result;
                    img.className = 'w-full h-full object-contain rounded-lg shadow-sm transition-transform duration-200 hover:scale-[1.01]';
                    img.alt = 'Cover Image Preview';
                    previewContainer.appendChild(img);
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

    // =========================================================================
    // 1. Session Heartbeat Keep-Alive & CSRF Auto-Refresh
    // =========================================================================
    (function initHeartbeat() {
        const HEARTBEAT_INTERVAL = 3 * 60 * 1000; // Ping every 3 minutes
        const HEARTBEAT_URL = '<?= ADMIN_URL ?>/ajax_heartbeat.php';

        async function doHeartbeat() {
            try {
                const res = await fetch(HEARTBEAT_URL, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    cache: 'no-store'
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.csrf_token) {
                        const csrfInputs = document.querySelectorAll('input[name="_csrf"], input[name="csrf_token"]');
                        csrfInputs.forEach(input => {
                            input.value = data.csrf_token;
                        });
                    }
                } else if (res.status === 401) {
                    console.warn('[Heartbeat] Admin session expired on server.');
                }
            } catch (err) {
                console.warn('[Heartbeat] Ping failed (network offline?):', err);
            }
        }

        // Trigger heartbeat periodically
        setInterval(doHeartbeat, HEARTBEAT_INTERVAL);
        // Also ping when window gains focus back from other tabs
        window.addEventListener('focus', () => {
            doHeartbeat();
        });
    })();

    // =========================================================================
    // 2. Local Auto-save & Draft Recovery System (LocalStorage)
    // =========================================================================
    (function initDraftAutoSave() {
        const articleId = <?= (int)($data['id'] ?? 0) ?>;
        const DRAFT_KEY = 'webpark_article_draft_' + articleId;
        const form = document.getElementById('unifiedForm');
        if (!form) return;

        const draftAlert = document.getElementById('draftRecoveryAlert');
        const draftSavedTime = document.getElementById('draftSavedTime');
        const btnRestore = document.getElementById('btnRestoreDraft');
        const btnDiscard = document.getElementById('btnDiscardDraft');
        const autoSaveText = document.getElementById('autoSaveText');

        let isRestoring = false;
        let isSubmitted = false;

        // Collect all data from form including TinyMCE editors
        function collectFormData() {
            const data = {
                savedAt: new Date().toISOString(),
                inputs: {},
                sections: { th: [], en: [] }
            };

            // Standard inputs
            const fields = [
                'meta_title', 'meta_description', 'meta_keywords',
                'meta_title_en', 'meta_description_en', 'meta_keywords_en',
                'cover_image_alt', 'category_id', 'created_at', 'slug', 'slug_en'
            ];
            fields.forEach(name => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) data.inputs[name] = el.value;
            });

            const isPinned = form.querySelector('input[name="is_pinned"]#is_pinned_input');
            if (isPinned) data.inputs['is_pinned'] = isPinned.checked;

            // Collect sections
            ['th', 'en'].forEach(lang => {
                const container = document.getElementById(`${lang}-sections-container`);
                if (!container) return;
                const items = container.querySelectorAll('.section-item');
                items.forEach((item, idx) => {
                    const topicInput = item.querySelector('input[type="text"]');
                    const textarea = item.querySelector('textarea');
                    let bodyVal = '';
                    if (textarea) {
                        const ed = editors[textarea.id];
                        bodyVal = (ed && typeof ed.getContent === 'function') ? ed.getContent() : textarea.value;
                    }
                    data.sections[lang].push({
                        topic: topicInput ? topicInput.value : '',
                        body: bodyVal
                    });
                });
            });

            return data;
        }

        // Save Draft to LocalStorage
        function saveDraft() {
            if (isRestoring || isSubmitted) return;
            try {
                const data = collectFormData();
                // Only save if user has actually typed something in title or body
                const hasContent = (data.inputs.meta_title && data.inputs.meta_title.trim() !== '') ||
                    (data.inputs.meta_title_en && data.inputs.meta_title_en.trim() !== '') ||
                    data.sections.th.some(s => s.topic || (s.body && s.body !== '<p><br></p>' && s.body.trim() !== '')) ||
                    data.sections.en.some(s => s.topic || (s.body && s.body !== '<p><br></p>' && s.body.trim() !== ''));

                if (!hasContent) return;

                localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
                if (autoSaveText) {
                    const now = new Date();
                    const timeStr = now.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    autoSaveText.textContent = `บันทึกร่างในเครื่องล่าสุด ${timeStr}`;
                }
            } catch (err) {
                console.warn('[Draft] Failed to save to localStorage:', err);
            }
        }

        // Check and Show Recovery Banner if saved draft exists
        function checkDraftRecovery() {
            try {
                const raw = localStorage.getItem(DRAFT_KEY);
                if (!raw) return;
                const draft = JSON.parse(raw);
                if (!draft || !draft.savedAt) return;

                const savedDate = new Date(draft.savedAt);
                const timeStr = savedDate.toLocaleString('th-TH', {
                    day: 'numeric', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                if (draftSavedTime) draftSavedTime.textContent = timeStr;
                if (draftAlert) draftAlert.classList.remove('hidden');
            } catch (e) {
                console.warn('[Draft] Check recovery failed:', e);
            }
        }

        // Restore Draft Data
        function restoreDraft() {
            try {
                const raw = localStorage.getItem(DRAFT_KEY);
                if (!raw) return;
                const draft = JSON.parse(raw);
                isRestoring = true;

                // Restore inputs
                if (draft.inputs) {
                    Object.keys(draft.inputs).forEach(name => {
                        const el = form.querySelector(`[name="${name}"]`);
                        if (el) {
                            el.value = draft.inputs[name];
                            el.dispatchEvent(new Event('input', { bubbles: true }));
                            el.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });

                    const isPinned = form.querySelector('input[name="is_pinned"]#is_pinned_input');
                    if (isPinned && typeof draft.inputs['is_pinned'] !== 'undefined') {
                        isPinned.checked = !!draft.inputs['is_pinned'];
                    }
                }

                // Restore sections
                if (draft.sections) {
                    ['th', 'en'].forEach(lang => {
                        const container = document.getElementById(`${lang}-sections-container`);
                        if (!container) return;
                        const items = container.querySelectorAll('.section-item');
                        items.forEach(item => {
                            const textarea = item.querySelector('textarea');
                            if (textarea && editors[textarea.id]) {
                                editors[textarea.id].remove();
                                delete editors[textarea.id];
                            }
                            item.remove();
                        });

                        if (lang === 'th') thCount = 0;
                        if (lang === 'en') enCount = 0;

                        const savedSecs = draft.sections[lang] || [];
                        if (savedSecs.length > 0) {
                            savedSecs.forEach((sec, idx) => {
                                createSectionElement(lang, idx, sec.topic || '', sec.body || '');
                            });
                        } else {
                            createSectionElement(lang, 0, '', '');
                        }
                    });
                }

                if (draftAlert) draftAlert.classList.add('hidden');
                if (autoSaveText) autoSaveText.textContent = 'กู้คืนเนื้อหาฉบับร่างสำเร็จ!';
                isRestoring = false;
            } catch (err) {
                console.error('[Draft] Restore error:', err);
                alert('เกิดข้อผิดพลาดในการกู้คืนเนื้อหา');
                isRestoring = false;
            }
        }

        if (btnRestore) {
            btnRestore.addEventListener('click', restoreDraft);
        }

        if (btnDiscard) {
            btnDiscard.addEventListener('click', () => {
                if (confirm('คุณแน่ใจหรือไม่ว่าต้องการละทิ้งฉบับร่างที่บันทึกไว้ในเครื่องนี้?')) {
                    localStorage.removeItem(DRAFT_KEY);
                    if (draftAlert) draftAlert.classList.add('hidden');
                }
            });
        }

        // Auto-save every 20 seconds
        setInterval(saveDraft, 20 * 1000);

        // Auto-save on typing inputs
        form.addEventListener('input', debounce(saveDraft, 2000));

        // When form is submitted successfully, clean localStorage draft
        form.addEventListener('submit', () => {
            isSubmitted = true;
            // Let the form submit, draft removed after submit initiated
            localStorage.removeItem(DRAFT_KEY);
        });

        // Delay check until TinyMCE is fully initiated
        setTimeout(checkDraftRecovery, 600);

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    })();
</script>