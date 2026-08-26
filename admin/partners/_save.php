<?php
/**
 * Shared partner save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();
$id = (int) ($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);
if ($name === '') {
    flash('error', 'กรุณากรอกชื่อบริษัท');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
if ($categoryId === 0) {
    flash('error', 'กรุณาเลือกหมวดหมู่');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
$data = [
    'name' => $name,
    'image_alt' => trim($_POST['image_alt'] ?? ''),
    'category_id' => $categoryId,
    'sort_order' => (int) ($_POST['sort_order'] ?? 1),
    'is_active' => isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1,
];
$imagePath = trim($_POST['image_url'] ?? '');
try {
    $uploadedImage = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
    if ($uploadedImage) {
        $data['image_url'] = $uploadedImage;
    } elseif ($imagePath !== '') {
        $data['image_url'] = $imagePath;
    }
} catch (RuntimeException $exception) {
    flash('error', $exception->getMessage());
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
if ($id) {
    $sets = [];
    $params = [];
    foreach ($data as $column => $value) {
        $sets[] = "$column = ?";
        $params[] = $value;
    }
    $params[] = $id;
    db()->prepare('UPDATE partners SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลโลโก้พันธมิตรเรียบร้อยแล้ว');
} else {
    $columns = implode(',', array_keys($data));
    $placeholders = rtrim(str_repeat('?,', count($data)), ',');
    db()->prepare("INSERT INTO partners ($columns) VALUES ($placeholders)")->execute(array_values($data));
    flash('success', 'เพิ่มโลโก้พันธมิตรใหม่เรียบร้อยแล้ว');
}
header('Location: index.php');
exit;
