<?php
/**
 * Delete a contact setting by config_key via GET or POST.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if (!isset($_GET['key']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}
$configKey = trim($_GET['key'] ?? $_POST['key'] ?? '');
if ($configKey !== '') {
    $protectedKeys = ['site_name', 'site_logo', 'theme_primary_color'];
    if (in_array($configKey, $protectedKeys, true)) {
        flash('error', "ไม่อนุญาตให้ลบการตั้งค่าระบบหลัก ('{$configKey}')");
        header('Location: index.php');
        exit;
    }
    db()->prepare('DELETE FROM settings WHERE config_key = ?')->execute([$configKey]);
    flash('success', "ลบการตั้งค่า '{$configKey}' เรียบร้อยแล้ว");
} else {
    flash('error', 'ไม่พบคีย์ที่ต้องการลบ');
}
header('Location: index.php');
exit;
