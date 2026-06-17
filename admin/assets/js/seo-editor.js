/* global CKEDITOR */
(function () {
    function syncSeoCounters(elements) {
        const titleLen = elements.titleInput.value.length;
        const descLen = elements.descInput.value.length;

        elements.titleCounter.textContent = `${titleLen} / 60`;
        elements.titleCounter.className = `text-xs font-medium ${titleLen > 60 ? 'text-rose-600' : 'text-slate-500'}`;

        elements.descCounter.textContent = `${descLen} / 155`;
        elements.descCounter.className = `text-xs font-medium ${descLen > 155 ? 'text-rose-600' : 'text-slate-500'}`;
    }

    function slugify(value) {
        return value.toLowerCase()
            .replace(/[^a-z0-9\u0E00-\u0E7F]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    async function initSeoEditor(options) {
        const editorEl = document.querySelector(options.editorSelector);
        const ClassicEditor = window.ClassicEditor || (window.CKEDITOR && window.CKEDITOR.ClassicEditor);
        if (!editorEl || !ClassicEditor) {
            return null;
        }

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

        if (!elements.titleInput || !elements.descInput || !elements.slugInput || !elements.form || !elements.contentInput) {
            return null;
        }

        if (editorEl.innerHTML.trim() === '' && elements.contentInput.value) {
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

        const updateSeoState = () => {
            syncSeoCounters(elements);
        };

        elements.titleInput.addEventListener('input', () => {
            if (!elements.slugInput.dataset.edited) {
                elements.slugInput.value = slugify(elements.titleInput.value);
            }
            updateSeoState();
        });

        elements.descInput.addEventListener('input', updateSeoState);
        if (elements.keywordsInput) {
            elements.keywordsInput.addEventListener('input', updateSeoState);
        }
        elements.slugInput.addEventListener('input', () => {
            elements.slugInput.dataset.edited = 'true';
            updateSeoState();
        });

        editor.model.document.on('change:data', updateSeoState);
        editor.model.document.on('change:data', () => {
            elements.contentInput.value = editor.getData();
        });
        elements.form.addEventListener('submit', () => {
            elements.contentInput.value = editor.getData();
        });

        syncSeoCounters(elements);
        elements.contentInput.value = editor.getData();
        return editor;
    }

    window.WEBPARKSeoEditor = {
        init: initSeoEditor,
    };
})();
