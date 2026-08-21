-- Migration: 003_create_contact_messages.sql
-- Description: Create contact_messages table for frontend contact submissions

CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id`               INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสข้อความติดต่อ (Primary Key)',
    `company_name`     VARCHAR(255)  NULL     COMMENT 'ชื่อบริษัท (ไม่บังคับ)',
    `first_name`       VARCHAR(50)   NOT NULL COMMENT 'ชื่อจริง',
    `last_name`        VARCHAR(50)   NOT NULL COMMENT 'นามสกุล',
    `phone`            VARCHAR(20)   NOT NULL COMMENT 'เบอร์โทรศัพท์ (ตัวเลขล้วน ≤10 หลัก)',
    `email`            VARCHAR(255)  NOT NULL COMMENT 'อีเมลติดต่อ',
    `message`          TEXT          NOT NULL COMMENT 'ข้อความจากลูกค้า',
    `pdpa_consent`     TINYINT(1)    NOT NULL DEFAULT 0 COMMENT '1 = ยอมรับ PDPA',
    `pdpa_consent_at`  TIMESTAMP     NULL     COMMENT 'เวลาที่กดยอมรับ PDPA',
    `status`           ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new' COMMENT 'สถานะข้อความ',
    `ip_address`       VARCHAR(45)   NULL     COMMENT 'IP Address ผู้ส่ง',
    `user_agent`       TEXT          NULL     COMMENT 'User Agent ของ Browser',
    `source_page`      VARCHAR(255)  NULL     COMMENT 'หน้าที่ส่งฟอร์ม',
    `email_sent`       TINYINT(1)    NOT NULL DEFAULT 0 COMMENT '1 = ส่งอีเมลแจ้งเตือนสำเร็จ',
    `created_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่และเวลาที่ส่งข้อความ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อความติดต่อจากลูกค้า';
