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
        'name_th' => 'SALES MANAGEMENT',
        'name_en' => 'Sales Management',
        'description_th' => 'บริหารจัดการงานขายครบวงจร ตั้งแต่การเสนอราคา จนถึงการส่งมอบสินค้าและออกใบแจ้งหนี้',
        'description_en' => 'Comprehensive sales management from quotation to delivery and invoicing.',
        'bullets_th' => [
            'จัดการลูกค้าและโอกาสทางการขาย',
            'ใบเสนอราคา / ใบสั่งขาย / ใบส่งสินค้า',
            'ติดตามยอดขายและสถานะงานการขาย'
        ],
        'bullets_en' => [
            'Customer and opportunity management',
            'Quotations / Sales orders / Delivery notes',
            'Track sales and order status'
        ],
        'icon' => 'ERP_10.svg'
    ],
    [
        'id' => 2,
        'name_th' => 'PURCHASE MANAGEMENT',
        'name_en' => 'Purchase Management',
        'description_th' => 'ควบคุมการจัดซื้อและบริหารซัพพลายเออร์อย่างมีประสิทธิภาพ ตั้งแต่ขอซื้อจนถึงการรับสินค้า',
        'description_en' => 'Efficient procurement and supplier management from request to receiving.',
        'bullets_th' => [
            'ขอซื้อ / อนุมัติซื้อ / ใบสั่งซื้อ',
            'เปรียบเทียบราคาและผู้จำหน่าย',
            'ติดตามสถานะการสั่งซื้อและรับสินค้า'
        ],
        'bullets_en' => [
            'Purchase request / Approval / PO',
            'Price and vendor comparison',
            'Track PO status and receiving'
        ],
        'icon' => 'ERP_11.svg'
    ],
    [
        'id' => 3,
        'name_th' => 'STOCK MANAGEMENT',
        'name_en' => 'Stock Management',
        'description_th' => 'บริหารสต็อกสินค้าและงานคลังด้วยระบบเรียลไทม์ ลดต้นทุนและป้องกันสินค้าขาด-เกิน',
        'description_en' => 'Real-time inventory and warehouse management to reduce costs and prevent stockouts.',
        'bullets_th' => [
            'รับเข้า / จ่ายออก / โอนย้ายสินค้า',
            'ตรวจนับสต็อกและปรับปรุงสต็อก',
            'ควบคุมคุณภาพและล็อตสินค้า'
        ],
        'bullets_en' => [
            'Goods receipt / Issue / Transfer',
            'Stock count and adjustment',
            'Quality control and lot tracking'
        ],
        'icon' => 'ERP_12.svg'
    ],
    [
        'id' => 4,
        'name_th' => 'ACCOUNTING & FINANCE',
        'name_en' => 'Accounting & Finance',
        'description_th' => 'จัดการการเงินและบัญชีอย่างครบวงจร ถูกต้องตามมาตรฐานบัญชี',
        'description_en' => 'Comprehensive financial and accounting management compliant with standards.',
        'bullets_th' => [
            'บัญชีแยกประเภท / เจ้าหนี้ / ลูกหนี้',
            'งบการเงิน / ภาษี / ต้นทุน',
            'กระแสเงินสด / งบประมาณ'
        ],
        'bullets_en' => [
            'General ledger / AP / AR',
            'Financial statements / Tax / Costing',
            'Cash flow / Budgeting'
        ],
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
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .hero-parallax-img {
        transform: scale(1.15);
        will-change: transform;
    }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.001ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
<section id="erp-hero" class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="hero-parallax-img w-full h-full object-cover object-center opacity-100 mix-blend-screen">
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
        .animate-scroll { animation: scroll 20s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }

        /* สไตล์พิเศษสำหรับหน้าจอ Desktop (ใหญ่กว่า iPad Pro) เพื่อให้เหมือนหน้าอื่นๆ */
        @media (min-width: 1025px) {
            .desktop-wide-container-erp {
                max-width: 1720px !important;
                padding-left: 2.5rem !important;
                padding-right: 2.5rem !important;
            }
            .desktop-erp-hero-h1 {
                font-size: 7rem !important;
                line-height: 1.1 !important;
                white-space: nowrap !important;
            }
            .desktop-erp-hero-text-1 {
                background: none !important;
                -webkit-text-fill-color: #475569 !important; /* สีเทาเข้ม Solid */
                color: #475569 !important;
            }
            .desktop-erp-hero-text-2 {
                background: none !important;
                -webkit-text-fill-color: #2563eb !important; /* สีน้ำเงิน Solid */
                color: #2563eb !important;
            }
            .desktop-erp-hero-subtitle {
                font-size: 2.5rem !important;
                line-height: 1.3 !important;
                background: none !important;
                -webkit-text-fill-color: #0663F6 !important;
                color: #0663F6 !important;
            }
            .desktop-erp-hero-p {
                font-size: 1.375rem !important;
                line-height: 1.8 !important;
                max-width: none !important; /* ยกเลิกการจำกัดความกว้างให้บรรทัดยาวเต็มที่ */
            }
            .desktop-erp-hero-content-wrapper {
                max-width: none !important; /* ปลดล็อคกล่องนอกสุดที่บังคับให้ตัดคำ */
            }

            /* สไตล์สำหรับส่วน ระบบ ERP คืออะไร (Desktop) */
            .desktop-erp-about-container {
                max-width: 1720px !important;
            }
            .desktop-erp-about-wrapper {
                flex-direction: row !important;
            }
            .desktop-erp-about-left {
                flex: none !important; 
                width: 380px !important; /* บีบด้านซ้ายให้พอดี */
                max-width: 380px !important;
                border-bottom: none !important;
                border-right: 1px solid #f1f5f9 !important;
                padding: 3rem 2.5rem !important;
            }
            .desktop-erp-about-right {
                flex: 1 !important; 
                width: auto !important;
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }
            .desktop-erp-about-label {
                font-size: 2.25rem !important; /* ERP SYSTEM ให้ใหญ่กว่า Title */
                margin-bottom: 1.25rem !important; /* เพิ่มระยะห่างเพื่อไม่ให้เส้นใต้ทับตัวอักษรด้านล่าง */
                line-height: 1.2 !important;
                display: block !important;
            }
            .desktop-erp-about-title {
                font-size: 1.75rem !important; /* ระบบ ERP คืออะไร ให้เล็กกว่า Label */
                margin-bottom: 1rem !important;
            }
            .desktop-erp-about-desc {
                font-size: 1.125rem !important; /* ขยายขนาดให้อ่านง่ายขึ้น */
                line-height: 1.8 !important;
                color: #475569 !important;
            }
            .desktop-erp-about-card {
                padding: 3rem 1.5rem !important;
                border-bottom: none !important;
                border-right: 1px solid #f1f5f9 !important;
            }
            .desktop-erp-about-card-last {
                border-right: none !important;
            }
            .desktop-erp-about-icon {
                width: 4.5rem !important;
                height: 4.5rem !important;
                margin-bottom: 1.5rem !important;
            }
            .desktop-erp-about-card-title {
                font-size: 1.35rem !important; /* ขยายขนาดหัวข้อในการ์ด */
                margin-bottom: 0.75rem !important;
            }
            .desktop-erp-about-card-desc {
                font-size: 1.05rem !important; /* ขยายขนาดรายละเอียดในการ์ด */
                line-height: 1.6 !important;
            }

            /* สไตล์สำหรับส่วน ERP Modules (Desktop) */
            .desktop-erp-module-container {
                max-width: 1720px !important;
                padding-left: 2.5rem !important;
                padding-right: 2.5rem !important;
            }
            .desktop-erp-module-grid {
                gap: 2.5rem !important;
            }
            .desktop-erp-module-card {
                padding: 3rem !important;
                border-radius: 1.5rem !important;
            }
            /* ยกเลิกสีพื้นหลัง hover เป็นสีน้ำเงิน ให้เป็นแค่ white แบบในรูป */
            .desktop-erp-module-card:hover {
                background-color: #ffffff !important;
                border-color: #f1f5f9 !important;
                box-shadow: 0 20px 40px -15px rgba(0,0,0,0.08) !important;
            }
            .desktop-erp-module-inner {
                align-items: flex-start !important;
                gap: 2.5rem !important;
            }
            .desktop-erp-module-icon-wrap {
                width: 7rem !important;
                height: 7rem !important;
                background: none !important;
                border-radius: 0 !important;
                flex-shrink: 0 !important;
            }
            .desktop-erp-module-icon-img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }
            .desktop-erp-module-content {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.75rem !important;
            }
            .desktop-erp-module-title {
                font-size: 1.25rem !important;
                color: #0663F6 !important; /* สีน้ำเงินสดแบบในรูป */
                text-transform: uppercase !important;
                margin-bottom: 0 !important;
            }
            /* ยกเลิกการเปลี่ยนสี text ตอน hover */
            .desktop-erp-module-card:hover .desktop-erp-module-title {
                color: #0663F6 !important;
            }
            .desktop-erp-module-desc {
                font-size: 0.95rem !important;
                color: #334155 !important;
                line-height: 1.6 !important;
                margin-bottom: 0.25rem !important;
            }
            .desktop-erp-module-card:hover .desktop-erp-module-desc {
                color: #334155 !important;
            }
            .desktop-erp-module-bullets {
                list-style-type: disc !important;
                padding-left: 1.5rem !important;
                color: #0663F6 !important; /* bullet color */
                margin-top: 0 !important;
            }
            .desktop-erp-module-bullets-wrapper {
                display: block !important;
            }
            .desktop-erp-module-bullets li {
                font-size: 0.95rem !important;
                color: #475569 !important; /* text color */
                margin-bottom: 0.5rem !important;
                line-height: 1.5 !important;
            }
            .desktop-erp-module-card:hover .desktop-erp-module-bullets li {
                color: #475569 !important;
            }

            /* สไตล์สำหรับส่วน ERP Benefits (Desktop) */
            .desktop-erp-benefit-container {
                max-width: 1720px !important;
            }
            .desktop-erp-benefit-card {
                padding: 3rem 1.5rem !important; /* เพิ่ม Padding ให้การ์ดดูใหญ่ขึ้น */
                border-radius: 1rem !important;
                border: none !important;
                box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06) !important;
                background-color: #ffffff !important;
            }
            .desktop-erp-benefit-icon-wrap {
                width: 5.5rem !important;
                height: 5.5rem !important;
                background: none !important;
                margin-bottom: 1.5rem !important;
            }
            .desktop-erp-benefit-icon-img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important;
            }
            .desktop-erp-benefit-title {
                font-size: 1.15rem !important;
                color: #0663F6 !important; /* สีน้ำเงินสว่างตามรูป */
                margin-bottom: 0.75rem !important;
                font-weight: 700 !important;
            }
            .desktop-erp-benefit-desc {
                font-size: 0.95rem !important;
                color: #475569 !important;
            }

            /* สไตล์สำหรับส่วน ตัวอย่างผลงานของเรา (Desktop) */
            .desktop-erp-portfolio-container {
                max-width: 1720px !important;
            }
            .desktop-erp-portfolio-header-container {
                border-bottom: none !important; /* ลบเส้นคั่นเทายาวๆ ออก */
                margin-bottom: 2.5rem !important;
                padding-bottom: 0 !important;
            }
            .desktop-erp-portfolio-title {
                font-size: 2.25rem !important;
                color: #0663F6 !important; /* สีน้ำเงินสว่าง */
                display: inline-block !important;
                border-bottom: 3px solid #0663F6 !important; /* ขีดเส้นใต้สีน้ำเงิน */
                padding-bottom: 0.75rem !important;
                margin: 0 !important;
            }
            /* ซ่อน text ข้างล่างการ์ดผลงาน ให้เหลือแต่รูปล้วนแบบในดีไซน์ */
            .desktop-erp-portfolio-text {
                display: none !important;
            }
            .desktop-erp-portfolio-img-container {
                height: auto !important; /* ปล่อยให้สูงตามสัดส่วนภาพ */
                background: transparent !important;
            }
            .desktop-erp-portfolio-img-container img {
                border-radius: 1rem !important;
            }
            .desktop-erp-portfolio-badge {
                display: none !important;
            }
            .desktop-erp-portfolio-card {
                border: none !important;
                box-shadow: none !important;
            }
            .desktop-erp-portfolio-card:hover {
                box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1) !important;
            }
        }
    </style>
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10 desktop-wide-container-erp">
        <!-- Mobile Background Image (Only covers this Hero container) -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
                class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-white/40"></div>
            <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent"></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            <div class="max-w-2xl desktop-erp-hero-content-wrapper">
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
                    <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                        <?php if (getCurrentLang() === 'th'): ?>
                            ระบบบริหารจัดการทรัพยากรองค์กร<br>
                            (Enterprise Resource Planning)<br>
                            ที่ช่วยรวมข้อมูลและกระบวนการทำงาน<br>
                            สำคัญขององค์กรไว้ในระบบเดียว<br>
                            ลดงานซ้ำซ้อน เพิ่มประสิทธิภาพการทำงาน<br>
                            และขับเคลื่อนองค์กรสู่อนาคตดิจิทัล
                        <?php else: ?>
                            <?= e(t('common.articles_knowledge_summary')) ?> <br>
                            <?= e(t('common.articles_coverage_summary')) ?><br>
                            <?= e(t('common.articles_growth_summary')) ?>
                        <?php endif; ?>
                    </p>
                    <div class="animate-entrance-up delay-400 flex flex-col items-start gap-4">
                        <a href="<?= e(route_url('/service')) ?>" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
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
                    <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter desktop-erp-hero-h1">
                        <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 desktop-erp-hero-text-1">
                            <?= getCurrentLang() === 'th' ? 'ระบบ' : 'ERP' ?>
                        </span>
                        <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1 lg:ml-2 desktop-erp-hero-text-2">
                            <?= getCurrentLang() === 'th' ? 'ERP' : 'Systems' ?>
                        </span><br>
                        <span class="text-xl md:text-2xl lg:text-4xl font-medium leading-snug bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-0 pb-3 pt-1 desktop-erp-hero-subtitle" style="animation-delay: -3s;">
                            <?= getCurrentLang() === 'th' ? 'เชื่อมต่อทุกกระบวนการธุรกิจ<br>แบบครบวงจรในแพลตฟอร์มเดียว' : 'Connecting every business process<br>end-to-end on a single platform' ?>
                        </span>
                    </h1>
                    <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium desktop-erp-hero-p">
                        <?php if (getCurrentLang() === 'th'): ?>
                            รวบรวมบทความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ<br>ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI<br>และโซลูชัน ที่ช่วยพัฒนาองค์กรให้เติบโตได้อย่างยั่งยืน
                        <?php else: ?>
                            <?= e(t('common.articles_knowledge_summary')) ?> <br class="hidden md:block">
                            <?= e(t('common.articles_coverage_summary')) ?><br class="hidden md:block">
                            <?= e(t('common.articles_growth_summary')) ?>
                        <?php endif; ?>
                    </p>
                    <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                        <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
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
    @media (min-width: 1280px) {
        .erp-left-col { flex: none !important; width: 480px !important; max-width: 480px !important; }
        .erp-right-col { flex: 1 !important; width: auto !important; }
    }
</style>
<section class="bg-white pt-8 pb-4 lg:pt-24 lg:pb-8">
    <div class="erp-bleed-wrapper mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16 overflow-hidden desktop-erp-about-container">
        <div class="w-full rounded-[1rem] bg-white flex flex-col xl:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden desktop-erp-about-wrapper">
            <div class="erp-left-col gsap-erp-about-left group flex-1 flex flex-col justify-center p-6 lg:p-8 border-b xl:border-b-0 xl:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50 cursor-pointer opacity-0 translate-y-10 desktop-erp-about-left">
                <div>
                    <span class="text-primary font-bold text-lg md:text-base tracking-wide inline-block mb-3 mx-0 uppercase desktop-erp-about-label">
                        <span class="border-b-[3px] border-primary pb-0.5">ERP</span> SYSTEM
                    </span>
                    <h2 class="text-[#043B94] text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700 desktop-erp-about-title">
                        <?= getCurrentLang() === 'th' ? 'ระบบ ERP คืออะไร' : 'What is an ERP System?' ?>
                    </h2>
                    <p class="text-gray-500 text-lg lg:text-xl leading-relaxed mb-6 desktop-erp-about-desc">
                        <?= getCurrentLang() === 'th' 
                            ? 'ERP คือ ระบบที่รวบรวมและเชื่อมโยงกระบวนการทำงานหลักขององค์กร ไม่ว่าจะเป็นการขาย การจัดซื้อ คลังสินค้า การเงิน การผลิต ทรัพยากรบุคคลและงานอื่นๆ ให้ทำงานร่วมกันบนฐานข้อมูลเดียวแบบเรียลไทม์ ช่วยให้ผู้บริหารมองเห็นภาพรวม ตัดสินใจได้แม่นยำและตอบสนองต่อการเปลี่ยนแปลงได้รวดเร็ว' 
                            : 'ERP is a system that integrates core business processes—such as sales, procurement, inventory, finance, manufacturing, and HR—to work together on a single real-time database. It helps executives see the big picture, make accurate decisions, and respond swiftly to changes.' 
                        ?>
                    </p>
                </div>
            </div>
            <div class="erp-right-col gsap-erp-about-right flex-[4] grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 w-full opacity-0 translate-y-10 desktop-erp-about-right">
                <?php
                $serviceCards = [
                    ['icon' => asset_url('images/ERP_1.svg'), 'title' => getCurrentLang() === 'th' ? 'ข้อมูลเชื่อมต่อครบทุกแผนก' : 'Connected Data Across Departments', 'desc' => getCurrentLang() === 'th' ? 'ข้อมูลเป็นหนึ่งเดียว ไม่ต้องทำงานซ้ำ' : 'Single source of truth, eliminating duplicate work.', 'href' => '#'],
                    ['icon' => asset_url('images/ERP_2.svg'), 'title' => getCurrentLang() === 'th' ? 'ทำงานอัตโนมัติ ลดความผิดพลาด' : 'Automated Processes & Reduced Errors', 'desc' => getCurrentLang() === 'th' ? 'ลดขั้นตอนงานเอกสาร เพิ่มความแม่นยำ' : 'Minimize paperwork and increase accuracy.', 'href' => '#'],
                    ['icon' => asset_url('images/ERP_3.svg'), 'title' => getCurrentLang() === 'th' ? 'มองเห็นแบบเรียลไทม์ ตัดสินใจได้ไว' : 'Real-time Visibility & Quick Decisions', 'desc' => getCurrentLang() === 'th' ? 'รายงานและ Dashboard อัปเดตตลอดเวลา' : 'Always-updated reports and dashboards.', 'href' => '#'],
                    ['icon' => asset_url('images/ERP_4.svg'), 'title' => getCurrentLang() === 'th' ? 'รองรับการเติบโต ของธุรกิจ' : 'Supports Business Growth', 'desc' => getCurrentLang() === 'th' ? 'ขยายระบบได้ตามความต้องการ พร้อมเติบโตในอนาคต' : 'Scalable system ready to grow with your business in the future.', 'href' => '#'],
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
                    $borderClass .= ' xl:border-b-0';
                    if ($i < 3) {
                        $borderClass .= ' xl:border-r';
                    } else {
                        $borderClass .= ' xl:border-r-0';
                    }
                    
                    $desktopLastClass = ($i === $lastIdx) ? ' desktop-erp-about-card-last' : '';
                ?>
                    <div class="gsap-erp-about-card relative group cursor-pointer flex flex-col justify-center p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl opacity-0 translate-y-10 desktop-erp-about-card<?= $desktopLastClass ?>">
                        <div>
                            <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110 desktop-erp-about-icon">
                                <img src="<?= e($card['icon']) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                            </div>
                            <h2 class="text-[#043B94] font-bold text-lg md:text-xl lg:text-2xl xl:text-3xl text-center mb-3 whitespace-normal tracking-tight transition-colors duration-300 group-hover:text-blue-600 desktop-erp-about-card-title">
                                <?= e($card['title']) ?>
                            </h2>
                            <p class="text-gray-500 text-base md:text-lg lg:text-xl leading-relaxed mb-6 text-center transition-colors duration-300 group-hover:text-gray-600 desktop-erp-about-card-desc">
                                <?= e($card['desc']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<section id="modules" class="bg-slate-50 py-10 lg:py-12 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8 desktop-erp-module-container">
        <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-blue-600 tracking-tight mb-4">
                <span class="lg:hidden">ERP modules</span>
                <span class="hidden lg:inline uppercase">ERP MODULE</span>
            </h2>
            <span class="text-blue-400 lg:text-[#043B94] font-bold text-lg md:text-xl lg:text-2xl uppercase lg:normal-case mb-3 block">
                <span class="lg:hidden"><?= e(t('erp.process_coverage_title') !== 'erp.process_coverage_title' ? t('erp.process_coverage_title') : (getCurrentLang() === 'th' ? 'ระบบครอบคลุมทุกกระบวนการทำงาน' : 'A System That Covers Every Process')) ?></span>
                <span class="hidden lg:inline"><?= e(getCurrentLang() === 'th' ? 'ครบทุกโมดูล ตอบโจทย์ทุกการทำงานขององค์กร' : 'Complete modules for all enterprise operations') ?></span>
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 lg:gap-6 desktop-erp-module-grid">
            <?php foreach ($modulesData as $module): ?>
                <div class="gsap-erp-module-card bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:bg-primary hover:border-primary transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden opacity-0 translate-y-10 desktop-erp-module-card">
                    <div class="relative z-10 flex flex-row items-center gap-5 desktop-erp-module-inner">
                        <div class="shrink-0 w-16 h-16 bg-blue-50/60 group-hover:bg-white/20 rounded-full flex items-center justify-center transition-colors duration-300 desktop-erp-module-icon-wrap">
                            <img src="<?= e(asset_url('images/' . $module['icon'])) ?>" alt="<?= e($module['name_en']) ?>" class="w-10 h-10 object-contain group-hover:scale-110 transition-all duration-300 desktop-erp-module-icon-img" />
                        </div>
                        <div class="text-left flex-1 desktop-erp-module-content">
                            <h3 class="text-xl lg:text-2xl font-bold text-[#043B94] mb-2 group-hover:text-white transition-colors desktop-erp-module-title">
                                <?= e(getCurrentLang() === 'th' ? $module['name_th'] : $module['name_en']) ?> 
                            </h3>
                            <p class="text-base lg:text-lg text-slate-500 group-hover:text-white/90 leading-relaxed transition-colors line-clamp-2 md:line-clamp-none desktop-erp-module-desc">
                                <?= e(getCurrentLang() === 'th' ? $module['description_th'] : $module['description_en']) ?>
                            </p>
                            <?php if (!empty($module['bullets_th']) && getCurrentLang() === 'th'): ?>
                                <ul class="hidden desktop-erp-module-bullets-wrapper desktop-erp-module-bullets">
                                    <?php foreach($module['bullets_th'] as $bullet): ?>
                                        <li><?= e($bullet) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php elseif (!empty($module['bullets_en']) && getCurrentLang() !== 'th'): ?>
                                <ul class="hidden desktop-erp-module-bullets-wrapper desktop-erp-module-bullets">
                                    <?php foreach($module['bullets_en'] as $bullet): ?>
                                        <li><?= e($bullet) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="bg-slate-50 py-10 lg:py-10 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8 desktop-erp-benefit-container">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-center text-[#022862] tracking-tight py-10">
            <?= e(t('erp.cta_banner_title') !== 'erp.cta_banner_title' ? t('erp.cta_banner_title') : (getCurrentLang() === 'th' ? 'ERP ที่ช่วยยกระดับธุรกิจของคุณ' : 'ERP That Elevates Your Business')) ?>
        </h2>
        <style>
            .erp-benefit-grid-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1.5rem;
            }
            .erp-benefit-card-wrapper {
                width: calc(50% - 0.75rem);
            }
            @media (min-width: 1280px) {
                .erp-benefit-card-wrapper {
                    width: calc(20% - 1.2rem);
                }
            }
        </style>
        <div class="erp-benefit-grid-container">
            <?php
            $erpBenefits = [
                [
                    'title' => t('erp.benefit_complete_data_title') !== 'erp.benefit_complete_data_title' ? t('erp.benefit_complete_data_title') : (getCurrentLang() === 'th' ? 'ข้อมูลครบถ้วน' : 'Complete Data'),
                    'desc' => getCurrentLang() === 'th' ? 'เชื่อมต่อทุกแผนกในระบบเดียว' : 'All departments in one system', /* แก้ไขคำอธิบายตามรูป */
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
                <div class="erp-benefit-card-wrapper gsap-erp-benefit-card bg-white rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 opacity-0 translate-y-10 desktop-erp-benefit-card">
                    <div class="w-14 h-14 mx-auto bg-blue-50/70 rounded-full flex items-center justify-center mb-4 shrink-0 desktop-erp-benefit-icon-wrap">
                        <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain desktop-erp-benefit-icon-img">
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-[#043B94] mb-1 desktop-erp-benefit-title"><?= e($benefit['title']) ?></h4>
                        <p class="text-sm text-slate-500 leading-relaxed desktop-erp-benefit-desc"><?= e($benefit['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php if (!empty($erpPortfolios)): ?>
<section class="bg-white py-10 lg:py-20 font-sans">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8 desktop-erp-portfolio-container">
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-200 pb-5 mb-10 gap-4 desktop-erp-portfolio-header-container">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold leading-none tracking-tight text-[#022862] m-0 desktop-erp-portfolio-title">
                    <?= e(getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Our Portfolio') ?>
                </h2>
            </div>
        </div>
        <style>
            @media (min-width: 768px) and (max-width: 1023px) {
                .tablet-slider-container {
                    display: flex !important;
                    overflow-x: auto !important;
                    scroll-snap-type: x mandatory !important;
                    -webkit-overflow-scrolling: touch;
                    padding-bottom: 1.5rem !important;
                    gap: 1.5rem !important;
                }
                .tablet-slider-card {
                    width: calc(50% - 0.75rem) !important;
                    flex-shrink: 0 !important;
                    scroll-snap-align: start !important;
                }
                .tablet-show-dots {
                    display: flex !important;
                }
                #erp-portfolio-dots button:nth-child(n+4) {
                    display: none !important;
                }
            }
        </style>
        <div id="erp-portfolio-scroll-container" class="tablet-slider-container flex overflow-x-auto snap-x snap-mandatory scrollbar-none gap-8 pb-6 -mx-4 px-4 md:mx-0 md:px-0 lg:grid lg:grid-cols-4 lg:overflow-visible lg:snap-none">
            <?php foreach ($erpPortfolios as $port): 
                $imgSrc = resolve_article_image_url($port['image_path'] ?? '', asset_url('images/erp.png'));
                $detailUrl = isset($port['slug']) ? route_url('/portfolio/' . $port['slug']) : route_url('/portfolio');
            ?>
                <a href="<?= e($detailUrl) ?>" class="tablet-slider-card gsap-erp-portfolio-card block w-[85vw] lg:w-auto shrink-0 snap-center opacity-0 translate-y-10 desktop-erp-portfolio-card">
                    <article class="group w-full h-full rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col hover:-translate-y-1">
                    <div class="h-[220px] w-full overflow-hidden bg-slate-100 relative desktop-erp-portfolio-img-container">
                        <img src="<?= e($imgSrc) ?>" alt="<?= e($port['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        <span class="absolute bottom-3 left-3 bg-primary/95 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm desktop-erp-portfolio-badge">ERP SYSTEM</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1 desktop-erp-portfolio-text">
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
        <div class="tablet-show-dots flex justify-center gap-2 mt-2 lg:hidden" id="erp-portfolio-dots">
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
                
                let activeIndex = closestIndex;
                if (window.innerWidth >= 768 && window.innerWidth < 1024) {
                    activeIndex = Math.min(closestIndex, 2);
                }

                dots.forEach((dot, index) => {
                    if (index === activeIndex) {
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