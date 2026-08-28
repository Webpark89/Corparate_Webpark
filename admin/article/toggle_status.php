<?php

/**
 * Toggle article visibility status via POST with CSRF verification.
 *
 * POST params:
 *   id     (int)    – article ID
 *   status (string) – current status before toggle
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

csrf_verify();

$id            = (int) ($_POST['id'] ?? 0);
$currentStatus = trim($_POST['status'] ?? '');

if ($id <= 0) {
    flash('error', 'Invalid article ID.');
    header('Location: index.php');
    exit;
}

// Toggle logic:
//   published → hidden
//   hidden    → published
//   draft     → hidden  (draft ยังไม่ publish แต่กดซ่อนได้)
$newStatus = match ($currentStatus) {
    'published' => 'hidden',
    'hidden'    => 'published',
    default     => 'hidden',
};

db()
    ->prepare('UPDATE article SET status = ?, updated_at = NOW() WHERE id = ?')
    ->execute([$newStatus, $id]);

$label = $newStatus === 'hidden' ? 'ซ่อนบทความแล้ว' : 'แสดงบทความแล้ว';
flash('success', $label);

// Return to same URL the admin came from (preserve search/filter params)
$ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $ref);
exit;
