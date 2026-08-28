<?php
/**
 * Delete Admin User (Super Admin only).
 */
require_once __DIR__ . '/../includes/functions.php';
require_super_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$currentAdminId = (int)($_SESSION['admin_id'] ?? 0);

if ($id <= 0) {
    flash('error', 'ไม่พบข้อมูลผู้ดูแลระบบที่ต้องการลบ');
    header('Location: index.php');
    exit;
}

// Guard 1: Prevent deleting own logged-in account
if ($id === $currentAdminId) {
    flash('error', 'คุณไม่สามารถลบบัญชีของตนเองที่กำลังล็อกอินอยู่ได้');
    header('Location: index.php');
    exit;
}

$user = find_admin_by_id($id);
if (!$user) {
    flash('error', 'ไม่พบข้อมูลผู้ดูแลระบบที่ต้องการลบในฐานข้อมูล');
    header('Location: index.php');
    exit;
}

// Guard 2: Prevent deleting the last remaining Super Admin
if ($user['role'] === 'super_admin') {
    $totalSuperAdmins = (int) db()->query("SELECT COUNT(*) FROM admins WHERE role = 'super_admin'")->fetchColumn();
    if ($totalSuperAdmins <= 1) {
        flash('error', 'ไม่สามารถลบ Super Admin คนสุดท้ายในระบบได้ (ต้องมี Super Admin อย่างน้อย 1 คนเสมอ)');
        header('Location: index.php');
        exit;
    }
}

try {
    $stmt = db()->prepare('DELETE FROM admins WHERE id = :id');
    $stmt->execute(['id' => $id]);

    flash('success', 'ลบผู้ดูแลระบบ "' . $user['username'] . '" เรียบร้อยแล้ว');
} catch (Exception $e) {
    flash('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
}

header('Location: index.php');
exit;
