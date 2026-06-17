<?php

declare(strict_types=1);

return [
    '/' => [HomeController::class, 'index'],
    '/article' => [HomeController::class, 'article'],
    '/articles' => [HomeController::class, 'article'],
    '/portfolio' => [HomeController::class, 'portfolio'],
    '/services' => [HomeController::class, 'services'],
    '/service-detail' => [HomeController::class, 'serviceDetail'],
    '/service/{service}/{feature}' => [HomeController::class, 'serviceFeature'],
    '/erp' => [HomeController::class, 'erp'],
    '/about' => [HomeController::class, 'about'],
    '/contact' => [HomeController::class, 'contact'],
];
