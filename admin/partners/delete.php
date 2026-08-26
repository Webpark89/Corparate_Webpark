<?php
/**
 * Delete a partner by ID via GET or POST.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if (!isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id) {
    db()->prepare('DELETE FROM partners WHERE id = ?')->execute([$id]);
    flash('success', 'ลบข้อมูลโลโก้พันธมิตรเรียบร้อยแล้ว');
}
header('Location: index.php');
exit;
