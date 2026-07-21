<?php
/**
 * Toggle portfolio visibility status via POST with CSRF verification.
 *
 * POST params:
 *   id     (int)    – portfolio ID
 *   status (string) – current status before toggle
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
csrf_verify();
$id            = (int) ($_POST['id'] ?? 0);
$currentStatus = trim($_POST['status'] ?? '');
if ($id <= 0) {
    flash('error', 'Invalid portfolio ID.');
    header('Location: index.php');
    exit;
}
// Toggle logic:
//   published → hidden
//   hidden    → published
//   draft     → hidden
$newStatus = match ($currentStatus) {
    'published' => 'hidden',
    'hidden'    => 'published',
    default     => 'hidden',
};
db()
    ->prepare('UPDATE portfolio SET status = ?, updated_at = NOW() WHERE id = ?')
    ->execute([$newStatus, $id]);
$label = $newStatus === 'hidden' ? 'ซ่อนผลงานเรียบร้อยแล้ว' : 'แสดงผลงานเรียบร้อยแล้ว';
flash('success', $label);
$ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $ref);
exit;
