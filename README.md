# WEBPARK (PHP & Tailwind CSS)

โปรเจกต์เว็บแอปพลิเคชัน พัฒนาด้วย PHP และจัดสไตล์ด้วย Tailwind CSS

## โครงสร้างโฟลเดอร์เบื้องต้น
- `admin/` : ระบบจัดการหลังบ้าน
- `frontend/` : ส่วนหน้าเว็บหลัก
- `index.php` : หน้าแรกของเว็บไซต์

## สิ่งที่ต้องเตรียมก่อนเริ่มใช้งาน (Prerequisites)
- [Node.js](https://nodejs.org/) (แนะนำเวอร์ชัน LTS สำหรับคอมไพล์ Tailwind CSS)
- เว็บเซิร์ฟเวอร์สำหรับรัน PHP (เช่น XAMPP, Laragon หรือ PHP CLI)

## การเข้าใช้งานระบบ (Access Paths)
เมื่อรันเว็บเซิร์ฟเวอร์ (เช่น เปิด Apache ใน XAMPP/MAMP) เรียบร้อยแล้ว สามารถเข้าดูเว็บไซต์ผ่านเบราว์เซอร์ได้ที่ Path ดังนี้:
- หน้าเว็บหลัก (Frontend): http://localhost:8888/WEBPARK/
- ระบบจัดการหลังบ้าน (Admin): http://localhost:8888/WEBPARK/admin/login.php (admin/admin1234)

## วิธีการติดตั้งและเริ่มต้นใช้งาน (Installation & Usage)

### 1. ติดตั้ง Dependencies
เมื่อทำการ Clone โปรเจกต์นี้มาแล้ว ให้เปิด Terminal ในโฟลเดอร์หลัก (Root) แล้วรันคำสั่งเพื่อดาวน์โหลด `node_modules`:
```bash
npm install

### เข้าไปในโฟลเดอร์ admin และ ทำการรัน npm install
### เข้าไปในโฟลเดอร์ frontend และทำการรัน npm install
