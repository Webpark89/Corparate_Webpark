<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

csrf_verify();

$id = (int)($_POST['id'] ?? 0);

if ($id) {
    // ดึงข้อมูลจากตาราง review แทน article
    db()->prepare('DELETE FROM review WHERE id = ?')->execute([$id]);

    // เปลี่ยนข้อความแจ้งเตือนเมื่อลบสำเร็จ
    flash('success', 'ลบข้อมูลรีวิวเรียบร้อยแล้ว');
}

header('Location: index.php');
exit; // แนะนำให้ใส่ exit; ต่อท้าย header เสมอเพื่อป้องกันไม่ให้โค้ดส่วนอื่นทำงานต่อ