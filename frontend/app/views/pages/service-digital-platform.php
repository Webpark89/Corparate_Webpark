<?php
declare(strict_types=1);

$pageTitle = getCurrentLang() === 'th' ? 'แพลตฟอร์มดิจิทัล (Digital Platform) | WEBPARK' : 'Digital Platform Solutions | WEBPARK';
$heroBgImage = asset_url('images/digital-platform-hero-bg.png');
?>
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-entrance-up {
        opacity: 1 !important;
        animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 50ms; }
    .delay-200 { animation-delay: 100ms; }
    .delay-300 { animation-delay: 150ms; }
    .delay-400 { animation-delay: 200ms; }
    .delay-500 { animation-delay: 250ms; }

    /* Float / Parallax on Hero 3D Graphic */
    @keyframes heroFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(0.5deg); }
    }
    .animate-hero-float {
        animation: heroFloat 6s ease-in-out infinite;
    }

    /* Gradient Typography */
    .dp-gradient-title-dark {
        background: linear-gradient(135deg, #1e293b 0%, #475569 50%, #0f172a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #1e293b;
    }
    .dp-gradient-title-blue {
        background: linear-gradient(135deg, #004ecc 0%, #0663F6 40%, #0284c7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #0663F6;
    }

    /* Standalone Core Layout Rules */
    .dp-intro-main-card {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        background-color: #ffffff !important;
    }
    @media (min-width: 1280px) {
        .dp-intro-main-card {
            flex-direction: row !important;
        }
        .dp-intro-card-left {
            width: 32% !important;
            flex-shrink: 0 !important;
        }
    }
    .dp-intro-right-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        width: 100% !important;
        flex: 1 !important;
    }
    @media (max-width: 1024px) {
        .dp-intro-right-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    @media (max-width: 640px) {
        .dp-intro-right-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }
    }

    /* Solution Card Glow & Hover (Completely Still - NO Bounce) */
    .dp-solution-card {
        transform: none !important;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .dp-solution-card:hover {
        transform: none !important;
        box-shadow: 0 10px 25px -5px rgba(6, 99, 246, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
        border-color: #bfdbfe;
    }

    /* Benefit Card Hover */
    .dp-benefit-card {
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .dp-benefit-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px -8px rgba(2, 40, 98, 0.1);
        border-color: #93c5fd;
    }

    /* Portfolio Mockup Card Hover */
    .dp-port-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .dp-port-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 45px -12px rgba(4, 59, 148, 0.16);
    }

    /* ===================================================
       IPAD PORTRAIT ONLY: MATCH ONLINE MARKETING EXACTLY
       Specifically: (min-width: 760px) and (max-width: 1024px) and (orientation: portrait)
       =================================================== */
    @media (min-width: 760px) and (max-width: 1024px) and (orientation: portrait) {
        /* Section 1: Hero Section (2-line title, text on left, 3D graphic on right) */
        .dp-hero-container {
            padding-top: 4.5rem !important;
            padding-bottom: 5rem !important;
        }
        .dp-hero-left-col {
            max-width: 68% !important;
            margin-left: 0 !important;
        }
        .dp-breadcrumb-nav,
        .dp-breadcrumb-list {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            align-items: center !important;
        }
        .dp-breadcrumb-list li,
        .dp-breadcrumb-list a,
        .dp-breadcrumb-list span {
            white-space: nowrap !important;
            display: inline-block !important;
            word-break: keep-all !important;
        }
        .dp-hero-h1-wrapper {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem !important;
        }
        .dp-hero-h1 {
            font-size: 3.5rem !important;
            font-weight: 900 !important;
            line-height: 1.08 !important;
            display: block !important;
            white-space: normal !important;
        }
        .dp-hero-p {
            font-size: 0.95rem !important;
            line-height: 1.65 !important;
            font-weight: 500 !important;
            color: #475569 !important;
            max-width: 100% !important;
            margin-bottom: 1.75rem !important;
        }
        .dp-hero-btn-container {
            flex-direction: row !important;
            align-items: center !important;
            gap: 1rem !important;
            flex-wrap: nowrap !important;
        }
        .dp-hero-bg-img {
            object-position: 88% center !important;
        }

        /* Section 2: Intro Summary Card (Header top, 4 pillars horizontal) */
        .dp-intro-section {
            padding-top: 0px !important;
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .dp-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        .dp-intro-main-card {
            flex-direction: column !important;
        }
        .dp-intro-card-left {
            width: 100% !important;
            max-width: 100% !important;
            flex: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-right: none !important;
            padding: 2rem 2.25rem 1.5rem !important;
        }
        .dp-intro-right-grid {
            width: 100% !important;
            flex: none !important;
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
        .dp-intro-pillar-card {
            padding: 1.5rem 0.5rem !important;
            border-bottom: none !important;
            border-right: 1px solid #f1f5f9 !important;
            border-top: none !important;
            border-left: none !important;
        }
        .dp-intro-pillar-card:last-child {
            border-right: none !important;
        }
        .dp-intro-pillar-card h3 {
            font-size: 0.95rem !important;
            line-height: 1.35 !important;
            min-height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin-bottom: 0.35rem !important;
        }
        .dp-intro-pillar-card p {
            font-size: 0.775rem !important;
            line-height: 1.35 !important;
            text-align: center !important;
        }
        .dp-intro-pillar-card svg {
            width: 3.25rem !important;
            height: 3.25rem !important;
            margin-bottom: 0.5rem !important;
        }

        /* Section 3: 10 Solutions Grid (2 columns x 5 rows like landscape) */
        .dp-solutions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .dp-solution-card {
            padding: 1.25rem !important;
        }

        /* Section 4: Benefits Grid (บน 2, กลาง 2, ล่าง 1 ยาว) */
        .dp-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .dp-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .dp-benefits-grid h3 {
            font-size: 1.15rem !important;
            min-height: 2.75rem !important;
            line-height: 1.35 !important;
            margin-bottom: 0.5rem !important;
        }
        .dp-benefits-grid p {
            font-size: 0.925rem !important;
            line-height: 1.55 !important;
        }
        .dp-benefits-grid svg {
            width: 3.75rem !important;
            height: 3.75rem !important;
            margin-bottom: 0.75rem !important;
        }

        /* Card 5: ล่าง 1 ยาว (Spans 2 columns, wide layout) */
        .dp-benefit-card-last {
            grid-column: span 2 / span 2 !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 2rem !important;
            padding: 2rem 3rem !important;
            text-align: left !important;
        }
        .dp-benefit-card-last svg {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
            width: 4.25rem !important;
            height: 4.25rem !important;
        }
        .dp-benefit-card-last .dp-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .dp-benefit-card-last h3 {
            text-align: left !important;
            justify-content: flex-start !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
            font-size: 1.25rem !important;
        }
        .dp-benefit-card-last p {
            text-align: left !important;
            font-size: 0.95rem !important;
        }

        /* Section 5: Portfolio Showcase Grid (4 cards in 1 row like landscape) */
        .dp-showcase-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .dp-showcase-grid .dp-port-card {
            border-radius: 1rem !important;
        }
        .dp-showcase-grid h3 {
            font-size: 0.9rem !important;
        }
        .dp-showcase-grid p {
            font-size: 0.75rem !important;
        }
        .dp-showcase-grid span {
            font-size: 0.7rem !important;
        }
    }

    /* iPad Mini Portrait (760px - 820px) Specific Fine-tuning */
    @media (min-width: 760px) and (max-width: 820px) and (orientation: portrait) {
        .dp-hero-h1 {
            font-size: 3rem !important;
        }
        .dp-hero-left-col {
            max-width: 68% !important;
        }
        .dp-benefits-grid h3 {
            font-size: 1.05rem !important;
        }
        .dp-benefits-grid p {
            font-size: 0.85rem !important;
        }
        .dp-benefit-card-last h3 br,
        .dp-benefit-card-last p br {
            display: none !important;
        }
        .dp-benefit-card-last h3 {
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
        }
        .dp-benefit-card-last p {
            white-space: nowrap !important;
        }
        .dp-showcase-grid h3 {
            font-size: 0.825rem !important;
        }
    }

    /* iPad Pro Portrait (821px - 1100px) Specific Fine-tuning */
    @media (min-width: 821px) and (max-width: 1100px) and (orientation: portrait) {
        .dp-hero-h1 {
            font-size: 4rem !important;
        }
        .dp-hero-left-col {
            max-width: 65% !important;
        }
        /* Single line text for Benefits cards on iPad Pro Portrait */
        .dp-benefits-grid h3 br,
        .dp-benefits-grid p br {
            display: none !important;
        }
        .dp-benefits-grid h3 {
            font-size: 1.15rem !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.5rem !important;
        }
        .dp-benefits-grid p {
            font-size: 0.9rem !important;
            white-space: nowrap !important;
        }
        .dp-showcase-grid h3 {
            font-size: 0.95rem !important;
        }
    }

    /* Mobile & iPad Spacing and 2-2-1 Benefits Layout (Both Mobile and iPad up to 1440px) */
    @media (max-width: 1440px) {
        .dp-intro-section {
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .dp-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        #dp-solutions,
        .dp-solutions-section {
            padding-top: 4.5rem !important;
        }

        /* Section 4 Benefits: บน 2, กลาง 2, ล่าง 1 ยาว สำหรับ Mobile และ iPad ทุกรุ่น */
        .dp-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .dp-benefits-grid > div {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            padding: 1.25rem 0.5rem !important;
            border-radius: 1rem !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last {
            grid-column: span 2 / span 2 !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 1rem !important;
            padding: 1.25rem 1rem !important;
            text-align: left !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last .dp-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last svg {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
        }
        /* ข้อความในการ์ดแบบบรรทัดต่อบรรทัด */
        .dp-benefits-grid h3 br {
            display: none !important;
        }
        .dp-benefits-grid h3 {
            font-size: clamp(0.72rem, 2.8vw, 1.25rem) !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.35rem !important;
        }
        .dp-benefits-grid p {
            font-size: clamp(0.65rem, 2vw, 0.95rem) !important;
            line-height: 1.35 !important;
        }
    }

    @media (max-width: 640px) {
        .dp-benefits-grid {
            gap: 0.5rem !important;
        }
        .dp-benefits-grid > div {
            padding: 1.25rem 0.35rem !important;
        }
        .dp-benefits-grid svg {
            width: 2.5rem !important;
            height: 2.5rem !important;
            margin-bottom: 0.4rem !important;
        }
        .dp-benefits-grid h3 {
            font-size: clamp(0.65rem, 2.6vw, 0.85rem) !important;
            white-space: nowrap !important;
            letter-spacing: -0.01em !important;
        }
        .dp-benefits-grid p {
            font-size: 0.62rem !important;
            white-space: normal !important;
            line-height: 1.3 !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last {
            gap: 0.75rem !important;
            padding: 1rem 0.75rem !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last p {
            white-space: normal !important;
        }
    }

    @media (min-width: 760px) and (max-width: 1440px) {
        .dp-benefits-grid {
            gap: 1.25rem !important;
        }
        .dp-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .dp-benefits-grid > div.dp-benefit-card-last {
            gap: 2rem !important;
            padding: 2rem 3rem !important;
        }
        .dp-benefits-grid p {
            white-space: nowrap !important;
        }
        .dp-benefits-grid p br {
            display: none !important;
        }
    }
</style>

<!-- ==========================================
     SECTION 1: HERO SECTION
=========================================== -->
<section id="dp-hero" class="relative font-sans bg-[#f7faff] overflow-hidden pt-8 pb-16 lg:pt-16 lg:pb-28">
    <!-- Desktop Background Banner -->
    <div class="absolute inset-0 z-0 hidden lg:block overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Digital Platform Background" 
            class="w-full h-full object-cover object-[right_center]"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/50 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <!-- Mobile Background Banner -->
    <div class="absolute inset-0 z-0 lg:hidden overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Digital Platform Background" 
            class="w-full h-full object-cover object-[75%_center] dp-hero-bg-img"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/80 to-white/30"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8 relative z-10 dp-hero-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            <!-- Left Column: Title & Description -->
            <div class="lg:col-span-7 xl:col-span-7 dp-hero-left-col">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb" class="animate-entrance-up delay-100 mb-5 dp-breadcrumb-nav">
                    <ol class="inline-flex items-center text-sm md:text-base font-medium text-slate-500 dp-breadcrumb-list whitespace-nowrap flex-nowrap">
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200 whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'หน้าแรก' : 'Home') ?>
                            </a>
                        </li>
                        <li class="whitespace-nowrap"><span class="text-slate-400 mx-2.5 whitespace-nowrap">/</span></li>
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/services')) ?>" class="hover:text-primary transition-colors duration-200 whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'บริการของเรา' : 'Services') ?>
                            </a>
                        </li>
                        <li class="whitespace-nowrap"><span class="text-slate-400 mx-2.5 whitespace-nowrap">/</span></li>
                        <li aria-current="page" class="whitespace-nowrap">
                            <span class="text-slate-400 font-semibold whitespace-nowrap"><?= getCurrentLang() === 'th' ? 'แพลตฟอร์มดิจิทัล' : 'Platform Digital' ?></span>
                        </li>
                    </ol>
                </nav>

                <!-- Hero Title (Gradient) -->
                <h1 class="animate-entrance-up delay-200 mb-4 tracking-tight flex flex-col items-start leading-[1.08] dp-hero-h1-wrapper">
                    <?php if (getCurrentLang() === 'th'): ?>
                        <span class="dp-gradient-title-dark font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase dp-hero-h1">
                            แพลตฟอร์ม
                        </span>
                        <span class="dp-gradient-title-blue font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase dp-hero-h1">
                            ดิจิทัล
                        </span>
                    <?php else: ?>
                        <span class="dp-gradient-title-dark font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase dp-hero-h1">
                            PLATFORM
                        </span>
                        <span class="dp-gradient-title-blue font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase dp-hero-h1">
                            DIGITAL
                        </span>
                    <?php endif; ?>
                </h1>

                <!-- Hero Subtitle & Description -->
                <p class="animate-entrance-up delay-300 text-slate-600 text-base md:text-lg lg:text-xl leading-relaxed max-w-2xl mb-8 font-medium dp-hero-p" style="text-wrap: balance;">
                    <?php if (getCurrentLang() === 'th'): ?>
                        บริการออกแบบและพัฒนาระบบ แพลตฟอร์มดิจิทัล และเว็บแอปพลิเคชัน ที่ตอบโจทย์การทำงานขององค์กรอย่างแท้จริง เพิ่มประสิทธิภาพการทำงาน ลดขั้นตอน และรองรับการขยายตัวของธุรกิจในอนาคต
                    <?php else: ?>
                        Comprehensive digital platform design, web application, and enterprise system development tailored to your business operations—increasing efficiency, automating workflows, and scaling sustainably.
                    <?php endif; ?>
                </p>

                <!-- Hero Action Buttons -->
                <div class="animate-entrance-up delay-400 flex flex-wrap items-center gap-4 dp-hero-btn-container">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 bg-primary hover:bg-blue-700 text-white text-base font-semibold rounded-full shadow-lg shadow-blue-500/25 transition-all duration-300 hover:-translate-y-0.5 whitespace-nowrap">
                        <?= e(getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert') ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#dp-solutions" class="inline-flex items-center gap-3.5 transition-all hover:-translate-y-0.5 group">
                        <div class="h-12 w-12 bg-white flex items-center justify-center rounded-full shadow-md border border-slate-200 transition-all duration-300 group-hover:bg-slate-50 group-hover:scale-105 group-hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 fill-current transition-transform duration-300 group-hover:scale-110 ml-0.5" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-700 text-base font-semibold transition-colors duration-300 group-hover:text-primary whitespace-nowrap">
                            <?= e(getCurrentLang() === 'th' ? 'ดูวิดีโอแนะนำ' : 'Watch Intro Video') ?>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Right Column Spacer to preserve layout -->
            <div class="lg:col-span-5 xl:col-span-5 hidden lg:block"></div>

        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 2: INTRO SUMMARY CARD (Exact Mockup Match)
=========================================== -->
<section class="bg-white pt-2 pb-6 lg:pt-4 lg:pb-10 font-sans dp-intro-section">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-20 -mt-12 lg:-mt-20 xl:-mt-24 dp-intro-card-container">
        <div class="gsap-dp-card-container w-full rounded-2xl lg:rounded-3xl bg-white flex flex-col xl:flex-row items-stretch shadow-[0_10px_40px_rgba(4,59,148,0.08)] border border-slate-200/80 dp-intro-main-card">
            
            <!-- Left Block: Digital Platform คืออะไร (Spacious, matching right mockup) -->
            <div class="gsap-dp-about-left w-full xl:w-[32%] 2xl:w-[30%] shrink-0 flex flex-col justify-center p-8 sm:p-10 lg:p-12 xl:p-14 border-b xl:border-b-0 xl:border-r border-slate-200/80 bg-white dp-intro-card-left">
                <div class="inline-flex flex-col items-start mb-2">
                    <span class="text-[#0663F6] font-extrabold text-2xl lg:text-3xl tracking-tight uppercase">
                        DIGITAL PLATFORM
                    </span>
                    <div class="w-14 h-[3.5px] bg-[#0663F6] mt-1.5 mb-4"></div>
                </div>
                <h2 class="text-[#043B94] text-xl lg:text-2xl font-bold leading-tight mb-4">
                    <?= getCurrentLang() === 'th' ? 'Digital Platform คืออะไร' : 'What is a Digital Platform' ?>
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm lg:text-[14.5px] leading-relaxed max-w-md">
                    <?php if (getCurrentLang() === 'th'): ?>
                        Digital Platform คือ โครงสร้างดิจิทัลที่เชื่อมต่อคน กระบวนการ และข้อมูลเข้าด้วยกันบนแพลตฟอร์มเดียว ช่วยให้องค์กรทำงานได้ต่อเนื่อง สร้างประสบการณ์ที่ดีให้ผู้ใช้ และขยายระบบได้อย่างยืดหยุ่นและปลอดภัย
                    <?php else: ?>
                        Digital Platform is a digital infrastructure connecting people, processes, and data onto a unified platform—enabling organizations to operate continuously, deliver superior user experiences, and scale flexibly and securely.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Right Grid: 4 Pillars from Mockup (Spacious, large cards) -->
            <div class="gsap-dp-about-right flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 w-full divide-y sm:divide-y-0 sm:divide-x divide-slate-200/80 dp-intro-right-grid">
                
                <!-- Pillar 1: เชื่อมต่อทุกช่องทาง -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors dp-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <line x1="32" y1="26" x2="48" y2="18" stroke="#93c5fd" stroke-width="2.5" stroke-dasharray="2.5 2.5" />
                            <line x1="32" y1="26" x2="16" y2="46" stroke="#93c5fd" stroke-width="2.5" stroke-dasharray="2.5 2.5" />
                            <line x1="32" y1="26" x2="48" y2="46" stroke="#93c5fd" stroke-width="2.5" stroke-dasharray="2.5 2.5" />
                            <circle cx="32" cy="24" r="14" fill="#0284c7" />
                            <ellipse cx="32" cy="24" rx="7" ry="14" stroke="#e0f2fe" stroke-width="1.5" />
                            <line x1="18" y1="24" x2="46" y2="24" stroke="#e0f2fe" stroke-width="1.5" />
                            <line x1="22" y1="17" x2="42" y2="17" stroke="#e0f2fe" stroke-width="1.2" />
                            <line x1="22" y1="31" x2="42" y2="31" stroke="#e0f2fe" stroke-width="1.2" />
                            <circle cx="48" cy="18" r="8" fill="#10b981" />
                            <path d="M45 22.5c0-2 1.5-3 3-3s3 1 3 3" stroke="white" stroke-width="1.3" fill="none" />
                            <circle cx="48" cy="15.5" r="2.2" fill="white" />
                            <circle cx="16" cy="46" r="8" fill="#ef4444" />
                            <path d="M13 50.5c0-2 1.5-3 3-3s3 1 3 3" stroke="white" stroke-width="1.3" fill="none" />
                            <circle cx="16" cy="43.5" r="2.2" fill="white" />
                            <circle cx="48" cy="46" r="8" fill="#0663F6" />
                            <path d="M45 50.5c0-2 1.5-3 3-3s3 1 3 3" stroke="white" stroke-width="1.3" fill="none" />
                            <circle cx="48" cy="43.5" r="2.2" fill="white" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'เชื่อมต่อทุกช่องทาง' : 'Omnichannel Connectivity' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'รวมทุกช่องทางออนไลน์<br>และออฟไลน์ไว้ในที่เดียว' : 'Unify online and offline channels into one cohesive hub.' ?>
                    </p>
                </div>

                <!-- Pillar 2: ออกแบบตามธุรกิจ -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors dp-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="11" y="15" width="40" height="36" rx="4" fill="#bae6fd" />
                            <rect x="7" y="13" width="9" height="40" rx="4.5" fill="#38bdf8" />
                            <line x1="22" y1="22" x2="44" y2="22" stroke="#0284c7" stroke-width="1.8" stroke-dasharray="2 2" />
                            <line x1="22" y1="28" x2="36" y2="28" stroke="#0284c7" stroke-width="1.8" stroke-dasharray="2 2" />
                            <circle cx="42" cy="38" r="11" fill="#0284c7" />
                            <circle cx="42" cy="38" r="4.5" fill="#bae6fd" />
                            <path d="M42 25v4M42 47v4M29 38h4M51 38h4M32.8 28.8l2.8 2.8M48.4 44.4l2.8 2.8M32.8 47.2l2.8-2.8M48.4 31.6l2.8-2.8" stroke="#0284c7" stroke-width="3.5" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ออกแบบตามธุรกิจ' : 'Custom Business Fit' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'ปรับแต่งได้ตามความต้องการ<br>และกระบวนการขององค์กร' : 'Tailor-made to organizational goals and operational flows.' ?>
                    </p>
                </div>

                <!-- Pillar 3: ขยายระบบได้ง่าย -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors dp-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M32 54L13 44l19-10 19 10-19 10z" fill="#f59e0b" />
                            <path d="M13 44v5.5l19 10 19-10.5V44L32 54 13 44z" fill="#d97706" />
                            <path d="M32 39L17 31l15-8 15 8-15 8z" fill="#06b6d4" />
                            <path d="M17 31v4.5l15 8 15-8V31l-15 8-15-8z" fill="#0891b2" />
                            <g fill="#10b981">
                                <path d="M22 24l-4.5 4.5h2.8v8h3.4v-8h2.8L22 24z" />
                                <path d="M32 10l-5.5 5.5h3.5v11h4V15.5h3.5L32 10z" />
                                <path d="M42 24l-4.5 4.5h2.8v8h3.4v-8h2.8L42 24z" />
                            </g>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ขยายระบบได้ง่าย' : 'Effortless Scalability' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'โครงสร้างยืดหยุ่น<br>รองรับการเติบโต' : 'Flexible architecture built for rapid business expansion.' ?>
                    </p>
                </div>

                <!-- Pillar 4: ใช้งานง่ายและปลอดภัย -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors dp-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M32 6l20 8v18c0 16-20 28-20 28S12 48 12 32V14l20-8z" fill="url(#shieldGradLarge)" />
                            <path d="M24 32l6 6 14-14" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                            <defs>
                                <linearGradient id="shieldGradLarge" x1="12" y1="6" x2="52" y2="60" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#38bdf8" />
                                    <stop offset="1" stop-color="#0284c7" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ใช้งานง่ายและปลอดภัย' : 'Intuitive & Secure' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'ประสบการณ์ใช้งานที่ดี<br>พร้อมมาตรฐานความปลอดภัย' : 'Optimal user experience backed by strict security standards.' ?>
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 3: 10 DIGITAL PLATFORM SOLUTIONS
=========================================== -->
<section id="dp-solutions" class="bg-white pt-4 lg:pt-8 pb-12 lg:pb-20 font-sans dp-solutions-section">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header (Exact Match to Mockup) -->
        <div class="text-center max-w-3xl mx-auto mb-8 lg:mb-10">
            <div class="inline-flex flex-col items-start mb-2">
                <span class="text-[#0663F6] font-extrabold text-2xl sm:text-3xl tracking-tight uppercase">
                    DIGITAL PLATFORM SOLUTIONS
                </span>
                <div class="w-14 h-[3.5px] bg-[#0663F6] mt-1 mb-2.5"></div>
            </div>
            <h2 class="text-base sm:text-lg md:text-xl font-bold text-[#043B94] leading-snug">
                <?= getCurrentLang() === 'th' ? 'ครบทุกโซลูชัน ตอบโจทย์ทุกการใช้งานขององค์กร' : 'All-in-one solutions answering every enterprise operational need' ?>
            </h2>
        </div>

        <?php
        $solutions = [
            [
                'title' => 'CORPORATE WEBSITE',
                'subtitle' => 'เว็บไซต์องค์กรเพื่อการสื่อสารภาพลักษณ์และข้อมูลอย่างมืออาชีพ',
                'bullets' => [
                    'ดีไซน์สวย รองรับทุกอุปกรณ์',
                    'เพิ่มความน่าเชื่อถือให้แบรนด์',
                    'รองรับ SEO และการวัดผลทางข้อมูล'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="18" width="80" height="60" rx="6" fill="#e2e8f0"/>
                        <path d="M8 24C8 20.6863 10.6863 18 14 18H82C85.3137 18 88 20.6863 88 24V28H8V24Z" fill="#1e293b"/>
                        <circle cx="16" cy="23" r="2.5" fill="#ef4444"/>
                        <circle cx="23" cy="23" r="2.5" fill="#f59e0b"/>
                        <circle cx="30" cy="23" r="2.5" fill="#10b981"/>
                        <rect x="12" y="32" width="72" height="42" rx="3" fill="#f8fafc"/>
                        <circle cx="48" cy="53" r="16" fill="#0284c7"/>
                        <ellipse cx="48" cy="53" rx="8" ry="16" stroke="#bae6fd" stroke-width="1.6"/>
                        <line x1="32" y1="53" x2="64" y2="53" stroke="#bae6fd" stroke-width="1.6"/>
                        <line x1="36" y1="45" x2="60" y2="45" stroke="#bae6fd" stroke-width="1.4"/>
                        <line x1="36" y1="61" x2="60" y2="61" stroke="#bae6fd" stroke-width="1.4"/>
                    </svg>
                '
            ],
            [
                'title' => 'CUSTOMER PORTAL',
                'subtitle' => 'พอร์ทัลสำหรับลูกค้า จัดการข้อมูลและบริการได้ด้วยตนเอง',
                'bullets' => [
                    'เข้าดูข้อมูลและประวัติการใช้งาน',
                    'แจ้งปัญหาและติดตามสถานะ',
                    'เพิ่มความพึงพอใจและลดภาระงาน'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M42 74H54L56 80H40L42 74Z" fill="#334155"/>
                        <rect x="34" y="80" width="28" height="3" rx="1.5" fill="#1e293b"/>
                        <rect x="12" y="16" width="72" height="58" rx="6" fill="#1e293b"/>
                        <circle cx="18" cy="22" r="2" fill="#ef4444"/>
                        <circle cx="24" cy="22" r="2" fill="#f59e0b"/>
                        <circle cx="30" cy="22" r="2" fill="#10b981"/>
                        <rect x="16" y="27" width="64" height="42" rx="3" fill="#bae6fd"/>
                        <rect x="25" y="32" width="46" height="32" rx="4" fill="white" stroke="#e2e8f0" stroke-width="1"/>
                        <circle cx="37" cy="43" r="6" fill="#ef4444"/>
                        <circle cx="37" cy="41" r="2.5" fill="white"/>
                        <path d="M33 47C33 45 35 44 37 44C39 44 41 45 41 47" stroke="white" stroke-width="1.2" stroke-linecap="round"/>
                        <rect x="47" y="38" width="18" height="3.5" rx="1.5" fill="#f97316"/>
                        <rect x="47" y="44" width="14" height="2.5" rx="1" fill="#f59e0b"/>
                        <rect x="30" y="55" width="36" height="2.5" rx="1" fill="#0284c7"/>
                        <rect x="30" y="59" width="24" height="2" rx="1" fill="#94a3b8"/>
                        <circle cx="82" cy="20" r="1.5" fill="#f59e0b"/>
                        <circle cx="10" cy="38" r="1.5" fill="#0663F6"/>
                    </svg>
                '
            ],
            [
                'title' => 'E-LEARNING PLATFORM',
                'subtitle' => 'แพลตฟอร์มการเรียนออนไลน์ครบวงจร',
                'bullets' => [
                    'จัดการคอร์สและบทเรียนออนไลน์',
                    'ระบบแบบทดสอบและการติดตามผล',
                    'ออกแบบการเรียนรู้แบบออนไลน์'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="22" y="80" width="32" height="4" rx="2" fill="#cbd5e1"/>
                        <path d="M34 72H42L40 80H36L34 72Z" fill="#94a3b8"/>
                        <rect x="14" y="28" width="48" height="44" rx="5" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="1.5"/>
                        <rect x="18" y="32" width="40" height="32" rx="3" fill="#f8fafc"/>
                        <rect x="22" y="43" width="22" height="15" rx="3" fill="#ef4444"/>
                        <path d="M31 47.5L37 50.5L31 53.5V47.5Z" fill="white"/>
                        <rect x="20" y="60" width="26" height="4" rx="1.5" fill="#10b981"/>
                        <rect x="22" y="64" width="22" height="2" rx="1" fill="#059669"/>
                        <path d="M47 52C49 50 52 50 54 52" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
                        <path d="M45 56C48 54 53 54 56 56" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="50.5" cy="61" r="1.5" fill="#f59e0b"/>
                        <path d="M22 24L38 18L54 24L38 30L22 24Z" fill="#475569"/>
                        <path d="M30 27.5V33C30 35 38 37 38 37C38 37 46 35 46 33V27.5" fill="#334155"/>
                        <path d="M48 24V32" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="48" cy="33" r="1.5" fill="#f59e0b"/>
                    </svg>
                '
            ],
            [
                'title' => 'E-COMMERCE PLATFORM',
                'subtitle' => 'ร้านค้าออนไลน์ รองรับการขายทุกช่องทาง',
                'bullets' => [
                    'จัดการสินค้าและคำสั่งซื้อ',
                    'ระบบชำระเงินและโปรโมชั่น',
                    'เชื่อมต่อขนส่งและสต็อก'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="42" y="24" width="18" height="14" rx="2" fill="#f43f5e"/>
                        <rect x="60" y="28" width="16" height="10" rx="2" fill="#06b6d4"/>
                        <path d="M36 38H76L70 60H40L36 38Z" fill="#fbbf24"/>
                        <rect x="44" y="42" width="3" height="14" rx="1" fill="#f59e0b"/>
                        <rect x="52" y="42" width="3" height="14" rx="1" fill="#f59e0b"/>
                        <rect x="60" y="42" width="3" height="14" rx="1" fill="#f59e0b"/>
                        <path d="M22 30H27L33 64H72" stroke="#64748b" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="43" cy="72" r="6" fill="#334155"/>
                        <circle cx="43" cy="72" r="2.5" fill="#94a3b8"/>
                        <circle cx="67" cy="72" r="6" fill="#334155"/>
                        <circle cx="67" cy="72" r="2.5" fill="#94a3b8"/>
                    </svg>
                '
            ],
            [
                'title' => 'BOOKING / RESERVATION SYSTEM',
                'subtitle' => 'ระบบจองและนัดหมายอัตโนมัติ',
                'bullets' => [
                    'จัดการตารางเวลาและคิวแบบเรียลไทม์',
                    'แจ้งเตือนนัดหมายอัตโนมัติ',
                    'ลดความผิดพลาดและซ้ำซ้อน'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="18" y="24" width="52" height="50" rx="6" fill="#e0f2fe"/>
                        <path d="M18 30C18 26.6863 20.6863 24 24 24H64C67.3137 24 70 26.6863 70 30V34H18V30Z" fill="#f43f5e"/>
                        <rect x="28" y="18" width="4" height="10" rx="2" fill="#cbd5e1"/>
                        <rect x="42" y="18" width="4" height="10" rx="2" fill="#cbd5e1"/>
                        <rect x="56" y="18" width="4" height="10" rx="2" fill="#cbd5e1"/>
                        <circle cx="30" cy="44" r="2.5" fill="#0284c7"/>
                        <circle cx="44" cy="44" r="2.5" fill="#0284c7"/>
                        <circle cx="58" cy="44" r="2.5" fill="#0284c7"/>
                        <circle cx="30" cy="56" r="2.5" fill="#0284c7"/>
                        <circle cx="44" cy="56" r="2.5" fill="#0284c7"/>
                        <circle cx="64" cy="64" r="16" fill="white" stroke="#f43f5e" stroke-width="3"/>
                        <circle cx="64" cy="64" r="13" fill="#fff1f2"/>
                        <path d="M64 56V64L69 68" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                '
            ],
            [
                'title' => 'MEMBERSHIP / LOYALTY SYSTEM',
                'subtitle' => 'ระบบสมาชิกและสะสมคะแนน',
                'bullets' => [
                    'สะสมแต้มและแลกของรางวัล',
                    'จัดการระดับสมาชิกและสิทธิพิเศษ',
                    'สร้างความสัมพันธ์และความภักดีต่อแบรนด์'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="18" y="32" width="60" height="42" rx="6" fill="white" stroke="#cbd5e1" stroke-width="1.5"/>
                        <path d="M18 38C18 34.6863 20.6863 32 24 32H72C75.3137 32 78 34.6863 78 38V42H18V38Z" fill="#ef4444"/>
                        <circle cx="34" cy="55" r="9" fill="#f97316"/>
                        <circle cx="34" cy="52" r="4" fill="white"/>
                        <path d="M28 62C28 59 31 58 34 58C37 58 40 59 40 62" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="48" y="49" width="22" height="4" rx="2" fill="#334155"/>
                        <rect x="48" y="56" width="16" height="3" rx="1.5" fill="#94a3b8"/>
                        <rect x="48" y="62" width="12" height="2.5" rx="1" fill="#f59e0b"/>
                        <polygon points="48,12 52,22 63,23 55,30 58,41 48,35 38,41 41,30 33,23 44,22" fill="#fbbf24" stroke="#d97706" stroke-width="1.5"/>
                    </svg>
                '
            ],
            [
                'title' => 'DASHBOARD & REPORTING',
                'subtitle' => 'แดชบอร์ดสรุปและวิเคราะห์ข้อมูลธุรกิจ',
                'bullets' => [
                    'รายงานข้อมูลแบบเรียลไทม์',
                    'วิเคราะห์แนวโน้มและสถิติสำคัญ',
                    'สนับสนุนการตัดสินใจของผู้บริหาร'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M42 74H54L56 80H40L42 74Z" fill="#64748b"/>
                        <rect x="34" y="80" width="28" height="3" rx="1.5" fill="#334155"/>
                        <rect x="14" y="18" width="68" height="56" rx="6" fill="#0f172a"/>
                        <rect x="18" y="22" width="60" height="42" rx="3" fill="#f8fafc"/>
                        <circle cx="33" cy="43" r="10" fill="#0284c7"/>
                        <path d="M33 43L33 33A10 10 0 0 1 43 43Z" fill="#fbbf24"/>
                        <path d="M33 43L43 43A10 10 0 0 1 33 53Z" fill="#10b981"/>
                        <rect x="50" y="44" width="4" height="12" rx="1" fill="#0663F6"/>
                        <rect x="56" y="38" width="4" height="18" rx="1" fill="#10b981"/>
                        <rect x="62" y="32" width="4" height="24" rx="1" fill="#f59e0b"/>
                        <rect x="68" y="41" width="4" height="15" rx="1" fill="#f43f5e"/>
                    </svg>
                '
            ],
            [
                'title' => 'CMS / CONTENT MANAGEMENT',
                'subtitle' => 'ระบบจัดการเนื้อหาเว็บไซต์อย่างอิสระ',
                'bullets' => [
                    'แก้ไขข้อความและรูปภาพได้ง่าย',
                    'จัดการโครงสร้างเว็บและบทความ',
                    'อัปเดตข้อมูลได้เองโดยไม่ต้องเขียนโค้ด'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 20C22 17.7909 23.7909 16 26 16H54L72 34V76C72 78.2091 70.2091 80 68 80H26C23.7909 80 22 78.2091 22 76V20Z" fill="#0ea5e9"/>
                        <path d="M54 16V30C54 32.2091 55.7909 34 58 34H72" fill="#38bdf8"/>
                        <rect x="30" y="40" width="22" height="3.5" rx="1.5" fill="white"/>
                        <rect x="30" y="48" width="28" height="3.5" rx="1.5" fill="white"/>
                        <rect x="30" y="56" width="20" height="3.5" rx="1.5" fill="white"/>
                        <g transform="translate(42, 44) rotate(-35)">
                            <rect x="0" y="0" width="10" height="28" rx="2" fill="#fbbf24"/>
                            <path d="M0 28L5 36L10 28H0Z" fill="#fed7aa"/>
                            <path d="M3.5 34L5 36L6.5 34H3.5Z" fill="#1e293b"/>
                            <rect x="0" y="0" width="10" height="6" fill="#f43f5e"/>
                        </g>
                    </svg>
                '
            ],
            [
                'title' => 'INTRANET / EMPLOYEE PORTAL',
                'subtitle' => 'พอร์ทัลบุคลากรและระบบทำงานภายใน',
                'bullets' => [
                    'ศูนย์รวมข่าวสารและประกาศองค์กร',
                    'ระบบส่งคำร้องและขออนุมัติออนไลน์',
                    'ส่งเสริมการทำงานร่วมกันในทีม'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="42" y="12" width="12" height="6" rx="2" fill="#f59e0b"/>
                        <rect x="45" y="18" width="6" height="8" fill="#94a3b8"/>
                        <rect x="18" y="24" width="60" height="52" rx="6" fill="#e0f2fe" stroke="#38bdf8" stroke-width="2"/>
                        <rect x="36" y="27" width="24" height="4" rx="2" fill="#94a3b8"/>
                        <circle cx="34" cy="48" r="9" fill="#ef4444"/>
                        <circle cx="34" cy="45" r="4" fill="white"/>
                        <path d="M28 55C28 52 31 51 34 51C37 51 40 52 40 55" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="48" y="42" width="22" height="4" rx="2" fill="#0284c7"/>
                        <rect x="48" y="50" width="16" height="3" rx="1.5" fill="#f59e0b"/>
                        <rect x="48" y="56" width="18" height="3" rx="1.5" fill="#64748b"/>
                        <rect x="26" y="66" width="44" height="2.5" rx="1" fill="#cbd5e1"/>
                    </svg>
                '
            ],
            [
                'title' => 'API & SYSTEM INTEGRATION',
                'subtitle' => 'ระบบเชื่อมต่อและผสานรวมข้อมูล API',
                'bullets' => [
                    'เชื่อมต่อ ERP, CRM และระบบภายนอก',
                    'ถ่ายโอนข้อมูลอัตโนมัติและปลอดภัย',
                    'สถาปัตยกรรมยืดหยุ่น รองรับการขยายตัว'
                ],
                'icon_svg' => '
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 drop-shadow-sm shrink-0" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M38 24C32 24 28 28 28 34V42C28 46 24 48 20 48C24 48 28 50 28 54V62C28 68 32 72 38 72" stroke="#0284c7" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M58 24C64 24 68 28 68 34V42C68 46 72 48 76 48C72 48 68 50 68 54V62C68 68 64 72 58 72" stroke="#0663F6" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                '
            ],
        ];
        ?>

        <!-- Solutions Grid (Exact Match to Mockup) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 dp-solutions-grid">
            <?php foreach ($solutions as $sol): ?>
                <div class="dp-solution-card bg-white rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-start gap-5 sm:gap-6 group">
                    <div class="shrink-0 flex items-center justify-center">
                        <?= $sol['icon_svg'] ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[#0663F6] text-lg sm:text-xl font-extrabold tracking-tight mb-1 group-hover:text-blue-700 transition-colors">
                            <?= e($sol['title']) ?>
                        </h3>
                        <p class="text-slate-700 text-xs sm:text-[13px] font-semibold mb-2.5 leading-snug">
                            <?= e($sol['subtitle']) ?>
                        </p>
                        <ul class="space-y-1.5 text-slate-500 text-xs sm:text-[13px]">
                            <?php foreach ($sol['bullets'] as $bullet): ?>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0663F6] shrink-0"></span>
                                    <span><?= e($bullet) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ==========================================
     SECTION 4: 5 BUSINESS BENEFITS (Exact Left Mockup Match)
=========================================== -->
<section class="py-14 lg:py-20 font-sans" style="background-color: #edf4fe;">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header (Exact Match to Left Mockup) -->
        <div class="text-center max-w-4xl mx-auto mb-10 lg:mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-[#022862] leading-tight">
                <span class="text-[#0663F6]">DIGITAL PLATFORM</span> <?= getCurrentLang() === 'th' ? 'ที่ช่วยยกระดับธุรกิจของคุณ' : ' That Elevates Your Business' ?>
            </h2>
        </div>

        <?php
        $benefits = [
            [
                'title' => getCurrentLang() === 'th' ? 'ข้อมูลรวมศูนย์' : 'Centralized Data',
                'desc' => getCurrentLang() === 'th' ? 'เข้าถึงข้อมูลได้ครบถ้วน<br>แม่นยำ และเป็นปัจจุบัน' : 'Access comprehensive, precise, and real-time business data.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0 drop-shadow-sm" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="14" y="20" width="34" height="12" rx="3" fill="#cbd5e1"/>
                        <circle cx="20" cy="26" r="1.5" fill="#10b981"/>
                        <circle cx="25" cy="26" r="1.5" fill="#f59e0b"/>
                        <rect x="32" y="24.5" width="12" height="3" rx="1.5" fill="#94a3b8"/>
                        
                        <rect x="14" y="36" width="34" height="12" rx="3" fill="#cbd5e1"/>
                        <circle cx="20" cy="42" r="1.5" fill="#10b981"/>
                        <circle cx="25" cy="42" r="1.5" fill="#f59e0b"/>
                        <rect x="32" y="40.5" width="12" height="3" rx="1.5" fill="#94a3b8"/>

                        <rect x="14" y="52" width="34" height="12" rx="3" fill="#cbd5e1"/>
                        <circle cx="20" cy="58" r="1.5" fill="#10b981"/>
                        <circle cx="25" cy="58" r="1.5" fill="#f59e0b"/>
                        <rect x="32" y="56.5" width="12" height="3" rx="1.5" fill="#94a3b8"/>

                        <ellipse cx="56" cy="38" rx="13" ry="5.5" fill="#0284c7"/>
                        <path d="M43 38v22c0 3 5.8 5.5 13 5.5s13-2.5 13-5.5V38" fill="#0369a1"/>
                        <ellipse cx="56" cy="48" rx="13" ry="5.5" fill="#0284c7" fill-opacity="0.2" stroke="#38bdf8" stroke-width="1.2"/>
                        <ellipse cx="56" cy="60" rx="13" ry="5.5" fill="#0284c7" stroke="#38bdf8" stroke-width="1.2"/>
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'เพิ่มประสิทธิภาพ<br>การทำงาน' : 'Boost <br>Productivity',
                'desc' => getCurrentLang() === 'th' ? 'ลดขั้นตอนซ้ำซ้อน ทำงานไว<br>และลดต้นทุน' : 'Streamline workflows, work faster, and reduce operating costs.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0 drop-shadow-sm" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M40 18l3 4 5-1 1 5 5 1 0 5 5 2-2 5 3 4-4 3 1 5-5 1-1 5-5 0-2 5-5-2-4 3-3-4-5 1-1-5-5-1 0-5-5-2 2-5-3-4 4-3-1-5 5-1 1-5 5 0 2-5 5 2z" fill="#475569"/>
                        <circle cx="40" cy="40" r="15" fill="#06b6d4"/>
                        <circle cx="40" cy="40" r="7" fill="#e0f2fe"/>
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'ประสบการณ์<br>ผู้ใช้ที่ดีขึ้น' : 'Enhanced User <br>Experience',
                'desc' => getCurrentLang() === 'th' ? 'ใช้งานง่าย รวดเร็ว<br>สร้างความพึงพอใจ' : 'Intuitive, swift, and delivering utmost customer satisfaction.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0 drop-shadow-sm" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="28" cy="36" r="6" fill="#fb923c"/>
                        <path d="M20 54c0-5 3.5-8 8-8s8 3 8 8" fill="#fb923c"/>
                        <circle cx="52" cy="36" r="6" fill="#fb923c"/>
                        <path d="M44 54c0-5 3.5-8 8-8s8 3 8 8" fill="#fb923c"/>
                        <circle cx="40" cy="32" r="7" fill="#f87171"/>
                        <path d="M30 54c0-6 4.5-10 10-10s10 4 10 10" fill="#f87171"/>
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'รองรับการเติบโต' : 'Scalable for Growth',
                'desc' => getCurrentLang() === 'th' ? 'โครงสร้างยืดหยุ่น<br>พร้อมรองรับอนาคต' : 'Flexible architecture fully prepared for future expansion.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0 drop-shadow-sm" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="16" y1="62" x2="64" y2="62" stroke="#86efac" stroke-width="2"/>
                        <rect x="20" y="48" width="8" height="14" rx="2" fill="#86efac"/>
                        <rect x="31" y="40" width="8" height="22" rx="2" fill="#4ade80"/>
                        <rect x="42" y="32" width="8" height="30" rx="2" fill="#22c55e"/>
                        <rect x="53" y="24" width="8" height="38" rx="2" fill="#16a34a"/>
                        <path d="M22 42Q42 32 58 18" stroke="#22c55e" stroke-width="4.5" stroke-linecap="round"/>
                        <path d="M50 16H60V26" stroke="#22c55e" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'เชื่อมต่อระบบ<br>ได้ยืดหยุ่น' : 'Seamless System <br>Integration',
                'desc' => getCurrentLang() === 'th' ? 'บูรณาการระบบเดิมได้<br>โดยไม่สะดุด' : 'Seamlessly integrate with legacy setups without disruption.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0 drop-shadow-sm" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="28" y1="24" x2="52" y2="28" stroke="#334155" stroke-width="2.5"/>
                        <line x1="52" y1="28" x2="62" y2="44" stroke="#334155" stroke-width="2.5"/>
                        <line x1="62" y1="44" x2="54" y2="58" stroke="#334155" stroke-width="2.5"/>
                        <line x1="54" y1="58" x2="42" y2="66" stroke="#334155" stroke-width="2.5"/>
                        <line x1="42" y1="66" x2="26" y2="52" stroke="#334155" stroke-width="2.5"/>
                        <line x1="26" y1="52" x2="28" y2="24" stroke="#334155" stroke-width="2.5"/>
                        <line x1="28" y1="24" x2="54" y2="58" stroke="#334155" stroke-width="2"/>
                        <line x1="26" y1="52" x2="52" y2="28" stroke="#334155" stroke-width="2"/>
                        <circle cx="28" cy="24" r="5" fill="#f43f5e"/>
                        <circle cx="52" cy="28" r="5" fill="#0284c7"/>
                        <circle cx="62" cy="44" r="5" fill="#84cc16"/>
                        <circle cx="54" cy="58" r="5" fill="#f59e0b"/>
                        <circle cx="42" cy="66" r="5" fill="#0663F6"/>
                        <circle cx="26" cy="52" r="5" fill="#a855f7"/>
                    </svg>
                ',
            ],
        ];
        ?>

        <!-- 5 Cards Row Grid (Exact Match to Left Mockup) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 dp-benefits-grid">
            <?php foreach ($benefits as $idx => $b): ?>
                <div class="bg-white rounded-2xl px-4 py-8 sm:px-5 sm:py-9 lg:px-5 lg:py-10 text-center border border-slate-100 shadow-[0_8px_30px_rgba(4,59,148,0.06)] flex flex-col items-center group hover:shadow-lg transition-all duration-300 <?= $idx === 4 ? 'dp-benefit-card-last' : '' ?>">
                    <?= $b['icon_svg'] ?>
                    <div class="dp-benefit-content flex flex-col items-center">
                        <h3 class="text-[#0663F6] font-bold text-base sm:text-lg mb-3 leading-snug min-h-[48px] flex items-center justify-center">
                            <?= $b['title'] ?>
                        </h3>
                        <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed mt-auto">
                            <?= $b['desc'] ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ==========================================
     SECTION 5: PORTFOLIO SHOWCASE
=========================================== -->
<section class="bg-white py-14 lg:py-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header (Exact Match to Mockup) -->
        <div class="mb-10 lg:mb-12">
            <div class="inline-flex flex-col items-start">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0663F6] tracking-tight">
                    <?= getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Selected Case Studies' ?>
                </h2>
                <div class="w-12 h-[3.5px] bg-[#0663F6] mt-1.5"></div>
            </div>
        </div>

        <?php
        $showcases = [
            [
                'title' => 'IPM Club',
                'category' => 'E-learning',
                'desc' => 'แพลตฟอร์มการเรียนรู้ออนไลน์และศูนย์รวมหลักสูตรพัฒนาบุคลากร',
                'image' => asset_url('images/service-home.png'),
            ],
            [
                'title' => 'Yamaha RAID',
                'category' => 'E-commerce',
                'desc' => 'ระบบจัดการแคตตาล็อกอะไหล่และสั่งซื้อออนไลน์แบบ B2B',
                'image' => asset_url('images/yamaha.png'),
            ],
            [
                'title' => 'Plaas1',
                'category' => 'Intranet',
                'desc' => 'พอร์ทัลศูนย์กลางข้อมูลและการสื่อสารภายในองค์กรระดับสูง',
                'image' => asset_url('images/ab.png'),
            ],
            [
                'title' => 'RE-Inn',
                'category' => 'CMS',
                'desc' => 'ระบบบริหารจัดการเนื้อหาและอสังหาริมทรัพย์ยุคใหม่',
                'image' => asset_url('images/story.png'),
            ],
        ];
        ?>

        <!-- 4 Mockup Showcase Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 dp-showcase-grid">
            <?php foreach ($showcases as $item): ?>
                <div class="gsap-dp-portfolio-card dp-port-card block bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:border-blue-200 group">
                    <!-- Monitor / Device Frame -->
                    <div class="p-3 bg-slate-50 border-b border-slate-100 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[11px] text-slate-400 font-mono ml-2 truncate">webpark.co.th/case/<?= strtolower(str_replace(' ', '-', $item['title'])) ?></span>
                    </div>
                    <div class="h-48 overflow-hidden bg-slate-100 relative">
                        <img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" onerror="this.src='<?= e(asset_url('images/erp.png')) ?>';">
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-primary border border-blue-100">
                                <?= e($item['category']) ?>
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-[#022862] group-hover:text-primary transition-colors mb-1.5">
                            <?= e($item['title']) ?>
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            <?= e($item['desc']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>


<!-- ==========================================
     SECTION 7: GSAP SCROLLTRIGGER ANIMATION
=========================================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
            document.querySelectorAll(".opacity-0").forEach(el => {
                el.classList.remove("opacity-0", "translate-y-10");
            });
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        // Helper function for scroll reveal
        function revealOnScroll(selector, delay = 0, yOffset = 30) {
            const els = gsap.utils.toArray(selector);
            if (!els.length) return;

            els.forEach((el, index) => {
                gsap.to(el, {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 88%",
                        toggleActions: "play none none none"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.7,
                    delay: delay + (index * 0.1),
                    ease: "power2.out",
                    clearProps: "transform"
                });
            });
        }

        // 1. Intro Card Animation (Clean Fade & Slide with ClearProps)
        gsap.from(".gsap-dp-card-container", {
            scrollTrigger: {
                trigger: ".gsap-dp-card-container",
                start: "top 95%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 15,
            duration: 0.7,
            ease: "power2.out",
            clearProps: "all"
        });



        // 4. Portfolio Cards Animation
        const portCards = gsap.utils.toArray(".gsap-dp-portfolio-card");
        if (portCards.length > 0) {
            gsap.to(portCards, {
                scrollTrigger: {
                    trigger: ".gsap-dp-portfolio-card",
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                y: 0,
                opacity: 1,
                duration: 0.65,
                stagger: 0.1,
                ease: "power2.out",
                clearProps: "transform"
            });
        }
    });
</script>
