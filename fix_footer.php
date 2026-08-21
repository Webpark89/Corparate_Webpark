<?php
\ = 'frontend/app/views/components/footer.php';
\ = file_get_contents(\);
// Replace route_url('/" . 'xyz' . "') with route_url('/xyz')
\ = preg_replace('/\\'\\/\\" \\. \\'([a-zA-Z0-9_\\-\\/]+)\\' \\. \\"\\'/s', '\\'/\\\'', \);
file_put_contents(\, \);
