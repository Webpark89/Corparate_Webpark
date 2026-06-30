<?php

/**
 * Shared service save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
csrf_verify();

$id = $_POST['id'] ?? null;
$title = $_POST['title'];
$slug = $_POST['slug'];
$summary = $_POST['summary'];
$isActive = isset($_POST['is_active']) ? 1 : 0;

$featuresList = array_map('trim', explode(',', $_POST['features']));
$detailsJson = json_encode(['features' => $featuresList]);

$imagePath = $_POST['old_image'] ?? '';
if (!empty($_FILES['image']['name'])) {
    $imagePath = handle_upload('image', ['jpg', 'png', 'webp']);
}

if ($id) {
    $statement = db()->prepare('UPDATE service SET slug=?, title=?, summary=?, details_json=?, image=?, is_active=? WHERE id=?');
    $statement->execute([$slug, $title, $summary, $detailsJson, $imagePath, $isActive, $id]);
    flash('success', 'แก้ไขข้อมูลเรียบร้อย');
} else {
    $statement = db()->prepare('INSERT INTO service (slug, title, summary, details_json, image, is_active) VALUES (?,?,?,?,?,?)');
    $statement->execute([$slug, $title, $summary, $detailsJson, $imagePath, $isActive]);
    flash('success', 'เพิ่มบริการใหม่เรียบร้อย');
}

header('Location: index.php');
exit;
