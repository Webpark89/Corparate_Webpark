<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากไม่ได้ส่งค่ามาแบบ POST หรือ GET ให้หยุดการทำงาน
if (!isset($_GET['key']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// เลิกตรวจสอบ CSRF ชั่วคราวถ้าเรียกผ่าน GET (หรือให้ข้ามไปเช็คตอนส่ง POST เท่านั้น)
// csrf_verify(); 

// รับค่า key (เป็น String) แทน id
$config_key = trim($_GET['key'] ?? $_POST['key'] ?? '');

if ($config_key !== '') {

    // ป้องกันการลบคีย์ระบบที่สำคัญ (Optional: คุณสามารถเพิ่มคีย์ที่ห้ามลบไว้ที่นี่ได้)
    $protected_keys = ['site_name', 'site_logo', 'theme_primary_color'];
    if (in_array($config_key, $protected_keys)) {
        flash('error', "ไม่อนุญาตให้ลบการตั้งค่าระบบหลัก ('{$config_key}')");
        header('Location: index.php');
        exit;
    }

    // ลบข้อมูลจากตาราง settings
    db()->prepare('DELETE FROM settings WHERE config_key = ?')->execute([$config_key]);

    // เปลี่ยนข้อความแจ้งเตือนเมื่อลบสำเร็จ
    flash('success', "ลบการตั้งค่า '{$config_key}' เรียบร้อยแล้ว");
} else {
    flash('error', 'ไม่พบคีย์ที่ต้องการลบ');
}

header('Location: index.php');
exit;