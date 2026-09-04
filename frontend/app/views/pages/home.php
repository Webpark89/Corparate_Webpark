<?php
declare(strict_types=1);
/**
 * Home page view — hero, portfolio slider, reviews, and knowledge articles.
 */
$services = $services ?? [];
$activeTab = $activeTab ?? 'news';
$displayArticles = $latestArticles ?? $articles ?? $blogs ?? [];
$displayPortfolios = $displayPortfolios ?? [];
$reviews = $reviews ?? [];
$heroImage = asset_url('images/Pkatty.webp');
$projectRoot = dirname(__DIR__, 3);
$resolveServiceImage = static function (string $imagePath): string {
    $imagePath = trim($imagePath);
    if ($imagePath === '') return '';
    return resolve_article_image_url($imagePath);
};
$resolveReviewImage = static function (string $imagePath) use ($resolveServiceImage): string {
    $imagePath = trim($imagePath);
    if ($imagePath === '') return asset_url('images/Pkatty.webp');
    if (str_starts_with($imagePath, '//')) return $imagePath;
    $resolvedImage = $resolveServiceImage($imagePath);
    return $resolvedImage !== '' ? $resolvedImage : asset_url('images/Pkatty.webp');
};
$partnerLogos = [];
if (!empty($partners) && is_array($partners)) {
    foreach ($partners as $p) {
        $partnerLogos[] = [
            'url' => partner_logo_url($p['image_url']),
            'alt' => $p['image_alt'] ?: $p['name']
        ];
    }
}
?>
<section class="relative font-sans bg-[#f7faff] overflow-hidden mt-0 mx-0 mb-4 sm:mt-0 sm:mx-6 sm:mb-6 rounded-t-none rounded-b-[2rem] lg:m-0 lg:rounded-none">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="bg" class="hero-parallax-img w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/5"></div>
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
        .mobile-hero-woman { width: 58%; bottom: 0px; right: 0px; opacity: 1; z-index: 5; }
        @media (min-width: 768px) { .mobile-hero-woman { width: auto; bottom: 0; right: 0; opacity: 1; } }
        .hero-parallax-img {
            transform: scale(1.12);
            will-change: transform;
        }

        @media (min-width: 1181px) {
            .desktop-home-hero-h1 {
                font-size: 5.5rem !important;
                line-height: 1.1 !important;
            }
            .desktop-home-hero-p {
                font-size: 1.25rem !important;
                line-height: 1.75 !important;
                max-width: 34rem !important;
            }
        }
    </style>
    <div class="mx-auto w-full max-w-[1720px] px-5 sm:px-6 lg:px-10 pt-8 pb-12 md:pt-12 md:pb-24 lg:pt-28 lg:pb-32 relative z-10 ipad-air-landscape-hero-container">
        <!-- Mobile Gradient Mask to prevent text overlapping presenter image -->
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/90 to-white/20 md:hidden z-0 pointer-events-none"></div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:pb-12 lg:gap-10 items-center relative z-10">
            <div class="max-w-3xl relative z-10 text-left mx-0 lg:ml-12 ipad-pro-ml-0 xl:ml-24 flex flex-col items-start w-full ipad-air-landscape-left-col">
                <div class="animate-entrance-up delay-100 inline-flex items-center justify-center gap-2 md:gap-2.5 px-4 py-2 md:px-6 md:py-2.5 rounded-full border border-blue-600 mb-6 shadow-sm bg-white/40">
                    <span class="text-blue-600 font-black text-base md:text-xl leading-none flex items-center shrink-0">•</span>
                    <span class="text-[12px] sm:text-[13px] md:text-base lg:text-lg font-bold text-blue-600 tracking-wide whitespace-nowrap leading-none">
                        Digital Solutions for Modern Business
                    </span>
                </div>
                <h1 class="animate-entrance-up delay-200 text-5xl md:text-7xl lg:text-8xl font-black leading-[1.1] mb-2 tracking-tighter text-left">
                    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block py-2 md:py-2.5 desktop-home-hero-h1">WEBPARK</span><br>
                    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block py-1 md:py-2 -mt-1 md:-mt-2 lg:-mt-2 desktop-home-hero-h1">COMPANY</span>
                </h1>
                <p class="animate-entrance-up delay-300 mt-6 text-blue-900 md:text-[#0b1b42] text-base md:text-lg leading-relaxed max-w-lg mx-0 mb-10 font-bold md:font-semibold w-11/12 sm:w-3/4 md:w-full text-left relative z-20 ipad-pro-hero-desc ipad-mini-hero-desc mobile-hero-desc desktop-home-hero-p">
                    <span class="md:hidden">
                        <?= getCurrentLang() === 'th' ? 'ผู้ให้บริการพัฒนา Digital Platform<br>และระบบ AI ที่ช่วยให้ธุรกิจไทย<br>ก้าวไปข้างหน้า ด้วยเทคโนโลยี<br>ที่ใช้งานได้จริง' : 'Digital Platform and AI system<br>development provider helping Thai businesses<br>move forward with practical technology.' ?>
                    </span>
                    <span class="hidden md:inline">
                        <?= getCurrentLang() === 'th' ? 'ผู้ให้บริการพัฒนา Digital Platform<br class="hidden ipad-air-br ipad-mini-br"> และระบบ AI <br class="ipad-air-hidden ipad-mini-hidden">ที่ช่วยให้ธุรกิจไทยก้าวไปข้างหน้า<br class="hidden ipad-pro-strict-inline ipad-air-br ipad-mini-br">ด้วยเทคโนโลยีที่ใช้งานได้จริง' : 'Digital Platform and AI system<br class="hidden ipad-pro-strict-inline"> development provider helping<br class="hidden ipad-pro-strict-inline"> Thai businesses move forward<br class="hidden ipad-pro-strict-inline"> with practical technology.' ?>
                    </span>
                </p>
                <div class="animate-entrance-up delay-400 flex flex-col items-start self-start md:self-auto md:flex-row md:justify-start gap-3 md:gap-4 ipad-air-hero-btn-container ipad-mini-hero-btn-container">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5 whitespace-nowrap">
                        <span class="md:hidden"><?= getCurrentLang() === 'th' ? 'ดูบริการของเรา' : 'Our Services' ?></span>
                        <span class="hidden md:inline"><?= getCurrentLang() === 'th' ? 'ปรึกษาผู้เชี่ยวชาญ' : 'Consult an Expert' ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#about" class="inline-flex items-center gap-3 md:gap-4 transition-all hover:-translate-y-0.5 group whitespace-nowrap">
                        <div class="h-12 w-12 md:h-14 md:w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-sm md:text-lg font-semibold group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video')) ?></span>
                    </a>
                </div>
            </div>
            <div class="hidden lg:block lg:col-start-2"></div>
        </div>
        <style>
            @media (min-width: 1181px) {
                .desktop-hero-woman { top: auto !important; bottom: 0 !important; }
                .desktop-hero-woman img { height: 590px !important; max-width: none !important; object-fit: contain !important; object-position: bottom right !important; }
            }
            @media (min-width: 1280px) {
                .desktop-hero-woman { top: auto !important; bottom: 0 !important; }
                .desktop-hero-woman img { height: 670px !important; }
            }
            @media (min-width: 1536px) {
                .desktop-hero-woman { top: auto !important; bottom: 0 !important; }
                .desktop-hero-woman img { height: 720px !important; }
            }
            /* iPad Air specific fix for hero text overlapping hand */
            @media (min-width: 820px) and (max-width: 820px) {
                .ipad-air-br {
                    display: initial !important; 
                }
                .ipad-air-hidden {
                    display: none !important;
                }
                .ipad-air-about-desc {
                    padding-right: 2.5rem !important;
                }
                
                /* Hero Buttons Stack for iPad Air */
                .ipad-air-hero-btn-container {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                }
                
                /* Stats Section Font Sizes for iPad Air */
                .ipad-air-stat-desc { font-size: 1.125rem !important; line-height: 1.5 !important; margin-top: 0.25rem !important; }
                
                /* Articles Section Font Sizes for iPad Air */
                .ipad-air-article-h2 { font-size: 2.25rem !important; line-height: 1.2 !important; }
                .ipad-air-article-h3 { font-size: 1.5rem !important; line-height: 1.3 !important; }
                .ipad-air-article-desc { font-size: 1.125rem !important; line-height: 1.6 !important; }
                .ipad-air-card-title { font-size: 1.35rem !important; line-height: 1.4 !important; }
                .ipad-air-card-desc { font-size: 1.125rem !important; line-height: 1.6 !important; }
                .ipad-air-card-link { font-size: 1rem !important; line-height: 1.5 !important; }
                .ipad-air-card-badge { font-size: 0.875rem !important; padding: 0.375rem 0.75rem !important; }

                /* About Us & Services Cards Font Sizes for iPad Air */
                .ipad-air-font-about-tag { font-size: 1rem !important; }
                .ipad-air-font-about-h2 { font-size: 1.625rem !important; line-height: 2rem !important; }
                .ipad-air-font-title { font-size: 1.35rem !important; line-height: 1.75rem !important; }
                .ipad-air-font-desc { font-size: 1.125rem !important; line-height: 1.65rem !important; }
                .ipad-air-font-link { font-size: 1rem !important; line-height: 1.5rem !important; }

                /* Our Approach Section for iPad Air */
                .ipad-air-about-process-header {
                    flex-direction: row !important;
                    align-items: baseline !important;
                    gap: 0.75rem !important;
                }
                .ipad-air-about-process-step { font-size: 1.75rem !important; }
                .ipad-air-about-process-title { font-size: 1.5rem !important; }
                .ipad-air-about-process-desc { font-size: 1.125rem !important; line-height: 1.6 !important; }
            }
            
            /* Mobile (< 768px) specific fixes */
            @media (max-width: 767px) {
                .mobile-hero-woman {
                    width: 62% !important;
                    max-width: 305px !important;
                    right: -32px !important;
                    bottom: 0px !important;
                }
                .mobile-hero-woman img {
                    object-fit: contain !important;
                    object-position: right bottom !important;
                    height: 425px !important;
                    max-height: 58vh !important;
                    width: auto !important;
                }
                .mobile-hero-desc {
                    max-width: 58% !important;
                    width: 58% !important;
                    font-size: 0.925rem !important;
                    letter-spacing: -0.3px !important;
                }
                .mobile-hidden { display: none !important; }
                .mobile-br { display: initial !important; }
            }
            
            /* iPad Mini Portrait (760px - 820px) specific fixes & compact font scale */
            @media (min-width: 760px) and (max-width: 820px) {
                .ipad-mini-br {
                    display: initial !important;
                }
                .ipad-mini-hidden {
                    display: none !important;
                }
                .ipad-mini-hero-btn-container {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.85rem !important;
                }
                .desktop-hero-h1 {
                    font-size: 2.75rem !important;
                    line-height: 1.15 !important;
                }
                .ipad-mini-hero-desc {
                    max-width: 60% !important;
                    width: 60% !important;
                    font-size: 0.95rem !important;
                    line-height: 1.55 !important;
                }
                .ipad-pro-hero-desc {
                    font-size: 0.95rem !important;
                    line-height: 1.55 !important;
                    width: 420px !important;
                    max-width: 420px !important;
                }
                
                /* About Us & Services Cards Font Sizes for iPad Mini */
                .ipad-pro-font-about-tag, .ipad-air-font-about-tag { font-size: 0.85rem !important; }
                .ipad-pro-font-about-h2, .ipad-air-font-about-h2 { font-size: 1.25rem !important; line-height: 1.6rem !important; }
                .ipad-pro-font-title, .ipad-air-font-title { font-size: 0.975rem !important; line-height: 1.3rem !important; min-height: 2.5rem !important; }
                .ipad-pro-font-desc, .ipad-air-font-desc { font-size: 0.8rem !important; line-height: 1.4rem !important; min-height: 4.75rem !important; }
                .ipad-pro-font-link, .ipad-air-font-link { font-size: 0.825rem !important; line-height: 1.35rem !important; }
                .ipad-pro-font-h2 { font-size: 2.1rem !important; }
                .ipad-pro-font-subtitle { font-size: 1.5rem !important; line-height: 1.85rem !important; }

                /* Articles Section Font Sizes for iPad Mini */
                .ipad-pro-article-h2, .ipad-air-article-h2 { font-size: 1.65rem !important; line-height: 1.2 !important; }
                .ipad-pro-article-h3, .ipad-air-article-h3 { font-size: 1.2rem !important; line-height: 1.3 !important; }
                .ipad-pro-article-desc, .ipad-air-article-desc { font-size: 0.875rem !important; line-height: 1.5 !important; }
                .ipad-pro-card-title, .ipad-air-card-title { font-size: 1.05rem !important; line-height: 1.35 !important; }
                .ipad-pro-card-desc, .ipad-air-card-desc { font-size: 0.85rem !important; line-height: 1.45 !important; }
                .ipad-pro-card-link, .ipad-air-card-link { font-size: 0.825rem !important; line-height: 1.35 !important; }
                .ipad-pro-card-badge, .ipad-air-card-badge { font-size: 0.7rem !important; padding: 0.2rem 0.5rem !important; }
            }

            /* iPad Air Landscape (1180px) specific fixes */
            @media (min-width: 1180px) and (max-width: 1180px) and (orientation: landscape) {
                .ipad-air-hero-btn-container {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 1.25rem !important;
                }
            }
        </style>
        <div class="animate-entrance-left delay-500 absolute top-auto bottom-0 lg:top-28 lg:bottom-auto right-0 md:right-4 lg:right-8 z-0 pointer-events-none max-w-full transform md:-translate-y-2 flex justify-end mobile-hero-woman desktop-hero-woman">
            <picture class="w-full md:w-auto flex justify-end">
                <img src="<?= e($heroImage) ?>" alt="WEBPARK Presenter" class="w-full md:w-auto object-contain object-right-bottom h-auto md:h-[400px] lg:h-[600px] opacity-100">
            </picture>
        </div>
    </div>
</section>
<style>
/* Stacked 2-Part Layout for iPad Pro, iPad Air & Mobile (Both Portrait and Landscape up to 1440px) */
@media (max-width: 1440px) {
    .ipad-pro-stack-override { flex-direction: column !important; background: transparent !important; border: none !important; box-shadow: none !important; gap: 1.5rem !important; overflow: visible !important; }
    .ipad-pro-hidden { display: none !important; }
    .ipad-pro-w-full-override { max-width: 100% !important; width: 100% !important; border-right: none !important; border-radius: 2rem !important; border: 1px solid #f3f4f6 !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; background: white !important; padding: 2rem 2.5rem !important; }
    .ipad-pro-flex-visible { display: flex !important; }
    .ipad-pro-display-grid { display: grid !important; grid-template-columns: repeat(2, minmax(0, 1fr)) !important; align-items: center !important; gap: 2.5rem !important; }
    .ipad-pro-display-block { display: block !important; }
    .ipad-pro-display-flex { display: flex !important; flex-direction: column !important; justify-content: space-between !important; height: 100% !important; }
    .ipad-pro-grid-2-override { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; border-radius: 2rem !important; border: 1px solid #f3f4f6 !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; background: white !important; }
    .ipad-pro-border-br { border-right: 1px solid #f3f4f6 !important; border-bottom: 1px solid #f3f4f6 !important; }
    .ipad-pro-border-b { border-bottom: 1px solid #f3f4f6 !important; border-right: none !important; }
    .ipad-pro-border-r { border-right: 1px solid #f3f4f6 !important; border-bottom: none !important; }
    .ipad-pro-border-none { border: none !important; }
    .ipad-pro-p-6 { padding: 2.25rem 2rem !important; }
    
    /* Overrides for Articles layout on tablets */
    .ipad-pro-articles-slider {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width: 100% !important;
    }
    .ipad-pro-articles-card {
        width: 100% !important;
        max-width: 100% !important;
        flex-shrink: 0 !important;
        scroll-snap-align: center !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
}

/* Dedicated Large Font Scale for iPad Pro Landscape (1024px - 1366px Landscape) */
@media (min-width: 1024px) and (max-width: 1366px) and (orientation: landscape) {
    .ipad-pro-strict-hidden { display: none !important; }
    .ipad-pro-strict-inline { display: inline !important; }
    
    .desktop-hero-h1 {
        font-size: 4.5rem !important;
        line-height: 1.15 !important;
    }
    .ipad-pro-hero-desc,
    .desktop-hero-desc {
        font-size: 1.35rem !important;
        line-height: 2.1rem !important;
        width: 540px !important;
        max-width: 540px !important;
        padding-right: 0 !important;
    }
    
    .desktop-hero-woman {
        top: auto !important;
        bottom: 0px !important;
        transform: none !important;
        right: -40px !important;
    }
    .desktop-hero-woman img {
        height: 540px !important;
        max-height: 100% !important;
        object-fit: contain !important;
        object-position: bottom right !important;
    }
    
    .ipad-air-landscape-hero-container {
        padding-top: 3.5rem !important;
        padding-bottom: 0rem !important;
    }
    .ipad-air-landscape-left-col {
        padding-bottom: 2.5rem !important;
        margin-left: 0 !important;
    }
    .ipad-air-hero-btn-container,
    .ipad-mini-hero-btn-container {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1.25rem !important;
    }
    
    /* iPad Pro Font Size Overrides */
    .ipad-pro-about-br { display: inline !important; }
    .ipad-pro-font-about-tag { font-size: 2.25rem !important; line-height: 2.5rem !important; font-weight: 900 !important; color: #0663F6 !important; }
    .ipad-pro-about-bar { width: 5.5rem !important; height: 5px !important; margin-top: 0.4rem !important; margin-bottom: 0.75rem !important; background-color: #0663F6 !important; }
    .ipad-pro-font-about-h2 { font-size: 1.6rem !important; line-height: 2rem !important; font-weight: 800 !important; min-height: 4rem !important; margin-bottom: 0.75rem !important; color: #043B94 !important; }
    .ipad-pro-font-title {
        font-size: 1.45rem !important;
        line-height: 1.85rem !important;
        font-weight: 700 !important;
        min-height: 4rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        white-space: normal !important;
        margin-bottom: 0.75rem !important;
    }
    .ipad-pro-about-card-desc {
        font-size: 1.15rem !important;
        line-height: 1.85rem !important;
        min-height: 9.5rem !important;
        width: 100% !important;
        max-width: 100% !important;
        color: #6b7280 !important;
    }
    .ipad-pro-font-desc {
        font-size: 1.1rem !important;
        line-height: 1.8rem !important;
        min-height: 9.5rem !important;
        color: #6b7280 !important;
    }
    .ipad-pro-font-link {
        font-size: 1.1rem !important;
        line-height: 1.6rem !important;
        font-weight: 600 !important;
    }
    .ipad-pro-font-h2 { font-size: 3.25rem !important; line-height: 1.1 !important; }
    .ipad-pro-font-subtitle { font-size: 2.35rem !important; line-height: 2.6rem !important; }
    
    /* iPad Pro Articles Font Size Overrides */
    .ipad-pro-article-h2 { font-size: 2.75rem !important; line-height: 1.2 !important; }
    .ipad-pro-article-h3 { font-size: 1.85rem !important; line-height: 1.35 !important; }
    .ipad-pro-article-desc { font-size: 1.3rem !important; line-height: 1.8rem !important; }
    .ipad-pro-card-title { font-size: 1.75rem !important; line-height: 1.45 !important; }
    .ipad-pro-card-desc { font-size: 1.3rem !important; line-height: 1.7rem !important; }
    .ipad-pro-card-link { font-size: 1.15rem !important; line-height: 1.55 !important; }
    .ipad-pro-card-badge { font-size: 1.05rem !important; padding: 0.4rem 0.85rem !important; }
}

/* Dedicated Large Font Scale for iPad Pro Portrait */
@media (min-width: 821px) and (max-width: 1366px) and (orientation: portrait) {
    .desktop-hero-h1 {
        font-size: 4.25rem !important;
        line-height: 1.15 !important;
    }
    .ipad-pro-hero-desc,
    .desktop-hero-desc {
        font-size: 1.35rem !important;
        line-height: 2.1rem !important;
        width: 100% !important;
        max-width: 580px !important;
    }
    .ipad-pro-font-about-tag, .ipad-air-font-about-tag { font-size: 1.15rem !important; }
    .ipad-pro-font-about-h2, .ipad-air-font-about-h2 { font-size: 1.85rem !important; line-height: 2.25rem !important; }
    .ipad-pro-font-title, .ipad-air-font-title { font-size: 1.45rem !important; line-height: 1.75rem !important; min-height: 3.5rem !important; }
    .ipad-pro-font-desc, .ipad-air-font-desc { font-size: 1.15rem !important; line-height: 1.75rem !important; min-height: 6rem !important; }
    .ipad-pro-font-link, .ipad-air-font-link { font-size: 1.05rem !important; line-height: 1.55rem !important; }
    .ipad-pro-font-h2 { font-size: 3rem !important; line-height: 1.15 !important; }
    .ipad-pro-font-subtitle { font-size: 2.25rem !important; line-height: 2.6rem !important; }
    .ipad-pro-article-h2, .ipad-air-article-h2 { font-size: 2.5rem !important; line-height: 1.2 !important; }
    .ipad-pro-article-h3, .ipad-air-article-h3 { font-size: 1.75rem !important; line-height: 1.35 !important; }
    .ipad-pro-article-desc, .ipad-air-article-desc { font-size: 1.25rem !important; line-height: 1.75rem !important; }
    .ipad-pro-card-title, .ipad-air-card-title { font-size: 1.5rem !important; line-height: 1.45 !important; }
    .ipad-pro-card-desc, .ipad-air-card-desc { font-size: 1.15rem !important; line-height: 1.65 !important; }
    .ipad-pro-card-link, .ipad-air-card-link { font-size: 1.05rem !important; line-height: 1.55 !important; }
    .ipad-pro-card-badge, .ipad-air-card-badge { font-size: 0.95rem !important; padding: 0.35rem 0.75rem !important; }
}
</style>
<section class="relative bg-white z-20 mt-0 md:mt-0 lg:-mt-1 pb-6 lg:pb-6 overflow-hidden">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 bg-white">
        <div class="w-full flex flex-col lg:flex-row items-stretch lg:bg-white lg:rounded-[1rem] lg:shadow-[0_4px_25px_rgba(0,0,0,0.06)] lg:border lg:border-gray-100 lg:overflow-hidden gap-4 lg:gap-0 ipad-pro-stack-override">
            <div class="hidden lg:flex flex-1 lg:max-w-[280px] xl:max-w-[320px] items-center justify-center p-6 lg:p-8 border-b lg:border-b-0 shrink-0 bg-white ipad-pro-hidden">
                <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" class="w-32 lg:w-48 xl:w-56 h-auto object-contain">
            </div>
            <div class="flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border lg:border-none border-gray-100 lg:border-r shrink-0 bg-white rounded-t-none rounded-b-[2rem] lg:rounded-none shadow-sm lg:shadow-none ipad-pro-w-full-override ipad-pro-p-6">
                <div class="grid grid-cols-2 lg:contents items-center gap-4 lg:gap-6 w-full ipad-pro-display-grid">
                    <div class="flex items-center justify-center lg:hidden border-r border-gray-200 pr-4 h-fit self-center ipad-pro-flex-visible">
                        <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK logo" class="w-full max-w-[120px] md:max-w-[150px] h-auto object-contain">
                    </div>
                    <div class="lg:contents text-left ipad-pro-display-block">
                        <div class="flex flex-col justify-between h-full lg:h-auto lg:contents ipad-pro-display-flex">
                            <div>
                                <div class="text-left w-full mb-3">
                                    <span class="text-primary font-bold text-lg md:text-sm tracking-wide inline-block mx-0 ipad-pro-font-about-tag ipad-air-font-about-tag"><?= e(t('common.about_us_heading')) ?></span>
                                    <div class="w-8 h-[3px] bg-primary mt-1 ipad-pro-about-bar"></div>
                                </div>
                                <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-3 ipad-pro-font-about-h2 ipad-air-font-about-h2">
                                    <?= e(t('common.we_are_partner')) ?><br class="hidden lg:inline"><?= e(t('common.in_technology')) ?>
                                </h2>
                                <p class="text-gray-500 text-[0.8rem] md:text-sm leading-relaxed mb-4 ipad-pro-font-desc ipad-air-font-desc ipad-pro-about-card-desc">
                                    <?= e(t('common.partner_description')) ?>
                                </p>
                            </div>
                            <a href="<?= e(route_url('/about')) ?>" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto ipad-pro-font-link ipad-air-font-link">
                                <?= e(t('common.cta_read_more')) ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-[4] grid grid-cols-2 lg:grid-cols-4 w-full bg-white rounded-[2rem] lg:rounded-none shadow-sm lg:shadow-none border border-gray-100 lg:border-none overflow-hidden ipad-pro-grid-2-override">
                <?php
                $serviceCards = [
                    ['icon' => 'icon-3.png', 'title' => getCurrentLang() === 'th' ? 'ระบบ ERP / ERM' : 'ERP / ERM',        'desc' => t('common.solution_org_control'), 'href' => route_url('/erp')],
                    ['icon' => 'icon-2.png', 'title' => getCurrentLang() === 'th' ? 'แพลตฟอร์มดิจิทัล' : 'Platform Digital', 'desc' => t('common.solution_digital_platform'),              'href' => route_url('/services/digital-platform')],
                    ['icon' => 'icon-4.png', 'title' => getCurrentLang() === 'th' ? 'การตลาดออนไลน์' : 'Online Marketing', 'desc' => t('common.solution_online_marketing'),   'href' => route_url('/services/online-marketing')],
                    ['icon' => 'icon-1.png', 'title' => getCurrentLang() === 'th' ? 'ออกแบบสร้างสรรค์' : 'Creative / Design','desc' => t('common.solution_brand_design'),    'href' => route_url('/services/creative-design')],
                ];
                foreach ($serviceCards as $i => $card):
                    $borderClass = '';
                    $ipadProBorderClass = '';
                    if ($i === 0) {
                        $borderClass = 'border-r border-b lg:border-b-0';
                        $ipadProBorderClass = 'ipad-pro-border-br';
                    } elseif ($i === 1) {
                        $borderClass = 'border-b lg:border-r lg:border-b-0';
                        $ipadProBorderClass = 'ipad-pro-border-b';
                    } elseif ($i === 2) {
                        $borderClass = 'border-r';
                        $ipadProBorderClass = 'ipad-pro-border-r';
                    } elseif ($i === 3) {
                        $ipadProBorderClass = 'ipad-pro-border-none';
                    }
                ?>
                    <div onclick="window.location.href='<?= e($card['href']) ?>'" class="gsap-home-service-card relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> <?= $ipadProBorderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl opacity-0 translate-y-10 ipad-pro-p-6">
                    <div>
                        <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                            <img src="<?= e(asset_url('images/' . $card['icon'])) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                        </div>
                        <h2 class="text-[#043B94] font-bold text-base md:text-lg xl:text-xl text-center mb-3 whitespace-normal tracking-tight transition-colors duration-300 group-hover:text-blue-600 ipad-pro-font-title ipad-air-font-title">
                            <?= e($card['title']) ?>
                        </h2>
                        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600 ipad-pro-font-desc ipad-air-font-desc">
                            <?= e($card['desc']) ?>
                        </p>
                    </div>
                    <a href="<?= e($card['href']) ?>" class="inline-flex items-center text-primary text-sm font-medium transition-colors duration-300 group-hover:text-blue-700 w-max mt-auto ipad-pro-font-link ipad-air-font-link">
                        <?= e(t('common.cta_read_more')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php if (!empty($displayPortfolios)): ?>
<section class="bg-white pt-8 pb-4 lg:pt-10 lg:pb-6 overflow-hidden">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10">
        <div class="mb-10 lg:ml-12 ipad-pro-ml-0 xl:ml-24 lg:mr-12 xl:mr-24">
            <div class="hidden md:block">
                <div class="pl-0.5">
                    <h2 class="text-3xl md:text-4xl font-extrabold leading-none text-blue-600 m-0 mb-2.5 ipad-pro-font-h2 inline-block">
                        <?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?>
                    </h2>
                    <div class="w-8 h-[3px] bg-primary mb-4"></div>
                </div>
                <span class="block text-2xl lg:text-3xl font-bold leading-tight text-[#0b1b42] mb-6 ipad-pro-font-subtitle">
                    <?= e(t('home.portfolio_subtitle') !== 'home.portfolio_subtitle' ? t('home.portfolio_subtitle') : (getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Our Portfolio')) ?>
                </span>
                <div class="flex flex-row items-end justify-between gap-4 mb-8">
                    <p class="text-sm md:text-base leading-relaxed text-slate-500 max-w-xl m-0 ipad-pro-font-desc">
                        <?= getCurrentLang() === 'th' ? 'รวมผลงานที่ช่วยต่อยอดแบรนด์<br>และพาธุรกิจสู่มิติใหม่ที่ช่วยให้ธุรกิจเติบโตได้อย่างยั่งยืน' : 'A collection of digital solutions we designed and developed<br>to help businesses grow sustainably.' ?>
                    </p>
                    <a href="<?= e(route_url('/services')) ?>" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md">
                        <?= e(t('home.view_all_services') !== 'home.view_all_services' ? t('home.view_all_services') : (getCurrentLang() === 'th' ? 'ดูบริการของเรา' : 'View Our Services')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="block md:hidden text-left">
                <div class="mb-3">
                    <span class="text-primary font-bold text-xl tracking-wide inline-block"><?= e(t('common.nav_services') !== 'common.nav_services' ? t('common.nav_services') : (getCurrentLang() === 'th' ? 'บริการของเรา' : 'Our Services')) ?></span>
                    <div class="w-8 h-[3px] bg-primary mt-1"></div>
                </div>
                <h2 class="text-dark font-bold text-2xl leading-tight mb-3"><?= e(t('home.portfolio_subtitle') !== 'home.portfolio_subtitle' ? t('home.portfolio_subtitle') : (getCurrentLang() === 'th' ? 'ตัวอย่างผลงานของเรา' : 'Our Portfolio')) ?></h2>
                <p class="text-sm leading-relaxed text-slate-500 mb-0">
                    <?= e(getCurrentLang() === 'th' ? 'รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน' : 'A collection of digital solutions we designed and developed to help businesses grow sustainably.') ?>
                </p>
            </div>
        </div>
        <?php
        $categoryColors = [
            'ERP / ERM'        => '#0066ff',
            'ERP'              => '#0066ff',
            'Digital Platform' => '#00b894',
            'Online Marketing' => '#e17055',
            'Creative / Design'=> '#6c5ce7',
            'Website Portfolio'=> '#0066ff',
        ];
        $totalPortfolios = count($displayPortfolios);
        $visibleCount    = 4;
        ?>
        <div class="relative lg:ml-12 ipad-pro-ml-0 xl:ml-24 lg:mr-12 xl:mr-24">
            <button id="portfolio-prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md hidden lg:flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <div id="portfolio-slider" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($displayPortfolios as $index => $project): ?>
                    <?php
                    $project      = (array)$project;
                    $isVisible    = $index < $visibleCount ? '' : 'hidden';
                    $projectId    = (int)($project['id'] ?? 0);
                    $projectTitle = (string)($project['title'] ?? 'Portfolio');
                    $projectDesc  = mb_strimwidth(strip_tags((string)($project['description'] ?? $project['summary'] ?? '')), 0, 80, '...');
                    $projectCat   = (string)($project['category'] ?? 'Portfolio');
                    if (getCurrentLang() !== 'th') {
                        if (strpos($projectTitle, 'ผลงานรับทำเว็บไซต์องค์กร') !== false) {
                            $projectTitle = str_replace('ผลงานรับทำเว็บไซต์องค์กร บริษัท', 'Corporate Website for Client', $projectTitle);
                            $projectTitle = str_replace('ผลงานรับทำเว็บไซต์องค์กร', 'Corporate Website', $projectTitle);
                            $projectDesc = 'Design and develop a corporate website to present a professional and reliable image...';
                        } elseif (strpos($projectTitle, 'ผลงานรับทำระบบ E-commerce') !== false) {
                            $projectTitle = str_replace('ผลงานรับทำระบบ E-commerce ร้าน', 'E-commerce System for Client', $projectTitle);
                            $projectTitle = str_replace('ผลงานรับทำระบบ E-commerce', 'E-commerce System', $projectTitle);
                            $projectDesc = 'Develop an online store system with integrated credit card payment...';
                        }
                    }
                    $catColor     = $categoryColors[$projectCat] ?? '#0066ff';
                    $projectImage = resolve_article_image_url($project['image_path'] ?? '', asset_url('images/erp.png'));
                    ?>
                    <article class="portfolio-card gsap-home-portfolio-card group rounded-[1.2rem] overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col <?= $isVisible ?> opacity-0 translate-y-10" data-index="<?= $index ?>">
                        <div class="flex flex-col h-full cursor-default">
                            <div class="h-[200px] sm:h-[180px] lg:h-[200px] w-full overflow-hidden bg-slate-100 shrink-0">
                                <img src="<?= e($projectImage) ?>" alt="<?= e($projectTitle) ?>" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            </div>
                            <div class="p-5 sm:p-4 lg:p-6 flex flex-col flex-1 bg-white lg:group-hover:bg-[#0663F6] transition-colors duration-500">
                                <div class="flex items-center gap-3 mb-6 lg:group-hover:mb-3 transition-all duration-500">
                                    <?php if (!empty($project['logo_path'])): ?>
                                        <div class="bg-white rounded-full flex items-center justify-center shrink-0 w-10 h-10 overflow-hidden shadow-sm">
                                            <img src="<?= e(asset_url($project['logo_path'])) ?>" class="h-6 w-6 object-contain" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="text-lg lg:text-xl font-bold text-[#0b1b42] lg:group-hover:!text-white leading-snug line-clamp-1 transition-colors duration-500"><?= e($projectTitle) ?></h3>
                                </div>
                                <div class="hidden lg:block max-h-0 overflow-hidden opacity-0 lg:group-hover:max-h-24 lg:group-hover:opacity-100 lg:group-hover:mb-6 transition-all duration-500">
                                    <p class="text-white text-sm leading-relaxed line-clamp-3 font-light"><?= e($projectDesc) ?></p>
                                </div>
                                <div class="mt-auto">
                                    <span class="inline-block text-sm font-semibold px-5 py-1.5 rounded-full border lg:group-hover:!border-white lg:group-hover:!text-white transition-all duration-500" style="color:<?= e($catColor) ?>;border-color:<?= e($catColor) ?>;">
                                        <?= e($projectCat) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <button id="portfolio-next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 z-10 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-md hidden lg:flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                    <?= $totalPortfolios <= $visibleCount ? 'disabled' : '' ?> aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <!-- Dots for Desktop -->
        <div id="portfolio-dots-container" class="hidden lg:flex justify-center items-center gap-2 mt-8"></div>
        <!-- Page Numbers for Mobile/Tablet -->
        <div id="portfolio-pagination-container" class="flex lg:hidden justify-center items-center gap-2 mt-8"></div>
    </div>
</section>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const prevBtn = document.getElementById('portfolio-prev');
    const nextBtn = document.getElementById('portfolio-next');
    const dotsContainer = document.getElementById('portfolio-dots-container');
    const paginationContainer = document.getElementById('portfolio-pagination-container');
    const cards   = document.querySelectorAll('.portfolio-card');
    let cur = 0;
    const getVisibleCount = () => window.innerWidth < 1024 ? 2 : 4;
    function update() {
        const visible = getVisibleCount();
        const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
        cards.forEach((c, i) => c.classList.toggle('hidden', !(i >= cur * visible && i < (cur + 1) * visible)));
        // Update pagination rendering based on viewport
        if (window.innerWidth < 1024) {
            renderMobilePagination();
        } else {
            renderDesktopDots();
        }
        if (prevBtn) prevBtn.disabled = cur === 0;
        if (nextBtn) nextBtn.disabled = cur >= max;
    }
    function renderDesktopDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const visible = getVisibleCount();
        const pageCount = Math.ceil(cards.length / visible);
        if (pageCount <= 1) return;
        for (let i = 0; i < pageCount; i++) {
            const dot = document.createElement('span');
            dot.className = `portfolio-dot h-2 rounded-full cursor-pointer transition-all ${i === cur ? 'bg-primary w-6' : 'bg-slate-300 w-2'}`;
            dot.dataset.index = i;
            dot.addEventListener('click', () => {
                cur = i;
                update();
            });
            dotsContainer.appendChild(dot);
        }
    }
    function renderMobilePagination() {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';
        const visible = getVisibleCount();
        const pageCount = Math.ceil(cards.length / visible);
        if (pageCount <= 1) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'flex items-center overflow-hidden bg-white shadow-sm mx-auto';
        wrapper.style.borderRadius = '8px';
        wrapper.style.border = '1px solid #cbd5e1';
        const prevBtnMobile = document.createElement('button');
        prevBtnMobile.type = 'button';
        prevBtnMobile.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 18px; width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>`;
        prevBtnMobile.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        prevBtnMobile.style.height = '44px';
        prevBtnMobile.style.width = '48px';
        prevBtnMobile.style.color = '#1e40af';
        prevBtnMobile.style.borderRight = '1px solid #cbd5e1';
        prevBtnMobile.disabled = cur === 0;
        if (cur === 0) prevBtnMobile.style.opacity = '0.3';
        prevBtnMobile.addEventListener('click', () => {
            if (cur > 0) {
                cur--;
                update();
            }
        });
        wrapper.appendChild(prevBtnMobile);
        const infoText = document.createElement('span');
        infoText.className = 'flex items-center justify-center font-medium tracking-wide';
        infoText.style.height = '44px';
        infoText.style.padding = '0 20px';
        infoText.style.minWidth = '100px';
        infoText.style.color = '#1e40af';
        infoText.style.fontSize = '16px';
        infoText.textContent = `${cur + 1} of ${pageCount}`;
        wrapper.appendChild(infoText);
        const nextBtnMobile = document.createElement('button');
        nextBtnMobile.type = 'button';
        nextBtnMobile.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="height: 18px; width: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>`;
        nextBtnMobile.className = 'flex items-center justify-center transition-colors hover:bg-slate-50';
        nextBtnMobile.style.height = '44px';
        nextBtnMobile.style.width = '48px';
        nextBtnMobile.style.color = '#1e40af';
        nextBtnMobile.style.borderLeft = '1px solid #cbd5e1';
        nextBtnMobile.disabled = cur >= pageCount - 1;
        if (cur >= pageCount - 1) nextBtnMobile.style.opacity = '0.3';
        nextBtnMobile.addEventListener('click', () => {
            if (cur < pageCount - 1) {
                cur++;
                update();
            }
        });
        wrapper.appendChild(nextBtnMobile);
        paginationContainer.appendChild(wrapper);
    }
    if (prevBtn) prevBtn.addEventListener('click', () => {
        if (cur > 0) {
            cur--;
            update();
        }
    });
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const visible = getVisibleCount();
            const max = Math.max(0, Math.ceil(cards.length / visible) - 1);
            if (cur < max) {
                cur++;
                update();
            }
        });
    }
    // Listen to resize to recalculate pages dynamically
    let prevVisible = getVisibleCount();
    window.addEventListener('resize', () => {
        const currentVisible = getVisibleCount();
        if (currentVisible !== prevVisible) {
            prevVisible = currentVisible;
            const max = Math.max(0, Math.ceil(cards.length / currentVisible) - 1);
            if (cur > max) {
                cur = max;
            }
            update();
        }
    });
    update();
});
</script>
<?php
$totalReviews = count($reviews);
?>
<section class="relative pt-6 pb-6 lg:pt-8 lg:pb-10 overflow-hidden">
    <div class="absolute inset-0 -z-10 pointer-events-none">
        <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" alt="bg" class="w-full h-full object-cover object-center opacity-20 mix-blend-screen">
        <div class="absolute inset-0 bg-white/50"></div>
    </div>
    <div class="relative mx-auto w-full max-w-7xl px-5 sm:px-6 lg:px-8">
        <?php if ($totalReviews > 0): ?>
        <div class="mb-8 lg:mb-12 text-center max-w-4xl mx-auto">
            <h2 class="hidden lg:block text-primary font-bold text-4xl md:text-3xl tracking-normal uppercase mb-3">
                REVIEW
            </h2>
            <span class="hidden lg:block text-xl md:text-2xl lg:text-[28px] font-bold leading-tight text-dark">
                <?= getCurrentLang() === 'th' ? 'กว่า <span class="text-primary">120</span> องค์กรชั้นนำ ที่เลือก <span class="text-primary">WEBPARK</span> เป็นพาร์ทเนอร์ด้านดิจิทัล' : 'Over <span class="text-primary">120</span> leading organizations trust <span class="text-primary">WEBPARK</span> as their digital partner' ?>
            </span>
        </div>
        <?php endif; ?>
        <div class="lg:hidden mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_2.svg" alt="120+ องค์กรชั้นนำ" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight">120+ <span class="text-xl"><?= e(getCurrentLang() === 'th' ? 'องค์กรชั้นนำ' : 'Top Orgs') ?></span></h3>
                        <p class="text-slate-600 text-sm font-medium ipad-air-stat-desc"><?= e(getCurrentLang() === 'th' ? 'ที่ไว้วางใจ Webpark' : 'Trust Webpark') ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_1.svg" alt="15+ ปี" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight">15+ <span class="text-xl"><?= e(getCurrentLang() === 'th' ? 'ปี' : 'Years') ?></span></h3>
                        <p class="text-slate-600 text-sm font-medium ipad-air-stat-desc"><?= e(getCurrentLang() === 'th' ? 'แห่งประสบการณ์ ด้านเทคโนโลยี' : 'Of Technology Experience') ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_3.svg" alt="50+" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight decoration-blue-500">50+</h3>
                        <p class="text-slate-600 text-sm font-medium mt-1 ipad-air-stat-desc"><?= e(getCurrentLang() === 'th' ? 'ระบบและโปรเจกต์ ที่ส่งมอบ' : 'Systems & Projects Delivered') ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 flex flex-row items-center justify-start gap-5 border border-slate-100">
                    <img src="/Corparate_Webpark/frontend/public/assets/images/Capa_4.svg" alt="ครบวงจร" class="w-20 h-20 object-contain flex-shrink-0" />
                    <div class="flex flex-col text-left">
                        <h3 class="text-2xl font-black text-blue-600 mb-1 tracking-tight"><?= e(getCurrentLang() === 'th' ? 'ครบวงจร' : 'End-to-End') ?></h3>
                        <p class="text-slate-600 text-sm font-medium ipad-air-stat-desc"><?= e(getCurrentLang() === 'th' ? 'ตั้งแต่วางแผนพัฒนา ถึงดูแลหลังบ้าน' : 'From Planning to Maintenance') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($totalReviews > 0): ?>
        <div class="lg:hidden text-center mb-6">
            <h2 class="text-primary font-bold text-xl tracking-normal uppercase mb-2">
                REVIEW
            </h2>
            <span class="block text-[17px] sm:text-lg font-bold leading-tight text-dark px-4">
                <?= getCurrentLang() === 'th' ? 'กว่า <span class="text-primary">120</span> องค์กรชั้นนำ ที่เลือก <span class="text-primary">WEBPARK</span> เป็นพาร์ทเนอร์ด้านดิจิทัล' : 'Over <span class="text-primary">120</span> leading organizations trust <span class="text-primary">WEBPARK</span> as their digital partner' ?>
            </span>
        </div>
        <style>
            .pause-on-hover:hover {
                animation-play-state: paused !important;
            }
        </style>
        <div class="overflow-hidden relative mt-8 w-full" style="-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent); mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);">
            <div class="flex w-max gap-6 items-center animate-scroll py-4 pause-on-hover">
                <?php 
                // Duplicate reviews to create a seamless infinite scroll effect
                $scrollingReviews = array_merge($reviews, $reviews, $reviews);
                ?>
                <?php foreach ($scrollingReviews as $index => $review): ?>
                    <?php
                    $reviewerName  = (string)($review['reviewer_name'] ?? '');
                    $reviewerMeta  = implode(', ', array_values(array_filter([(string)($review['reviewer_position'] ?? ''), (string)($review['reviewer_company'] ?? '')], static fn($v) => trim($v) !== '')));
                    $reviewerImage = $resolveReviewImage((string)($review['reviewer_image_url'] ?? ''));
                    $rating        = max(0, min(5, isset($review['rating']) ? (int)$review['rating'] : 5));
                    ?>
                    <article class="review-card group flex-none bg-white rounded-[1.5rem] p-5 lg:p-6 shadow-sm border border-[#f3f4f6] flex flex-col justify-between hover:bg-primary hover:-translate-y-1 transition-all duration-300" style="width: 286px; height: 280px;">
                        <div class="flex items-center justify-center w-full mb-3 mt-1 shrink-0">
                            <div class="flex items-center gap-1.5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-colors <?= $i <= $rating ? 'text-yellow-400 group-hover:text-yellow-300' : 'text-slate-200 group-hover:text-white/30' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                                    </svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="absolute -top-3 -left-2 text-4xl font-serif text-slate-200 group-hover:text-white/20 transition-colors select-none" aria-hidden="true">“</span>
                            <p class="text-sm leading-relaxed text-slate-600 group-hover:text-white transition-colors relative z-10 pl-2 line-clamp-4 overflow-hidden">
                                <?= e($review['content'] ?? '') ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-3 mt-4 shrink-0 border-t border-slate-50 group-hover:border-white/10 pt-4">
                            <img class="h-10 w-10 lg:h-11 lg:w-11 rounded-full object-cover bg-slate-100 shrink-0" src="<?= e($reviewerImage) ?>" alt="<?= e($reviewerName ?: 'Customer') ?>">
                            <div class="overflow-hidden">
                                <h4 class="text-sm font-bold text-dark group-hover:text-white transition-colors truncate"><?= e($reviewerName) ?></h4>
                                <?php if ($reviewerMeta !== ''): ?>
                                    <p class="text-xs font-medium text-slate-400 group-hover:text-white/80 transition-colors truncate mt-0.5"><?= e($reviewerMeta) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($partnerLogos)): ?>
        <div class="mx-auto w-full max-w-7xl py-8 mt-10">
            <h2 class="text-center text-primary font-bold text-2xl md:text-3xl tracking-normal uppercase mb-3 block">
                <?= e(getCurrentLang() === 'th' ? 'องค์กรชั้นนำที่ไว้วางใจ WEBPARK' : 'Leading Organizations that Trust WEBPARK') ?>
            </h2>
            <?php 
            $multiplier = count($partnerLogos) < 10 ? 4 : 2; // Dynamically adapt repeat times based on logo count
            ?>
            <!-- Desktop Layout: 1 Row Marquee -->
            <div class="hidden lg:block overflow-hidden relative mt-10 w-full">
                <!-- Fade overlays for premium look -->
                <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-[#f7faff] via-[#f7faff]/80 to-transparent z-10 pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-[#f7faff] via-[#f7faff]/80 to-transparent z-10 pointer-events-none"></div>
                <div class="flex w-max gap-16 items-center animate-scroll py-4">
                    <?php 
                    $desktopLogos = [];
                    for ($i = 0; $i < $multiplier; $i++) {
                        $desktopLogos = array_merge($desktopLogos, $partnerLogos);
                    }
                    ?>
                    <?php foreach ($desktopLogos as $logo): ?>
                        <div class="flex shrink-0 items-center justify-center w-[120px] h-[60px] opacity-80 hover:opacity-100 transition-opacity duration-300">
                            <img src="<?= e($logo['url']) ?>" alt="<?= e($logo['alt']) ?>" class="max-h-full max-w-full object-contain grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Mobile Layout: 2 Rows Marquee -->
            <div class="block lg:hidden overflow-hidden relative mt-8 w-full">
                <!-- Fade overlays for premium look -->
                <div class="absolute inset-y-0 left-0 w-12 bg-gradient-to-r from-[#f7faff] to-transparent z-10 pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-12 bg-gradient-to-l from-[#f7faff] to-transparent z-10 pointer-events-none"></div>
                <?php 
                $row1 = [];
                $row2 = [];
                foreach ($partnerLogos as $index => $logo) {
                    if ($index % 2 === 0) {
                        $row1[] = $logo;
                    } else {
                        $row2[] = $logo;
                    }
                }
                $mobileRow1 = [];
                $mobileRow2 = [];
                for ($i = 0; $i < $multiplier; $i++) {
                    $mobileRow1 = array_merge($mobileRow1, $row1);
                    $mobileRow2 = array_merge($mobileRow2, $row2);
                }
                ?>
                <!-- Row 1 -->
                <div class="flex w-max gap-10 items-center animate-scroll py-2">
                    <?php foreach ($mobileRow1 as $logo): ?>
                        <div class="flex shrink-0 items-center justify-center w-[100px] h-[50px] opacity-90">
                            <img src="<?= e($logo['url']) ?>" alt="<?= e($logo['alt']) ?>" class="max-h-full max-w-full object-contain cursor-pointer">
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Row 2 -->
                <div class="flex w-max gap-10 items-center animate-scroll py-2 mt-2">
                    <?php foreach ($mobileRow2 as $logo): ?>
                        <div class="flex shrink-0 items-center justify-center w-[100px] h-[50px] opacity-90">
                            <img src="<?= e($logo['url']) ?>" alt="<?= e($logo['alt']) ?>" class="max-h-full max-w-full object-contain cursor-pointer">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <p class="text-center mt-6 text-xs text-slate-400 tracking-wide font-medium">
                <?= e(getCurrentLang() === 'th' ? 'ทั้งหมดมาจากธุรกิจ การเงิน อสังหาริมทรัพย์ โรงงาน วิศวกรรม สื่อ และอีกมากมาย' : 'Including finance, real estate, manufacturing, engineering, media, and more.') ?>
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>
<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
<?php if (count($displayArticles) > 0): ?>
<section class="bg-slate-50 pt-6 pb-10 lg:py-10 border-t border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10">
        <div class="hidden lg:flex flex-row items-end justify-between mb-6 ipad-pro-hidden lg:ml-12 ipad-pro-ml-0 xl:ml-24 lg:mr-12 xl:mr-24">
            <div>
                <h2 class="text-primary font-black text-3xl tracking-normal m-0 inline-block">
                    <?= getCurrentLang() === 'th' ? 'บทความ' : 'Articles' ?>
                </h2>
                <div class="w-12 h-1 bg-primary mt-2 mb-4 rounded-full"></div>
                <h3 class="text-[#043B94] font-bold text-xl mb-3 tracking-tight">
                    <?= getCurrentLang() === 'th' ? 'สาระน่ารู้จาก WEBPARK' : 'Knowledge from WEBPARK' ?>
                </h3>
                <p class="text-slate-500 text-sm leading-[1.6] font-medium m-0">
                    <?= getCurrentLang() === 'th' ? 'รวมบทความสาระน่ารู้ ที่จะช่วยต่อยอดแบรนด์<br>และพาธุรกิจสู่วิถีดิจิทัลที่ช่วยให้ธุรกิจเติบโตได้อย่างยั่งยืน' : 'A collection of informative articles to help elevate your brand<br>and guide your business into the digital era for sustainable growth.' ?>
                </p>
            </div>
            <a href="<?= e(route_url('/article')) ?>" class="shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5 whitespace-nowrap">
                <?= getCurrentLang() === 'th' ? 'ดูบทความของเรา' : 'View Our Articles' ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
        <div class="lg:hidden mb-6 flex flex-col items-start text-left ipad-pro-flex-visible">
            <h2 class="text-primary font-black text-3xl md:text-2xl tracking-normal m-0 inline-block ipad-pro-article-h2 ipad-air-article-h2">
                <?= getCurrentLang() === 'th' ? 'บทความ' : 'Articles' ?>
            </h2>
            <div class="w-14 h-[3.5px] bg-primary mt-1.5 mb-3 rounded-full"></div>
            <h3 class="text-dark font-black text-2xl md:text-xl mb-3 mt-1 tracking-tight ipad-pro-article-h3 ipad-air-article-h3">
                <?= getCurrentLang() === 'th' ? 'สาระน่ารู้จาก WEBPARK' : 'Knowledge from WEBPARK' ?>
            </h3>
            <p class="text-slate-600 text-sm md:text-[0.8rem] leading-[1.6] font-medium ipad-pro-article-desc ipad-air-article-desc">
                <?= getCurrentLang() === 'th' ? 'รวมบทความสาระน่ารู้ ที่จะช่วยต่อยอดแบรนด์<br>และพาธุรกิจสู่วิถีดิจิทัลที่ช่วยให้ธุรกิจเติบโตได้อย่างยั่งยืน' : 'A collection of informative articles to help elevate your brand <br class="mobile-hidden">and guide your business into the digital era for sustainable growth.' ?>
            </p>
        </div>
        <div id="knowledge-slider" class="flex lg:grid overflow-x-auto lg:overflow-visible snap-x snap-mandatory flex-nowrap lg:flex-wrap lg:grid-cols-3 gap-6 pt-2 pb-6 hide-scrollbar ipad-pro-articles-slider">
            <?php foreach ($displayArticles as $art): ?>
                <?php
                $artId       = (int)($art['id'] ?? 0);
                $itemLang = getCurrentLang();
                $artTitle = (string)($art['title'] ?? 'Article');
                if ($itemLang === 'en' && !empty($art['meta_title_en'])) {
                    $artTitle = $art['meta_title_en'];
                }
                $summaryContent = (string)($art['description'] ?? $art['summary'] ?? '');
                if ($itemLang === 'en' && !empty($art['meta_description_en'])) {
                    $summaryContent = (string)$art['meta_description_en'];
                }
                $artSummary  = mb_strimwidth(strip_tags($summaryContent), 0, 110, '...');
                $artCat      = (string)($art['category'] ?? 'Knowledge');
                $artImage    = resolve_article_image_url($art['image_path'] ?? '', asset_url('images/erp.png'));
                ?>
                <article class="gsap-home-article-card flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md group w-full lg:w-auto shrink-0 lg:shrink snap-center lg:snap-align-none opacity-0 translate-y-10 ipad-pro-articles-card">
                    <a href="<?= e($artId > 0 ? route_url('/article', ['id' => $artId]) : route_url('/article')) ?>" class="flex flex-col h-full">
                        <div class="relative aspect-[16/9] w-full bg-slate-900 overflow-hidden shrink-0">
                            <img src="<?= e($artImage) ?>" alt="<?= e($artTitle) ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <span class="absolute bottom-3 left-3 rounded-md bg-primary/95 backdrop-blur-sm px-2.5 py-1 text-[10px] font-bold tracking-wider text-white uppercase shadow-sm ipad-pro-card-badge">
                                <?= e($artCat) ?>
                            </span>
                        </div>
                        <div class="flex flex-col flex-1 p-6">
                            <h3 class="text-[17px] md:text-base font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-primary transition-colors mb-3 ipad-pro-card-title ipad-air-card-title">
                                <?= e($artTitle) ?>
                            </h3>
                            <p class="text-[14px] md:text-[13px] text-slate-500 leading-relaxed line-clamp-3 mb-5 flex-1 ipad-pro-card-desc ipad-air-card-desc">
                                <?= e($artSummary) ?>
                            </p>
                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center gap-1 text-[13px] md:text-xs font-bold text-primary ipad-pro-card-link ipad-air-card-link">
                                <?= e(t('common.cta_read_more')) ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <div id="knowledge-dots" class="flex lg:hidden justify-center items-center gap-2 mt-4 flex-wrap ipad-pro-flex-visible"></div>
    </div>
</section>
<?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('knowledge-slider');
    if (!slider) return;
    const cards = slider.querySelectorAll('article');
    const dotsContainer = document.getElementById('knowledge-dots');
    if (!dotsContainer) return;
    const totalItems = cards.length;
    function updateKnowledgeDots() {
        const scrollLeft = slider.scrollLeft;
        const cardWidth = cards[0]?.getBoundingClientRect().width || slider.clientWidth || 1;
        const index = Math.min(totalItems - 1, Math.max(0, Math.round(scrollLeft / cardWidth)));
        const dots = dotsContainer.querySelectorAll('.knowledge-dot');
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-primary', i === index);
            dot.classList.toggle('w-6', i === index);
            dot.classList.toggle('bg-slate-300', i !== index);
            dot.classList.toggle('w-2', i !== index);
        });
    }
    if (totalItems > 1) {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < totalItems; i++) {
            const dot = document.createElement('span');
            dot.className = `knowledge-dot h-2 rounded-full cursor-pointer transition-all ${i === 0 ? 'bg-primary w-6' : 'bg-slate-300 w-2'}`;
            dot.addEventListener('click', () => {
                const cardWidth = cards[0]?.getBoundingClientRect().width || slider.clientWidth;
                slider.scrollTo({
                    left: i * cardWidth,
                    behavior: 'smooth'
                });
            });
            dotsContainer.appendChild(dot);
        }
        slider.addEventListener('scroll', updateKnowledgeDots, { passive: true });
        window.addEventListener('resize', updateKnowledgeDots, { passive: true });
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    gsap.registerPlugin(ScrollTrigger);
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
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
    // 1. Hero Parallax
    if (!prefersReducedMotion) {
        gsap.utils.toArray(".hero-parallax-img").forEach((img) => {
            gsap.to(img, {
                yPercent: 12,
                ease: "none",
                scrollTrigger: {
                    trigger: "section",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                }
            });
        });
    }
    // 2. Service Cards Stagger
    const serviceCards = gsap.utils.toArray(".gsap-home-service-card");
    if (serviceCards.length) {
        if (prefersReducedMotion) {
            gsap.set(serviceCards, { y: 0, opacity: 1 });
        } else {
            gsap.to(serviceCards, {
                scrollTrigger: {
                    trigger: ".gsap-home-service-card",
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                },
                y: 0,
                opacity: 1,
                duration: 0.5,
                stagger: 0.1,
                ease: "power2.out"
            });
        }
    }
    // 3. Portfolio Cards
    revealOnScroll(".gsap-home-portfolio-card", { stagger: 0.1 });
    // 4. Articles Section
    revealOnScroll(".gsap-home-article-card", { stagger: 0.08 });
});
</script>
