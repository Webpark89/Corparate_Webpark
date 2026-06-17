-- WEBPARK Default Data Seed
-- Database: WEBPARK

SET NAMES utf8mb4;

-- ----------------------------
-- Default data for admins
-- ----------------------------
INSERT INTO admins (username, email, password_hash, display_name, role, is_active) VALUES
('admin', 'admin@webpark.com', '$2y$12$hDzP3bxYxaNrutNUb7qEq.HhRaltcuXAO8KnaZkONMBEq4qPDoY7.', 'Administrator', 'admin', 1),
('editor', 'editor@webpark.com', '$2y$12$hDzP3bxYxaNrutNUb7qEq.HhRaltcuXAO8KnaZkONMBEq4qPDoY7.', 'Editor', 'editor', 1),
('author', 'author@webpark.com', '$2y$12$hDzP3bxYxaNrutNUb7qEq.HhRaltcuXAO8KnaZkONMBEq4qPDoY7.', 'Author', 'author', 1);

-- ----------------------------
-- Default data for categories
-- ----------------------------
INSERT INTO categories (name, slug, description, parent_id, sort_order, is_active) VALUES
('ข่าวสาร', 'news', 'ข่าวสารและกิจกรรมของบริษัท', NULL, 1, 1),
('บทความ', 'articles', 'บทความและความรู้ต่างๆ', NULL, 2, 1),
('กิจกรรม', 'events', 'กิจกรรมและโปรโมชั่น', NULL, 3, 1),
('โปรเจกต์', 'projects', 'โปรเจกต์และผลงาน', NULL, 4, 1),
('เทคโนโลยี', 'technology', 'เรื่องเกี่ยวกับเทคโนโลยี', NULL, 5, 1),
('ธุรกิจ', 'business', 'เรื่องธุรกิจ', NULL, 6, 1);

-- ----------------------------
-- Default data for authors
-- ----------------------------
INSERT INTO authors (display_name, slug, email, bio, is_active) VALUES
('ทีมงาน WEBPARK', 'webpark-team', 'info@webpark.com', 'ทีมงานผู้เชี่ยวชาญด้านการพัฒนาเว็บไซต์และระบบ ERP', 1),
('ฝ่ายการตลาด', 'marketing-team', 'marketing@webpark.com', 'ฝ่ายการตลาดของ WEBPARK', 1),
('ฝ่ายเทคนิค', 'technical-team', 'technical@webpark.com', 'ทีมช่างเทคนิคผู้เชี่ยวชาญ', 1);

-- ----------------------------
-- Default data for company
-- ----------------------------
INSERT INTO company (name_th, name_en, tagline, description, email, phone, fax, is_active) VALUES
('บริษัท เว็บพาร์ค จำกัด', 'WEBPARK Co., Ltd.', 'พัฒนาธุรกิจด้วยเทคโนโลยี', 'บริษัท ห้างหุ้นส่วนจำกัด ผู้ให้บริการพัฒนาเว็บไซต์และระบบ ERP ครบวงจร', 'info@webpark.com', '02-123-4567', '02-123-4568', 1);

-- ----------------------------
-- Default data for article
-- ----------------------------
INSERT INTO article (slug, title, summary, content, category_id, author_id, meta_title, meta_description, meta_keywords, status, featured, published_at) VALUES
('welcome-webpark', 'ยินดีต้อนรับสู่ WEBPARK', 'ยินดีต้อนรับสู่ WEBPARK - ผู้นำด้านการพัฒนาเว็บไซต์และระบบ ERP', '<p>ยินดีต้อนรับสู่ WEBPARK</p><p>เราคือทีมผู้เชี่ยวชาญด้านการพัฒนาเว็บไซต์และระบบ ERP ครบวงจร</p>', 1, 1, 'WEBPARK - ผู้นำด้านการพัฒนาเว็บไซต์และระบบ ERP', 'WEBPARK ให้บริการพัฒนาเว็บไซต์และระบบ ERP ครบวงจร', 'เว็บไซต์, ERP, พัฒนาเว็บ', 'published', 1, NOW());

-- ----------------------------
-- Default data for portfolio
-- ----------------------------
INSERT INTO portfolio (slug, title, subtitle, description, client_name, project_date, status, featured, sort_order) VALUES
('sample-project', 'ตัวอย่างโปรเจกต์', 'ตัวอย่างผลงาน', 'ตัวอย่างผลงานการพัฒนาเว็บไซต์', 'ลูกค้าตัวอย่าง', '2026-01-15', 'published', 1, 1);

-- ----------------------------
-- Default data for partner_categories
-- ----------------------------
INSERT INTO partner_categories (name, slug, description, icon, sort_order, is_active) VALUES
('Technology', 'technology', 'พันธมิตรด้านเทคโนโลยี', 'fa-microchip', 1, 1),
('Consulting', 'consulting', 'พันธมิตรที่ปรึกษา', 'fa-handshake', 2, 1),
('Marketing', 'marketing', 'พันธมิตรด้านการตลาด', 'fa-bullhorn', 3, 1);

-- ----------------------------
-- Default data for partners
-- ----------------------------
INSERT INTO partners (slug, name, description, website, category_id, is_active, sort_order) VALUES
('partner-1', 'พันธมิตร 1', 'ตัวอย่างพันธมิตร 1', 'https://partner1.com', 1, 1, 1),
('partner-2', 'พันธมิตร 2', 'ตัวอย่างพันธมิตร 2', 'https://partner2.com', 2, 1, 2),
('partner-3', 'พันธมิตร 3', 'ตัวอย่างพันธมิตร 3', 'https://partner3.com', 3, 1, 3);

-- ----------------------------
-- Default data for service
-- ----------------------------
INSERT INTO service (slug, title, subtitle, summary, description, icon, status, featured, sort_order, published_at) VALUES
('web-development', 'พัฒนาเว็บไซต์', 'Website Development', 'บริการพัฒนาเว็บไซต์ครบวงจร', '<p>บริการพัฒนาเว็บไซต์ครบวงจรตามมาตรฐานสากล</p>', 'fa-globe', 'published', 1, 1, NOW()),
('erp-system', 'ระบบ ERP', 'ERP System', 'บริการพัฒนาระบบ ERP ให้ธุรกิจ', '<p>บริการพัฒนาระบบ ERP สำหรับธุรกิจของคุณ</p>', 'fa-cogs', 'published', 2, 2, NOW()),
('digital-marketing', 'การตลาดดิจิทัล', 'Digital Marketing', 'บริการด้านการตลาดออนไลน์', '<p>บริการด้านการตลาดออนไลน์เพื่อเพิ่มโอกาสทางธุรกิจ</p>', 'fa-bullhorn', 'published', 3, 3, NOW());

-- ----------------------------
-- Default data for service_features
-- ----------------------------
INSERT INTO service_features (service_id, title, description, icon, sort_order) VALUES
(1, 'Responsive Design', 'ออกแบบให้รองรับทุกอุปกรณ์', 'fa-mobile-alt', 1),
(1, 'SEO Optimized', 'ปรับแต่งเพื่อเครื่องมือค้นหา', 'fa-search', 2),
(1, 'Fast Loading', 'โหลดเร็วและมีประสิทธิภาพ', 'fa-bolt', 3),
(2, 'Inventory Management', 'บริหารจัดการสินค้าคงคลัง', 'fa-boxes', 1),
(2, 'Financial Accounting', 'บัญชีและการเงิน', 'fa-calculator', 2),
(2, 'Human Resources', 'บริหารงานบุคคล', 'fa-users', 3),
(3, 'Social Media Marketing', 'การตลาดผ่านโซเชียลมีเดีย', 'fa-share-alt', 1),
(3, 'Content Strategy', 'วางแผนเนื้อหา', 'fa-pen-fancy', 2),
(3, 'Analytics', 'วิเคราะห์ข้อมูล', 'fa-chart-line', 3);

-- ----------------------------
-- Default data for erp_modules
-- ----------------------------
INSERT INTO erp_modules (slug, name, name_en, description, summary, status, featured, sort_order, published_at) VALUES
('accounting', 'บัญชีและการเงิน', 'Accounting & Finance', 'ระบบบัญชีและการเงินครบวงจร', 'ระบบบัญชีและการเงินครบวงจรสำหรับธุรกิจ', 'published', 1, 1, NOW()),
('inventory', 'บริหารสินค้าคงคลัง', 'Inventory Management', 'ระบบบริหารสินค้าคงคลัง', 'ระบบบริหารสินค้าคงคลังอย่างมีประสิทธิภาพ', 'published', 2, 2, NOW()),
('hr', 'บริหารงานบุคคล', 'Human Resources', 'ระบบบริหารงานบุคคล', 'ระบบบริหารงานบุคคลครบวงจร', 'published', 3, 3, NOW()),
('sales', 'การขาย', 'Sales Management', 'ระบบบริหารการขาย', 'ระบบบริหารการขายและลูกค้า', 'published', 4, 4, NOW()),
('purchasing', 'การซื้อ', 'Purchasing Management', 'ระบบบริหารการซื้อ', 'ระบบบริหารการซื้อสินค้า', 'published', 5, 5, NOW()),
('production', 'การผลิต', 'Production Management', 'ระบบบริหารการผลิต', 'ระบบบริหารการผลิต', 'published', 6, 6, NOW());

-- ----------------------------
-- Default data for erp_module_features
-- ----------------------------
INSERT INTO erp_module_features (module_id, title, title_en, description, icon, sort_order) VALUES
(1, 'บันทึกบัญชี', 'General Ledger', 'บันทึกบัญชีทั่วไป', 'fa-book', 1),
(1, 'บัญชีเจ้าหนี้', 'Accounts Payable', 'บัญชีเจ้าหนี้', 'fa-money-bill-wave', 2),
(1, 'บัญชีลูกหนี้', 'Accounts Receivable', 'บัญชีลูกหนี้', 'fa-money-bill', 3),
(2, 'คลังสินค้า', 'Warehouse', 'จัดการคลังสินค้า', 'fa-warehouse', 1),
(2, 'สินค้าคงคลัง', 'Stock Management', 'บริหารสินค้าคงคลัง', 'fa-boxes', 2),
(2, 'โอนย้าย', 'Transfer', 'โอนย้ายสินค้าระหว่างคลัง', 'fa-exchange-alt', 3),
(3, 'ข้อมูลพนักงาน', 'Employee Data', 'ฐานข้อมูลพนักงาน', 'fa-id-card', 1),
(3, 'การลา', 'Leave Management', 'ระบบจัดการการลา', 'fa-calendar-minus', 2),
(3, 'เงินเดือน', 'Payroll', 'ระบบเงินเดือน', 'fa-dollar-sign', 3),
(4, 'ใบเสนอราคา', 'Quotation', 'ใบเสนอราคา', 'fa-file-invoice', 1),
(4, 'ใบสั่งซื้อ', 'Purchase Order', 'ใบสั่งซื้อ', 'fa-shopping-cart', 2),
(4, 'ใบแจ้งหนี้', 'Invoice', 'ใบแจ้งหนี้', 'fa-file-invoice-dollar', 3),
(5, 'ใบขอซื้อ', 'Purchase Request', 'ใบขอซื้อ', 'fa-file-alt', 1),
(5, 'เปรียบเทียบราคา', 'Price Comparison', 'เปรียบเทียบราคาผู้ขาย', 'fa-balance-scale', 2),
(6, 'ใบสั่งผลิต', 'Production Order', 'ใบสั่งผลิต', 'fa-industry', 1),
(6, 'วางแผนการผลิต', 'Production Planning', 'วางแผนการผลิต', 'fa-clipboard-list', 2);

-- ----------------------------
-- Default data for review
-- ----------------------------
INSERT INTO review (customer_name, customer_company, customer_position, rating, title, content, is_featured, status, published_at) VALUES
('สมชาย ใจดี', 'บริษัท ตัวอย่าง จำกัด', 'ผู้จัดการ', 5, 'บริการที่ยอดเยี่ยม', 'ทีมงานมีความเชี่ยวชาญมาก บริการดีเยี่ยมครับ', 1, 'approved', NOW()),
('มินะ ใจงาม', 'ห้างหุ้นส่วน ตัวอย่าง', 'เจ้าของ', 4, 'พอใจมาก', 'บริการดี ส่งงานตรงเวลา', 0, 'approved', NOW()),
('วิชัย มีความสามารถ', 'บริษัท สมรรถนะ จำกัด', 'ผู้บริหาร', 5, 'แนะนำเลย', 'ทีมงานเยี่ยมมากครับ ทำงานสำเร็จลุล่วงด้วยดี', 1, 'approved', NOW());

-- ----------------------------
-- Default data for settings
-- ----------------------------
INSERT INTO settings (config_key, config_value, config_group, description, is_protected) VALUES
('site_name', 'WEBPARK', 'general', 'ชื่อเว็บไซต์', 1),
('site_description', 'บริษัท ห้างหุ้นส่วนจำกัด ให้บริการพัฒนาเว็บไซต์และระบบ ERP', 'general', 'คำอธิบายเว็บไซต์', 1),
('site_keywords', 'เว็บไซต์, ERP, พัฒนาเว็บ, ระบบ ERP', 'general', 'คำค้นหาหลัก', 0),
('site_email', 'info@webpark.com', 'contact', 'อีเมลติดต่อ', 1),
('site_phone', '02-123-4567', 'contact', 'โทรศัพท์ติดต่อ', 1),
('site_fax', '02-123-4568', 'contact', 'แฟกซ์', 1),
('site_address', 'กรุงเทพมหานคร ประเทศไทย', 'contact', 'ที่อยู่', 0),
('facebook_url', 'https://facebook.com/webpark', 'social', 'Facebook', 0),
('line_url', 'https://line.me/ti/p/@webpark', 'social', 'LINE', 0),
('youtube_url', 'https://youtube.com/webpark', 'social', 'YouTube', 0),
('instagram_url', 'https://instagram.com/webpark', 'social', 'Instagram', 0),
('linkedin_url', 'https://linkedin.com/company/webpark', 'social', 'LinkedIn', 0),
('meta_title', 'WEBPARK - ผู้นำด้านการพัฒนาเว็บไซต์และระบบ ERP', 'seo', 'SEO Title', 1),
('meta_description', 'WEBPARK ให้บริการพัฒนาเว็บไซต์และระบบ ERP ครบวงจร', 'seo', 'SEO Description', 1),
('logo', 'logo.png', 'design', 'โลโก้', 1),
('favicon', 'favicon.ico', 'design', 'ไลโก้', 1),
('items_per_page', '20', 'pagination', 'จำนวนรายการต่อหน้า', 0),
('date_format', 'd/m/Y', 'display', 'รูปแบบวันที่', 0),
('time_format', 'H:i', 'display', 'รูปแบบเวลา', 0),
('timezone', 'Asia/Bangkok', 'system', 'โซนเวลา', 1),
('language', 'th', 'system', 'ภาษา', 1),
('currency', 'THB', 'system', 'สกุลเงิน', 1),
('maintenance_mode', '0', 'system', 'โหมดปิดปรับปรุง', 1);