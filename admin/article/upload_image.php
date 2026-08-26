<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/upload_error.log');
require_once __DIR__ . '/../includes/functions.php';
require_login();
header('Content-Type: application/json');
if (!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => ['message' => 'Upload failed or no file sent.']]);
    exit;
}
$file = $_FILES['upload'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedTypes, true)) {
    echo json_encode(['error' => ['message' => 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.']]);
    exit;
}
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
    $ext = 'jpg'; // Fallback
}
$filename = uniqid('content_', true) . '.' . $ext;
// Determine the base path for assets. We assume it's in frontend/public/assets/images/uploads
$uploadDir = dirname(__DIR__, 2) . '/frontend/public/assets/images/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
$destination = $uploadDir . $filename;
if (move_uploaded_file($file['tmp_name'], $destination)) {
    // Return the URL that can be accessed by the browser
    // Using asset_url if it works, otherwise a hardcoded relative path based on ROOT_URL
    $url = defined('ROOT_URL') ? ROOT_URL . '/frontend/public/assets/images/uploads/' . $filename : '/Corparate_Webpark/frontend/public/assets/images/uploads/' . $filename;
    echo json_encode([
        'url' => $url
    ]);
} else {
    echo json_encode(['error' => ['message' => 'Failed to save uploaded file.']]);
}
