<?php
/**
 * Create a new portfolio entry — renders the form and delegates POST to _save.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}
$pageTitle = 'สร้างผลงานใหม่';
$page = 'portfolio';
require_once __DIR__ . '/../includes/header.php';
$portfolio = [];
$action = 'create';
$formAction = 'create.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
