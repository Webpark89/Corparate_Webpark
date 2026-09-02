<?php
/**
 * Database Migration Script for Dynamic RBAC (Role-Based Access Control)
 * Usage: Run via CLI `php admin/database/migrate_rbac.php` or via browser by authorized admin.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = db();
    echo "Starting RBAC Migration...\n";

    // 1. Create `roles` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `roles` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL COMMENT 'ชื่อบทบาท เช่น ผู้ดูแลระบบสูงสุด, เจ้าหน้าที่คอนเทนต์',
            `slug` VARCHAR(50) NOT NULL UNIQUE COMMENT 'รหัสบทบาท เช่น super_admin, editor, support',
            `description` VARCHAR(255) NULL COMMENT 'คำอธิบายบทบาทและขอบเขตหน้าที่',
            `is_system` TINYINT(1) DEFAULT 0 COMMENT '1 = Role ของระบบ ห้ามลบ',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลบทบาทผู้ใช้งาน (Roles)';
    ");
    echo "- Table `roles` ready.\n";

    // 2. Create `permissions` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `permissions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `module` VARCHAR(50) NOT NULL COMMENT 'กลุ่มโมดูล เช่น article, category, portfolio, review, partners, service, inbox, contact, users, roles',
            `module_name` VARCHAR(100) NOT NULL COMMENT 'ชื่อโมดูลภาษาไทย เช่น การจัดการบทความ',
            `action` VARCHAR(50) NOT NULL COMMENT 'การกระทำ เช่น view, create, edit, delete, update_status',
            `code` VARCHAR(100) NOT NULL UNIQUE COMMENT 'รหัสสิทธิ์ เช่น article.view, article.create',
            `name` VARCHAR(100) NOT NULL COMMENT 'ชื่อสิทธิ์ภาษาไทย เช่น ดูรายการบทความ',
            `sort_order` INT DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บ Master ข้อมูลสิทธิ์การใช้งาน (Permissions)';
    ");
    echo "- Table `permissions` ready.\n";

    // 3. Create `role_permissions` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `role_permissions` (
            `role_id` INT NOT NULL,
            `permission_id` INT NOT NULL,
            PRIMARY KEY (`role_id`, `permission_id`),
            FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางจับคู่บทบาทกับสิทธิ์การใช้งาน';
    ");
    echo "- Table `role_permissions` ready.\n";

    // 4. Add `role_id` column to `admins` table if not exists
    $stmt = $pdo->query("SHOW COLUMNS FROM `admins` LIKE 'role_id'");
    if (!$stmt->fetch()) {
        $pdo->exec("
            ALTER TABLE `admins` 
            ADD COLUMN `role_id` INT NULL AFTER `role`,
            ADD CONSTRAINT `fk_admins_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL;
        ");
        echo "- Column `role_id` added to `admins`.\n";
    } else {
        echo "- Column `role_id` already exists in `admins`.\n";
    }

    // 5. Seed Permissions Master Data
    $permissions = [
        // Article Module
        ['module' => 'article', 'module_name' => 'การจัดการบทความ', 'action' => 'view', 'code' => 'article.view', 'name' => 'ดูรายการบทความ', 'sort_order' => 10],
        ['module' => 'article', 'module_name' => 'การจัดการบทความ', 'action' => 'create', 'code' => 'article.create', 'name' => 'เพิ่มบทความใหม่', 'sort_order' => 11],
        ['module' => 'article', 'module_name' => 'การจัดการบทความ', 'action' => 'edit', 'code' => 'article.edit', 'name' => 'แก้ไขบทความ', 'sort_order' => 12],
        ['module' => 'article', 'module_name' => 'การจัดการบทความ', 'action' => 'delete', 'code' => 'article.delete', 'name' => 'ลบบทความ', 'sort_order' => 13],

        // Category Module
        ['module' => 'category', 'module_name' => 'หมวดหมู่บทความ', 'action' => 'view', 'code' => 'category.view', 'name' => 'ดูหมวดหมู่บทความ', 'sort_order' => 20],
        ['module' => 'category', 'module_name' => 'หมวดหมู่บทความ', 'action' => 'create', 'code' => 'category.create', 'name' => 'เพิ่มหมวดหมู่บทความ', 'sort_order' => 21],
        ['module' => 'category', 'module_name' => 'หมวดหมู่บทความ', 'action' => 'edit', 'code' => 'category.edit', 'name' => 'แก้ไขหมวดหมู่บทความ', 'sort_order' => 22],
        ['module' => 'category', 'module_name' => 'หมวดหมู่บทความ', 'action' => 'delete', 'code' => 'category.delete', 'name' => 'ลบหมวดหมู่บทความ', 'sort_order' => 23],

        // Portfolio Module
        ['module' => 'portfolio', 'module_name' => 'การจัดการผลงาน', 'action' => 'view', 'code' => 'portfolio.view', 'name' => 'ดูรายการผลงาน', 'sort_order' => 30],
        ['module' => 'portfolio', 'module_name' => 'การจัดการผลงาน', 'action' => 'create', 'code' => 'portfolio.create', 'name' => 'เพิ่มผลงานใหม่', 'sort_order' => 31],
        ['module' => 'portfolio', 'module_name' => 'การจัดการผลงาน', 'action' => 'edit', 'code' => 'portfolio.edit', 'name' => 'แก้ไขผลงาน', 'sort_order' => 32],
        ['module' => 'portfolio', 'module_name' => 'การจัดการผลงาน', 'action' => 'delete', 'code' => 'portfolio.delete', 'name' => 'ลบผลงาน', 'sort_order' => 33],

        // Review Module
        ['module' => 'review', 'module_name' => 'การจัดการรีวิว', 'action' => 'view', 'code' => 'review.view', 'name' => 'ดูรายการรีวิว', 'sort_order' => 40],
        ['module' => 'review', 'module_name' => 'การจัดการรีวิว', 'action' => 'create', 'code' => 'review.create', 'name' => 'เพิ่มรีวิวใหม่', 'sort_order' => 41],
        ['module' => 'review', 'module_name' => 'การจัดการรีวิว', 'action' => 'edit', 'code' => 'review.edit', 'name' => 'แก้ไขรีวิว', 'sort_order' => 42],
        ['module' => 'review', 'module_name' => 'การจัดการรีวิว', 'action' => 'delete', 'code' => 'review.delete', 'name' => 'ลบรีวิว', 'sort_order' => 43],

        // Partners Module
        ['module' => 'partners', 'module_name' => 'การจัดการลูกค้า/พาร์ทเนอร์', 'action' => 'view', 'code' => 'partners.view', 'name' => 'ดูรายการลูกค้า', 'sort_order' => 50],
        ['module' => 'partners', 'module_name' => 'การจัดการลูกค้า/พาร์ทเนอร์', 'action' => 'create', 'code' => 'partners.create', 'name' => 'เพิ่มลูกค้าใหม่', 'sort_order' => 51],
        ['module' => 'partners', 'module_name' => 'การจัดการลูกค้า/พาร์ทเนอร์', 'action' => 'edit', 'code' => 'partners.edit', 'name' => 'แก้ไขข้อมูลลูกค้า', 'sort_order' => 52],
        ['module' => 'partners', 'module_name' => 'การจัดการลูกค้า/พาร์ทเนอร์', 'action' => 'delete', 'code' => 'partners.delete', 'name' => 'ลบข้อมูลลูกค้า', 'sort_order' => 53],

        // Service Module
        ['module' => 'service', 'module_name' => 'การจัดการบริการ', 'action' => 'view', 'code' => 'service.view', 'name' => 'ดูรายการบริการ', 'sort_order' => 60],
        ['module' => 'service', 'module_name' => 'การจัดการบริการ', 'action' => 'create', 'code' => 'service.create', 'name' => 'เพิ่มบริการใหม่', 'sort_order' => 61],
        ['module' => 'service', 'module_name' => 'การจัดการบริการ', 'action' => 'edit', 'code' => 'service.edit', 'name' => 'แก้ไขข้อมูลบริการ', 'sort_order' => 62],
        ['module' => 'service', 'module_name' => 'การจัดการบริการ', 'action' => 'delete', 'code' => 'service.delete', 'name' => 'ลบข้อมูลบริการ', 'sort_order' => 63],

        // Contact Inbox Module
        ['module' => 'inbox', 'module_name' => 'ข้อความจากลูกค้า (Inbox)', 'action' => 'view', 'code' => 'inbox.view', 'name' => 'ดูข้อความที่ส่งเข้ามา', 'sort_order' => 70],
        ['module' => 'inbox', 'module_name' => 'ข้อความจากลูกค้า (Inbox)', 'action' => 'update_status', 'code' => 'inbox.update_status', 'name' => 'อัปเดตสถานะ/จัดการข้อความ', 'sort_order' => 71],
        ['module' => 'inbox', 'module_name' => 'ข้อความจากลูกค้า (Inbox)', 'action' => 'delete', 'code' => 'inbox.delete', 'name' => 'ลบข้อความติดต่อ', 'sort_order' => 72],

        // Contact Settings Module
        ['module' => 'contact', 'module_name' => 'การตั้งค่าการติดต่อและบริษัท', 'action' => 'view', 'code' => 'contact.view', 'name' => 'ดูข้อมูลการติดต่อ', 'sort_order' => 80],
        ['module' => 'contact', 'module_name' => 'การตั้งค่าการติดต่อและบริษัท', 'action' => 'edit', 'code' => 'contact.edit', 'name' => 'แก้ไขข้อมูลการติดต่อและบริษัท', 'sort_order' => 81],

        // User Management Module
        ['module' => 'users', 'module_name' => 'การจัดการผู้ดูแลระบบ (Users)', 'action' => 'view', 'code' => 'users.view', 'name' => 'ดูรายชื่อผู้ดูแลระบบ', 'sort_order' => 90],
        ['module' => 'users', 'module_name' => 'การจัดการผู้ดูแลระบบ (Users)', 'action' => 'create', 'code' => 'users.create', 'name' => 'เพิ่มบัญชีผู้ดูแลระบบ', 'sort_order' => 91],
        ['module' => 'users', 'module_name' => 'การจัดการผู้ดูแลระบบ (Users)', 'action' => 'edit', 'code' => 'users.edit', 'name' => 'แก้ไขบัญชีและรหัสผ่านผู้ดูแลระบบ', 'sort_order' => 92],
        ['module' => 'users', 'module_name' => 'การจัดการผู้ดูแลระบบ (Users)', 'action' => 'delete', 'code' => 'users.delete', 'name' => 'ลบบัญชีผู้ดูแลระบบ', 'sort_order' => 93],

        // Roles & Permissions Module
        ['module' => 'roles', 'module_name' => 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)', 'action' => 'view', 'code' => 'roles.view', 'name' => 'ดูรายการบทบาทและสิทธิ์', 'sort_order' => 100],
        ['module' => 'roles', 'module_name' => 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)', 'action' => 'create', 'code' => 'roles.create', 'name' => 'สร้างบทบาทใหม่', 'sort_order' => 101],
        ['module' => 'roles', 'module_name' => 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)', 'action' => 'edit', 'code' => 'roles.edit', 'name' => 'แก้ไขบทบาทและตารางสิทธิ์', 'sort_order' => 102],
        ['module' => 'roles', 'module_name' => 'การจัดการบทบาทและสิทธิ์ (Roles & Permissions)', 'action' => 'delete', 'code' => 'roles.delete', 'name' => 'ลบบทบาท', 'sort_order' => 103],
    ];

    $permStmt = $pdo->prepare("
        INSERT INTO `permissions` (`module`, `module_name`, `action`, `code`, `name`, `sort_order`)
        VALUES (:module, :module_name, :action, :code, :name, :sort_order)
        ON DUPLICATE KEY UPDATE `module_name` = VALUES(`module_name`), `name` = VALUES(`name`), `sort_order` = VALUES(`sort_order`)
    ");

    foreach ($permissions as $p) {
        $permStmt->execute($p);
    }
    echo "- Seeded " . count($permissions) . " permissions.\n";

    // 6. Seed Default Roles
    $roles = [
        [
            'name' => 'ผู้ดูแลระบบสูงสุด (Super Admin)',
            'slug' => 'super_admin',
            'description' => 'มีสิทธิ์เข้าถึง จัดการ แก้ไข และลบข้อมูลทุกอย่างในระบบ',
            'is_system' => 1,
        ],
        [
            'name' => 'ผู้จัดการฝ่ายเนื้อหา (Content Manager)',
            'slug' => 'content_manager',
            'description' => 'จัดการบทความ หมวดหมู่ ผลงาน รีวิว บริการ ลูกค้า และดูข้อความติดต่อได้เต็มระบบ',
            'is_system' => 0,
        ],
        [
            'name' => 'เจ้าหน้าที่เขียนบทความ (Editor)',
            'slug' => 'editor',
            'description' => 'ดู สร้าง และแก้ไขบทความ หมวดหมู่ และผลงาน (ไม่มีสิทธิ์ลบ)',
            'is_system' => 0,
        ],
        [
            'name' => 'เจ้าหน้าที่บริการลูกค้า (Support)',
            'slug' => 'support',
            'description' => 'ดูและอัปเดตสถานะข้อความติดต่อจากลูกค้าใน Inbox',
            'is_system' => 0,
        ],
        [
            'name' => 'ผู้เข้าชมข้อมูล (Viewer)',
            'slug' => 'viewer',
            'description' => 'สามารถดูข้อมูลและรายการต่างๆ ในระบบได้อย่างเดียว (Read-only)',
            'is_system' => 0,
        ],
    ];

    $roleStmt = $pdo->prepare("
        INSERT INTO `roles` (`name`, `slug`, `description`, `is_system`)
        VALUES (:name, :slug, :description, :is_system)
        ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `description` = VALUES(`description`), `is_system` = VALUES(`is_system`)
    ");

    foreach ($roles as $r) {
        $roleStmt->execute($r);
    }
    echo "- Seeded default roles.\n";

    // Fetch all role IDs and permission IDs
    $allRoles = $pdo->query("SELECT slug, id FROM `roles`")->fetchAll(PDO::FETCH_KEY_PAIR);
    $allPerms = $pdo->query("SELECT code, id FROM `permissions`")->fetchAll(PDO::FETCH_KEY_PAIR);

    $rolePermInsert = $pdo->prepare("
        INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES (:role_id, :permission_id)
    ");

    // Assign permissions to Super Admin (All permissions)
    if (isset($allRoles['super_admin'])) {
        $superAdminId = $allRoles['super_admin'];
        foreach ($allPerms as $permId) {
            $rolePermInsert->execute(['role_id' => $superAdminId, 'permission_id' => $permId]);
        }
        echo "- Assigned all permissions to Super Admin.\n";
    }

    // Assign permissions to Content Manager
    if (isset($allRoles['content_manager'])) {
        $managerId = $allRoles['content_manager'];
        $managerCodes = [
            'article.view', 'article.create', 'article.edit', 'article.delete',
            'category.view', 'category.create', 'category.edit', 'category.delete',
            'portfolio.view', 'portfolio.create', 'portfolio.edit', 'portfolio.delete',
            'review.view', 'review.create', 'review.edit', 'review.delete',
            'partners.view', 'partners.create', 'partners.edit', 'partners.delete',
            'service.view', 'service.create', 'service.edit', 'service.delete',
            'inbox.view', 'inbox.update_status', 'inbox.delete',
            'contact.view', 'contact.edit',
        ];
        foreach ($managerCodes as $code) {
            if (isset($allPerms[$code])) {
                $rolePermInsert->execute(['role_id' => $managerId, 'permission_id' => $allPerms[$code]]);
            }
        }
        echo "- Assigned permissions to Content Manager.\n";
    }

    // Assign permissions to Editor
    if (isset($allRoles['editor'])) {
        $editorId = $allRoles['editor'];
        $editorCodes = [
            'article.view', 'article.create', 'article.edit',
            'category.view', 'category.create', 'category.edit',
            'portfolio.view', 'portfolio.create', 'portfolio.edit',
            'review.view', 'review.create', 'review.edit',
            'partners.view', 'partners.create', 'partners.edit',
            'service.view', 'service.create', 'service.edit',
        ];
        foreach ($editorCodes as $code) {
            if (isset($allPerms[$code])) {
                $rolePermInsert->execute(['role_id' => $editorId, 'permission_id' => $allPerms[$code]]);
            }
        }
        echo "- Assigned permissions to Editor.\n";
    }

    // Assign permissions to Support
    if (isset($allRoles['support'])) {
        $supportId = $allRoles['support'];
        $supportCodes = ['inbox.view', 'inbox.update_status'];
        foreach ($supportCodes as $code) {
            if (isset($allPerms[$code])) {
                $rolePermInsert->execute(['role_id' => $supportId, 'permission_id' => $allPerms[$code]]);
            }
        }
        echo "- Assigned permissions to Support.\n";
    }

    // Assign permissions to Viewer (All view permissions)
    if (isset($allRoles['viewer'])) {
        $viewerId = $allRoles['viewer'];
        foreach ($allPerms as $code => $permId) {
            if (str_ends_with($code, '.view')) {
                $rolePermInsert->execute(['role_id' => $viewerId, 'permission_id' => $permId]);
            }
        }
        echo "- Assigned view permissions to Viewer.\n";
    }

    // 7. Map existing admins to new roles
    if (isset($allRoles['super_admin'])) {
        $pdo->exec("UPDATE `admins` SET `role_id` = {$allRoles['super_admin']} WHERE `role` = 'super_admin' AND `role_id` IS NULL");
    }
    if (isset($allRoles['content_manager'])) {
        $pdo->exec("UPDATE `admins` SET `role_id` = {$allRoles['content_manager']} WHERE `role` = 'admin' AND `role_id` IS NULL");
    }
    echo "- Mapped existing admin accounts to roles.\n";

    echo "RBAC Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
