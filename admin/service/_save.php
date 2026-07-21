<?php
/**
 * Shared service save logic used by create.php and edit.php.
 */
require_once __DIR__ . '/../includes/functions.php';
csrf_verify();
$id = $_POST['id'] ?? null;
$title = $_POST['title'];
$slug = $_POST['slug'];
$summary = $_POST['summary'];
$isActive = isset($_POST['is_active']) ? 1 : 0;
$featuresList = array_filter(array_map('trim', explode(',', $_POST['features'])));
$dropdownTitle = trim($_POST['dropdown_title'] ?? '');
$detailsJson = json_encode([
    'dropdown_title' => $dropdownTitle
]);
$imagePath = $_POST['old_image'] ?? '';
try {
    if (!empty($_FILES['image']['name'])) {
        $imagePath = handle_upload('image', ['jpg', 'png', 'webp']);
    }
} catch (RuntimeException $e) {
    flash('error', 'อัพโหลดรูปภาพไม่สำเร็จ: ' . $e->getMessage());
    $redirectUrl = $id ? "edit.php?id=$id" : 'create.php';
    header("Location: $redirectUrl");
    exit;
}
try {
    $serviceId = $id;
    if ($id) {
        $statement = db()->prepare('UPDATE service SET slug=?, title=?, summary=?, details_json=?, image=?, is_active=? WHERE id=?');
        $statement->execute([$slug, $title, $summary, $detailsJson, $imagePath, $isActive, $id]);
        flash('success', 'แก้ไขข้อมูลเรียบร้อย');
    } else {
        $statement = db()->prepare('INSERT INTO service (slug, title, summary, details_json, image, is_active) VALUES (?,?,?,?,?,?)');
        $statement->execute([$slug, $title, $summary, $detailsJson, $imagePath, $isActive]);
        $serviceId = db()->lastInsertId();
        flash('success', 'เพิ่มบริการใหม่เรียบร้อย');
    }
    // Sync service_features
    if ($serviceId) {
        $stmt = db()->prepare('SELECT id, title FROM service_features WHERE service_id = ?');
        $stmt->execute([$serviceId]);
        $existingFeatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $existingTitles = array_column($existingFeatures, 'title');
        // Find features to add
        $featuresToAdd = array_diff($featuresList, $existingTitles);
        if (!empty($featuresToAdd)) {
            $insertStmt = db()->prepare('INSERT INTO service_features (service_id, title) VALUES (?, ?)');
            foreach ($featuresToAdd as $featureTitle) {
                $insertStmt->execute([$serviceId, $featureTitle]);
            }
        }
        // Find features to delete
        $featuresToDelete = array_diff($existingTitles, $featuresList);
        if (!empty($featuresToDelete)) {
            $deleteStmt = db()->prepare('DELETE FROM service_features WHERE service_id = ? AND title = ?');
            foreach ($featuresToDelete as $featureTitle) {
                $deleteStmt->execute([$serviceId, $featureTitle]);
            }
        }
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        flash('error', 'URL/Slug นี้มีการใช้งานแล้ว กรุณากำหนด URL ใหม่ที่ไม่ซ้ำกับของเดิมครับ');
        $redirectUrl = $id ? "edit.php?id=$id" : 'create.php';
        header("Location: $redirectUrl");
        exit;
    }
    throw $e;
}
header('Location: index.php');
exit;
