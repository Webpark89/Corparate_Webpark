<?php
$svgFile = 'frontend/public/assets/images/HeroHome.svg';
$logoFile = 'frontend/public/assets/images/logo.png';

if (!file_exists($svgFile) || !file_exists($logoFile)) {
    die("Files not found\n");
}

$svgContent = file_get_contents($svgFile);
$logoData = file_get_contents($logoFile);
$base64Logo = 'data:image/png;base64,' . base64_encode($logoData);

// Find <image id="image1_781_577" ... xlink:href="data:image/png;base64,...."/>
$pattern = '/(<image\s+id="image1_781_577"[^>]+xlink:href=")([^"]+)(")/s';

$count = 0;
$newSvgContent = preg_replace($pattern, '${1}' . $base64Logo . '${3}', $svgContent, -1, $count);

if ($count > 0) {
    file_put_contents($svgFile, $newSvgContent);
    echo "Replaced base64 string successfully. Replacements: $count\n";
} else {
    echo "Could not find the pattern to replace.\n";
}
?>
