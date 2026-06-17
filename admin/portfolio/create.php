<?php
$pageTitle = 'สร้างผลงานใหม่'; // เปลี่ยนชื่อหัวข้อหน้าเป็นภาษาไทย
$page = 'portfolio'; // ใช้สำหรับไฮไลท์เมนู Sidebar
require_once __DIR__ . '/../includes/header.php';

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

// สร้าง array ว่างสำหรับตัวแปรเก็บข้อมูล
$portfolio = [];
$action = 'create';
$formAction = 'create.php';

require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
