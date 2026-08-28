<?php

/**
 * Edit an existing article — loads record, renders form, delegates POST to _save.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/_save.php';
}

$id = (int) ($_GET['id'] ?? 0);
$statement = db()->prepare('SELECT * FROM article WHERE id = ?');
$statement->execute([$id]);
$article = $statement->fetch();
if (!$article) {
    http_response_code(404);
    exit('Article not found.');
}

$pageTitle = 'Edit Article';
$page = 'article';
require_once __DIR__ . '/../includes/header.php';
$action = 'edit';
$formAction = 'edit.php?id=' . $id;
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
