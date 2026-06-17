<?php
require_once __DIR__ . '/../includes/functions.php';
if (isset($_GET['ajax']) && isset($_GET['key'])) {
    $st = db()->prepare('SELECT * FROM settings WHERE config_key = ?');
    $st->execute([$_GET['key']]);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $st->fetch(PDO::FETCH_ASSOC)]);
    exit;
}
