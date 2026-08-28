<?php

declare(strict_types=1);

/**
 * AJAX Endpoint: Create a new category for articles.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/functions.php';

// 1. Auth check
if (empty($_SESSION['admin_logged_in'])) {
    if (!check_remember_me_cookie()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบใหม่อีกครั้ง']);
        exit;
    }
}

// 2. Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// 3. CSRF Verification
$tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
$submittedToken = $_POST[$tokenName] ?? $_POST['csrf_token'] ?? $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
$sessionToken = $_SESSION[$tokenName] ?? '';

if (empty($submittedToken) || !hash_equals((string)$sessionToken, (string)$submittedToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF Token ไม่ถูกต้องหรือหมดอายุ กรุณารีเฟรชหน้าเว็บ']);
    exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$slug = trim((string)($_POST['slug'] ?? ''));

if ($name === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกชื่อหมวดหมู่']);
    exit;
}

// Generate slug if empty
if ($slug === '') {
    $generatedSlug = slugify($name);
    if ($generatedSlug === '') {
        // Fallback for Thai-only names
        $generatedSlug = 'cat-' . substr(md5($name . microtime()), 0, 8);
    }
    $slug = $generatedSlug;
} else {
    $slug = slugify($slug);
    if ($slug === '') {
        $slug = 'cat-' . substr(md5($name . microtime()), 0, 8);
    }
}

try {
    $pdo = db();

    // Check if category name already exists
    $stmtCheckName = $pdo->prepare('SELECT id, name, slug FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1');
    $stmtCheckName->execute([$name]);
    $existingByName = $stmtCheckName->fetch();

    if ($existingByName) {
        // Return existing category so it selects directly
        echo json_encode([
            'success' => true,
            'id' => (int)$existingByName['id'],
            'name' => $existingByName['name'],
            'slug' => $existingByName['slug'],
            'is_existing' => true,
            'message' => 'พบหมวดหมู่นี้ในระบบแล้ว จึงเลือกหมวดหมู่นี้ให้อัตโนมัติ'
        ]);
        exit;
    }

    // Ensure unique slug
    $baseSlug = $slug;
    $counter = 1;
    $stmtCheckSlug = $pdo->prepare('SELECT id FROM categories WHERE slug = ? LIMIT 1');
    while (true) {
        $stmtCheckSlug->execute([$slug]);
        if (!$stmtCheckSlug->fetch()) {
            break;
        }
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    // Insert new category
    $stmtInsert = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
    $stmtInsert->execute([$name, $slug]);
    $newId = (int)$pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => $newId,
        'name' => $name,
        'slug' => $slug,
        'is_existing' => false,
        'message' => 'เพิ่มหมวดหมู่เรียบร้อยแล้ว'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()
    ]);
    exit;
}
