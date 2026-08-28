<?php

/**
 * Edit an existing service — loads record, renders form, delegates POST to _save.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);

$statement = db()->prepare('SELECT * FROM service WHERE id = ?');
$statement->execute([$id]);
$setting = $statement->fetch();

if (!$setting) {
    flash('error', 'ไม่พบข้อมูลบริการ');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
    exit;
}

$pageTitle = 'Edit Service';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;

require_once __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
