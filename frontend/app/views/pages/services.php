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
            ['label' => 'ERP System',           'href' => '#'],
            ['label' => 'Accounting & Finance', 'href' => '#'],
            ['label' => 'Sales / Purchase',     'href' => '#'],
            ['label' => 'Inventory / Warehouse','href' => '#'],
            ['label' => 'Customer Management',  'href' => '#'],
            ['label' => 'Lead Management',      'href' => '#'],
            ['label' => 'Customer Service',     'href' => '#'],
            ['label' => 'Partner / Supplier Management', 'href' => '#'],
            ['label' => 'HRM System',           'href' => '#'],
            ['label' => 'Attendance / Leave',   'href' => '#'],
            ['label' => 'Payroll',              'href' => '#'],
            ['label' => 'Workflow Approval',    'href' => '#'],
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
            ['label' => 'Website / Responsive / CMS',    'href' => '#'],
            ['label' => 'Mobile App / Mobile Site',      'href' => '#'],
            ['label' => 'E-commerce',                    'href' => '#'],
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
            ['label' => 'SEO',                           'href' => '#'],
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
            ['label' => 'Motion VDO',                    'href' => '#'],
            ['label' => 'Video Editing',                 'href' => '#'],
            ['label' => 'Presentation Video',            'href' => '#'],
            ['label' => 'E-Magazine',                    'href' => '#'],
            ['label' => 'Print Ads',                     'href' => '#'],
            ['label' => 'Online Banner',                 'href' => '#'],
            ['label' => 'Key Visual Design',             'href' => '#'],
        ],
    ],
];

$services = $mockServices;
?>
<!-- 
<section class="relative bg-slate-50 pt-16 pb-12 lg:pt-24 lg:pb-20 font-sans overflow-hidden">
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[300px] h-[300px] bg-indigo-100/40 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            <div class="flex flex-col items-start text-left">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 border" 
                      style="background-color: #eff6ff; color: #043B94; border-color: #bfdbfe;">
                    + SOLUTIONS by Webpark
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight mb-4" style="color: #022862;">
                    ความเชี่ยวชาญ และจุดเด่น
                </h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-8 max-w-xl">
                    เราผสานเทคโนโลยีและนวัตกรรมดิจิทัลเพื่อขับเคลื่อนธุรกิจของคุณอย่างยั่งยืน ครอบคลุมการยกระดับการทำงานด้วยระบบ ERP/ERM, การสร้างสรรค์ Digital Platform, ขยายการเติบوةด้วย Online Marketing และสื่อสารภาพลักษณ์ที่โดดเด่นผ่านเทรนด์ Creative & Design ล่าสุด
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#our-services" 
                       class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full text-white transition-all duration-200"
                       style="background-color: #043B94; box-shadow: 0 4px 14px rgba(4,59,148,0.25);"
                       onmouseover="this.style.backgroundColor='#022862';"
                       onmouseout="this.style.backgroundColor='#043B94';">
                        ดูบริการของเรา
                    </a>
                    <a href="<?= e(route_url('/contact')) ?>" 
                       class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full border transition-all duration-200"
                       style="background-color: #ffffff; color: #043B94; border-color: #cbd5e1;"
                       onmouseover="this.style.backgroundColor='#f8fafc';"
                       onmouseout="this.style.backgroundColor='#ffffff';">
                        ติดต่อเรา
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-md lg:max-w-lg aspect-square flex items-center justify-center">
                    <img 
                        src="<?= e(asset_url('images/hero-hologram.png')) ?>" 
                        alt="Webpark 3D Hologram Solutions" 
                        class="w-full h-auto object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-102"
                        loading="eager"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80'; this.className='w-full h-full object-cover rounded-3xl shadow-xl';"
                    >
                </div>
            </div>

        </div>
    </div>
</section> -->

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

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0 overflow-hidden">
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

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6">
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
                    
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">
                        <?= getCurrentLang() === 'th' ? 'ความเชี่ยวชาญ' : 'Expertise' ?>
                    </span><br>

                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 py-3" style="animation-delay: -3s;">
                        <?= getCurrentLang() === 'th' ? 'และจุดเด่น' : '& Strengths' ?>
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-[15px] sm:text-base md:text-lg leading-[1.65] max-w-lg mb-10 font-medium tracking-normal">
                    <?= getCurrentLang() === 'th' ? 'มากกว่า 20 ปี ที่เราสร้างสรรค์โซลูชันดิจิทัลครบวงจร ผสานเทคโนโลยี ความเชี่ยวชาญ และความเข้าใจธุรกิจ เพื่อเพิ่มประสิทธิภาพ สร้างการเติบโต และยกระดับองค์กรสู่อนาคตอย่างยั่งยืน' : 'Over 20 years of creating comprehensive digital solutions. We combine technology, expertise, and business understanding to help organizations increase efficiency and elevate into the future.' ?>
                </p>
                <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
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

<section id="our-services" class="bg-white pt-16 pb-6 font-sans scroll-mt-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="gsap-fade-up text-2xl md:text-3xl font-extrabold leading-tight mb-2" style="color: #022862;">
            <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
        </h1>

        <span class="text-2xl font-bold gsap-fade-up max-w-2xl mb-1" style="color: #043B94;">
            <?= getCurrentLang() === 'th' ? 'บริการของเรา ครอบคลุมทุกมิติธุรกิจดิจิทัล' : 'Our services cover every dimension of digital business' ?>
        </span>
        <p class="gsap-fade-up text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl">
            <?= getCurrentLang() === 'th' ? 'Webpark ให้บริการแบบครบวงจร ตั้งแต่การวางแผน ออกแบบ พัฒนา ไปจนถึงการดูแลหลังการใช้งาน<br>เพื่อช่วยให้องค์กรเพิ่มประสิทธิภาพ ลดต้นทุน และเติบโตได้อย่างยั่งยืนในยุคดิจิทัล' : 'Webpark provides end-to-end services, from planning, design, and development to post-deployment support.<br>We help organizations increase efficiency, reduce costs, and grow sustainably in the digital era.' ?>
        </p>
    </div>
</section>


<section class="bg-white pb-16 font-sans">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-start">

            <?php foreach ($services as $service):
                $sTitle  = (string)($service['title'] ?? '');
                $sSummary= (string)($service['summary'] ?? '');
                $sEmoji  = (string)($service['icon_emoji'] ?? '');
                $imgSrc  = asset_url($service['image_placeholder'] ?? '');
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
                        <span class="text-2xl leading-none"><?= e($sEmoji) ?></span>
                        <h2 class="text-lg lg:text-xl font-extrabold" style="color: #022862;"><?= e($sTitle) ?></h2>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        <?= e($sSummary) ?>
                    </p>

                    <?php if (!empty($subcats)): ?>
                    <div class="mt-auto border-t border-slate-100 pt-3">
                        <details class="group/details">
                            
                            <summary class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-bold cursor-pointer transition-colors duration-150 hover:bg-[#f0f5ff] text-[#022862] list-none">
                                <span><?= e($dropdownText) ?></span>
                                
                                <svg class="w-4 h-4 shrink-0 text-slate-400 transition-transform duration-200 group-open/details:rotate-180"
                                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>

                            <div class="pl-4 pr-3 py-2 space-y-2 border-l-2 border-slate-100 ml-3 mt-1 mb-2">
                                <?php foreach ($subcats as $item):
                                    $itemLabel = (string)($item['label'] ?? '');
                                    $itemHref  = (string)($item['href'] ?? '#');
                                ?>
                                <a href="<?= e($itemHref) ?>" class="group/item flex items-center gap-2 text-sm text-slate-600 hover:text-[#043B94] transition-all duration-300 hover:translate-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0 transition-all duration-300 group-hover/item:bg-[#043B94] group-hover/item:scale-125"></span>
                                    <span><?= e($itemLabel) ?></span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                            
                        </details>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- <section class="font-sans pb-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden"
             style="background: linear-gradient(120deg, #011431 0%, #043B94 55%, #1e40af 100%); min-height: 200px;">

            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/2"
                     style="background: url('<?= e(asset_url('images/bg-cta.jpg')) ?>') center/cover no-repeat; opacity: 0.18;"></div>
                <div class="absolute inset-0"
                     style="background: linear-gradient(to right, #011431 40%, transparent 100%);"></div>
            </div>

            <div class="relative px-8 py-14 md:py-16 text-center" style="z-index: 10;">
                <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-4 leading-tight">
                    พร้อมขับเคลื่อนธุรกิจของคุณไปข้างหน้าหรือยัง?
                </h2>
                <p class="text-sm md:text-base max-w-xl mx-auto mb-8 leading-relaxed" style="color: #bfdbfe;">
                    มาคุยกับทีม Webpark เพื่อค้นหาโซลูชันที่เหมาะกับธุรกิจของคุณ
                    ทั้ง Digital Platform, ระบบ AI และ ERP / ERM ในมุมที่ใช้สำหรับองค์กร
                </p>
                <a
                    href="<?= e(route_url('/contact')) ?>"
                    class="inline-flex items-center gap-2 font-bold text-sm px-7 py-3 rounded-full transition-colors duration-200"
                    style="background: #ffffff; color: #043B94; box-shadow: 0 4px 14px rgba(0,0,0,0.15);"
                    onmouseover="this.style.background='#eff6ff';"
                    onmouseout="this.style.background='#ffffff';"
                >
                    เริ่มต้นปรึกษากับเรา
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section> -->

<section class="bg-white py-16 font-sans">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="gsap-fade-up text-2xl md:text-4xl font-extrabold leading-tight mb-2" style="color: #022862;">
                <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
            </h2>

            <span class="text-2xl font-bold text-center justify-center gsap-fade-up mb-5 block" style="color: #043B94;">
                <?= getCurrentLang() === 'th' ? 'แนวคิดในการทำงานของเรา' : 'Our Approach' ?>
            </span>
            <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
                <?= getCurrentLang() === 'th' ? 'กระบวนการทำงานที่เป็นระบบ เพื่อส่งมอบโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจ และความยั่งยืนของข้อมูลธุรกิจที่องค์กรถือครอง' : 'A systematic work process to deliver digital solutions that meet business needs and ensure the sustainability of business data held by the organization.' ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $approachSteps = [
                [
                    'number' => '01',
                    'icon'   => asset_url('images/icon-1.png'),
                    'title'  => getCurrentLang() === 'th' ? 'เข้าใจธุรกิจของคุณ' : 'Understand Your Business',
                    'desc'   => getCurrentLang() === 'th' ? 'ศึกษาความต้องการ วิเคราะห์ปัญหา และกำหนดแนวทางที่เหมาะสมกับธุรกิจของท่านอย่างแท้จริง' : 'Study requirements, analyze problems, and determine the approach that truly suits your business.',
                ],
                [
                    'number' => '02',
                    'icon'   => asset_url('images/icon-2.png'),
                    'title'  => getCurrentLang() === 'th' ? 'ออกแบบให้ใช้งานได้จริง' : 'Design for Practicality',
                    'desc'   => getCurrentLang() === 'th' ? 'ออกแบบประสบการณ์ใช้งานที่เน้นความง่าย และประสิทธิภาพ ตอบโจทย์ผู้ใช้งานทุกระดับ' : 'Design user experiences focusing on simplicity and efficiency, meeting the needs of users at all levels.',
                ],
                [
                    'number' => '03',
                    'icon'   => asset_url('images/icon-3.png'),
                    'title'  => getCurrentLang() === 'th' ? 'ดูแลอย่างต่อเนื่อง' : 'Continuous Care',
                    'desc'   => getCurrentLang() === 'th' ? 'ให้บริการหลังการขาย พร้อมทีมซัพพอร์ต และอัปเดตระบบอย่างสม่ำเสมอ' : 'Provide after-sales service with a support team and regular system updates.',
                ],
                [
                    'number' => '04',
                    'icon'   => asset_url('images/icon-4.png'),
                    'title'  => getCurrentLang() === 'th' ? 'รองรับการเติบโต' : 'Support Growth',
                    'desc'   => getCurrentLang() === 'th' ? 'พัฒนาระบบที่ยืดหยุ่น สามารถขยายตัว และปรับตามธุรกิจที่เติบโตในอนาคต' : 'Develop flexible systems capable of scaling and adapting as the business grows in the future.',
                ],
            ];

            foreach ($approachSteps as $step):
            ?>
            <div class="gsap-approach-step flex flex-col items-start rounded-2xl border border-slate-100 bg-white p-6 transition-all duration-300 opacity-0 translate-y-10"
                style="box-shadow: 0 4px 20px 0 rgba(4,59,148,0.05);">

                <div class="w-14 h-14 shrink-0 rounded-xl bg-blue-50/50 flex items-center justify-center mb-4">
                    <img src="<?= e($step['icon']) ?>"
                         alt="<?= e($step['title']) ?>"
                         class="w-8 h-8 object-contain"
                         onerror="this.onerror=null;this.style.display='none'">
                </div>

                <div class="flex flex-col gap-1.5">
                    <span class="text-xl font-extrabold" style="color: #043B94;"><?= e($step['number']) ?></span>
                    <h3 class="text-base font-extrabold" style="color: #022862;"><?= e($step['title']) ?></h3>
                    <p class="text-slate-500 text-xs md:text-sm leading-relaxed"><?= e($step['desc']) ?></p>
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

        // 1. Animation สำหรับหัวข้อ OUR SERVICES
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

        // 2. Animation สำหรับการ์ดบริการ (แก้ไขใหม่: ให้ทำงานแยกทีละใบ)
        const serviceCards = gsap.utils.toArray(".gsap-service-card");
        serviceCards.forEach((card) => {
            gsap.to(card, {
                scrollTrigger: {
                    trigger: card, // ให้ตัวมันเองเป็นคนเช็คตำแหน่งว่าเลื่อนมาถึงหรือยัง
                    start: "top 85%", // เมื่อขอบบนของการ์ดใบนี้ ถึงจุด 85% ของจอ ค่อยทำงาน
                    toggleActions: "play none none reverse"
                },
                y: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power2.out"
            });
        });

        // 3. Animation สำหรับ Our Approach (แก้ไขใหม่เหมือนกัน)
        const approachSteps = gsap.utils.toArray(".gsap-approach-step");
        approachSteps.forEach((step) => {
            gsap.to(step, {
                scrollTrigger: {
                    trigger: step, // ให้กล่อง step แต่ละอันเป็น trigger ของตัวเอง
                    start: "top 90%",
                    toggleActions: "play none none reverse"
                },
                y: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power2.out"
            });
        });
    });
</script>