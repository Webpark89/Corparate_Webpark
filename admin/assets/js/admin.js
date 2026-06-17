// Theme toggle
const themeBtn = document.getElementById('themeToggle');
const saved = localStorage.getItem('admin-theme');
if (saved) document.documentElement.setAttribute('data-theme', saved);
themeBtn?.addEventListener('click', () => {
  const cur = document.documentElement.getAttribute('data-theme') === 'dark' ? '' : 'dark';
  document.documentElement.setAttribute('data-theme', cur);
  localStorage.setItem('admin-theme', cur);
});

// Toast helper
window.toast = function (msg, type='success') {
  let host = document.querySelector('.toast-container');
  if (!host) { host = document.createElement('div'); host.className='toast-container'; document.body.appendChild(host); }
  const el = document.createElement('div');
  el.className = `alert alert-${type} shadow-sm`;
  el.textContent = msg;
  host.appendChild(el);
  setTimeout(() => el.remove(), 3500);
};

// Auto-slug from title
document.querySelectorAll('[data-slug-source]').forEach(src => {
  const target = document.querySelector(src.dataset.slugSource);
  if (!target) return;
  src.addEventListener('input', () => {
    if (target.dataset.touched) return;
    target.value = src.value.toLowerCase()
      .replace(/[^\w\s-]/g, '').trim()
      .replace(/\s+/g, '-').replace(/-+/g, '-');
  });
  target.addEventListener('input', () => target.dataset.touched = '1');
});

// Auto-submit filter dropdowns
document.addEventListener('change', (event) => {
  const control = event.target;
  if (!(control instanceof HTMLSelectElement)) return;
  const form = control.closest('form.filter-form');
  if (!form) return;
  form.requestSubmit?.() ?? form.submit();
});

// Drag & drop preview
document.querySelectorAll('.dropzone').forEach(zone => {
  const input = zone.querySelector('input[type=file]');
  const preview = zone.querySelector('.preview');
  zone.addEventListener('click', () => input.click());
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('drag'));
  zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('drag');
    if (e.dataTransfer.files.length) {
      input.files = e.dataTransfer.files;
      input.dispatchEvent(new Event('change'));
    }
  });
  input?.addEventListener('change', () => {
    const f = input.files[0]; if (!f || !preview) return;
    const url = URL.createObjectURL(f);
    preview.innerHTML = `<img src="${url}" alt="" class="upload-preview-img">`;
  });
});

// Confirm delete (Bootstrap modal)
document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
  btn.addEventListener('click', e => {
    if (!confirm('Are you sure you want to delete this item? This cannot be undone.')) {
      e.preventDefault();
    }
  });
});
