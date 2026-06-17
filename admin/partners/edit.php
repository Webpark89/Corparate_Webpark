<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากมีการส่งฟอร์ม (POST) ให้ไปประมวลผลที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

$id = (int)($_GET['id'] ?? 0);

// เปลี่ยนมาดึงข้อมูลจากตาราง partners เรียบร้อยแล้ว
$st = db()->prepare('SELECT * FROM partners WHERE id = ?');
$st->execute([$id]);
$partner = $st->fetch();

if (!$partner) {
    http_response_code(404);
    exit('ไม่พบข้อมูลโลโก้พันธมิตรในระบบ (Partner not found.)');
}

// ตั้งค่าตัวแปรสำหรับหน้าเว็บให้สอดคล้องกับระบบพันธมิตร (Partners)
$pageTitle = 'แก้ไขข้อมูลโลโก้พันธมิตร';
$page = 'partners';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;

// ส่งต่อข้อมูลพาร์ทเนอร์ไปยังฟอร์ม (รองรับทั้งชื่อตัวแปร $partner หรือ $review เดิมเพื่อไม่ให้โค้ดพัง)
$review = $partner;

require __DIR__ . '/../includes/header.php';

// เรียกใช้ไฟล์ _form.php สำหรับแสดงฟอร์มแก้ไข
require __DIR__ . '/_form.php';

require __DIR__ . '/../includes/footer.php';
