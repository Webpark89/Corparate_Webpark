/* global CKEDITOR */
(function () {
    function syncSeoCounters(elements) {
        const titleLen = elements.titleInput.value.length;
        const descLen = elements.descInput.value.length;

        elements.titleCounter.textContent = `${titleLen} / 150`;
        elements.titleCounter.className = `text-xs font-medium ${titleLen > 150 ? 'text-rose-600' : 'text-slate-500'}`;

        elements.descCounter.textContent = `${descLen} / 500`;
        elements.descCounter.className = `text-xs font-medium ${descLen > 500 ? 'text-rose-600' : 'text-slate-500'}`;
    }

    function slugify(value) {
        return value.toLowerCase()
            .replace(/[^a-z0-9\u0E00-\u0E7F]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    async function initSeoEditor(options) {
        const editorEl = document.querySelector(options.editorSelector);
        const tinymce = window.tinymce;
        if (!editorEl || !tinymce) {
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

        tinymce.init({
            target: editorEl,
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
            min_height: 300,
            max_height: 800,
            autoresize_bottom_margin: 20,
            content_style: 'body { font-family: "Noto Sans Thai", Inter, ui-sans-serif, system-ui, sans-serif; font-size: 16px; line-height: 1.75; color: #475569; } p, span, li, div { font-size: 16px !important; line-height: 1.75 !important; }',
            images_upload_handler: function (blobInfo, progress) {
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
            },
            setup: function(editor) {
                editor.addShortcut('ctrl+q', 'Apply Primary Color', function () {
                    editor.execCommand('ForeColor', false, '#0663F6');
                });
                editor.on('change keyup paste', function(e) {
                    elements.contentInput.value = editor.getContent();
                    updateSeoState();
                });
                
                elements.form.addEventListener('submit', () => {
                    elements.contentInput.value = editor.getContent();
                });
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

        syncSeoCounters(elements);
        // The setup function inside tinymce.init handles content syncing
        return editor;
    }

    window.WEBPARKSeoEditor = {
        init: initSeoEditor,
    };
})();
