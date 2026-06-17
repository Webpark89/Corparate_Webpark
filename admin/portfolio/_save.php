<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$metaTitle = trim($_POST['meta_title'] ?? '');
$categoryId = (int)($_POST['category_id'] ?? 0);

// 1. ตรวจสอบข้อมูลบังคับ
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
$authorId = (int)($_POST['author_id'] ?? 0) ?: null;
$status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

// สร้าง Slug หากไม่ได้กรอกมา
$slug = trim($_POST['slug'] ?? '') ?: slugify($metaTitle);

// กำหนด Alt Text: หากช่องกรอกถูกปล่อยว่างไว้ ให้ใช้ชื่อผลงาน (Meta Title) แทนอัตโนมัติ
$coverImageAlt = trim($_POST['cover_image_alt'] ?? '');
if ($coverImageAlt === '') {
    $coverImageAlt = $metaTitle;
}

$coverImage = trim($_POST['cover_image'] ?? ''); // เก็บ Path รูปเก่าไว้กรณีไม่มีการอัปโหลดใหม่

// 2. จับคู่ข้อมูลให้ตรงกับตาราง Database เป๊ะๆ
$data = [
    'meta_title' => $metaTitle,
    'client_name' => $clientName,
    'category_id' => $categoryId,
    'meta_description' => $metaDescription,
    'meta_keywords' => trim($_POST['meta_keywords'] ?? ''),

    // **จุดสำคัญที่แก้ให้:** เปลี่ยนจาก 'content' เป็น 'description' เพื่อให้ตรงกับโครงสร้าง Database
    'description' => $_POST['content'] ?? '',
    'tech_stack' => $techStack,
    'author_id' => $authorId,
    'status' => $status,
    'slug' => $slug,
    'cover_image_alt' => $coverImageAlt,
];

try {
    // อัปโหลดไฟล์รูปภาพปก
    $cover = handle_upload('image_file');
    if ($cover) {
        $data['cover_image'] = $cover;
    } elseif ($coverImage !== '') {
        $data['cover_image'] = $coverImage;
    }
} catch (RuntimeException $e) {
    flash('error', $e->getMessage());
    header('Location: ' . ($id ? "edit.php?id=$id" : 'create.php'));
    exit;
}

// อัปเดต หรือ สร้างข้อมูลใหม่
if ($id) {
    $sets = [];
    $params = [];
    foreach ($data as $k => $v) {
        $sets[] = "$k = ?";
        $params[] = $v;
    }
    $params[] = $id;

    db()->prepare('UPDATE portfolio SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลผลงานเรียบร้อยแล้ว');
} else {
    $cols = implode(',', array_keys($data));
    $ph   = rtrim(str_repeat('?,', count($data)), ',');

    db()->prepare("INSERT INTO portfolio ($cols) VALUES ($ph)")->execute(array_values($data));
    flash('success', 'สร้างผลงานใหม่เรียบร้อยแล้ว');
}

header('Location: index.php');
exit;
