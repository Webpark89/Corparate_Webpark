<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

$id = (int)($_GET['id'] ?? 0);

// เปลี่ยนมาดึงข้อมูลจากตาราง review เรียบร้อยแล้ว
$st = db()->prepare('SELECT * FROM review WHERE id = ?');
$st->execute([$id]);
$review = $st->fetch();

if (!$review) {
    http_response_code(404);
    exit('ไม่พบข้อมูลรีวิวในระบบ (Review not found.)');
}

// ตั้งค่าตัวแปรสำหรับหน้าเว็บ
$pageTitle = 'แก้ไขข้อมูลรีวิว';
$page = 'review';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;

require __DIR__ . '/../includes/header.php';

// เรียกใช้ไฟล์ _form.php สำหรับแสดงฟอร์มแก้ไข
// (อย่าลืมตรวจเช็คในไฟล์ _form.php และ _save.php ให้เปลี่ยนไปใช้ตัวแปร $review และชื่อตาราง review ด้วยนะครับ)
require __DIR__ . '/_form.php';

require __DIR__ . '/../includes/footer.php';
