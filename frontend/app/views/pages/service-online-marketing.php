<?php
declare(strict_types=1);

$pageTitle = getCurrentLang() === 'th' ? 'การตลาดออนไลน์ (Online Marketing) | WEBPARK' : 'Online Marketing Solutions | WEBPARK';
$heroBgImage = asset_url('images/online-marketing-hero-bg.png');
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
    .om-gradient-title-dark {
        background: linear-gradient(135deg, #1e293b 0%, #475569 50%, #0f172a 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #1e293b;
    }
    .om-gradient-title-blue {
        background: linear-gradient(135deg, #004ecc 0%, #0663F6 40%, #0284c7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #0663F6;
    }

    /* Standalone Core Layout Rules (Guarantees layout even without compiled Tailwind) */
    .om-intro-main-card {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        background-color: #ffffff !important;
    }
    @media (min-width: 1280px) {
        .om-intro-main-card {
            flex-direction: row !important;
        }
        .om-intro-card-left {
            width: 32% !important;
            flex-shrink: 0 !important;
        }
    }
    .om-intro-right-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        width: 100% !important;
        flex: 1 !important;
    }
    @media (max-width: 1024px) {
        .om-intro-right-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    @media (max-width: 640px) {
        .om-intro-right-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }
    }

    /* Solution Card Glow & Hover (Completely Still - NO Bounce) */
    .om-solution-card {
        transform: none !important;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .om-solution-card:hover {
        transform: none !important;
        box-shadow: 0 10px 25px -5px rgba(6, 99, 246, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
        border-color: #bfdbfe;
    }

    /* ===================================================
       IPAD PORTRAIT ONLY: MATCH IPAD LANDSCAPE EXACTLY
       Specifically: (min-width: 760px) and (max-width: 1024px) and (orientation: portrait)
       =================================================== */
    @media (min-width: 760px) and (max-width: 1024px) and (orientation: portrait) {
        /* Section 1: Hero Section (2-line title, text on left, 3D graphic on right) */
        .om-hero-container {
            padding-top: 4.5rem !important;
            padding-bottom: 5rem !important;
        }
        .om-hero-left-col {
            max-width: 68% !important;
            margin-left: 0 !important;
        }
        .om-breadcrumb-nav,
        .om-breadcrumb-list {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            align-items: center !important;
        }
        .om-breadcrumb-list li,
        .om-breadcrumb-list a,
        .om-breadcrumb-list span {
            white-space: nowrap !important;
            display: inline-block !important;
            word-break: keep-all !important;
        }
        .om-hero-h1-wrapper {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem !important;
        }
        .om-hero-h1 {
            font-size: 3.5rem !important;
            font-weight: 900 !important;
            line-height: 1.08 !important;
            display: block !important;
            white-space: normal !important;
        }
        .om-hero-p {
            font-size: 0.95rem !important;
            line-height: 1.65 !important;
            font-weight: 500 !important;
            color: #475569 !important;
            max-width: 100% !important;
            margin-bottom: 1.75rem !important;
        }
        .om-hero-btn-container {
            flex-direction: row !important;
            align-items: center !important;
            gap: 1rem !important;
            flex-wrap: nowrap !important;
        }
        .om-hero-bg-img {
            object-position: 88% center !important;
        }

        /* Section 2: Intro Summary Card (Header top, 4 pillars horizontal) */
        .om-intro-section {
            padding-top: 0px !important;
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .om-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        .om-intro-main-card {
            flex-direction: column !important;
        }
        .om-intro-card-left {
            width: 100% !important;
            max-width: 100% !important;
            flex: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-right: none !important;
            padding: 2rem 2.25rem 1.5rem !important;
        }
        .om-intro-right-grid {
            width: 100% !important;
            flex: none !important;
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
        .om-intro-pillar-card {
            padding: 1.5rem 0.5rem !important;
            border-bottom: none !important;
            border-right: 1px solid #f1f5f9 !important;
            border-top: none !important;
            border-left: none !important;
        }
        .om-intro-pillar-card:last-child {
            border-right: none !important;
        }
        .om-intro-pillar-card h3 {
            font-size: 0.95rem !important;
            line-height: 1.35 !important;
            min-height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin-bottom: 0.35rem !important;
        }
        .om-intro-pillar-card p {
            font-size: 0.775rem !important;
            line-height: 1.35 !important;
            text-align: center !important;
        }
        .om-intro-pillar-card svg {
            width: 3.25rem !important;
            height: 3.25rem !important;
            margin-bottom: 0.5rem !important;
        }

        /* Section 3: 10 Solutions Grid (2 columns x 5 rows like landscape) */
        .om-solutions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .om-solution-card {
            padding: 1.25rem !important;
        }

        /* Section 4: Benefits Grid (บน 2, กลาง 2, ล่าง 1 ยาว) */
        .om-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .om-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .om-benefits-grid h3 {
            font-size: 1.15rem !important;
            min-height: 2.75rem !important;
            line-height: 1.35 !important;
            margin-bottom: 0.5rem !important;
        }
        .om-benefits-grid p {
            font-size: 0.925rem !important;
            line-height: 1.55 !important;
        }
        .om-benefits-grid svg {
            width: 3.75rem !important;
            height: 3.75rem !important;
            margin-bottom: 0.75rem !important;
        }

        /* Card 5: ล่าง 1 ยาว (Spans 2 columns, wide layout) */
        .om-benefit-card-last {
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
        .om-benefit-card-last svg {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
            width: 4.25rem !important;
            height: 4.25rem !important;
        }
        .om-benefit-card-last .om-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .om-benefit-card-last h3 {
            text-align: left !important;
            justify-content: flex-start !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
            font-size: 1.25rem !important;
        }
        .om-benefit-card-last p {
            text-align: left !important;
            font-size: 0.95rem !important;
        }

        /* Section 5: Portfolio Showcase Grid (4 cards in 1 row like landscape) */
        .om-showcase-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .om-showcase-grid .om-port-card {
            border-radius: 1rem !important;
        }
        .om-showcase-grid h3 {
            font-size: 0.9rem !important;
        }
        .om-showcase-grid p {
            font-size: 0.75rem !important;
        }
        .om-showcase-grid span {
            font-size: 0.7rem !important;
        }
    }

    /* iPad Mini Portrait (760px - 820px) Specific Fine-tuning */
    @media (min-width: 760px) and (max-width: 820px) and (orientation: portrait) {
        .om-hero-h1 {
            font-size: 3rem !important;
        }
        .om-hero-left-col {
            max-width: 68% !important;
        }
        .om-benefits-grid h3 {
            font-size: 1.05rem !important;
        }
        .om-benefits-grid p {
            font-size: 0.85rem !important;
        }
        .om-benefit-card-last h3 br,
        .om-benefit-card-last p br {
            display: none !important;
        }
        .om-benefit-card-last h3 {
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
        }
        .om-benefit-card-last p {
            white-space: nowrap !important;
        }
        .om-showcase-grid h3 {
            font-size: 0.825rem !important;
        }
    }

    /* iPad Pro Portrait (821px - 1100px) Specific Fine-tuning */
    @media (min-width: 821px) and (max-width: 1100px) and (orientation: portrait) {
        .om-hero-h1 {
            font-size: 4rem !important;
        }
        .om-hero-left-col {
            max-width: 55% !important;
        }
        /* Single line text for Benefits cards on iPad Pro Portrait */
        .om-benefits-grid h3 br,
        .om-benefits-grid p br {
            display: none !important;
        }
        .om-benefits-grid h3 {
            font-size: 1.15rem !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.5rem !important;
        }
        .om-benefits-grid p {
            font-size: 0.9rem !important;
            white-space: nowrap !important;
        }
        .om-showcase-grid h3 {
            font-size: 0.95rem !important;
        }
    }

    /* Mobile & iPad Spacing and 2-2-1 Benefits Layout (Both Mobile and iPad up to 1440px) */
    @media (max-width: 1440px) {
        .om-intro-section {
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .om-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        #om-solutions,
        .om-solutions-section {
            padding-top: 4.5rem !important;
        }

        /* Section 4 Benefits: บน 2, กลาง 2, ล่าง 1 ยาว สำหรับ Mobile และ iPad ทุกรุ่น */
        .om-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .om-benefits-grid > div {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            padding: 1.25rem 0.5rem !important;
            border-radius: 1rem !important;
        }
        .om-benefits-grid > div.om-benefit-card-last {
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
        .om-benefits-grid > div.om-benefit-card-last .om-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .om-benefits-grid > div.om-benefit-card-last svg {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
        }
        /* ข้อความในการ์ดแบบบรรทัดต่อบรรทัด */
        .om-benefits-grid h3 br {
            display: none !important;
        }
        .om-benefits-grid h3 {
            font-size: clamp(0.72rem, 2.8vw, 1.25rem) !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.35rem !important;
        }
        .om-benefits-grid p {
            font-size: clamp(0.65rem, 2vw, 0.95rem) !important;
            line-height: 1.35 !important;
        }
    }

    @media (max-width: 640px) {
        .om-benefits-grid {
            gap: 0.5rem !important;
        }
        .om-benefits-grid > div {
            padding: 1.25rem 0.35rem !important;
        }
        .om-benefits-grid svg {
            width: 2.5rem !important;
            height: 2.5rem !important;
            margin-bottom: 0.4rem !important;
        }
        .om-benefits-grid h3 {
            font-size: clamp(0.65rem, 2.6vw, 0.85rem) !important;
            white-space: nowrap !important;
            letter-spacing: -0.01em !important;
        }
        .om-benefits-grid p {
            font-size: 0.62rem !important;
            white-space: normal !important;
            line-height: 1.3 !important;
        }
        .om-benefits-grid > div.om-benefit-card-last {
            gap: 0.75rem !important;
            padding: 1rem 0.75rem !important;
        }
        .om-benefits-grid > div.om-benefit-card-last p {
            white-space: normal !important;
        }
    }

    @media (min-width: 760px) and (max-width: 1440px) {
        .om-benefits-grid {
            gap: 1.25rem !important;
        }
        .om-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .om-benefits-grid > div.om-benefit-card-last {
            gap: 2rem !important;
            padding: 2rem 3rem !important;
        }
        .om-benefits-grid p {
            white-space: nowrap !important;
        }
        .om-benefits-grid p br {
            display: none !important;
        }
    }
</style>

<!-- ==========================================
     SECTION 1: HERO SECTION
=========================================== -->
<section id="om-hero" class="relative font-sans bg-[#f7faff] overflow-hidden pt-8 pb-16 lg:pt-16 lg:pb-28">
    <!-- Desktop Background Banner -->
    <div class="absolute inset-0 z-0 hidden lg:block overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Online Marketing Background" 
            class="w-full h-full object-cover object-[right_center]"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/50 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <!-- Mobile Background Banner -->
    <div class="absolute inset-0 z-0 lg:hidden overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Online Marketing Background" 
            class="w-full h-full object-cover object-[75%_center] md:object-[right_center] om-hero-bg-img"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/80 to-white/30"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8 relative z-10 om-hero-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            <!-- Left Column: Title & Description -->
            <div class="lg:col-span-7 xl:col-span-7 om-hero-left-col">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb" class="animate-entrance-up delay-100 mb-5 om-breadcrumb-nav">
                    <ol class="inline-flex items-center flex-nowrap whitespace-nowrap text-sm md:text-base font-medium text-slate-500 om-breadcrumb-list">
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200 whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'หน้าแรก' : 'Home') ?>
                            </a>
                        </li>
                        <li class="whitespace-nowrap"><span class="text-slate-400 mx-2 sm:mx-2.5">/</span></li>
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/services')) ?>" class="hover:text-primary transition-colors duration-200 whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'บริการของเรา' : 'Services') ?>
                            </a>
                        </li>
                        <li class="whitespace-nowrap"><span class="text-slate-400 mx-2 sm:mx-2.5">/</span></li>
                        <li aria-current="page" class="whitespace-nowrap">
                            <span class="text-slate-400 font-semibold whitespace-nowrap"><?= getCurrentLang() === 'th' ? 'การตลาดออนไลน์' : 'Online Marketing' ?></span>
                        </li>
                    </ol>
                </nav>

                <!-- Hero Title (Dynamic TH/EN) -->
                <h1 class="animate-entrance-up delay-200 mb-4 tracking-tight flex flex-col items-start leading-[1.08] om-hero-h1-wrapper">
                    <?php if (getCurrentLang() === 'th'): ?>
                        <span class="om-gradient-title-dark font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase om-hero-h1">
                            การตลาด
                        </span>
                        <span class="om-gradient-title-blue font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase om-hero-h1">
                            ออนไลน์
                        </span>
                    <?php else: ?>
                        <span class="om-gradient-title-dark font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase om-hero-h1">
                            ONLINE
                        </span>
                        <span class="om-gradient-title-blue font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tighter uppercase om-hero-h1">
                            MARKETING
                        </span>
                    <?php endif; ?>
                </h1>

                <!-- Hero Subtitle & Description -->
                <p class="animate-entrance-up delay-300 text-slate-600 text-base md:text-lg lg:text-xl leading-relaxed max-w-2xl mb-8 font-medium om-hero-p" style="text-wrap: balance;">
                    <?php if (getCurrentLang() === 'th'): ?>
                        บริการวางแผนและทำการตลาดออนไลน์แบบครบวงจร ที่มุ่งเน้นการเติบโตของธุรกิจ เพิ่มยอดขาย ขยายฐานลูกค้า และสร้างการรับรู้แบรนด์อย่างมีประสิทธิภาพ วัดผลได้จริงในทุกขั้นตอน
                    <?php else: ?>
                        Comprehensive online marketing strategy and digital campaign management focused on business growth—driving sales, expanding customer bases, and building impactful brand awareness with measurable ROI.
                    <?php endif; ?>
                </p>

                <!-- Hero Action Buttons -->
                <div class="animate-entrance-up delay-400 flex flex-wrap items-center gap-4 om-hero-btn-container">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 bg-primary hover:bg-blue-700 text-white text-base font-semibold rounded-full shadow-lg shadow-blue-500/25 transition-all duration-300 hover:-translate-y-0.5 whitespace-nowrap">
                        <?= e(getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert') ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#om-solutions" class="inline-flex items-center gap-3.5 transition-all hover:-translate-y-0.5 group">
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
     SECTION 2: INTRO SUMMARY CARD
=========================================== -->
<section class="bg-white pt-2 pb-6 lg:pt-4 lg:pb-10 font-sans om-intro-section">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-20 -mt-12 lg:-mt-20 xl:-mt-24 om-intro-card-container">
        <div class="gsap-om-card-container w-full rounded-2xl lg:rounded-3xl bg-white flex flex-col xl:flex-row items-stretch shadow-[0_10px_40px_rgba(4,59,148,0.08)] border border-slate-200/80 om-intro-main-card">
            
            <!-- Left Block: Online Marketing คืออะไร -->
            <div class="w-full xl:w-[32%] 2xl:w-[30%] shrink-0 flex flex-col justify-center p-8 sm:p-10 lg:p-12 xl:p-14 border-b xl:border-b-0 xl:border-r border-slate-200/80 bg-white om-intro-card-left">
                <div class="inline-flex flex-col items-start mb-2">
                    <span class="text-[#0663F6] font-extrabold text-2xl lg:text-3xl tracking-tight uppercase">
                        ONLINE MARKETING
                    </span>
                    <div class="w-14 h-[3.5px] bg-[#0663F6] mt-1.5 mb-4"></div>
                </div>
                <h2 class="text-[#043B94] text-xl lg:text-2xl font-bold leading-tight mb-4">
                    <?= getCurrentLang() === 'th' ? 'Online Marketing คืออะไร' : 'What is Online Marketing' ?>
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm lg:text-[14.5px] leading-relaxed max-w-md">
                    <?php if (getCurrentLang() === 'th'): ?>
                        การทำตลาดบนช่องทางดิจิทัลที่ช่วยให้ธุรกิจเข้าถึงกลุ่มเป้าหมายได้อย่างแม่นยำ เพิ่มโอกาสสร้างยอดขาย และสร้างความได้เปรียบในการแข่งขันอย่างยั่งยืน
                    <?php else: ?>
                        Digital marketing strategies that empower businesses to reach precise audiences, accelerate revenue growth, and establish a sustainable competitive edge.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Right Grid: 4 Pillars from Mockup -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 w-full divide-y sm:divide-y-0 sm:divide-x divide-slate-200/80 om-intro-right-grid">
                
                <!-- Pillar 1 -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors om-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="24" fill="#fee2e2" />
                            <circle cx="32" cy="32" r="18" stroke="#ef4444" stroke-width="3" fill="white" />
                            <circle cx="32" cy="32" r="11" stroke="#ef4444" stroke-width="3" fill="#fee2e2" />
                            <circle cx="32" cy="32" r="5" fill="#ef4444" />
                            <path d="M46 18 L35 29" stroke="#1e293b" stroke-width="3.5" stroke-linecap="round" />
                            <path d="M44 14 L50 20 L44 26 L38 20 Z" fill="#3b82f6" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'เจาะกลุ่มเป้าหมายแม่นยำ' : 'Precise Targeting' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'เข้าถึงลูกค้าที่แท้จริง<br>ด้วย Data และ AI Targeting' : 'Reach genuine audiences using data & AI targeting.' ?>
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors om-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="28" cy="28" r="18" fill="#e0f2fe" />
                            <path d="M28 28 L28 14 A14 14 0 0 1 42 28 Z" fill="#0663F6" />
                            <path d="M28 28 L42 28 A14 14 0 0 1 28 42 Z" fill="#38bdf8" />
                            <path d="M28 28 L28 42 A14 14 0 0 1 14 28 Z" fill="#fbbf24" />
                            <circle cx="28" cy="28" r="18" stroke="#0284c7" stroke-width="3" fill="none" />
                            <path d="M41 41 L54 54" stroke="#64748b" stroke-width="5" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'วัดผลได้ชัดเจน' : 'Clear Analytics' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'ติดตามผลแบบ Real-time<br>วิเคราะห์ ROI ได้ทุกแคมเปญ' : 'Track real-time KPIs and analyze campaign ROI.' ?>
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors om-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14" y="38" width="8" height="16" rx="2" fill="#bbf7d0" />
                            <rect x="26" y="30" width="8" height="24" rx="2" fill="#86efac" />
                            <rect x="38" y="22" width="8" height="32" rx="2" fill="#4ade80" />
                            <path d="M12 40 L26 28 L38 24 L52 12" stroke="#16a34a" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M42 12 H52 V22" stroke="#16a34a" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'สร้างยอดขายต่อเนื่อง' : 'Sustainable Sales' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'เพิ่ม Conversion Rate<br>และสร้างการเติบโตอย่างยั่งยืน' : 'Boost conversion rates and drive revenue growth.' ?>
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors om-intro-pillar-card">
                    <div class="w-24 h-24 lg:w-28 lg:h-28 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-20 h-20 lg:w-24 lg:h-24 drop-shadow-sm" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="22" fill="#ffedd5" />
                            <circle cx="32" cy="32" r="16" stroke="#f97316" stroke-width="2.5" fill="white" />
                            <path d="M32 14 L32 50 M14 32 L50 32" stroke="#fdba74" stroke-width="2" stroke-linecap="round" />
                            <polygon points="32,18 35,29 46,32 35,35 32,46 29,35 18,32 29,29" fill="#ea580c" />
                            <circle cx="32" cy="32" r="3.5" fill="white" />
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-2 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'สื่อสารตรงจุด' : 'Targeted Messaging' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] lg:text-sm leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'สื่อสารข้อความที่ตรงใจ<br>ผ่านช่องทางที่เหมาะสม' : 'Deliver the right message via optimal channels.' ?>
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 3: 10 ONLINE MARKETING SOLUTIONS
=========================================== -->
<section id="om-solutions" class="bg-white pt-4 lg:pt-8 pb-6 lg:pb-10 font-sans om-solutions-section">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center mb-8 lg:mb-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0663F6] tracking-tight mb-3">
                ONLINE MARKETING SOLUTIONS
            </h2>
            <p class="text-slate-600 text-sm sm:text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed">
                <?= getCurrentLang() === 'th' ? 'โซลูชันการตลาดออนไลน์ครบวงจร ตอบสนองทุกเป้าหมายทางธุรกิจ' : 'Comprehensive digital marketing solutions engineered to fulfill all your business objectives.' ?>
            </p>
        </div>

        <!-- Solutions Grid (2 Columns x 5 Rows) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6 om-solutions-grid">
            
            <!-- 1. STRATEGY & GROWTH -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" y="14" width="36" height="44" rx="4" fill="#f8fafc" stroke="#38bdf8" stroke-width="2.5" />
                        <rect x="22" y="8" width="16" height="8" rx="2" fill="#0284c7" />
                        <circle cx="30" cy="12" r="1.5" fill="white" />
                        <circle cx="44" cy="44" r="14" fill="#fee2e2" stroke="#ef4444" stroke-width="2" />
                        <circle cx="44" cy="44" r="9" fill="white" stroke="#ef4444" stroke-width="2" />
                        <circle cx="44" cy="44" r="4" fill="#ef4444" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        STRATEGY & GROWTH
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'วางกลยุทธ์การตลาดแบบองค์รวม' : 'Holistic marketing strategy' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'วิเคราะห์คู่แข่งและโอกาสทางธุรกิจ' : 'Competitive & market opportunity analysis' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'กำหนด KPI และกรอบเวลาที่ชัดเจน' : 'Clear KPI roadmap and milestone timing' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 2. PERFORMANCE MARKETING -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 26 L36 16 V46 L16 36 Z" fill="#64748b" />
                        <path d="M36 16 L44 12 V50 L36 46 Z" fill="#ef4444" />
                        <rect x="8" y="24" width="8" height="14" rx="2" fill="#ef4444" />
                        <path d="M22 36 L18 52 H26 L30 38 Z" fill="#0284c7" />
                        <path d="M48 24 A10 10 0 0 1 48 38" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" />
                        <path d="M54 18 A18 18 0 0 1 54 44" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        PERFORMANCE MARKETING
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ยิงแอดโฆษณาเน้นยอดขายและผลลัพธ์' : 'Outcome-driven paid advertising campaigns' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ยิงโฆษณา Google, Facebook, TikTok' : 'Google Ads, Meta & TikTok Ads management' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'บริหารงบประมาณให้คุ้มค่าที่สุด' : 'Maximize cost-efficiency and return on spend' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 3. SEO & CONTENT MARKETING -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="12" width="48" height="38" rx="4" fill="#f0f9ff" stroke="#38bdf8" stroke-width="2" />
                        <rect x="8" y="12" width="48" height="9" rx="4" fill="#e0f2fe" />
                        <circle cx="14" cy="16.5" r="1.5" fill="#ef4444" />
                        <circle cx="19" cy="16.5" r="1.5" fill="#f59e0b" />
                        <circle cx="24" cy="16.5" r="1.5" fill="#10b981" />
                        <path d="M16 42 L26 34 L36 38 L48 24" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="28" cy="40" r="10" fill="#e0f2fe" stroke="#6366f1" stroke-width="3" />
                        <path d="M21 47 L12 56" stroke="#4f46e5" stroke-width="4" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        SEO & CONTENT MARKETING
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ปรับแต่ง SEO ดันเว็บไซต์ติดหน้าแรก' : 'On-page and technical SEO rank acceleration' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'สร้าง Content คุณภาพตรงใจกลุ่มเป้าหมาย' : 'High-impact value-driven content creation' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เพิ่ม Organic Traffic ระยะยาว' : 'Sustainable organic traffic acquisition' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 4. SOCIAL MEDIA MANAGEMENT -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="18" y="10" width="28" height="46" rx="6" fill="#f8fafc" stroke="#64748b" stroke-width="2.5" />
                        <line x1="26" y1="14" x2="38" y2="14" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" />
                        <circle cx="14" cy="22" r="7" fill="#f59e0b" />
                        <path d="M12 22 L14 24 L17 20" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        <circle cx="50" cy="22" r="7" fill="#0663F6" />
                        <path d="M48 24 L52 20" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        <circle cx="14" cy="42" r="7" fill="#38bdf8" />
                        <circle cx="50" cy="42" r="7" fill="#ef4444" />
                        <circle cx="32" cy="33" r="6" fill="#10b981" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        SOCIAL MEDIA MANAGEMENT
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ดูแลและบริหารจัดการ Social Media ครบวงจร' : 'Full-funnel social media operations' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'วางแผน Content รายเดือน' : 'Strategic monthly content calendars' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'สร้างการมีส่วนร่วมกับผู้ติดตาม' : 'Foster organic community engagement' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 5. ADS & CAMPAIGN MANAGEMENT -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="18" y="14" width="34" height="34" rx="4" fill="#ede9fe" stroke="#8b5cf6" stroke-width="2" />
                        <circle cx="35" cy="31" r="7" fill="#ef4444" />
                        <polygon points="14,14 16,19 21,21 16,23 14,28 12,23 7,21 12,19" fill="#f59e0b" />
                        <path d="M10 40 L22 34 V48 L10 44 Z" fill="#f97316" />
                        <rect x="6" y="38" width="4" height="8" rx="1" fill="#ea580c" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        ADS & CAMPAIGN MANAGEMENT
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'วางแผนและสร้างสรรค์แคมเปญโฆษณา' : 'Creative ad concept design and execution' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ปรับแต่งกลุ่มเป้าหมาย A/B Testing' : 'Granular audience targeting & A/B testing' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ติดตามและ Optimize โฆษณาต่อเนื่อง' : 'Continuous optimization for lowest CPA' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 6. DATA ANALYTICS & REPORTING -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="14" width="44" height="38" rx="3" fill="#f8fafc" stroke="#38bdf8" stroke-width="2" />
                        <line x1="10" y1="24" x2="54" y2="24" stroke="#e2e8f0" stroke-width="1.5" />
                        <rect x="16" y="32" width="5" height="15" rx="1" fill="#38bdf8" />
                        <rect x="24" y="28" width="5" height="19" rx="1" fill="#0284c7" />
                        <rect x="32" y="36" width="5" height="11" rx="1" fill="#f59e0b" />
                        <circle cx="44" cy="34" r="8" fill="#ef4444" />
                        <path d="M44 34 L44 26 A8 8 0 0 1 52 34 Z" fill="#fbbf24" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        DATA ANALYTICS & REPORTING
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'วิเคราะห์ข้อมูลการตลาดเชิงลึก' : 'Deep-dive marketing performance insights' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'สรุปผล Performance Report ละเอียด' : 'Granular executive dashboards & monthly reports' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ให้คำแนะนำในการพัฒนาต่อยอด' : 'Actionable strategic recommendations' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 7. MARKETING AUTOMATION -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="22" r="9" fill="#f1f5f9" stroke="#64748b" stroke-width="2.5" />
                        <circle cx="20" cy="22" r="4" fill="#94a3b8" />
                        <circle cx="44" cy="42" r="11" fill="#f1f5f9" stroke="#0284c7" stroke-width="2.5" />
                        <circle cx="44" cy="42" r="5" fill="#38bdf8" />
                        <path d="M26 30 L38 24 V40 L26 34 Z" fill="#ef4444" />
                        <path d="M42 22 A16 16 0 0 1 44 26" stroke="#10b981" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        MARKETING AUTOMATION
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ระบบการตลาดอัตโนมัติประหยัดเวลา' : 'Time-saving automated marketing workflows' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เชื่อมต่อ Customer Journey อัตโนมัติ' : 'Seamless customer journey lifecycle triggers' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ส่งข้อความและ Email แบบ Personalized' : 'Personalized omnichannel email & SMS triggers' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 8. LEAD GENERATION & CRM SUPPORT -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" y="16" width="40" height="28" rx="3" fill="#f0fdf4" stroke="#22c55e" stroke-width="2" />
                        <rect x="8" y="44" width="48" height="4" rx="2" fill="#64748b" />
                        <rect x="36" y="10" width="16" height="8" rx="2" fill="#eab308" />
                        <text x="39" y="16" font-size="6" font-weight="bold" fill="white">CRM</text>
                        <circle cx="26" cy="30" r="7" fill="#38bdf8" />
                        <path d="M26 30 L26 23 A7 7 0 0 1 33 30 Z" fill="#ef4444" />
                        <rect x="37" y="24" width="11" height="12" rx="1.5" fill="#dcfce7" stroke="#16a34a" stroke-width="1" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        LEAD GENERATION & CRM SUPPORT
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เก็บ Lead ลูกค้าคุณภาพสูง' : 'Capture high-intent qualified enterprise leads' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เชื่อมต่อกับระบบ CRM ไร้รอยต่อ' : 'Seamless integration with leading CRM platforms' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เพิ่มโอกาสปิดการขายได้เร็วขึ้น' : 'Shorten sales cycles & accelerate deal closing' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 9. BRAND CONTENT & CREATIVE -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="12" width="44" height="34" rx="4" fill="#f8fafc" stroke="#64748b" stroke-width="2" />
                        <rect x="14" y="16" width="16" height="12" rx="2" fill="#0284c7" />
                        <polygon points="20,20 25,22 20,24" fill="white" />
                        <circle cx="20" cy="38" r="8" fill="#f59e0b" />
                        <circle cx="38" cy="34" r="6" stroke="#ef4444" stroke-width="2" fill="white" />
                        <path d="M46 38 L54 46" stroke="#0663F6" stroke-width="3" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        BRAND CONTENT & CREATIVE
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ออกแบบ Artwork และกราฟิกมืออาชีพ' : 'Professional visual design and artwork production' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ผลิตวิดีโอและ Motion Graphic' : 'High-engagement video & motion graphic production' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เล่าเรื่องแบรนด์อย่างทรงพลัง' : 'Impactful brand storytelling that inspires action' ?></span></li>
                    </ul>
                </div>
            </div>

            <!-- 10. OMNI-CHANNEL COMMUNICATION -->
            <div class="om-solution-card group rounded-2xl bg-white p-6 sm:p-7 border border-slate-200/90 flex flex-row items-center gap-5 sm:gap-6">
                <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-16 h-16 sm:w-18 sm:h-18" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="18" fill="#e0f2fe" stroke="#0284c7" stroke-width="2" />
                        <ellipse cx="32" cy="32" rx="9" ry="18" stroke="#38bdf8" stroke-width="1.5" />
                        <line x1="14" y1="32" x2="50" y2="32" stroke="#38bdf8" stroke-width="1.5" />
                        <circle cx="14" cy="20" r="5" fill="#10b981" />
                        <circle cx="50" cy="20" r="5" fill="#f59e0b" />
                        <circle cx="32" cy="52" r="5" fill="#ef4444" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#043B94] font-extrabold text-lg sm:text-xl tracking-tight mb-2 uppercase">
                        OMNI-CHANNEL COMMUNICATION
                    </h3>
                    <ul class="space-y-1 text-xs sm:text-sm text-slate-500 leading-relaxed font-normal">
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'ผสานการสื่อสารทุกช่องทางเป็นหนึ่งเดียว' : 'Unify communications across every customer touchpoint' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'เชื่อมต่อ Online สู่ Offline (O2O)' : 'Seamless Online-to-Offline (O2O) integration' ?></span></li>
                        <li class="flex items-start gap-1.5"><span class="text-[#0663F6] font-bold text-base leading-none">•</span><span><?= getCurrentLang() === 'th' ? 'มอบประสบการณ์ที่ดีที่สุดแก่ลูกค้า' : 'Deliver cohesive and memorable customer experiences' ?></span></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 4: BENEFITS SECTION (Based on Digital Platform structure)
=========================================== -->
<section class="bg-[#edf4fe] py-16 lg:py-24 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header (Exact Match to Digital Platform & Mockup) -->
        <div class="text-center max-w-4xl mx-auto mb-10 lg:mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-[32px] font-extrabold text-[#022862] leading-tight">
                <span class="text-[#0663F6]">ONLINE MARKETING</span> <?= getCurrentLang() === 'th' ? 'ที่ช่วยยกระดับธุรกิจของคุณ' : ' That Elevates Your Business' ?>
            </h2>
        </div>

        <?php
        $omBenefits = [
            [
                'title' => getCurrentLang() === 'th' ? 'เพิ่มการมองเห็นแบรนด์' : 'Boost Brand Visibility',
                'desc' => getCurrentLang() === 'th' ? 'ทำให้แบรนด์ของคุณโดดเด่น<br>และเป็นที่รู้จักมากขึ้น' : 'Make your brand stand out<br>and widely recognized.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 20 V14 H20 M44 14 H50 V20 M14 44 V50 H20 M50 44 V50 H44" stroke="#ef4444" stroke-width="3.5" stroke-linecap="round" />
                        <path d="M18 32 C22 24 42 24 46 32 C42 40 22 40 18 32 Z" fill="#e0e7ff" stroke="#3b82f6" stroke-width="2.5" />
                        <circle cx="32" cy="32" r="5" fill="#0663F6" />
                        <circle cx="34" cy="30" r="1.5" fill="white" />
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'เข้าถึงกลุ่มเป้าหมายตรงจุด' : 'Target Precise Audiences',
                'desc' => getCurrentLang() === 'th' ? 'สื่อสารกับคนที่ใช่ ด้วยช่องทาง<br>และข้อความที่เหมาะสม' : 'Reach the right audience via<br>optimal channels & messaging.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="22" stroke="#ef4444" stroke-width="3.5" fill="none" />
                        <circle cx="32" cy="32" r="15" stroke="#ef4444" stroke-width="2.5" fill="#fff1f2" />
                        <line x1="32" y1="6" x2="32" y2="12" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                        <line x1="32" y1="52" x2="32" y2="58" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                        <line x1="6" y1="32" x2="12" y2="32" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                        <line x1="52" y1="32" x2="58" y2="32" stroke="#ef4444" stroke-width="3" stroke-linecap="round" />
                        <circle cx="32" cy="28" r="3" fill="#f59e0b" />
                        <path d="M27 38 C27 34 37 34 37 38" fill="#f59e0b" />
                        <circle cx="26" cy="30" r="2.5" fill="#38bdf8" />
                        <path d="M22 38 C22 35 30 35 30 38" fill="#38bdf8" />
                        <circle cx="38" cy="30" r="2.5" fill="#38bdf8" />
                        <path d="M34 38 C34 35 42 35 42 38" fill="#38bdf8" />
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'วัดผลได้แบบเรียลไทม์' : 'Real-time Measurement',
                'desc' => getCurrentLang() === 'th' ? 'ติดตามผลลัพธ์ได้ตลอดเวลา<br>ปรับปรุงได้อย่างรวดเร็ว' : 'Monitor live campaign metrics and iterate rapidly.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="22" stroke="#ef4444" stroke-width="3.5" fill="#fff1f2" />
                        <line x1="32" y1="13" x2="32" y2="17" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        <line x1="32" y1="47" x2="32" y2="51" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        <line x1="13" y1="32" x2="17" y2="32" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        <line x1="47" y1="32" x2="51" y2="32" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        <line x1="32" y1="32" x2="30" y2="18" stroke="#1e293b" stroke-width="2.5" stroke-linecap="round" />
                        <line x1="32" y1="32" x2="32" y2="20" stroke="#ef4444" stroke-width="2" stroke-linecap="round" />
                        <circle cx="32" cy="32" r="2.5" fill="#ef4444" />
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'เพิ่มยอดขาย และ Conversion' : 'Elevate Conversions & Sales',
                'desc' => getCurrentLang() === 'th' ? 'เปลี่ยนการเข้าชมให้เป็นลูกค้า<br>และยอดขายที่เติบโต' : 'Convert traffic into customers and sustainable revenue.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="18" y="38" width="6" height="14" rx="1.5" fill="#4ade80" />
                        <rect x="28" y="30" width="6" height="22" rx="1.5" fill="#22c55e" />
                        <rect x="38" y="22" width="6" height="30" rx="1.5" fill="#16a34a" />
                        <path d="M14 36 C24 32 32 24 46 16" stroke="#22c55e" stroke-width="4" stroke-linecap="round" fill="none" />
                        <path d="M38 14 H48 V24" stroke="#22c55e" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                    </svg>
                ',
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'พัฒนากลยุทธ์ต่อเนื่องจากข้อมูล' : 'Data-driven Strategic Evolution',
                'desc' => getCurrentLang() === 'th' ? 'ใช้ข้อมูลเชิงลึกในการวางแผน<br>และเพิ่มประสิทธิภาพต่อเนื่อง' : 'Leverage data insights for planning and continuous optimization.',
                'icon_svg' => '
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mb-5 shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="26" cy="18" rx="11" ry="4.5" fill="#1e293b" />
                        <path d="M15 18 V34 C15 36.5 20 38.5 26 38.5 C32 38.5 37 36.5 37 34 V18" fill="#1e293b" />
                        <ellipse cx="26" cy="18" rx="11" ry="4.5" fill="#334155" />
                        <line x1="19" y1="24" x2="33" y2="24" stroke="#475569" stroke-width="1.5" />
                        <circle cx="21" cy="24" r="1" fill="#eab308" />
                        <circle cx="24" cy="24" r="1" fill="#22c55e" />
                        <line x1="19" y1="30" x2="33" y2="30" stroke="#475569" stroke-width="1.5" />
                        <circle cx="21" cy="30" r="1" fill="#eab308" />
                        <circle cx="24" cy="30" r="1" fill="#22c55e" />
                        <path d="M38 24 C44 26 44 36 38 42" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" fill="none" />
                        <path d="M20 40 C14 36 14 28 20 22" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" fill="none" />
                        <rect x="36" y="44" width="4.5" height="10" rx="1" fill="#ef4444" />
                        <rect x="43" y="38" width="4.5" height="16" rx="1" fill="#f59e0b" />
                        <rect x="50" y="30" width="4.5" height="24" rx="1" fill="#22c55e" />
                        <path d="M54 22 L54 28 M54 22 L48 22" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                ',
            ],
        ];
        ?>

        <!-- 5 Cards Row Grid (Exact Digital Platform Pattern) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 om-benefits-grid">
            <?php foreach ($omBenefits as $idx => $b): ?>
                <div class="bg-white rounded-2xl px-4 py-8 sm:px-5 sm:py-9 lg:px-5 lg:py-10 text-center border border-slate-100 shadow-[0_8px_30px_rgba(4,59,148,0.06)] flex flex-col items-center group hover:shadow-lg transition-all duration-300 <?= $idx === 4 ? 'om-benefit-card-last' : '' ?>">
                    <?= $b['icon_svg'] ?>
                    <div class="om-benefit-content flex flex-col items-center">
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
     SECTION 5: PORTFOLIO SHOWCASE (Based on Digital Platform structure)
=========================================== -->
<section class="bg-white py-14 lg:py-20 font-sans border-t border-slate-100">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header -->
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
                'title' => 'KPN Click',
                'category' => 'SEO',
                'logo' => asset_url('images/port_logo_kpn.png'),
                'image' => asset_url('images/port_monitor_1.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'เว็บไซต์ที่ออกแบบเพื่อสื่อสารข้อมูลโครงการและบริการของแบรนด์อย่างครบถ้วน รองรับทุกอุปกรณ์ และช่วยให้ผู้ใช้งานเข้าถึงข้อมูลได้ง่าย'
                    : 'Designed to comprehensively communicate project and brand service information, fully responsive on all devices and user-friendly.',
            ],
            [
                'title' => 'Yamaha LEAD',
                'category' => 'Online Campaign',
                'logo' => asset_url('images/yamaha.png'),
                'image' => asset_url('images/port_monitor_2.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'ระบบ ERP สำหรับบริหารข้อมูลและกระบวนการทำงานภายในองค์กร ช่วยลดขั้นตอนซ้ำซ้อนและเพิ่มประสิทธิภาพการจัดการงานอย่างเป็นระบบ'
                    : 'Comprehensive enterprise ERP optimizing workflows, eliminating redundant tasks, and enhancing operational efficiency.',
            ],
            [
                'title' => 'Nusasiri',
                'category' => 'Monitoring & Analysis',
                'logo' => asset_url('images/port_logo_nusasiri.png'),
                'image' => asset_url('images/port_monitor_3.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'ระบบติดตามและวิเคราะห์ข้อมูลการตลาดแบบเรียลไทม์ ช่วยวัดความคุ้มค่าของการลงทุนและปรับแผนกลยุทธ์ได้อย่างแม่นยำ'
                    : 'Real-time marketing performance tracking and analytics to measure ROI and optimize strategic campaigns with precision.',
            ],
            [
                'title' => 'NS Gas',
                'category' => 'Social Media Content',
                'logo' => asset_url('images/port_logo_nsgas.png'),
                'image' => asset_url('images/port_monitor_4.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'การสร้างสรรค์คอนเทนต์และบริหารจัดการสื่อสังคมออนไลน์ เพื่อสร้างการรับรู้แบรนด์และกระตุ้นยอดขายอย่างมีประสิทธิภาพ'
                    : 'High-engagement social media content creation and management to elevate brand awareness and drive sales growth.',
            ],
        ];
        ?>

        <!-- 4 Mockup Showcase Cards Grid (Only the hovered card expands) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 items-start om-showcase-grid">
            <?php foreach ($showcases as $item): ?>
                <div class="om-port-card group block bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-[0_8px_30px_rgba(4,59,148,0.06)] hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between cursor-pointer" onclick="window.location.href='<?= e(route_url('/portfolio')) ?>'">
                    <!-- Screen Area -->
                    <div class="w-full aspect-[4/3.1] overflow-hidden bg-slate-900 relative">
                        <img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                    </div>

                    <!-- Content Area (Turns Vibrant Blue on Hover) -->
                    <div class="p-5 sm:p-6 flex flex-col justify-between flex-1 bg-white group-hover:bg-[#0663F6] transition-colors duration-300 min-h-[140px]">
                        <div>
                            <!-- Header: Logo & Title -->
                            <div class="flex items-center gap-3 mb-2.5">
                                <div class="w-10 h-10 rounded-full bg-slate-100/90 group-hover:bg-white flex items-center justify-center p-1.5 shadow-sm shrink-0 transition-colors duration-300">
                                    <img src="<?= e($item['logo']) ?>" alt="<?= e($item['title']) ?>" class="max-h-full max-w-full object-contain">
                                </div>
                                <h3 class="text-[#043B94] group-hover:text-white font-bold text-lg transition-colors duration-300">
                                    <?= e($item['title']) ?>
                                </h3>
                            </div>

                            <!-- Description (Reveals Smoothly on Hover) -->
                            <div class="max-h-0 opacity-0 group-hover:max-h-48 group-hover:opacity-100 overflow-hidden transition-all duration-300 ease-out">
                                <p class="text-white/95 text-xs sm:text-[13px] leading-relaxed font-normal pt-2 pb-1">
                                    <?= e($item['desc']) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Tag Button (White on hover) -->
                        <div class="pt-3">
                            <span class="inline-flex px-4 py-1 rounded-full text-xs font-semibold text-[#0663F6] border border-[#0663F6] group-hover:text-white group-hover:border-white transition-colors duration-300 bg-transparent">
                                <?= e($item['category']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ==========================================
     SECTION 6: GSAP SCROLLTRIGGER ANIMATION
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

        // Hero Parallax on 3D Element
        gsap.to(".animate-hero-float", {
            y: 40,
            ease: "none",
            scrollTrigger: {
                trigger: "#om-hero",
                start: "top top",
                end: "bottom top",
                scrub: 1.2
            }
        });

        // Overview Card Entrance
        gsap.from(".gsap-om-card-container", {
            scrollTrigger: {
                trigger: ".gsap-om-card-container",
                start: "top 88%",
                toggleActions: "play none none none"
            },
            y: 35,
            opacity: 0,
            duration: 0.8,
            ease: "power2.out"
        });
    });
</script>
