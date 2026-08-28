<?php
/**
 * Admin User Management — List, search, and manage admin users (Super Admin only).
 */
$pageTitle = 'การจัดการผู้ดูแลระบบ';
$page = 'users';
require_once __DIR__ . '/../includes/header.php';
require_super_admin();

$search = trim($_GET['search'] ?? '');
$roleFilter = trim($_GET['role'] ?? '');
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;

$whereSql = '';
$params = [];

if ($search !== '') {
    $whereSql .= ' AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (in_array($roleFilter, ['super_admin', 'admin'], true)) {
    $whereSql .= ' AND role = ?';
    $params[] = $roleFilter;
}

// Counts for stat cards
$totalUsers = (int) db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
$totalSuperAdmins = (int) db()->query("SELECT COUNT(*) FROM admins WHERE role = 'super_admin'")->fetchColumn();
$totalRegularAdmins = (int) db()->query("SELECT COUNT(*) FROM admins WHERE role = 'admin'")->fetchColumn();

// Filtered total count for pagination
$countStmt = db()->prepare('SELECT COUNT(*) FROM admins WHERE 1=1' . $whereSql);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

$sql = 'SELECT id, username, email, full_name, role, last_login, created_at, updated_at
        FROM admins
        WHERE 1=1' . $whereSql . '
        ORDER BY FIELD(role, "super_admin", "admin"), id ASC
        LIMIT ' . (int)$pagination['perPage'] . ' OFFSET ' . (int)$pagination['offset'];

$statement = db()->prepare($sql);
$statement->execute($params);
$users = $statement->fetchAll();
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8">
    <!-- Header Title & Add Button -->
    <header class="mb-5 flex flex-col gap-3 border-l-4 border-blue-500 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">การจัดการผู้ดูแลระบบ (Users & Roles)</h2>
            <p class="mt-1 text-xs text-slate-500">จัดการบัญชีผู้ใช้งาน สิทธิ์การเข้าถึง และรีเซ็ตรหัสผ่านสำหรับผู้ดูแลระบบ</p>
        </div>
        <a href="create.php"
            style="display: inline-flex; align-items: center; gap: 0.5rem; height: 2.375rem; padding: 0 1.125rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.75rem; background-color: #2563eb; color: #ffffff; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.15s;"
            onmouseover="this.style.backgroundColor='#1d4ed8';"
            onmouseout="this.style.backgroundColor='#2563eb';">
            <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>เพิ่มผู้ดูแลระบบใหม่</span>
        </a>
    </header>

    <!-- Clickable Status/Role Filter Cards (1x3 Horizontal Layout) -->
    <div style="display: flex; flex-direction: row; gap: 1rem; width: 100%; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <!-- Card 1: All Users -->
        <?php $isActiveAll = ($roleFilter === ''); ?>
        <a href="?role="
            style="flex: 1; min-width: 220px; display: flex; align-items: center; justify-content: space-between; padding: 1.125rem 1.25rem; border-radius: 1rem; text-decoration: none; transition: all 0.2s; cursor: pointer; <?= $isActiveAll ? 'border: 2px solid #2563eb; background-color: #eff6ff; box-shadow: 0 4px 14px rgba(37,99,235,0.15);' : 'border: 1px solid #e2e8f0; background-color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04);' ?>"
            onmouseover="if(!<?= $isActiveAll ? 'true' : 'false' ?>) { this.style.borderColor='#93c5fd'; this.style.backgroundColor='#f8fafc'; }"
            onmouseout="if(!<?= $isActiveAll ? 'true' : 'false' ?>) { this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff'; }">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; <?= $isActiveAll ? 'background-color: #2563eb; color: #ffffff;' : 'background-color: #eff6ff; color: #2563eb;' ?>">
                    <svg style="width: 1.375rem; height: 1.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; <?= $isActiveAll ? 'color: #1e40af;' : 'color: #64748b;' ?>">ผู้ดูแลระบบทั้งหมด</span>
                    <p style="margin: 0; font-size: 1.375rem; font-weight: 700; line-height: 1.25; <?= $isActiveAll ? 'color: #1d4ed8;' : 'color: #1e293b;' ?>"><?= number_format($totalUsers) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บัญชี</span></p>
                </div>
            </div>
            <?php if ($isActiveAll): ?>
                <span style="font-size: 0.6875rem; font-weight: 700; padding: 0.25rem 0.625rem; border-radius: 9999px; background-color: #2563eb; color: #ffffff;">เลือกอยู่</span>
            <?php endif; ?>
        </a>

        <!-- Card 2: Super Admin -->
        <?php $isActiveSuper = ($roleFilter === 'super_admin'); ?>
        <a href="?role=super_admin"
            style="flex: 1; min-width: 220px; display: flex; align-items: center; justify-content: space-between; padding: 1.125rem 1.25rem; border-radius: 1rem; text-decoration: none; transition: all 0.2s; cursor: pointer; <?= $isActiveSuper ? 'border: 2px solid #9333ea; background-color: #faf5ff; box-shadow: 0 4px 14px rgba(147,51,234,0.15);' : 'border: 1px solid #e2e8f0; background-color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04);' ?>"
            onmouseover="if(!<?= $isActiveSuper ? 'true' : 'false' ?>) { this.style.borderColor='#d8b4fe'; this.style.backgroundColor='#faf5ff'; }"
            onmouseout="if(!<?= $isActiveSuper ? 'true' : 'false' ?>) { this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff'; }">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; <?= $isActiveSuper ? 'background-color: #9333ea; color: #ffffff;' : 'background-color: #faf5ff; color: #9333ea;' ?>">
                    <svg style="width: 1.375rem; height: 1.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; <?= $isActiveSuper ? 'color: #6b21a8;' : 'color: #64748b;' ?>">Super Admin (สูงสุด)</span>
                    <p style="margin: 0; font-size: 1.375rem; font-weight: 700; line-height: 1.25; <?= $isActiveSuper ? 'color: #7e22ce;' : 'color: #1e293b;' ?>"><?= number_format($totalSuperAdmins) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บัญชี</span></p>
                </div>
            </div>
            <?php if ($isActiveSuper): ?>
                <span style="font-size: 0.6875rem; font-weight: 700; padding: 0.25rem 0.625rem; border-radius: 9999px; background-color: #9333ea; color: #ffffff;">เลือกอยู่</span>
            <?php endif; ?>
        </a>

        <!-- Card 3: Admin -->
        <?php $isActiveAdmin = ($roleFilter === 'admin'); ?>
        <a href="?role=admin"
            style="flex: 1; min-width: 220px; display: flex; align-items: center; justify-content: space-between; padding: 1.125rem 1.25rem; border-radius: 1rem; text-decoration: none; transition: all 0.2s; cursor: pointer; <?= $isActiveAdmin ? 'border: 2px solid #0284c7; background-color: #f0f9ff; box-shadow: 0 4px 14px rgba(2,132,199,0.15);' : 'border: 1px solid #e2e8f0; background-color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04);' ?>"
            onmouseover="if(!<?= $isActiveAdmin ? 'true' : 'false' ?>) { this.style.borderColor='#7dd3fc'; this.style.backgroundColor='#f0f9ff'; }"
            onmouseout="if(!<?= $isActiveAdmin ? 'true' : 'false' ?>) { this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff'; }">
            <div style="display: flex; align-items: center; gap: 0.875rem;">
                <div style="width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; <?= $isActiveAdmin ? 'background-color: #0284c7; color: #ffffff;' : 'background-color: #f0f9ff; color: #0284c7;' ?>">
                    <svg style="width: 1.375rem; height: 1.375rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <span style="display: block; font-size: 0.75rem; font-weight: 600; <?= $isActiveAdmin ? 'color: #0369a1;' : 'color: #64748b;' ?>">Admin (ทั่วไป)</span>
                    <p style="margin: 0; font-size: 1.375rem; font-weight: 700; line-height: 1.25; <?= $isActiveAdmin ? 'color: #0284c7;' : 'color: #1e293b;' ?>"><?= number_format($totalRegularAdmins) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บัญชี</span></p>
                </div>
            </div>
            <?php if ($isActiveAdmin): ?>
                <span style="font-size: 0.6875rem; font-weight: 700; padding: 0.25rem 0.625rem; border-radius: 9999px; background-color: #0284c7; color: #ffffff;">เลือกอยู่</span>
            <?php endif; ?>
        </a>
    </div>

    <!-- Users Table Section -->
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-medium">
                    <tr>
                        <th class="px-4 py-3 text-left w-12">#</th>
                        <th class="px-4 py-3 text-left">ผู้ใช้งาน (User)</th>
                        <th class="px-4 py-3 text-left">ชื่อ-นามสกุล</th>
                        <th class="px-4 py-3 text-left">บทบาท (Role)</th>
                        <th class="px-4 py-3 text-left">เข้าสู่ระบบล่าสุด</th>
                        <th class="px-4 py-3 text-left">วันที่สร้าง</th>
                        <th class="px-4 py-3 text-center w-28">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/60 bg-white">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                ไม่พบข้อมูลผู้ดูแลระบบตามเงื่อนไขที่ค้นหา
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $index => $u):
                            $isSelf = ((int)$u['id'] === (int)$me['id']);
                        ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3 text-slate-400 font-mono">
                                    <?= (int)$pagination['offset'] + $index + 1 ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full <?= $u['role'] === 'super_admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?> flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                            <?= mb_substr($u['username'], 0, 2) ?>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-800 flex items-center gap-1.5">
                                                <?= e($u['username']) ?>
                                                <?php if ($isSelf): ?>
                                                    <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-medium text-emerald-800">คุณ</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-[11px] text-slate-400 truncate"><?= e($u['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-700 font-medium">
                                    <?= e($u['full_name'] ?: '-') ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($u['role'] === 'super_admin'): ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; border-radius: 9999px; background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%); color: #7e22ce; padding: 0.25rem 0.625rem; font-size: 0.6875rem; font-weight: 700; border: 1px solid #d8b4fe; box-shadow: 0 1px 3px rgba(147,51,234,0.12);">
                                            <svg style="width: 13px; height: 13px; color: #9333ea; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Super Admin</span>
                                        </span>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; border-radius: 9999px; background-color: #eff6ff; color: #2563eb; padding: 0.25rem 0.625rem; font-size: 0.6875rem; font-weight: 600; border: 1px solid #bfdbfe;">
                                            <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #2563eb;"></span>
                                            <span>Admin</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-slate-500">
                                    <?php if ($u['last_login']): ?>
                                        <span title="<?= e($u['last_login']) ?>">
                                            <?= date('d/m/Y H:i', strtotime($u['last_login'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-400 italic">ยังไม่เคยล็อกอิน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-slate-500">
                                    <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= (int)$u['id'] ?>"
                                            style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.75rem; background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; transition: all 0.15s; text-decoration: none;"
                                            onmouseover="this.style.backgroundColor='#2563eb'; this.style.color='#ffffff';"
                                            onmouseout="this.style.backgroundColor='#eff6ff'; this.style.color='#2563eb';"
                                            title="แก้ไขข้อมูลผู้ใช้">
                                            <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>แก้ไข</span>
                                        </a>

                                        <?php if ($isSelf): ?>
                                            <button type="button" disabled
                                                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.75rem; background-color: #f1f5f9; color: #94a3b8; border: 1px solid #e2e8f0; cursor: not-allowed; opacity: 0.7;"
                                                title="ไม่สามารถลบบัญชีของตนเองได้">
                                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>ลบ</span>
                                            </button>
                                        <?php else: ?>
                                            <button type="button"
                                                onclick="confirmDelete(<?= (int)$u['id'] ?>, '<?= e(addslashes($u['username'])) ?>')"
                                                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 0.75rem; background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; transition: all 0.15s; cursor: pointer;"
                                                onmouseover="this.style.backgroundColor='#dc2626'; this.style.color='#ffffff';"
                                                onmouseout="this.style.backgroundColor='#fef2f2'; this.style.color='#dc2626';"
                                                title="ลบผู้ใช้นี้">
                                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>ลบ</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($pagination['pages'] > 1): ?>
            <div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3">
                <div class="text-xs text-slate-500">
                    แสดง <?= (int)$pagination['offset'] + 1 ?> ถึง <?= min($totalRows, (int)$pagination['offset'] + $perPage) ?> จากทั้งหมด <?= number_format($totalRows) ?> รายการ
                </div>
                <div class="flex gap-1">
                    <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                        <a href="?p=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($roleFilter) ?>"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold <?= $i === $pagination['current'] ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- Delete Confirmation Modal & Form -->
<form id="deleteForm" method="post" action="delete.php" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="deleteId" value="">
</form>

<script>
function confirmDelete(id, username) {
    if (confirm('คุณต้องการลบผู้ดูแลระบบ "' + username + '" ใช่หรือไม่?\n\nการกระทำนี้ไม่สามารถเรียกคืนได้')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
