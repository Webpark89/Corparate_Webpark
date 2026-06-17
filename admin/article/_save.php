<?php

/** Shared save logic used by create.php and edit.php */
require_once __DIR__ . '/../includes/functions.php';
require_login();
csrf_verify();

$id = (int)($_POST['id'] ?? 0);
$metaTitle = trim($_POST['meta_title'] ?? '');
if ($metaTitle === '') {
    flash('error', 'Meta title is required.');
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

$data = [
    'slug' => trim($_POST['slug'] ?? '') ?: slugify($metaTitle),
    'meta_title' => $metaTitle,
    'meta_keywords' => trim($_POST['meta_keywords'] ?? ''),
    'meta_description' => trim($_POST['meta_description'] ?? ''),
    'category_id' => (int)($_POST['category_id'] ?? 0),
    'cover_image_alt' => trim($_POST['cover_image_alt'] ?? $metaTitle),
    'content' => sanitize_html($_POST['content'] ?? ''),
    'author_id' => (int)($_POST['author_id'] ?? 0) ?: null,
    'status' => ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft',
];

$imagePath = trim($_POST['cover_image'] ?? '');
try {
    $img = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp']);
    if ($img) {
        $data['cover_image'] = $img;
    } elseif ($imagePath !== '') {
        $data['cover_image'] = $imagePath;
    }
} catch (RuntimeException $e) {
    flash('error', $e->getMessage());
    header('Location: ' . ($id ? 'edit.php?id=' . $id : 'create.php'));
    exit;
}

if ($id) {
    $sets = [];
    $params = [];
    foreach ($data as $k => $v) {
        $sets[] = "$k = ?";
        $params[] = $v;
    }
    $params[] = $id;
    db()->prepare('UPDATE article SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'Article updated.');
} else {
    $cols = implode(',', array_keys($data));
    $ph = rtrim(str_repeat('?,', count($data)), ',');
    db()->prepare("INSERT INTO article ($cols) VALUES ($ph)")->execute(array_values($data));
    flash('success', 'Article created.');
}

header('Location: index.php');
exit;
