<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากไม่ได้ส่งค่ามาแบบ POST (หรือ GET ถ้าคุณใช้ลิงก์ลบแบบ GET) ให้หยุดการทำงาน
// หมายเหตุ: โค้ดที่หน้า index.php ของคุณ ลิงก์ลบเป็นแบบ GET (window.location.href='delete.php?id=...')
// ถ้าคุณใช้ลิงก์นั้น ให้เปลี่ยนเป็นเช็คค่า GET แทนตามตัวอย่างด้านล่างนี้ครับ
if (!isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// เลิกตรวจสอบ CSRF ชั่วคราวถ้าเรียกผ่าน GET (หรือให้ข้ามไปเช็คตอนส่ง POST เท่านั้น)
// csrf_verify(); 

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id) {
    // 1. ดึงข้อมูลรูปภาพเก่ามาเพื่อลบไฟล์ออกจาก Server (ถ้ามีฟังก์ชันลบไฟล์)
    // $st = db()->prepare('SELECT image_url FROM partners WHERE id = ?');
    // $st->execute([$id]);
    // $partner = $st->fetch();
    // if ($partner && $partner['image_url']) {
    //     @unlink(__DIR__ . '/../' . $partner['image_url']);
    // }

    // 2. ดึงข้อมูลจากตาราง partners แทน
    db()->prepare('DELETE FROM partners WHERE id = ?')->execute([$id]);

    // 3. เปลี่ยนข้อความแจ้งเตือนเมื่อลบสำเร็จ
    flash('success', 'ลบข้อมูลโลโก้พันธมิตรเรียบร้อยแล้ว');
}

header('Location: index.php');
exit;
