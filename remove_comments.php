<?php
$dirs = [
    'c:/xampp/htdocs/Corparate_Webpark/frontend/app/views',
    'c:/xampp/htdocs/Corparate_Webpark/admin'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $f = $file->getPathname();
            $c = file_get_contents($f);
            
            // Remove HTML comments containing Thai characters
            $nc = preg_replace_callback('/<!--(.*?)-->/s', function($m) {
                return preg_match('/[\x{0E00}-\x{0E7F}]/u', $m[1]) ? '' : $m[0];
            }, $c);
            
            // Remove CSS block comments containing Thai characters
            $nc = preg_replace_callback('/\/\*(.*?)\*\//s', function($m) {
                return preg_match('/[\x{0E00}-\x{0E7F}]/u', $m[1]) ? '' : $m[0];
            }, $nc);
            
            // Remove empty lines that might have been left behind (optional, can leave them)
            $nc = preg_replace('/^\h*\r?\n/m', '', $nc);
            
            if ($nc !== $c) {
                file_put_contents($f, $nc);
                echo "Cleaned: $f\n";
            }
        }
    }
}
echo "Done!\n";
