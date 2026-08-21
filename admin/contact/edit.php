<?php
/**
 * Edit contact setting — renders the edit form and handles POST update.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

// 1. AJAX request handler
if (isset($_GET['ajax']) && isset($_GET['key'])) {
    $statement = db()->prepare('SELECT * FROM settings WHERE config_key = ?');
    $statement->execute([$_GET['key']]);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $statement->fetch(PDO::FETCH_ASSOC)]);
    exit;
}

$key = trim((string) ($_GET['key'] ?? ($_POST['config_key'] ?? '')));

if ($key === '') {
    flash('error', 'ไม่พบคีย์การตั้งค่าที่ต้องการแก้ไข');
    header('Location: index.php');
    exit;
}

// 2. Handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $group = trim((string) ($_POST['group'] ?? 'contact'));
    $description = trim((string) ($_POST['description'] ?? ''));
    $configValue = (string) ($_POST['config_value'] ?? '');

    try {
        // Handle file upload if present
        $uploadedFile = handle_upload('config_file');
        if ($uploadedFile !== null) {
            $configValue = $uploadedFile;
        }

        $statement = db()->prepare('
            UPDATE settings 
            SET config_value = ?, `group` = ?, description = ?, updated_at = NOW() 
            WHERE config_key = ?
        ');
        $statement->execute([$configValue, $group, $description, $key]);

        flash('success', "บันทึกการตั้งค่า '{$key}' เรียบร้อยแล้ว");
    } catch (Exception $exception) {
        flash('error', 'เกิดข้อผิดพลาด: ' . $exception->getMessage());
    }

    header('Location: index.php');
    exit;
}

// 3. Render GET edit page
$stmt = db()->prepare('SELECT * FROM settings WHERE config_key = ? LIMIT 1');
$stmt->execute([$key]);
$setting = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$setting) {
    flash('error', "ไม่พบการตั้งค่า '{$key}' ในระบบ");
    header('Location: index.php');
    exit;
}

$pageTitle = 'แก้ไขการตั้งค่า: ' . ($setting['description'] ?: $setting['config_key']);
$page = 'contact';
$action = 'edit';
$formAction = 'edit.php?key=' . urlencode($key);

require_once __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
