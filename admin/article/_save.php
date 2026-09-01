<?php

/**
 * Shared article save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
$metaTitle = trim($_POST['meta_title'] ?? '');
if ($metaTitle === '') {
    flash('error', 'Meta title is required.');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

$sectionsInput = $_POST['sections'] ?? [];
$finalSections = [];

foreach (['th', 'en'] as $lang) {
    if (isset($sectionsInput[$lang]) && is_array($sectionsInput[$lang])) {
        foreach ($sectionsInput[$lang] as $item) {
            $topic = trim($item['topic'] ?? '');
            $body = isset($item['body']) ? sanitize_html(convert_plain_bullets_to_html($item['body'])) : '';
            if ($body !== '') {
                $basePath = defined('SITE_URL') ? SITE_URL : '/Corparate_Webpark';
                $body = preg_replace(
                    '#src=["\'](?:\.\./)+frontend/public/assets/([^"\']+)["\']#i',
                    'src="' . $basePath . '/frontend/public/assets/$1"',
                    $body
                );
            }
            if ($topic !== '' || $body !== '') {
                $finalSections[] = [
                    'lang' => $lang,
                    'topic' => $topic,
                    'body' => $body
                ];
            }
        }
    }
}
$serializedContent = json_encode($finalSections, JSON_UNESCAPED_UNICODE);

$metaTitleEn = trim($_POST['meta_title_en'] ?? '');
$slugEn = trim($_POST['slug_en'] ?? '');
if ($slugEn === '' && $metaTitleEn !== '') {
    $slugEn = slugify($metaTitleEn);
}

$data = [
    'slug' => trim($_POST['slug'] ?? '') ?: slugify($metaTitle),
    'slug_en' => $slugEn !== '' ? $slugEn : null,
    'meta_title' => $metaTitle,
    'meta_title_en' => $metaTitleEn,
    'meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
    'meta_keywords_en' => trim($_POST['meta_keywords_en'] ?? ''),
    'meta_description' => trim($_POST['meta_description'] ?? ''),
    'meta_description_en' => trim($_POST['meta_description_en'] ?? ''),
    'source_url' => trim($_POST['source_url'] ?? ''),
    'category_id' => (int) ($_POST['category_id'] ?? 0),
    'is_pinned' => (isset($_POST['is_pinned']) && (string)$_POST['is_pinned'] === '1') ? 1 : 0,
    'cover_image_alt' => trim($_POST['cover_image_alt'] ?? $metaTitle),
    'content' => $serializedContent,
    'author_id' => (int) ($_POST['author_id'] ?? 0) ?: null,
    'status' => in_array($_POST['status'] ?? 'draft', ['published', 'draft', 'hidden'], true) ? ($_POST['status'] ?? 'draft') : 'draft',
    'created_at' => (isset($_POST['created_at']) && trim($_POST['created_at']) !== '') ? date('Y-m-d H:i:s', strtotime($_POST['created_at'])) : date('Y-m-d H:i:s'),
];

// Ensure only 1 article is pinned across the system
if ($data['is_pinned'] === 1) {
    if ($id > 0) {
        db()->prepare('UPDATE article SET is_pinned = 0 WHERE id != ?')->execute([$id]);
    } else {
        db()->exec('UPDATE article SET is_pinned = 0');
    }
}

$imagePath = trim($_POST['cover_image'] ?? '');
try {
    if (!empty($_FILES['image_file']['name'])) {
        $maxSizeBytes = 1024 * 1024; // 1 MB limit
        if ($_FILES['image_file']['size'] > $maxSizeBytes) {
            $sizeKb = round($_FILES['image_file']['size'] / 1024, 1);
            throw new RuntimeException("ขนาดไฟล์รูปภาพเกินกำหนด ({$sizeKb} KB) กรุณาใช้รูปภาพขนาดไม่เกิน 1 MB (แนะนำ 150 – 350 KB)");
        }
        $uploadedImage = handle_upload('image_file', ['webp']);
        if ($uploadedImage) {
            $data['cover_image'] = $uploadedImage;
        }
    } elseif ($imagePath !== '') {
        $data['cover_image'] = $imagePath;
    }
} catch (RuntimeException $exception) {
    $msg = $exception->getMessage();
    if ($msg === 'File type not allowed.' || $msg === 'Invalid MIME type.') {
        $msg = 'ระบบรองรับเฉพาะไฟล์รูปภาพนามสกุล .webp เท่านั้น กรุณาแปลงไฟล์เป็น .webp ก่อนอัปโหลดครับ';
    }
    flash('error', 'อัปโหลดรูปภาพไม่สำเร็จ: ' . $msg);
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

try {
    if ($id) {
        $sets = [];
        $params = [];
        foreach ($data as $column => $value) {
            $sets[] = "$column = ?";
            $params[] = $value;
        }
        $params[] = $id;
        db()->prepare('UPDATE article SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
        flash('success', 'Article updated.');
    } else {
        $columns = implode(',', array_keys($data));
        $placeholders = rtrim(str_repeat('?,', count($data)), ',');
        db()->prepare("INSERT INTO article ($columns) VALUES ($placeholders)")->execute(array_values($data));
        flash('success', 'Article created.');
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        flash('error', 'Error: URL Slug (ลิงก์บทความ) มีการใช้งานซ้ำกับบทความอื่น กรุณาเปลี่ยนชื่อลิงก์ใหม่ไม่ให้ซ้ำกันครับ');
    } else {
        flash('error', 'Database Error: ' . $e->getMessage());
    }
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

header('Location: index.php');
exit;
