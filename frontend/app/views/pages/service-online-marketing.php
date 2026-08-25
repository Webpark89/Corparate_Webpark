<?php

declare(strict_types=1);

$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-7.png');
$ctaImage = asset_url('images/bg-cta.jpg');
$lang = getCurrentLang();

$marketingPillars = [
    [
        'icon' => asset_url('images/ERP_7.svg'),
        'title' => $lang === 'th' ? 'ขับเคลื่อนด้วยข้อมูลจริง' : 'Data & Analytics Driven',
        'desc' => $lang === 'th' ? 'วางกลยุทธ์จาก Insight ของกลุ่มเป้าหมาย ไม่ใช่การคาดเดา' : 'Strategies formulated from deep audience insights and market data.',
    ],
    [
        'icon' => asset_url('images/ERP_2.svg'),
        'title' => $lang === 'th' ? 'เข้าถึงกลุ่มเป้าหมายแม่นยำ' : 'High-Precision Targeting',
        'desc' => $lang === 'th' ? 'ยิงแอดและสื่อสารตรงสู่ผู้ที่มีแนวโน้มเป็นลูกค้าตัวจริง' : 'Pinpoint ad delivery and messaging to high-intent buyer personas.',
    ],
    [
        'icon' => asset_url('images/ERP_3.svg'),
        'title' => $lang === 'th' ? 'ผลลัพธ์และ ROI วัดผลได้' : 'Measurable ROI & Reports',
        'desc' => $lang === 'th' ? 'ติดตามยอดขาย Conversion และต้นทุนต่อลูกค้าแบบ Real-time' : 'Track sales, conversion rates, and acquisition costs in real-time.',
    ],
    [
        'icon' => asset_url('images/ERP_4.svg'),
        'title' => $lang === 'th' ? 'ปรับปรุงแคมเปญต่อเนื่อง' : 'Continuous Optimization',
        'desc' => $lang === 'th' ? 'A/B Testing และปรับจูนงบประมาณเพื่อให้ได้ผลลัพธ์สูงสุด' : 'Continuous A/B testing and budget reallocation for peak performance.',
    ],
];

$marketingModules = [
    [
        'name_th' => 'Search Engine Optimization (SEO)',
        'name_en' => 'Search Engine Optimization (SEO)',
        'desc_th' => 'ดันอันดับเว็บไซต์สู่หน้าแรก Google แบบยั่งยืน เพิ่ม Organic Traffic คุณภาพด้วย Technical SEO และ Content Marketing',
        'desc_en' => 'Drive sustainable top-tier Google rankings and high-intent organic traffic through Technical SEO and Content Strategy.',
        'icon' => 'ERP_10.svg'
    ],
    [
        'name_th' => 'Performance Advertising (Paid Media)',
        'name_en' => 'Performance Advertising (Paid Media)',
        'desc_th' => 'บริหารจัดการแคมเปญโฆษณา Google Ads, Facebook, TikTok และ Line Ads เพื่อสร้างยอดขายและ Lead ที่มีคุณภาพสูง',
        'desc_en' => 'Scale revenue with ROI-focused paid ads across Google Search, Meta Ads, TikTok Ads, and LINE Official Ads.',
        'icon' => 'ERP_11.svg'
    ],
    [
        'name_th' => 'Content Strategy & Copywriting',
        'name_en' => 'Content Strategy & Copywriting',
        'desc_th' => 'วางแผนและผลิตเนื้อหาที่ดึงดูดใจ เล่าเรื่องแบรนด์อย่างทรงพลัง และกระตุ้นให้เกิดการตัดสินใจซื้อในทุกขั้นตอน',
        'desc_en' => 'Compelling storytelling, expert SEO copywriting, and engaging creative content that guides users to conversion.',
        'icon' => 'ERP_12.svg'
    ],
    [
        'name_th' => 'Social Media Management & Branding',
        'name_en' => 'Social Media Management & Branding',
        'desc_th' => 'ดูแลภาพลักษณ์และการมีส่วนร่วมบนโซเชียลมีเดียครบวงจร สร้างคอมมูนิตี้และขยายฐานแฟนคลับของแบรนด์',
        'desc_en' => 'End-to-end community management, visual content curation, and audience engagement across all major channels.',
        'icon' => 'ERP_13.svg'
    ],
    [
        'name_th' => 'Conversion Rate Optimization (CRO)',
        'name_en' => 'Conversion Rate Optimization (CRO)',
        'desc_th' => 'วิเคราะห์และปรับปรุงหน้า Landing Page รวมถึง Funnel การซื้อขาย เพื่อเปลี่ยนผู้เข้าชมเว็บให้กลายเป็นลูกค้าจริงสูงสุด',
        'desc_en' => 'Analyze user heatmaps, UX friction, and checkout funnels to maximize conversion rates from incoming traffic.',
        'icon' => 'ERP_14.svg'
    ],
    [
        'name_th' => 'Tracking, Analytics & BI Dashboard',
        'name_en' => 'Tracking, Analytics & BI Dashboard',
        'desc_th' => 'ติดตั้ง GA4, Google Tag Manager, Conversion API และสร้าง Data Studio Dashboard เพื่อแสดงผลลัพธ์แบบโปร่งใส',
        'desc_en' => 'Implement GA4, GTM, Meta CAPI, and custom Looker Studio dashboards for real-time marketing intelligence.',
        'icon' => 'ERP_15.svg'
    ],
];

$marketingBenefits = [
    [
        'title' => $lang === 'th' ? 'เจาะกลุ่มเป้าหมายตรงจุด' : 'Laser-Focused Reach',
        'desc' => $lang === 'th' ? 'เข้าถึงลูกค้าที่มีความต้องการซื้อจริง' : 'Reach high-intent customers who are actively looking for you.',
        'icon' => asset_url('images/ERP_5.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'งบโฆษณาคุ้มค่าสูงสุด' : 'Maximized Ad Spend',
        'desc' => $lang === 'th' ? 'ลดการสูญเสียงบด้วยการปรับแต่งอย่างแม่นยำ' : 'Eliminate wasted budget with data-backed bid optimization.',
        'icon' => asset_url('images/ERP_6.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'รายงานผลลัพธ์โปร่งใส' : '100% Transparent Data',
        'desc' => $lang === 'th' ? 'ตรวจสอบยอดขายและตัวเลขได้ตลอด 24 ชม.' : 'Access clear dashboards and performance metrics anytime.',
        'icon' => asset_url('images/ERP_7.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'สร้างแบรนด์อย่างยั่งยืน' : 'Long-Term Brand Value',
        'desc' => $lang === 'th' ? 'อันดับ SEO และ Content อยู่สร้างคุณค่าระยะยาว' : 'SEO authority and content assets that deliver ongoing value.',
        'icon' => asset_url('images/ERP_8.svg'),
    ],
    [
        'title' => $lang === 'th' ? 'เพิ่มยอดขายและขยายสาขา' : 'Scalable Revenue',
        'desc' => $lang === 'th' ? 'สเกลงบประมาณเพื่อขยายยอดขายได้อย่างมั่นใจ' : 'Confident budget scaling that reliably compounds revenue.',
        'icon' => asset_url('images/ERP_9.svg'),
    ],
];

$marketingProcess = [
    [
        'num' => '01',
        'title' => $lang === 'th' ? 'Market Audit & Research' : 'Market Audit & Research',
        'desc' => $lang === 'th' ? 'วิเคราะห์พฤติกรรมลูกค้า คู่แข่ง และช่องว่างทางการตลาดเพื่อกำหนดทิศทาง' : 'Audit current assets, analyze competitors, and pinpoint high-value market opportunities.'
    ],
    [
        'num' => '02',
        'title' => $lang === 'th' ? 'Strategy & Funnel Plan' : 'Strategy & Funnel Plan',
        'desc' => $lang === 'th' ? 'วางแผน Marketing Funnel, สื่อโฆษณา, คีย์เวิร์ด SEO และข้อความสื่อสาร' : 'Structure omnichannel funnels, target keywords, ad creatives, and conversion triggers.'
    ],
    [
        'num' => '03',
        'title' => $lang === 'th' ? 'Execution & Campaign Launch' : 'Execution & Campaign Launch',
        'desc' => $lang === 'th' ? 'เริ่มยิงแคมเปญโฆษณา ผลิตคอนเทนต์ และปรับโครงสร้าง On-page / Off-page SEO' : 'Launch optimized campaigns, roll out SEO architecture, and distribute premium content.'
    ],
    [
        'num' => '04',
        'title' => $lang === 'th' ? 'Optimize & Scale' : 'Optimize & Scale',
        'desc' => $lang === 'th' ? 'วิเคราะห์ข้อมูล Conversion ปรับลดต้นทุนต่อคลิก และขยายงบประมาณในช่องทางที่ทำกำไร' : 'Optimize CPA/ROAS through continuous A/B testing and scale top-performing channels.'
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
        <img src="<?= e($heroImage) ?>" alt="Online Marketing Background" 
            class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/10"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-20 lg:pt-28 lg:pb-32 relative z-10 desktop-wide-container">
        <!-- Mobile Background -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="Online Marketing Background" 
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
                            <span class="text-primary font-semibold"><?= $lang === 'th' ? 'การตลาดออนไลน์' : 'Online Marketing' ?></span>
                        </li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-4 tracking-tighter">
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'การตลาด' : 'Online' ?>
                    </span>
                    <span class="text-4xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block pb-0 pt-2 ml-1 lg:ml-2 desktop-hero-h1">
                        <?= $lang === 'th' ? 'ออนไลน์' : 'Marketing' ?>
                    </span>
                    <br>
                    <span class="text-xl md:text-2xl lg:text-4xl font-medium leading-snug bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 pb-3 pt-1" style="animation-delay: -3s;">
                        <?= $lang === 'th' ? 'ขยายฐานลูกค้าและยอดขาย<br>ด้วยกลยุทธ์การตลาดที่วัดผลได้จริง' : 'Grow Your Reach & Revenue with<br>Data-Driven Digital Marketing' ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-4 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-8 font-medium desktop-hero-p">
                    <?= $lang === 'th' 
                        ? 'วางกลยุทธ์และทำการตลาดออนไลน์ครบวงจร (SEO, Ads, Content & Analytics) มุ่งเน้นผลตอบแทนจากการลงทุน (ROI) และการเติบโตของธุรกิจอย่างต่อเนื่อง'
                        : 'Holistic digital marketing combining SEO, performance advertising, content, and data analytics designed to maximize your return on investment (ROI).'
                    ?>
                </p>

                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= $lang === 'th' ? 'วางแผนการตลาดกับเรา' : 'Start Marketing Plan' ?>
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
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= $lang === 'th' ? 'ดูบริการทั้งหมด' : 'Explore Services' ?></span>
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
                        <span class="border-b-[3px] border-primary pb-0.5">ONLINE</span> MARKETING
                    </span>
                    <h2 class="text-[#043B94] text-3xl xl:text-4xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        <?= $lang === 'th' ? 'การตลาดที่มีทิศทาง และวัดผลได้' : 'Strategic, Measurable Marketing' ?>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-6">
                        <?= $lang === 'th' 
                            ? 'เราไม่เพียงแค่ยิงแอดเพื่อสร้างยอดการมองเห็น แต่เราวางโครงสร้าง Marketing Funnel เพื่อดึงดูดผู้ที่มีความต้องการจริง เปลี่ยนผู้ชมเป็นผู้ซื้อ และสร้างฐานลูกค้าประจำที่พร้อมเติบโตไปพร้อมกับคุณ' 
                            : 'We focus beyond surface impressions. We build robust marketing funnels that attract high-intent prospects, convert visitors into buyers, and generate sustainable recurring revenue.'
                        ?>
                    </p>
                </div>
            </div>

            <div class="erp-right-col gsap-reveal flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php foreach ($marketingPillars as $i => $pillar): 
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
                <?= $lang === 'th' ? 'บริการการตลาดดิจิทัลของเรา' : 'MARKETING SERVICES' ?>
            </h2>
            <span class="text-[#043B94] font-bold text-lg md:text-xl block">
                <?= $lang === 'th' ? 'ครอบคลุมทุกช่องทาง ตั้งแต่อันดับค้นหา โฆษณา ไปจนถึงคอนเทนต์' : 'Omnichannel strategies from search visibility to performance ads' ?>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($marketingModules as $module): ?>
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
            <?= $lang === 'th' ? 'ผลลัพธ์ที่คุณจะได้รับจากการทำการตลาดกับเรา' : 'Measurable Marketing Outcomes' ?>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php foreach ($marketingBenefits as $benefit): ?>
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
                <?= $lang === 'th' ? 'ขั้นตอนการทำงานเชิงกลยุทธ์' : 'Our Strategic Process' ?>
            </h2>
            <p class="text-slate-500 text-base md:text-lg">
                <?= $lang === 'th' ? 'ทุกแคมเปญผ่านการวางแผนและทดสอบ เพื่อการเติบโตอย่างมั่นคง' : 'Systematic methodology to ensure every marketing dollar achieves maximum return' ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($marketingProcess as $step): ?>
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
