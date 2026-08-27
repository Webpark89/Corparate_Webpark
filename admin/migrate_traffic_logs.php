<?php
/**
 * Migration script for Daily Visitor Logs (Accurate Unique Visitors Tracking)
 */
require_once __DIR__ . '/includes/functions.php';

try {
    $db = db();
    
    // Create `daily_visitor_logs` table
    $sql = "CREATE TABLE IF NOT EXISTS `daily_visitor_logs` (
        `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `date` DATE NOT NULL COMMENT 'วันที่เข้าชม',
        `visitor_hash` VARCHAR(64) NOT NULL COMMENT 'SHA-256 Hash ของ IP + UserAgent + Date',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `uniq_date_hash` (`date`, `visitor_hash`),
        INDEX `idx_date` (`date`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึก Hash ป้องกันการนับ Unique Visitor ซ้ำ';";
    
    $db->exec($sql);
    echo "Table 'daily_visitor_logs' created successfully.\n";

} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
