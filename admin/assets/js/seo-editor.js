    function applyFieldStatus(input, counter, len, minOpt, maxOpt, maxLimit, optLabel, isThai = true) {
        if (!input || !counter) return;

        if (len === 0) {
            counter.textContent = `0 / ${maxLimit} (${optLabel})`;
            counter.style.cssText = 'color: #64748b; background: transparent; border: none; font-weight: 500;';
            input.style.cssText = '';
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
            input.style.cssText = '';
        }
    }

    function syncSeoCounters(elements) {
        try {
            if (!elements.titleInput || !elements.titleCounter || !elements.descInput || !elements.descCounter) {
                console.warn("SEO elements missing, skipping sync.");
                return;
            }
            const titleLen = elements.titleInput.value ? elements.titleInput.value.length : 0;
            const descLen = elements.descInput.value ? elements.descInput.value.length : 0;

            applyFieldStatus(elements.titleInput, elements.titleCounter, titleLen, 50, 60, 120, 'แนะนำ 50-60 ตัวอักษร', true);
            applyFieldStatus(elements.descInput, elements.descCounter, descLen, 120, 160, 200, 'แนะนำ 120-160 ตัวอักษร', true);
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
            console.log("Initializing SEO Editor with options:", options);
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

            console.log("Found DOM elements:", elements);

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
                        updateSeoState();
                    });
                }

                syncSeoCounters(elements);
                console.log("SEO Counters initialized successfully.");
            } else {
                console.error("Missing titleInput or descInput. Counters not bound.");
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
