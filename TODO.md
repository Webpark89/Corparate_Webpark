# TODO

## Footer SITEMAP (desktop-left) to match mobile-right

1. ตรวจสอบไฟล์ footer component ที่ควบคุม SITEMAP toggle และ panel
2. แก้ค่าเริ่มต้นของ panel ให้เปิดอยู่ (height = scrollHeight), ตั้ง aria-expanded = true และหมุน arrow = 180deg
3. เปลี่ยนพื้นหลังของ section/panel จาก bg-slate-900/bgdark ให้เป็นสีขาว/อ่อน (light) และปรับสีตัวอักษร SITEMAP/รายการให้มองเห็น
4. ยืนยันว่า text SITEMAP และ chevron แสดงผล (สี/คอนทราสต์) บนพื้นหลังใหม่
5. ทำรายงาน: ระบุไฟล์ + line ก่อน/after (หรือ block) ที่ถูกแก้

## SITEMAP FIX ตอนนี้
- ตรวจสอบพบว่ามี `bg-slate-50` ที่ `#footerSitemapPanel` ทำให้เกิด white box บน dark section
- ต้องปรับตาม Figma: Desktop dark navy + heading/arrow white (expanded), Mobile white + heading/arrow blue (collapsed)

