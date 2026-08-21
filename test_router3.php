<?php
require "frontend/app/core/helpers.php";
require "frontend/app/core/Router.php";

$_SERVER["REQUEST_URI"] = "/Corparate_Webpark/%E0%B8%AB%E0%B8%99%E0%B9%89%E0%B8%B2%E0%B9%81%E0%B8%A3%E0%B8%81/th";
$_SERVER["HTTP_HOST"] = "localhost:8080";

$routes = require "frontend/routes.php";
$router = new Router($routes);
$reflection = new ReflectionClass($router);
$method = $reflection->getMethod("resolveRequestPath");
$method->setAccessible(true);
$path = $method->invoke($router, null);
echo "Resolved: " . $path . "\n";
echo "Exists? " . (isset($routes[$path]) ? "yes" : "no") . "\n";

