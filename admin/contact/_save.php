<?php
/**
 * Bulk-save contact settings from the index form.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keys'])) {
    try {
        $statement = db()->prepare('UPDATE settings SET config_value = ?, updated_at = NOW() WHERE config_key = ?');
        foreach ($_POST['keys'] as $index => $key) {
            $statement->execute([$_POST['values'][$index], $key]);
        }
        flash('success', 'บันทึกข้อมูลเรียบร้อย');
    } catch (Exception $exception) {
        flash('error', 'เกิดข้อผิดพลาด: ' . $exception->getMessage());
    }
}
header('Location: index.php');
exit;
