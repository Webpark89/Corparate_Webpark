<?php
/**
 * Show all reviews via POST with CSRF verification.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

csrf_verify();

db()->prepare('UPDATE review SET is_active = 1, updated_at = NOW()')->execute();

flash('success', 'แสดงรีวิวทั้งหมดเรียบร้อยแล้ว');
header('Location: index.php');
exit;
