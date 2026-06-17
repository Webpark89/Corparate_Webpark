<?php
require_once __DIR__ . '/../includes/functions.php';
csrf_verify();

$id = $_POST['id'] ?? null;
$title = $_POST['title'];
$slug = $_POST['slug'];
$summary = $_POST['summary'];
$is_active = isset($_POST['is_active']) ? 1 : 0;

// แปลง text input เป็น JSON
$features_arr = array_map('trim', explode(',', $_POST['features']));
$details_json = json_encode(['features' => $features_arr]);

// อัปโหลดไฟล์
$image_path = $_POST['old_image'] ?? '';
if (!empty($_FILES['image']['name'])) {
    $image_path = handle_upload('image', ['jpg', 'png', 'webp']);
}

if ($id) {
    // อัปเดตข้อมูล (ไม่มี accent)
    $stmt = db()->prepare("UPDATE service SET slug=?, title=?, summary=?, details_json=?, image=?, is_active=? WHERE id=?");
    $stmt->execute([$slug, $title, $summary, $details_json, $image_path, $is_active, $id]);
    flash('success', 'แก้ไขข้อมูลเรียบร้อย');
} else {
    // เพิ่มข้อมูลใหม่ (ไม่มี accent)
    $stmt = db()->prepare("INSERT INTO service (slug, title, summary, details_json, image, is_active) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$slug, $title, $summary, $details_json, $image_path, $is_active]);
    flash('success', 'เพิ่มบริการใหม่เรียบร้อย');
}

header('Location: index.php');
