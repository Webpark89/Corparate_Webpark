<?php
$f = "frontend/app/views/components/footer.php";
$c = file_get_contents($f);
$c = preg_replace("/route_url\(''\/\" \. '([^'']+)' \. \"''\)/", "route_url(''/$1'')", $c);
$c = str_replace(" . ''", "", $c);
file_put_contents($f, $c);

