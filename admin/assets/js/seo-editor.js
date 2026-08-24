/* global CKEDITOR */
(function () {
    function syncSeoCounters(elements) {
        try {
            if (!elements.titleInput || !elements.titleCounter || !elements.descInput || !elements.descCounter) {
                console.warn("SEO elements missing, skipping sync.");
                return;
            }
            const titleLen = elements.titleInput.value ? elements.titleInput.value.length : 0;
            const descLen = elements.descInput.value ? elements.descInput.value.length : 0;

            elements.titleCounter.textContent = `${titleLen} / 120`;
            elements.titleCounter.className = `text-xs font-medium ${titleLen > 120 ? 'text-rose-600' : 'text-slate-500'}`;

            if (titleLen > 120) {
                elements.titleInput.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.titleInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            } else {
                elements.titleInput.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.titleInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            }

            elements.descCounter.textContent = `${descLen} / 200`;
            elements.descCounter.className = `text-xs font-medium ${descLen > 200 ? 'text-rose-600' : 'text-slate-500'}`;

            if (descLen > 200) {
                elements.descInput.classList.add('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.descInput.classList.remove('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
            } else {
                elements.descInput.classList.remove('border-rose-500', 'focus:border-rose-500', 'focus:ring-rose-500/10');
                elements.descInput.classList.add('border-slate-300', 'focus:border-blue-500', 'focus:ring-blue-500/10');
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
