<?php
require "frontend/app/core/helpers.php";
require "frontend/app/core/Router.php";

$routes = require "frontend/routes.php";
echo "Keys: \n";
foreach(array_keys($routes) as $k) {
    echo $k . " (" . bin2hex($k) . ")\n";
}
$testKey = "/หน้าแรก";
echo "TestKey: " . $testKey . " (" . bin2hex($testKey) . ")\n";
echo "Exists? " . (isset($routes[$testKey]) ? "yes" : "no") . "\n";

