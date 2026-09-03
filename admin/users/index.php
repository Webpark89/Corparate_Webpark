<?php
/**
 * Admin User List & Management
 * Pixel-perfect responsive UI matching system standard.
 */
require_once __DIR__ . '/../includes/functions.php';
require_permission('users.view');

$search = trim($_GET['search'] ?? '');
$roleFilter = trim($_GET['role'] ?? '');
$roleIdFilter = (int)($_GET['role_id'] ?? 0);
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;

// Fetch all roles for dropdown & counts
$allRoles = db()->query('SELECT * FROM roles ORDER BY is_system DESC, id ASC')->fetchAll();

// If roleFilter slug is provided, map to role_id
if ($roleFilter !== '' && $roleIdFilter === 0) {
    foreach ($allRoles as $r) {
        if ($r['slug'] === $roleFilter) {
            $roleIdFilter = (int)$r['id'];
            break;
        }
    }
}

// Stats Calculation
$totalUsers = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
$totalSuperAdmins = (int) db()->query("
    SELECT COUNT(*) FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    WHERE r.slug = 'super_admin' OR (a.role_id IS NULL AND a.role = 'super_admin')
")->fetchColumn();
$totalRoles = count($allRoles);

// Query Construction
$whereSql = ' WHERE 1=1';
$params = [];

if ($search !== '') {
    $whereSql .= ' AND (a.username LIKE :search OR a.email LIKE :search OR a.full_name LIKE :search)';
    $params['search'] = "%$search%";
}

if ($roleIdFilter > 0) {
    $whereSql .= ' AND a.role_id = :role_id';
    $params['role_id'] = $roleIdFilter;
}

// Count total matching
$countStmt = db()->prepare("SELECT COUNT(*) FROM admins a $whereSql");
$countStmt->execute($params);
$totalRows = (int)$countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

// Fetch paginated admin users with joined roles
$sql = "
    SELECT 
        a.id,
        a.username,
        a.email,
        a.full_name,
        a.role,
        a.role_id,
        a.created_at,
        a.last_login,
        r.name AS role_name,
        r.slug AS role_slug,
        r.is_system AS role_is_system
    FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    $whereSql
    ORDER BY a.id ASC
    LIMIT :limit OFFSET :offset
";

$stmt = db()->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', (int)$pagination['perPage'], PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Handle Super Admin clear all rate limit lockouts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear_lockouts') {
    require_super_admin();
    csrf_verify();
    $cleared = clear_all_rate_limits();
    $_SESSION['flash_success'] = "ปลดล็อกและล้างประวัติการติดล็อกทั้งหมดเรียบร้อยแล้ว ({$cleared} รายการ)";
    header('Location: ' . ADMIN_URL . '/users/index.php');
    exit;
}

$pageTitle = 'Users';
$page = 'users';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8 space-y-5">
    <!-- Flash Notification -->
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div style="border-radius: 1rem; border: 1px solid #bbf7d0; background: #f0fdf4; padding: 0.875rem 1.25rem; display: flex; align-items: center; justify-content: space-between; color: #15803d;">
            <div style="display: flex; align-items: center; gap: 0.625rem; font-weight: 500; font-size: 0.875rem;">
                <svg style="width: 20px; height: 20px; color: #16a34a; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span><?= e($_SESSION['flash_success']) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #16a34a; cursor: pointer; padding: 4px;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- Page Header & Action Buttons -->
    <header class="flex flex-col gap-4 border-l-4 border-blue-600 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">การจัดการผู้ใช้งาน (Users)</h1>
            <p class="mt-1 text-xs text-slate-500">จัดการบัญชีผู้ใช้งาน กำหนดบทบาท และสิทธิ์การเข้าถึงระบบ</p>
        </div>
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.625rem;">
            <?php if (is_super_admin()): ?>
                <form method="post" onsubmit="return confirm('คุณต้องการปลดล็อกการระงับการเข้าสู่ระบบและรีเซ็ตเวลาของทุกบัญชี/IP ใช่หรือไม่?');" style="margin: 0;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="clear_lockouts">
                    <button type="submit"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; height: 38px; padding: 0 1rem; border-radius: 0.75rem; background-color: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; font-size: 0.75rem; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='#ffe4e6';"
                        onmouseout="this.style.backgroundColor='#fff1f2';">
                        <svg style="width: 16px; height: 16px; color: #e11d48;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        <span>ปลดล็อกระบบทั้งหมด</span>
                    </button>
                </form>
            <?php endif; ?>

            <?php if (has_permission('roles.view')): ?>
                <a href="<?= ADMIN_URL ?>/roles/index.php"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; height: 38px; padding: 0 1rem; border-radius: 0.75rem; background-color: #ffffff; color: #334155; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 600; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.03); transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#f8fafc';"
                    onmouseout="this.style.backgroundColor='#ffffff';">
                    <svg style="width: 16px; height: 16px; color: #64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>จัดการบทบาทและสิทธิ์</span>
                </a>
            <?php endif; ?>

            <?php if (has_permission('users.create')): ?>
                <a href="create.php"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; height: 38px; padding: 0 1.25rem; border-radius: 0.75rem; background-color: #0f172a; color: #ffffff; border: none; font-size: 0.75rem; font-weight: 600; text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#1e293b';"
                    onmouseout="this.style.backgroundColor='#0f172a';">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>เพิ่มผู้ใช้งานใหม่</span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Stats Summary Cards (Horizontal Responsive Grid) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
        <!-- Card 1 -->
        <a href="index.php" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid <?= ($roleIdFilter === 0 && $search === '') ? '#0f172a' : '#e2e8f0' ?>; background: #ffffff; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.15s;">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #f1f5f9; color: #0f172a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">ผู้ดูแลระบบทั้งหมด</div>
                    <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalUsers) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บัญชี</span></div>
                </div>
            </div>
            <?php if ($roleIdFilter === 0 && $search === ''): ?>
                <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; background: #0f172a; color: #ffffff; font-size: 0.625rem; font-weight: 700;">เลือกอยู่</span>
            <?php endif; ?>
        </a>

        <!-- Card 2 -->
        <a href="index.php?role=super_admin" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid <?= ($roleFilter === 'super_admin') ? '#7e22ce' : '#e2e8f0' ?>; background: #ffffff; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.15s;">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #faf5ff; color: #7e22ce; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">Super Admin (สูงสุด)</div>
                    <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalSuperAdmins) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บัญชี</span></div>
                </div>
            </div>
            <?php if ($roleFilter === 'super_admin'): ?>
                <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; background: #7e22ce; color: #ffffff; font-size: 0.625rem; font-weight: 700;">เลือกอยู่</span>
            <?php endif; ?>
        </a>

        <!-- Card 3 -->
        <a href="<?= ADMIN_URL ?>/roles/index.php" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: all 0.15s;">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #f1f5f9; color: #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">บทบาทในระบบทั้งหมด</div>
                    <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalRoles) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บทบาท</span></div>
                </div>
            </div>
            <span style="font-size: 0.75rem; font-weight: 600; color: #64748b;">จัดการ →</span>
        </a>
    </div>

    <!-- Search & Filter Bar (No Icon Overlap) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1rem 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <form method="get" action="index.php" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <!-- Search input box with integrated prefix icon -->
            <div style="flex: 1 1 280px; display: flex; align-items: center; height: 40px; border: 1px solid #e2e8f0; border-radius: 0.75rem; background: #f8fafc; overflow: hidden;">
                <span style="display: flex; align-items: center; justify-content: center; padding: 0 0.75rem; height: 100%; border-right: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.75rem; user-select: none;">
                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="<?= e($search) ?>" placeholder="ค้นหาชื่อผู้ใช้, อีเมล, ชื่อ-นามสกุล..."
                    style="flex: 1; height: 100%; border: none; outline: none; background: transparent; padding: 0 0.75rem; font-size: 0.75rem; color: #0f172a;">
            </div>

            <!-- Role Dropdown -->
            <div style="flex: 0 1 220px; min-width: 180px;">
                <select name="role_id" onchange="this.form.submit()"
                    style="width: 100%; height: 40px; padding: 0 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; background: #f8fafc; font-size: 0.75rem; color: #334155; outline: none; cursor: pointer;">
                    <option value="">-- บทบาททั้งหมด --</option>
                    <?php foreach ($allRoles as $r): ?>
                        <option value="<?= (int)$r['id'] ?>" <?= ($roleIdFilter === (int)$r['id']) ? 'selected' : '' ?>>
                            <?= e($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <button type="submit"
                    style="height: 40px; padding: 0 1.25rem; border-radius: 0.75rem; background: #0f172a; color: #ffffff; font-size: 0.75rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#1e293b';"
                    onmouseout="this.style.backgroundColor='#0f172a';">
                    ค้นหา
                </button>
                <?php if ($search !== '' || $roleIdFilter > 0 || $roleFilter !== ''): ?>
                    <a href="index.php"
                        style="display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 1rem; border-radius: 0.75rem; background: #ffffff; color: #475569; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.backgroundColor='#f8fafc';"
                        onmouseout="this.style.backgroundColor='#ffffff';">
                        ล้างค่า
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Users Table (Generous Spacing & No Icon Overlap) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #475569;">
                    <tr>
                        <th style="padding: 0.875rem 1rem; text-align: center; width: 48px;">#</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 220px;">ผู้ใช้งาน (User)</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 180px;">ชื่อ-นามสกุล</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 180px;">บทบาท (Role)</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 140px;">เข้าสู่ระบบล่าสุด</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 120px;">วันที่สร้าง</th>
                        <th style="padding: 0.875rem 1.25rem; text-align: right; min-width: 130px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody style="background-color: #ffffff;">
                    <?php foreach ($users as $index => $u): 
                        $isSuper = ($u['role_slug'] === 'super_admin' || $u['role'] === 'super_admin');
                        $roleDisplay = $u['role_name'] ?: ($isSuper ? 'Super Admin' : 'Admin');
                        $initials = mb_strtoupper(mb_substr($u['username'], 0, 2, 'UTF-8'), 'UTF-8');
                    ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='#ffffff';">
                            <!-- Index -->
                            <td style="padding: 1rem; text-align: center; color: #94a3b8; font-family: monospace;">
                                <?= $pagination['offset'] + $index + 1 ?>
                            </td>

                            <!-- User (Avatar + Username + Email) -->
                            <td style="padding: 1rem 1.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; background-color: <?= $isSuper ? '#faf5ff' : '#f1f5f9' ?>; color: <?= $isSuper ? '#7e22ce' : '#334155' ?>; border: 1px solid <?= $isSuper ? '#e9d5ff' : '#e2e8f0' ?>;">
                                        <?= e($initials) ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.75rem;">
                                            <?= e($u['username']) ?>
                                        </div>
                                        <div style="font-size: 0.6875rem; color: #94a3b8; margin-top: 0.125rem;">
                                            <?= e($u['email']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Full Name -->
                            <td style="padding: 1rem 1.25rem; color: #334155; font-weight: 500;">
                                <?= e($u['full_name'] ?: '—') ?>
                            </td>

                            <!-- Role Badge (Crisp, Elegant, Compact) -->
                            <td style="padding: 1rem 1.25rem;">
                                <?php if ($isSuper): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; background-color: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff;">
                                        <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #9333ea;"></span>
                                        <?= e($roleDisplay) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;">
                                        <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #2563eb;"></span>
                                        <?= e($roleDisplay) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Last Login -->
                            <td style="padding: 1rem 1.25rem; color: #64748b; font-family: monospace; font-size: 0.6875rem;">
                                <?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '—' ?>
                            </td>

                            <!-- Created At -->
                            <td style="padding: 1rem 1.25rem; color: #64748b; font-family: monospace; font-size: 0.6875rem;">
                                <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                            </td>

                            <!-- Actions -->
                            <td style="padding: 1rem 1.25rem; text-align: right;">
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 0.375rem;">
                                    <?php if (has_permission('users.edit')): ?>
                                        <a href="edit.php?id=<?= $u['id'] ?>"
                                            style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.375rem 0.75rem; border-radius: 0.5rem; background-color: #ffffff; color: #334155; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                                            onmouseover="this.style.backgroundColor='#f8fafc';"
                                            onmouseout="this.style.backgroundColor='#ffffff';">
                                            <svg style="width: 14px; height: 14px; color: #64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>แก้ไข</span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (has_permission('users.delete')): ?>
                                        <button type="button"
                                            onclick="openDeleteUserModal(<?= $u['id'] ?>, '<?= e(addslashes($u['username'])) ?>', <?= ((int)$_SESSION['admin_id'] === (int)$u['id']) ? 'true' : 'false' ?>, <?= ($isSuper && $totalSuperAdmins <= 1) ? 'true' : 'false' ?>)"
                                            style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem; border-radius: 0.5rem; background-color: #ffffff; color: #dc2626; border: 1px solid #fecaca; cursor: pointer; transition: all 0.15s;"
                                            onmouseover="this.style.backgroundColor='#fef2f2';"
                                            onmouseout="this.style.backgroundColor='#ffffff';"
                                            title="ลบผู้ดูแลระบบ">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" style="padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.75rem;">
                                ไม่พบข้อมูลผู้ดูแลระบบตรงกับเงื่อนไขการค้นหา
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalRows > $perPage): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.875rem 1.5rem; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 0.75rem;">
                <span style="color: #64748b;">
                    แสดง <?= min($pagination['offset'] + 1, $totalRows) ?> - <?= min($pagination['offset'] + $pagination['perPage'], $totalRows) ?> จากทั้งหมด <?= number_format($totalRows) ?> รายการ
                </span>
                <div style="display: flex; align-items: center; gap: 0.375rem;">
                    <?php for ($p = 1; $p <= $pagination['pages']; $p++): ?>
                        <a href="?p=<?= $p ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?><?= $roleIdFilter > 0 ? '&role_id=' . $roleIdFilter : '' ?>"
                            style="display: inline-flex; width: 32px; height: 32px; align-items: center; justify-content: center; border-radius: 0.5rem; font-weight: 600; text-decoration: none; font-size: 0.75rem; <?= $p === $currentPage ? 'background-color: #0f172a; color: #ffffff;' : 'background-color: #ffffff; border: 1px solid #e2e8f0; color: #334155;' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteUserModal" style="position: fixed; inset: 0; z-index: 50; display: none; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.4); padding: 1rem;">
    <div style="width: 100%; max-width: 440px; border-radius: 1rem; background-color: #ffffff; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem 0;" id="modalUserName">ยืนยันการลบผู้ใช้งาน</h3>
        <p style="font-size: 0.75rem; color: #64748b; line-height: 1.5; margin: 0 0 1.5rem 0;" id="modalUserDesc">
            คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีผู้ดูแลระบบนี้?
        </p>

        <form id="deleteUserForm" method="post" action="delete.php" style="display: flex; align-items: center; justify-content: flex-end; gap: 0.625rem; margin: 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="deleteUserId" value="">
            <button type="button" onclick="closeDeleteUserModal()"
                style="padding: 0.5rem 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #ffffff; font-size: 0.75rem; font-weight: 600; color: #334155; cursor: pointer;">
                ยกเลิก
            </button>
            <button type="submit" id="confirmDeleteUserBtn"
                style="padding: 0.5rem 1.25rem; border-radius: 0.75rem; background: #dc2626; border: none; font-size: 0.75rem; font-weight: 600; color: #ffffff; cursor: pointer;">
                ลบผู้ดูแลระบบ
            </button>
        </form>
    </div>
</div>

<script>
function openDeleteUserModal(userId, username, isSelf, isLastSuperAdmin) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('modalUserName').textContent = 'ลบผู้ดูแลระบบ: ' + username;
    const desc = document.getElementById('modalUserDesc');
    const submitBtn = document.getElementById('confirmDeleteUserBtn');

    if (isSelf) {
        desc.innerHTML = '<span style="color: #dc2626; font-weight: 600;">ข้อผิดพลาด:</span> คุณไม่สามารถลบบัญชีของตนเองที่กำลังล็อกอินอยู่ได้';
        submitBtn.disabled = true;
        submitBtn.style.backgroundColor = '#cbd5e1';
        submitBtn.style.cursor = 'not-allowed';
    } else if (isLastSuperAdmin) {
        desc.innerHTML = '<span style="color: #dc2626; font-weight: 600;">ข้อผิดพลาด:</span> ไม่สามารถลบ Super Admin คนสุดท้ายในระบบได้ (ต้องมี Super Admin อย่างน้อย 1 คนเสมอ)';
        submitBtn.disabled = true;
        submitBtn.style.backgroundColor = '#cbd5e1';
        submitBtn.style.cursor = 'not-allowed';
    } else {
        desc.textContent = 'คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีผู้ดูแลระบบ "' + username + '" ออกจากระบบ? การกระทำนี้ไม่สามารถยกเลิกได้';
        submitBtn.disabled = false;
        submitBtn.style.backgroundColor = '#dc2626';
        submitBtn.style.cursor = 'pointer';
    }

    const modal = document.getElementById('deleteUserModal');
    modal.style.display = 'flex';
}

function closeDeleteUserModal() {
    const modal = document.getElementById('deleteUserModal');
    modal.style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
