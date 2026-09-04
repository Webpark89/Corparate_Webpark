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
    '/article/{slug}' => [HomeController::class, 'article'],
    '/articles' => [HomeController::class, 'article'],
    '/articles/{slug}' => [HomeController::class, 'article'],
    '/article-detail-mockup' => [HomeController::class, 'articleDetailMockup'],
    '/portfolio' => [HomeController::class, 'portfolio'],
    '/portfolio/{slug}' => [HomeController::class, 'portfolio'],
    '/services' => [HomeController::class, 'services'],
    '/services/digital-platform' => [HomeController::class, 'serviceDigitalPlatform'],
    '/services/online-marketing' => [HomeController::class, 'serviceOnlineMarketing'],
    '/services/creative-design' => [HomeController::class, 'serviceCreativeDesign'],
    '/services/{service}' => [HomeController::class, 'serviceDetail'],
    '/services/{service}/{feature}' => [HomeController::class, 'serviceFeature'],
    '/service' => [HomeController::class, 'services'],
    '/service/digital-platform' => [HomeController::class, 'serviceDigitalPlatform'],
    '/service/online-marketing' => [HomeController::class, 'serviceOnlineMarketing'],
    '/service/creative-design' => [HomeController::class, 'serviceCreativeDesign'],
    '/service-detail' => [HomeController::class, 'serviceDetail'],
    '/service/{service}' => [HomeController::class, 'serviceDetail'],
    '/service/{service}/{feature}' => [HomeController::class, 'serviceFeature'],
    '/erp' => [HomeController::class, 'erp'],
    '/about' => [HomeController::class, 'about'],
    '/contact' => [HomeController::class, 'contact'],
];
