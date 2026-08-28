<?php
/**
 * Edit Admin User & Reset Password (Super Admin only).
 */
require_once __DIR__ . '/../includes/functions.php';
require_super_admin();

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
$role = $user['role'] ?? 'admin';

// Count total super admins in system
$totalSuperAdmins = (int) db()->query("SELECT COUNT(*) FROM admins WHERE role = 'super_admin'")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $role = trim($_POST['role'] ?? 'admin');
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    // Validation
    if ($username === '' || $email === '') {
        $error = 'กรุณากรอกชื่อผู้ใช้และอีเมล';
    } elseif (!preg_match('/^[a-zA-Z0-9_.-]{3,50}$/', $username)) {
        $error = 'ชื่อผู้ใช้ (Username) ต้องเป็นภาษาอังกฤษ ตัวเลข หรือ _ . - และมีความยาว 3-50 ตัวอักษร';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'รูปแบบอีเมลไม่ถูกต้อง';
    } elseif (!in_array($role, ['super_admin', 'admin'], true)) {
        $error = 'บทบาท (Role) ที่เลือกไม่ถูกต้อง';
    } elseif ($user['role'] === 'super_admin' && $role !== 'super_admin' && $totalSuperAdmins <= 1) {
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
                    // Update with new password
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = db()->prepare(
                        'UPDATE admins 
                         SET username = :username, email = :email, full_name = :full_name, role = :role, password_hash = :hash, updated_at = NOW() 
                         WHERE id = :id'
                    );
                    $updateStmt->execute([
                        'username'  => $username,
                        'email'     => $email,
                        'full_name' => $fullName ?: null,
                        'role'      => $role,
                        'hash'      => $passwordHash,
                        'id'        => $id,
                    ]);
                } else {
                    // Update without changing password
                    $updateStmt = db()->prepare(
                        'UPDATE admins 
                         SET username = :username, email = :email, full_name = :full_name, role = :role, updated_at = NOW() 
                         WHERE id = :id'
                    );
                    $updateStmt->execute([
                        'username'  => $username,
                        'email'     => $email,
                        'full_name' => $fullName ?: null,
                        'role'      => $role,
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
$pageTitle = 'แก้ไขข้อมูลผู้ดูแลระบบ';
$page = 'users';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="mx-auto w-full max-w-6xl px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <!-- Breadcrumb & Header Title -->
    <div class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="index.php" class="hover:text-blue-600 transition">การจัดการผู้ดูแลระบบ</a>
                <span>/</span>
                <span class="text-slate-600 font-medium">แก้ไขข้อมูลผู้ดูแลระบบ</span>
            </div>
            <h2 class="text-lg font-bold text-slate-900">
                แก้ไขข้อมูลผู้ดูแลระบบ: <?= e($user['username']) ?>
                <?php if ($isSelf): ?>
                    <span class="ml-2 rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">บัญชีของคุณ</span>
                <?php endif; ?>
            </h2>
        </div>
        <a href="index.php"
            class="inline-flex h-9 items-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 shadow-sm">
            ← ย้อนกลับ
        </a>
    </div>

    <?php if ($error): ?>
        <div class="mb-5 p-4 text-xs text-red-800 rounded-2xl bg-red-50 border border-red-200 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= e($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- User Meta Info Card -->
    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50/50 p-4 text-xs text-slate-500 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="font-semibold text-slate-700">รหัสผู้ใช้ ID:</span>
            <span class="font-mono bg-white px-2 py-0.5 rounded-lg border border-slate-200 text-slate-800 font-bold">#<?= (int)$user['id'] ?></span>
        </div>
        <div class="flex items-center gap-4">
            <div>
                <span class="text-slate-400">สร้างเมื่อ:</span>
                <span class="text-slate-700 font-medium"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span>
            </div>
            <div>
                <span class="text-slate-400">ล็อกอินล่าสุด:</span>
                <span class="text-slate-700 font-medium"><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'ยังไม่เคยเข้าใช้งาน' ?></span>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <form id="editAdminForm" method="post" action="edit.php?id=<?= (int)$user['id'] ?>" novalidate class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <?= csrf_field() ?>

        <div class="p-6 space-y-6">
            <!-- Basic Information Section -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    ข้อมูลบัญชีผู้ใช้งาน
                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ชื่อผู้ใช้ (Username) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" required value="<?= e($username) ?>"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            อีเมล (Email) <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required value="<?= e($email) ?>"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ชื่อ-นามสกุลจริง (Full Name)
                        </label>
                        <input type="text" name="full_name" value="<?= e($fullName) ?>"
                            placeholder="เช่น นายสมชาย ใจดี"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Role Selection Section -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    บทบาทและสิทธิ์การใช้งาน (Role)
                </h3>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="relative flex cursor-pointer rounded-2xl border p-4 transition-all hover:bg-slate-50/50 <?= $role === 'admin' ? 'border-blue-500 bg-blue-50/20 ring-2 ring-blue-500/20' : 'border-slate-200' ?>">
                        <input type="radio" name="role" value="admin" <?= $role === 'admin' ? 'checked' : '' ?> class="sr-only" onchange="updateRoleCards()">
                        <div class="flex items-start gap-3">
                            <div class="w-4 h-4 mt-0.5 rounded-full border flex items-center justify-center <?= $role === 'admin' ? 'border-blue-600 bg-blue-600' : 'border-slate-300' ?>" id="radio-admin">
                                <div class="w-1.5 h-1.5 rounded-full bg-white <?= $role === 'admin' ? '' : 'hidden' ?>" id="dot-admin"></div>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-800">Admin (ผู้ดูแลระบบทั่วไป)</span>
                                <span class="block mt-1 text-[11px] text-slate-500 leading-relaxed">
                                    จัดการเนื้อหาเว็บไซต์ได้ทั้งหมด แต่ไม่มีสิทธิ์จัดการผู้ใช้งานคนอื่น
                                </span>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex cursor-pointer rounded-2xl border p-4 transition-all hover:bg-slate-50/50 <?= $role === 'super_admin' ? 'border-purple-500 bg-purple-50/20 ring-2 ring-purple-500/20' : 'border-slate-200' ?>">
                        <input type="radio" name="role" value="super_admin" <?= $role === 'super_admin' ? 'checked' : '' ?> class="sr-only" onchange="updateRoleCards()">
                        <div class="flex items-start gap-3">
                            <div class="w-4 h-4 mt-0.5 rounded-full border flex items-center justify-center <?= $role === 'super_admin' ? 'border-purple-600 bg-purple-600' : 'border-slate-300' ?>" id="radio-super_admin">
                                <div class="w-1.5 h-1.5 rounded-full bg-white <?= $role === 'super_admin' ? '' : 'hidden' ?>" id="dot-super_admin"></div>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-purple-900">Super Admin (ผู้ดูแลระบบสูงสุด)</span>
                                <span class="block mt-1 text-[11px] text-slate-500 leading-relaxed">
                                    สิทธิ์สูงสุด สามารถเพิ่ม/แก้ไข/ลบผู้ดูแลระบบคนอื่นได้
                                </span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Password Reset Section with Eye Toggle & Live Checklist -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    เปลี่ยนรหัสผ่านใหม่ (Reset Password)
                </h3>
                <p class="text-[11px] text-slate-400 mb-4">เว้นว่างทั้งสองช่องไว้ หากไม่ต้องการเปลี่ยนรหัสผ่านเดิม</p>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            รหัสผ่านใหม่ (New Password)
                        </label>
                        <div class="relative flex items-center">
                            <input type="password" name="password" id="edit_password"
                                placeholder="เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 pr-11 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition">
                            <button type="button" onclick="togglePasswordVisibility('edit_password', this)"
                                style="position: absolute; right: 0.625rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0.25rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: color 0.15s;"
                                onmouseover="this.style.color='#475569';"
                                onmouseout="this.style.color='#94a3b8';"
                                title="แสดง/ซ่อนรหัสผ่าน">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ยืนยันรหัสผ่านใหม่ (Confirm Password)
                        </label>
                        <div class="relative flex items-center">
                            <input type="password" name="confirm_password" id="edit_confirm_password"
                                placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 pr-11 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:outline-none transition">
                            <button type="button" onclick="togglePasswordVisibility('edit_confirm_password', this)"
                                style="position: absolute; right: 0.625rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0.25rem; color: #94a3b8; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: color 0.15s;"
                                onmouseover="this.style.color='#475569';"
                                onmouseout="this.style.color='#94a3b8';"
                                title="แสดง/ซ่อนรหัสผ่าน">
                                <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Live Password Requirements Checklist (Image 2 style) -->
                <div id="edit_pwd_checklist" style="display: none; margin-top: 1rem; padding: 1rem; border-radius: 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: #334155; margin-bottom: 0.625rem;">
                        ข้อกำหนดความปลอดภัยของรหัสผ่าน:
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0.5rem; font-size: 0.75rem;">
                        <div id="edit_rule_len" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; transition: all 0.2s;">
                            <span id="icon_edit_rule_len">✕</span>
                            <span>ความยาวอย่างน้อย 6 ตัวอักษร</span>
                        </div>
                        <div id="edit_rule_lower" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; transition: all 0.2s;">
                            <span id="icon_edit_rule_lower">✕</span>
                            <span>มีตัวพิมพ์เล็ก (a-z) อย่างน้อย 1 ตัว</span>
                        </div>
                        <div id="edit_rule_num" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; transition: all 0.2s;">
                            <span id="icon_edit_rule_num">✕</span>
                            <span>มีตัวเลข (0-9) อย่างน้อย 1 ตัว</span>
                        </div>
                        <div id="edit_rule_match" style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; transition: all 0.2s;">
                            <span id="icon_edit_rule_match">✕</span>
                            <span>รหัสผ่านทั้ง 2 ช่องตรงกัน</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions Footer -->
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-5">
            <a href="index.php"
                style="display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.75rem; background-color: #ffffff; color: #475569; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.15s;"
                onmouseover="this.style.backgroundColor='#f8fafc';"
                onmouseout="this.style.backgroundColor='#ffffff';">
                ยกเลิก
            </a>
            <button type="submit"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.625rem 1.75rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.75rem; background-color: #2563eb; color: #ffffff; border: none; cursor: pointer; white-space: nowrap; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.15s;"
                onmouseover="this.style.backgroundColor='#1d4ed8';"
                onmouseout="this.style.backgroundColor='#2563eb';">
                <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>บันทึกการเปลี่ยนแปลง</span>
            </button>
        </div>
    </form>
</div>

<script>
function updateRoleCards() {
    const radios = document.querySelectorAll('input[name="role"]');
    radios.forEach(radio => {
        const card = radio.closest('label');
        const dot = document.getElementById('dot-' + radio.value);
        const radioCircle = document.getElementById('radio-' + radio.value);

        if (radio.checked) {
            if (radio.value === 'super_admin') {
                card.className = 'relative flex cursor-pointer rounded-2xl border p-4 transition-all border-purple-500 bg-purple-50/20 ring-2 ring-purple-500/20';
                radioCircle.className = 'w-4 h-4 mt-0.5 rounded-full border flex items-center justify-center border-purple-600 bg-purple-600';
            } else {
                card.className = 'relative flex cursor-pointer rounded-2xl border p-4 transition-all border-blue-500 bg-blue-50/20 ring-2 ring-blue-500/20';
                radioCircle.className = 'w-4 h-4 mt-0.5 rounded-full border flex items-center justify-center border-blue-600 bg-blue-600';
            }
            if (dot) dot.classList.remove('hidden');
        } else {
            card.className = 'relative flex cursor-pointer rounded-2xl border p-4 transition-all border-slate-200 hover:bg-slate-50/50';
            radioCircle.className = 'w-4 h-4 mt-0.5 rounded-full border flex items-center justify-center border-slate-300';
            if (dot) dot.classList.add('hidden');
        }
    });
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>';
        btn.style.color = '#2563eb';
    } else {
        input.type = 'password';
        btn.innerHTML = '<svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
        btn.style.color = '#94a3b8';
    }
}

// Live Password Checklist Validator for Edit page
const editPwdInput = document.getElementById('edit_password');
const editConfInput = document.getElementById('edit_confirm_password');
const checklistContainer = document.getElementById('edit_pwd_checklist');

function validateEditPasswordLive() {
    const val = editPwdInput.value;
    const confVal = editConfInput.value;

    if (!val && !confVal) {
        checklistContainer.style.display = 'none';
        return true;
    }

    checklistContainer.style.display = 'block';

    const hasLen = val.length >= 6;
    const hasLower = /[a-z]/.test(val);
    const hasNum = /[0-9]/.test(val);
    const hasMatch = Boolean(val && confVal && val === confVal);

    updateCheckItem('edit_rule_len', 'icon_edit_rule_len', hasLen, 'ความยาวอย่างน้อย 6 ตัวอักษร');
    updateCheckItem('edit_rule_lower', 'icon_edit_rule_lower', hasLower, 'มีตัวพิมพ์เล็ก (a-z) อย่างน้อย 1 ตัว');
    updateCheckItem('edit_rule_num', 'icon_edit_rule_num', hasNum, 'มีตัวเลข (0-9) อย่างน้อย 1 ตัว');
    updateCheckItem('edit_rule_match', 'icon_edit_rule_match', hasMatch, 'รหัสผ่านทั้ง 2 ช่องตรงกัน');

    return hasLen && hasLower && hasNum && hasMatch;
}

function updateCheckItem(ruleId, iconId, isValid, text) {
    const item = document.getElementById(ruleId);
    const icon = document.getElementById(iconId);
    if (!item || !icon) return;

    if (isValid) {
        item.style.color = '#15803d';
        item.style.fontWeight = '600';
        icon.innerHTML = '✓';
        icon.style.color = '#16a34a';
        icon.style.fontWeight = '700';
    } else {
        item.style.color = '#94a3b8';
        item.style.fontWeight = '500';
        icon.innerHTML = '✕';
        icon.style.color = '#94a3b8';
        icon.style.fontWeight = '400';
    }
}

editPwdInput.addEventListener('input', validateEditPasswordLive);
editConfInput.addEventListener('input', validateEditPasswordLive);

document.getElementById('editAdminForm').addEventListener('submit', function(e) {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');

    if (!usernameInput.value.trim() || !emailInput.value.trim()) {
        alert('กรุณากรอกชื่อผู้ใช้และอีเมลให้ครบถ้วน');
        e.preventDefault();
        return;
    }

    if (editPwdInput.value || editConfInput.value) {
        if (!validateEditPasswordLive()) {
            alert('กรุณากรอกรหัสผ่านใหม่ให้ถูกต้องครบถ้วนตามข้อกำหนดความปลอดภัย');
            e.preventDefault();
        }
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
