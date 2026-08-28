<?php

/**
 * Delete an article via POST with CSRF verification.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
if ($id) {
    db()->prepare('DELETE FROM article WHERE id = ?')->execute([$id]);
    flash('success', 'Article deleted.');
}

header('Location: index.php');
exit;
