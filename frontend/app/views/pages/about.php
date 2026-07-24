<?php
declare(strict_types=1);
/**
 * About company page view — hero, values, services overview, and stats.
 */
$values = $values ?? [];
$timeline = $timeline ?? [];
$team = $team ?? [];
$heroImage = asset_url('images/bg-6.png');
$partners = $partners ?? [];
$trustLogos = $trustLogos ?? [];
$company = $company ?? [];
$contactEmail = $company['contact']['email'] ?? 'oraphan@webpark.co.th';
$contactPhone = $company['contact']['phone'] ?? '095 539 2666';
$contactAddress = $company['contact']['address'] ?? '525/89 ซอยลาดพร้าว126 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310';
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
    /* แก้บั๊ก: คลาสนี้ถูกใช้ในปุ่ม CTA ของ Hero (บรรทัด animate-entrance-up delay-400)
       แต่เดิมไม่มีการประกาศ CSS รองรับ ทำให้ปุ่มไม่มี animation เลย */
    .animate-entrance-up {
        opacity: 0;
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
        /* เพิ่มต่อท้ายในแท็ก <style> */
    .scrollbar-none::-webkit-scrollbar {
        display: none; /* สำหรับ Chrome, Safari และ Opera */
    }
    .scrollbar-none {
        -ms-overflow-style: none;  /* สำหรับ IE และ Edge */
        scrollbar-width: none;  /* สำหรับ Firefox */
    }
    /* Parallax: ขยายรูปเผื่อไว้ล่วงหน้า เพื่อไม่ให้เห็นขอบโหว่ตอนรูปเลื่อนตาม scroll */
    .hero-parallax-img {
        transform: scale(1.15, 1.15);
        will-change: transform;
    }
    @media (min-width: 1025px) {
        .hero-parallax-img {
            transform: scale(1.6) translate(-2%, -5%) !important;
        }
    }
    @media (min-width: 1536px) {
        .hero-parallax-img {
            transform: scale(1.8) translate(-2%, -2%) !important;
        }
    }
    
    /* Custom Layout for iPad Pro Portrait (1024px - 1279px) */
    @media (min-width: 1024px) and (max-width: 1279px) {
        .ipad-pro-about-hero-h1 {
            font-size: 3.5rem !important; /* Scale down to take less background space */
            line-height: 1.1 !important;
        }
        .ipad-pro-about-hero-span1 {
            white-space: nowrap !important; /* Force to stay on one line */
        }
        .ipad-pro-about-hero-p {
            max-width: 90% !important; /* Adjust wrapping */
            padding-right: 2rem !important; /* Prevent widows */
        }
        
        /* Increase font size for Our Approach on iPad Pro */
        .ipad-pro-about-process-step {
            font-size: 1.5rem !important;
        }
        .ipad-pro-about-process-title {
            font-size: 1.25rem !important;
        }
        .ipad-pro-about-process-desc {
            font-size: 1rem !important;
        /* Hide specific br tags on iPad Pro to fix word wrapping */
        .ipad-pro-hidden-br {
            display: none !important;
        }

        /* Reduce top padding for Our Approach section */
        .ipad-pro-approach-section {
            padding-top: 2rem !important;
            padding-bottom: 3rem !important;
        }
    }

    /* แก้ไขปัญหาพื้นหลังจางบน Desktop */
    @media (min-width: 1025px) {
        .desktop-bg-vibrant {
            opacity: 1 !important;
            mix-blend-mode: normal !important;
        }
        .desktop-overlay-vibrant {
            background-image: linear-gradient(to right, #ffffff, transparent) !important;
        }
        /* บังคับให้ส่วนแนวคิดการทำงาน แบ่งเป็น 2 คอลัมน์ (ซ้าย-ขวา) บนจอ Desktop */
        .desktop-concept-grid {
            grid-template-columns: 40% 55% !important;
            gap: 4rem !important;
        }
        .desktop-concept-text {
            text-align: left !important;
            align-items: flex-start !important;
        }
        .desktop-concept-p {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        /* คลาสสำหรับขยายความกว้างเต็มจอ */
        .desktop-wide-container {
            max-width: 1720px !important;
            padding-left: 2.5rem !important;
            padding-right: 2.5rem !important;
        }
        /* ขยายขนาดกล่อง Stats */
        .desktop-stat-card {
            padding: 3rem !important;
            gap: 2.5rem !important;
        }
        .desktop-stat-img {
            width: 8rem !important;
            height: 8rem !important;
        }
        .desktop-stat-h3 {
            font-size: 3.5rem !important;
            line-height: 1 !important;
        }
        .desktop-stat-span {
            font-size: 2rem !important;
        }
        .desktop-stat-p {
            font-size: 1.25rem !important;
            line-height: 1.75rem !important;
            margin-top: 0.5rem !important;
        }
        /* ขยายขนาดกล่องบริการ (Services) */
        .desktop-services-grid {
            gap: 2.5rem !important;
        }
        .desktop-services-card {
            padding: 3rem !important;
        }
        .desktop-services-icon {
            width: 5rem !important;
            height: 5rem !important;
            margin-bottom: 2rem !important;
        }
        .desktop-services-h3 {
            font-size: 1.5rem !important;
            margin-bottom: 1rem !important;
        }
        .desktop-services-p {
            font-size: 1.125rem !important;
            line-height: 1.75rem !important;
        }
        .desktop-services-title {
            font-size: 3rem !important;
        }
        /* ป้องกันข้อความภาษาอังกฤษตกร่อง (Wrap) บนหน้าจอ Desktop */
        .desktop-hero-nowrap {
            white-space: nowrap !important;
        }

        /* ปรับแต่งกล่อง Our Approach ให้ตัวเลขและชื่อหัวข้ออยู่บรรทัดเดียวกัน */
        .desktop-about-process-header {
            flex-direction: row !important;
            align-items: center !important;
            gap: 1rem !important;
            margin-bottom: 0.25rem !important;
        }
        .desktop-about-process-step {
            font-size: 1.4rem !important;
            margin-bottom: 0 !important;
        }
        .desktop-about-process-title {
            font-size: 1.5rem !important;
            margin-bottom: 0 !important;
        }

        /* ปลดล็อคความกว้างข้อความส่วน Hero ให้เรียงบรรทัดสวยงาม */
        .desktop-about-hero-content-wrapper {
            max-width: none !important;
        }
        .desktop-about-hero-p {
            font-size: 1.375rem !important;
            line-height: 1.8 !important;
            max-width: none !important;
        }
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
<section id="about-hero" class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-4 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <!-- Desktop Background Image -->
    <div class="absolute inset-0 z-0 hidden lg:block">
        <img src="<?= e($heroImage) ?>" alt="bg" class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>
    <div class="mx-auto w-full max-w-[1720px] px-6 sm:px-6 lg:px-10 pt-12 pb-16 lg:pt-28 lg:pb-16 relative z-10">
        <!-- Mobile Background Image (Only covers this Hero container) -->
        <div class="absolute inset-0 z-0 overflow-hidden lg:hidden rounded-2xl">
            <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
                class="hero-parallax-img w-full h-full object-cover object-[75%_center] opacity-100 mix-blend-screen">
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-white/40"></div>
            <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent"></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center relative z-10">
            <div class="max-w-2xl desktop-about-hero-content-wrapper">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-6 hidden sm:block">
                    <ol class="inline-flex items-center text-sm md:text-base font-medium text-slate-500">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200">
                                <?= e(t('common.nav_home') !== 'common.nav_home' ? t('common.nav_home') : (getCurrentLang() === 'th' ? 'หน้าแรก' : 'Home')) ?>
                            </a>
                        </li>
                        <li>
                            <span class="text-slate-400" style="margin: 0 4px;">/</span>
                        </li>
                        <li aria-current="page">
                            <span class="text-slate-400"><?= e(t('common.nav_about') !== 'common.nav_about' ? t('common.nav_about') : (getCurrentLang() === 'th' ? 'เกี่ยวกับเรา' : 'About Us')) ?></span>
                        </li>
                    </ol>
                </nav>
                <h1 class="animate-fade-up delay-200 text-5xl sm:text-4xl md:text-6xl lg:text-8xl font-bold leading-[1.1] mb-2 tracking-tighter ipad-pro-about-hero-h1">
                    <span class="bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3 desktop-hero-nowrap ipad-pro-about-hero-span1"><?= e(getCurrentLang() === 'th' ? 'ผู้ให้บริการด้าน' : 'Service Provider for') ?></span><br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] text-bold bg-clip-text text-transparent animate-text-gradient inline-block desktop-hero-nowrap" style="animation-delay: -3s;">ERP / ERM</span>
                </h1>
                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium desktop-about-hero-p ipad-pro-about-hero-p">
                    <?= getCurrentLang() === 'th' ? 'WEBPARK ผู้เชี่ยวชาญด้าน ERP/ERM และระบบดิจิทัล <br class="hidden xl:block">ครบวงจร เราช่วยให้องค์กรของคุณทำงานอย่างชาญฉลาด <br class="hidden xl:block">ด้วยเทคโนโลยีล้ำสมัยแพลตฟอร์มดจิทัลและ AI <br class="hidden xl:block">เพื่อการเติบโตที่ยั่งยืนในยุคดิจิทัล' : 'WEBPARK, expert in ERP/ERM and comprehensive <br class="hidden xl:block">digital systems. We help your organization work smartly <br class="hidden xl:block">with cutting-edge digital platforms and AI <br class="hidden xl:block">for sustainable growth in the digital era.' ?>
                </p>
                <div class="animate-entrance-up delay-400 flex flex-col sm:flex-row items-start gap-4">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base md:text-lg font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(t('common.cta_view_services') !== 'common.cta_view_services' ? t('common.cta_view_services') : (getCurrentLang() === 'th' ? 'ดูบริการของเรา' : 'View Our Services')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="#about" class="group inline-flex items-center gap-4 transition-all hover:-translate-y-0.5">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all duration-300 group-hover:bg-slate-50 group-hover:scale-105 group-hover:shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg sm:text font-semibold transition-colors duration-300 group-hover:text-primary">
                            <?= e(t('common.cta_watch_intro_video') !== 'common.cta_watch_intro_video' ? t('common.cta_watch_intro_video') : (getCurrentLang() === 'th' ? 'ดูวิดีโอแนะนำ' : 'Watch Video')) ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-white pt-10 pb-8 lg:pt-20 lg:pb-24">
    <div class="mx-auto w-full max-w-[1720px] px-6 sm:px-6 lg:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="about-intro-text max-w-xl xl:max-w-2xl opacity-0 translate-y-10">
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] xl:text-5xl font-bold leading-normal py-2 mb-0 lg:mb-1" style="color: #0663F6;">
                    <?= e(t('common.nav_about') !== 'common.nav_about' ? t('common.nav_about') : (getCurrentLang() === 'th' ? 'เกี่ยวกับเรา' : 'About Us')) ?>
                </h2>
                <div class="h-[3px] mb-4 lg:mb-6" style="width: 48px; background-color: #0663F6;"></div>
                <span class="text-xl xl:text-2xl leading-relaxed mb-8 block" style="color: #043B94;">
                    <?= getCurrentLang() === 'th' ? 'เรา คือ WEBPARK<br>ผู้นำด้านโซลูชันธุรกิจดิจิทัล' : 'We are WEBPARK<br>Leaders in Digital Business Solutions' ?>
                </span>
                <p class="text-slate-500 md:text-slate-600 text-lg md:text-lg xl:text-xl leading-relaxed mb-6">
                    <?= getCurrentLang() === 'th' ? 'WEBPARK ก่อตั้งขึ้นด้วยวิสัยทัศน์ที่มุ่งมั่นในการยกระดับศักยภาพธุรกิจไทยผ่านเทคโนโลยีและนวัตกรรมดิจิทัล เราเชี่ยวชาญในการพัฒนาระบบ ERP / ERM แพลตฟอร์มดิจิทัล และโซลูชันที่ตอบโจทย์ทุกความต้องการขององค์กรในทุกอุตสาหกรรม' : 'WEBPARK was founded with a strong vision to elevate the potential of Thai businesses through technology and digital innovation. We specialize in developing ERP / ERM systems, digital platforms, and solutions that meet all organizational needs across industries.' ?>
                </p>
                <p class="text-slate-500 md:text-slate-600 text-lg md:text-lg xl:text-xl leading-relaxed mb-10">
                    <?= getCurrentLang() === 'th' ? 'ด้วยทีมงานผู้เชี่ยวชาญ ประสบการณ์ยาวนาน และความเข้าใจธุรกิจอย่างลึกซึ้ง เราพร้อมเป็นพาร์ทเนอร์ที่เชื่อถือได้ เพื่อช่วยให้องค์กรของคุณก้าวสู่อนาคตได้อย่างมั่นคงและยั่งยืน' : 'With our team of experts, extensive experience, and deep business understanding, we are ready to be your trusted partner to help your organization advance into the future steadily and sustainably.' ?>
                </p>
            </div>
            <div class="about-intro-grid bg-white rounded-[2rem] p-6 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 opacity-0 translate-y-10">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-8 lg:gap-12 relative">
                    <div class="block absolute top-1/2 left-0 w-full h-px bg-slate-100 -translate-y-1/2"></div>
                    <div class="block absolute top-0 left-1/2 w-px h-full bg-slate-100 -translate-x-1/2"></div>
                    <div class="about-intro-item flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 group-hover:scale-110 transition-transform">
                            <img src="<?= asset_url('images/about_1.svg') ?>" alt="Experience" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base"><?= getCurrentLang() === 'th' ? 'ประสบการณ์ยาวนาน<br>มากกว่า 12 ปี' : 'Extensive Experience<br>Over 12 Years' ?></h4>
                    </div>
                    <div class="about-intro-item flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 group-hover:scale-110 transition-transform">
                            <img src="<?= asset_url('images/about_2.svg') ?>" alt="Expert Team" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base"><?= getCurrentLang() === 'th' ? 'ทีมผู้เชี่ยวชาญ<br>พร้อมดูแลคุณ' : 'Expert Team<br>Ready to Serve You' ?></h4>
                    </div>
                    <div class="about-intro-item flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 group-hover:scale-110 transition-transform">
                            <img src="<?= asset_url('images/about_3.svg') ?>" alt="Reliable Solution" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base"><?= getCurrentLang() === 'th' ? 'โซลูชันที่เชื่อถือได้<br>ปลอดภัย มั่นคง' : 'Reliable Solutions<br>Secure & Stable' ?></h4>
                    </div>
                    <div class="about-intro-item flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 group-hover:scale-110 transition-transform">
                            <img src="<?= asset_url('images/about_4.svg') ?>" alt="Results Driven" class="w-full h-full object-contain">
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base"><?= getCurrentLang() === 'th' ? 'มุ่งมั่นผลลัพธ์ที่สร้าง<br>การเติบโตให้ธุรกิจ' : 'Committed to Results<br>Driving Business Growth' ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-white py-8 font-sans">
    <div class="mx-auto w-full max-w-[1720px] px-6 sm:px-6 lg:px-10"> 
        <div class="gsap-dark-cta bg-dark rounded-[2rem] overflow-hidden relative shadow-2xl flex flex-col md:flex-row items-center min-h-[300px] lg:min-h-[400px] opacity-0 translate-y-10">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0b162c] to-transparent z-10"></div>
            <div class="relative z-20 p-8 md:p-12 lg:p-16 w-full md:w-1/2">
                <h2 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight mb-0 md:mb-1">
                    <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
                </h2>
                <div class="mt-2 mb-4" style="width: 48px; height: 3px; background-color: #0663F6;"></div>
                <p class="text-slate-300 text-base md:text-lg xl:text-xl leading-relaxed mb-8 max-w-sm xl:max-w-md">
                    <?= getCurrentLang() === 'th' ? 'Webpark ให้บริการด้าน ERP / ERM, Digital Platform, Online Marketing และ Creative / Design แบบครบวงจร เพื่อช่วยให้องค์กรทำงานได้อย่างมีประสิทธิภาพ เติบโตอย่างเป็นระบบและพร้อมแข่งขันในยุคดิจิทัล' : 'Webpark provides comprehensive services in ERP/ERM, Digital Platform, Online Marketing, and Creative/Design to help organizations work efficiently, grow systematically, and be ready to compete in the digital era.' ?>
                </p>
                <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center gap-2 px-6 py-2.5 xl:px-8 xl:py-3 bg-white text-dark text-sm xl:text-base font-bold rounded-full hover:bg-blue-600 hover:text-white transition-all">
                    <?= e(t('common.cta_view_services') !== 'common.cta_view_services' ? t('common.cta_view_services') : (getCurrentLang() === 'th' ? 'ดูบริการของเรา' : 'View Our Services')) ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 xl:h-5 xl:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
            <div class="absolute right-0 bottom-0 inset-0 z-0">
                <img src="<?= e(asset_url('images/contact.png')) ?>" alt="Portfolio" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>
<section class="bg-white py-8 lg:py-24 font-sans ipad-pro-approach-section">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-6"> 
        <div class="grid grid-cols-1 xl:grid-cols-[40%_55%] gap-12 xl:gap-16 justify-between items-start desktop-concept-grid">
            <div class="xl:top-8 self-start text-left md:text-center xl:text-left mx-0 md:mx-auto xl:mx-0 flex flex-col items-start md:items-center xl:items-start w-full desktop-concept-text">
                <h2 class="text-3xl md:text-4xl font-bold leading-tight mb-3 md:mb-6" style="color: #054FC5;">
                    <?= getCurrentLang() === 'th' ? 'แนวคิดในการทำงานของเรา' : 'Our Approach' ?>
                </h2>
                <p class="text-left md:text-center xl:text-left text-slate-600 text-lg md:text-lg leading-relaxed max-w-2xl xl:max-w-md mx-0 md:mx-auto xl:mx-0 desktop-concept-p">
                    <?= getCurrentLang() === 'th' ? 'เราเชื่อว่าการพัฒนาระบบและโซลูชันดิจิทัลที่ดี <br class="md:hidden"> ไม่ได้เริ่มจากเทคโนโลยีเพียงอย่างเดียว <br class="md:hidden"> แต่เริ่มจากความเข้าใจธุรกิจของคุณ <br class="md:hidden"> เราทำงานแบบพาร์ทเนอร์ร่วมคิด ร่วมสร้าง <br class="md:hidden"> เพื่อให้ทุกโซลูชันที่เราส่งมอบ <span class="whitespace-nowrap">สามารถใช้งานได้</span> <br class="md:hidden"> <span class="whitespace-nowrap">สร้างคุณค่า</span> และช่วยให้ธุรกิจของคุณ <br class="md:hidden"> เติบโตได้อย่างยั่งยืน' : 'We believe that developing great digital systems and solutions doesn\'t start with technology alone, but with understanding your business. We work as a partner to co-think and co-create, ensuring every solution we deliver is practical, creates value, and helps your business grow sustainably.' ?>
                </p>
            </div>
            <div class="flex flex-col gap-4">
                <?php
                $processes = [
                    [
                        'step' => '01', 
                        'title' => getCurrentLang() === 'th' ? 'เข้าใจธุรกิจของคุณ' : 'Understand Your Business',      
                        'desc' => getCurrentLang() === 'th' ? 'เราทำความเข้าใจเป้าหมาย ความต้องการ <br class="md:hidden"> และกระบวนการทำงานของธุรกิจ <br class="md:hidden"> เพื่อออกแบบแนวทาง<span class="whitespace-nowrap">ที่ตอบโจทย์</span>ได้อย่างเหมาะสม' : 'We understand your business goals, needs, and workflows to design the most suitable approach.', 
                        'icon' => asset_url('images/think_1.svg')
                    ],
                    [
                        'step' => '02', 
                        'title' => getCurrentLang() === 'th' ? 'ออกแบบให้ใช้งานได้จริง' : 'Design for Practical Use', 
                        'desc' => getCurrentLang() === 'th' ? 'เราออกแบบระบบให้ใช้งานง่าย <span class="whitespace-nowrap">รองรับการขยายตัว</span> <br class="md:hidden"> <br class="hidden md:inline"> และสอดคล้องกับกระบวนการทำงานของธุรกิจ' : 'We design systems that are easy to use, scalable <br class="hidden md:inline"> and aligned with your business processes.',                              
                        'icon' => asset_url('images/think_2.svg')
                    ],
                    [
                        'step' => '03', 
                        'title' => getCurrentLang() === 'th' ? 'ดูแลอย่างต่อเนื่อง' : 'Continuous Care',       
                        'desc' => getCurrentLang() === 'th' ? 'เราพร้อมให้คำปรึกษาและบริการหลังการขาย<span class="inline md:hidden">อย่างต่อเนื่อง</span> <br class="md:hidden"> <span class="hidden md:inline">อย่างต่อเนื่อง</span> <br class="hidden md:inline"> เพื่อสร้างความมั่นใจตลอดการใช้งาน' : 'We provide continuous consultation and after-sales service <br class="hidden md:inline"> to build confidence throughout usage.',                         
                        'icon' => asset_url('images/think_3.svg')
                    ],
                    [
                        'step' => '04', 
                        'title' => getCurrentLang() === 'th' ? 'รองรับการเติบโต' : 'Support Growth',          
                        'desc' => getCurrentLang() === 'th' ? 'เราพัฒาระบบที่สามารถเติบโตไปพร้อมกับ <br class="md:hidden"> ธุรกิจของคุณ <br class="hidden md:inline"> และพร้อมปรับเปลี่ยนให้รองรับอนาคตขององค์กร' : 'We develop systems that grow with your business <br class="hidden md:inline"> and are ready to adapt for the future of your organization.',                                          
                        'icon' => asset_url('images/think_4.svg')
                    ],
                ];
                ?>
                <?php foreach ($processes as $item): ?>
                    <div class="gsap-process-item bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_2px_12px_rgba(4,59,148,0.06)] flex items-center gap-5 hover:border-blue-200 hover:shadow-md transition-all group opacity-0 translate-y-10">
                        <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center group-hover:scale-105 transition-transform">
                            <img src="<?= e($item['icon']) ?>"
                                 alt="<?= e($item['title']) ?>"
                                 class="w-12 h-12 object-contain"
                                 onerror="this.onerror=null;this.style.display='none'">
                        </div>
                        <div class="flex flex-col gap-1 desktop-about-process-content">
                            <div class="flex flex-row items-center gap-2 md:flex-col md:items-start md:gap-0 desktop-about-process-header">
                                <span class="text-xl font-extrabold desktop-about-process-step ipad-pro-about-process-step" style="color: #043B94;"><?= e($item['step']) ?></span>
                                <h4 class="text-dark font-bold text-lg md:text-base group-hover:text-primary transition-colors desktop-about-process-title ipad-pro-about-process-title"><?= e($item['title']) ?></h4>
                            </div>
                            <p class="text-slate-500 text-base md:text-sm leading-relaxed desktop-about-process-desc ipad-pro-about-process-desc"><?= $item['desc'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<section id="about-services-scroll" class="bg-[#f8fafc] pt-8 pb-8 lg:pt-24 lg:pb-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-6 desktop-wide-container"> 
        <div class="mb-2">
            <h2 class="text-2xl md:text-4xl font-bold text-primary mb-4 desktop-services-title tracking-tight md:tracking-normal whitespace-nowrap">
                <?= getCurrentLang() === 'th' ? 'บริการของเราครอบคลุมทุกด้าน' : 'Comprehensive Services Coverage' ?>
            </h2>
        </div>
        <p class="mb-3 text-sm md:text-base leading-relaxed text-slate-600 max-w-3xl">
            <?= getCurrentLang() === 'th' ? 'ระบบที่ช่วยพัฒนาโซลูชันดิจิทัลที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน' : 'Systems that help develop digital solutions to help your business grow sustainably.' ?>
        </p>
        <div id="service-scroll-container" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none gap-6 pb-6 -mx-4 px-4 md:mx-0 md:px-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:overflow-visible md:snap-none desktop-services-grid">
            <?php
            $services = [
                ['title' => getCurrentLang() === 'th' ? 'พัฒนาระบบซอฟต์แวร์' : 'Software Development', 'desc' => getCurrentLang() === 'th' ? 'ออกแบบและพัฒนาระบบที่ตอบโจทย์ความต้องการ <br class="ipad-pro-hidden-br"> เฉพาะขององค์กร' : 'Design and develop systems tailored to <br class="ipad-pro-hidden-br"> your organization\'s specific needs', 'icon' => asset_url('images/about_5.svg')],
                ['title' => getCurrentLang() === 'th' ? 'ระบบสำหรับองค์กร' : 'Enterprise Systems', 'desc' => getCurrentLang() === 'th' ? 'วางโครงสร้างระบบองค์กรที่แข็งแกร่ง <br class="ipad-pro-hidden-br"> รองรับการทำงานและประสิทธิภาพที่เพิ่มขึ้น' : 'Build strong enterprise system structures <br class="ipad-pro-hidden-br"> supporting increased operations and efficiency', 'icon' => asset_url('images/about_6.svg')],
                ['title' => getCurrentLang() === 'th' ? 'การตลาดออนไลน์' : 'Online Marketing', 'desc' => getCurrentLang() === 'th' ? 'วางกลยุทธ์การตลาดออนไลน์ครบวงจร <br class="ipad-pro-hidden-br"> เพิ่มการมองเห็นและสร้างโอกาสทางธุรกิจ' : 'Plan comprehensive online marketing strategies <br class="ipad-pro-hidden-br"> increasing visibility and business opportunities', 'icon' => asset_url('images/about_7.svg')],
                ['title' => getCurrentLang() === 'th' ? 'เว็บไซต์และแอปพลิเคชัน' : 'Websites & Applications', 'desc' => getCurrentLang() === 'th' ? 'พัฒนาเว็บไซต์และแอปพลิเคชันที่สวยงาม <br class="ipad-pro-hidden-br"> ใช้งานง่าย รองรับทุกอุปกรณ์' : 'Develop beautiful, user-friendly websites <br class="ipad-pro-hidden-br"> and applications supporting all devices', 'icon' => asset_url('images/about_8.svg')],
                ['title' => getCurrentLang() === 'th' ? 'AI Agent และระบบอัตโนมัติ' : 'AI Agent & Automation', 'desc' => getCurrentLang() === 'th' ? 'ผสานพลัง AI และระบบอัตโนมัติ <br class="ipad-pro-hidden-br"> เพื่อเพิ่มประสิทธิภาพและลดต้นทุนการทำงาน' : 'Integrate AI power and automation <br class="ipad-pro-hidden-br"> to increase efficiency and reduce costs', 'icon' => asset_url('images/about_9.svg')],
                ['title' => getCurrentLang() === 'th' ? 'งานออกแบบดิจิทัล' : 'Digital Design', 'desc' => getCurrentLang() === 'th' ? 'สร้างสรรค์งานออกแบบดิจิทัลคุณภาพสูง <br class="ipad-pro-hidden-br"> สื่อสารแบรนด์อย่างมืออาชีพ' : 'Create high-quality digital designs <br class="ipad-pro-hidden-br"> communicating your brand professionally', 'icon' => asset_url('images/about_10.svg')]
            ];
            ?>
            <?php foreach ($services as $item): ?>
                <div class="gsap-scroll-card w-[85vw] md:w-auto shrink-0 snap-center bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group cursor-pointer hover:bg-primary opacity-0 translate-y-10 desktop-services-card">
                    <div class="w-12 h-12 mb-6 group-hover:scale-110 transition-transform desktop-services-icon">
                        <img src="<?= e($item['icon']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-contain">
                    </div>
                    <h3 class="text-dark font-bold text-lg mb-3 group-hover:text-white transition-colors desktop-services-h3">
                        <?= e($item['title']) ?>
                    </h3>
                    <p class="text-slate-500 text-md leading-relaxed group-hover:text-white transition-colors desktop-services-p">
                        <?= $item['desc'] ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Indicator dots for mobile only -->
        <div class="flex justify-center gap-2 mt-2 md:hidden" id="service-dots">
            <?php for ($i = 0; $i < count($services); $i++): ?>
                <button 
                    class="w-2.5 h-2.5 rounded-full transition-all duration-300 <?= $i === 0 ? 'bg-primary w-5' : 'bg-slate-300' ?>" 
                    aria-label="Go to slide <?= $i + 1 ?>"
                    onclick="scrollToService(<?= $i ?>)"
                ></button>
            <?php endfor; ?>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('service-scroll-container');
            const dots = document.querySelectorAll('#service-dots button');
            if (!container || dots.length === 0) return;
            container.addEventListener('scroll', function() {
                const scrollLeft = container.scrollLeft;
                const width = container.clientWidth;
                let closestIndex = 0;
                let minDiff = Infinity;
                const children = container.children;
                const cardElements = Array.from(children).filter(el => el.tagName === 'DIV' && !el.classList.contains('absolute'));
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
        function scrollToService(index) {
            const container = document.getElementById('service-scroll-container');
            if (!container) return;
            const cardElements = Array.from(container.children).filter(el => el.tagName === 'DIV' && !el.classList.contains('absolute'));
            if (cardElements[index]) {
                cardElements[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
        </script>
    </div>
</section>
<section class="bg-slate-50 pt-8 pb-8 lg:pt-16 lg:pb-20 font-sans mb-4">
    <div class="mx-auto w-full max-w-7xl px-6 sm:px-6 lg:px-6 desktop-wide-container"> 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-6">
            <div class="gsap-stat-card bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 flex flex-row items-center justify-start gap-6 border border-slate-100 opacity-0 translate-y-10 desktop-stat-card">
                <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_2.svg" alt="120+ องค์กรชั้นนำ" class="w-20 h-20 md:w-24 md:h-24 object-contain flex-shrink-0 desktop-stat-img" />
                <div class="flex flex-col text-left">
                    <h3 class="text-2xl md:text-3xl font-black text-blue-600 mb-1 tracking-tight desktop-stat-h3"><span class="stat-count" data-target="120">0</span>+ <span class="text-xl md:text-2xl desktop-stat-span"><?= e(getCurrentLang() === 'th' ? 'องค์กรชั้นนำ' : 'Top Orgs') ?></span></h3>
                    <p class="text-slate-600 text-sm md:text-base font-medium desktop-stat-p"><?= e(getCurrentLang() === 'th' ? 'ที่ไว้วางใจ Webpark' : 'Trust Webpark') ?></p>
                </div>
            </div>
            <div class="gsap-stat-card bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 flex flex-row items-center justify-start gap-6 border border-slate-100 opacity-0 translate-y-10 desktop-stat-card">
                <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_1.svg" alt="15+ ปี" class="w-20 h-20 md:w-24 md:h-24 object-contain flex-shrink-0 desktop-stat-img" />
                <div class="flex flex-col text-left">
                    <h3 class="text-2xl md:text-3xl font-black text-blue-600 mb-1 tracking-tight desktop-stat-h3"><span class="stat-count" data-target="15">0</span>+ <span class="text-xl md:text-2xl desktop-stat-span"><?= e(getCurrentLang() === 'th' ? 'ปี' : 'Years') ?></span></h3>
                    <p class="text-slate-600 text-sm md:text-base font-medium desktop-stat-p"><?= e(getCurrentLang() === 'th' ? 'แห่งประสบการณ์ ด้านเทคโนโลยี' : 'Of Technology Experience') ?></p>
                </div>
            </div>
            <div class="gsap-stat-card bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 flex flex-row items-center justify-start gap-6 border border-slate-100 opacity-0 translate-y-10 desktop-stat-card">
                <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_3.svg" alt="50+" class="w-20 h-20 md:w-24 md:h-24 object-contain flex-shrink-0 desktop-stat-img" />
                <div class="flex flex-col text-left">
                    <h3 class="text-2xl md:text-3xl font-black text-blue-600 mb-1 tracking-tight decoration-blue-500 desktop-stat-h3"><span class="stat-count" data-target="50">0</span>+</h3>
                    <p class="text-slate-600 text-sm md:text-base font-medium mt-1 desktop-stat-p"><?= e(getCurrentLang() === 'th' ? 'ระบบและโปรเจกต์ ที่ส่งมอบ' : 'Systems & Projects Delivered') ?></p>
                </div>
            </div>
            <div class="gsap-stat-card bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 flex flex-row items-center justify-start gap-6 border border-slate-100 opacity-0 translate-y-10 desktop-stat-card">
                <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_4.svg" alt="ครบวงจร" class="w-20 h-20 md:w-24 md:h-24 object-contain flex-shrink-0 desktop-stat-img" />
                <div class="flex flex-col text-left">
                    <h3 class="text-2xl md:text-3xl font-black text-blue-600 mb-1 tracking-tight desktop-stat-h3"><?= e(getCurrentLang() === 'th' ? 'ครบวงจร' : 'End-to-End') ?></h3>
                    <p class="text-slate-600 text-sm md:text-base font-medium desktop-stat-p"><?= e(getCurrentLang() === 'th' ? 'ตั้งแต่วางแผนพัฒนา ถึงดูแลหลังบ้าน' : 'From Planning to Maintenance') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);
        // เช็คว่าผู้ใช้ตั้งค่าเครื่องให้ลด Motion ไว้หรือไม่ (Accessibility)
        // ถ้าใช่ จะข้าม animation ที่เกี่ยวกับการเคลื่อนไหวเยอะๆ (parallax, count-up)
        // และแสดงเนื้อหาแบบปกติทันที
        const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        // Helper: reveal element(s) แบบ fade+slide ตอน scroll มาถึง หรือแสดงทันทีถ้า reduced motion
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
                    duration: 0.7,
                    ease: "power2.out",
                    stagger: options.stagger || 0
                });
            });
        }
        // 1. Parallax รูปพื้นหลัง Hero
        if (!prefersReducedMotion) {
            gsap.utils.toArray(".hero-parallax-img").forEach((img) => {
                gsap.to(img, {
                    yPercent: 12,
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#about-hero",
                        start: "top top",
                        end: "bottom top",
                        scrub: true
                    }
                });
            });
        }
        // 2. Section "เกี่ยวกับเรา" — ข้อความและกล่อง grid ไอคอน fade+slide เข้ามาแยกกัน
        revealOnScroll(".about-intro-text");
        revealOnScroll(".about-intro-grid");
        // 2.1 ไอคอน 4 ช่องภายในกล่อง ไล่ทีละอันเล็กน้อย (stagger) หลังจากกล่องแม่โผล่
        if (!prefersReducedMotion) {
            const introItems = gsap.utils.toArray(".about-intro-item");
            gsap.set(introItems, { opacity: 0 }); // ตั้งค่าเริ่มต้นให้โปร่งใสก่อน (ไม่มี CSS class รองรับ opacity-0 อยู่แล้ว)
            introItems.forEach((item, i) => {
                gsap.to(item, {
                    scrollTrigger: {
                        trigger: ".about-intro-grid",
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    opacity: 1,
                    duration: 0.5,
                    delay: 0.2 + i * 0.1,
                    ease: "power1.out"
                });
            });
        }
        // 3. CTA banner สีเข้ม
        revealOnScroll(".gsap-dark-cta");
        // 4. Our Approach — ไล่ทีละขั้นตอนจากบนลงล่าง
        gsap.utils.toArray(".gsap-process-item").forEach((item, i) => {
            if (prefersReducedMotion) {
                gsap.set(item, { y: 0, opacity: 1 });
                return;
            }
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 88%",
                    toggleActions: "play none none reverse"
                },
                y: 0,
                opacity: 1,
                duration: 0.6,
                delay: i * 0.08,
                ease: "power2.out"
            });
        });
        // 5. การ์ดบริการแนวนอน (scroll snap) — ไล่ทีละใบตอน section เลื่อนเข้ามาในจอ
        //    ใช้ section ครอบเป็น trigger ตัวเดียว (การ์ดเรียงแนวนอน ไม่ได้อยู่คนละตำแหน่งแนวตั้ง)
        const scrollCards = gsap.utils.toArray(".gsap-scroll-card");
        if (scrollCards.length) {
            if (prefersReducedMotion) {
                gsap.set(scrollCards, { y: 0, opacity: 1 });
            } else {
                gsap.to(scrollCards, {
                    scrollTrigger: {
                        trigger: "#about-services-scroll",
                        start: "top 80%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.12,
                    ease: "power2.out"
                });
            }
        }
        // 6. การ์ดสถิติ + Count-up ตัวเลข (120+ / 15+ / 50+)
        gsap.utils.toArray(".gsap-stat-card").forEach((card) => {
            const countEl = card.querySelector(".stat-count");
            const target = countEl ? parseInt(countEl.getAttribute("data-target"), 10) || 0 : 0;
            if (prefersReducedMotion) {
                gsap.set(card, { y: 0, opacity: 1 });
                if (countEl) countEl.textContent = target; // แสดงเลขสุดท้ายทันที ไม่ต้องนับ
                return;
            }
            const cardTimeline = gsap.timeline({
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                }
            });
            cardTimeline.to(card, {
                y: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power2.out"
            });
            if (countEl) {
                const counter = { val: 0 };
                cardTimeline.to(counter, {
                    val: target,
                    duration: 1.4,
                    ease: "power1.out",
                    snap: { val: 1 }, // ปัดเป็นเลขจำนวนเต็มระหว่างนับ ไม่ให้เห็นทศนิยม
                    onUpdate: () => {
                        countEl.textContent = Math.round(counter.val);
                    }
                }, "-=0.3"); // เริ่มนับซ้อนกับตอนการ์ด fade เข้ามาเล็กน้อย ให้รู้สึกต่อเนื่อง
            }
        });
    });
</script>