<?php
/**
 * Shared review save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();
$id = (int) ($_POST['id'] ?? 0);
$reviewerName = trim($_POST['reviewer_name'] ?? '');
$content = trim($_POST['content'] ?? '');
if ($reviewerName === '') {
    flash('error', 'กรุณากรอกชื่อผู้รีวิว');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
if ($content === '') {
    flash('error', 'กรุณากรอกข้อความรีวิว');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
$data = [
    'rating' => max(1, min(5, (int) ($_POST['rating'] ?? 5))),
    'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
    'reviewer_name' => $reviewerName,
    'reviewer_position' => trim($_POST['reviewer_position'] ?? ''),
    'reviewer_company' => trim($_POST['reviewer_company'] ?? ''),
    'sort_order' => (int) ($_POST['sort_order'] ?? 0),
    'is_active' => isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1,
];
$imagePath = trim($_POST['reviewer_image_url'] ?? '');
try {
    $uploadedImage = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    if ($uploadedImage) {
        $data['reviewer_image_url'] = $uploadedImage;
    } elseif ($imagePath !== '') {
        $data['reviewer_image_url'] = $imagePath;
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
    db()->prepare('UPDATE review SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลรีวิวเรียบร้อยแล้ว');
} else {
    $columns = implode(',', array_keys($data));
    $placeholders = rtrim(str_repeat('?,', count($data)), ',');
    db()->prepare("INSERT INTO review ($columns) VALUES ($placeholders)")->execute(array_values($data));
    flash('success', 'สร้างรีวิวใหม่เรียบร้อยแล้ว');
}
header('Location: index.php');
exit;
