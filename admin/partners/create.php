<?php
$pageTitle = 'เพิ่มโลโก้พันธมิตรใหม่'; // เปลี่ยนชื่อหัวข้อหน้าให้ตรงกับระบบ Partners
$page = 'partners'; // เปลี่ยนเพื่อให้เมนู Sidebar/Header ไฮไลท์ถูกต้อง
require_once __DIR__ . '/../includes/header.php';

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

// สร้าง array ว่างสำหรับตัวแปรเก็บข้อมูล
$partner = [];
$review = $partner; // ทิ้งตัวแปร $review ไว้เผื่อในไฟล์ _form.php ยังเรียกใช้อยู่ โค้ดจะได้ไม่พังครับ
$action = 'create';
$formAction = 'create.php';

require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
