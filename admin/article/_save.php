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
            $body = isset($item['body']) ? sanitize_html($item['body']) : '';
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
    'cover_image_alt' => trim($_POST['cover_image_alt'] ?? $metaTitle),
    'content' => $serializedContent,
    'author_id' => (int) ($_POST['author_id'] ?? 0) ?: null,
    'status' => in_array($_POST['status'] ?? 'draft', ['published', 'draft', 'hidden'], true) ? ($_POST['status'] ?? 'draft') : 'draft',
];

$imagePath = trim($_POST['cover_image'] ?? '');
try {
    $uploadedImage = handle_upload('image_file', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    if ($uploadedImage) {
        $data['cover_image'] = $uploadedImage;
    } elseif ($imagePath !== '') {
        $data['cover_image'] = $imagePath;
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
    db()->prepare('UPDATE article SET ' . implode(',', $sets) . ' WHERE id = ?')->execute($params);
    flash('success', 'Article updated.');
} else {
    $columns = implode(',', array_keys($data));
    $placeholders = rtrim(str_repeat('?,', count($data)), ',');
    db()->prepare("INSERT INTO article ($columns) VALUES ($placeholders)")->execute(array_values($data));
    flash('success', 'Article created.');
}

header('Location: index.php');
exit;
