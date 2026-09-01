<?php

declare(strict_types=1);

$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/erp-hero-bg.png');
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

    /* Parallax: ขยายรูปเผื่อไว้ล่วงหน้า เพื่อไม่ให้เห็นขอบโหว่ตอนรูปเลื่อนตาม scroll */
    .hero-parallax-img {
        transform: scale(1.15);
        will-change: transform;
    }

    /* Accessibility: เคารพการตั้งค่า Reduce Motion ของผู้ใช้ */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.001ms !important;
            scroll-behavior: auto !important;
        }
    }
    @media (min-width: 1025px) {
        .desktop-erp-hero-h1 {
            font-size: 5.5rem !important;
            line-height: 1.1 !important;
        }
        .desktop-erp-hero-p {
            font-size: 1.25rem !important;
            line-height: 1.75 !important;
            max-width: 34rem !important;
        }
    }

    /* iPad Pro Custom Layout */
    @media (min-width: 1024px) and (max-width: 1279px) {
        .ipad-pro-erp-hero-h1-wrapper {
            white-space: nowrap !important;
        }
        .ipad-pro-erp-hero-h1 {
            font-size: 4.2rem !important; /* Scale down further to ensure it fits strictly on one line */
            line-height: 1 !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
            display: inline-block !important;
            white-space: nowrap !important;
        }
        .ipad-pro-erp-hero-subtitle {
            font-size: 2rem !important; /* Make subtitle fit beautifully on exactly two lines */
            line-height: 1.3 !important;
            display: inline-block !important;
            margin-top: 1rem !important;
        }
        /* Break the subtitle nicely for English */
        .ipad-pro-erp-hero-subtitle br {
            display: block !important;
        }
    }
</style>





<section id="erp-hero" class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover object-[right_center]"
            style="filter: contrast(1.18) saturate(1.25) brightness(0.97);">
            
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent z-10"></div>
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

    <div class="mx-auto w-full max-w-7xl lg:max-w-[1720px] px-6 sm:px-6 lg:px-10 pt-12 pb-24 lg:pt-40 lg:pb-32 relative z-10">
        <!-- Mobile Background Image (Only covers this Hero container) -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
                class="w-full h-full object-cover object-[75%_center] md:object-[right_center]"
                style="filter: contrast(1.18) saturate(1.25) brightness(0.97);">
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            
            <div class="max-w-2xl lg:ml-12 xl:ml-24">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6 hidden sm:block">
                        <ol class="inline-flex items-center text-sm md:text-base font-medium text-slate-500">
                            <li>
                                <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                    <?= e(t('common.nav_home')) ?>
                                </a>
                            </li>
                            
                            <li>
                                <span class="text-slate-400" style="margin: 0 4px;">/</span>
                            </li>
                            
                            <li aria-current="page">
                                <span class="text-slate-400">ERP System</span>
                            </li>
                        </ol>
                    </nav>
                
                <!-- Mobile Only Hero Content -->
                <div class="block md:hidden">
                    <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                        <span class="text-5xl font-extrabold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2">
                            <?= getCurrentLang() === 'th' ? 'ระบบ' : 'ERP' ?>
                        </span>
                        <span class="text-5xl font-extrabold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1">
                            <?= getCurrentLang() === 'th' ? 'ERP' : 'Systems' ?>
                        </span><br>

                        <span class="text-xl font-bold leading-[1.4] bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-0 pb-3 pt-1" style="animation-delay: -3s;">
                            <?= getCurrentLang() === 'th' ? 'เชื่อมต่อทุกกระบวนการธุรกิจ<br>แบบครบวงจรในแพลตฟอร์มเดียว' : 'Connecting every business process<br>end-to-end on a single platform' ?>
                        </span>
                    </h1>

                    <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-sm sm:text-base leading-relaxed max-w-lg mb-8 font-medium">
                        <?php if (getCurrentLang() === 'th'): ?>
                            ระบบบริหารจัดการทรัพยากรองค์กร (ERP) ที่ช่วยรวมข้อมูลและกระบวนการทำงานสำคัญขององค์กรไว้ในระบบเดียว ลดงานซ้ำซ้อน เพิ่มประสิทธิภาพการทำงาน และขับเคลื่อนองค์กรสู่อนาคตดิจิทัล
                        <?php else: ?>
                            Enterprise Resource Planning (ERP) systems that integrate core business processes and data into a single unified platform, eliminating duplicate work and boosting efficiency.
                        <?php endif; ?>
                    </p>
                    
                    <div class="animate-entrance-up delay-400 flex flex-col items-start gap-4">
                        <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5 whitespace-nowrap">
                            <?= getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert' ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="#about" class="inline-flex items-center gap-3 transition-all hover:-translate-y-0.5 group">
                            <div class="h-12 w-12 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 fill-current" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            <span class="text-slate-800 text-sm font-semibold group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video')) ?></span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Only Hero Content -->
                <div class="hidden md:block">
                    <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter ipad-pro-erp-hero-h1-wrapper"><span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 desktop-erp-hero-h1 ipad-pro-erp-hero-h1"><?= getCurrentLang() === 'th' ? 'ระบบ' : 'ERP' ?></span><span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1 lg:ml-2 desktop-erp-hero-h1 ipad-pro-erp-hero-h1"><?= getCurrentLang() === 'th' ? 'ERP' : 'Systems' ?></span><br><span class="text-xl md:text-2xl lg:text-4xl font-medium leading-snug bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-0 pb-3 pt-1 ipad-pro-erp-hero-subtitle" style="animation-delay: -3s;"><?= getCurrentLang() === 'th' ? 'เชื่อมต่อทุกกระบวนการธุรกิจ<br>แบบครบวงจรในแพลตฟอร์มเดียว' : 'Connecting every business process<br>end-to-end on a single platform' ?></span></h1>

                    <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium desktop-erp-hero-p">
                        <?php if (getCurrentLang() === 'th'): ?>
                            รวบรวมบทความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ<br>ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI<br>และโซลูชัน ที่ช่วยพัฒนาองค์กรให้เติบโตได้อย่างยั่งยืน
                        <?php else: ?>
                            <?php 
                                $en_knowledge = e(t('common.articles_knowledge_summary'));
                                $en_knowledge = str_replace(', and business strategy', ',<br class="hidden lg:block"> and business strategy', $en_knowledge);
                                echo $en_knowledge;
                            ?> <br class="hidden xl:block">
                            <?= e(t('common.articles_coverage_summary')) ?><br class="hidden xl:block">
                            <?= e(t('common.articles_growth_summary')) ?>
                        <?php endif; ?>
                    </p>
                    <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                        <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5 whitespace-nowrap">
                            <?= getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert' ?>
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
    </div>



</section>

<style>
    @media (min-width: 760px) and (max-width: 819px) {
        .ipad-mini-erp-desc {
            font-size: 1.15rem !important;
            line-height: 1.75 !important;
            max-width: 100% !important;
        }
    }
    @media (min-width: 1280px) {
        .erp-left-col { flex: none !important; width: 440px !important; max-width: 440px !important; }
        .erp-right-col { flex: 1 !important; width: auto !important; }
    }
    @media (min-width: 1536px) {
        .erp-left-col { width: 460px !important; max-width: 460px !important; }
    }
</style>
<section class="bg-white pt-8 pb-4 lg:pt-24 lg:pb-8">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16 overflow-hidden">
        <div class="w-full rounded-2xl lg:rounded-3xl bg-white flex flex-col xl:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-slate-100 overflow-hidden">

            <div class="erp-left-col gsap-erp-about-left group flex-1 flex flex-col justify-center p-8 lg:p-10 xl:p-12 border-b xl:border-b-0 xl:border-r border-slate-100 shrink-0 bg-white transition-all duration-300 opacity-0 translate-y-10">
                <div>
                    <div class="inline-flex flex-col items-start mb-3">
                        <span class="text-primary font-extrabold text-2xl lg:text-3xl tracking-tight uppercase">
                            ERP SYSTEM
                        </span>
                        <div class="w-10 h-[3px] bg-primary mt-1.5"></div>
                    </div>
                    <h2 class="text-[#043B94] text-2xl lg:text-3xl font-bold leading-tight mb-4 transition-colors duration-300">
                        <?= getCurrentLang() === 'th' ? 'ระบบ ERP คืออะไร' : 'What is an ERP System?' ?>
                    </h2>
                    <p class="text-slate-500 text-sm lg:text-[15px] leading-relaxed max-w-md ipad-mini-erp-desc">
                        <?= getCurrentLang() === 'th' 
                            ? 'ERP คือ ระบบที่รวบรวมและเชื่อมโยงกระบวนการทำงานหลักขององค์กร ไม่ว่าจะเป็น การขาย การจัดซื้อ คลังสินค้า การเงิน การผลิต ทรัพยากรบุคคล และงานอื่น ๆ ให้ทำงานร่วมกันบนฐานข้อมูลเดียวแบบเรียลไทม์ ช่วยให้ผู้บริหารมองเห็นภาพรวม ตัดสินใจได้แม่นยำ และตอบสนองต่อการเปลี่ยนแปลงได้อย่างรวดเร็ว' 
                            : 'ERP is a system that integrates core business processes—such as sales, procurement, inventory, finance, manufacturing, and HR—to work together on a single real-time database. It helps executives see the big picture, make accurate decisions, and respond swiftly to changes.' 
                        ?>
                    </p>
                </div>
            </div>

            <div class="erp-right-col gsap-erp-about-right flex-[4] grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 w-full opacity-0 translate-y-10">
                <?php
                $serviceCards = [
                    [
                        'icon' => asset_url('images/ERP_1.svg'),
                        'title_th' => "ข้อมูลเชื่อมต่อ<br>ครบทุกแผนก",
                        'title_en' => "Connected Data<br>Across Departments",
                        'desc_th' => "ข้อมูลเป็นหนึ่งเดียว<br>ไม่ต้องทำงานซ้ำ",
                        'desc_en' => "Single source of truth<br>No duplicate work",
                    ],
                    [
                        'icon' => asset_url('images/ERP_2.svg'),
                        'title_th' => "ทำงานอัตโนมัติ<br>ลดความผิดพลาด",
                        'title_en' => "Automated Workflow<br>Reduced Errors",
                        'desc_th' => "ลดขั้นตอนงานเอกสาร<br>เพิ่มความแม่นยำ",
                        'desc_en' => "Minimize paperwork<br>Increase accuracy",
                    ],
                    [
                        'icon' => asset_url('images/ERP_3.svg'),
                        'title_th' => "มองเห็นแบบเรียลไทม์<br>ตัดสินใจได้ไว",
                        'title_en' => "Real-time Visibility<br>Fast Decisions",
                        'desc_th' => "รายงานและ Dashboard<br>อัปเดตตลอดเวลา",
                        'desc_en' => "Always-updated reports<br>and Dashboards",
                    ],
                    [
                        'icon' => asset_url('images/ERP_4.svg'),
                        'title_th' => "รองรับการเติบโต<br>ของธุรกิจ",
                        'title_en' => "Supports Growth<br>of Your Business",
                        'desc_th' => "ขยายระบบได้ตามความต้องการ<br>พร้อมเติบโตในอนาคต",
                        'desc_en' => "Scalable system<br>ready for future",
                    ],
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
                    <div class="gsap-erp-about-card relative group flex flex-col justify-center p-4 sm:p-6 lg:p-8 xl:p-10 <?= $borderClass ?> border-slate-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.06)] hover:-translate-y-1 hover:z-10 opacity-0 translate-y-10">
                         <div class="flex flex-col items-center justify-center text-center">
                             <div class="h-12 w-12 sm:h-16 sm:w-16 lg:h-20 lg:w-20 mx-auto mb-3 sm:mb-5 flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                 <img src="<?= e($card['icon']) ?>" alt="" class="h-full w-full object-contain">
                             </div>
                             <h3 class="text-[#043B94] font-bold text-sm sm:text-base lg:text-lg xl:text-xl text-center mb-2 sm:mb-3 leading-snug transition-colors duration-300 group-hover:text-blue-600">
                                 <?= getCurrentLang() === 'th' ? $card['title_th'] : $card['title_en'] ?>
                             </h3>
                             <p class="text-slate-500 text-[11px] sm:text-xs lg:text-sm leading-relaxed text-center font-normal transition-colors duration-300">
                                 <?= getCurrentLang() === 'th' ? $card['desc_th'] : $card['desc_en'] ?>
                             </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>



<section id="modules" class="bg-slate-50 py-10 lg:py-12 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 tracking-tight mb-4">
                <span class="lg:hidden">ERP modules</span>
                <span class="hidden lg:inline uppercase">ERP MODULE</span>
            </h2>
            <span class="text-blue-400 lg:text-[#043B94] font-bold text-lg md:text-xl uppercase lg:normal-case mb-3 block">
                <span class="lg:hidden"><?= e(t('erp.process_coverage_title') !== 'erp.process_coverage_title' ? t('erp.process_coverage_title') : (getCurrentLang() === 'th' ? 'ระบบครอบคลุมทุกกระบวนการทำงาน' : 'A System That Covers Every Process')) ?></span>
                <span class="hidden lg:inline"><?= e(getCurrentLang() === 'th' ? 'ครบทุกโมดูล ตอบโจทย์ทุกการทำงานขององค์กร' : 'Complete modules for all enterprise operations') ?></span>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 lg:gap-6">
            <?php foreach ($modulesData as $module): ?>
                <div class="gsap-erp-module-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:bg-primary hover:border-primary transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden opacity-0 translate-y-10">
                    
                    <div class="relative z-10 flex flex-row items-center gap-5">
                        <div class="shrink-0 w-16 h-16 bg-blue-50/60 group-hover:bg-white/20 rounded-full flex items-center justify-center transition-colors duration-300">
                            <img src="<?= e(asset_url('images/' . $module['icon'])) ?>" alt="<?= e($module['name_en']) ?>" class="w-10 h-10 object-contain group-hover:scale-110 transition-all duration-300" />
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="text-xl font-bold text-[#043B94] mb-2 group-hover:text-white transition-colors">
                                <?= e(getCurrentLang() === 'th' ? $module['name_th'] : $module['name_en']) ?> 
                            </h3>
                            <p class="text-base text-slate-500 group-hover:text-white/90 leading-relaxed transition-colors line-clamp-2 md:line-clamp-none">
                                <?= e(getCurrentLang() === 'th' ? $module['description_th'] : $module['description_en']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>



<section class="bg-slate-50 py-10 lg:py-10 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-extrabold text-center text-[#022862] tracking-tight pt-2 pb-6 md:py-10">
            <?= e(t('erp.cta_banner_title') !== 'erp.cta_banner_title' ? t('erp.cta_banner_title') : (getCurrentLang() === 'th' ? 'ERP ที่ช่วยยกระดับธุรกิจของคุณ' : 'ERP That Elevates Your Business')) ?>
        </h2>

        <style>
            @media (max-width: 639px) {
                .mobile-span-2 {
                    grid-column: span 2 / span 2 !important;
                }
            }
            @media (min-width: 768px) and (max-width: 1023px) {
                .ipad-air-grid-2 {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
                .ipad-air-span-2 {
                    grid-column: span 2 / span 2 !important;
                    display: flex !important;
                    flex-direction: row !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: left !important;
                    gap: 1.5rem !important;
                }
                .ipad-air-span-2 .sm\:mx-auto {
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    margin-bottom: 0 !important;
                }
            }
            @media (min-width: 1024px) and (max-width: 1279px) {
                .ipad-pro-grid-2 {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                }
                .ipad-pro-span-2 {
                    grid-column: span 2 / span 2 !important;
                    display: flex !important;
                    flex-direction: row !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: left !important;
                    gap: 1.5rem !important;
                }
                .ipad-pro-span-2 .sm\:mx-auto {
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    margin-bottom: 0 !important;
                }
            }
        </style>
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-6 ipad-pro-grid-2 ipad-air-grid-2">
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
            <?php foreach ($erpBenefits as $index => $benefit): ?>
                <?php if($index === 4): ?>
                    <!-- 5th Block: Horizontal rectangle on mobile, Square on desktop -->
                    <div class="gsap-erp-benefit-card bg-white rounded-2xl py-10 px-6 sm:p-6 border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 mobile-span-2 ipad-pro-span-2 ipad-air-span-2 sm:col-span-1 flex flex-row sm:block items-center text-left sm:text-center gap-6 sm:gap-0 opacity-0 translate-y-10">
                        <div class="w-16 h-16 sm:w-14 sm:h-14 sm:mx-auto shrink-0 bg-blue-50/70 rounded-full flex items-center justify-center sm:mb-4">
                            <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <h4 class="text-lg sm:text-base font-bold text-[#043B94] mb-1 sm:mb-1"><?= e($benefit['title']) ?></h4>
                            <p class="text-base sm:text-sm text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Blocks 1-4: Square -->
                    <div class="gsap-erp-benefit-card bg-white rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 opacity-0 translate-y-10">
                        <div class="w-14 h-14 mx-auto bg-blue-50/70 rounded-full flex items-center justify-center mb-4 shrink-0">
                            <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-[#043B94] mb-1"><?= e($benefit['title']) ?></h4>
                            <p class="text-sm text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($erpPortfolios)): ?>
<section class="bg-white py-10 lg:py-20 font-sans">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-200 pb-5 mb-10 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold leading-snug md:leading-tight tracking-tight text-[#022862] m-0">
                    <?= e(t('erp.portfolio_section_title') !== 'erp.portfolio_section_title' ? t('erp.portfolio_section_title') : (getCurrentLang() === 'th' ? 'ผลงานพัฒนาระบบ ERP' : 'ERP System Development Portfolio')) ?>
                </h2>
            </div>
        </div>

        <div id="erp-portfolio-scroll-container" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none gap-8 pb-6 -mx-4 px-4 md:mx-0 md:px-0 md:grid md:grid-cols-2 xl:grid-cols-4 md:overflow-visible md:snap-none">
            <?php foreach ($erpPortfolios as $port): 
                $imgSrc = resolve_article_image_url($port['image_path'] ?? '', asset_url('images/erp.png'));
                $detailUrl = isset($port['slug']) ? route_url('/portfolio/' . $port['slug']) : route_url('/portfolio');
            ?>
                <a href="<?= e($detailUrl) ?>" class="gsap-erp-portfolio-card block w-[85vw] md:w-auto shrink-0 snap-center opacity-0 translate-y-10">
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);

        const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        // Helper function for reveal on scroll
        function revealOnScroll(selector, options = {}) {
            const els = gsap.utils.toArray(selector);
            if (!els.length) return;

            if (prefersReducedMotion) {
                gsap.set(els, { y: 0, opacity: 1 });
                return;
            }

            els.forEach((el) => {
                gsap.to(el, {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.6,
                    ease: "power2.out",
                    stagger: options.stagger || 0
                });
            });
        }

        // 1. Hero Parallax Background Image
        if (!prefersReducedMotion) {
            gsap.utils.toArray(".hero-parallax-img").forEach((img) => {
                gsap.to(img, {
                    yPercent: 12,
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#erp-hero",
                        start: "top top",
                        end: "bottom top",
                        scrub: true
                    }
                });
            });
        }

        // 2. Section "ระบบ ERP คืออะไร"
        revealOnScroll(".gsap-erp-about-left");
        revealOnScroll(".gsap-erp-about-right");
        
        if (!prefersReducedMotion) {
            const aboutCards = gsap.utils.toArray(".gsap-erp-about-card");
            if (aboutCards.length) {
                gsap.to(aboutCards, {
                    scrollTrigger: {
                        trigger: ".gsap-erp-about-right",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    stagger: 0.1,
                    ease: "power1.out"
                });
            }
        }

        // 3. Section "ERP Modules" (10 Modules Staggered)
        const moduleCards = gsap.utils.toArray(".gsap-erp-module-card");
        if (moduleCards.length) {
            if (prefersReducedMotion) {
                gsap.set(moduleCards, { y: 0, opacity: 1 });
            } else {
                gsap.to(moduleCards, {
                    scrollTrigger: {
                        trigger: "#modules",
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: "power2.out"
                });
            }
        }

        // 4. Section "ERP Benefits" (5 Benefits Staggered)
        const benefitCards = gsap.utils.toArray(".gsap-erp-benefit-card");
        if (benefitCards.length) {
            if (prefersReducedMotion) {
                gsap.set(benefitCards, { y: 0, opacity: 1 });
            } else {
                gsap.to(benefitCards, {
                    scrollTrigger: {
                        trigger: ".gsap-erp-benefit-card",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: "power2.out"
                });
            }
        }

        // 5. Section "Portfolio Showcase"
        const portfolioCards = gsap.utils.toArray(".gsap-erp-portfolio-card");
        if (portfolioCards.length) {
            if (prefersReducedMotion) {
                gsap.set(portfolioCards, { y: 0, opacity: 1 });
            } else {
                gsap.to(portfolioCards, {
                    scrollTrigger: {
                        trigger: "#erp-portfolio-scroll-container",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: "power2.out"
                });
            }
        }
    });
</script>