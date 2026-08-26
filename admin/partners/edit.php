<?php
/**
 * Edit an existing partner — loads record, renders form, delegates POST to _save.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}
$id = (int) ($_GET['id'] ?? 0);
$statement = db()->prepare('SELECT * FROM partners WHERE id = ?');
$statement->execute([$id]);
$partner = $statement->fetch();
if (!$partner) {
    http_response_code(404);
    exit('ไม่พบข้อมูลโลโก้พันธมิตรในระบบ (Partner not found.)');
}
$pageTitle = 'แก้ไขข้อมูลโลโก้พันธมิตร';
$page = 'partners';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;
$review = $partner;
require_once __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
