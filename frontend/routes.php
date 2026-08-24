<?php

declare(strict_types=1);

/**
 * Frontend route map — path => [ControllerClass, actionMethod].
 *
 * Dynamic segments use {paramName} syntax (see Router::dispatchDynamicRoute).
 */
return [
    '/' => [HomeController::class, 'index'],
    '/article' => [HomeController::class, 'article'],
    '/articles' => [HomeController::class, 'article'],
    '/article-detail-mockup' => [HomeController::class, 'articleDetailMockup'],
    '/portfolio' => [HomeController::class, 'portfolio'],
    '/services' => [HomeController::class, 'services'],
    '/services/digital-platform' => [HomeController::class, 'serviceDigitalPlatform'],
    '/services/online-marketing' => [HomeController::class, 'serviceOnlineMarketing'],
    '/services/creative-design' => [HomeController::class, 'serviceCreativeDesign'],
    '/service-detail' => [HomeController::class, 'serviceDetail'],
    '/service/{service}/{feature}' => [HomeController::class, 'serviceFeature'],
    '/erp' => [HomeController::class, 'erp'],
    '/about' => [HomeController::class, 'about'],
    '/contact' => [HomeController::class, 'contact'],
    '/contact/submit' => [HomeController::class, 'contactSubmit'],
    '/privacy-policy' => [HomeController::class, 'privacyPolicy'],
    '/privacy-policy.php' => [HomeController::class, 'privacyPolicy'],
];
