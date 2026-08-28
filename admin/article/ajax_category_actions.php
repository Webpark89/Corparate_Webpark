<?php

declare(strict_types=1);

/**
 * AJAX API for full Category CRUD operations: list, create, update, delete.
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

$action = trim((string)($_REQUEST['action'] ?? 'list'));

// 2. CSRF Verification for modifying actions
if (in_array($action, ['create', 'update', 'delete'], true)) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        exit;
    }

    $tokenName = defined('CSRF_TOKEN_NAME') ? CSRF_TOKEN_NAME : '_csrf';
    $submittedToken = $_POST[$tokenName] ?? $_POST['csrf_token'] ?? $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $sessionToken = $_SESSION[$tokenName] ?? '';

    if (empty($submittedToken) || !hash_equals((string)$sessionToken, (string)$submittedToken)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'CSRF Token ไม่ถูกต้องหรือหมดอายุ']);
        exit;
    }
}

try {
    $pdo = db();

    switch ($action) {
        case 'list':
            $stmt = $pdo->query('
                SELECT 
                    c.id, 
                    c.name, 
                    c.slug,
                    c.created_at,
                    COUNT(a.id) AS article_count
                FROM categories c
                LEFT JOIN article a ON a.category_id = c.id AND a.deleted_at IS NULL
                GROUP BY c.id
                ORDER BY c.name ASC
            ');
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'categories' => $categories
            ]);
            exit;

        case 'create':
            $name = trim((string)($_POST['name'] ?? ''));
            $slug = trim((string)($_POST['slug'] ?? ''));

            if ($name === '') {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'กรุณากรอกชื่อหมวดหมู่']);
                exit;
            }

            if ($slug === '') {
                $generatedSlug = slugify($name);
                $slug = ($generatedSlug !== '') ? $generatedSlug : ('cat-' . substr(md5($name . microtime()), 0, 8));
            } else {
                $slug = slugify($slug);
                if ($slug === '') {
                    $slug = 'cat-' . substr(md5($name . microtime()), 0, 8);
                }
            }

            // Check if name already exists
            $stmtCheck = $pdo->prepare('SELECT id, name FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1');
            $stmtCheck->execute([$name]);
            if ($stmtCheck->fetch()) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'มีหมวดหมู่ชื่อนี้อยู่ในระบบแล้ว']);
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

            $stmtInsert = $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
            $stmtInsert->execute([$name, $slug]);
            $newId = (int)$pdo->lastInsertId();

            echo json_encode([
                'success' => true,
                'id' => $newId,
                'name' => $name,
                'slug' => $slug,
                'message' => 'สร้างหมวดหมู่ใหม่สำเร็จ'
            ]);
            exit;

        case 'update':
            $id = (int)($_POST['id'] ?? 0);
            $name = trim((string)($_POST['name'] ?? ''));
            $slug = trim((string)($_POST['slug'] ?? ''));

            if ($id <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสหมวดหมู่']);
                exit;
            }

            if ($name === '') {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'กรุณากรอกชื่อหมวดหมู่']);
                exit;
            }

            if ($slug === '') {
                $generatedSlug = slugify($name);
                $slug = ($generatedSlug !== '') ? $generatedSlug : ('cat-' . substr(md5($name . microtime()), 0, 8));
            } else {
                $slug = slugify($slug);
                if ($slug === '') {
                    $slug = 'cat-' . substr(md5($name . microtime()), 0, 8);
                }
            }

            // Check if name is taken by another category
            $stmtCheckName = $pdo->prepare('SELECT id FROM categories WHERE LOWER(name) = LOWER(?) AND id != ? LIMIT 1');
            $stmtCheckName->execute([$name, $id]);
            if ($stmtCheckName->fetch()) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'มีหมวดหมู่อื่นที่ใช้ชื่อนี้แล้ว']);
                exit;
            }

            // Ensure unique slug for other categories
            $baseSlug = $slug;
            $counter = 1;
            $stmtCheckSlug = $pdo->prepare('SELECT id FROM categories WHERE slug = ? AND id != ? LIMIT 1');
            while (true) {
                $stmtCheckSlug->execute([$slug, $id]);
                if (!$stmtCheckSlug->fetch()) {
                    break;
                }
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $stmtUpdate = $pdo->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?');
            $stmtUpdate->execute([$name, $slug, $id]);

            echo json_encode([
                'success' => true,
                'id' => $id,
                'name' => $name,
                'slug' => $slug,
                'message' => 'แก้ไขหมวดหมู่สำเร็จ'
            ]);
            exit;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                http_response_code(422);
                echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสหมวดหมู่']);
                exit;
            }

            // Count affected articles
            $stmtCount = $pdo->prepare('SELECT COUNT(*) FROM article WHERE category_id = ? AND deleted_at IS NULL');
            $stmtCount->execute([$id]);
            $affectedCount = (int)$stmtCount->fetchColumn();

            // Set article category_id to NULL
            $stmtUnlink = $pdo->prepare('UPDATE article SET category_id = NULL WHERE category_id = ?');
            $stmtUnlink->execute([$id]);

            // Delete the category
            $stmtDelete = $pdo->prepare('DELETE FROM categories WHERE id = ?');
            $stmtDelete->execute([$id]);

            echo json_encode([
                'success' => true,
                'id' => $id,
                'affected_articles' => $affectedCount,
                'message' => 'ลบหมวดหมู่เรียบร้อยแล้ว'
            ]);
            exit;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'เกิดข้อผิดพลาดในการดำเนินการ: ' . $e->getMessage()
    ]);
    exit;
}
