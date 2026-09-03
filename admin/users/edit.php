<?php
/**
 * Edit Admin User & Reset Password
 * Pixel-perfect responsive UI with Horizontal Carousel and Live Role Search.
 */
require_once __DIR__ . '/../includes/functions.php';
require_permission('users.edit');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    flash('error', 'ไม่พบข้อมูลผู้ดูแลระบบที่ระบุ');
    header('Location: index.php');
    exit;
}

$user = find_admin_by_id($id);
if (!$user) {
    flash('error', 'ไม่พบข้อมูลผู้ดูแลระบบที่ระบุในฐานข้อมูล');
    header('Location: index.php');
    exit;
}

$me = current_admin();
$isSelf = ($id === (int)$me['id']);
$error = '';

$username = $user['username'];
$email = $user['email'];
$fullName = $user['full_name'] ?? '';
$selectedRoleId = (int)($user['role_id'] ?? 0);

// Fetch all available roles
$allRoles = db()->query('SELECT * FROM roles ORDER BY is_system DESC, id ASC')->fetchAll();

// If user's role_id is not set, find by role slug
if ($selectedRoleId === 0 && !empty($user['role'])) {
    foreach ($allRoles as $r) {
        if ($r['slug'] === $user['role']) {
            $selectedRoleId = (int)$r['id'];
            break;
        }
    }
}

// Count total super admins in system
$totalSuperAdmins = (int) db()->query("
    SELECT COUNT(*) FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    WHERE r.slug = 'super_admin' OR (a.role_id IS NULL AND a.role = 'super_admin')
")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $newRoleId = (int)($_POST['role_id'] ?? 0);
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    // Find selected role
    $newRole = null;
    foreach ($allRoles as $r) {
        if ((int)$r['id'] === $newRoleId) {
            $newRole = $r;
            break;
        }
    }

    $isTargetCurrentSuper = ($user['role_slug'] === 'super_admin' || $user['role'] === 'super_admin');
    $isNewRoleSuper = ($newRole && $newRole['slug'] === 'super_admin');

    // Validation
    if ($username === '' || $email === '' || !$newRole) {
        $error = 'กรุณากรอกชื่อผู้ใช้ อีเมล และเลือกบทบาทที่ถูกต้อง';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
        $error = 'ชื่อผู้ใช้ (Username) ต้องเป็นภาษาอังกฤษ ตัวเลข หรือ _ . - และมีความยาว 3-50 ตัวอักษร';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'รูปแบบอีเมลไม่ถูกต้อง';
    } elseif ($isTargetCurrentSuper && !$isNewRoleSuper && $totalSuperAdmins <= 1) {
        $error = 'ไม่สามารถลดระดับสิทธิ์ของ Super Admin คนสุดท้ายในระบบได้ (ต้องมี Super Admin อย่างน้อย 1 คนเสมอ)';
    } elseif ($password !== '' && strlen($password) < 6) {
        $error = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
    } elseif ($password !== '' && $password !== $confirmPassword) {
        $error = 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';
    } else {
        // Check uniqueness excluding current user ID
        $checkStmt = db()->prepare('SELECT id, username, email FROM admins WHERE (username = :u OR email = :e) AND id != :id LIMIT 1');
        $checkStmt->execute(['u' => $username, 'e' => $email, 'id' => $id]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            if (strcasecmp($existing['username'], $username) === 0) {
                $error = 'ชื่อผู้ใช้ (Username) นี้มีผู้ใช้งานอื่นใช้อยู่แล้ว';
            } else {
                $error = 'อีเมล (Email) นี้มีผู้ใช้งานอื่นใช้อยู่แล้ว';
            }
        } else {
            try {
                if ($password !== '') {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = db()->prepare(
                        'UPDATE admins 
                         SET username = :username, email = :email, full_name = :full_name, role = :role, role_id = :role_id, password_hash = :hash, updated_at = NOW() 
                         WHERE id = :id'
                    );
                    $updateStmt->execute([
                        'username'  => $username,
                        'email'     => $email,
                        'full_name' => $fullName ?: null,
                        'role'      => $newRole['slug'],
                        'role_id'   => $newRole['id'],
                        'hash'      => $passwordHash,
                        'id'        => $id,
                    ]);
                } else {
                    $updateStmt = db()->prepare(
                        'UPDATE admins 
                         SET username = :username, email = :email, full_name = :full_name, role = :role, role_id = :role_id, updated_at = NOW() 
                         WHERE id = :id'
                    );
                    $updateStmt->execute([
                        'username'  => $username,
                        'email'     => $email,
                        'full_name' => $fullName ?: null,
                        'role'      => $newRole['slug'],
                        'role_id'   => $newRole['id'],
                        'id'        => $id,
                    ]);
                }

                // If editing own profile, refresh session
                if ($isSelf) {
                    $freshUser = find_admin_by_id($id);
                    if ($freshUser) {
                        set_admin_session($freshUser);
                    }
                }

                flash('success', 'บันทึกการแก้ไขข้อมูลผู้ดูแลระบบ "' . $username . '" เรียบร้อยแล้ว');
                header('Location: index.php');
                exit;
            } catch (Exception $e) {
                $error = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage();
            }
        }
    }
}

// Render HTML Header only after POST handling
$pageTitle = 'Edit User: ' . $user['username'];
$page = 'users';
require_once __DIR__ . '/../includes/header.php';
?>

<style>
/* Custom Scrollbar for horizontal role carousel */
.roles-carousel::-webkit-scrollbar {
    height: 6px;
}
.roles-carousel::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 9999px;
}
.roles-carousel::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 9999px;
}
.roles-carousel::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8 space-y-6">
    <!-- Breadcrumb & Header Title -->
    <header class="border-l-4 border-blue-600 pl-4 flex flex-col gap-1">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.25rem;">
            <a href="index.php" style="color: #64748b; text-decoration: none;">การจัดการผู้ดูแลระบบ</a>
            <span>/</span>
            <span style="color: #334155; font-weight: 500;">แก้ไขข้อมูลผู้ดูแลระบบ</span>
        </div>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
            <h1 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
                <span>แก้ไขผู้ดูแลระบบ: <?= e($user['username']) ?></span>
                <?php if ($isSelf): ?>
                    <span style="padding: 0.25rem 0.625rem; border-radius: 0.5rem; background-color: #ecfdf5; color: #047857; font-size: 0.75rem; font-weight: 700; border: 1px solid #a7f3d0;">บัญชีของคุณ</span>
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

    <!-- User Meta Info Card -->
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; font-size: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="font-weight: 600; color: #334155;">รหัสผู้ใช้ ID:</span>
            <span style="font-family: monospace; background: #f1f5f9; padding: 0.125rem 0.5rem; border-radius: 0.375rem; border: 1px solid #e2e8f0; color: #0f172a; font-weight: 700;">#<?= (int)$user['id'] ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 1.5rem; color: #64748b;">
            <div>
                <span>สร้างเมื่อ:</span>
                <span style="color: #0f172a; font-weight: 600; margin-left: 0.25rem;"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
            </div>
            <div>
                <span>ล็อกอินล่าสุด:</span>
                <span style="color: #0f172a; font-weight: 600; margin-left: 0.25rem;"><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'ยังไม่เคยเข้าใช้งาน' ?></span>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form id="editAdminForm" method="post" action="edit.php?id=<?= (int)$user['id'] ?>" novalidate style="display: flex; flex-direction: column; gap: 1.5rem;">
        <?= csrf_field() ?>

        <!-- Section 1: General Info -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 1.25rem 0;">ข้อมูลทั่วไป (General Info)</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <!-- Full Name -->
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;" for="full_name">
                        ชื่อ-นามสกุล (Full Name)
                    </label>
                    <input type="text" name="full_name" id="full_name" value="<?= e($fullName) ?>"
                        placeholder="เช่น สมชาย ใจดี"
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: border-color 0.15s;"
                        onfocus="this.style.borderColor='#0f172a';"
                        onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <!-- Email -->
                <div>
                    <label id="label_email" style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; transition: color 0.15s;" for="email">
                        อีเมล (Email) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="<?= e($email) ?>"
                        placeholder="เช่น somchai@webpark.co.th"
                        class="admin-form-input"
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: all 0.15s;">
                    <p id="email_err" class="hidden text-xs font-medium text-red-500 mt-1 pl-1"></p>
                </div>

                <!-- Username -->
                <div style="grid-column: 1 / -1;">
                    <label id="label_username" style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; transition: color 0.15s;" for="username">
                        ชื่อผู้ใช้เข้าสู่ระบบ (Username) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="username" id="username" value="<?= e($username) ?>"
                        class="admin-form-input"
                        style="width: 100%; height: 42px; padding: 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: all 0.15s;">
                    <p id="username_err" class="hidden text-xs font-medium text-red-500 mt-1 pl-1"></p>
                </div>
            </div>
        </div>

        <!-- Section 2: Role Selection with Horizontal Carousel & Search -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <!-- Top Bar: Header & Controls (Search & Scroll Arrows) -->
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h2 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0;">กำหนดบทบาทและสิทธิ์ (Role & Access)</h2>
                        <span id="role_count_badge" style="font-size: 0.6875rem; font-weight: 600; background-color: #f1f5f9; color: #475569; padding: 0.125rem 0.5rem; border-radius: 9999px; border: 1px solid #e2e8f0;">
                            <?= count($allRoles) ?> บทบาท
                        </span>
                    </div>
                    <p style="font-size: 0.75rem; color: #94a3b8; margin: 0.25rem 0 0 0;">เลือกบทบาทที่ต้องการมอบหมายให้กับผู้ดูแลระบบคนนี้ (เลื่อนซ้าย-ขวา เพื่อดูทั้งหมด)</p>
                </div>

                <!-- Controls: Search Input & Carousel Navigation Buttons -->
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <!-- Instant Search Input -->
                    <div style="display: flex; align-items: center; height: 34px; border: 1px solid #cbd5e1; border-radius: 0.5rem; background: #ffffff; padding: 0 0.5rem; width: 190px;">
                        <svg style="width: 14px; height: 14px; color: #94a3b8; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="role_search_input" placeholder="ค้นหาบทบาท..." oninput="filterRoleCards(this.value)"
                            style="border: none; background: transparent; outline: none; padding: 0 0.375rem; font-size: 0.75rem; color: #0f172a; width: 100%;">
                    </div>

                    <!-- Carousel Navigation Arrows -->
                    <div style="display: flex; align-items: center; gap: 0.25rem;">
                        <button type="button" onclick="scrollRolesCarousel(-320)" title="เลื่อนซ้าย"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: #ffffff; color: #334155; cursor: pointer; transition: all 0.15s;"
                            onmouseover="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#cbd5e1';"
                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#e2e8f0';">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" onclick="scrollRolesCarousel(320)" title="เลื่อนขวา"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: #ffffff; color: #334155; cursor: pointer; transition: all 0.15s;"
                            onmouseover="this.style.backgroundColor='#f8fafc'; this.style.borderColor='#cbd5e1';"
                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#e2e8f0';">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <?php if (has_permission('roles.view')): ?>
                        <a href="<?= ADMIN_URL ?>/roles/index.php" target="_blank"
                            style="display: inline-flex; align-items: center; height: 34px; padding: 0 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; background: #ffffff; font-size: 0.75rem; font-weight: 600; color: #0f172a; text-decoration: none; transition: all 0.15s;"
                            onmouseover="this.style.backgroundColor='#f8fafc';"
                            onmouseout="this.style.backgroundColor='#ffffff';">
                            จัดการบทบาท →
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Horizontal Scrollable Cards Track -->
            <div id="rolesCarouselTrack" class="roles-carousel" style="display: flex; gap: 1rem; overflow-x: auto; scroll-snap-type: x mandatory; padding: 0.25rem 0.25rem 0.75rem 0.25rem; scroll-behavior: smooth;">
                <?php foreach ($allRoles as $r): 
                    $isSelected = ($selectedRoleId === (int)$r['id']);
                    $isSuper = ($r['slug'] === 'super_admin');
                ?>
                    <label class="role-card-item" data-role-name="<?= e(mb_strtolower($r['name'], 'UTF-8')) ?>" data-role-slug="<?= e(mb_strtolower($r['slug'], 'UTF-8')) ?>" data-role-desc="<?= e(mb_strtolower($r['description'] ?? '', 'UTF-8')) ?>"
                        style="position: relative; display: flex; flex: 0 0 290px; min-width: 290px; max-width: 290px; scroll-snap-align: start; cursor: pointer; border-radius: 0.875rem; padding: 1.125rem; border: 1px solid <?= $isSelected ? ($isSuper ? '#9333ea' : '#0f172a') : '#e2e8f0' ?>; background-color: <?= $isSelected ? ($isSuper ? '#faf5ff' : '#f8fafc') : '#ffffff' ?>; box-shadow: <?= $isSelected ? '0 4px 6px -1px rgba(0,0,0,0.05)' : '0 1px 2px rgba(0,0,0,0.02)' ?>; transition: all 0.15s;">
                        <input type="radio" name="role_id" value="<?= (int)$r['id'] ?>" <?= $isSelected ? 'checked' : '' ?> style="position: absolute; opacity: 0; width: 0; height: 0;" onchange="updateRoleSelection(this, <?= $isSuper ? 'true' : 'false' ?>)">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem; width: 100%;">
                            <div class="radio-circle" style="width: 18px; height: 18px; margin-top: 2px; border-radius: 9999px; border: 1px solid <?= $isSelected ? ($isSuper ? '#9333ea' : '#0f172a') : '#cbd5e1' ?>; background-color: <?= $isSelected ? ($isSuper ? '#9333ea' : '#0f172a') : 'transparent' ?>; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s;">
                                <div class="radio-dot" style="width: 6px; height: 6px; border-radius: 9999px; background-color: #ffffff; display: <?= $isSelected ? 'block' : 'none' ?>;"></div>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <span style="font-size: 0.8125rem; font-weight: 700; color: <?= $isSuper ? '#581c87' : '#0f172a' ?>; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= e($r['name']) ?>">
                                        <?= e($r['name']) ?>
                                    </span>
                                    <?php if ($r['is_system']): ?>
                                        <span style="font-size: 0.625rem; font-weight: 700; color: #7e22ce; background-color: #f3e8ff; padding: 0.125rem 0.5rem; border-radius: 9999px; flex-shrink: 0;">System</span>
                                    <?php endif; ?>
                                </div>
                                <div style="font-family: monospace; font-size: 0.6875rem; color: #64748b; margin-bottom: 0.375rem;">
                                    @<?= e($r['slug']) ?>
                                </div>
                                <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.6875rem; color: #64748b; line-height: 1.4;" title="<?= e($r['description'] ?: 'ไม่มีคำอธิบายเพิ่มเติม') ?>">
                                    <?= e($r['description'] ?: 'ไม่มีคำอธิบายเพิ่มเติม') ?>
                                </span>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>

                <!-- Empty Search Result -->
                <div id="rolesEmptySearch" style="display: none; padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.75rem; width: 100%;">
                    ไม่พบบทบาทที่ตรงกับคำค้นหา
                </div>
            </div>
        </div>

        <!-- Section 3: Password Reset -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1.5rem 1.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem 0;">เปลี่ยนรหัสผ่านใหม่ (Reset Password)</h2>
            <p style="font-size: 0.75rem; color: #94a3b8; margin: 0 0 1.25rem 0;">เว้นว่างทั้งสองช่องไว้ หากไม่ต้องการเปลี่ยนรหัสผ่านเดิม</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                <!-- New Password -->
                <div>
                    <label id="label_edit_password" style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; transition: color 0.15s;" for="edit_password">
                        รหัสผ่านใหม่ (New Password)
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="password" id="edit_password"
                            placeholder="เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน"
                            class="admin-form-input"
                            style="width: 100%; height: 42px; padding: 0 2.5rem 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: all 0.15s;">
                        <button type="button" onclick="togglePasswordVisibility('edit_password', this)"
                            style="position: absolute; right: 0.75rem; background: none; border: none; padding: 0.25rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center;">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="edit_password_err" class="hidden text-xs font-medium text-red-500 mt-1 pl-1"></p>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label id="label_edit_confirm_password" style="display: block; font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; transition: color 0.15s;" for="edit_confirm_password">
                        ยืนยันรหัสผ่านใหม่ (Confirm Password)
                    </label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" name="confirm_password" id="edit_confirm_password"
                            placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                            class="admin-form-input"
                            style="width: 100%; height: 42px; padding: 0 2.5rem 0 1rem; border-radius: 0.75rem; border: 1px solid #cbd5e1; background: #ffffff; font-size: 0.8125rem; color: #0f172a; outline: none; transition: all 0.15s;">
                        <button type="button" onclick="togglePasswordVisibility('edit_confirm_password', this)"
                            style="position: absolute; right: 0.75rem; background: none; border: none; padding: 0.25rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center;">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="edit_confirm_password_err" class="hidden text-xs font-medium text-red-500 mt-1 pl-1"></p>
                </div>
            </div>

            <!-- Password Requirements Checklist -->
            <div id="edit_pwd_checklist" style="display: none; margin-top: 1.25rem; padding: 1rem; border-radius: 0.75rem; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;">ข้อกำหนดความปลอดภัยของรหัสผ่าน:</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.5rem; font-size: 0.75rem;">
                    <div id="edit_rule_len" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8;">
                        <span id="icon_edit_rule_len">✕</span>
                        <span>ความยาวอย่างน้อย 6 ตัวอักษร</span>
                    </div>
                    <div id="edit_rule_lower" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8;">
                        <span id="icon_edit_rule_lower">✕</span>
                        <span>มีตัวพิมพ์เล็ก (a-z) อย่างน้อย 1 ตัว</span>
                    </div>
                    <div id="edit_rule_num" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8;">
                        <span id="icon_edit_rule_num">✕</span>
                        <span>มีตัวเลข (0-9) อย่างน้อย 1 ตัว</span>
                    </div>
                    <div id="edit_rule_match" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8;">
                        <span id="icon_edit_rule_match">✕</span>
                        <span>รหัสผ่านทั้ง 2 ช่องตรงกัน</span>
                    </div>
                </div>
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
                    บันทึกการเปลี่ยนแปลง (Save)
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Role Selection Carousel & Filtering
function scrollRolesCarousel(offset) {
    const track = document.getElementById('rolesCarouselTrack');
    if (track) {
        track.scrollBy({ left: offset, behavior: 'smooth' });
    }
}

function filterRoleCards(query) {
    query = query.trim().toLowerCase();
    const cards = document.querySelectorAll('.role-card-item');
    const emptyMsg = document.getElementById('rolesEmptySearch');
    let visibleCount = 0;

    cards.forEach(card => {
        const name = card.dataset.roleName || '';
        const slug = card.dataset.roleSlug || '';
        const desc = card.dataset.roleDesc || '';

        if (!query || name.includes(query) || slug.includes(query) || desc.includes(query)) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if (emptyMsg) {
        emptyMsg.style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    const badge = document.getElementById('role_count_badge');
    if (badge) {
        badge.textContent = visibleCount + ' บทบาท' + (query ? ' (พบ)' : '');
    }
}

function updateRoleSelection(radio, isSuper) {
    document.querySelectorAll('input[name="role_id"]').forEach(r => {
        const card = r.closest('label');
        const dot = card.querySelector('.radio-dot');
        const circle = card.querySelector('.radio-circle');

        if (r.checked) {
            if (isSuper) {
                card.style.borderColor = '#9333ea';
                card.style.backgroundColor = '#faf5ff';
                circle.style.borderColor = '#9333ea';
                circle.style.backgroundColor = '#9333ea';
            } else {
                card.style.borderColor = '#0f172a';
                card.style.backgroundColor = '#f8fafc';
                circle.style.borderColor = '#0f172a';
                circle.style.backgroundColor = '#0f172a';
            }
            if (dot) dot.style.display = 'block';
        } else {
            card.style.borderColor = '#e2e8f0';
            card.style.backgroundColor = '#ffffff';
            circle.style.borderColor = '#cbd5e1';
            circle.style.backgroundColor = 'transparent';
            if (dot) dot.style.display = 'none';
        }
    });
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.style.color = '#0f172a';
    } else {
        input.type = 'password';
        btn.style.color = '#94a3b8';
    }
}

<style>
.is-invalid-user {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
}
.label-invalid-user {
    color: #ef4444 !important;
}
</style>

// Live Password Checklist Validator for Edit page
const editPwdInput = document.getElementById('edit_password');
const editConfInput = document.getElementById('edit_confirm_password');
const editPwdErr = document.getElementById('edit_password_err');
const editConfErr = document.getElementById('edit_confirm_password_err');
const checklistContainer = document.getElementById('edit_pwd_checklist');

const usernameInput = document.getElementById('username');
const emailInput = document.getElementById('email');
const lblUser = document.getElementById('label_username');
const lblEmail = document.getElementById('label_email');
const lblEditPwd = document.getElementById('label_edit_password');
const lblEditConf = document.getElementById('label_edit_confirm_password');
const userErr = document.getElementById('username_err');
const emailErr = document.getElementById('email_err');

function setUserError(inputEl, labelEl, errEl, msg) {
    if (inputEl) inputEl.classList.add('is-invalid-user');
    if (labelEl) labelEl.classList.add('label-invalid-user');
    if (errEl) {
        errEl.textContent = msg;
        errEl.classList.remove('hidden');
    }
}

function clearUserError(inputEl, labelEl, errEl) {
    if (inputEl) inputEl.classList.remove('is-invalid-user');
    if (labelEl) labelEl.classList.remove('label-invalid-user');
    if (errEl) {
        errEl.textContent = '';
        errEl.classList.add('hidden');
    }
}

if (usernameInput) {
    usernameInput.addEventListener('input', () => {
        if (usernameInput.value.trim()) clearUserError(usernameInput, lblUser, userErr);
    });
}

if (emailInput) {
    emailInput.addEventListener('input', () => {
        if (emailInput.value.trim()) clearUserError(emailInput, lblEmail, emailErr);
    });
}

function validateEditPasswordLive() {
    const val = editPwdInput.value;
    const confVal = editConfInput.value;

    if (!val && !confVal) {
        checklistContainer.style.display = 'none';
        clearUserError(editPwdInput, lblEditPwd, editPwdErr);
        clearUserError(editConfInput, lblEditConf, editConfErr);
        return true;
    }

    checklistContainer.style.display = 'block';

    const hasLen = val.length >= 6;
    const hasLower = /[a-z]/.test(val);
    const hasNum = /[0-9]/.test(val);
    const hasMatch = Boolean(val && confVal && val === confVal);

    updateCheckItem('edit_rule_len', 'icon_edit_rule_len', hasLen);
    updateCheckItem('edit_rule_lower', 'icon_edit_rule_lower', hasLower);
    updateCheckItem('edit_rule_num', 'icon_edit_rule_num', hasNum);
    updateCheckItem('edit_rule_match', 'icon_edit_rule_match', hasMatch);

    if (hasLen) clearUserError(editPwdInput, lblEditPwd, editPwdErr);
    if (hasMatch) clearUserError(editConfInput, lblEditConf, editConfErr);

    return hasLen && hasLower && hasNum && hasMatch;
}

function updateCheckItem(ruleId, iconId, isValid) {
    const item = document.getElementById(ruleId);
    const icon = document.getElementById(iconId);
    if (!item || !icon) return;

    if (isValid) {
        item.style.color = '#047857';
        item.style.fontWeight = '600';
        icon.innerHTML = '✓';
        icon.style.color = '#059669';
        icon.style.fontWeight = '700';
    } else {
        item.style.color = '#94a3b8';
        item.style.fontWeight = '400';
        icon.innerHTML = '✕';
        icon.style.color = '#94a3b8';
        icon.style.fontWeight = '400';
    }
}

editPwdInput.addEventListener('input', validateEditPasswordLive);
editConfInput.addEventListener('input', validateEditPasswordLive);

document.getElementById('editAdminForm').addEventListener('submit', function(e) {
    let isValid = true;
    let firstInp = null;

    if (!usernameInput.value.trim()) {
        setUserError(usernameInput, lblUser, userErr, 'กรุณากรอกชื่อผู้ใช้เข้าสู่ระบบ');
        isValid = false;
        if (!firstInp) firstInp = usernameInput;
    }

    const emailVal = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailVal) {
        setUserError(emailInput, lblEmail, emailErr, 'กรุณากรอกอีเมล');
        isValid = false;
        if (!firstInp) firstInp = emailInput;
    } else if (!emailRegex.test(emailVal)) {
        setUserError(emailInput, lblEmail, emailErr, 'รูปแบบอีเมลไม่ถูกต้อง');
        isValid = false;
        if (!firstInp) firstInp = emailInput;
    }

    if (editPwdInput.value || editConfInput.value) {
        if (!editPwdInput.value) {
            setUserError(editPwdInput, lblEditPwd, editPwdErr, 'กรุณากรอกรหัสผ่านใหม่');
            isValid = false;
            if (!firstInp) firstInp = editPwdInput;
        } else if (editPwdInput.value.length < 6) {
            setUserError(editPwdInput, lblEditPwd, editPwdErr, 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร');
            isValid = false;
            if (!firstInp) firstInp = editPwdInput;
        }

        if (!editConfInput.value) {
            setUserError(editConfInput, lblEditConf, editConfErr, 'กรุณายืนยันรหัสผ่านใหม่');
            isValid = false;
            if (!firstInp) firstInp = editConfInput;
        } else if (editConfInput.value !== editPwdInput.value) {
            setUserError(editConfInput, lblEditConf, editConfErr, 'รหัสผ่านทั้ง 2 ช่องไม่ตรงกัน');
            isValid = false;
            if (!firstInp) firstInp = editConfInput;
        }
    }

    if (!isValid) {
        e.preventDefault();
        if (firstInp) firstInp.focus();
    }
});

// Auto-scroll selected card into view on page load
document.addEventListener('DOMContentLoaded', () => {
    const selectedRadio = document.querySelector('input[name="role_id"]:checked');
    if (selectedRadio) {
        const card = selectedRadio.closest('label');
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
