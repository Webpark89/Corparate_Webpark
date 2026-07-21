<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

// Initialize language early to ensure cookie is set before headers are sent
if (function_exists('getCurrentLang')) {
    getCurrentLang();
}

