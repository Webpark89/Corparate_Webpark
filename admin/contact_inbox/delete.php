<?php
/**
 * Delete a contact message via POST with CSRF verification.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

csrf_verify();

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = db()->prepare('DELETE FROM contact_messages WHERE id = ?');
    $stmt->execute([$id]);
    flash('success', 'ลบข้อความติดต่อเรียบร้อยแล้ว');
} else {
    flash('error', 'ไม่พบข้อความที่ต้องการลบ');
}

$returnUrl = trim((string) ($_POST['return_url'] ?? 'index.php'));
if ($returnUrl === '' || str_starts_with($returnUrl, 'http')) {
    $returnUrl = 'index.php';
}

header('Location: ' . $returnUrl);
exit;
