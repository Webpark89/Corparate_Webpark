<?php

declare(strict_types=1);

/**
 * WEBPARK Frontend — Application configuration.
 *
 * Returned as an associative array and loaded into APP_CONFIG at bootstrap.
 * Company contact details here serve as fallbacks when DB settings are empty.
 *
 * @return array<string, mixed>
 */
return [
    'app' => [
        'name' => 'webpark',
        /** Base path for routing — must match Apache/nginx alias or subdirectory. */
        'base_url' => '/Corparate_Webpark',
        'asset_base_url' => '/Corparate_Webpark/frontend/public',
        'description' => 'Lightweight MVC refactor for the WEBPARK site.',
    ],
    'company' => [
        'name' => 'WEBPARK Co., Ltd.',
        'tagline' => 'Enterprise software, ERP, AI, and digital product delivery.',
        'contact' => [
            'email' => 'oraphan@webpark.co.th',
            'phone' => '095 539 2666',
            'address' => '525/89 ซอยลาดพร้าว126 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310',
        ],
        'hours' => 'จันทร์ – ศุกร์ · 9:00 – 18:00',
    ],
];
