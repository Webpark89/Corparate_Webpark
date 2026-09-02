<?php
/**
 * Roles & Permissions Management List
 * Pixel-perfect responsive UI matching system standard.
 */
require_once __DIR__ . '/../includes/functions.php';
require_permission('roles.view');

$search = trim($_GET['search'] ?? '');
$currentPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;

// Stats calculation
$totalRoles = (int) db()->query('SELECT COUNT(*) FROM roles')->fetchColumn();
$totalSystemRoles = (int) db()->query('SELECT COUNT(*) FROM roles WHERE is_system = 1')->fetchColumn();
$totalCustomRoles = (int) db()->query('SELECT COUNT(*) FROM roles WHERE is_system = 0')->fetchColumn();
$totalPermissions = (int) db()->query('SELECT COUNT(*) FROM permissions')->fetchColumn();

// Query construction
$whereSql = '';
$params = [];
if ($search !== '') {
    $whereSql .= ' WHERE (r.name LIKE :q OR r.slug LIKE :q OR r.description LIKE :q)';
    $params['q'] = "%$search%";
}

// Count total matching
$countStmt = db()->prepare("SELECT COUNT(*) FROM roles r $whereSql");
$countStmt->execute($params);
$totalRows = (int)$countStmt->fetchColumn();

$pagination = paginate($totalRows, $perPage, $currentPage);

// Fetch roles with user count and permission count
$sql = "
    SELECT 
        r.*,
        (SELECT COUNT(*) FROM admins a WHERE a.role_id = r.id) AS user_count,
        (SELECT COUNT(*) FROM role_permissions rp WHERE rp.role_id = r.id) AS permission_count
    FROM roles r
    $whereSql
    ORDER BY r.is_system DESC, r.id ASC
    LIMIT :limit OFFSET :offset
";

$stmt = db()->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', (int)$pagination['perPage'], PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$roles = $stmt->fetchAll();

$pageTitle = 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)';
$page = 'roles';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="mx-auto w-full max-w-none px-2 pb-8 pt-1 text-sm md:px-4 lg:px-8 space-y-5">
    <!-- Page Header & Action Button -->
    <header class="flex flex-col gap-4 border-l-4 border-blue-600 pl-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">การจัดการบทบาทและสิทธิ์ (Roles & Permissions)</h1>
            <p class="mt-1 text-xs text-slate-500">กำหนดกลุ่มผู้ใช้งานและตั้งค่าสิทธิ์การเข้าถึงแบบละเอียด (ดู, เพิ่ม, แก้ไข, ลบ) แยกตามแต่ละโมดูล</p>
        </div>
        <?php if (has_permission('roles.create')): ?>
            <div>
                <a href="create.php"
                    style="display: inline-flex; align-items: center; gap: 0.5rem; height: 38px; padding: 0 1.25rem; border-radius: 0.75rem; background-color: #0f172a; color: #ffffff; border: none; font-size: 0.75rem; font-weight: 600; text-decoration: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); white-space: nowrap; transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#1e293b';"
                    onmouseout="this.style.backgroundColor='#0f172a';">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>สร้างบทบาทใหม่</span>
                </a>
            </div>
        <?php endif; ?>
    </header>

    <!-- Stats Summary Cards (Horizontal 4-Column Responsive Grid) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <!-- Card 1 -->
        <div style="display: flex; align-items: center; gap: 0.875rem; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #f1f5f9; color: #0f172a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">บทบาททั้งหมด</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalRoles) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บทบาท</span></div>
            </div>
        </div>

        <!-- Card 2 -->
        <div style="display: flex; align-items: center; gap: 0.875rem; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #faf5ff; color: #7e22ce; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">บทบาทระบบหลัก</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalSystemRoles) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บทบาท</span></div>
            </div>
        </div>

        <!-- Card 3 -->
        <div style="display: flex; align-items: center; gap: 0.875rem; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #f1f5f9; color: #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">บทบาทที่สร้างเอง</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalCustomRoles) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">บทบาท</span></div>
            </div>
        </div>

        <!-- Card 4 -->
        <div style="display: flex; align-items: center; gap: 0.875rem; padding: 1rem 1.25rem; border-radius: 1rem; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
            <div style="width: 40px; height: 40px; border-radius: 0.75rem; background-color: #f1f5f9; color: #0f172a; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.6875rem; font-weight: 500; color: #64748b;">สิทธิ์ทั้งหมดในระบบ</div>
                <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;"><?= number_format($totalPermissions) ?> <span style="font-size: 0.75rem; font-weight: 400; color: #94a3b8;">สิทธิ์</span></div>
            </div>
        </div>
    </div>

    <!-- Search Bar Card (No Icon Overlap) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 1rem 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <form method="get" action="index.php" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <div style="flex: 1 1 300px; display: flex; align-items: center; height: 40px; border: 1px solid #e2e8f0; border-radius: 0.75rem; background: #f8fafc; overflow: hidden;">
                <span style="display: flex; align-items: center; justify-content: center; padding: 0 0.75rem; height: 100%; border-right: 1px solid #e2e8f0; color: #94a3b8; font-size: 0.75rem; user-select: none;">
                    <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="<?= e($search) ?>" placeholder="ค้นหาชื่อบทบาท, รหัสบทบาท (Slug), หรือคำอธิบาย..."
                    style="flex: 1; height: 100%; border: none; outline: none; background: transparent; padding: 0 0.75rem; font-size: 0.75rem; color: #0f172a;">
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <button type="submit"
                    style="height: 40px; padding: 0 1.25rem; border-radius: 0.75rem; background: #0f172a; color: #ffffff; font-size: 0.75rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.15s;"
                    onmouseover="this.style.backgroundColor='#1e293b';"
                    onmouseout="this.style.backgroundColor='#0f172a';">
                    ค้นหา
                </button>
                <?php if ($search !== ''): ?>
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

    <!-- Roles Table (Clean Layout & No Icon Overlap) -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #475569;">
                    <tr>
                        <th style="padding: 0.875rem 1.25rem; min-width: 220px;">บทบาท (Role Name)</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 140px;">รหัส (Slug)</th>
                        <th style="padding: 0.875rem 1.25rem; min-width: 240px;">คำอธิบาย</th>
                        <th style="padding: 0.875rem 1rem; text-align: center; min-width: 130px;">สิทธิ์ที่ได้รับ</th>
                        <th style="padding: 0.875rem 1rem; text-align: center; min-width: 100px;">ผู้ใช้งาน</th>
                        <th style="padding: 0.875rem 1rem; text-align: center; min-width: 110px;">ประเภท</th>
                        <th style="padding: 0.875rem 1.25rem; text-align: right; min-width: 140px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody style="background-color: #ffffff;">
                    <?php foreach ($roles as $r): 
                        $isSuper = ($r['slug'] === 'super_admin');
                        $isSystem = (bool)$r['is_system'];
                        $permCount = (int)$r['permission_count'];
                        $userCount = (int)$r['user_count'];
                    ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='#ffffff';">
                            <!-- Role Name -->
                            <td style="padding: 1rem 1.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; background-color: <?= $isSuper ? '#faf5ff' : '#f1f5f9' ?>; color: <?= $isSuper ? '#7e22ce' : '#334155' ?>; border: 1px solid <?= $isSuper ? '#e9d5ff' : '#e2e8f0' ?>;">
                                        <?= mb_substr($r['name'], 0, 1, 'UTF-8') ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.75rem;">
                                            <?= e($r['name']) ?>
                                        </div>
                                        <div style="font-size: 0.6875rem; color: #94a3b8; margin-top: 0.125rem;">
                                            สร้างเมื่อ <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Slug -->
                            <td style="padding: 1rem 1.25rem;">
                                <code style="padding: 0.25rem 0.5rem; border-radius: 0.375rem; background-color: #f1f5f9; font-family: monospace; font-size: 0.6875rem; font-weight: 600; color: #334155; border: 1px solid #e2e8f0;">
                                    <?= e($r['slug']) ?>
                                </code>
                            </td>

                            <!-- Description -->
                            <td style="padding: 1rem 1.25rem; color: #475569; font-size: 0.75rem; line-height: 1.5; max-width: 280px;">
                                <?= e($r['description'] ?: 'ไม่มีคำอธิบาย') ?>
                            </td>

                            <!-- Permissions Count -->
                            <td style="padding: 1rem; text-align: center;">
                                <?php if ($isSuper): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                        <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #059669;"></span>
                                        ทุกสิทธิ์ (100%)
                                    </span>
                                <?php else: ?>
                                    <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">
                                        <?= $permCount ?> / <?= $totalPermissions ?> สิทธิ์
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Users count -->
                            <td style="padding: 1rem; text-align: center;">
                                <a href="<?= ADMIN_URL ?>/users/index.php?role_id=<?= $r['id'] ?>"
                                    style="display: inline-flex; align-items: center; gap: 0.25rem; font-weight: 700; color: #0f172a; text-decoration: none;">
                                    <span><?= $userCount ?></span>
                                    <span style="color: #94a3b8; font-weight: 400;">คน</span>
                                </a>
                            </td>

                            <!-- Type -->
                            <td style="padding: 1rem; text-align: center;">
                                <?php if ($isSystem): ?>
                                    <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 700; background-color: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff;">
                                        ระบบหลัก
                                    </span>
                                <?php else: ?>
                                    <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.625rem; font-weight: 600; background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                        กำหนดเอง
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td style="padding: 1rem 1.25rem; text-align: right;">
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 0.375rem;">
                                    <?php if (has_permission('roles.edit')): ?>
                                        <a href="edit.php?id=<?= $r['id'] ?>"
                                            style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.375rem 0.75rem; border-radius: 0.5rem; background-color: #ffffff; color: #334155; border: 1px solid #e2e8f0; font-size: 0.75rem; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                                            onmouseover="this.style.backgroundColor='#f8fafc';"
                                            onmouseout="this.style.backgroundColor='#ffffff';">
                                            <svg style="width: 14px; height: 14px; color: #64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>ตั้งค่าสิทธิ์</span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (has_permission('roles.delete') && !$isSystem): ?>
                                        <button type="button"
                                            onclick="openDeleteModal(<?= $r['id'] ?>, '<?= e(addslashes($r['name'])) ?>', <?= $userCount ?>)"
                                            style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem; border-radius: 0.5rem; background-color: #ffffff; color: #dc2626; border: 1px solid #fecaca; cursor: pointer; transition: all 0.15s;"
                                            onmouseover="this.style.backgroundColor='#fef2f2';"
                                            onmouseout="this.style.backgroundColor='#ffffff';"
                                            title="ลบบทบาทนี้">
                                            <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($roles)): ?>
                        <tr>
                            <td colspan="7" style="padding: 3rem 1rem; text-align: center; color: #94a3b8; font-size: 0.75rem;">
                                ไม่พบข้อมูลบทบาทตรงกับคำค้นหา
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
                        <a href="?p=<?= $p ?><?= $search !== '' ? '&search=' . urlencode($search) : '' ?>"
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
<div id="deleteModal" style="position: fixed; inset: 0; z-index: 50; display: none; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.4); padding: 1rem;">
    <div style="width: 100%; max-width: 440px; border-radius: 1rem; background-color: #ffffff; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 0.5rem 0;" id="modalRoleName">ยืนยันการลบบทบาท</h3>
        <p style="font-size: 0.75rem; color: #64748b; line-height: 1.5; margin: 0 0 1.5rem 0;" id="modalRoleDesc">
            คุณแน่ใจหรือไม่ว่าต้องการลบบทบาทนี้? การกระทำนี้ไม่สามารถย้อนกลับได้
        </p>

        <form id="deleteRoleForm" method="post" action="delete.php" style="display: flex; align-items: center; justify-content: flex-end; gap: 0.625rem; margin: 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="deleteRoleId" value="">
            <button type="button" onclick="closeDeleteModal()"
                style="padding: 0.5rem 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #ffffff; font-size: 0.75rem; font-weight: 600; color: #334155; cursor: pointer;">
                ยกเลิก
            </button>
            <button type="submit" id="confirmDeleteBtn"
                style="padding: 0.5rem 1.25rem; border-radius: 0.75rem; background: #dc2626; border: none; font-size: 0.75rem; font-weight: 600; color: #ffffff; cursor: pointer;">
                ลบบทบาท
            </button>
        </form>
    </div>
</div>

<script>
function openDeleteModal(roleId, roleName, userCount) {
    document.getElementById('deleteRoleId').value = roleId;
    document.getElementById('modalRoleName').textContent = 'ลบบทบาท: ' + roleName;
    const desc = document.getElementById('modalRoleDesc');
    const submitBtn = document.getElementById('confirmDeleteBtn');

    if (userCount > 0) {
        desc.innerHTML = '<span style="color: #dc2626; font-weight: 600;">คำเตือน:</span> มีผู้ดูแลระบบ ' + userCount + ' คนกำลังใช้งานบทบาทนี้อยู่ กรุณาย้ายผู้ใช้งานไปยังบทบาทอื่นก่อนลบ';
        submitBtn.disabled = true;
        submitBtn.style.backgroundColor = '#cbd5e1';
        submitBtn.style.cursor = 'not-allowed';
    } else {
        desc.textContent = 'คุณแน่ใจหรือไม่ว่าต้องการลบบทบาท "' + roleName + '" ออกจากระบบ? การกระทำนี้ไม่สามารถยกเลิกได้';
        submitBtn.disabled = false;
        submitBtn.style.backgroundColor = '#dc2626';
        submitBtn.style.cursor = 'pointer';
    }

    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
