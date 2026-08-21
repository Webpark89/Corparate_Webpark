<?php
function app_base_url() { return "/Corparate_Webpark"; }
$requestUri = "/Corparate_Webpark/" . urlencode("หน้าแรก") . "/th";
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$requestPath = urldecode($requestPath);
$basePath = app_base_url();
if ($basePath !== "" && str_starts_with($requestPath, $basePath)) {
    $requestPath = substr($requestPath, strlen($basePath)) ?: "/";
}
$requestPath = "/" . trim($requestPath, "/");
if (preg_match("#/(th|en)$#", $requestPath, $matches)) {
    $requestPath = substr($requestPath, 0, -strlen($matches[0]));
    if ($requestPath === "") $requestPath = "/";
}
echo "Resolved path: " . $requestPath . "\n";
$routes = require "frontend/routes.php";
if (isset($routes[$requestPath])) {
    echo "Match found!\n";
} else {
    echo "No match found for: " . $requestPath . "\n";
}

