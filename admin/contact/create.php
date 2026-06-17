<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

// หากมีการ POST มา ให้ไปที่ _save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
    exit;
}

$pageTitle = 'Add New Setting';
$page = 'settings';
$action = 'create';
$formAction = 'create.php';
$setting = ['group' => $_GET['group'] ?? 'general']; // รับค่า group จาก URL ถ้ามี

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
