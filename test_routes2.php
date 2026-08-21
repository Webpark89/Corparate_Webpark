<?php
$routes = require "frontend/routes.php";
foreach ($routes as $k => $v) {
    echo bin2hex($k) . " " . $k . "\n";
}

