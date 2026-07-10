<?php

declare(strict_types=1);

$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-6.png');
$ctaImage = asset_url('images/bg-cta.jpg');

/**
 * ERP product page view — modules, benefits, and portfolio showcase.
 */


 $mockModules = [
    [
        'id' => 1,
        'name_th' => 'ระบบบริหารการขาย',
        'name_en' => 'Sales Management',
        'description_th' => 'จัดการงานขาย กำหนดราคา บริการลูกค้า และติดตามสถานะการขาย',
        'description_en' => 'Manage sales operations, pricing, customer service, and sales tracking.',
        'icon' => 'ERP_10.svg'
    ],
    [
        'id' => 2,
        'name_th' => 'ระบบจัดซื้อ',
        'name_en' => 'Purchase Management',
        'description_th' => 'เพิ่มประสิทธิภาพการจัดซื้อและการบริหารผู้ขาย',
        'description_en' => 'Procurement efficiency and supplier management.',
        'icon' => 'ERP_11.svg'
    ],
    [
        'id' => 3,
        'name_th' => 'ระบบบริหารสินค้าคงคลัง',
        'name_en' => 'Stock Management',
        'description_th' => 'ควบคุมสต็อกสินค้า ตรวจสอบการเคลื่อนไหว และแจ้งเตือนเมื่อถึงจุดสั่งซื้อ',
        'description_en' => 'Inventory control, stock monitoring, and reorder alerts.',
        'icon' => 'ERP_12.svg'
    ],
    [
        'id' => 4,
        'name_th' => 'ระบบบัญชีและการเงิน',
        'name_en' => 'Accounting & Finance',
        'description_th' => 'บันทึกรายรับรายจ่าย ออกเอกสารทางการเงิน และสรุปงบการเงิน',
        'description_en' => 'Financial and accounting processes, invoicing, and reporting.',
        'icon' => 'ERP_13.svg'
    ],
    [
        'id' => 5,
        'name_th' => 'ระบบการผลิต',
        'name_en' => 'Production Management',
        'description_th' => 'วางแผนการผลิต ควบคุมทรัพยากร ลดต้นทุน และเพิ่มประสิทธิภาพ',
        'description_en' => 'Production planning, resource control, cost reduction, efficiency.',
        'icon' => 'ERP_14.svg'
    ],
    [
        'id' => 6,
        'name_th' => 'ระบบบริหารทรัพยากรบุคคล',
        'name_en' => 'Human Resource Management',
        'description_th' => 'จัดการข้อมูลพนักงาน ประเมินผล และติดตามการทำงาน',
        'description_en' => 'Employee data management and performance evaluation.',
        'icon' => 'ERP_15.svg'
    ],
    [
        'id' => 7,
        'name_th' => 'ระบบอนุมัติและเวิร์กโฟลว์',
        'name_en' => 'Workflow Approval',
        'description_th' => 'กำหนดขั้นตอนการอนุมัติเอกสารและงานต่างๆ เพิ่มความโปร่งใส',
        'description_en' => 'Approval processes and workflow control.',
        'icon' => 'ERP_16.svg'
    ],
    [
        'id' => 8,
        'name_th' => 'ระบบลูกค้าสัมพันธ์',
        'name_en' => 'Customer Relationship Management',
        'description_th' => 'จัดการข้อมูลลูกค้าและติดตามความสัมพันธ์เพื่อเพิ่มโอกาสการขาย',
        'description_en' => 'Customer data and relationship tracking.',
        'icon' => 'ERP_17.svg'
    ],
    [
        'id' => 9,
        'name_th' => 'ระบบควบคุมคุณภาพ',
        'name_en' => 'Quality Control',
        'description_th' => 'ตรวจสอบคุณภาพการผลิต ลดของเสีย และรักษามาตรฐานสินค้า',
        'description_en' => 'Production quality assurance and inspection.',
        'icon' => 'ERP_18.svg'
    ],
    [
        'id' => 10,
        'name_th' => 'ระบบซ่อมบำรุง',
        'name_en' => 'Maintenance',
        'description_th' => 'จัดการการซ่อมบำรุงเครื่องจักร ลด Downtime และยืดอายุการใช้งาน',
        'description_en' => 'Equipment maintenance and downtime reduction.',
        'icon' => 'ERP_19.svg'
    ],
];


$mockErpPortfolios = [
    [
        'id' => 1,
        'title' => t('erp.case_factory_title') !== 'erp.case_factory_title' ? t('erp.case_factory_title') : (getCurrentLang() === 'th' ? 'ระบบ ERP บริหารโรงงานผลิตชิ้นส่วน' : 'ERP System for Parts Manufacturing Plant'),
        'description' => t('erp.case_factory_desc') !== 'erp.case_factory_desc' ? t('erp.case_factory_desc') : (getCurrentLang() === 'th' ? 'พัฒนาระบบ ERP ครบวงจร เชื่อมโยงฝ่ายจัดซื้อ ฝ่ายผลิต และบัญชี ลดข้อผิดพลาดและต้นทุนสูญเปล่ากว่า 30%' : 'Developed a complete ERP system connecting procurement, production, and accounting'),
        'image_path' => 'images/erp.png'
    ],
    [
        'id' => 2,
        'title' => t('erp.case_retail_title') !== 'erp.case_retail_title' ? t('erp.case_retail_title') : (getCurrentLang() === 'th' ? 'ระบบบริหารจัดการสต็อกธุรกิจค้าปลีก' : 'Retail Business Stock Management System'),
        'description' => t('erp.case_retail_desc') !== 'erp.case_retail_desc' ? t('erp.case_retail_desc') : (getCurrentLang() === 'th' ? 'เชื่อมต่อข้อมูลหลายสาขาแบบ Real-time พร้อมระบบ POS และสรุปยอดขายรายวันอัตโนมัติ' : 'Connected multi-branch data in real time with a POS system'),
        'image_path' => 'images/bg-cta.jpg'
    ],
    [
        'id' => 3,
        'title' => t('erp.case_logistics_title') !== 'erp.case_logistics_title' ? t('erp.case_logistics_title') : (getCurrentLang() === 'th' ? 'แพลตฟอร์มบริหารงานโลจิสติกส์' : 'Logistics Management Platform'),
        'description' => t('erp.case_logistics_desc') !== 'erp.case_logistics_desc' ? t('erp.case_logistics_desc') : (getCurrentLang() === 'th' ? 'ระบบติดตามสถานะการขนส่ง เชื่อมต่อกับคลังสินค้า พร้อมแดชบอร์ดสรุปประสิทธิภาพการจัดส่ง' : 'A shipment tracking system connected to warehouses'),
        'image_path' => 'images/bg-hand.jpg'
    ],
    [
        'id' => 4,
        'title' => getCurrentLang() === 'th' ? 'ระบบ CRM/HR' : 'CRM/HR System',
        'description' => getCurrentLang() === 'th' ? 'ระบบจัดการลีด และ ระบบจัดทรัพยากรมนุษย์' : 'Lead management and human resource systems',
        'image_path' => 'images/bg-hand.jpg'
    ]
];

// ใช้ Mock Data แทน Database ชั่วคราว
$modulesData = $mockModules;
$erpPortfolios = $mockErpPortfolios;
?>
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-entrance {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    /* 1. แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง (Entrance) */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0; /* ซ่อนไว้ก่อนเริ่ม */
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 2. แอนิเมชันสำหรับตัวอักษรสีเหลือบ (Gradient Flow) */
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }

    /* คลาสหน่วงเวลา เพื่อให้เนื้อหาไล่ลำดับกันขึ้นมา */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }

    .scrollbar-none::-webkit-scrollbar {
        display: none; /* สำหรับ Chrome, Safari และ Opera */
    }
    .scrollbar-none {
        -ms-overflow-style: none;  /* สำหรับ IE และ Edge */
        scrollbar-width: none;  /* สำหรับ Firefox */
    }
</style>



<!-- <section class="relative font-sans bg-gradient-to-b from-blue-50/80 to-white overflow-hidden pt-24 pb-20 lg:pt-32 lg:pb-28">
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="animate-entrance inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-200 bg-white mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            <span class="text-xs md:text-sm font-bold text-primary uppercase tracking-wide">Enterprise Resource Planning</span>
        </div>

        <h1 class="animate-entrance delay-100 text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.2] text-[#022862] mb-6 tracking-tight">
            ยกระดับองค์กรด้วยระบบ <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">ERP</span><br class="hidden sm:block">
            เชื่อมต่อทุกแผนกให้เป็นหนึ่งเดียว
        </h1>

        <p class="animate-entrance delay-200 text-base md:text-lg text-slate-500 max-w-3xl mx-auto leading-relaxed mb-10 font-medium">
            ระบบบริหารจัดการทรัพยากรองค์กรที่ออกแบบมาเพื่อธุรกิจของคุณโดยเฉพาะ ช่วยลดต้นทุน 
            เพิ่มประสิทธิภาพการทำงาน และเปลี่ยนข้อมูลที่ซับซ้อนให้เป็นการตัดสินใจที่แม่นยำ
        </p>

        <div class="animate-entrance delay-300 flex flex-wrap items-center justify-center gap-4">
            <a href="#modules" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                ดูโมดูลทั้งหมด
            </a>
            <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-[#022862] border border-slate-200 text-sm font-semibold rounded-full hover:bg-slate-50 transition-all shadow-sm hover:-translate-y-0.5">
                ปรึกษาผู้เชี่ยวชาญ
            </a>
        </div>
    </div>
</section> -->

<section class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover object-center opacity-100 mix-blend-screen">
            
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>
    <style>
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideLeft {
            0% { opacity: 0; transform: translateX(50px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        .animate-entrance-up { opacity: 0; animation: fadeSlideUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-entrance-left { opacity: 0; animation: fadeSlideLeft 1s cubic-bezier(0.16,1,0.3,1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-scroll { animation: scroll 20s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }
    </style>

    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <!-- Mobile Background Image (Only covers this Hero container) -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
                class="w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-white/40"></div>
            <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            
            <div class="max-w-2xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6 hidden sm:block">
                        <ol class="inline-flex items-center space-x-2 text-sm md:text-base font-medium text-slate-500">
                            <li>
                                <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                    <?= e(t('common.nav_home')) ?>
                                </a>
                            </li>
                            
                            <li>
                                <span class="text-slate-400" style="margin: 0 4px;">/</span>
                            </li>
                            
                            <li aria-current="page">
                                <span class="text-slate-400"><?= e(t('common.nav_erp')) ?></span>
                            </li>
                        </ol>
                    </nav>
                
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">
                        ERP Systems
                    </span><br>

                    <span class="text-2xl md:text-3xl lg:text-5xl font-medium bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 py-3" style="animation-delay: -3s;">
                        <?= e(t('erp.modules_intro')) ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    <?= e(t('common.articles_knowledge_summary')) ?> <br class="hidden md:block">
                    <?= e(t('common.articles_coverage_summary')) ?><br class="hidden md:block">
                    <?= e(t('common.articles_growth_summary')) ?>
                </p>
                <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/service')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(t('common.cta_view_services')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#about" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video')) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-6 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16">
        <div class="w-full rounded-[1rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">

            <div class="group flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50 cursor-pointer">
                <div>
                    <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        <?= e(t('common.about_us_heading')) ?>
                    </h2>
                    <span class="text-primary font-bold text-md block mb-3">
                        <?= e(t('common.we_are_partner')) ?><br><?= e(t('common.in_technology')) ?>
                    </span>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        <?= e(t('common.partner_description')) ?>
                    </p>
                </div>
            </div>

            <div class="flex-[4] grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php
                $serviceCards = [
                    ['icon' => asset_url('images/ERP_1.svg'), 'title' => 'ERP / ERM',        'desc' => t('common.solution_org_control'), 'href' => route_url('/erp')],
                    ['icon' => asset_url('images/ERP_2.svg'), 'title' => 'Digital Platform', 'desc' => t('common.solution_digital_platform'),              'href' => '#'],
                    ['icon' => asset_url('images/ERP_3.svg'), 'title' => 'Online Marketing', 'desc' => t('common.solution_online_marketing'),   'href' => '#'],
                    ['icon' => asset_url('images/ERP_4.svg'), 'title' => 'Creative / Design','desc' => t('common.solution_brand_design'),    'href' => '#'],
                ];
                $lastIdx = count($serviceCards) - 1;
                foreach ($serviceCards as $i => $card):
                    $borderClass = '';
                    if ($i < $lastIdx) {
                        $borderClass .= ' border-b';
                    }
                    if ($i < 2) {
                        $borderClass .= ' sm:border-b';
                    } else {
                        $borderClass .= ' sm:border-b-0';
                    }
                    if ($i % 2 === 0) {
                        $borderClass .= ' sm:border-r';
                    } else {
                        $borderClass .= ' sm:border-r-0';
                    }
                    $borderClass .= ' lg:border-b-0';
                    if ($i < 3) {
                        $borderClass .= ' lg:border-r';
                    } else {
                        $borderClass .= ' lg:border-r-0';
                    }
                ?>
                    <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                        <div>
                            <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                                <img src="<?= e($card['icon']) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                            </div>
                            <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-normal tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                                <?= e($card['title']) ?>
                            </h2>
                            <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                                <?= e($card['desc']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div> -->

</section>

<section class="bg-white py-8 lg:py-24">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-6 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16">
        <div class="w-full rounded-[1rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">

            <div class="group flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50 cursor-pointer">
                <div>
                    <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        <?= e(t('common.about_us_heading')) ?>
                    </h2>
                    <span class="text-primary font-bold text-md block mb-3">
                        <?= e(t('common.we_are_partner')) ?><br><?= e(t('common.in_technology')) ?>
                    </span>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        <?= e(t('common.partner_description')) ?>
                    </p>
                </div>
            </div>

            <div class="flex-[4] grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php
                $serviceCards = [
                    ['icon' => asset_url('images/ERP_1.svg'), 'title' => 'ERP / ERM',        'desc' => t('common.solution_org_control'), 'href' => route_url('/erp')],
                    ['icon' => asset_url('images/ERP_2.svg'), 'title' => 'Digital Platform', 'desc' => t('common.solution_digital_platform'),              'href' => '#'],
                    ['icon' => asset_url('images/ERP_3.svg'), 'title' => 'Online Marketing', 'desc' => t('common.solution_online_marketing'),   'href' => '#'],
                    ['icon' => asset_url('images/ERP_4.svg'), 'title' => 'Creative / Design','desc' => t('common.solution_brand_design'),    'href' => '#'],
                ];
                $lastIdx = count($serviceCards) - 1;
                foreach ($serviceCards as $i => $card):
                    $borderClass = '';
                    if ($i < $lastIdx) {
                        $borderClass .= ' border-b';
                    }
                    if ($i < 2) {
                        $borderClass .= ' sm:border-b';
                    } else {
                        $borderClass .= ' sm:border-b-0';
                    }
                    if ($i % 2 === 0) {
                        $borderClass .= ' sm:border-r';
                    } else {
                        $borderClass .= ' sm:border-r-0';
                    }
                    $borderClass .= ' lg:border-b-0';
                    if ($i < 3) {
                        $borderClass .= ' lg:border-r';
                    } else {
                        $borderClass .= ' lg:border-r-0';
                    }
                ?>
                    <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                        <div>
                            <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                                <img src="<?= e($card['icon']) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                            </div>
                            <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-normal tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                                <?= e($card['title']) ?>
                            </h2>
                            <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                                <?= e($card['desc']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- <section class="bg-white py-16 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ลดต้นทุน เพิ่มกำไร</h3>
                <p class="text-sm text-slate-500 leading-relaxed">มองเห็นรอยรั่วไหลของต้นทุน ควบคุมงบประมาณและสต็อกสินค้าได้อย่างมีประสิทธิภาพสูงสุด ลดความสูญเสียที่ไม่จำเป็น</p>
            </div>
            
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ข้อมูล Real-Time</h3>
                <p class="text-sm text-slate-500 leading-relaxed">ข้อมูลทุกแผนกเชื่อมโยงถึงกัน อัปเดตแบบวินาทีต่อวินาที ทำให้ผู้บริหารตัดสินใจได้ทันท่วงทีบนฐานข้อมูลที่ถูกต้อง</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ลดความซ้ำซ้อน</h3>
                <p class="text-sm text-slate-500 leading-relaxed">ทำงานผ่านแพลตฟอร์มเดียว บอกลาการใช้ Excel หลายไฟล์หรือระบบที่ไม่เชื่อมต่อกัน ลดข้อผิดพลาดจากการกรอกข้อมูลซ้ำ (Human Error)</p>
            </div>
        </div>
    </div>
</section> -->

<section id="modules" class="bg-slate-50 py-10 lg:py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 tracking-tight mb-4">
                ERP modules
            </h2>
            <span class="text-blue-400 font-bold text-md md:text-md uppercase mb-3 block"><?= e(t('erp.process_coverage_title') !== 'erp.process_coverage_title' ? t('erp.process_coverage_title') : (getCurrentLang() === 'th' ? 'ระบบครอบคลุมทุกกระบวนการทำงาน' : 'A System That Covers Every Process')) ?></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 lg:gap-6">
            <?php foreach ($modulesData as $module): ?>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:bg-primary hover:border-primary transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden">
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-blue-50/60 rounded-full flex items-center justify-center mb-6 mx-auto md:mx-0 transition-colors duration-300">
                            <img src="<?= e(asset_url('images/' . $module['icon'])) ?>" alt="<?= e($module['name_en']) ?>" class="w-10 h-10 object-contain transition-all duration-300" />
                        </div>
                        <h3 class="text-center md:text-left text-lg font-bold text-[#043B94] mb-3 group-hover:text-primary transition-colors">
                            <?= e(getCurrentLang() === 'th' ? $module['name_th'] : $module['name_en']) ?> 
                        </h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            <?= e(getCurrentLang() === 'th' ? $module['description_th'] : $module['description_en']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- <section class="bg-white py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-primary mb-3 uppercase tracking-wide">QUALITY CONTROL</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    ควบคุมคุณภาพผลผลิตและกระบวนการผลิตได้มาตรฐานและตรวจสอบได้
                </p>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ตั้งมาตรฐานคุณภาพ (QC Plan)
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ตรวจสอบคุณภาพ / บันทึกผล 3 ขั้นตอน
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        จัดการข้อบกพร่องพร้อมแนวทางการแก้ไข (CAPA)
                    </li>
                </ul>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-primary mb-3 uppercase tracking-wide">MAINTENANCE</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    บริหารจัดการงานบำรุงรักษาเครื่องจักรและอุปกรณ์ ลด Downtime เพิ่มประสิทธิภาพการทำงาน
                </p>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        แผนบำรุงรักษาเชิงป้องกัน (PM)
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        แจ้งซ่อม / ติดตามงาน / ประวัติการซ่อม
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ควบคุมค่าใช้จ่ายและเวลาการบำรุงรักษา
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section> -->

<section class="bg-slate-50 py-10 lg:py-10 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-extrabold text-center text-[#022862] tracking-tight py-10">
            <?= e(t('erp.cta_banner_title') !== 'erp.cta_banner_title' ? t('erp.cta_banner_title') : (getCurrentLang() === 'th' ? 'ERP ที่ช่วยยกระดับธุรกิจของคุณ' : 'ERP That Elevates Your Business')) ?>
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-6">
            <?php
            $erpBenefits = [
                [
                    'title' => t('erp.benefit_complete_data_title') !== 'erp.benefit_complete_data_title' ? t('erp.benefit_complete_data_title') : (getCurrentLang() === 'th' ? 'ข้อมูลครบถ้วน' : 'Complete Data'),
                    'desc' => getCurrentLang() === 'th' ? 'รวมทุกแผนกไว้ในระบบเดียว' : 'All departments in one system',
                    'icon' => asset_url('images/ERP_5.svg'),
                ],
                [
                    'title' => t('erp.less_duplication_title') !== 'erp.less_duplication_title' ? t('erp.less_duplication_title') : (getCurrentLang() === 'th' ? 'ลดงานซ้ำซ้อน' : 'Less Duplication'),
                    'desc' => getCurrentLang() === 'th' ? 'เพิ่มประสิทธิภาพการทำงาน' : 'Increase working efficiency',
                    'icon' => asset_url('images/ERP_6.svg'),
                ],
                [
                    'title' => t('erp.benefit_realtime_data_title') !== 'erp.benefit_realtime_data_title' ? t('erp.benefit_realtime_data_title') : (getCurrentLang() === 'th' ? 'ข้อมูลเรียลไทม์' : 'Real-Time Data'),
                    'desc' => t('erp.benefit_realtime_data_desc') !== 'erp.benefit_realtime_data_desc' ? t('erp.benefit_realtime_data_desc') : (getCurrentLang() === 'th' ? 'ตัดสินใจได้แม่นยำและรวดเร็ว' : 'Make decisions accurately and quickly'),
                    'icon' => asset_url('images/ERP_7.svg'),
                ],
                [
                    'title' => t('erp.benefit_risk_control_title') !== 'erp.benefit_risk_control_title' ? t('erp.benefit_risk_control_title') : (getCurrentLang() === 'th' ? 'ควบคุมความเสี่ยง' : 'Risk Control'),
                    'desc' => t('erp.benefit_risk_control_desc') !== 'erp.benefit_risk_control_desc' ? t('erp.benefit_risk_control_desc') : (getCurrentLang() === 'th' ? 'ตรวจสอบและติดตามได้ทุกขั้นตอน' : 'Audit and track every step'),
                    'icon' => asset_url('images/ERP_8.svg'),
                ],
                [
                    'title' => t('erp.benefit_scalable_title') !== 'erp.benefit_scalable_title' ? t('erp.benefit_scalable_title') : (getCurrentLang() === 'th' ? 'ขยายได้ตามธุรกิจ' : 'Scalable'),
                    'desc' => t('erp.benefit_scalable_desc') !== 'erp.benefit_scalable_desc' ? t('erp.benefit_scalable_desc') : (getCurrentLang() === 'th' ? 'รองรับการเติบโตในอนาคต' : 'Support future growth'),
                    'icon' => asset_url('images/ERP_9.svg'),
                ],
            ];
            ?>
            <?php foreach ($erpBenefits as $benefit): ?>
                <div class="bg-white rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 mx-auto bg-blue-50/70 rounded-full flex items-center justify-center mb-4">
                        <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain">
                    </div>
                    <h4 class="text-sm font-bold text-[#043B94] mb-1"><?= e($benefit['title']) ?></h4>
                    <p class="text-xs text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($erpPortfolios)): ?>
<section class="bg-white py-10 lg:py-20 font-sans">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-200 pb-5 mb-10 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold leading-none tracking-tight text-[#022862] m-0">
                    <?= e(t('erp.portfolio_section_title') !== 'erp.portfolio_section_title' ? t('erp.portfolio_section_title') : (getCurrentLang() === 'th' ? 'ผลงานพัฒนาระบบ ERP' : 'ERP System Development Portfolio')) ?>
                </h2>
            </div>
        </div>

        <div id="erp-portfolio-scroll-container" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none gap-8 pb-6 -mx-4 px-4 md:mx-0 md:px-0 md:grid md:grid-cols-4 lg:grid-cols-4 md:overflow-visible md:snap-none">
            <?php foreach ($erpPortfolios as $port): 
                $imgSrc = asset_url($port['image_path']);
                $detailUrl = isset($port['slug']) ? route_url('/portfolio/' . $port['slug']) : route_url('/portfolio');
            ?>
                <a href="<?= e($detailUrl) ?>" class="block w-[85vw] md:w-auto shrink-0 snap-center">
                    <article class="group w-full h-full rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col hover:-translate-y-1">
                    <div class="h-[220px] w-full overflow-hidden bg-slate-100 relative">
                        <img src="<?= e($imgSrc) ?>" alt="<?= e($port['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        <span class="absolute bottom-3 left-3 bg-primary/95 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">ERP SYSTEM</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-base font-bold text-[#0b1b42] leading-snug line-clamp-2 group-hover:text-primary transition-colors mb-3">
                            <?= e($port['title']) ?>
                        </h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed line-clamp-3 mb-5 flex-1">
                            <?= e($port['description']) ?>
                        </p>
                        <div class="mt-auto pt-4 border-t border-slate-50"> 
                            <span
                                class="inline-flex items-center justify-center
                                    rounded-full border-2 border-primary
                                    px-3 py-1
                                    text-sm font-medium
                                    text-primary
                                    hover:bg-primary hover:text-white
                                    transition-colors">
                                ERP System
                            </span>
                        </div>
                    </div>
                </article>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center gap-2 mt-2 md:hidden" id="erp-portfolio-dots">
            <?php for ($i = 0; $i < count($erpPortfolios); $i++): ?>
                <button 
                    class="w-2.5 h-2.5 rounded-full transition-all duration-300 <?= $i === 0 ? 'bg-primary w-5' : 'bg-slate-300' ?>" 
                    aria-label="Go to slide <?= $i + 1 ?>"
                    onclick="scrollToErpPortfolio(<?= $i ?>)"
                ></button>
            <?php endfor; ?>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('erp-portfolio-scroll-container');
            const dots = document.querySelectorAll('#erp-portfolio-dots button');
            
            if (!container || dots.length === 0) return;
            
            container.addEventListener('scroll', function() {
                const scrollLeft = container.scrollLeft;
                const width = container.clientWidth;
                
                let closestIndex = 0;
                let minDiff = Infinity;
                
                const children = container.children;
                const cardElements = Array.from(children).filter(el => el.tagName === 'A');
                
                cardElements.forEach((el, index) => {
                    const diff = Math.abs(el.offsetLeft - scrollLeft - (width - el.clientWidth) / 2);
                    if (diff < minDiff) {
                        minDiff = diff;
                        closestIndex = index;
                    }
                });
                
                dots.forEach((dot, index) => {
                    if (index === closestIndex) {
                        dot.classList.add('bg-primary', 'w-5');
                        dot.classList.remove('bg-slate-300');
                    } else {
                        dot.classList.add('bg-slate-300');
                        dot.classList.remove('bg-primary', 'w-5');
                    }
                });
            });
        });

        function scrollToErpPortfolio(index) {
            const container = document.getElementById('erp-portfolio-scroll-container');
            if (!container) return;
            const cardElements = Array.from(container.children).filter(el => el.tagName === 'A');
            if (cardElements[index]) {
                cardElements[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
        </script>

    </div>
</section>
<?php endif; ?>

<!-- <section class="relative font-sans py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-[#021a4a] via-[#03245c] to-[#0b3f9e]">
        <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-r from-[#021a4a]/90 via-[#021a4a]/70 to-[#0b3f9e]/60"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-white leading-tight mb-4 drop-shadow-sm">
                    พร้อมเริ่มต้นโครงการ ERP<br class="hidden sm:block">
                    ยกระดับองค์กรของคุณแล้วหรือยัง?
                </h2>
                <p class="text-white/90 text-sm md:text-base leading-relaxed mb-8 max-w-xl">
                    ทีมผู้เชี่ยวชาญของเราพร้อมให้คำปรึกษาและออกแบบระบบ
                    ที่เหมาะสมกับธุรกิจของคุณ
                </p>
                <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-[#022862] text-sm font-bold rounded-full hover:bg-blue-50 transition-all shadow-md hover:-translate-y-0.5">
                    ปรึกษาผู้เชี่ยวชาญ
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" alt="ทีมงาน ERP"
                    class="rounded-2xl w-full h-[280px] object-cover shadow-2xl border border-white/20"
                    onerror="this.closest('div').innerHTML='<div class=&quot;w-full h-[280px] rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center&quot;><svg class=&quot;w-16 h-16 text-white/40&quot; fill=&quot;none&quot; viewBox=&quot;0 0 24 24&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;1.5&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; d=&quot;M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 16.5V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18v-1.5m-18 0V6A2.25 2.25 0 015.25 3.75h13.5A2.25 2.25 0 0121 6v10.5m-18 0h18M8.25 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z&quot;/></svg></div>'">
            </div>
        </div>
    </div>
</section> -->

<!-- <section class="bg-white py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-2 block">GET IN TOUCH</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#022862] tracking-tight mb-4">
                    ปรึกษาโซลูชัน ERP<br>กับผู้เชี่ยวชาญของเรา
                </h2>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8 max-w-md">
                    กรอกข้อมูลเพื่อให้ทีมงานติดต่อกลับ
                    และวางแผนการใช้งานระบบที่เหมาะกับธุรกิจของคุณ
                </p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 shadow-sm">
                <form action="<?= e(route_url('/contact/submit')) ?>" method="post" class="space-y-4">
                    <?php if (function_exists('csrf_field')): ?>
                        <?= csrf_field() ?>
                    <?php endif; ?>
                    <input type="hidden" name="source" value="erp_page">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="full_name" required placeholder="ชื่อ - นามสกุล"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <input type="tel" name="phone" required placeholder="เบอร์โทรศัพท์"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="email" name="email" required placeholder="อีเมล"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <input type="text" name="company" placeholder="บริษัท"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                    </div>

                    <textarea name="message" rows="4" placeholder="รายละเอียด / ความต้องการ"
                        class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"></textarea>

                    <label class="flex items-start gap-2 text-xs text-slate-500">
                        <input type="checkbox" name="consent" required class="mt-0.5 rounded border-slate-300 text-primary focus:ring-primary/40">
                        <span>
                            ฉันยินยอมตาม <a href="<?= e(route_url('/privacy-policy')) ?>" class="text-primary underline">นโยบายความเป็นส่วนตัว</a>
                            และข้อกำหนดและเงื่อนไขของเว็บไซต์
                        </span>
                    </label>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ส่งข้อมูล
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section> -->