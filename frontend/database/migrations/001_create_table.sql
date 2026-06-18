-- =====================================================================
-- 1. สร้างตารางหลัก (Master Tables)
-- =====================================================================

CREATE TABLE `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสผู้ดูแลระบบ (Primary Key)',
    `username` VARCHAR(100) NOT NULL UNIQUE COMMENT 'ชื่อผู้ใช้สำหรับ Login',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'อีเมลผู้ดูแลระบบ',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'รหัสผ่านที่เข้ารหัสแล้ว (Hashed Password)',
    `full_name` VARCHAR(255) COMMENT 'ชื่อ-นามสกุลจริง',
    `role` VARCHAR(50) DEFAULT 'admin' COMMENT 'สิทธิ์การใช้งาน เช่น admin, super_admin',
    `last_login` TIMESTAMP NULL DEFAULT NULL COMMENT 'เวลาที่ล็อกอินครั้งล่าสุด',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้างบัญชี',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขข้อมูลล่าสุด'
) COMMENT = 'ตารางเก็บข้อมูลผู้ดูแลระบบ (Admins)';

CREATE TABLE `authors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสผู้เขียน',
    `display_name` VARCHAR(255) NOT NULL COMMENT 'นามปากกา / ชื่อที่แสดงหน้าเว็บ',
    `bio` TEXT COMMENT 'ประวัติผู้เขียนย่อๆ',
    `avatar` VARCHAR(255) COMMENT 'รูปโปรไฟล์ขนาดเล็ก (URL/Path)',
    `contact` VARCHAR(255) COMMENT 'ข้อมูลติดต่อเพิ่มเติม',
    `email` VARCHAR(255) COMMENT 'อีเมลผู้เขียน',
    `profile_image` VARCHAR(255) COMMENT 'รูปโปรไฟล์เต็ม (URL/Path)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บข้อมูลผู้เขียนบทความและทีมงาน';

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL Slug (เช่น /category/news)',
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อหมวดหมู่',
    `parent_id` INT DEFAULT NULL COMMENT 'ID ของหมวดหมู่หลัก (ถ้าเป็น NULL คือหมวดหมู่ระดับบนสุด)',
    `description` TEXT COMMENT 'คำอธิบายหมวดหมู่',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) COMMENT = 'ตารางเก็บหมวดหมู่ทั่วไป (รองรับ Sub-category ผ่าน parent_id)';

CREATE TABLE `partner_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อหมวดหมู่พาร์ทเนอร์',
    `sort_order` INT DEFAULT 0 COMMENT 'ลำดับการแสดงผล (ค่าน้อยแสดงก่อน)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บหมวดหมู่ของพาร์ทเนอร์ธุรกิจ';

CREATE TABLE `company` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อบริษัท',
    `tax_id` VARCHAR(50) COMMENT 'เลขประจำตัวผู้เสียภาษี',
    `address` TEXT COMMENT 'ที่อยู่บริษัท',
    `phone` VARCHAR(50) COMMENT 'เบอร์โทรศัพท์ติดต่อ',
    `email` VARCHAR(255) COMMENT 'อีเมลติดต่อหลัก',
    `logo_path` VARCHAR(255) COMMENT 'พาร์ทเก็บรูปโลโก้บริษัท',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บข้อมูลบริษัท (ใช้แสดงในหน้า Contact / Footer)';

CREATE TABLE `erp_modules` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL Slug ของโมดูล',
    `title` VARCHAR(255) NOT NULL COMMENT 'ชื่อโมดูล ERP',
    `description` TEXT COMMENT 'คำอธิบายโมดูล',
    `icon_svg` TEXT COMMENT 'โค้ด SVG สำหรับไอคอน',
    `sort_order` INT DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
    `is_active` TINYINT(1) DEFAULT 1 COMMENT 'สถานะ: 1=เปิดใช้งาน, 0=ปิดใช้งาน',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บข้อมูลโมดูลระบบ ERP ที่บริษัทให้บริการ';

CREATE TABLE `review` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reviewer_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อผู้รีวิว',
    `rating` DECIMAL(3,1) DEFAULT 5.0 COMMENT 'คะแนนรีวิว (เช่น 4.5, 5.0)',
    `content` TEXT COMMENT 'ข้อความรีวิว',
    `reviewer_position` VARCHAR(255) COMMENT 'ตำแหน่งงานของผู้รีวิว',
    `reviewer_company` VARCHAR(255) COMMENT 'บริษัทของผู้รีวิว',
    `reviewer_image_url` VARCHAR(255) COMMENT 'รูปภาพผู้รีวิว',
    `sort_order` INT DEFAULT 0 COMMENT 'ลำดับการแสดงผล',
    `is_active` TINYINT(1) DEFAULT 1 COMMENT 'สถานะ: 1=แสดงผล, 0=ซ่อน',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บข้อมูลรีวิวหรือ Testimonial จากลูกค้า';

CREATE TABLE `service` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL Slug ของบริการ',
    `title` VARCHAR(255) NOT NULL COMMENT 'ชื่อบริการ',
    `summary` TEXT COMMENT 'คำอธิบายแบบย่อ',
    `details_json` JSON COMMENT 'รายละเอียดเชิงลึก (เก็บเป็นโครงสร้าง JSON เพื่อความยืดหยุ่น)',
    `accent` VARCHAR(50) COMMENT 'สี Theme ประจำบริการ (เช่น HEX Code)',
    `image` VARCHAR(255) COMMENT 'รูปภาพประกอบบริการ',
    `is_active` TINYINT(1) DEFAULT 1 COMMENT 'สถานะการเปิดให้บริการ',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft Delete (ถ้ามีค่าคือถูกลบแล้ว)'
) COMMENT = 'ตารางเก็บข้อมูลบริการหลักของบริษัท';

CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `config_key` VARCHAR(255) UNIQUE NOT NULL COMMENT 'คีย์อ้างอิงการตั้งค่า (เช่น site_name)',
    `config_value` TEXT COMMENT 'ค่าของการตั้งค่า',
    `group` VARCHAR(100) COMMENT 'กลุ่มของการตั้งค่า (เช่น general, seo)',
    `category` VARCHAR(100) COMMENT 'หมวดหมู่ย่อย',
    `description` TEXT COMMENT 'คำอธิบายว่าค่านี้ใช้ทำอะไร',
    `is_protected` TINYINT(1) DEFAULT 0 COMMENT 'สถานะป้องกัน: 1=ห้ามลบ/แก้ไขคีย์นี้จากระบบหน้าบ้าน',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางเก็บ Global Settings ของระบบ (Key-Value Config)';

-- =====================================================================
-- 2. สร้างตารางที่มีการเชื่อมโยง Foreign Key (Child Tables)
-- =====================================================================

CREATE TABLE `article` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL ของบทความ',
    `meta_title` VARCHAR(255) COMMENT 'SEO Title',
    `meta_keywords` VARCHAR(255) COMMENT 'SEO Keywords',
    `meta_description` TEXT COMMENT 'SEO Description',
    `category_id` INT COMMENT 'อ้างอิงหมวดหมู่บทความ',
    `cover_image` VARCHAR(255) COMMENT 'รูปภาพหน้าปกบทความ',
    `cover_image_alt` VARCHAR(255) COMMENT 'Alt Text ของรูปหน้าปก (เพื่อ SEO)',
    `content` LONGTEXT COMMENT 'เนื้อหาบทความแบบเต็ม (HTML/Rich Text)',
    `author_id` INT COMMENT 'อ้างอิงผู้เขียน',
    `status` VARCHAR(50) DEFAULT 'draft' COMMENT 'สถานะ: draft (ร่าง), published (เผยแพร่)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้างบทความ',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขล่าสุด',
    `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft Delete: เก็บเวลาที่ลบบทความ',
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`author_id`) REFERENCES `authors`(`id`) ON DELETE SET NULL
) COMMENT = 'ตารางเก็บข้อมูลบทความ / บล็อก';

CREATE TABLE `partners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อพาร์ทเนอร์',
    `image_url` VARCHAR(255) COMMENT 'โลโก้พาร์ทเนอร์',
    `image_alt` VARCHAR(255) COMMENT 'Alt Text โลโก้',
    `category_id` INT COMMENT 'อ้างอิงหมวดหมู่พาร์ทเนอร์',
    `sort_order` INT DEFAULT 0 COMMENT 'ลำดับการแสดง',
    `is_active` TINYINT(1) DEFAULT 1 COMMENT 'สถานะการแสดงผล',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `partner_categories`(`id`) ON DELETE SET NULL
) COMMENT = 'ตารางเก็บรายชื่อพาร์ทเนอร์ / ลูกค้าทางธุรกิจ';

CREATE TABLE `portfolio` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL ผลงาน',
    `client_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อลูกค้าประจำโปรเจกต์',
    `category_id` INT COMMENT 'หมวดหมู่ผลงาน',
    `meta_title` VARCHAR(255) COMMENT 'SEO Title',
    `meta_keywords` VARCHAR(255) COMMENT 'SEO Keywords',
    `meta_description` TEXT COMMENT 'SEO Description',
    `description` TEXT COMMENT 'รายละเอียดของผลงาน/โปรเจกต์',
    `tech_stack` TEXT COMMENT 'เทคโนโลยีที่ใช้ (เช่น React, Node.js)',
    `author_id` INT COMMENT 'ผู้รับผิดชอบหรือผู้โพสต์ผลงาน',
    `status` VARCHAR(50) DEFAULT 'published' COMMENT 'สถานะผลงาน (draft/published)',
    `cover_image` VARCHAR(255) COMMENT 'รูปภาพหน้าปกผลงาน',
    `cover_image_alt` VARCHAR(255) COMMENT 'Alt Text สำหรับภาพปก',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Soft Delete',
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`author_id`) REFERENCES `authors`(`id`) ON DELETE SET NULL
) COMMENT = 'ตารางเก็บข้อมูลผลงาน (Portfolio / Case Studies)';

CREATE TABLE `service_features` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `service_id` INT NOT NULL COMMENT 'อ้างอิงบริการหลัก',
    `title` VARCHAR(255) NOT NULL COMMENT 'ชื่อฟีเจอร์ย่อย',
    `slug` VARCHAR(255) COMMENT 'URL ของฟีเจอร์ย่อย (ถ้ามีหน้าแยก)',
    `summary` TEXT COMMENT 'คำอธิบายฟีเจอร์แบบย่อ',
    `content` TEXT COMMENT 'รายละเอียดฟีเจอร์แบบเต็ม',
    `image` VARCHAR(255) COMMENT 'ภาพประกอบฟีเจอร์',
    `sort_order` INT DEFAULT 0 COMMENT 'ลำดับการแสดงผลฟีเจอร์',
    `is_active` TINYINT(1) DEFAULT 1 COMMENT 'สถานะการแสดงผล',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`service_id`) REFERENCES `service`(`id`) ON DELETE CASCADE
) COMMENT = 'ตารางเก็บฟีเจอร์หรือบริการย่อยที่อิงกับบริการหลัก (ลบบริการหลัก ฟีเจอร์หายตาม)';