-- =====================================================================
-- 1. ข้อมูลบริษัท (Company)
-- =====================================================================
INSERT INTO `company` (`name`, `tax_id`, `address`, `phone`, `email`, `logo_path`) 
VALUES (
    'บริษัท เวบปาค จำกัด (Webpark Co., Ltd.)', 
    '0105555082753', 
    '525/89 ซอยลาดพร้าว 126 (กรัณฑ์พร) แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310', 
    '095 539 2666', 
    'info@webpark.co.th', 
    '/uploads/images/webpark-logo.png'
);

-- =====================================================================
-- 2. ข้อมูลผู้ดูแลระบบ (Admins) 
-- (หมายเหตุ: password_hash เป็นตัวอย่างการเข้ารหัสของคำว่า 'password123')
-- =====================================================================
INSERT INTO `admins` (`username`, `email`, `password_hash`, `full_name`, `role`) 
VALUES 
('admin_webpark', 'admin@webpark.co.th', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Webpark Super Admin', 'super_admin');

-- =====================================================================
-- 3. ข้อมูลผู้เขียนบทความ (Authors)
-- =====================================================================
INSERT INTO `authors` (`display_name`, `bio`, `email`, `profile_image`) 
VALUES 
('Webpark Team', 'ทีมงานผู้เชี่ยวชาญด้าน Digital Marketing, SEO และ Web Development', 'content@webpark.co.th', '/uploads/authors/team.jpg');

-- =====================================================================
-- 4. หมวดหมู่หลัก (Categories) สำหรับบทความและผลงาน
-- =====================================================================
INSERT INTO `categories` (`id`, `slug`, `name`, `description`) VALUES 
(1, 'digital-marketing', 'Digital Marketing', 'บทความและความรู้ด้านการตลาดออนไลน์'),
(2, 'web-development', 'Web Development', 'เทคนิคและอัปเดตเกี่ยวกับการสร้างเว็บไซต์'),
(3, 'seo', 'SEO & Data Analytics', 'การทำ SEO และวิเคราะห์ข้อมูล'),
(4, 'portfolio-website', 'Website Portfolio', 'ผลงานรับทำเว็บไซต์');

-- =====================================================================
-- 5. หมวดหมู่พาร์ทเนอร์ (Partner Categories)
-- =====================================================================
INSERT INTO `partner_categories` (`id`, `name`, `sort_order`) VALUES 
(1, 'Clients', 1),
(2, 'Tech Partners', 2);

-- =====================================================================
-- 6. การตั้งค่าระบบ (Settings)
-- =====================================================================
INSERT INTO `settings` (`config_key`, `config_value`, `group`, `category`, `description`, `is_protected`) VALUES 
('site_name', 'Webpark Co.,ltd. Digital Marketing Solutions', 'general', 'global', 'ชื่อเว็บไซต์หลัก', 1),
('meta_description', 'บริษัท เวบปาค จำกัด เชี่ยวชาญการตลาดออนไลน์ รับทำเว็บไซต์ และ ERP System เพิ่มพลังการตลาดดิจิทัล พลิกโฉมธุรกิจของคุณ', 'seo', 'global', 'SEO Description หน้าแรก', 1),
('facebook_url', 'https://www.facebook.com/webpark', 'social', 'links', 'ลิงก์ Facebook Page', 0);

-- =====================================================================
-- 7. ข้อมูลบริการหลัก (Service)
-- =====================================================================
INSERT INTO `service` (`id`, `slug`, `title`, `summary`, `details_json`, `accent`, `image`) VALUES 
(1, 'web-design', 'รับทำและออกแบบเว็บไซต์ (Web Design)', 'บริการพัฒนาเว็บไซต์ Responsive, CMS, และ E-commerce เต็มรูปแบบ', '{"features": ["Responsive", "CMS", "E-commerce", "Mobile App"]}', '#007bff', '/uploads/services/web-design.jpg'),
(2, 'online-marketing', 'การตลาดออนไลน์ (Online Marketing)', 'วางแผนการตลาดออนไลน์ Social Media, SEO และ Online Campaign', '{"features": ["SEO", "Media Planner", "Social Network", "ROI Analysis"]}', '#28a745', '/uploads/services/marketing.jpg'),
(3, 'erp-system', 'ระบบ ERP / ERM (Enterprise Resource Planning)', 'พัฒนาระบบหลังบ้านเพื่อจัดการทรัพยากรองค์กรให้มีประสิทธิภาพ', '{"features": ["Accounting", "HR", "CRM", "Inventory"]}', '#6f42c1', '/uploads/services/erp.jpg');

-- =====================================================================
-- 8. ฟีเจอร์ย่อยของบริการ (Service Features) -> โยงกับ service_id
-- =====================================================================
INSERT INTO `service_features` (`service_id`, `title`, `slug`, `summary`, `content`, `sort_order`) VALUES 
(1, 'Website / Responsive / CMS', 'responsive-cms', 'เว็บไซต์ที่รองรับทุกหน้าจอ', 'ออกแบบและพัฒนาเว็บไซต์ที่แสดงผลได้ดีทั้งบนมือถือและคอมพิวเตอร์ พร้อมระบบจัดการเนื้อหา (CMS) ที่ใช้งานง่าย', 1),
(1, 'E-commerce', 'e-commerce', 'ระบบร้านค้าออนไลน์', 'รับทำเว็บไซต์ E-commerce เต็มรูปแบบพร้อมระบบตะกร้าสินค้าและการชำระเงิน', 2),
(2, 'SEO (Search Engine Optimization)', 'seo-service', 'ทำเว็บไซต์ให้ติดหน้าแรก Google', 'วิเคราะห์คีย์เวิร์ด ปรับปรุงโครงสร้างเว็บ และสร้างเนื้อหาคุณภาพเพื่อดันอันดับบน Google', 1);

-- =====================================================================
-- 9. โมดูล ERP (ERP Modules)
-- =====================================================================
INSERT INTO `erp_modules` (`slug`, `title`, `description`, `icon_svg`, `sort_order`) VALUES 
('erp-accounting', 'Accounting System', 'ระบบบัญชีและการเงิน บันทึกรายรับ-รายจ่าย และออกรายงานภาษี', '<svg>...</svg>', 1),
('erp-inventory', 'Inventory Management', 'ระบบจัดการคลังสินค้า เช็คสต๊อกแบบ Real-time', '<svg>...</svg>', 2);

-- =====================================================================
-- 10. ผลงาน (Portfolio) -> โยงกับ category_id 4
-- =====================================================================
INSERT INTO `portfolio` (`slug`, `client_name`, `category_id`, `meta_title`, `description`, `tech_stack`, `author_id`, `cover_image`) VALUES 
('corporate-website-client-a', 'Client A Co., Ltd.', 4, 'ผลงานรับทำเว็บไซต์องค์กร บริษัท Client A', 'ออกแบบและพัฒนาเว็บไซต์องค์กร (Corporate Website) เพื่อนำเสนอภาพลักษณ์ที่น่าเชื่อถือ', 'HTML, CSS, Vue.js, PHP', 1, '/uploads/portfolio/client-a.jpg'),
('ecommerce-client-b', 'Client B Shop', 4, 'ผลงานรับทำระบบ E-commerce ร้าน Client B', 'พัฒนาระบบร้านค้าออนไลน์พร้อมเชื่อมต่อระบบตัดบัตรเครดิต', 'React, Node.js, MySQL', 1, '/uploads/portfolio/client-b.jpg');

-- =====================================================================
-- 11. บทความ (Article)
-- =====================================================================
INSERT INTO `article` (`slug`, `meta_title`, `category_id`, `cover_image`, `content`, `author_id`, `status`) VALUES 
('why-seo-is-important', 'ทำไม SEO ถึงสำคัญสำหรับธุรกิจในปีนี้', 3, '/uploads/articles/seo-guide.jpg', '<p>การทำ SEO คือกุญแจสำคัญที่จะช่วยให้ธุรกิจของคุณถูกค้นเจอโดยกลุ่มเป้าหมายบน Google...</p>', 1, 'published'),
('what-is-erp', 'ทำความรู้จักระบบ ERP ตัวช่วยจัดการธุรกิจให้ง่ายขึ้น', 2, '/uploads/articles/erp-intro.jpg', '<p>ระบบ ERP (Enterprise Resource Planning) เป็นซอฟต์แวร์ที่ช่วยเชื่อมโยงและจัดการกระบวนการทำงานต่างๆ ขององค์กร...</p>', 1, 'published');

-- =====================================================================
-- 12. พาร์ทเนอร์และลูกค้า (Partners)
-- =====================================================================
INSERT INTO `partners` (`name`, `image_url`, `image_alt`, `category_id`, `sort_order`) VALUES 
('Google Partner', '/uploads/partners/google.png', 'Google Partner Logo', 2, 1),
('Facebook Business', '/uploads/partners/facebook.png', 'Facebook Business Logo', 2, 2);

-- =====================================================================
-- 13. รีวิว (Review)
-- =====================================================================
INSERT INTO `review` (`reviewer_name`, `rating`, `content`, `reviewer_position`, `reviewer_company`) VALUES 
('คุณสมชาย', 5.0, 'ทีมงาน Webpark ให้คำปรึกษาดีมากครับ ช่วยดันยอดขายผ่านช่องทางออนไลน์ได้จริง แนะนำเลยครับ', 'Marketing Manager', 'ABC Company'),
('คุณสมหญิง', 4.5, 'ระบบ ERP ที่พัฒนาให้ใช้งานง่าย ช่วยลดเวลาในการทำเอกสารและบัญชีไปได้เยอะเลยค่ะ', 'CEO', 'SME Thai Co.');