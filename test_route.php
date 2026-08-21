<?php
require "frontend/app/views/components/functions.php";
require "frontend/app/core/helpers.php";
$_SERVER["HTTPS"]="off";
$_SERVER["HTTP_HOST"]="localhost:8080";
$_GET["lang"]="th";
$_COOKIE["lang"]="th";

$currentLang = "th";
$navItems = [
    ["path" => $currentLang === "th" ? "/หน้าแรก" : "/home", "label" => "test", "page" => "home"]
];
echo route_url($navItems[0]["path"]);

