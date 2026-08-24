<?php
/**
 * Update contact message status via POST with CSRF verification.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
$status = trim((string) ($_POST['status'] ?? ''));
$allowed = ['new', 'read', 'replied', 'archived'];

if ($id > 0 && in_array($status, $allowed, true)) {
    $stmt = db()->prepare('UPDATE contact_messages SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);

    if (!empty($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    $statusLabel = match ($status) {
        'new'      => 'ใหม่',
        'read'     => 'อ่านแล้ว',
        'replied'  => 'ตอบกลับแล้ว',
        'archived' => 'เก็บถาวร',
        default    => $status
    };

    flash('success', "อัปเดตสถานะเป็น '{$statusLabel}' เรียบร้อยแล้ว");
} else {
    flash('error', 'ไม่สามารถอัปเดตสถานะได้ ข้อมูลไม่ถูกต้อง');
}

$returnUrl = trim((string) ($_POST['return_url'] ?? 'index.php'));
if ($returnUrl === '' || str_starts_with($returnUrl, 'http')) {
    $returnUrl = 'index.php';
}

header('Location: ' . $returnUrl);
exit;
