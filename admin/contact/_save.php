<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keys'])) {
    try {
        $stmt = db()->prepare("UPDATE settings SET config_value = ?, updated_at = NOW() WHERE config_key = ?");
        foreach ($_POST['keys'] as $index => $key) {
            $stmt->execute([$_POST['values'][$index], $key]);
        }
        flash('success', 'บันทึกข้อมูลเรียบร้อย');
    } catch (Exception $e) {
        flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
    }
}
header('Location: index.php');
