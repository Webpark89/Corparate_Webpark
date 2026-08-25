<?php

declare(strict_types=1);

$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-6.png');
$ctaImage = asset_url('images/bg-cta.jpg');
$lang = getCurrentLang();

$digitalPillars = [
    [
        'icon' => asset_url('images/ERP_1.svg'),
        'title' => $lang === 'th' ? 'สถาปัตยกรรมระดับ Enterprise' : 'Enterprise Architecture',
        'desc' => $lang === 'th' ? 'โครงสร้างระบบปลอดภัยสูง รองรับโหลดมหาศาล และพร้อมสเกล' : 'High security, robust scalability, and enterprise-grade performance.',
    ],
    [
        'icon' => asset_url('images/ERP_2.svg'),
        'title' => $lang === 'th' ? 'เชื่อมต่อ API & ระบบหลังบ้าน' : 'Seamless API & System Integration',
        'desc' => $lang === 'th' ? 'เชื่อมโยง ERP, CRM, Payment Gateway และฐานข้อมูลอย่างไร้รอยต่อ' : 'Connect ERP, CRM, payment gateways, and databases seamlessly.',
    ],
    [
        'icon' => asset_url('images/ERP_3.svg'),
        'title' => $lang === 'th' ? 'ออกแบบเฉพาะตามธุรกิจ 100%' : '100% Custom Tailored',
        'desc' => $lang === 'th' ? 'พัฒนาฟังก์ชันตรงตาม Workflow ขององค์กร ไม่ถูกจำกัดด้วยเทมเพลต' : 'Tailored exactly to your workflows without template limitations.',
    ],
    [
        'icon' => asset_url('images/ERP_4.svg'),
        'title' => $lang === 'th' ? 'ประสบการณ์ใช้งานที่ลื่นไหล' : 'Modern & Fast UI/UX',
        'desc' => $lang === 'th' ? 'ออกแบบตามหลัก Human-Centered ใช้งานง่าย และรองรับทุกอุปกรณ์' : 'Human-centered design, highly responsive across all devices.',
    ],
];

$digitalModules = [
    [
        'name_th' => 'Custom Web Application',
        'name_en' => 'Custom Web Application',
        'desc_th' => 'พัฒนาระบบเว็บแอปพลิเคชันเฉพาะทางสำหรับองค์กร เช่น พอร์ทัลลูกค้า ระบบจอง ระบบหลังบ้าน และแดชบอร์ดบริหาร',
        'desc_en' => 'Develop tailored web applications for enterprises including client portals, booking systems, and admin dashboards.',
        'icon' => 'ERP_10.svg'
    ],
    [
        'name_th' => 'Mobile App Development (iOS & Android)',
        'name_en' => 'Mobile App Development (iOS & Android)',
        'desc_th' => 'สร้างแอปพลิเคชันมือถือทั้ง Native และ Cross-Platform (Flutter / React Native) ที่เสถียร สวยงาม และใช้งานลื่นไหล',
        'desc_en' => 'Build high-performance Native and Cross-Platform mobile apps (Flutter / React Native) with superior UX.',
        'icon' => 'ERP_11.svg'
    ],
    [
        'name_th' => 'SaaS & Multi-Tenant Platform',
        'name_en' => 'SaaS & Multi-Tenant Platform',
        'desc_th' => 'พัฒนาแพลตฟอร์มซอฟต์แวร์รูปแบบบอกรับสมาชิก (Subscription) พร้อมระบบจัดการสิทธิ์ บิลลิ่ง และความปลอดภัยขั้นสูง',
        'desc_en' => 'Build scalable SaaS platforms with multi-tenancy, subscription billing, and robust security controls.',
        'icon' => 'ERP_12.svg'
    ],
    [
        'name_th' => 'E-Commerce & Payment Ecosystem',
        'name_en' => 'E-Commerce & Payment Ecosystem',
        'desc_th' => 'ระบบร้านค้าออนไลน์ขั้นสูง จัดการสต็อกสินค้า โปรโมชัน และเชื่อมต่อระบบชำระเงินทุกช่องทางอย่างปลอดภัย',
        'desc_en' => 'High-conversion e-commerce systems with smart inventory, promotions, and secure payment integrations.',
        'icon' => 'ERP_13.svg'
    ],
    [
        'name_th' => 'System Integration & Custom API',
        'name_en' => 'System Integration & Custom API',
        'desc_th' => 'สร้างและเชื่อมต่อ RESTful / GraphQL API เพื่อให้อุปกรณ์และระบบต่าง ๆ ในองค์กรสื่อสารกันได้อัตโนมัติ',
        'desc_en' => 'Develop and integrate RESTful/GraphQL APIs to connect disparate software into an automated ecosystem.',
        'icon' => 'ERP_14.svg'
    ],
    [
        'name_th' => 'Cloud Architecture & DevOps',
        'name_en' => 'Cloud Architecture & DevOps',
        'desc_th' => 'วางโครงสร้างระบบบน AWS / GCP / Azure พร้อมระบบ CI/CD, Auto-scaling และการสำรองข้อมูลอัตโนมัติ',
        'desc_en' => 'Design resilient cloud setups on AWS/GCP/Azure with automated CI/CD pipelines, scaling, and backups.',
        'icon' => 'ERP_15.svg'
    ],
];

$digitalBenefits = [
    [
        'title' => $lang === 'th' ? 'ยืดหยุ่นสูง ปรับแต่งได้ 100%' : '100% Flexible & Custom',
        'desc' => $lang === 'th' ? 'ปรับเปลี่ยนฟังก์ชันตามการเติบโตของธุรกิจได้ตลอดเวลา' : 'Adapt and extend features anytime as your business evolves.',
        'icon' => asset_url('images/ERP_5.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'ประหยัดต้นทุนระยะยาว' : 'Long-Term Cost Efficiency',
        'desc' => $lang === 'th' ? 'เป็นเจ้าของ Source Code และข้อมูลเอง ไม่ติดค่าสัญญารายหัว' : 'Full ownership of source code and data with zero vendor lock-in.',
        'icon' => asset_url('images/ERP_6.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'ข้อมูลรวมเป็นหนึ่งเดียว' : 'Unified Central Data',
        'desc' => $lang === 'th' ? 'เชื่อมต่อทุกแผนก ลดความผิดพลาดและงานซ้ำซ้อน' : 'Connect all departments, eliminating silos and duplicate workflows.',
        'icon' => asset_url('images/ERP_7.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'ปลอดภัยมาตรฐานสากล' : 'Enterprise Grade Security',
        'desc' => $lang === 'th' ? 'ปกป้องข้อมูลองค์กรด้วยมาตรฐานความปลอดภัยและการเข้ารหัส' : 'Protect organizational assets with robust encryption and role control.',
        'icon' => asset_url('images/ERP_8.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'พร้อมขยายตัวไร้ขีดจำกัด' : 'Infinite Scalability',
        'desc' => $lang === 'th' ? 'รองรับผู้ใช้งานหลักหมื่นถึงหลักล้านคนได้อย่างเสถียร' : 'Scale smoothly from thousands to millions of active users.',
        'icon' => asset_url('images/ERP_9.svg'),
    ],
];

$processSteps = [
    [
        'num' => '01',
        'title' => $lang === 'th' ? 'Consult & Discover' : 'Consult & Discover',
        'desc' => $lang === 'th' ? 'วิเคราะห์ความต้องการ โครงสร้างธุรกิจ และ Pain Points เพื่อวางเป้าหมายร่วมกัน' : 'Analyze business requirements, workflows, and pain points to define clear objectives.'
    ],
    [
        'num' => '02',
        'title' => $lang === 'th' ? 'Architecture & UI/UX' : 'Architecture & UI/UX',
        'desc' => $lang === 'th' ? 'ออกแบบสถาปัตยกรรมระบบ ฐานข้อมูล และ Prototype หน้าจอให้ทดลองก่อนเริ่มเขียนโค้ด' : 'Design system architecture, database schema, and interactive UI/UX prototypes.'
    ],
    [
        'num' => '03',
        'title' => $lang === 'th' ? 'Agile Development' : 'Agile Development',
        'desc' => $lang === 'th' ? 'พัฒนาด้วยกระบวนการ Agile ส่งมอบงานเป็น Sprint และทดสอบระบบอย่างเข้มงวด (QA)' : 'Develop through agile sprints with continuous QA testing, code review, and feedback loops.'
    ],
    [
        'num' => '04',
        'title' => $lang === 'th' ? 'Deploy & Scaling' : 'Deploy & Scaling',
        'desc' => $lang === 'th' ? 'นำระบบขึ้นใช้งานจริง (Production) พร้อมฝึกอบรม และดูแลรักษาระบบต่อเนื่อง (SLA)' : 'Seamless launch to production with team training, monitoring, and ongoing SLA maintenance.'
    ],
];
?>

<style>
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }

    .hero-parallax-img {
        transform: scale(1.15);
        will-change: transform;
    }

    @media (min-width: 1025px) {
        .desktop-wide-container {
            max-width: 1720px !important;
            padding-left: 2.5rem !important;
            padding-right: 2.5rem !important;
        }
        .desktop-hero-h1 {
            font-size: 5.5rem !important;
            line-height: 1.1 !important;
        }
        .desktop-hero-p {
            font-size: 1.25rem !important;
            line-height: 1.75 !important;
            max-width: 36rem !important;
        }
    }
</style>

<!-- Hero Section -->
<section id="service-hero" class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="Digital Platform Background" 
            class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/10"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-20 lg:pt-28 lg:pb-32 relative z-10 desktop-wide-container">
        <!-- Mobile Background -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="Digital Platform Background" 
                class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-white/40"></div>
            <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            <div class="max-w-2xl lg:ml-12 xl:ml-24">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6 hidden sm:block">
                    <ol class="inline-flex items-center text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('common.nav_home')) ?>
                            </a>
                        </li>
                        <li><span class="text-slate-400 mx-2">/</span></li>
                        <li>
                            <a href="<?= e(route_url('/services')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('common.nav_services')) ?>
                            </a>
                        </li>
                        <li><span class="text-slate-400 mx-2">/</span></li>
                        <li aria-current="page">
                            <span class="text-primary font-semibold"><?= $lang === 'th' ? 'แพลตฟอร์มดิจิทัล' : 'Digital Platform' ?></span>
                        </li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-4 tracking-tighter">
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'แพลตฟอร์ม' : 'Digital' ?>
                    </span>
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1 lg:ml-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'ดิจิทัล' : 'Platform' ?>
                    </span>
                    <br>
                    <span class="text-xl md:text-2xl lg:text-4xl font-medium leading-snug bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 pb-3 pt-1" style="animation-delay: -3s;">
                        <?= $lang === 'th' ? 'ขับเคลื่อนองค์กรสู่ยุคใหม่<br>ด้วยซอฟต์แวร์และแพลตฟอร์มเฉพาะตัว' : 'Empowering Enterprises with Custom Software<br>& Digital Platform Solutions' ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-4 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-8 font-medium desktop-hero-p">
                    <?= $lang === 'th' 
                        ? 'ออกแบบและพัฒนา Digital Platform แบบครบวงจร ตั้งแต่ Web Application, Mobile App ไปจนถึง Custom SaaS เพื่อเพิ่มขีดความสามารถในการแข่งขันและรองรับการเติบโตอย่างยั่งยืน'
                        : 'End-to-end design and engineering for custom Web Applications, Mobile Apps, and SaaS systems to scale your operations and competitive edge.'
                    ?>
                </p>

                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= $lang === 'th' ? 'ปรึกษาผู้เชี่ยวชาญฟรี' : 'Consult an Expert' ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#modules" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= $lang === 'th' ? 'ดูโซลูชันทั้งหมด' : 'Explore Solutions' ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section & 4 Key Pillars -->
<section class="bg-white pt-8 pb-4 lg:pt-20 lg:pb-8">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-20 -mt-10 lg:-mt-16 pb-6 lg:pb-16 overflow-hidden">
        <div class="w-full rounded-[1.5rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">
            
            <div class="erp-left-col gsap-reveal group flex-1 flex flex-col justify-center p-6 lg:p-10 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50">
                <div>
                    <span class="text-primary font-bold text-lg md:text-base tracking-wide inline-block mb-3 uppercase">
                        <span class="border-b-[3px] border-primary pb-0.5">DIGITAL</span> PLATFORMS
                    </span>
                    <h2 class="text-[#043B94] text-3xl xl:text-4xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        <?= $lang === 'th' ? 'ทำไมองค์กรยุคใหม่ต้องมี Platform ของตัวเอง?' : 'Why Build a Custom Digital Platform?' ?>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-6">
                        <?= $lang === 'th' 
                            ? 'การพึ่งพาแพลตฟอร์มสำเร็จรูปหรือการทำงานแบบเดิมอาจไม่ยืดหยุ่นพอสำหรับการขยายตัว การสร้าง Digital Platform เฉพาะขององค์กรช่วยให้คุณเป็นเจ้าของข้อมูล 100% ปรับแต่งฟังก์ชันได้ตามต้องการ และสร้างความได้เปรียบทางธุรกิจในระยะยาว' 
                            : 'Off-the-shelf tools often restrict business expansion. A proprietary digital platform gives you full data ownership, tailored workflows, and a sustainable competitive advantage.'
                        ?>
                    </p>
                </div>
            </div>

            <div class="erp-right-col gsap-reveal flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php foreach ($digitalPillars as $i => $pillar): 
                    $borderClass = $i < 3 ? 'lg:border-r border-b sm:border-b-0 border-gray-100' : 'border-gray-100';
                ?>
                    <div class="relative group flex flex-col justify-center p-6 lg:p-8 <?= $borderClass ?> bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                        <div>
                            <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 group-hover:-translate-y-2 group-hover:scale-110">
                                <img src="<?= e($pillar['icon']) ?>" alt="<?= e($pillar['title']) ?>" class="h-full w-full object-contain">
                            </div>
                            <h3 class="text-[#043B94] font-bold text-lg md:text-xl text-center mb-3 tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                                <?= e($pillar['title']) ?>
                            </h3>
                            <p class="text-gray-500 text-base leading-relaxed text-center group-hover:text-gray-600">
                                <?= e($pillar['desc']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Modules / Capabilities Section -->
<section id="modules" class="bg-slate-50 py-12 lg:py-16 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 tracking-tight mb-3 uppercase">
                <?= $lang === 'th' ? 'บริการพัฒนาแพลตฟอร์มของเรา' : 'PLATFORM CAPABILITIES' ?>
            </h2>
            <span class="text-[#043B94] font-bold text-lg md:text-xl block">
                <?= $lang === 'th' ? 'ครอบคลุมทุกความต้องการ ตั้งแต่เว็บ แอปพลิเคชัน จนถึงคลาวด์' : 'End-to-end engineering tailored to your business ecosystem' ?>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($digitalModules as $module): ?>
                <div class="gsap-reveal bg-white rounded-2xl p-6 lg:p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:bg-primary hover:border-primary transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden">
                    <div class="relative z-10 flex flex-row items-start sm:items-center gap-5">
                        <div class="shrink-0 w-16 h-16 bg-blue-50/70 group-hover:bg-white/20 rounded-2xl flex items-center justify-center transition-colors duration-300">
                            <img src="<?= e(asset_url('images/' . $module['icon'])) ?>" alt="<?= e($module['name_en']) ?>" class="w-9 h-9 object-contain group-hover:scale-110 transition-all duration-300" />
                        </div>
                        <div class="text-left flex-1">
                            <h3 class="text-xl font-bold text-[#043B94] mb-2 group-hover:text-white transition-colors">
                                <?= e($lang === 'th' ? $module['name_th'] : $module['name_en']) ?>
                            </h3>
                            <p class="text-base text-slate-500 group-hover:text-white/90 leading-relaxed transition-colors">
                                <?= e($lang === 'th' ? $module['desc_th'] : $module['desc_en']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Key Benefits Section -->
<section class="bg-white py-12 lg:py-16 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-extrabold text-center text-[#022862] tracking-tight mb-10">
            <?= $lang === 'th' ? 'คุณค่าและประโยชน์ที่องค์กรจะได้รับ' : 'Measurable Business Benefits' ?>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php foreach ($digitalBenefits as $benefit): ?>
                <div class="gsap-reveal bg-[#fcfdff] rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 mx-auto bg-blue-50/80 rounded-full flex items-center justify-center mb-4 shrink-0">
                        <img src="<?= e($benefit['icon']) ?>" alt="<?= e($benefit['title']) ?>" class="h-full w-full object-contain">
                    </div>
                    <h4 class="text-base font-bold text-[#043B94] mb-2"><?= e($benefit['title']) ?></h4>
                    <p class="text-sm text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Work Process Section -->
<section class="bg-[#f8faff] py-14 lg:py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#022862] tracking-tight mb-3">
                <?= $lang === 'th' ? 'ขั้นตอนการทำงานที่เป็นระบบ' : 'Our Development Process' ?>
            </h2>
            <p class="text-slate-500 text-base md:text-lg">
                <?= $lang === 'th' ? 'ส่งมอบงานอย่างโปร่งใส ตรงเวลา และมีมาตรฐานวิศวกรรมระดับสากล' : 'Transparent, agile delivery adhering to global engineering benchmarks' ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($processSteps as $step): ?>
                <div class="gsap-reveal bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                    <div class="text-4xl font-extrabold text-blue-600/30 mb-3"><?= e($step['num']) ?></div>
                    <h3 class="text-lg font-bold text-[#043B94] mb-2"><?= e($step['title']) ?></h3>
                    <p class="text-sm text-slate-500 leading-relaxed"><?= e($step['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined") {
            gsap.registerPlugin(ScrollTrigger);
            gsap.utils.toArray(".gsap-reveal").forEach((el) => {
                gsap.fromTo(el, 
                    { opacity: 0, y: 30 },
                    {
                        opacity: 1, 
                        y: 0, 
                        duration: 0.8, 
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: el,
                            start: "top 88%",
                            toggleActions: "play none none reverse"
                        }
                    }
                );
            });
        }
    });
</script>
