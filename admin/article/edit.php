<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT * FROM article WHERE id = ?');
$st->execute([$id]);
$article = $st->fetch();
if (!$article) {
    http_response_code(404);
    exit('Article not found.');
}

$pageTitle = 'Edit Article';
$page = 'article';
require __DIR__ . '/../includes/header.php';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;
require __DIR__ . '/_form.php';
require __DIR__ . '/../includes/footer.php';
