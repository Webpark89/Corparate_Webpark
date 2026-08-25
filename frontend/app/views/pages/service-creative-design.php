<?php

declare(strict_types=1);

$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-5.png');
$ctaImage = asset_url('images/bg-cta.jpg');
$lang = getCurrentLang();

$designPillars = [
    [
        'icon' => asset_url('images/ERP_1.svg'),
        'title' => $lang === 'th' ? 'ออกแบบตามพฤติกรรมผู้ใช้' : 'Human-Centered Design',
        'desc' => $lang === 'th' ? 'เน้นประสบการณ์ที่ใช้งานง่าย สวยงาม และเข้าถึงอารมณ์ความรู้สึก' : 'Intuitive, beautiful, and emotionally engaging user experiences.',
    ],
    [
        'icon' => asset_url('images/ERP_2.svg'),
        'title' => $lang === 'th' ? 'เอกลักษณ์แบรนด์ที่โดดเด่น' : 'Unique Brand Identity',
        'desc' => $lang === 'th' ? 'สร้างภาพจำที่แตกต่าง ไม่ซ้ำใคร และสะท้อนตัวตนขององค์กร' : 'Distinct visual identity that sets your enterprise apart in the market.',
    ],
    [
        'icon' => asset_url('images/ERP_3.svg'),
        'title' => $lang === 'th' ? 'ดีไซน์ที่สร้าง Conversion' : 'Conversion-Driven UI',
        'desc' => $lang === 'th' ? 'ออกแบบเพื่อดึงดูดสายตาและนำทางผู้ใช้งานไปสู่การตัดสินใจซื้อ' : 'Strategic layouts and visual cues that effortlessly drive conversions.',
    ],
    [
        'icon' => asset_url('images/ERP_4.svg'),
        'title' => $lang === 'th' ? 'มาตรฐาน Design System' : 'Scalable Design Systems',
        'desc' => $lang === 'th' ? 'สร้างระบบคู่มือการออกแบบที่พร้อมส่งต่อนักพัฒนาได้อย่างรวดเร็ว' : 'Comprehensive component libraries for seamless developer handoff.',
    ],
];

$designModules = [
    [
        'name_th' => 'Brand Identity & Corporate Design',
        'name_en' => 'Brand Identity & Corporate Design',
        'desc_th' => 'ออกแบบโลโก้ โทนสี ฟอนต์ และคู่มืออัตลักษณ์องค์กร (Brand CI Manual) เพื่อภาพลักษณ์ที่เป็นมืออาชีพและน่าเชื่อถือ',
        'desc_en' => 'Craft logos, color palettes, typography, and full Brand Guidelines (CI) that elevate enterprise credibility.',
        'icon' => 'ERP_10.svg'
    ],
    [
        'name_th' => 'UI/UX Design for Web & Applications',
        'name_en' => 'UI/UX Design for Web & Applications',
        'desc_th' => 'ออกแบบหน้าตาและประสบการณ์การใช้งานของเว็บไซต์และแอปพลิเคชันมือถือ ด้วย Figma Prototype ที่พร้อมใช้งานจริง',
        'desc_en' => 'Design intuitive, modern web and mobile app interfaces with interactive high-fidelity Figma prototypes.',
        'icon' => 'ERP_11.svg'
    ],
    [
        'name_th' => 'Motion Graphics & 2D/3D Animation',
        'name_en' => 'Motion Graphics & 2D/3D Animation',
        'desc_th' => 'ผลิตวิดีโอแนะนำบริการ โมชันกราฟิก และแอนิเมชันสำหรับนำเสนอสินค้า เพื่อการสื่อสารที่น่าตื่นตาตื่นใจ',
        'desc_en' => 'Create compelling explainer videos, 2D/3D animations, and motion visuals that captivate audiences.',
        'icon' => 'ERP_12.svg'
    ],
    [
        'name_th' => 'Marketing Campaign & Social Media Assets',
        'name_en' => 'Marketing Campaign & Social Media Assets',
        'desc_th' => 'ออกแบบภาพแบนเนอร์ สื่อโฆษณาออนไลน์ และคอนเทนต์โซเชียลมีเดียที่สะดุดตาและช่วยเพิ่มยอดคลิก (CTR)',
        'desc_en' => 'Produce high-converting banner ads, social media visual sets, and campaign promotional graphics.',
        'icon' => 'ERP_13.svg'
    ],
    [
        'name_th' => 'Enterprise Design System & UI Kits',
        'name_en' => 'Enterprise Design System & UI Kits',
        'desc_th' => 'สร้างชุด Component และ Design System มาตรฐานสำหรับองค์กรขนาดใหญ่ เพื่อลดเวลาการพัฒนาผลิตภัณฑ์ใหม่',
        'desc_en' => 'Build robust design token libraries and UI component systems that accelerate enterprise product delivery.',
        'icon' => 'ERP_14.svg'
    ],
    [
        'name_th' => 'Packaging & Corporate Print Media',
        'name_en' => 'Packaging & Corporate Print Media',
        'desc_th' => 'ออกแบบบรรจุภัณฑ์ แคตตาล็อกสินค้า และสื่อสิ่งพิมพ์พรีเมียมที่สะท้อนคุณค่าของแบรนด์ในทุกจุดสัมผัส',
        'desc_en' => 'Design premium packaging, product brochures, and print collateral that reinforce brand excellence.',
        'icon' => 'ERP_15.svg'
    ],
];

$designBenefits = [
    [
        'title' => $lang === 'th' ? 'สร้างภาพจำที่น่าประทับใจ' : 'Memorable Brand Recall',
        'desc' => $lang === 'th' ? 'ครองใจลูกค้าตั้งแต่แรกเห็นด้วยดีไซน์ระดับสากล' : 'Instantly captivate customers with world-class aesthetics.',
        'icon' => asset_url('images/ERP_5.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'เพิ่มมูลค่าสินค้าและบริการ' : 'Higher Perceived Value',
        'desc' => $lang === 'th' ? 'ยกระดับความพรีเมียม เพิ่มโอกาสตั้งราคาที่สูงขึ้น' : 'Enhance brand perception to justify premium product pricing.',
        'icon' => asset_url('images/ERP_6.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'ประสบการณ์ใช้งานที่ลื่นไหล' : 'Seamless Usability',
        'desc' => $lang === 'th' ? 'ลดขั้นตอนสับสน เพิ่มความพึงพอใจของลูกค้า' : 'Eliminate user friction and delight visitors at every step.',
        'icon' => asset_url('images/ERP_7.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'สื่อสารชัดเจน ตรงประเด็น' : 'Clear Visual Messaging',
        'desc' => $lang === 'th' ? 'แปลงข้อมูลซับซ้อนให้เข้าใจง่ายผ่านภาพกราฟิก' : 'Turn complex ideas into clear, digestible visual narratives.',
        'icon' => asset_url('images/ERP_8.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'พร้อมต่อยอดทุกแพลตฟอร์ม' : 'Cross-Platform Ready',
        'desc' => $lang === 'th' ? 'ไฟล์และชุด Assets มาตรฐานพร้อมใช้งานทุกสื่อ' : 'Export-ready assets organized for web, app, and print.',
        'icon' => asset_url('images/ERP_9.svg'),
    ],
];

$designProcess = [
    [
        'num' => '01',
        'title' => $lang === 'th' ? 'Brand Discovery & Moodboard' : 'Brand Discovery & Moodboard',
        'desc' => $lang === 'th' ? 'ทำความเข้าใจบุคลิกแบรนด์ ค่านิยม และสร้าง Moodboard กำหนดทิศทางงานดีไซน์' : 'Uncover brand personality, target demographics, and define visual moodboards.'
    ],
    [
        'num' => '02',
        'title' => $lang === 'th' ? 'Concept Design & Wireframe' : 'Concept Design & Wireframe',
        'desc' => $lang === 'th' ? 'ขึ้นโครงสร้าง Wireframe และนำเสนอคอนเซ็ปต์ดีไซน์หลากหลายทิศทางให้เลือก' : 'Develop structural wireframes and present distinct creative visual directions.'
    ],
    [
        'num' => '03',
        'title' => $lang === 'th' ? 'High-Fidelity Prototyping' : 'High-Fidelity Prototyping',
        'desc' => $lang === 'th' ? 'ลงรายละเอียดสี ฟอนต์ รูปภาพ และสร้าง Prototype ที่สามารถกดเล่นได้เสมือนจริง' : 'Refine colors, typography, assets, and build interactive clickable prototypes.'
    ],
    [
        'num' => '04',
        'title' => $lang === 'th' ? 'Final Assets & Design System' : 'Final Assets & Design System',
        'desc' => $lang === 'th' ? 'ส่งมอบไฟล์ต้นฉบับคุณภาพสูง (Figma, AI, PSD, SVG) พร้อมคู่มือมาตรฐานการใช้งาน' : 'Deliver organized master files and full brand/design guidelines ready for development.'
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
        <img src="<?= e($heroImage) ?>" alt="Creative Design Background" 
            class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/10"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-20 lg:pt-28 lg:pb-32 relative z-10 desktop-wide-container">
        <!-- Mobile Background -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="Creative Design Background" 
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
                            <span class="text-primary font-semibold"><?= $lang === 'th' ? 'ออกแบบสร้างสรรค์' : 'Creative Design' ?></span>
                        </li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-4 tracking-tighter">
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'ออกแบบ' : 'Creative' ?>
                    </span>
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1 lg:ml-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'สร้างสรรค์' : '& Design' ?>
                    </span>
                    <br>
                    <span class="text-xl md:text-2xl lg:text-4xl font-medium leading-snug bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 pb-3 pt-1" style="animation-delay: -3s;">
                        <?= $lang === 'th' ? 'สร้างเอกลักษณ์และประสบการณ์ดิจิทัล<br>ที่ยกระดับภาพลักษณ์แบรนด์ของคุณ' : 'Elevating Brand Identity & Delivering<br>Extraordinary Digital Experiences' ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-4 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-8 font-medium desktop-hero-p">
                    <?= $lang === 'th' 
                        ? 'สร้างสรรค์อัตลักษณ์แบรนด์ (CI), ออกแบบ UI/UX เว็บไซต์และแอปพลิเคชัน ตลอดจนสื่อโมชันกราฟิก เพื่อสร้างความประทับใจและตอบโจทย์เป้าหมายทางธุรกิจ'
                        : 'Holistic design services from Corporate Identity (CI) and modern UI/UX to motion graphics engineered to build brand value and conversion.'
                    ?>
                </p>

                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= $lang === 'th' ? 'ปรึกษาทีมออกแบบ' : 'Consult Designers' ?>
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
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= $lang === 'th' ? 'ดูบริการออกแบบ' : 'Explore Design' ?></span>
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
                        <span class="border-b-[3px] border-primary pb-0.5">CREATIVE</span> DESIGN
                    </span>
                    <h2 class="text-[#043B94] text-3xl xl:text-4xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        <?= $lang === 'th' ? 'ดีไซน์ที่ดี ไม่ใช่แค่ความสวยงาม' : 'Design Beyond Mere Aesthetics' ?>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-6">
                        <?= $lang === 'th' 
                            ? 'เราผสานความสวยงาม ศิลปะ และหลักจิตวิทยาของผู้ใช้งานเข้าด้วยกัน เพื่อให้ทุกงานดีไซน์ไม่เพียงแค่ดูโดดเด่น แต่ยังช่วยแก้ปัญหา เพิ่มความน่าเชื่อถือ และนำพาธุรกิจไปสู่ความสำเร็จ' 
                            : 'We merge artistry, functional UX, and behavioral psychology to create designs that not only captivate the eye but solve real user problems and compound brand equity.'
                        ?>
                    </p>
                </div>
            </div>

            <div class="erp-right-col gsap-reveal flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php foreach ($designPillars as $i => $pillar): 
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
                <?= $lang === 'th' ? 'บริการออกแบบสร้างสรรค์ของเรา' : 'CREATIVE SERVICES' ?>
            </h2>
            <span class="text-[#043B94] font-bold text-lg md:text-xl block">
                <?= $lang === 'th' ? 'ครอบคลุมทุกมิติด้านดีไซน์ ตั้งแต่โลโก้ UI/UX ไปจนถึงวิดีโอแอนิเมชัน' : 'Comprehensive design solutions from brand identity to motion graphics' ?>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($designModules as $module): ?>
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
            <?= $lang === 'th' ? 'สิ่งที่คุณจะได้รับจากบริการออกแบบของเรา' : 'Core Design Values & Benefits' ?>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php foreach ($designBenefits as $benefit): ?>
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
                <?= $lang === 'th' ? 'กระบวนการออกแบบสร้างสรรค์' : 'Our Creative Workflow' ?>
            </h2>
            <p class="text-slate-500 text-base md:text-lg">
                <?= $lang === 'th' ? 'ใส่ใจในทุกรายละเอียด ตั้งแต่แนวคิดตั้งต้นจนถึงชิ้นงานพร้อมใช้งาน' : 'Meticulous attention to detail from initial moodboard to production-ready design assets' ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($designProcess as $step): ?>
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
