<?php
/**
 * Edit Role & Configure Permissions Matrix
 * Pixel-perfect UI matching system standard and Image 4 reference.
 */
require_once __DIR__ . '/../includes/functions.php';
require_permission('roles.edit');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    flash('error', 'ไม่พบบทบาทที่ต้องการแก้ไข');
    header('Location: index.php');
    exit;
}

$role = db()->prepare('SELECT * FROM roles WHERE id = ?');
$role->execute([$id]);
$role = $role->fetch();

if (!$role) {
    flash('error', 'ไม่พบข้อมูลบทบาทในระบบ');
    header('Location: index.php');
    exit;
}

$isSystem = (bool)$role['is_system'];
$isSuperAdmin = ($role['slug'] === 'super_admin');
$error = '';

$name = $role['name'];
$slug = $role['slug'];
$description = $role['description'] ?? '';

// Module definition with labels and mapping to action codes
$definedModules = [
    'dashboard' => [
        'name' => 'แดชบอร์ด (Dashboard)',
        'actions' => [
            'view' => ['code' => 'dashboard.view', 'name' => 'ดูข้อมูลแดชบอร์ด', 'col' => 'read']
        ]
    ],
    'article' => [
        'name' => 'การจัดการบทความ (Articles)',
        'actions' => [
            'create' => ['code' => 'article.create', 'name' => 'เพิ่มบทความ', 'col' => 'create'],
            'view'   => ['code' => 'article.view', 'name' => 'ดูบทความ', 'col' => 'read'],
            'edit'   => ['code' => 'article.edit', 'name' => 'แก้ไขบทความ', 'col' => 'update'],
            'delete' => ['code' => 'article.delete', 'name' => 'ลบบทความ', 'col' => 'delete'],
        ]
    ],
    'category' => [
        'name' => 'หมวดหมู่บทความ (Categories)',
        'actions' => [
            'create' => ['code' => 'category.create', 'name' => 'เพิ่มหมวดหมู่', 'col' => 'create'],
            'view'   => ['code' => 'category.view', 'name' => 'ดูหมวดหมู่', 'col' => 'read'],
            'edit'   => ['code' => 'category.edit', 'name' => 'แก้ไขหมวดหมู่', 'col' => 'update'],
            'delete' => ['code' => 'category.delete', 'name' => 'ลบหมวดหมู่', 'col' => 'delete'],
        ]
    ],
    'portfolio' => [
        'name' => 'การจัดการผลงาน (Portfolio)',
        'actions' => [
            'create' => ['code' => 'portfolio.create', 'name' => 'เพิ่มผลงาน', 'col' => 'create'],
            'view'   => ['code' => 'portfolio.view', 'name' => 'ดูผลงาน', 'col' => 'read'],
            'edit'   => ['code' => 'portfolio.edit', 'name' => 'แก้ไขผลงาน', 'col' => 'update'],
            'delete' => ['code' => 'portfolio.delete', 'name' => 'ลบผลงาน', 'col' => 'delete'],
        ]
    ],
    'review' => [
        'name' => 'การจัดการรีวิว (Reviews)',
        'actions' => [
            'create' => ['code' => 'review.create', 'name' => 'เพิ่มรีวิว', 'col' => 'create'],
            'view'   => ['code' => 'review.view', 'name' => 'ดูรีวิว', 'col' => 'read'],
            'edit'   => ['code' => 'review.edit', 'name' => 'แก้ไขรีวิว', 'col' => 'update'],
            'delete' => ['code' => 'review.delete', 'name' => 'ลบรีวิว', 'col' => 'delete'],
        ]
    ],
    'partners' => [
        'name' => 'การจัดการลูกค้า/พาร์ทเนอร์ (Partners)',
        'actions' => [
            'create' => ['code' => 'partners.create', 'name' => 'เพิ่มลูกค้า', 'col' => 'create'],
            'view'   => ['code' => 'partners.view', 'name' => 'ดูรายชื่อลูกค้า', 'col' => 'read'],
            'edit'   => ['code' => 'partners.edit', 'name' => 'แก้ไขข้อมูลลูกค้า', 'col' => 'update'],
            'delete' => ['code' => 'partners.delete', 'name' => 'ลบข้อมูลลูกค้า', 'col' => 'delete'],
        ]
    ],
    'service' => [
        'name' => 'การจัดการบริการ (Services)',
        'actions' => [
            'create' => ['code' => 'service.create', 'name' => 'เพิ่มบริการ', 'col' => 'create'],
            'view'   => ['code' => 'service.view', 'name' => 'ดูรายการบริการ', 'col' => 'read'],
            'edit'   => ['code' => 'service.edit', 'name' => 'แก้ไขบริการ', 'col' => 'update'],
            'delete' => ['code' => 'service.delete', 'name' => 'ลบบริการ', 'col' => 'delete'],
        ]
    ],
    'inbox' => [
        'name' => 'ข้อความจากลูกค้า (Inbox)',
        'actions' => [
            'view'          => ['code' => 'inbox.view', 'name' => 'ดูข้อความติดต่อ', 'col' => 'read'],
            'update_status' => ['code' => 'inbox.update_status', 'name' => 'อัปเดตสถานะ/จัดการ', 'col' => 'update'],
            'delete'        => ['code' => 'inbox.delete', 'name' => 'ลบข้อความ', 'col' => 'delete'],
        ]
    ],
    'contact' => [
        'name' => 'ตั้งค่าการติดต่อ (Contact Settings)',
        'actions' => [
            'view' => ['code' => 'contact.view', 'name' => 'ดูข้อมูลติดต่อ', 'col' => 'read'],
            'edit' => ['code' => 'contact.edit', 'name' => 'แก้ไขข้อมูลติดต่อ', 'col' => 'update'],
        ]
    ],
    'users' => [
        'name' => 'การจัดการผู้ใช้งาน (Users)',
        'actions' => [
            'create' => ['code' => 'users.create', 'name' => 'เพิ่มผู้ใช้งาน', 'col' => 'create'],
            'view'   => ['code' => 'users.view', 'name' => 'ดูรายชื่อผู้ใช้งาน', 'col' => 'read'],
            'edit'   => ['code' => 'users.edit', 'name' => 'แก้ไขผู้ใช้งาน', 'col' => 'update'],
            'delete' => ['code' => 'users.delete', 'name' => 'ลบผู้ใช้งาน', 'col' => 'delete'],
        ]
    ],
    'roles' => [
        'name' => 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)',
        'actions' => [
            'create' => ['code' => 'roles.create', 'name' => 'สร้างบทบาท', 'col' => 'create'],
            'view'   => ['code' => 'roles.view', 'name' => 'ดูรายการบทบาท', 'col' => 'read'],
            'edit'   => ['code' => 'roles.edit', 'name' => 'แก้ไขบทบาทและสิทธิ์', 'col' => 'update'],
            'delete' => ['code' => 'roles.delete', 'name' => 'ลบบทบาท', 'col' => 'delete'],
        ]
    ],
];

// Fetch all permissions from database to map code -> id
$dbPerms = db()->query('SELECT id, code, module, action FROM permissions')->fetchAll();
$permCodeToId = [];
foreach ($dbPerms as $p) {
    $permCodeToId[$p['code']] = (int)$p['id'];
}

// Fetch currently assigned permission IDs for this role
$currentPermIds = db()->query('SELECT permission_id FROM role_permissions WHERE role_id = ' . $id)->fetchAll(PDO::FETCH_COLUMN);
$currentPermIds = array_map('intval', $currentPermIds);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $selectedPermIds = array_map('intval', $_POST['permissions'] ?? []);

    if (!$isSystem) {
        $slug = strtolower(trim($_POST['slug'] ?? ''));
    }

    // Validation
    if ($name === '' || (!$isSystem && $slug === '')) {
        $error = 'กรุณากรอกชื่อบทบาทและรหัสบทบาทให้ครบถ้วน';
    } elseif (!$isSystem && !preg_match('/^[a-z0-9_]{2,50}$/', $slug)) {
        $error = 'รหัสบทบาท (Slug) ต้องเป็นภาษาอังกฤษตัวพิมพ์เล็ก ตัวเลข หรือ _ และยาว 2-50 ตัวอักษร';
    } else {
        // Check uniqueness for slug if changed
        if (!$isSystem && $slug !== $role['slug']) {
            $stmt = db()->prepare('SELECT id FROM roles WHERE slug = :slug AND id != :id LIMIT 1');
            $stmt->execute(['slug' => $slug, 'id' => $id]);
            if ($stmt->fetch()) {
                $error = 'รหัสบทบาท (Slug) "' . e($slug) . '" มีอยู่ในระบบแล้ว กรุณาใช้รหัสอื่น';
            }
        }

        if (!$error) {
            try {
                db()->beginTransaction();

                // 1. Update Role metadata
                if ($isSystem) {
                    $updateStmt = db()->prepare('
                        UPDATE roles 
                        SET name = :name, description = :description, updated_at = NOW() 
                        WHERE id = :id
                    ');
                    $updateStmt->execute([
                        'name' => $name,
                        'description' => $description ?: null,
                        'id' => $id,
                    ]);
                } else {
                    $updateStmt = db()->prepare('
                        UPDATE roles 
                        SET name = :name, slug = :slug, description = :description, updated_at = NOW() 
                        WHERE id = :id
                    ');
                    $updateStmt->execute([
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $description ?: null,
                        'id' => $id,
                    ]);
                }

                // 2. Sync Permissions (Delete old & Insert new)
                db()->prepare('DELETE FROM role_permissions WHERE role_id = ?')->execute([$id]);

                if (!empty($selectedPermIds)) {
                    $rpInsert = db()->prepare('INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)');
                    foreach ($selectedPermIds as $permId) {
                        if ($permId > 0) {
                            $rpInsert->execute(['role_id' => $id, 'permission_id' => $permId]);
                        }
                    }
                }

                db()->commit();

                // Refresh current admin permissions cache if editing own role
                if (isset($_SESSION['admin_role_id']) && (int)$_SESSION['admin_role_id'] === $id) {
                    refresh_current_admin_permissions($id);
                }

                flash('success', 'บันทึกการแก้ไขบทบาทและสิทธิ์ "' . $name . '" เรียบร้อยแล้ว');
                header('Location: index.php');
                exit;
            } catch (Exception $e) {
                db()->rollBack();
                $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage();
            }
        }
    }
} else {
    $selectedPermIds = $currentPermIds;
}

$pageTitle = 'Edit Role: ' . $role['name'];
$page = 'roles';
require_once __DIR__ . '/../includes/header.php';
?>

<style>
/* Custom Dark Checked Checkboxes matching Reference Image 4 */
input[type="checkbox"].matrix-checkbox {
    appearance: none;
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e1;
    border-radius: 6px;
    background-color: #ffffff;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    position: relative;
    vertical-align: middle;
    margin: 0;
    flex-shrink: 0;
}
input[type="checkbox"].matrix-checkbox:hover {
    border-color: #0f172a;
    background-color: #f8fafc;
}
input[type="checkbox"].matrix-checkbox:checked {
    background-color: #0f172a;
    border-color: #0f172a;
}
input[type="checkbox"].matrix-checkbox:checked::after {
    content: '';
    display: block;
    width: 5px;
    height: 10px;
    border: solid #ffffff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    position: absolute;
    top: 2px;
}
input[type="checkbox"].matrix-checkbox:indeterminate {
    background-color: #0f172a;
    border-color: #0f172a;
}
input[type="checkbox"].matrix-checkbox:indeterminate::after {
    content: '';
    display: block;
    width: 10px;
    height: 2.5px;
    background-color: #ffffff;
    border-radius: 1px;
}
</style>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8 space-y-6">
    <!-- Breadcrumb & Header Title -->
    <header class="border-l-4 border-blue-600 pl-4 flex flex-col gap-1">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.25rem;">
            <a href="index.php" style="color: #64748b; text-decoration: none;">การจัดการบทบาทและสิทธิ์</a>
            <span>/</span>
            <span style="color: #334155; font-weight: 500;">แก้ไขบทบาท</span>
        </div>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
            <h1 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
                <span>แก้ไขบทบาท: <?= e($role['name']) ?></span>
                <?php if ($isSystem): ?>
                    <span style="padding: 0.25rem 0.625rem; border-radius: 0.5rem; background-color: #faf5ff; color: #7e22ce; font-size: 0.75rem; font-weight: 700; border: 1px solid #e9d5ff;">System Role</span>
                <?php endif; ?>
            </h1>
        </div>
    </header>

    <?php if ($error): ?>
        <div style="padding: 1rem 1.25rem; border-radius: 1rem; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; font-size: 0.75rem; display: flex; align-items: center; gap: 0.75rem;">
            <svg style="width: 20px; height: 20px; color: #ef4444; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= e($error) ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= $id ?>" id="roleMatrixForm" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?= csrf_field() ?>

        <!-- Section 1: General Info -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 1.25rem 0;">ข้อมูลทั่วไป (General Info)</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <!-- Role Name -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;" for="name">
                        ชื่อบทบาท (Role Name) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="<?= e($name) ?>" required
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='#0f172a';"
                        onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <!-- Slug -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;" for="slug">
                        รหัสบทบาท (Slug ID) <?= $isSystem ? '' : '<span style="color: #ef4444;">*</span>' ?>
                    </label>
                    <input type="text" id="slug" name="slug" value="<?= e($slug) ?>"
                        <?= $isSystem ? 'readonly' : 'required' ?>
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background-color: <?= $isSystem ? '#f1f5f9' : '#ffffff' ?>; color: <?= $isSystem ? '#64748b' : '#0f172a' ?>; font-family: monospace; font-size: 0.8125rem; outline: none; cursor: <?= $isSystem ? 'not-allowed' : 'text' ?>;">
                    <?php if ($isSystem): ?>
                        <p style="font-size: 0.6875rem; color: #94a3b8; margin: 0.375rem 0 0 0;">บทบาทระบบหลักไม่อนุญาตให้เปลี่ยนรหัส Slug</p>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;" for="description">
                        คำอธิบายหน้าที่และความรับผิดชอบ (Description)
                    </label>
                    <input type="text" id="description" name="description" value="<?= e($description) ?>"
                        placeholder="อธิบายขอบเขตหน้าที่และความรับผิดชอบของบทบาทนี้โดยสังเขป"
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='#0f172a';"
                        onblur="this.style.borderColor='#cbd5e1';">
                </div>
            </div>
        </div>

        <!-- Section 2: Role & Permission Matrix -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <h2 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0;">สิทธิ์การใช้งานตามเมนูระบบ (Role & Permission)</h2>
                    <p style="font-size: 0.75rem; color: #94a3b8; margin: 0.25rem 0 0 0;">กำหนดสิทธิ์การดู เพิ่ม แก้ไข และลบข้อมูลในแต่ละเมนูระบบอย่างละเอียด</p>
                </div>

                <!-- Quick Presets -->
                <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem; border-radius: 0.75rem; background: #f1f5f9; border: 1px solid #e2e8f0;">
                    <button type="button" onclick="setPreset('all')"
                        style="padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; color: #334155; border: none; background: transparent; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none';">
                        เลือกทั้งหมด
                    </button>
                    <button type="button" onclick="setPreset('read')"
                        style="padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; color: #334155; border: none; background: transparent; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none';">
                        ดูอย่างเดียว (View only)
                    </button>
                    <button type="button" onclick="setPreset('none')"
                        style="padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; color: #64748b; border: none; background: transparent; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#dc2626'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#64748b'; this.style.boxShadow='none';">
                        ล้างทั้งหมด
                    </button>
                </div>
            </div>

            <!-- Permission Matrix Table -->
            <div style="overflow-x: auto; width: 100%; border: 1px solid #e2e8f0; border-radius: 0.75rem;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                    <!-- Table Header -->
                    <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0f172a;">
                        <tr>
                            <!-- Column 1: Module Master Toggle -->
                            <th style="padding: 0.875rem 1.25rem; width: 40%; min-width: 260px;">
                                <label style="display: inline-flex; align-items: center; gap: 0.75rem; cursor: pointer; user-select: none;">
                                    <input type="checkbox" id="masterModuleToggle" class="matrix-checkbox" onchange="toggleMasterAll(this)">
                                    <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;">เมนูระบบ (System Menu)</span>
                                </label>
                            </th>

                            <!-- Column 2: Create Toggle -->
                            <th style="padding: 0.875rem 1rem; text-align: center; min-width: 110px;">
                                <label style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                                    <input type="checkbox" class="matrix-checkbox column-toggle" data-col="create" onchange="toggleEntireColumn('create', this)">
                                    <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;">Create (เพิ่ม)</span>
                                </label>
                            </th>

                            <!-- Column 3: Read Toggle -->
                            <th style="padding: 0.875rem 1rem; text-align: center; min-width: 110px;">
                                <label style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                                    <input type="checkbox" class="matrix-checkbox column-toggle" data-col="read" onchange="toggleEntireColumn('read', this)">
                                    <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;">View (ดู)</span>
                                </label>
                            </th>

                            <!-- Column 4: Update Toggle -->
                            <th style="padding: 0.875rem 1rem; text-align: center; min-width: 110px;">
                                <label style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                                    <input type="checkbox" class="matrix-checkbox column-toggle" data-col="update" onchange="toggleEntireColumn('update', this)">
                                    <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;">Update (แก้ไข)</span>
                                </label>
                            </th>

                            <!-- Column 5: Delete Toggle -->
                            <th style="padding: 0.875rem 1rem; text-align: center; min-width: 110px;">
                                <label style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                                    <input type="checkbox" class="matrix-checkbox column-toggle" data-col="delete" onchange="toggleEntireColumn('delete', this)">
                                    <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;">Delete (ลบ)</span>
                                </label>
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body Rows -->
                    <tbody style="background-color: #ffffff;">
                        <?php foreach ($definedModules as $moduleKey => $m): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='#ffffff';">
                                <!-- Module Name & Row Master Checkbox -->
                                <td style="padding: 1rem 1.25rem;">
                                    <label style="display: inline-flex; align-items: center; gap: 0.75rem; cursor: pointer; user-select: none;">
                                        <input type="checkbox" class="matrix-checkbox row-master-checkbox"
                                            data-module="<?= e($moduleKey) ?>"
                                            onchange="toggleEntireRow('<?= e($moduleKey) ?>', this)">
                                        <span style="font-weight: 700; font-size: 0.75rem; color: #0f172a;"><?= e($m['name']) ?></span>
                                    </label>
                                </td>

                                <!-- Create Column -->
                                <td style="padding: 1rem; text-align: center;">
                                    <?php if (isset($m['actions']['create'])): 
                                        $act = $m['actions']['create'];
                                        $pId = $permCodeToId[$act['code']] ?? 0;
                                        $isChecked = in_array($pId, $selectedPermIds, true);
                                    ?>
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <input type="checkbox" name="permissions[]" value="<?= $pId ?>"
                                                class="matrix-checkbox perm-item"
                                                data-module="<?= e($moduleKey) ?>"
                                                data-col="create"
                                                data-action="create"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                onchange="onPermissionItemChange(this)">
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-family: monospace; font-size: 0.75rem;">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Read Column -->
                                <td style="padding: 1rem; text-align: center;">
                                    <?php if (isset($m['actions']['view'])): 
                                        $act = $m['actions']['view'];
                                        $pId = $permCodeToId[$act['code']] ?? 0;
                                        $isChecked = in_array($pId, $selectedPermIds, true);
                                    ?>
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <input type="checkbox" name="permissions[]" value="<?= $pId ?>"
                                                class="matrix-checkbox perm-item"
                                                data-module="<?= e($moduleKey) ?>"
                                                data-col="read"
                                                data-action="view"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                onchange="onPermissionItemChange(this)">
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-family: monospace; font-size: 0.75rem;">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Update Column -->
                                <td style="padding: 1rem; text-align: center;">
                                    <?php 
                                        $updateAct = $m['actions']['edit'] ?? ($m['actions']['update_status'] ?? null);
                                        if ($updateAct):
                                            $pId = $permCodeToId[$updateAct['code']] ?? 0;
                                            $isChecked = in_array($pId, $selectedPermIds, true);
                                    ?>
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <input type="checkbox" name="permissions[]" value="<?= $pId ?>"
                                                class="matrix-checkbox perm-item"
                                                data-module="<?= e($moduleKey) ?>"
                                                data-col="update"
                                                data-action="edit"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                onchange="onPermissionItemChange(this)">
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-family: monospace; font-size: 0.75rem;">—</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Delete Column -->
                                <td style="padding: 1rem; text-align: center;">
                                    <?php if (isset($m['actions']['delete'])): 
                                        $act = $m['actions']['delete'];
                                        $pId = $permCodeToId[$act['code']] ?? 0;
                                        $isChecked = in_array($pId, $selectedPermIds, true);
                                    ?>
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <input type="checkbox" name="permissions[]" value="<?= $pId ?>"
                                                class="matrix-checkbox perm-item"
                                                data-module="<?= e($moduleKey) ?>"
                                                data-col="delete"
                                                data-action="delete"
                                                <?= $isChecked ? 'checked' : '' ?>
                                                onchange="onPermissionItemChange(this)">
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-family: monospace; font-size: 0.75rem;">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Actions Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
            <a href="index.php"
                style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 600; color: #475569; text-decoration: none;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>ย้อนกลับ (Back)</span>
            </a>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="index.php"
                    style="display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 1.25rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; background-color: #ffffff; font-size: 0.75rem; font-weight: 600; color: #334155; text-decoration: none; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#f8fafc';"
                    onmouseout="this.style.backgroundColor='#ffffff';">
                    ยกเลิก (Cancel)
                </a>
                <button type="submit"
                    style="display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 1.75rem; border-radius: 0.75rem; background-color: #0f172a; font-size: 0.75rem; font-weight: 600; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#1e293b';"
                    onmouseout="this.style.backgroundColor='#0f172a';">
                    บันทึกข้อมูล (Save)
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Matrix Table Interaction Functions
function onPermissionItemChange(checkbox) {
    const module = checkbox.dataset.module;
    const action = checkbox.dataset.action;

    // Auto check 'view' when checking modify actions
    if (checkbox.checked && action !== 'view') {
        const viewCb = document.querySelector(`.perm-item[data-module="${module}"][data-col="read"]`);
        if (viewCb && !viewCb.checked) {
            viewCb.checked = true;
        }
    }

    updateRowMasterStatus(module);
    updateAllColumnToggles();
}

function toggleEntireRow(moduleKey, rowCheckbox) {
    const isChecked = rowCheckbox.checked;
    const items = document.querySelectorAll(`.perm-item[data-module="${moduleKey}"]`);
    items.forEach(cb => {
        cb.checked = isChecked;
    });
    updateAllColumnToggles();
}

function toggleEntireColumn(columnName, colCheckbox) {
    const isChecked = colCheckbox.checked;
    const items = document.querySelectorAll(`.perm-item[data-col="${columnName}"]`);
    items.forEach(cb => {
        cb.checked = isChecked;
        if (isChecked && columnName !== 'read') {
            const mod = cb.dataset.module;
            const viewCb = document.querySelector(`.perm-item[data-module="${mod}"][data-col="read"]`);
            if (viewCb) viewCb.checked = true;
        }
    });
    updateAllRowMasters();
}

function toggleMasterAll(masterCheckbox) {
    const isChecked = masterCheckbox.checked;
    document.querySelectorAll('.perm-item').forEach(cb => {
        cb.checked = isChecked;
    });
    document.querySelectorAll('.row-master-checkbox').forEach(cb => {
        cb.checked = isChecked;
        cb.indeterminate = false;
    });
    document.querySelectorAll('.column-toggle').forEach(cb => {
        cb.checked = isChecked;
        cb.indeterminate = false;
    });
}

function setPreset(type) {
    if (type === 'all') {
        document.querySelectorAll('.perm-item').forEach(cb => cb.checked = true);
    } else if (type === 'read') {
        document.querySelectorAll('.perm-item').forEach(cb => {
            cb.checked = (cb.dataset.col === 'read');
        });
    } else if (type === 'none') {
        document.querySelectorAll('.perm-item').forEach(cb => cb.checked = false);
    }
    updateAllRowMasters();
    updateAllColumnToggles();
}

function updateRowMasterStatus(moduleKey) {
    const items = document.querySelectorAll(`.perm-item[data-module="${moduleKey}"]`);
    const master = document.querySelector(`.row-master-checkbox[data-module="${moduleKey}"]`);
    if (!master || items.length === 0) return;

    const checkedCount = Array.from(items).filter(cb => cb.checked).length;
    if (checkedCount === 0) {
        master.checked = false;
        master.indeterminate = false;
    } else if (checkedCount === items.length) {
        master.checked = true;
        master.indeterminate = false;
    } else {
        master.checked = false;
        master.indeterminate = true;
    }
}

function updateAllRowMasters() {
    document.querySelectorAll('.row-master-checkbox').forEach(master => {
        const moduleKey = master.dataset.module;
        updateRowMasterStatus(moduleKey);
    });
}

function updateAllColumnToggles() {
    ['create', 'read', 'update', 'delete'].forEach(colName => {
        const colToggle = document.querySelector(`.column-toggle[data-col="${colName}"]`);
        const items = document.querySelectorAll(`.perm-item[data-col="${colName}"]`);
        if (!colToggle || items.length === 0) return;

        const checkedCount = Array.from(items).filter(cb => cb.checked).length;
        if (checkedCount === 0) {
            colToggle.checked = false;
            colToggle.indeterminate = false;
        } else if (checkedCount === items.length) {
            colToggle.checked = true;
            colToggle.indeterminate = false;
        } else {
            colToggle.checked = false;
            colToggle.indeterminate = true;
        }
    });

    // Update Master All
    const allPerms = document.querySelectorAll('.perm-item');
    const masterAll = document.getElementById('masterModuleToggle');
    if (masterAll && allPerms.length > 0) {
        const checkedAll = Array.from(allPerms).filter(cb => cb.checked).length;
        if (checkedAll === 0) {
            masterAll.checked = false;
            masterAll.indeterminate = false;
        } else if (checkedAll === allPerms.length) {
            masterAll.checked = true;
            masterAll.indeterminate = false;
        } else {
            masterAll.checked = false;
            masterAll.indeterminate = true;
        }
    }
}

// Initialize row/col master states on load
document.addEventListener('DOMContentLoaded', () => {
    updateAllRowMasters();
    updateAllColumnToggles();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
