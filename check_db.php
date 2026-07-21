<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $db->query("SELECT id, title FROM article LIMIT 10");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row) {
    echo "ID: " . $row['id'] . " | Title: " . $row['title'] . "\n";
}
