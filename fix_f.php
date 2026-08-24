<?php
$content = file_get_contents('frontend/app/views/components/footer.php');
$content = str_replace('/href\ => route_url\(\\'/\\" . \\'', 'href' => route_url(\'/', $content);
$content = str_replace('\\' . \\"\'\)', '\')', $content);
$content = str_replace('\\' . \\"'\) . \'', '\') . \'', $content);
file_put_contents('frontend/app/views/components/footer.php', $content);
