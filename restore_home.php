<?php
exec('git checkout HEAD -- frontend/app/views/pages/home.php 2>&1', $output, $return_var);
echo "Return: $return_var\n";
echo implode("\n", $output);
?>
