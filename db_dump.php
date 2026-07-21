<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $db->query("SELECT id, title, meta_title, meta_title_en FROM article ORDER BY id DESC LIMIT 5");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
file_put_contents(__DIR__ . '/db_dump.txt', print_r($rows, true));
