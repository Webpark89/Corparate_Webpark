<?php
require_once __DIR__ . '/includes/functions.php';

try {
    $db = db();
    $sql = "ALTER TABLE `article` 
            ADD COLUMN `slug_en` VARCHAR(255) UNIQUE COMMENT 'URL ภาษาอังกฤษ',
            ADD COLUMN `meta_title_en` VARCHAR(255) COMMENT 'SEO Title EN',
            ADD COLUMN `meta_keywords_en` VARCHAR(255) COMMENT 'SEO Keywords EN',
            ADD COLUMN `meta_description_en` TEXT COMMENT 'SEO Description EN',
            ADD COLUMN `source_url` VARCHAR(255) COMMENT 'ที่มาของบทความ';";
    $db->exec($sql);
    echo "Migration successful.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
