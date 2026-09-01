<?php

declare(strict_types=1);

/**
 * Admin Category Management page — list, create, edit, delete categories.
 */

$pageTitle = 'การจัดการหมวดหมู่บทความ';
$page = 'category';

require_once __DIR__ . '/../includes/header.php';

$pdo = db();
$stmt = $pdo->query('
    SELECT 
        c.id, 
        c.name, 
        c.slug,
        c.created_at,
        COUNT(a.id) AS article_count
    FROM categories c
    LEFT JOIN article a ON a.category_id = c.id AND a.deleted_at IS NULL
    GROUP BY c.id
    ORDER BY c.name ASC
');
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8 space-y-5">
    <!-- Page Header -->
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการหมวดหมู่บทความ</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการ เพิ่ม แก้ไขชื่อ หรือลบหมวดหมู่ของบทความบนเว็บไซต์</p>
        </div>
        <a href="<?= ADMIN_URL ?>/article/index.php" 
           class="inline-flex h-9 items-center gap-2 px-4 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:text-blue-600 rounded-xl shadow-xs transition">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>กลับหน้ารายการบทความ</span>
        </a>
    </header>

    <!-- Quick Add Category Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 md:p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">➕</span>
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">เพิ่มหมวดหมู่ใหม่</h3>
            </div>
        </div>

        <form id="createCategoryForm" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; width: 100%;">
            <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
            
            <div style="flex: 1 1 320px; min-width: 240px;">
                <label class="text-xs font-semibold text-slate-700 block mb-1.5">
                    ชื่อหมวดหมู่ <span class="text-red-500">*</span>
                </label>
                <input type="text" id="catNameInput" name="name" placeholder="เช่น AI & Automation, Cloud Computing..." required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs md:text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition" style="height: 42px;">
            </div>

            <div style="flex: 1 1 260px; min-width: 200px;">
                <label class="text-xs font-semibold text-slate-500 block mb-1.5">
                    URL Slug (เว้นว่างให้ระบบสร้างอัตโนมัติ)
                </label>
                <input type="text" id="catSlugInput" name="slug" placeholder="เช่น ai-automation"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white outline-none transition" style="height: 42px;">
            </div>

            <div style="flex: 0 0 auto; min-width: 150px;">
                <button type="submit" id="btnCreateCat"
                    class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition flex items-center justify-center gap-2 cursor-pointer" style="height: 42px; padding: 0 24px; white-space: nowrap;">
                    <span>+ เพิ่มหมวดหมู่</span>
                </button>
            </div>
        </form>
        <div id="createCatError" class="hidden mt-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium"></div>
    </div>

    <!-- Category List Card -->
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700">รายการหมวดหมู่ทั้งหมด</span>
                <span class="rounded-full bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 text-xs font-bold">
                    <span id="catCountDisplay"><?= count($categories) ?></span> หมวดหมู่
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5" style="width: 35%;">ชื่อหมวดหมู่</th>
                        <th class="px-6 py-3.5" style="width: 30%;">URL Slug สำหรับฟิลเตอร์</th>
                        <th class="px-6 py-3.5 text-center" style="width: 15%;">จำนวนบทความ</th>
                        <th class="px-6 py-3.5 text-right" style="width: 20%;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody" class="divide-y divide-slate-100">
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">ยังไม่มีหมวดหมู่ในระบบ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $c): ?>
                            <tr id="cat-row-<?= (int)$c['id'] ?>" class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4 font-semibold text-slate-900 text-sm">
                                    <div class="cat-name-view"><?= e($c['name']) ?></div>
                                    <div class="cat-name-edit hidden">
                                        <input type="text" class="edit-name-input w-full rounded-xl border border-blue-400 px-3 py-2 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20" value="<?= e($c['name']) ?>" style="height: 38px;">
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    <div class="cat-slug-view font-mono text-[11px] bg-slate-100 px-2.5 py-1 rounded-md inline-block border border-slate-200/60">
                                        /article?category=<?= e($c['slug']) ?>
                                    </div>
                                    <div class="cat-slug-edit hidden">
                                        <input type="text" class="edit-slug-input w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20" value="<?= e($c['slug']) ?>" style="height: 38px;">
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= (int)$c['article_count'] > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-500' ?>">
                                        <?= (int)$c['article_count'] ?> บทความ
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <!-- View Actions -->
                                    <div class="cat-action-view inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-xs">
                                        <button type="button" onclick="startEditCategory(<?= (int)$c['id'] ?>)"
                                            class="bg-white px-4 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition cursor-pointer flex items-center gap-1.5" style="padding: 8px 16px;">
                                            <span>✏️ แก้ไข</span>
                                        </button>
                                        <button type="button" onclick="deleteCategory(<?= (int)$c['id'] ?>, '<?= e(addslashes($c['name'])) ?>', <?= (int)$c['article_count'] ?>)"
                                            class="border-l border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer flex items-center gap-1.5" style="padding: 8px 16px;">
                                            <span>🗑️ ลบ</span>
                                        </button>
                                    </div>
                                    <!-- Edit Actions -->
                                    <div class="cat-action-edit hidden inline-flex overflow-hidden rounded-xl border border-slate-200 shadow-xs">
                                        <button type="button" onclick="saveEditCategory(<?= (int)$c['id'] ?>)"
                                            class="bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition cursor-pointer flex items-center gap-1.5" style="padding: 8px 16px;">
                                            <span>💾 บันทึก</span>
                                        </button>
                                        <button type="button" onclick="cancelEditCategory(<?= (int)$c['id'] ?>)"
                                            class="border-l border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer" style="padding: 8px 16px;">
                                            <span>ยกเลิก</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '<?= csrf_token() ?>';
const API_URL = '<?= ADMIN_URL ?>/article/ajax_category_actions.php';

// 1. Create Category
const createForm = document.getElementById('createCategoryForm');
if (createForm) {
    createForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('catNameInput').value.trim();
        const slug = document.getElementById('catSlugInput').value.trim();
        const errorDiv = document.getElementById('createCatError');
        const btn = document.getElementById('btnCreateCat');

        if (!name) return;
        errorDiv.classList.add('hidden');
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('action', 'create');
            formData.append('name', name);
            formData.append('slug', slug);
            formData.append('_csrf', CSRF_TOKEN);

            const res = await fetch(API_URL, { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                location.reload();
            } else {
                errorDiv.textContent = data.message || 'ไม่สามารถเพิ่มหมวดหมู่ได้';
                errorDiv.classList.remove('hidden');
            }
        } catch (err) {
            errorDiv.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์';
            errorDiv.classList.remove('hidden');
        } finally {
            btn.disabled = false;
        }
    });
}

// 2. Start Edit
function startEditCategory(id) {
    const row = document.getElementById('cat-row-' + id);
    if (!row) return;
    row.querySelector('.cat-name-view').classList.add('hidden');
    row.querySelector('.cat-name-edit').classList.remove('hidden');
    row.querySelector('.cat-slug-view').classList.add('hidden');
    row.querySelector('.cat-slug-edit').classList.remove('hidden');
    row.querySelector('.cat-action-view').classList.add('hidden');
    row.querySelector('.cat-action-edit').classList.remove('hidden');
    row.querySelector('.edit-name-input').focus();
}

// 3. Cancel Edit
function cancelEditCategory(id) {
    const row = document.getElementById('cat-row-' + id);
    if (!row) return;
    row.querySelector('.cat-name-view').classList.remove('hidden');
    row.querySelector('.cat-name-edit').classList.add('hidden');
    row.querySelector('.cat-slug-view').classList.remove('hidden');
    row.querySelector('.cat-slug-edit').classList.add('hidden');
    row.querySelector('.cat-action-view').classList.remove('hidden');
    row.querySelector('.cat-action-edit').classList.add('hidden');
}

// 4. Save Edit
async function saveEditCategory(id) {
    const row = document.getElementById('cat-row-' + id);
    if (!row) return;
    const name = row.querySelector('.edit-name-input').value.trim();
    const slug = row.querySelector('.edit-slug-input').value.trim();

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
        formData.append('_csrf', CSRF_TOKEN);

        const res = await fetch(API_URL, { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            row.querySelector('.cat-name-view').textContent = data.name;
            row.querySelector('.cat-slug-view').textContent = '/article?category=' + data.slug;
            row.querySelector('.edit-name-input').value = data.name;
            row.querySelector('.edit-slug-input').value = data.slug;
            cancelEditCategory(id);
        } else {
            alert(data.message || 'ไม่สามารถแก้ไขได้');
        }
    } catch (err) {
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    }
}

// 5. Delete Category
async function deleteCategory(id, name, articleCount) {
    let confirmMsg = `คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่ "${name}"?`;
    if (articleCount > 0) {
        confirmMsg += `\n\n⚠️ มีบทความ ${articleCount} เรื่องอยู่ในหมวดหมู่นี้ (บทความจะไม่ถูกลบ แต่จะถูกเปลี่ยนเป็น "ไม่มีหมวดหมู่")`;
    }

    if (!confirm(confirmMsg)) return;

    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        formData.append('_csrf', CSRF_TOKEN);

        const res = await fetch(API_URL, { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            const row = document.getElementById('cat-row-' + id);
            if (row) row.remove();
            const countDisplay = document.getElementById('catCountDisplay');
            if (countDisplay) {
                countDisplay.textContent = Math.max(0, parseInt(countDisplay.textContent) - 1);
            }
        } else {
            alert(data.message || 'ไม่สามารถลบได้');
        }
    } catch (err) {
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
