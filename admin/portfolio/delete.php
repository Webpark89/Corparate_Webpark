<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// ตรวจสอบว่าต้องส่งข้อมูลมาแบบ POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

csrf_verify();

$id = (int)($_POST['id'] ?? 0);

if ($id) {
    // ลบข้อมูลจากตาราง portfolio
    db()->prepare('DELETE FROM portfolio WHERE id = ?')->execute([$id]);

    // เปลี่ยนข้อความแจ้งเตือนเป็นภาษาไทย
    flash('success', 'ลบข้อมูลผลงานเรียบร้อยแล้ว');
}

header('Location: index.php');
exit; // แนะนำให้ใส่ exit; ต่อท้าย header เสมอเพื่อป้องกันไม่ให้โค้ดทำงานต่อ