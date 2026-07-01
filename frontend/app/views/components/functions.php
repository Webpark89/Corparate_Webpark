<?php
declare(strict_types=1);

function loadLanguage(string $lang = 'th'): array {
    $file = __DIR__ . "/lang_{$lang}.php";
    if (file_exists($file)) {
        return include $file;
    }
    return include __DIR__ . "/lang_th.php"; // default ภาษาไทย
}
