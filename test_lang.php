<?php
require "frontend/app/views/components/functions.php";
function app_base_url() { return "/Corparate_Webpark"; }
$_SERVER["HTTPS"]="off";
$_SERVER["HTTP_HOST"]="localhost:8080";
$_SERVER["REQUEST_URI"]="/Corparate_Webpark/home/en";
echo current_url_with_lang("th") . "\n";
$_SERVER["REQUEST_URI"]="/Corparate_Webpark/หน้าแรก/th";
echo current_url_with_lang("en") . "\n";

