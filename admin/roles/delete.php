<?php
/**
 * Delete Role Script
 */
require_once __DIR__ . '/../includes/functions.php';
require_permission('roles.delete');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    flash('error', 'ไม่พบรหัสบทบาทที่ต้องการลบ');
    header('Location: index.php');
    exit;
}

// CSRF Verification
$submittedToken = $_GET[CSRF_TOKEN_NAME] ?? ($_POST[CSRF_TOKEN_NAME] ?? '');
if (!hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', (string) $submittedToken)) {
    http_response_code(419);
    flash('error', 'Invalid CSRF token.');
    header('Location: index.php');
    exit;
}

$stmt = db()->prepare('SELECT * FROM roles WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$role = $stmt->fetch();

if (!$role) {
    flash('error', 'ไม่พบบทบาทที่ระบุ');
    header('Location: index.php');
    exit;
}

// System role check
if ($role['is_system']) {
    flash('error', 'ไม่สามารถลบบทบาทของระบบ (System Role) ได้');
    header('Location: index.php');
    exit;
}

// Check if any admin users are assigned to this role
$userCountStmt = db()->prepare('SELECT COUNT(*) FROM admins WHERE role_id = :id');
$userCountStmt->execute(['id' => $id]);
$userCount = (int)$userCountStmt->fetchColumn();

if ($userCount > 0) {
    flash('error', 'ไม่สามารถลบบทบาท "' . $role['name'] . '" ได้เนื่องจากมีผู้ใช้งาน ' . $userCount . ' คน ผูกอยู่กับบทบาทนี้ กรุณาย้ายผู้ใช้งานไปบทบาทอื่นก่อน');
    header('Location: index.php');
    exit;
}

try {
    $deleteStmt = db()->prepare('DELETE FROM roles WHERE id = :id');
    $deleteStmt->execute(['id' => $id]);
    flash('success', 'ลบบทบาท "' . $role['name'] . '" เรียบร้อยแล้ว');
} catch (Exception $e) {
    flash('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
}

header('Location: index.php');
exit;
