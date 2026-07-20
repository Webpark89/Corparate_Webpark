<?php

declare(strict_types=1);

$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-6.png');
$ctaImage = asset_url('images/bg-cta.jpg');

/**
 * Services listing page — grid 2-column card layout.
 *
 * Layout  : Hero Section + heading + 2-column card grid + CTA
 * ข้อมูล  : $mockServices (เปลี่ยนเป็น DB query ได้ภายหลัง)
 * รูป     : ใช้ image_placeholder เดิมจาก mockServices
 */

 $mockServices = [
    [
        'id'                => 1,
        'icon_emoji'        => '🖥️',
        'title'             => 'ERP / ERM',
        'summary'           => getCurrentLang() === 'th' ? 'พัฒนาระบบบริหารจัดการองค์กร เพื่อเพิ่มประสิทธิภาพการทำงาน เชื่อมโยงข้อมูล และรองรับการเติบโตของธุรกิจ' : 'Develop enterprise management systems to increase efficiency, connect data, and support business growth.',
        'image_placeholder' => 'images/erp.png',
        'dropdown_title'    => 'ERP / ERM / HR',
        'subcategories'     => [
            ['label' => 'ERP System',           'href' => route_url('/erp')],
            ['label' => 'Accounting & Finance', 'href' => route_url('/article', ['id' => 39])],
            ['label' => 'Sales / Purchase',     'href' => route_url('/article', ['id' => 40])],
            ['label' => 'Inventory / Warehouse','href' => route_url('/article', ['id' => 41])],
            ['label' => 'Customer Management',  'href' => route_url('/article', ['id' => 42])],
            ['label' => 'Lead Management',      'href' => '#'],
            ['label' => 'Customer Service',     'href' => route_url('/article', ['id' => 29])],
            ['label' => 'Partner / Supplier Management', 'href' => route_url('/article', ['id' => 30])],
            ['label' => 'HRM System',           'href' => route_url('/article', ['id' => 31])],
            ['label' => 'Attendance / Leave',   'href' => route_url('/article', ['id' => 32])],
            ['label' => 'Payroll',              'href' => route_url('/article', ['id' => 14])],
            ['label' => 'Workflow Approval',    'href' => route_url('/article', ['id' => 34])],
        ],
    ],
    [
        'id'                => 2,
        'icon_emoji'        => '🌐',
        'title'             => 'Digital Platform',
        'summary'           => getCurrentLang() === 'th' ? 'ออกแบบและพัฒนาแพลตฟอร์มดิจิทัล เว็บไซต์ และระบบธุรกิจออนไลน์ที่ใช้งานง่าย ยืดหยุ่น และตอบโจทย์องค์กร' : 'Design and develop digital platforms, websites, and online business systems that are user-friendly, flexible, and meet organizational needs.',
        'image_placeholder' => 'images/bg-cta.jpg',
        'dropdown_title'    => 'Platform / Communication / Data',
        'subcategories'     => [
            ['label' => 'Website / Responsive / CMS',    'href' => route_url('/article', ['id' => 35])],
            ['label' => 'Mobile App / Mobile Site',      'href' => route_url('/article', ['id' => 36])],
            ['label' => 'E-commerce',                    'href' => route_url('/article', ['id' => 37])],
            ['label' => 'Custom Web Application',        'href' => '#'],
            ['label' => 'Membership / Portal System',    'href' => '#'],
            ['label' => 'SMS Service',                   'href' => '#'],
            ['label' => 'Email Marketing',               'href' => '#'],
            ['label' => 'Chatbot / Live Chat',           'href' => '#'],
            ['label' => 'Game / Interactive Campaign',   'href' => '#'],
            ['label' => 'Big Data',                      'href' => '#'],
            ['label' => 'E-learning',                    'href' => '#'],
            ['label' => 'Dashboard',                     'href' => '#'],
            ['label' => 'Data Management',               'href' => '#'],
        ],
    ],
    [
        'id'                => 3,
        'icon_emoji'        => '📣',
        'title'             => 'Online Marketing',
        'summary'           => getCurrentLang() === 'th' ? 'วางกลยุทธ์การตลาดออนไลน์ เพื่อเพิ่มการมองเห็น สร้างโอกาสทางธุรกิจ และเพิ่มยอดขายได้อย่างวัดผลได้จริง' : 'Plan online marketing strategies to increase visibility, create business opportunities, and measurably increase sales.',
        'image_placeholder' => 'images/bg-hand.jpg',
        'dropdown_title'    => 'Strategy / Performance / Content',
        'subcategories'     => [
            ['label' => 'Digital Marketing Consultant',  'href' => '#'],
            ['label' => 'Media Planner / PR & Media Strategy', 'href' => '#'],
            ['label' => 'SEO',                           'href' => route_url('/article-detail-mockup')],
            ['label' => 'Social Network',                'href' => '#'],
            ['label' => 'Online Campaign',               'href' => '#'],
            ['label' => 'Monitoring & Analysis',         'href' => '#'],
            ['label' => 'Campaign Performance Report',   'href' => '#'],
            ['label' => 'Return on Investment (ROI)',    'href' => '#'],
            ['label' => 'Productivity Analysis',         'href' => '#'],
            ['label' => 'Content Strategy',              'href' => '#'],
            ['label' => 'Ads Management',                'href' => '#'],
            ['label' => 'Social Media Content',          'href' => '#'],
            ['label' => 'Search Engine Marketing',       'href' => '#'],
        ],
    ],
    [
        'id'                => 4,
        'icon_emoji'        => '🎨',
        'title'             => 'Creative / Design',
        'summary'           => getCurrentLang() === 'th' ? 'สร้างสรรค์งานออกแบบดิจิทัลและคอนเทนต์ที่ช่วยสื่อสารแบรนด์ ทั้ง UI/UX, Graphic, Motion และสื่อสารแบรนด์' : 'Create digital designs and content that help communicate your brand, including UI/UX, Graphic, Motion, and brand communication.',
        'image_placeholder' => 'images/women-office.jpg',
        'dropdown_title'    => 'Design / Motion / Media',
        'subcategories'     => [
            ['label' => 'Web Design',                    'href' => '#'],
            ['label' => 'UX/UI Design',                  'href' => '#'],
            ['label' => 'Cartoon & Character Design',    'href' => '#'],
            ['label' => 'Infographic',                   'href' => '#'],
            ['label' => 'Animation TV & YouTube Online', 'href' => '#'],
            ['label' => 'Motion VDO',                    'href' => route_url('/article', ['id' => 14])],
            ['label' => 'Video Editing',                 'href' => '#'],
            ['label' => 'Presentation Video',            'href' => '#'],
            ['label' => 'E-Magazine',                    'href' => '#'],
            ['label' => 'Print Ads',                     'href' => '#'],
            ['label' => 'Online Banner',                 'href' => '#'],
            ['label' => 'Key Visual Design',             'href' => '#'],
        ],
    ],
];

// Merge database services with mock subcategories
if (isset($services) && is_array($services)) {
    $mergedServices = [];
    foreach ($services as $dbService) {
        $slug = $dbService['slug'];
        $mockMatch = null;
        if ($slug === 'erp-erm' || str_contains(strtolower($dbService['title']), 'erp')) {
            $mockMatch = $mockServices[0];
        } elseif ($slug === 'digital-platform' || str_contains(strtolower($dbService['title']), 'digital')) {
            $mockMatch = $mockServices[1];
        } elseif ($slug === 'online-marketing' || str_contains(strtolower($dbService['title']), 'marketing')) {
            $mockMatch = $mockServices[2];
        } elseif ($slug === 'creative-design' || str_contains(strtolower($dbService['title']), 'creative')) {
            $mockMatch = $mockServices[3];
        } else {
            $mockMatch = $mockServices[0];
        }

        // Get features from the model which now loads from service_features table
        $dbFeatures = $dbService['features'] ?? [];
        $mappedSubcategories = [];
        if (!empty($dbFeatures)) {
            foreach ($dbFeatures as $feature) {
                if (empty(trim($feature))) continue;
                // Keep the ERP link for ERP System, else use #
                $href = ($slug === 'erp-erm' && $feature === 'ERP System') ? route_url('/erp') : '#';
                
                // --- ONLINE MARKETING ---
                if (str_contains(strtolower($feature), 'seo')) $href = route_url('/article-detail-mockup');
                if (str_contains(strtolower($feature), 'payroll')) $href = route_url('/article', ['id' => 14]);
                if (str_contains(strtolower($feature), 'motion')) $href = route_url('/article', ['id' => 14]);
                // --- ERP / ERM / HR ---
                if (str_contains(strtolower($feature), 'customer service')) $href = route_url('/article', ['id' => 29]);
                if (str_contains(strtolower($feature), 'partner')) $href = route_url('/article', ['id' => 30]);
                if (str_contains(strtolower($feature), 'hrm')) $href = route_url('/article', ['id' => 31]);
                if (str_contains(strtolower($feature), 'attendance')) $href = route_url('/article', ['id' => 32]);
                if (str_contains(strtolower($feature), 'workflow')) $href = route_url('/article', ['id' => 34]);
                if (str_contains(strtolower($feature), 'accounting')) $href = route_url('/article', ['id' => 39]);
                if (str_contains(strtolower($feature), 'sales')) $href = route_url('/article', ['id' => 40]);
                if (str_contains(strtolower($feature), 'inventory')) $href = route_url('/article', ['id' => 41]);
                if (str_contains(strtolower($feature), 'customer management')) $href = route_url('/article', ['id' => 42]);
                
                // --- DIGITAL PLATFORMS ---
                if (str_contains(strtolower($feature), 'website')) $href = route_url('/article', ['id' => 35]);
                if (str_contains(strtolower($feature), 'mobile')) $href = route_url('/article', ['id' => 36]);
                if (str_contains(strtolower($feature), 'e-commerce') || str_contains(strtolower($feature), 'ecommerce')) $href = route_url('/article', ['id' => 37]);
                
                $mappedSubcategories[] = [
                    'label' => $feature,
                    'href' => $href
                ];
            }
        }
        
        // Fallback to mock data if database features are empty or corrupted by invalid JSON
        if (empty($mappedSubcategories)) {
            $mappedSubcategories = $mockMatch['subcategories'] ?? [];
        }

        $mergedServices[] = [
            'id' => $dbService['id'],
            'icon_emoji' => $mockMatch['icon_emoji'] ?? '⚙️',
            'title' => $dbService['title'],
            'summary' => $dbService['summary'],
            'image_placeholder' => $dbService['image'] ? 'uploads/' . $dbService['image'] : $mockMatch['image_placeholder'],
            'dropdown_title' => !empty($dbService['details']['dropdown_title']) ? $dbService['details']['dropdown_title'] : $mockMatch['dropdown_title'],
            'subcategories' => $mappedSubcategories,
        ];
    }
    $services = $mergedServices;
} else {
    // Fallback to mock data if database is empty or still has old data
    $services = $mockServices;
}
?>

<style>
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
    /* ซ่อนลูกศร Default ของแท็ก <summary> ในเบราว์เซอร์ต่างๆ */
    details > summary {
        list-style: none;
    }
    details > summary::-webkit-details-marker {
        display: none;
    }
    /* ซ่อนลูกศร Default ของแท็ก <summary> */
    details > summary { list-style: none; }
    details > summary::-webkit-details-marker { display: none; }

    /* แอนิเมชันสำหรับเนื้อหาด้านใน Dropdown เมื่อถูกเปิด */
    details[open] summary ~ * {
        animation: dropDownFade .3s ease-in-out forwards;
    }

    @keyframes dropDownFade {
        0% {
            opacity: 0;
            transform: translateY(-10px); /* เริ่มต้นเลื่อนขึ้นไป 10px */
        }
        100% {
            opacity: 1;
            transform: translateY(0); /* เลื่อนกลับมาตำแหน่งปกติ */
        }
    }
</style>

<section id="services-hero" class="relative font-sans bg-[#f7faff] overflow-hidden m-0 border-none rounded-none">
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
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-scroll { animation: scroll 20s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }
        
        /* บังคับตำแหน่งรูปภาพและ Overlay ด้วย CSS โดยตรง เพื่อเลี่ยงปัญหา Tailwind ไม่คอมไพล์ */
        .hero-bg-img-services {
            object-position: 85% 0% !important;
        }
        .hero-overlay-mobile-services {
            /* เฉดสีขาวเฉพาะฝั่งซ้ายและด้านบนที่ตัวหนังสืออยู่ ปล่อยฝั่งขวาให้โปร่งใสเพื่อให้เห็นรูปภาพ */
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.95) 50%, rgba(255, 255, 255, 0) 85%) !important;
        }

        /* Parallax: ขยายรูปเผื่อไว้ล่วงหน้า เพื่อไม่ให้เห็นขอบโหว่ตอนรูปเลื่อนตาม scroll */
        .hero-parallax-img {
            transform: scale(1.15);
            will-change: transform;
        }

        /* ไอคอนขั้นตอน Our Approach: hover แล้วขยับ+หมุนเบาๆ (ทำงานหลังจาก GSAP เคลียร์ inline transform ตอน entrance เสร็จ) */
        .gsap-approach-icon {
            transition: transform 0.3s ease;
        }
        .gsap-approach-step:hover .gsap-approach-icon {
            transform: scale(1.12) rotate(-6deg);
        }

        /* ไอคอน emoji ในการ์ดบริการ: bounce ตอน hover การ์ด */
        @keyframes iconEmojiBounce {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            30%      { transform: translateY(-6px) rotate(-10deg); }
            55%      { transform: translateY(0) rotate(8deg); }
            75%      { transform: translateY(-2px) rotate(-4deg); }
        }
        .group:hover .service-icon-emoji {
            animation: iconEmojiBounce 0.6s ease-in-out;
        }
        .service-icon-emoji {
            display: inline-block;
        }

        /* Accessibility: เคารพการตั้งค่า Reduce Motion ของผู้ใช้ ลด/ปิด animation แบบ CSS ทั้งหมดในหน้านี้ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>

    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <!-- Mobile Background Image (Only covers this Hero container) -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden">
            <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
                class="hero-parallax-img w-full h-full object-cover hero-bg-img-services opacity-100">
            <div class="absolute inset-0 hero-overlay-mobile-services"></div>
            <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white/50 to-transparent"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            
            <div class="max-w-2xl">
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
                                <span class="text-slate-400"><?= e(t('common.nav_services')) ?></span>
                            </li>
                        </ol>
                    </nav>
                    
                <h1 class="animate-fade-up delay-200 mb-2 tracking-tighter flex flex-col items-start">
                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient py-2">
                        <?= getCurrentLang() === 'th' ? 'ความเชี่ยวชาญ' : 'Expertise' ?>
                    </span>

                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient py-4 -mt-4 md:-mt-6 lg:-mt-8" style="animation-delay: -3s;">
                        <?= getCurrentLang() === 'th' ? 'และจุดเด่น' : '& Strengths' ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                    <?php if (getCurrentLang() === 'th'): ?>
                        มากกว่า 20 ปี ที่เราสร้างสรรค์โซลูชันดิจิทัลครบวงจร<br class="block sm:hidden"> ผสานเทคโนโลยี ความเชี่ยวชาญ และความเข้าใจธุรกิจ<br class="block sm:hidden"> เพื่อเพิ่มประสิทธิภาพ สร้างการเติบโต<br class="block sm:hidden"> และยกระดับองค์กรสู่อนาคตอย่างยั่งยืน
                    <?php else: ?>
                        Over 20 years of creating comprehensive digital solutions. We combine technology, expertise, and business understanding to help organizations increase efficiency and elevate into the future.
                    <?php endif; ?>
                </p>
                <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert') ?>
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
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video') !== 'common.cta_watch_intro_video' ? t('common.cta_watch_intro_video') : (getCurrentLang() === 'th' ? 'ดูวิดีโอแนะนำ' : 'Watch Video')) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="our-services" class="bg-white py-8 lg:py-16 font-sans scroll-mt-6">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold leading-tight tracking-tight mb-0 md:mb-1 gsap-fade-up" style="color: #0663F6 !important;">
            <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
        </h1>
        <div class="mt-2 mb-4 md:mb-6 gsap-fade-up" style="width: 48px; height: 3px; background-color: #0663F6;"></div>

        <span class="text-2xl md:text-3xl font-bold gsap-fade-up w-full max-w-none mb-4 block leading-tight" style="color: #043B94;">
            <?= getCurrentLang() === 'th' ? 'บริการของเรา ครอบคลุมทุกมิติธุรกิจดิจิทัล' : 'Our services cover every dimension of digital business' ?>
        </span>
        <p class="gsap-fade-up text-slate-500 text-lg md:text-[15px] lg:text-base xl:text-[17px] leading-relaxed w-full max-w-none">
            <?= getCurrentLang() === 'th' ? 'Webpark ให้บริการแบบครบวงจร ตั้งแต่การวางแผน ออกแบบ พัฒนา ไปจนถึงการดูแลหลังการใช้งาน เพื่อช่วยให้องค์กรเพิ่มประสิทธิภาพ ลดต้นทุน และเติบโตได้อย่างยั่งยืนในยุคดิจิทัล' : 'Webpark provides end-to-end services, from planning, design, and development to post-deployment support. We help organizations increase efficiency, reduce costs, and grow sustainably in the digital era.' ?>
        </p>
    </div>
</section>


<section id="gsap-services-grid" class="bg-white py-8 lg:py-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-start">

            <?php foreach ($services as $service):
                $sTitle  = (string)($service['title'] ?? '');
                $sSummary= (string)($service['summary'] ?? '');
                $sEmoji  = (string)($service['icon_emoji'] ?? '');
                $imgSrc  = resolve_article_image_url($service['image_placeholder'] ?? '');
                $subcats = (array)($service['subcategories'] ?? []);
                $dropdownText = getCurrentLang() === 'th' ? (string)($service['dropdown_title'] ?? 'ดูหัวข้อย่อย') : (string)($service['dropdown_title'] ?? 'View Subcategories');
            ?>

            <div class="gsap-service-card group rounded-2xl border border-slate-100 bg-white overflow-hidden flex flex-col opacity-0 translate-y-10"
                style="box-shadow: 0 2px 12px 0 rgba(4,59,148,0.07);">

                <div class="relative w-full overflow-hidden bg-slate-100" style="aspect-ratio: 16/9;">
                    <img
                        src="<?= e($imgSrc) ?>"
                        alt="<?= e($sTitle) ?>"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                    >
                </div>

                <div class="flex flex-col flex-1 p-5 lg:p-6">

                    <div class="flex items-center gap-2 mb-2">
                        <span class="service-icon-emoji text-2xl leading-none"><?= e($sEmoji) ?></span>
                        <h2 class="text-lg lg:text-xl font-extrabold" style="color: #022862;"><?= e($sTitle) ?></h2>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        <?= e($sSummary) ?>
                    </p>

                    <div class="mt-auto border-t border-slate-100 pt-3">
                        <details class="group/details">
                            
                            <summary class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-bold cursor-pointer transition-colors duration-150 hover:bg-[#f0f5ff] text-[#022862] list-none">
                                <span><?= e($dropdownText) ?></span>
                                
                                <svg class="w-4 h-4 shrink-0 text-slate-400 transition-transform duration-200 group-open/details:rotate-180"
                                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>

                            <?php if (!empty($subcats)): ?>
                            <div class="pl-4 pr-3 py-2 space-y-2 border-l-2 border-slate-100 ml-3 mt-1 mb-2">
                                <?php foreach ($subcats as $item):
                                    $itemLabel = (string)($item['label'] ?? '');
                                    $itemHref  = (string)($item['href'] ?? '#');
                                ?>
                                <a href="<?= $itemHref ?>" class="group/item flex items-center gap-2 text-sm text-slate-600 hover:text-[#043B94] transition-all duration-300 hover:translate-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0 transition-all duration-300 group-hover/item:bg-[#043B94] group-hover/item:scale-125"></span>
                                    <span><?= e($itemLabel) ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                        </details>
                    </div>

                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<section class="font-sans pb-12">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">
        <div class="gsap-cta-box relative rounded-3xl overflow-hidden opacity-0 translate-y-10"
            style="background: linear-gradient(120deg, #011431 0%, #043B94 55%, #1e40af 100%); min-height: 200px;">

            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/2"
                    style="background: url('<?= e(asset_url('images/bg-cta.jpg')) ?>') center/cover no-repeat; opacity: 0.18;"></div>
                <div class="absolute inset-0"
                    style="background: linear-gradient(to right, #011431 40%, transparent 100%);"></div>
            </div>

            <div class="relative px-8 py-14 md:py-16 text-center" style="z-index: 10;">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    <?= getCurrentLang() === 'th' ? 'พร้อมขับเคลื่อนธุรกิจของคุณไปข้างหน้าหรือยัง?' : 'Ready to drive your business forward?' ?>
                </h2>
                <!-- Desktop Version -->
                <p class="hidden md:block text-base md:text-lg max-w-3xl mx-auto mb-8 leading-relaxed" style="color: #bfdbfe;">
                    <?= getCurrentLang() === 'th' ? 'มาคุยกับทีม Webpark เพื่อค้นหาโซลูชันที่เหมาะกับธุรกิจของคุณ<br>ทั้ง Digital Platform, ระบบ AI และ ERP / ERM ในมุมที่ใช้สำหรับองค์กร' : 'Talk to the Webpark team to find the right solution for your business,<br>including Digital Platforms, AI systems, and ERP / ERM tailored for enterprise use.' ?>
                </p>
                <!-- Mobile Version -->
                <p class="block md:hidden text-base max-w-sm mx-auto mb-8 leading-relaxed" style="color: #bfdbfe;">
                    <?= getCurrentLang() === 'th' ? 'มาคุยกับทีม WEBPARK<br>เพื่อค้นหาโซลูชัน<br>ที่เหมาะกับธุรกิจของคุณ<br>ทั้ง DIGITAL PLATFORM,<br>ระบบ AI และ ERP / ERM<br>ในมุมที่ใช้สำหรับองค์กร' : 'Talk to the WEBPARK team<br>to find the right solution<br>for your business,<br>including DIGITAL PLATFORMS,<br>AI systems, and ERP / ERM<br>tailored for enterprise use.' ?>
                </p>
                <a
                    href="<?= e(route_url('/contact')) ?>"
                    class="inline-flex items-center gap-2 font-bold text-base px-7 py-3 rounded-full transition-colors duration-200"
                    style="background: #ffffff; color: #043B94; box-shadow: 0 4px 14px rgba(0,0,0,0.15);"
                    onmouseover="this.style.background='#eff6ff';"
                    onmouseout="this.style.background='#ffffff';"
                >
                    <?= getCurrentLang() === 'th' ? 'เริ่มต้นปรึกษากับเรา' : 'Start Consulting with Us' ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#eef6ff] py-12 lg:py-16 font-sans">
    <div class="mx-auto max-w-7xl px-6 sm:px-6 lg:px-8">

        <div class="flex flex-col items-center text-center max-w-5xl mx-auto mb-12">
            <div class="flex flex-col items-start mb-4 md:mb-6">
                <span class="text-3xl md:text-4xl font-bold gsap-fade-up mb-1 block" style="color: #054FC5 !important; -webkit-text-fill-color: #054FC5 !important; background: none !important;">
                    <?= getCurrentLang() === 'th' ? 'แนวคิดในการทำงานของเรา' : 'Our Approach' ?>
                </span>
                <div class="mt-1 gsap-fade-up" style="width: 48px; height: 3px; background-color: #0663F6;"></div>
            </div>
            <p class="text-slate-500 text-lg md:text-xl leading-relaxed max-w-4xl text-center mx-auto">
                <?= getCurrentLang() === 'th' ? 'กระบวนการทำงานที่เป็นระบบ เพื่อส่งมอบโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจ และความยั่งยืนของข้อมูลธุรกิจที่องค์กรถือครอง' : 'A systematic work process to deliver digital solutions that meet business needs and ensure the sustainability of business data held by the organization.' ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 max-w-5xl mx-auto">
            <?php
            $approachSteps = [
                [
                    'number' => '01',
                    'icon'   => asset_url('images/think_1.svg'),
                    'title'  => getCurrentLang() === 'th' ? 'เข้าใจธุรกิจของคุณ' : 'Understand Your Business',
                    'desc'   => getCurrentLang() === 'th' ? 'ศึกษาความต้องการ วิเคราะห์ปัญหา และกำหนดแนวทางที่เหมาะสมกับธุรกิจของท่านอย่างแท้จริง' : 'Study requirements, analyze problems, and determine the approach that truly suits your business.',
                ],
                [
                    'number' => '02',
                    'icon'   => asset_url('images/think_2.svg'),
                    'title'  => getCurrentLang() === 'th' ? 'ออกแบบให้ใช้งานได้จริง' : 'Design for Practicality',
                    'desc'   => getCurrentLang() === 'th' ? 'ออกแบบประสบการณ์ใช้งานที่เน้นความง่าย และประสิทธิภาพ ตอบโจทย์ผู้ใช้งานทุกระดับ' : 'Design user experiences focusing on simplicity and efficiency, meeting the needs of users at all levels.',
                ],
                [
                    'number' => '03',
                    'icon'   => asset_url('images/think_3.svg'),
                    'title'  => getCurrentLang() === 'th' ? 'ดูแลอย่างต่อเนื่อง' : 'Continuous Care',
                    'desc'   => getCurrentLang() === 'th' ? 'ให้บริการหลังการขาย พร้อมทีมซัพพอร์ต และอัปเดตระบบอย่างสม่ำเสมอ' : 'Provide after-sales service with a support team and regular system updates.',
                ],
                [
                    'number' => '04',
                    'icon'   => asset_url('images/think_4.svg'),
                    'title'  => getCurrentLang() === 'th' ? 'รองรับการเติบโต' : 'Support Growth',
                    'desc'   => getCurrentLang() === 'th' ? 'พัฒนาระบบที่ยืดหยุ่น สามารถขยายตัว และปรับตามธุรกิจที่เติบโตในอนาคต' : 'Develop flexible systems capable of scaling and adapting as the business grows in the future.',
                ],
            ];

            foreach ($approachSteps as $step):
            ?>
            <div class="gsap-approach-step flex flex-row items-center md:items-start gap-5 md:gap-6 rounded-3xl border border-blue-50/50 bg-white p-6 md:p-8 transition-all duration-300 opacity-0 translate-y-10"
                style="box-shadow: 0 8px 30px -10px rgba(4,59,148,0.08);">

                <div class="gsap-approach-icon w-16 h-16 md:w-20 md:h-20 shrink-0 flex items-center justify-center md:pt-1">
                    <img src="<?= e($step['icon']) ?>"
                         alt="<?= e($step['title']) ?>"
                         class="w-14 h-14 md:w-16 md:h-16 object-contain drop-shadow-sm"
                         onerror="this.onerror=null;this.style.display='none'">
                </div>

                <div class="flex flex-col gap-1 md:gap-1.5 md:pt-1">
                    <span class="gsap-approach-number text-2xl md:text-3xl font-extrabold" style="color: #043B94;"><?= e($step['number']) ?></span>
                    <h3 class="text-xl md:text-2xl font-extrabold mb-1" style="color: #022862;"><?= e($step['title']) ?></h3>
                    <p class="text-slate-600 text-base md:text-lg leading-[1.7]"><?= e($step['desc']) ?></p>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        // ลงทะเบียน ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // เช็คว่าผู้ใช้ตั้งค่าเครื่องให้ลด Motion ไว้หรือไม่ (Accessibility)
        // ถ้าใช่ จะข้าม animation ที่เกี่ยวกับการเคลื่อนไหวเยอะๆ (parallax, pin, elastic pop)
        // และแสดงเนื้อหาแบบปกติทันที โดยยังคง fade เบาๆ ไว้เพื่อไม่ให้กระพริบ
        const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        // 0. Parallax รูปพื้นหลัง Hero — รูปเลื่อนช้ากว่าคอนเทนต์เล็กน้อยตอน scroll ผ่าน section
        if (!prefersReducedMotion) {
            gsap.utils.toArray(".hero-parallax-img").forEach((img) => {
                gsap.to(img, {
                    yPercent: 12,
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#services-hero",
                        start: "top top",
                        end: "bottom top",
                        scrub: true // ผูกตรงกับตำแหน่ง scroll แบบ real-time ไม่มี delay
                    }
                });
            });
        }

        // 1. Animation สำหรับหัวข้อ OUR SERVICES
        if (prefersReducedMotion) {
            gsap.set(".gsap-fade-up", { y: 0, opacity: 1 });
        } else {
            gsap.from(".gsap-fade-up", {
                scrollTrigger: {
                    trigger: "#our-services",
                    start: "top 85%", // เริ่มเมื่อขอบบนของ section เลื่อนมาถึง 85% ของหน้าจอ
                    toggleActions: "play none none reverse" // เล่นเมื่อเจอ ถอยกลับเมื่อเลื่อนขึ้น
                },
                y: 40,
                opacity: 0,
                duration: 0.8,
                stagger: 0.2, // ให้ h1 กับ p ค่อยๆ ขึ้นมาเหลื่อมเวลากันเล็กน้อย
                ease: "power2.out"
            });
        }

        // 2. Animation สำหรับการ์ดบริการ
        // Desktop/Tablet (≥768px): Pin ทั้ง section ไว้ แล้วให้การ์ดโผล่ทีละใบตามระยะที่เลื่อน (scrub)
        //   จนกว่าจะครบ 4 ใบ ถึงจะปลดล็อกให้เลื่อนผ่าน section นี้ไปต่อได้
        // Mobile (<768px): ใช้แบบเดิม (โผล่ทีละใบเมื่อเลื่อนมาถึง ไม่ pin) เพราะจอเล็ก pin ยาวๆ จะกระทบ UX
        const serviceCardsWrapper = document.querySelector("#gsap-services-grid");
        const serviceCards = gsap.utils.toArray(".gsap-service-card");

        if (serviceCardsWrapper && serviceCards.length && prefersReducedMotion) {
            // Reduced motion: แสดงการ์ดทั้งหมดทันที ไม่ pin ไม่ scrub
            gsap.set(serviceCards, { y: 0, opacity: 1 });
        } else if (serviceCardsWrapper && serviceCards.length) {
            ScrollTrigger.matchMedia({

                // --- Desktop / Tablet: Pin + Scrub ---
                "(min-width: 768px)": function () {
                    const cardsTimeline = gsap.timeline({
                        scrollTrigger: {
                            trigger: serviceCardsWrapper,
                            start: "top top+=80", // เผื่อระยะ header/nav ที่ sticky อยู่ด้านบน ปรับเลขนี้ตามความสูง header จริง
                            end: "+=" + (serviceCards.length * 500), // ระยะ scroll รวม ~500px ต่อการ์ด 1 ใบ ปรับได้ตามความรู้สึก
                            pin: true,
                            scrub: 1, // ค่อยๆ ตามการเลื่อน 1 วินาที ให้ความรู้สึกลื่นไหล ไม่กระตุก
                            anticipatePin: 1,
                            // markers: true, // เปิดบรรทัดนี้ตอน debug เพื่อดูตำแหน่ง start/end บนจอ
                        }
                    });

                    serviceCards.forEach((card, index) => {
                        cardsTimeline.to(card, {
                            y: 0,
                            opacity: 1,
                            duration: 1,
                            ease: "power2.out"
                        }, index); // แต่ละใบเริ่ม animate เรียงตามลำดับเวลาในไทม์ไลน์ ทำให้โผล่ทีละใบ
                    });

                    // ฟังก์ชัน cleanup: เรียกอัตโนมัติเมื่อ media query ไม่ตรงแล้ว (เช่น ย่อจอลงต่ำกว่า 768px)
                    return () => {
                        cardsTimeline.scrollTrigger && cardsTimeline.scrollTrigger.kill();
                        cardsTimeline.kill();
                    };
                },

                // --- Mobile: แบบเดิม ไม่ pin ---
                "(max-width: 767px)": function () {
                    const mobileTriggers = serviceCards.map((card) => {
                        return gsap.to(card, {
                            scrollTrigger: {
                                trigger: card,
                                start: "top 85%",
                                toggleActions: "play none none reverse"
                            },
                            y: 0,
                            opacity: 1,
                            duration: 0.6,
                            ease: "power2.out"
                        });
                    });

                    return () => {
                        mobileTriggers.forEach((tween) => {
                            tween.scrollTrigger && tween.scrollTrigger.kill();
                            tween.kill();
                        });
                    };
                }

            });
        }

        // 2.5 Animation สำหรับ CTA Box ท้ายหน้า — fade + slide ขึ้นตอน scroll มาถึง
        const ctaBox = document.querySelector(".gsap-cta-box");
        if (ctaBox) {
            if (prefersReducedMotion) {
                gsap.set(ctaBox, { y: 0, opacity: 1 });
            } else {
                gsap.to(ctaBox, {
                    scrollTrigger: {
                        trigger: ctaBox,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.7,
                    ease: "power2.out"
                });
            }
        }

        // 3. Animation สำหรับ Our Approach
        // การ์ดทั้งใบ fade+slide ขึ้นตามปกติ ส่วนไอคอนกับเลขลำดับ (01-04) จะ "pop" ตามเข้ามาทีหลังเล็กน้อย
        // แบบ elastic ให้ความรู้สึกมีชีวิตชีวา ส่วน hover ของไอคอนคุมด้วย CSS (.gsap-approach-step:hover .gsap-approach-icon)
        const approachSteps = gsap.utils.toArray(".gsap-approach-step");
        approachSteps.forEach((step) => {
            const icon = step.querySelector(".gsap-approach-icon");
            const number = step.querySelector(".gsap-approach-number");

            if (prefersReducedMotion) {
                gsap.set(step, { y: 0, opacity: 1 });
                if (icon) gsap.set(icon, { clearProps: "opacity,transform" });
                if (number) gsap.set(number, { clearProps: "opacity,transform" });
                return;
            }

            // ตั้งค่าเริ่มต้นของไอคอน/เลข ให้เล็กและโปร่งใสก่อน pop เข้ามา
            if (icon) gsap.set(icon, { opacity: 0, scale: 0.5, rotate: 14 });
            if (number) gsap.set(number, { opacity: 0, scale: 0.3 });

            const stepTimeline = gsap.timeline({
                scrollTrigger: {
                    trigger: step, // ให้กล่อง step แต่ละอันเป็น trigger ของตัวเอง
                    start: "top 90%",
                    toggleActions: "play none none reverse"
                }
            });

            stepTimeline.to(step, {
                y: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power2.out"
            });

            if (number) {
                stepTimeline.to(number, {
                    opacity: 1,
                    scale: 1,
                    duration: 0.5,
                    ease: "back.out(2.5)",
                    clearProps: "transform" // เคลียร์ inline transform หลังจบ ให้ไม่ค้างทับ hover/css อื่นภายหลัง
                }, "-=0.35");
            }

            if (icon) {
                stepTimeline.to(icon, {
                    opacity: 1,
                    scale: 1,
                    rotate: 0,
                    duration: 0.55,
                    ease: "back.out(1.7)",
                    clearProps: "transform" // เคลียร์แล้วให้ CSS hover (.gsap-approach-step:hover .gsap-approach-icon) ควบคุมต่อได้
                }, "-=0.3");
            }
        });
    });
</script>