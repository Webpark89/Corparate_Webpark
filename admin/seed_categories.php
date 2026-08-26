<?php
require_once __DIR__ . '/../frontend/app/bootstrap.php';
$db = Database::getInstance();
// The new mockup categories
$categories = [
    ['slug' => 'erp-erm', 'name' => 'ERP / ERM'],
    ['slug' => 'digital-platform', 'name' => 'Digital Platform'],
    ['slug' => 'online-marketing', 'name' => 'Online Marketing'],
    ['slug' => 'creative-design', 'name' => 'Creative / Design']
];
try {
    // Empty the categories table first
    $db->exec('SET FOREIGN_KEY_CHECKS = 0');
    $db->exec('TRUNCATE TABLE categories');
    $stmt = $db->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)');
    foreach ($categories as $cat) {
        $stmt->execute([$cat['name'], $cat['slug']]);
    }
    $db->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "Categories successfully seeded.\n";
} catch (PDOException $e) {
    echo "Error seeding categories: " . $e->getMessage() . "\n";
}
