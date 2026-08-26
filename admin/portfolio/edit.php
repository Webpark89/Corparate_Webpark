<?php
/**
 * Edit an existing portfolio entry — loads record, renders form, delegates POST to _save.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}
$id = (int) ($_GET['id'] ?? 0);
$statement = db()->prepare('SELECT * FROM portfolio WHERE id = ?');
$statement->execute([$id]);
$portfolio = $statement->fetch();
if (!$portfolio) {
    http_response_code(404);
    exit('ไม่พบข้อมูลผลงานในระบบ (Portfolio not found.)');
}
$pageTitle = 'แก้ไขข้อมูลผลงาน';
$page = 'portfolio';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;
require_once __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
