# WEBPARK (PHP MVC + TailwindCSS)

โปรเจกต์เว็บแอป “WEBPARK” พัฒนาแบบ Lightweight MVC ด้วย **PHP** และจัดสไตล์ด้วย **Tailwind CSS** โดยแบ่งเป็น 2 ส่วนหลัก

- **Frontend**: เว็บไซต์สาธารณะ (หน้าแรก / บริการ / ERP / บทความ / โปรเจกต์ / ติดต่อ)
- **Admin**: ระบบหลังบ้านสำหรับจัดการคอนเทนต์ทั้งหมด (Articles/Portfolio/Services/Settings/Reviews/ERP modules ฯลฯ)

> เป้าหมายของ README ฉบับนี้: ให้คนในทีม “อ่านแล้วเริ่มทำงานต่อได้ทันที” ทั้งการตั้งค่าเครื่อง รันโปรเจกต์ จัดการฐานข้อมูล และดูว่าควรไปแก้โค้ดส่วนไหน

---

## 1) ภาพรวมระบบ (High-level)

### Frontend (Public Site)
โฟลเดอร์หลัก: `frontend/`

- Entry point: `index.php` (ที่ root) เรียกต่อไปที่ `frontend/public/index.php`
- Router: `frontend/routes.php`
- Controller: `frontend/app/controllers/HomeController.php`
- Models: `frontend/app/Models/*.php`
- Views: `frontend/app/views/**` และ layout: `frontend/app/views/layouts/main.php`

Frontend ใช้ Helpers ในการสร้าง URL/asset และ rendering:
- `route_url()` → ลิงก์ของหน้าเว็บผ่าน query param `?url=...`
- `asset_url()` → ลิงก์ไฟล์ CSS/JS/รูป
- `e()` → escape ข้อมูลเพื่อกัน XSS

### Admin (Back Office)
โฟลเดอร์หลัก: `admin/`

- หน้าล็อกอิน: `admin/login.php` user:admin pass:admin123
- Portal entry: `admin/index.php` (เรียกไปยัง dashboard เมื่อใช้ route `dashboard`)
- Helper/ความปลอดภัย: `admin/includes/functions.php` (CSRF/session/rate limit/upload ฯลฯ)

Admin เชื่อมต่อฐานข้อมูลเดียวกับ Frontend

---

## 2) โครงสร้างโฟลเดอร์ (Folder map)

```text
.
├─ index.php                              # root entry -> frontend/public/index.php
├─ README.md
├─ TODO.md
├─ frontend/
│  ├─ public/
│  │  ├─ index.php                       # frontend entry
│  │  └─ assets/
│  │     ├─ css/
│  │     ├─ js/
│  │     └─ images/
│  ├─ routes.php                          # routing table
│  ├─ config.php
│  └─ app/
│     ├─ controllers/
│     ├─ core/
│     │  ├─ Router.php
│     │  └─ helpers.php
│     ├─ Models/
│     └─ views/
│        ├─ layouts/main.php
│        └─ pages/** + components/**
│
└─ admin/
   ├─ index.php
   ├─ login.php
   ├─ logout.php
   ├─ config/
   │  ├─ config.php
   │  └─ database.php
   ├─ includes/
   │  └─ functions.php
   ├─ database/
   │  ├─ schema.sql
   │  └─ seed.sql
   ├─ assets/
   │  ├─ css/src + css/dist
   │  ├─ js/
   │  └─ images/
   └─ (หน้าจัดการ CRUD อยู่ในโฟลเดอร์ย่อย เช่น article/, service/, portfolio/ ...)
```

---

## 3) Prerequisites (สิ่งที่ต้องมี)

- PHP (เวอร์ชันที่รองรับ PHP 8+ แนะนำ)
- MySQL/MariaDB
- เว็บเซิร์ฟเวอร์ (เช่น Apache ผ่าน MAMP/XAMPP)
- Node.js + npm (สำหรับ build Tailwind)

---

## 4) ตั้งค่า Local Environment

### 4.1 ตั้งค่า MySQL

สร้างฐานข้อมูลชื่อ **WEBPARK** (ตาม `admin/config/config.php`)

> ถ้าจะใช้ชื่ออื่น ต้องแก้ DB_NAME ใน `admin/config/config.php`

### 4.2 Import Database

นำเข้าฐานข้อมูลจากไฟล์ใน `admin/database/`:

1. `admin/database/schema.sql`
2. `admin/database/seed.sql` (ถ้าต้องการข้อมูลตัวอย่าง)

จากนั้นตรวจว่า table ถูกสร้างเรียบร้อย

### 4.3 ตรวจค่า DB credentials

ตรวจค่าที่เกี่ยวกับฐานข้อมูลได้จาก:
- `admin/config/config.php`
  - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`
- Frontend จะอ่าน config ของฝั่งตนผ่าน `frontend/app/core/Database.php` และ helpers

> **หมายเหตุสำคัญ**: Frontend `helpers.php` เรียก `Database.php` ของ `frontend/app/core/Database.php` ดังนั้น config DB ต้อง match กับฐานข้อมูลจริงที่คุณ import

---

## 5) วิธีรันโปรเจกต์

### 5.1 รัน Frontend

1. เปิด Apache ที่ชี้ไปยังโฟลเดอร์โปรเจกต์
2. เปิด URL ตาม paths ของ environment

ในโปรเจกต์มี `base_url` ตั้งไว้ที่:
- `frontend/config.php` → `app.base_url = '/corperate_webpark'`

ดังนั้นตัวอย่าง URL ที่มักใช้คือ:
- Frontend: `http://localhost:8888/corperate_webpark/`
- หน้าอื่นใช้ route ผ่าน Router เช่น `?url=` ภายใน `route_url()`

### 5.2 รัน Admin

1. เปิด URL:
   - `http://localhost:8888/corperate_webpark/admin/login.php`

2. Credentials (ตามค่าใน config ตัวอย่าง):
- `ADMIN_USERNAME = admin`
- `ADMIN_PASSWORD_HASH` ถูกตั้งเป็น hash ตัวอย่าง

> ถ้าล็อกอินไม่ได้ ให้ตรวจค่า hash ใน `admin/config/config.php` หรือเปลี่ยนรหัสผ่านในระบบตามแนวทางในทีม

---

## 6) Routing & URL Conventions (วิธีสร้างลิงก์)

Frontend route mapping อยู่ที่:
- `frontend/routes.php`

สำคัญ:
- URL ส่วนใหญ่ถูกสร้างด้วย `route_url('/path')`
- `route_url()` จะสร้างลิงก์ในรูปแบบ:
  - `${baseUrl}/?url=${encodedPath}`

ตัวอย่าง:
- `route_url('/services')` → ลิงก์ไปหน้า services
- บางหน้าใช้ params เช่น
  - `route_url('/portfolio', ['id' => 123])`

---

## 7) Database Guide (ตารางที่เกี่ยวข้องกับเว็บ)

ไฟล์ schema อยู่ที่: `admin/database/schema.sql`

### ตาราง Frontend ที่ใช้จริง
- `article`
  - `status`: `draft` / `published` (Frontend จะกรอง published)
  - ใช้ในหน้า `HomeController@article` และ `views/pages/article.php`

- `portfolio`
  - `status`: `draft` / `published`
  - ใช้ในหน้า homepage (ดึง portfolio ล่าสุด) และหน้า portfolio detail/list

- `service`
  - `is_active`: เปิด/ปิดการแสดง
  - `slug`: ใช้ระบุ service

- `service_features`
  - ฟีเจอร์ย่อยของ service (ใช้ในหน้า service detail)

- `review`
  - `is_active`: กรองเฉพาะรีวิวที่แสดงผล

- `settings`
  - ใช้สำหรับข้อมูล Contact/Company และค่า global อื่น ๆ

- `erp_modules`
  - รายการโมดูล ERP ที่แสดงในหน้า ERP และ Sitemap/ Footer

---

## 8) Frontend ทำงานอย่างไร (จาก DB ไปหน้าเว็บ)

ตัวอย่าง flow ที่พบบ่อย:

### Article page
- Controller: `frontend/app/controllers/HomeController.php` → `article()`
- Model: `frontend/app/Models/Article.php`
  - `getAll()` / `getPublished()`
- View: `frontend/app/views/pages/article.php`
  - แสดงแท็บตาม category และรองรับ load more ด้วยข้อมูล JSON ที่ฝังในหน้า

### Service & Service Detail
- Controller:
  - `services()` → list
  - `serviceDetail()` → ยิงไป `serviceFeature()` เพื่อเลือก feature ตาม `service` + `topic`
- Model: `frontend/app/Models/Service.php`
  - `getAllActive()`
  - `getBySlug()`
  - `getFeaturesByServiceId()`
  - `getFeatureBySlugs()`

### ERP
- Controller: `erp()`
- View: `frontend/app/views/pages/erp.php`
- โดย footer/navbar จะดึง module จาก DB เพื่อสร้าง sitemap/items เพิ่มเติม

---

## 9) Admin: จัดการข้อมูลอะไรบ้าง

โฟลเดอร์ CRUD หลักจะอยู่ใน `admin/` ตามหมวด เช่น (ตัวอย่างโครงสร้างที่มีอยู่แล้วใน repo):

- `admin/article/` (index/create/edit/delete + _form/_save)
- `admin/portfolio/`
- `admin/service/`
- `admin/review/`
- `admin/contact/`
- `admin/master/` (โดยทั่วไปใช้จัดการข้อมูลตั้งต้น/lookup)
- `admin/partners/`

> โค้ดในแต่ละโฟลเดอร์ทำงานแบบ POST → validate/csrf → upload (ถ้ามี) → save ลง DB

### Upload รูป (แนวทางทั่วไป)
- โค้ด upload อยู่ที่: `admin/includes/functions.php`
  - `handle_upload()`
  - เก็บไฟล์ลง `uploads/` (ตาม `UPLOAD_DIR`)
- ตารางจะเก็บชื่อไฟล์ในฟิลด์ เช่น
  - `service.image`, `article.cover_image`, `portfolio.cover_image`, `review.reviewer_image_url`

---

## 10) Build & Asset Pipeline (Tailwind & CSS)

### 10.1 Frontend Tailwind

ใน `frontend/package.json` มี scripts สำหรับ build/css:
- `npm run build:css`
- `npm run watch:css`

โดยจะรวมไฟล์ partial:
- `frontend/public/assets/css/partials/base.css`
- `frontend/public/assets/css/partials/detail.css`
- `frontend/public/assets/css/partials/home.css`

ไปยัง:
- `frontend/public/assets/css/app.css`

แล้ว build เป็น:
- `frontend/public/assets/css/tailwind.css`

> ถ้า CSS ไม่อัปเดต ให้รัน build:css ใหม่

### 10.2 Admin Tailwind

ใน `admin/package.json` มี:
- `npm run tailwind:build`
- `npm run tailwind:watch`

เอาต์พุต:
- `admin/assets/css/dist/tailwind.css`

---

## 11) จุดที่ควรระวัง / Troubleshooting

### 11.1 base_url / asset URL ไม่ตรง
อาการ:
- ลิงก์พาไปหน้าไม่ถูก
- รูปไม่ขึ้น / CSS โหลดไม่เจอ

สาเหตุที่พบบ่อย:
- เปลี่ยน path ของโปรเจกต์ แต่ไม่อัปเดต `frontend/config.php` / `admin/config/config.php`

ตรวจค่า:
- `frontend/config.php` → `app.base_url`
- `admin/config/config.php` → `SITE_URL`

### 11.2 DB เชื่อมไม่ได้
- ตรวจ DB_HOST/DB_PORT/DB_NAME/DB_USER/DB_PASS
- ตรวจว่า import schema ถูกต้องแล้ว

### 11.3 Tailwind ไม่อัปเดต
- ลืมรัน `npm run build:css` (frontend) หรือ `npm run tailwind:build` (admin)

### 11.4 Admin ล็อกอินไม่ผ่าน
- ตรวจ hash ใน `admin/config/config.php`
- ตรวจ CSRF/session timeout (มี rate limit และ session timeout)

---

## 12) แนวทาง Contributing (ทำงานต่อแบบไม่พัง)

1. เพิ่ม/แก้ “ข้อมูลที่แสดงผล” ให้แก้ผ่าน DB/Controller/Model ก่อน
   - View เป็นชั้นนำข้อมูล (อย่าเขียน logic หนักๆ เพิ่ม)
2. ถ้าจะเพิ่มหน้าใหม่
   - เพิ่ม route ใน `frontend/routes.php`
   - เพิ่ม method ใน `HomeController`
   - สร้าง view file ใน `frontend/app/views/pages/`
3. หลีกเลี่ยงการใช้ path แบบ hardcode (เช่น `/frontend/public/...`)
   - ให้ใช้ `asset_url()` และ `route_url()` ตามระบบเดิม
4. เวลาแก้ layout ให้ดูว่ามี component ไหน require อยู่บ้าง
   - `frontend/app/views/layouts/main.php` require `navbar.php`, `cta.php`, `footer.php`

---

## 13) Quick Start (ทำตามนี้ให้จบในครั้งเดียว)

1. เปิด Terminal
2. ติดตั้ง dependencies (ถ้าต้อง build CSS):

```bash
# Frontend
cd frontend
npm install
npm run build:css

# Admin
cd ../admin
npm install
npm run tailwind:build
```

3. Import DB:
- เปิด MySQL แล้วรัน `admin/database/schema.sql`
- (แนะนำ) รัน `admin/database/seed.sql`

4. รัน Apache แล้วเปิด:
- Frontend: `http://localhost:8888/corperate_webpark/`
- Admin: `http://localhost:8888/corperate_webpark/admin/login.php`

---

## เอกสาร/งานค้าง
งานค้างดูได้ที่ `TODO.md`

ไฟล์สำคัญสำหรับแก้ไขในอนาคต:
- `frontend/app/controllers/HomeController.php`
- `frontend/app/views/**`
- `admin/{article,portfolio,service,review,contact,...}/**`
- `admin/database/schema.sql`
- `admin/database/seed.sql`



### ติดตั้ง Dependencies
เมื่อทำการ Clone โปรเจกต์นี้มาแล้ว ให้เปิด Terminal ในโฟลเดอร์หลัก (Root) แล้วรันคำสั่งเพื่อดาวน์โหลด `node_modules`:
```bash
npm install

### เข้าไปในโฟลเดอร์ admin และ ทำการรัน npm install
### เข้าไปในโฟลเดอร์ frontend และทำการรัน npm install
##
