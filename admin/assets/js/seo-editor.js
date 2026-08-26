/* global CKEDITOR */
(function () {
    function syncSeoCounters(elements) {
        try {
            if (!elements.titleInput || !elements.titleCounter || !elements.descInput || !elements.descCounter) {
                return;
            }
            const titleLen = elements.titleInput.value ? elements.titleInput.value.length : 0;
            const descLen = elements.descInput.value ? elements.descInput.value.length : 0;

            // Title validation & rating
            if (titleLen === 0) {
                elements.titleCounter.innerHTML = `0 / 120 <span class="text-slate-400 font-normal">(แนะนำ 50-60 ตัวอักษร)</span>`;
                elements.titleCounter.className = 'text-xs font-medium text-slate-500';
            } else if (titleLen >= 40 && titleLen <= 60) {
                elements.titleCounter.innerHTML = `${titleLen} / 120 <span class="text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded">✓ เหมาะสมที่สุดสำหรับ Google</span>`;
                elements.titleCounter.className = 'text-xs font-medium text-emerald-600';
            } else if (titleLen > 60 && titleLen <= 120) {
                elements.titleCounter.innerHTML = `${titleLen} / 120 <span class="text-amber-700 font-medium bg-amber-50 px-1.5 py-0.5 rounded">⚠️ Google อาจตัดท้าย ให้วาง Keyword สำคัญไว้ต้นประโยค</span>`;
                elements.titleCounter.className = 'text-xs font-medium text-amber-600';
            } else if (titleLen > 120) {
                elements.titleCounter.innerHTML = `${titleLen} / 120 <span class="text-rose-700 font-bold bg-rose-50 px-1.5 py-0.5 rounded">❌ เกินกำหนด 120 ตัวอักษร</span>`;
                elements.titleCounter.className = 'text-xs font-medium text-rose-600';
            } else {
                elements.titleCounter.innerHTML = `${titleLen} / 120 <span class="text-slate-400 font-normal">(แนะนำ 50-60 ตัวอักษร)</span>`;
                elements.titleCounter.className = 'text-xs font-medium text-slate-500';
            }

            if (titleLen > 120) {
                elements.titleInput.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.titleInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            } else {
                elements.titleInput.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.titleInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            }

            // Description validation & rating
            if (descLen === 0) {
                elements.descCounter.innerHTML = `0 / 200 <span class="text-slate-400 font-normal">(แนะนำ 120-160 ตัวอักษร)</span>`;
                elements.descCounter.className = 'text-xs font-medium text-slate-500';
            } else if (descLen >= 120 && descLen <= 160) {
                elements.descCounter.innerHTML = `${descLen} / 200 <span class="text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded">✓ เหมาะสมที่สุดสำหรับ Google</span>`;
                elements.descCounter.className = 'text-xs font-medium text-emerald-600';
            } else if (descLen > 160 && descLen <= 200) {
                elements.descCounter.innerHTML = `${descLen} / 200 <span class="text-amber-700 font-medium bg-amber-50 px-1.5 py-0.5 rounded">พอดี (อาจถูกตัดท้ายเล็กน้อยบนมือถือ)</span>`;
                elements.descCounter.className = 'text-xs font-medium text-amber-600';
            } else if (descLen > 200) {
                elements.descCounter.innerHTML = `${descLen} / 200 <span class="text-rose-700 font-bold bg-rose-50 px-1.5 py-0.5 rounded">❌ เกินกำหนด 200 ตัวอักษร</span>`;
                elements.descCounter.className = 'text-xs font-medium text-rose-600';
            } else {
                elements.descCounter.innerHTML = `${descLen} / 200 <span class="text-slate-400 font-normal">(แนะนำ 120-160 ตัวอักษร)</span>`;
                elements.descCounter.className = 'text-xs font-medium text-slate-500';
            }

            if (descLen > 200) {
                elements.descInput.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.descInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            } else {
                elements.descInput.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.descInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            }

            // Alt text check
            const altInput = document.getElementById('coverImageAlt');
            const altBadge = document.getElementById('altTextBadge');
            const altWarn = document.getElementById('altTextWarning');
            if (altInput && (altBadge || altWarn)) {
                const altVal = altInput.value.trim();
                if (altVal === '') {
                    if (altBadge) altBadge.innerHTML = '';
                    if (altWarn) altWarn.classList.add('hidden');
                } else if (/^\d+$/.test(altVal)) {
                    if (altBadge) altBadge.innerHTML = '<span class="text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded">⚠️ ไม่ควรเป็นตัวเลขล้วน</span>';
                    if (altWarn) {
                        altWarn.innerHTML = `⚠️ ข้อความ Alt Text ไม่ควรเป็นตัวเลขล้วน (เช่น "${altVal}") เพราะไม่มีผลต่อ SEO กรุณาเขียนข้อความบรรยายรูปภาพ เช่น "หน้าจอระบบ ERP บัญชีสำหรับองค์กร"`;
                        altWarn.classList.remove('hidden');
                    }
                } else if (altVal.length < 5) {
                    if (altBadge) altBadge.innerHTML = '<span class="text-amber-600 font-medium bg-amber-50 px-1.5 py-0.5 rounded">สั้นเกินไป</span>';
                    if (altWarn) {
                        altWarn.innerHTML = `💡 ข้อความ Alt Text สั้นเกินไป แนะนำให้อธิบายรายละเอียดของรูปภาพเพิ่มเติม`;
                        altWarn.classList.remove('hidden');
                    }
                } else {
                    if (altBadge) altBadge.innerHTML = '<span class="text-emerald-700 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded">✓ Alt Text สมบูรณ์</span>';
                    if (altWarn) altWarn.classList.add('hidden');
                }
            }
        } catch (e) {
            console.error("Error syncing SEO counters:", e);
        }
    }

    function slugify(value) {
        return value.toLowerCase()
            .replace(/[^a-z0-9\u0E00-\u0E7F]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    async function initSeoEditor(options) {
        try {
            const elements = {
                titleInput: document.querySelector(options.titleSelector),
                descInput: document.querySelector(options.descSelector),
                slugInput: document.querySelector(options.slugSelector),
                titleCounter: document.querySelector(options.titleCounterSelector),
                descCounter: document.querySelector(options.descCounterSelector),
                contentInput: document.querySelector(options.contentSelector),
                keywordsInput: document.querySelector('input[name="meta_keywords"]'),
                form: document.querySelector(options.formSelector),
            };

            if (elements.titleInput && elements.descInput) {
                const updateSeoState = () => {
                    syncSeoCounters(elements);
                };

                elements.titleInput.addEventListener('input', () => {
                    if (elements.slugInput && !elements.slugInput.dataset.edited) {
                        elements.slugInput.value = slugify(elements.titleInput.value);
                    }
                    updateSeoState();
                });

                elements.descInput.addEventListener('input', updateSeoState);

                if (elements.keywordsInput) {
                    elements.keywordsInput.addEventListener('input', updateSeoState);
                }

                if (elements.slugInput) {
                    elements.slugInput.addEventListener('input', () => {
                        elements.slugInput.dataset.edited = 'true';
                        elements.slugInput.value = slugify(elements.slugInput.value);
                        updateSeoState();
                    });
                }

                const altInput = document.getElementById('coverImageAlt');
                if (altInput) {
                    altInput.addEventListener('input', updateSeoState);
                }

                syncSeoCounters(elements);
            }

            const editorEl = document.querySelector(options.editorSelector);
            const ClassicEditor = window.ClassicEditor || (window.CKEDITOR && window.CKEDITOR.ClassicEditor);
            if (!editorEl || !ClassicEditor) {
                console.log("ClassicEditor or editor element not found. Skipping CKEditor setup.");
                return null;
            }

            if (editorEl.innerHTML.trim() === '' && elements.contentInput && elements.contentInput.value) {
                editorEl.innerHTML = elements.contentInput.value;
            }

            const editor = await ClassicEditor.create(editorEl, {
                licenseKey: 'GPL',
                placeholder: options.placeholder || 'เริ่มเขียนบทความที่น่าสนใจของคุณที่นี่...',
                toolbar: {
                    items: [ 'heading', '|', 'bold', 'italic', 'link', '|', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ],
                    shouldNotGroupWhenFull: true
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' },
                    ]
                },
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://'
                }
            });

            if (elements.contentInput) {
                editor.model.document.on('change:data', () => {
                    if (elements.titleInput && elements.descInput) syncSeoCounters(elements);
                    elements.contentInput.value = editor.getData();
                });
                if (elements.form) {
                    elements.form.addEventListener('submit', () => {
                        elements.contentInput.value = editor.getData();
                    });
                }
                elements.contentInput.value = editor.getData();
            }

            return editor;
        } catch (e) {
            console.error("Error in initSeoEditor:", e);
        }
    }

    window.WEBPARKSeoEditor = {
        init: initSeoEditor,
    };
})();
