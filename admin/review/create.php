<?php
$pageTitle = 'เพิ่มรีวิวใหม่'; // เปลี่ยนชื่อหัวข้อหน้า
$page = 'review'; // เปลี่ยนเพื่อให้เมนู Header ทำงานถูกต้อง
require_once __DIR__ . '/../includes/header.php';

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

// สร้าง array ว่างสำหรับตัวแปร $review แทน $article
$review = [];
$action = 'create';
$formAction = 'create.php';

require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
