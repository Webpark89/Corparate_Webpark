<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT * FROM portfolio WHERE id = ?');
$st->execute([$id]);
$portfolio = $st->fetch();

if (!$portfolio) {
    http_response_code(404);
    exit('ไม่พบข้อมูลผลงานในระบบ (Portfolio not found.)');
}

// ตั้งค่าตัวแปรสำหรับหน้าเว็บ
$pageTitle = 'แก้ไขข้อมูลผลงาน';
$page = 'portfolio';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;

require __DIR__ . '/../includes/header.php';

// เรียกใช้ไฟล์ _form.php สำหรับแสดงฟอร์มแก้ไข
require __DIR__ . '/_form.php';

require __DIR__ . '/../includes/footer.php';
