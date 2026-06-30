<?php

/**
 * Shared portfolio save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
$metaTitle = trim($_POST['meta_title'] ?? '');
$categoryId = (int) ($_POST['category_id'] ?? 0);

if ($metaTitle === '') {
    flash('error', 'กรุณากรอกชื่อผลงาน (Meta Title)');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}
if ($categoryId === 0) {
    flash('error', 'กรุณาเลือกหมวดหมู่ผลงานโครงการ');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

$clientName = trim($_POST['client_name'] ?? '');
$metaDescription = trim($_POST['meta_description'] ?? '');
$techStack = trim($_POST['tech_stack'] ?? '');
$authorId = (int) ($_POST['author_id'] ?? 0) ?: null;
$status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
$slug = trim($_POST['slug'] ?? '') ?: slugify($metaTitle);

$coverImageAlt = trim($_POST['cover_image_alt'] ?? '');
if ($coverImageAlt === '') {
    $coverImageAlt = $metaTitle;
}

$coverImage = trim($_POST['cover_image'] ?? '');

$data = [
    'meta_title' => $metaTitle,
    'client_name' => $clientName,
    'category_id' => $categoryId,
    'meta_description' => $metaDescription,
    'meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
    'description' => $_POST['content'] ?? '',
    'tech_stack' => $techStack,
    'author_id' => $authorId,
    'status' => $status,
    'slug' => $slug,
    'cover_image_alt' => $coverImageAlt,
];

try {
    $uploadedCover = handle_upload('image_file');
    if ($uploadedCover) {
        $data['cover_image'] = $uploadedCover;
    } elseif ($coverImage !== '') {
        $data['cover_image'] = $coverImage;
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

    db()->prepare('UPDATE portfolio SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลผลงานเรียบร้อยแล้ว');
} else {
    $columns = implode(',', array_keys($data));
    $placeholders = rtrim(str_repeat('?,', count($data)), ',');

    db()->prepare("INSERT INTO portfolio ($columns) VALUES ($placeholders)")->execute(array_values($data));
    flash('success', 'สร้างผลงานใหม่เรียบร้อยแล้ว');
}

header('Location: index.php');
exit;
