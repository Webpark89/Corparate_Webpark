<?php
// 1. เรียกใช้งานฟังก์ชันและตรวจสอบสิทธิ์ก่อนเป็นอันดับแรก
require_once __DIR__ . '/../includes/functions.php';
require_login();

// 2. รับค่า ID อย่างปลอดภัยและบังคับให้เป็น Integer (แก้ปัญหา Expected type int)
$id = (int)($_GET['id'] ?? 0);

// 3. ดึงข้อมูลจาก Database ครั้งเดียว
$st = db()->prepare('SELECT * FROM service WHERE id = ?');
$st->execute([$id]);
$setting = $st->fetch();

// 4. ตรวจสอบว่ามีข้อมูลจริงไหม
if (!$setting) {
    flash('error', 'ไม่พบข้อมูลบริการ');
    header('Location: index.php');
    exit;
}

// 5. หากมีการ POST ข้อมูลมา ให้ประมวลผลการบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
    exit;
}

// 6. กำหนดตัวแปรสำหรับหน้าเว็บ
$pageTitle = 'Edit Service';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;

// 7. แสดงผลหน้าเว็บ
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
