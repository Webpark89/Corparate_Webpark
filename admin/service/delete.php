<?php

/**
 * Delete a service entry by ID from the query string.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

db()->prepare('DELETE FROM service WHERE id = ?')->execute([$_GET['id']]);
flash('success', 'ลบบริการเรียบร้อย');
header('Location: index.php');
exit;
