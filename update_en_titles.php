<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->query("SELECT id, title, meta_title FROM article");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as $art) {
    $thTitle = $art['meta_title'] ?: $art['title'];
    $enTitle = '';
    if (strpos($thTitle, 'ERP บัญชี') !== false) {
        $enTitle = 'Which Business Suits ERP Accounting System Development? ERP Selection Guide for All Businesses 2026';
    } elseif (strpos($thTitle, 'ระบบ HR') !== false) {
        $enTitle = 'What is HR System Development Service? Elevating Comprehensive HR Management 2026';
    } elseif (strpos($thTitle, 'Digital Platforms') !== false) {
        $enTitle = 'Digital Platforms & Business Systems Services Elevating Business to the Digital Era 2026';
    }
    
    if ($enTitle) {
        $upd = $db->prepare("UPDATE article SET meta_title_en = :en WHERE id = :id");
        $upd->execute([':en' => $enTitle, ':id' => $art['id']]);
        echo "Updated article " . $art['id'] . "\n";
    }
}
echo "Done.";
