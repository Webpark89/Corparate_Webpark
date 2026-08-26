<?php
/**
 * Delete a service entry by ID via POST with CSRF verification.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
csrf_verify();
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash('error', 'Invalid service ID.');
    header('Location: index.php');
    exit;
}
db()->prepare('DELETE FROM service WHERE id = ?')->execute([$id]);
flash('success', 'ลบบริการเรียบร้อย');
header('Location: index.php');
exit;
