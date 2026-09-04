<?php
declare(strict_types=1);

$heroBgImage = asset_url('images/creative-design-hero-bg.png');
$ctaImage    = asset_url('images/bg-cta.jpg');
?>

<style>
    @keyframes heroFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .animate-hero-float {
        animation: heroFloat 5s ease-in-out infinite;
    }
    .text-gradient-creative {
        background: linear-gradient(135deg, #0663F6 0%, #00d2ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        color: #0663F6;
    }
    .sharp-image-render {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        filter: contrast(105%) saturate(106%);
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    /* Standalone Core Layout Rules */
    .cd-intro-main-card {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        background-color: #ffffff !important;
    }
    @media (min-width: 1280px) {
        .cd-intro-main-card {
            flex-direction: row !important;
        }
        .cd-intro-card-left {
            width: 32% !important;
            flex-shrink: 0 !important;
        }
    }
    .cd-intro-right-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        width: 100% !important;
        flex: 1 !important;
    }
    @media (max-width: 1024px) {
        .cd-intro-right-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    @media (max-width: 640px) {
        .cd-intro-right-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }
    }

    /* ===================================================
       IPAD PORTRAIT ONLY: MATCH ONLINE MARKETING EXACTLY
       Specifically: (min-width: 760px) and (max-width: 1024px) and (orientation: portrait)
       =================================================== */
    @media (min-width: 760px) and (max-width: 1024px) and (orientation: portrait) {
        /* Section 1: Hero Section (2-line title, text on left, 3D graphic on right) */
        .cd-hero-container {
            padding-top: 4.5rem !important;
            padding-bottom: 5rem !important;
        }
        .cd-hero-left-col {
            max-width: 68% !important;
            margin-left: 0 !important;
        }
        .cd-breadcrumb-nav,
        .cd-breadcrumb-list {
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            white-space: nowrap !important;
            align-items: center !important;
        }
        .cd-breadcrumb-list li,
        .cd-breadcrumb-list a,
        .cd-breadcrumb-list span {
            white-space: nowrap !important;
            display: inline-block !important;
            word-break: keep-all !important;
        }
        .cd-hero-h1-wrapper {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem !important;
        }
        .cd-hero-h1 {
            font-size: 3.5rem !important;
            font-weight: 900 !important;
            line-height: 1.08 !important;
            display: block !important;
            white-space: normal !important;
        }
        .cd-hero-p {
            font-size: 0.95rem !important;
            line-height: 1.65 !important;
            font-weight: 500 !important;
            color: #475569 !important;
            max-width: 100% !important;
            margin-bottom: 1.75rem !important;
        }
        .cd-hero-btn-container {
            flex-direction: row !important;
            align-items: center !important;
            gap: 1rem !important;
            flex-wrap: nowrap !important;
        }
        .cd-hero-bg-img {
            object-position: 88% center !important;
        }

        /* Section 2: Intro Summary Card (Header top, 4 pillars horizontal) */
        .cd-intro-section {
            padding-top: 0px !important;
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .cd-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        .cd-intro-main-card {
            flex-direction: column !important;
        }
        .cd-intro-card-left {
            width: 100% !important;
            max-width: 100% !important;
            flex: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-right: none !important;
            padding: 2rem 2.25rem 1.5rem !important;
        }
        .cd-intro-right-grid {
            width: 100% !important;
            flex: none !important;
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
        .cd-intro-pillar-card {
            padding: 1.5rem 0.5rem !important;
            border-bottom: none !important;
            border-right: 1px solid #f1f5f9 !important;
            border-top: none !important;
            border-left: none !important;
        }
        .cd-intro-pillar-card:last-child {
            border-right: none !important;
        }
        .cd-intro-pillar-card h3 {
            font-size: 0.95rem !important;
            line-height: 1.35 !important;
            min-height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin-bottom: 0.35rem !important;
        }
        .cd-intro-pillar-card p {
            font-size: 0.775rem !important;
            line-height: 1.35 !important;
            text-align: center !important;
        }
        .cd-intro-pillar-card svg {
            width: 3.25rem !important;
            height: 3.25rem !important;
            margin-bottom: 0.5rem !important;
        }

        /* Section 3: 10 Solutions Grid (2 columns x 5 rows like landscape) */
        .cd-solutions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .cd-solution-card {
            padding: 1.25rem !important;
        }

        /* Section 4: Benefits Grid (บน 2, กลาง 2, ล่าง 1 ยาว) */
        .cd-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 1.25rem !important;
        }
        .cd-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .cd-benefits-grid h3 {
            font-size: 1.15rem !important;
            min-height: 2.75rem !important;
            line-height: 1.35 !important;
            margin-bottom: 0.5rem !important;
        }
        .cd-benefits-grid p {
            font-size: 0.925rem !important;
            line-height: 1.55 !important;
        }
        .cd-benefits-grid .cd-benefit-icon-wrap {
            margin-bottom: 0.75rem !important;
        }

        /* Card 5: ล่าง 1 ยาว (Spans 2 columns, wide layout) */
        .cd-benefit-card-last {
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
        .cd-benefit-card-last .cd-benefit-icon-wrap {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
        }
        .cd-benefit-card-last .cd-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .cd-benefit-card-last h3 {
            text-align: left !important;
            justify-content: flex-start !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
            font-size: 1.25rem !important;
        }
        .cd-benefit-card-last p {
            text-align: left !important;
            font-size: 0.95rem !important;
        }

        /* Section 5: Portfolio Showcase Grid (4 cards in 1 row like landscape) */
        .cd-showcase-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .cd-showcase-grid .om-port-card {
            border-radius: 1rem !important;
        }
        .cd-showcase-grid h3 {
            font-size: 0.9rem !important;
        }
        .cd-showcase-grid p {
            font-size: 0.75rem !important;
        }
        .cd-showcase-grid span {
            font-size: 0.7rem !important;
        }
    }

    /* iPad Mini Portrait (760px - 820px) Specific Fine-tuning */
    @media (min-width: 760px) and (max-width: 820px) and (orientation: portrait) {
        .cd-hero-h1 {
            font-size: 3rem !important;
        }
        .cd-hero-left-col {
            max-width: 68% !important;
        }
        .cd-benefits-grid h3 {
            font-size: 1.05rem !important;
        }
        .cd-benefits-grid p {
            font-size: 0.85rem !important;
        }
        .cd-benefit-card-last h3 br,
        .cd-benefit-card-last p br {
            display: none !important;
        }
        .cd-benefit-card-last h3 {
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.25rem !important;
        }
        .cd-benefit-card-last p {
            white-space: nowrap !important;
        }
        .cd-showcase-grid h3 {
            font-size: 0.825rem !important;
        }
    }

    /* iPad Pro Portrait (821px - 1100px) Specific Fine-tuning */
    @media (min-width: 821px) and (max-width: 1100px) and (orientation: portrait) {
        .cd-hero-h1 {
            font-size: 4rem !important;
        }
        .cd-hero-left-col {
            max-width: 65% !important;
        }
        /* Single line text for Benefits cards on iPad Pro Portrait */
        .cd-benefits-grid h3 br,
        .cd-benefits-grid p br {
            display: none !important;
        }
        .cd-benefits-grid h3 {
            font-size: 1.15rem !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.5rem !important;
        }
        .cd-benefits-grid p {
            font-size: 0.9rem !important;
            white-space: nowrap !important;
        }
        .cd-showcase-grid h3 {
            font-size: 0.95rem !important;
        }
    }

    /* Mobile & iPad Spacing and 2-2-1 Benefits Layout (Both Mobile and iPad up to 1440px) */
    @media (max-width: 1440px) {
        .cd-intro-section {
            padding-bottom: 2rem !important;
            margin-bottom: 0px !important;
        }
        .cd-intro-card-container {
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }
        #cd-solutions,
        .cd-solutions-section {
            padding-top: 4.5rem !important;
        }

        /* Section 4 Benefits: บน 2, กลาง 2, ล่าง 1 ยาว สำหรับ Mobile และ iPad ทุกรุ่น */
        .cd-benefits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 0.75rem !important;
        }
        .cd-benefits-grid > div {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            padding: 1.25rem 0.5rem !important;
            border-radius: 1rem !important;
        }
        .cd-benefits-grid > div.cd-benefit-card-last {
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
        .cd-benefits-grid > div.cd-benefit-card-last .cd-benefit-content {
            align-items: flex-start !important;
            text-align: left !important;
        }
        .cd-benefits-grid > div.cd-benefit-card-last svg {
            margin-bottom: 0 !important;
            flex-shrink: 0 !important;
        }
        /* ข้อความในการ์ดแบบบรรทัดต่อบรรทัด */
        .cd-benefits-grid h3 br {
            display: none !important;
        }
        .cd-benefits-grid h3 {
            font-size: clamp(0.72rem, 2.8vw, 1.25rem) !important;
            white-space: nowrap !important;
            min-height: auto !important;
            margin-bottom: 0.35rem !important;
        }
        .cd-benefits-grid p {
            font-size: clamp(0.65rem, 2vw, 0.95rem) !important;
            line-height: 1.35 !important;
        }
    }

    @media (max-width: 640px) {
        .cd-benefits-grid {
            gap: 0.5rem !important;
        }
        .cd-benefits-grid > div {
            padding: 1.25rem 0.35rem !important;
        }
        .cd-benefits-grid svg {
            width: 2.5rem !important;
            height: 2.5rem !important;
            margin-bottom: 0.4rem !important;
        }
        .cd-benefits-grid h3 {
            font-size: clamp(0.65rem, 2.6vw, 0.85rem) !important;
            white-space: nowrap !important;
            letter-spacing: -0.01em !important;
        }
        .cd-benefits-grid p {
            font-size: 0.62rem !important;
            white-space: normal !important;
            line-height: 1.3 !important;
        }
        .cd-benefits-grid > div.cd-benefit-card-last {
            gap: 0.75rem !important;
            padding: 1rem 0.75rem !important;
        }
        .cd-benefits-grid > div.cd-benefit-card-last p {
            white-space: normal !important;
        }
    }

    @media (min-width: 760px) and (max-width: 1440px) {
        .cd-benefits-grid {
            gap: 1.25rem !important;
        }
        .cd-benefits-grid > div {
            padding: 2rem 1.5rem !important;
        }
        .cd-benefits-grid > div.cd-benefit-card-last {
            gap: 2rem !important;
            padding: 2rem 3rem !important;
        }
        .cd-benefits-grid p {
            white-space: nowrap !important;
        }
        .cd-benefits-grid p br {
            display: none !important;
        }
    }
</style>

<!-- Top Reading Progress Bar -->
<div id="reading-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-[#0663F6] z-[9999] transition-all duration-150 ease-out" style="width: 0%;"></div>

<!-- ==========================================
     SECTION 1: HERO SECTION
=========================================== -->
<section class="relative overflow-hidden font-sans bg-[#f7faff] pt-8 pb-16 lg:pt-14 lg:pb-28">
    <!-- Desktop Background Banner (Right Aligned High-Res 3D Graphic) -->
    <div class="absolute inset-0 z-0 hidden lg:block overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Creative & Design Background" 
            class="w-full h-full object-cover object-[right_center]"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/50 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <!-- Mobile/Tablet Background Banner -->
    <div class="absolute inset-0 z-0 lg:hidden overflow-hidden pointer-events-none">
        <img src="<?= e($heroBgImage) ?>" alt="Creative & Design Background" 
            class="w-full h-full object-cover object-[75%_center] cd-hero-bg-img"
            style="filter: contrast(1.04) saturate(1.05);">
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/80 to-white/30"></div>
        <div class="absolute inset-x-0 bottom-0 h-[20%] bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8 relative z-10 cd-hero-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Column: Breadcrumb, Titles, Description & CTA -->
            <div class="lg:col-span-7 flex flex-col items-start cd-hero-left-col">
                
                <!-- Breadcrumb -->
                <nav aria-label="Breadcrumb" class="mb-5 sm:mb-6 cd-breadcrumb-nav">
                    <ol class="inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 cd-breadcrumb-list whitespace-nowrap flex-nowrap">
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-[#0663F6] transition-colors whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'หน้าแรก' : 'Home') ?>
                            </a>
                        </li>
                        <li class="text-slate-300 whitespace-nowrap">/</li>
                        <li class="whitespace-nowrap">
                            <a href="<?= e(route_url('/services')) ?>" class="hover:text-[#0663F6] transition-colors whitespace-nowrap">
                                <?= e(getCurrentLang() === 'th' ? 'บริการของเรา' : 'Services') ?>
                            </a>
                        </li>
                        <li class="text-slate-300 whitespace-nowrap">/</li>
                        <li aria-current="page" class="text-slate-400 font-semibold whitespace-nowrap">
                            <?= getCurrentLang() === 'th' ? 'ออกแบบสร้างสรรค์' : 'CREATIVE / DESIGN' ?>
                        </li>
                    </ol>
                </nav>

                <!-- H1 Heading (Exact Match to Mockup) -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.08] mb-5 cd-hero-h1-wrapper">
                    <?php if (getCurrentLang() === 'th'): ?>
                        <span class="block text-[#1e293b] cd-hero-h1">ออกแบบ</span>
                        <span class="block text-[#0663F6] cd-hero-h1">สร้างสรรค์</span>
                    <?php else: ?>
                        <span class="block text-[#1e293b] cd-hero-h1">CREATIVE /</span>
                        <span class="block text-[#0663F6] cd-hero-h1">DESIGN</span>
                    <?php endif; ?>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-600 text-sm sm:text-base lg:text-[16.5px] leading-relaxed max-w-xl mb-8 cd-hero-p">
                    <?php if (getCurrentLang() === 'th'): ?>
                        ออกแบบอัตลักษณ์แบรนด์ สื่อการตลาด และดิจิทัลมีเดียที่โดดเด่น สื่อสารตัวตนของแบรนด์ได้อย่างชัดเจน สร้างความประทับใจตั้งแต่แรกเห็น
                    <?php else: ?>
                        Design distinctive brand identities, marketing collateral, and digital media that articulate your brand character and inspire lasting impressions.
                    <?php endif; ?>
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4 cd-hero-btn-container">
                    <a href="#contact-section" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#0663F6] text-white text-sm font-semibold shadow-[0_8px_20px_rgba(6,99,246,0.35)] hover:bg-blue-700 hover:shadow-[0_12px_24px_rgba(6,99,246,0.45)] hover:-translate-y-0.5 transition-all duration-300">
                        <span><?= getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert' ?></span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#cd-solutions" class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white text-slate-700 border border-slate-200 text-sm font-semibold shadow-sm hover:border-[#0663F6] hover:text-[#0663F6] transition-all duration-300">
                        <span class="w-6 h-6 rounded-full bg-[#0663F6]/10 text-[#0663F6] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        <span><?= getCurrentLang() === 'th' ? 'ดูวิดีโอแนะนำ' : 'Watch Video' ?></span>
                    </a>
                </div>
            </div>

            <!-- Right Column Spacer to preserve layout -->
            <div class="lg:col-span-5 hidden lg:block"></div>

        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 2: INTRO SUMMARY CARD
=========================================== -->
<section class="bg-white pt-2 pb-6 lg:pt-4 lg:pb-10 font-sans cd-intro-section">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-20 -mt-12 lg:-mt-20 xl:-mt-24 cd-intro-card-container">
        <div class="w-full rounded-2xl lg:rounded-3xl bg-white flex flex-col xl:flex-row items-stretch shadow-[0_10px_40px_rgba(4,59,148,0.08)] border border-slate-200/80 cd-intro-main-card">
            
            <!-- Left Block: Creative / Design คืออะไร -->
            <div class="w-full xl:w-[32%] 2xl:w-[30%] shrink-0 flex flex-col justify-center p-8 sm:p-10 lg:p-12 xl:p-14 border-b xl:border-b-0 xl:border-r border-slate-200/80 bg-white cd-intro-card-left">
                <div class="inline-flex flex-col items-start mb-2">
                    <span class="text-[#0663F6] font-extrabold text-2xl lg:text-3xl tracking-tight uppercase">
                        <?= getCurrentLang() === 'th' ? 'ออกแบบสร้างสรรค์' : 'CREATIVE / DESIGN' ?>
                    </span>
                    <div class="w-14 h-[3.5px] bg-[#0663F6] mt-1.5 mb-4"></div>
                </div>
                <h2 class="text-[#043B94] text-xl lg:text-2xl font-bold leading-tight mb-4">
                    <?= getCurrentLang() === 'th' ? 'งานออกแบบสร้างสรรค์ คืออะไร' : 'What is Creative / Design' ?>
                </h2>
                <p class="text-slate-600 text-xs sm:text-sm lg:text-[14.5px] leading-relaxed max-w-md">
                    <?php if (getCurrentLang() === 'th'): ?>
                        การออกแบบและสร้างสรรค์อัตลักษณ์ของแบรนด์ รวมถึงสื่อดิจิทัลทุกรูปแบบ เพื่อให้ธุรกิจของคุณสื่อสารกับกลุ่มเป้าหมายได้อย่างทรงพลังและน่าจดจำ
                    <?php else: ?>
                        Designing and crafting distinct brand identities and digital media, empowering your business to communicate with target audiences powerfully and memorably.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Right Grid: 4 Pillars from Mockup -->
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 w-full divide-y sm:divide-y-0 sm:divide-x divide-slate-200/80 cd-intro-right-grid">
                
                <!-- Pillar 1: สร้างความน่าเชื่อถือ -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors cd-intro-pillar-card">
                    <div class="w-20 h-20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-14 h-14" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14" y="8" width="36" height="42" rx="4" fill="#eff6ff" stroke="#0663F6" stroke-width="2.5"/>
                            <path d="M26 28L30 32L38 24" stroke="#0663F6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="32" cy="18" r="4" fill="#0663F6"/>
                            <path d="M22 40H42M22 45H36" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                            <path d="M42 36L48 54L32 48L16 54L22 36" fill="#f59e0b" opacity="0.9"/>
                            <circle cx="32" cy="38" r="6" fill="#fbbf24"/>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-1.5 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'สร้างความน่าเชื่อถือ' : 'Build Brand Credibility' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'อัตลักษณ์แบรนด์ที่โดดเด่น<br>น่าเชื่อถือ และจดจำง่าย' : 'Distinctive, credible, and memorable brand identity.' ?>
                    </p>
                </div>

                <!-- Pillar 2: ออกแบบตรงกลุ่มเป้าหมาย -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors cd-intro-pillar-card">
                    <div class="w-20 h-20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-14 h-14" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="22" stroke="#f97316" stroke-width="2.5" fill="#fff7ed"/>
                            <circle cx="32" cy="32" r="14" stroke="#f97316" stroke-width="2.5" stroke-dasharray="3 3"/>
                            <circle cx="32" cy="32" r="6" fill="#ea580c"/>
                            <path d="M44 20L32 32M44 20L38 20M44 20L44 26" stroke="#0663F6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-1.5 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ออกแบบตรงกลุ่มเป้าหมาย' : 'Audience-Centric Design' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'ดีไซน์ที่ตอบโจทย์<br>เข้าถึงผู้บริโภคยุคใหม่' : 'Tailored designs connecting seamlessly with modern consumers.' ?>
                    </p>
                </div>

                <!-- Pillar 3: ยกระดับภาพลักษณ์ธุรกิจ -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors cd-intro-pillar-card">
                    <div class="w-20 h-20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-14 h-14" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="12" y="14" width="40" height="28" rx="4" fill="#eff6ff" stroke="#0284c7" stroke-width="2.5"/>
                            <path d="M26 46L24 52H40L38 46" stroke="#0284c7" stroke-width="2" stroke-linecap="round"/>
                            <path d="M18 32L26 26L34 30L44 20" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="44" cy="20" r="2.5" fill="#10b981"/>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-1.5 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ยกระดับภาพลักษณ์ธุรกิจ' : 'Elevate Corporate Image' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'เพิ่มมูลค่าแบรนด์<br>ผ่านงานดีไซน์ระดับสากล' : 'Increase brand equity through international-standard design.' ?>
                    </p>
                </div>

                <!-- Pillar 4: ใช้งานได้หลากหลายมิติ -->
                <div class="px-5 py-8 sm:px-6 sm:py-10 lg:px-6 lg:py-12 xl:py-14 flex flex-col items-center text-center justify-center group hover:bg-blue-50/20 transition-colors cd-intro-pillar-card">
                    <div class="w-20 h-20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-14 h-14" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14" y="14" width="16" height="16" rx="3" fill="#f59e0b"/>
                            <rect x="34" y="14" width="16" height="16" rx="3" fill="#0663F6"/>
                            <rect x="14" y="34" width="16" height="16" rx="3" fill="#10b981"/>
                            <rect x="34" y="34" width="16" height="16" rx="3" fill="#8b5cf6"/>
                        </svg>
                    </div>
                    <h3 class="text-[#043B94] font-bold text-base lg:text-lg mb-1.5 leading-snug">
                        <?= getCurrentLang() === 'th' ? 'ใช้งานได้หลากหลายมิติ' : 'Omni-Channel Versatility' ?>
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed">
                        <?= getCurrentLang() === 'th' ? 'ใช้งานได้จริงทั้ง<br>สื่อออฟไลน์และออนไลน์' : 'Practical application across both offline and digital touchpoints.' ?>
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- ==========================================
     SECTION 3: 10 CREATIVE / DESIGN SOLUTIONS
=========================================== -->
<section id="cd-solutions" class="bg-white pt-6 lg:pt-10 pb-12 lg:pb-20 font-sans cd-solutions-section">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-12">
            <div class="inline-flex flex-col items-start mb-2">
                <span class="text-[#0663F6] font-extrabold text-2xl sm:text-3xl tracking-tight uppercase">
                    <?= getCurrentLang() === 'th' ? 'โซลูชันงานออกแบบสร้างสรรค์' : 'CREATIVE / DESIGN SOLUTIONS' ?>
                </span>
                <div class="w-16 h-[3.5px] bg-[#0663F6] mt-1.5 mb-3"></div>
            </div>
            <h2 class="text-base sm:text-lg md:text-xl font-bold text-[#043B94] leading-snug">
                <?= getCurrentLang() === 'th' ? 'โซลูชันการออกแบบครบวงจร เพื่อการเติบโตของแบรนด์ในทุกมิติ' : 'Comprehensive design solutions driving multidimensional brand growth' ?>
            </h2>
        </div>

        <?php
        $solutions = [
            [
                'title' => 'BRAND IDENTITY',
                'image' => asset_url('images/cd_sol_1.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ออกแบบโลโก้และ CI แบรนด์' : 'Brand Logo & CI Design',
                    getCurrentLang() === 'th' ? 'กำหนดคู่มือการใช้งานแบรนด์' : 'Brand Guideline Manual',
                    getCurrentLang() === 'th' ? 'ออกแบบ Stationery ครบชุด' : 'Complete Stationery Set',
                ]
            ],
            [
                'title' => 'UI / UX DESIGN',
                'image' => asset_url('images/cd_sol_2.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ออกแบบ User Interface สวยงาม' : 'Stunning Visual UI Design',
                    getCurrentLang() === 'th' ? 'วางโครงสร้าง UX ใช้งานง่าย' : 'Intuitive UX Architecture',
                    getCurrentLang() === 'th' ? 'ทำ Interactive Prototype' : 'Interactive Prototyping',
                ]
            ],
            [
                'title' => 'WEBSITE DESIGN',
                'image' => asset_url('images/cd_sol_3.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ออกแบบเว็บไซต์ทันสมัย' : 'Modern Website Design',
                    getCurrentLang() === 'th' ? 'Responsive รองรับทุกอุปกรณ์' : 'Responsive Across Devices',
                    getCurrentLang() === 'th' ? 'ออกแบบเน้น Conversion' : 'Conversion-Driven Layouts',
                ]
            ],
            [
                'title' => 'GRAPHIC DESIGN',
                'image' => asset_url('images/cd_sol_4.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'สื่อโฆษณา Banner & Infographic' : 'Ad Banners & Infographics',
                    getCurrentLang() === 'th' ? 'กราฟิกสำหรับโปรโมชั่น' : 'Campaign & Promotion Visuals',
                    getCurrentLang() === 'th' ? 'สื่อสิ่งพิมพ์และดิจิทัลครบวงจร' : 'Full-Spectrum Print & Digital',
                ]
            ],
            [
                'title' => 'SOCIAL MEDIA CREATIVE',
                'image' => asset_url('images/cd_sol_5.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'Artwork โซเชียลทุกแพลตฟอร์ม' : 'Multi-Platform Social Artwork',
                    getCurrentLang() === 'th' ? 'วาง Mood & Tone ให้สอดคล้อง' : 'Consistent Brand Mood & Tone',
                    getCurrentLang() === 'th' ? 'Template สำหรับโพสต์ประจำวัน' : 'Daily Engagement Templates',
                ]
            ],
            [
                'title' => 'MOTION GRAPHIC / VIDEO',
                'image' => asset_url('images/cd_sol_6.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ผลิตวิดีโอ Motion Graphic' : 'Dynamic Motion Graphics',
                    getCurrentLang() === 'th' ? 'แอนิเมชันเปิดตัวสินค้า' : 'Product Launch Animations',
                    getCurrentLang() === 'th' ? 'ตัดต่อและ Visual Effects' : 'Video Editing & VFX',
                ]
            ],
            [
                'title' => 'PRESENT & SALES KIT',
                'image' => asset_url('images/cd_sol_7.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ออกแบบ Company Profile' : 'Executive Company Profiles',
                    getCurrentLang() === 'th' ? 'Sales Pitch Deck ดึงดูดนักลงทุน' : 'Investor Sales Pitch Decks',
                    getCurrentLang() === 'th' ? 'สื่อและเอกสารสนับสนุนการขาย' : 'Sales Enablement Materials',
                ]
            ],
            [
                'title' => 'PACKAGING DESIGN',
                'image' => asset_url('images/cd_sol_8.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'ออกแบบบรรจุภัณฑ์และฉลาก' : 'Packaging & Label Design',
                    getCurrentLang() === 'th' ? 'สร้างความโดดเด่นบนชั้นวาง' : 'High Shelf & Online Standout',
                    getCurrentLang() === 'th' ? 'คำนึงถึงการผลิตจริง' : 'Production-Ready Specifications',
                ]
            ],
            [
                'title' => 'CONTENT VISUAL SYSTEM',
                'image' => asset_url('images/cd_sol_9.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'วางระบบ Key Visual แคมเปญ' : 'Campaign Key Visuals',
                    getCurrentLang() === 'th' ? 'กำหนด Moodboard และโทนสี' : 'Moodboards & Color Palettes',
                    getCurrentLang() === 'th' ? 'ภาพลักษณ์แบรนด์ที่เป็นหนึ่งเดียว' : 'Unified Brand Aesthetics',
                ]
            ],
            [
                'title' => 'DESIGN SYSTEM',
                'image' => asset_url('images/cd_sol_10.png'),
                'bullets' => [
                    getCurrentLang() === 'th' ? 'สร้าง UI Component Library' : 'UI Component Library',
                    getCurrentLang() === 'th' ? 'กำหนด Typography และสี' : 'Typography & Color Tokens',
                    getCurrentLang() === 'th' ? 'ส่งมอบทีมพัฒนาได้อย่างราบรื่น' : 'Seamless Developer Handoff',
                ]
            ],
        ];
        ?>

        <!-- 10 Solutions in 5x2 Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5 lg:gap-6 cd-solutions-grid">
            <?php foreach ($solutions as $sol): ?>
                <div class="bg-white rounded-xl border border-slate-200/80 p-4 sm:p-5 flex flex-col justify-between shadow-sm hover:shadow-md hover:border-blue-300 transition-all duration-300 group cd-solution-card">
                    <div>
                        <!-- Title -->
                        <h3 class="text-[#043B94] font-bold text-[13px] sm:text-sm tracking-tight mb-3 group-hover:text-[#0663F6] transition-colors line-clamp-1">
                            <?= e($sol['title']) ?>
                        </h3>

                        <!-- Image Preview -->
                        <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center p-2 mb-3.5 group-hover:bg-blue-50/30 transition-colors">
                            <img src="<?= e($sol['image']) ?>" alt="<?= e($sol['title']) ?>" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300 sharp-image-render">
                        </div>

                        <!-- Bullets -->
                        <ul class="space-y-1.5 text-slate-500 text-[11px] sm:text-xs leading-snug">
                            <?php foreach ($sol['bullets'] as $b): ?>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-[#0663F6] font-bold leading-none mt-0.5">•</span>
                                    <span><?= e($b) ?></span>
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
     SECTION 4: 5 BENEFITS SECTION
=========================================== -->
<section class="bg-[#edf4fe] py-14 lg:py-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-10 lg:mb-12">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#043B94] tracking-tight">
                <span class="text-[#0663F6]"><?= getCurrentLang() === 'th' ? 'งานออกแบบสร้างสรรค์' : 'CREATIVE / DESIGN' ?></span> <?= getCurrentLang() === 'th' ? 'ที่ช่วยยกระดับธุรกิจของคุณ' : 'That Elevates Your Business' ?>
            </h2>
        </div>

        <?php
        $benefits = [
            [
                'title' => getCurrentLang() === 'th' ? 'ภาพลักษณ์เป็นมืออาชีพ' : 'Professional Brand Image',
                'desc'  => getCurrentLang() === 'th' ? 'สร้างความน่าเชื่อถือ และภาพลักษณ์ระดับมืออาชีพ' : 'Build strong credibility and an international-standard presence.',
                'icon'  => '
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>'
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'สื่อสารแบรนด์ได้ชัดเจน' : 'Clear Brand Narrative',
                'desc'  => getCurrentLang() === 'th' ? 'เล่าเรื่องราวของแบรนด์ ผ่านงานดีไซน์ที่ตรงจุด' : 'Tell compelling stories through focused, purposeful design.',
                'icon'  => '
                    <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center text-amber-500 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>'
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'สร้างความผูกพันกับผู้ใช้' : 'Deep User Engagement',
                'desc'  => getCurrentLang() === 'th' ? 'ดีไซน์ที่เข้าถึงใจ สร้างความประทับใจระยะยาว' : 'User-centric designs inspiring lasting emotional connection.',
                'icon'  => '
                    <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center text-rose-500 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>'
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'เพิ่มโอกาสทางธุรกิจ' : 'Accelerate Business Growth',
                'desc'  => getCurrentLang() === 'th' ? 'ดีไซน์ที่ช่วยกระตุ้น การตัดสินใจซื้อของลูกค้า' : 'Optimized visuals that drive conversions and purchasing decisions.',
                'icon'  => '
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>'
            ],
            [
                'title' => getCurrentLang() === 'th' ? 'ใช้งานได้อย่างไร้รอยต่อ' : 'Seamless Application',
                'desc'  => getCurrentLang() === 'th' ? 'นำไปประยุกต์ใช้ได้ทุกช่องทาง สอดคล้องในทุกมิติ' : 'Adaptable assets maintaining coherence across all channels.',
                'icon'  => '
                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>'
            ],
        ];
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 cd-benefits-grid">
            <?php foreach ($benefits as $idx => $b): ?>
                <div class="bg-white rounded-2xl p-6 sm:p-7 flex flex-col items-center text-center shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 <?= $idx === 4 ? 'cd-benefit-card-last' : '' ?>">
                    <div class="cd-benefit-icon-wrap">
                        <?= $b['icon'] ?>
                    </div>
                    <div class="cd-benefit-content flex flex-col items-center">
                        <h3 class="text-[#043B94] font-bold text-base mb-2 leading-snug">
                            <?= e($b['title']) ?>
                        </h3>
                        <p class="text-slate-500 text-xs sm:text-[13px] leading-relaxed">
                            <?= e($b['desc']) ?>
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
                'category' => 'Website Design',
                'logo' => asset_url('images/port_logo_kpn.png'),
                'image' => asset_url('images/port_monitor_1.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'เว็บไซต์ที่ออกแบบเพื่อสื่อสารข้อมูลโครงการและบริการของแบรนด์อย่างครบถ้วน รองรับทุกอุปกรณ์ และช่วยให้ผู้ใช้งานเข้าถึงข้อมูลได้ง่าย'
                    : 'Designed to comprehensively communicate project and brand service information, fully responsive on all devices and user-friendly.',
            ],
            [
                'title' => 'Yamaha LEAD',
                'category' => 'Design System',
                'logo' => asset_url('images/yamaha.png'),
                'image' => asset_url('images/port_monitor_2.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'ระบบ ERP สำหรับบริหารข้อมูลและกระบวนการทำงานภายในองค์กร ช่วยลดขั้นตอนซ้ำซ้อนและเพิ่มประสิทธิภาพการจัดการงานอย่างเป็นระบบ'
                    : 'Comprehensive enterprise ERP optimizing workflows, eliminating redundant tasks, and enhancing operational efficiency.',
            ],
            [
                'title' => 'Nusasiri',
                'category' => 'Brand Identity',
                'logo' => asset_url('images/port_logo_nusasiri.png'),
                'image' => asset_url('images/port_monitor_3.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'ระบบติดตามและวิเคราะห์ข้อมูลการตลาดแบบเรียลไทม์ ช่วยวัดความคุ้มค่าของการลงทุนและปรับแผนกลยุทธ์ได้อย่างแม่นยำ'
                    : 'Real-time marketing performance tracking and analytics to measure ROI and optimize strategic campaigns with precision.',
            ],
            [
                'title' => 'NS Gas',
                'category' => 'Online Graphic',
                'logo' => asset_url('images/port_logo_nsgas.png'),
                'image' => asset_url('images/port_monitor_4.png'),
                'desc' => getCurrentLang() === 'th'
                    ? 'การสร้างสรรค์คอนเทนต์และบริหารจัดการสื่อสังคมออนไลน์ เพื่อสร้างการรับรู้แบรนด์และกระตุ้นยอดขายอย่างมีประสิทธิภาพ'
                    : 'High-engagement social media content creation and management to elevate brand awareness and drive sales growth.',
            ],
        ];
        ?>

        <!-- 4 Mockup Showcase Cards Grid (Only the hovered card expands) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 items-start cd-showcase-grid">
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
        if (typeof gsap !== 'undefined') {
            // Reading progress
            window.addEventListener('scroll', function() {
                const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                const progressBar = document.getElementById('reading-progress');
                if (progressBar) {
                    progressBar.style.width = scrolled + '%';
                }
            });
        }
    });
</script>
