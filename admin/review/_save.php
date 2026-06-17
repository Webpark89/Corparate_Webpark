<?php

/** Shared save logic used by create.php and edit.php */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$reviewerName = trim($_POST['reviewer_name'] ?? '');
$content = trim($_POST['content'] ?? '');

// ตรวจสอบข้อมูลที่ห้ามเป็นค่าว่าง
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

// รับค่าจากฟอร์มและจัดการข้อมูล
$data = [
    // บังคับให้คะแนนดาวอยู่ในช่วง 1 ถึง 5 เท่านั้น
    'rating' => max(1, min(5, (int)($_POST['rating'] ?? 5))),
    'content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'), 
    'reviewer_name' => $reviewerName,
    'reviewer_position' => trim($_POST['reviewer_position'] ?? ''),
    'reviewer_company' => trim($_POST['reviewer_company'] ?? ''),
    'sort_order' => (int)($_POST['sort_order'] ?? 0),
    'is_active' => isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1,
];

// จัดการการอัปโหลดรูปภาพโปรไฟล์ (Avatar)
$imagePath = trim($_POST['reviewer_image_url'] ?? '');
try {
    // อัปโหลดไฟล์รูปผ่าน input file ที่ชื่อ 'image_file'
    $img = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    if ($img) {
        $data['reviewer_image_url'] = $img;
    } elseif ($imagePath !== '') {
        $data['reviewer_image_url'] = $imagePath;
    }
} catch (RuntimeException $e) {
    flash('error', $e->getMessage());
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
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
    // ใช้ตาราง review
    db()->prepare('UPDATE review SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'อัปเดตข้อมูลรีวิวเรียบร้อยแล้ว');
} else {
    $cols = implode(',', array_keys($data));
    $ph = rtrim(str_repeat('?,', count($data)), ',');
    // ใช้ตาราง review
    db()->prepare("INSERT INTO review ($cols) VALUES ($ph)")->execute(array_values($data));
    flash('success', 'สร้างรีวิวใหม่เรียบร้อยแล้ว');
}

header('Location: index.php');
exit;
