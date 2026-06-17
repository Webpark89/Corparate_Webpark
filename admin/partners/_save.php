<?php

/** Shared save logic used by create.php and edit.php */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$categoryId = (int)($_POST['category_id'] ?? 0);

// 1. ตรวจสอบข้อมูลที่ห้ามเป็นค่าว่าง
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

// 2. รับค่าจากฟอร์มและจัดการข้อมูลให้ตรงกับตาราง partners
$data = [
    'name' => $name,
    'image_alt' => trim($_POST['image_alt'] ?? ''), // รองรับ Alt text สำหรับ SEO
    'category_id' => $categoryId,
    'sort_order' => (int)($_POST['sort_order'] ?? 0),
    'is_active' => isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1,
];

// 3. จัดการการอัปโหลดรูปภาพ (Logo)
$imagePath = trim($_POST['image_url'] ?? '');
try {
    // อัปโหลดไฟล์รูปผ่าน input file ที่ชื่อ 'image_file' (อนุญาตให้ใช้ svg ได้ด้วย เพราะโลโก้มักมีแบบเวกเตอร์)
    $img = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);

    if ($img) {
        $data['image_url'] = $img;
    } elseif ($imagePath !== '') {
        $data['image_url'] = $imagePath;
    }
} catch (RuntimeException $e) {
    flash('error', $e->getMessage());
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

// 4. อัปเดต หรือ สร้างข้อมูลใหม่
if ($id) {
    $sets = [];
    $params = [];
    foreach ($data as $k => $v) {
        $sets[] = "$k = ?";
        $params[] = $v;
    }
    $params[] = $id;

    // บันทึกลงตาราง partners แทน
    db()->prepare('UPDATE partners SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลโลโก้พันธมิตรเรียบร้อยแล้ว');
} else {
    $cols = implode(',', array_keys($data));
    $ph = rtrim(str_repeat('?,', count($data)), ',');

    // บันทึกลงตาราง partners แทน
    db()->prepare("INSERT INTO partners ($cols) VALUES ($ph)")->execute(array_values($data));
    flash('success', 'เพิ่มโลโก้พันธมิตรใหม่เรียบร้อยแล้ว');
}

header('Location: index.php');
exit;
