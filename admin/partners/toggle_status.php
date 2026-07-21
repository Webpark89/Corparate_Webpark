<?php
/**
 * Toggle partner visibility status via POST with CSRF verification.
 *
 * POST params:
 *   id     (int)    – partner ID
 *   status (string) – current status ('1' or '0') before toggle
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
csrf_verify();
$id            = (int) ($_POST['id'] ?? 0);
$currentStatus = (int) ($_POST['status'] ?? 0);
if ($id <= 0) {
    flash('error', 'Invalid partner ID.');
    header('Location: index.php');
    exit;
}
// Toggle: 1 → 0, 0 → 1
$newStatus = $currentStatus === 1 ? 0 : 1;
db()
    ->prepare('UPDATE partners SET is_active = ?, updated_at = NOW() WHERE id = ?')
    ->execute([$newStatus, $id]);
$label = $newStatus === 0 ? 'ซ่อนพาร์ทเนอร์แล้ว' : 'แสดงพาร์ทเนอร์แล้ว';
flash('success', $label);
$ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $ref);
exit;
