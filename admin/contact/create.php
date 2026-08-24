<?php
/**
 * Create a new contact setting — renders the form and handles POST insertion.
 */
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $configKey = trim((string) ($_POST['config_key'] ?? ''));
    $group = trim((string) ($_POST['group'] ?? 'contact'));
    $description = trim((string) ($_POST['description'] ?? ''));
    $configValue = (string) ($_POST['config_value'] ?? '');

    if ($configKey === '') {
        flash('error', 'กรุณาระบุชื่อตัวแปร (Config Key)');
        header('Location: create.php?group=' . urlencode($group));
        exit;
    }

    try {
        // Handle file upload if present
        $uploadedFile = handle_upload('config_file');
        if ($uploadedFile !== null) {
            $configValue = $uploadedFile;
        }

        $statement = db()->prepare('
            INSERT INTO settings (config_key, config_value, `group`, description) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                config_value = VALUES(config_value), 
                `group` = VALUES(`group`), 
                description = VALUES(description), 
                updated_at = NOW()
        ');
        $statement->execute([$configKey, $configValue, $group, $description]);

        flash('success', "เพิ่มรายการตั้งค่า '{$configKey}' เรียบร้อยแล้ว");
    } catch (Exception $exception) {
        flash('error', 'เกิดข้อผิดพลาด: ' . $exception->getMessage());
    }

    header('Location: index.php');
    exit;
}

$pageTitle = 'เพิ่มรายการติดต่อใหม่';
$page = 'contact';
$action = 'create';
$formAction = 'create.php';
$setting = ['group' => $_GET['group'] ?? 'contact'];

require_once __DIR__ . '/../includes/header.php';
require __DIR__ . '/_form.php';
require_once __DIR__ . '/../includes/footer.php';
