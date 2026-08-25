<?php
/**
 * Migration script for Real Views and Site Traffic Analytics
 */
require_once __DIR__ . '/includes/functions.php';

try {
    $db = db();
    
    // 1. Check and add `views` column to `article` table
    $colCheck = $db->query("SHOW COLUMNS FROM `article` LIKE 'views'")->fetch();
    if (!$colCheck) {
        $db->exec("ALTER TABLE `article` ADD COLUMN `views` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'จำนวนการเปิดอ่านบทความ' AFTER `status`, ADD INDEX `idx_article_views` (`views`)");
        echo "Added 'views' column to article table.\n";
    } else {
        echo "'views' column already exists in article table.\n";
    }

    // 2. Check and create `daily_traffic` table
    $sqlTraffic = "CREATE TABLE IF NOT EXISTS `daily_traffic` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `date` DATE NOT NULL UNIQUE COMMENT 'วันที่บันทึกสถิติ',
        `pageviews` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'จำนวนเปิดหน้าเพจรวม',
        `unique_visitors` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'จำนวนผู้เข้าชมไม่ซ้ำในแต่ละวัน',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_traffic_date` (`date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บสถิติการเข้าชมเว็บไซต์รายวัน';";
    $db->exec($sqlTraffic);
    echo "Table 'daily_traffic' ready.\n";

    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
