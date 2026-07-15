<?php
require_once __DIR__ . '/admin/includes/functions.php';

$stmt = db()->query('SELECT id, details_json FROM service WHERE details_json IS NOT NULL AND details_json != ""');
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach ($services as $service) {
    $details = json_decode($service['details_json'], true);
    if (is_array($details) && !empty($details['features'])) {
        $serviceId = $service['id'];
        
        // Get existing features
        $stmtExisting = db()->prepare('SELECT title FROM service_features WHERE service_id = ?');
        $stmtExisting->execute([$serviceId]);
        $existingFeatures = $stmtExisting->fetchAll(PDO::FETCH_COLUMN);
        
        $featuresToAdd = array_diff($details['features'], $existingFeatures);
        
        if (!empty($featuresToAdd)) {
            $insertStmt = db()->prepare('INSERT INTO service_features (service_id, title) VALUES (?, ?)');
            foreach ($featuresToAdd as $feature) {
                $insertStmt->execute([$serviceId, trim($feature)]);
                $count++;
            }
        }
    }
}
echo "Migrated $count features.";
