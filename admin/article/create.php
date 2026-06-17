<?php
$pageTitle = 'New Article';
$page = 'article';
require_once __DIR__ . '/../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}
$article = [];
$action = 'create';
$formAction = 'create.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
