<?php

/**
 * AJAX endpoint — fetch a single setting record by config_key.
 */
require_once __DIR__ . '/../includes/functions.php';

if (isset($_GET['ajax']) && isset($_GET['key'])) {
    $statement = db()->prepare('SELECT * FROM settings WHERE config_key = ?');
    $statement->execute([$_GET['key']]);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $statement->fetch(PDO::FETCH_ASSOC)]);
    exit;
}
