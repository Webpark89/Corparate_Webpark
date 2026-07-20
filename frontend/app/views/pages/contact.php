<?php

declare(strict_types=1);

/**
 * Contact page view — hero, inquiry form, company info, and map embed.
 */

$errors = $errors ?? [];
$submitted = $submitted ?? false;
$form = $form ?? [];
?>
<style>
    /* คงไว้แค่แอนิเมชันตอนโหลดหน้าสไลด์ขึ้น */
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
        transform: scale(1.12);
        will-change: transform;
    }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.001ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<!-- นำขอบโค้งและ margin ออก เพื่อให้ชิดขอบจอด้านบนและด้านข้างแบบ Edge-to-Edge -->
<section id="contact-hero" class="relative overflow-hidden font-sans bg-white border-none">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="hero-parallax-img w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-10 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl px-4 md:px-0">
                <nav aria-label="Breadcrumb" class="hidden md:block animate-fade-up delay-100 mb-6">
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
                            <span class="text-slate-400"><?= e(t('contact.hero_title')) ?></span>
                        </li>
                    </ol>
                </nav>

                <style>
                    .hero-title-text {
                        font-size: 2.25rem;
                        line-height: 1.25;
                    }
                    .hero-desc-text {
                        font-size: 17px;
                        line-height: 1.65;
                    }
                    @media (min-width: 768px) {
                        .hero-title-text { font-size: 3.5rem; line-height: 1.2; }
                        .hero-desc-text { font-size: 21px; line-height: 1.7; }
                    }
                    @media (min-width: 1024px) {
                        .hero-title-text { font-size: 4.5rem; line-height: 1.2; }
                    }
                    @media (min-width: 1280px) {
                        .hero-title-text { font-size: 5rem; line-height: 1.2; }
                    }
                    @keyframes text-gradient-pan {
                        0% { background-position: 0% center; }
                        50% { background-position: 100% center; }
                        100% { background-position: 0% center; }
                    }
                    .animate-text-gradient {
                        background-size: 200% auto;
                        animation: text-gradient-pan 6s linear infinite;
                    }
                </style>
                <h1 class="animate-fade-up delay-200 tracking-tight mb-2">
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block pb-1 md:pb-2 whitespace-nowrap"><?= e(t('contact.hero_title')) ?></span><br>
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block -mt-2 md:-mt-8 whitespace-nowrap" style="animation-delay: -3s;">WEBPARK</span>
                </h1>
                <?php
                if (getCurrentLang() === 'th') {
                    $mobile_desc = "มาพูดคุยเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์<br>หรือ ERP/ERM และดิจิทัลโซลูชัน<br>สำหรับธุรกิจคุณ";
                } else {
                    $mobile_desc = "Let's talk about your project, system,<br>website, or ERP/ERM and digital<br>solutions for your business";
                }
                ?>
                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                    <span class="block md:hidden leading-[1.75]">
                        <?= $mobile_desc ?>
                    </span>
                    <span class="hidden md:block leading-relaxed">
                        <?= e(getCurrentLang() === 'th' ? 'พูดคุยและปรึกษาเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์' : 'Let\'s talk about your project, system, website,') ?><br>
                        <?= e(getCurrentLang() === 'th' ? 'ERP / ERM และโซลูชันดิจิทัลเพื่อธุรกิจของคุณ' : 'or ERP/ERM and digital solutions for your business.') ?>
                    </span>
                </p>

                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start sm:items-center gap-2 md:gap-4">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        <?= e(t('common.cta_view_services')) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#" class="inline-flex items-center gap-3 transition-all hover:-translate-y-0.5 group">
                        <div class="h-12 w-12 bg-white flex items-center justify-center rounded-full shadow-sm border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-md group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-400 text-sm font-medium group-hover:text-primary transition-colors"><?= e(t('common.cta_watch_intro_video')) ?></span>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* Animation Delay Utilities */
    .animation-delay-200 { animation-delay: 0.2s; }
    .animation-delay-400 { animation-delay: 0.4s; }
</style>

<section id="contact-section" class="bg-white pt-6 pb-16 lg:py-24 font-sans border-none">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <style>
            @media (min-width: 1024px) {
                .align-with-right-cards { margin-top: 56px !important; }
            }
        </style>
        <style>
            @media (max-width: 1023px) {
                .mobile-swap-container {
                    display: flex !important;
                    flex-direction: column-reverse !important;
                }
            }
        </style>
        <div class="mobile-swap-container grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">
            <div class="gsap-contact-form lg:col-span-6 bg-white border border-slate-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] rounded-[2.5rem] p-6 md:p-10 flex flex-col align-with-right-cards opacity-0 translate-y-10">
                <h2 class="text-2xl md:text-3xl font-bold text-dark mb-6">
                    <?= e(t('contact.form_title')) ?>
                </h2>

                <?php if ($submitted): ?>
                    <div class="text-center py-16 flex flex-col items-center justify-center flex-grow">
                        <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2"><?= e(t('contact.form_success_title')) ?></h3>
                        <p class="text-slate-500 text-sm font-medium"><?= e(t('contact.form_success_desc')) ?></p>
                    </div>
                <?php else: ?>
                    <style>
                        .custom-placeholder::placeholder {
                            color: #022862 !important;
                            opacity: 1 !important;
                        }
                    </style>
                    <form method="post" class="flex flex-col flex-grow space-y-4">
                        <!-- Desktop Name Field (1 field) -->
                        <div id="contact_desktop-name-wrapper" class="hidden lg:block">
                            <input type="text" id="contact_name_desktop" name="name" placeholder="<?= e(t('common.form_label_fullname')) ?>" value="<?= e($form['name'] ?? '') ?>" required maxlength="100"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                        </div>

                        <!-- Mobile Name Fields (2 fields) -->
                        <div id="contact_mobile-name-wrapper" class="grid grid-cols-1 gap-4 lg:hidden">
                            <input type="text" id="contact_name_mobile_first" name="firstname" placeholder="<?= e(getCurrentLang() === 'th' ? 'ชื่อ' : 'First Name') ?>" value="<?= e($form['firstname'] ?? '') ?>" required maxlength="50"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                            <input type="text" id="contact_name_mobile_last" name="lastname" placeholder="<?= e(getCurrentLang() === 'th' ? 'นามสกุล' : 'Last Name') ?>" value="<?= e($form['lastname'] ?? '') ?>" required maxlength="50"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                        </div>

                        <script>
                            function updateContactFormLayout() {
                                const isDesktop = window.innerWidth >= 1024; // lg breakpoint
                                const nameDesktop = document.getElementById('contact_name_desktop');
                                const nameMobileFirst = document.getElementById('contact_name_mobile_first');
                                const nameMobileLast = document.getElementById('contact_name_mobile_last');
                                
                                if (isDesktop) {
                                    if(nameDesktop) nameDesktop.disabled = false;
                                    if(nameMobileFirst) nameMobileFirst.disabled = true;
                                    if(nameMobileLast) nameMobileLast.disabled = true;
                                } else {
                                    if(nameDesktop) nameDesktop.disabled = true;
                                    if(nameMobileFirst) nameMobileFirst.disabled = false;
                                    if(nameMobileLast) nameMobileLast.disabled = false;
                                }
                            }
                            window.addEventListener('resize', updateContactFormLayout);
                            window.addEventListener('DOMContentLoaded', updateContactFormLayout);
                            updateContactFormLayout();
                        </script>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input type="text" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?>" value="<?= e($form['phone'] ?? '') ?>" required maxlength="40"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">

                            <input type="email" name="email" placeholder="<?= e(t('common.form_label_email')) ?>" value="<?= e($form['email'] ?? '') ?>" required maxlength="255"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                        </div>

                        <div>
                            <textarea name="message" placeholder="<?= e(t('common.form_label_details')) ?>" required maxlength="1000" rows="5"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none focus:shadow-inner"><?= e($form['message'] ?? '') ?></textarea>
                        </div>

                        <div class="flex items-start gap-3 pt-2">
                            <input type="checkbox" id="privacy" name="privacy_agreed" required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all duration-200">
                            <label for="privacy" class="text-sm md:text-base leading-relaxed cursor-pointer select-none">
                                <span style="color: #022862;"><?= e(t('common.form_consent_prefix')) ?></span> <a href="#" style="color: #0663F6;" class="hover:underline transition-colors duration-200"><?= e(t('common.form_consent_privacy_policy')) ?></a> <span style="color: #0663F6;"><?= e(t('common.form_consent_terms_suffix')) ?></span>
                            </label>
                        </div>

                        <?php if ($errors !== []): ?>
                            <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                        <?php endif; ?>

                        <div class="pt-2 mt-auto flex justify-center">
                            <button type="submit" class="px-8 py-3.5 bg-primary hover:bg-blue-700 text-white font-bold text-base rounded-full shadow-md shadow-blue-500/10 transition-all duration-300 cursor-pointer hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <?= e(t('contact.cta_send_message')) ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div id="company-info" class="lg:col-span-6 space-y-4">
                <h2 class="text-xl md:text-2xl font-bold text-dark mb-6"><?= e(t('contact.company_info_title')) ?></h2>
                
                <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div><h4 style="color: #054FC5;" class="font-bold text-[17px] md:text-lg"><?= e(t('contact.company_name')) ?></h4></div>
                </div>

                <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('contact.address_label')) ?></h4>
                        <p style="color: #054FC5;" class="text-base font-medium leading-relaxed"><?= e(t('contact.company_address')) ?></p>
                    </div>
                </div>

                <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('common.form_label_phone')) ?></h4>
                        <p style="color: #054FC5;" class="text-base font-semibold tracking-wide">095-539-2666</p>
                    </div>
                </div>

                <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('common.form_label_email')) ?></h4>
                        <p style="color: #054FC5;" class="text-base font-semibold">oraphan@webpark.co.th</p>
                    </div>
                </div>

                <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10">
                    <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('contact.office_hours_label')) ?></h4>
                        <p style="color: #054FC5;" class="text-base font-medium"><?= e(t('contact.office_hours_value')) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 bg-[#eef4fc] border border-blue-200 rounded-xl py-6 px-6 flex flex-row items-center justify-center sm:justify-start gap-6 transition-all duration-300">
            <div class="text-[#043B94] shrink-0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-14 h-14">
                    <path d="M3 16v-5a9 9 0 0 1 18 0v5"></path>
                    <rect x="3" y="14" width="4" height="6" rx="1"></rect>
                    <rect x="17" y="14" width="4" height="6" rx="1"></rect>
                    <path d="M19 18v2a3 3 0 0 1-3 3h-5"></path>
                    <path d="M12 21a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                </svg>
            </div>
            <div class="text-left">
                <h4 class="font-bold text-[#043B94] text-lg md:text-xl leading-relaxed mb-2">
                    <?= e(t('contact.response_time_badge')) ?>
                </h4>
                <p class="text-slate-600 text-base leading-relaxed">
                    <?= e(t('contact.team_support_desc')) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white pb-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-[#f4f9ff] border border-blue-50/50 rounded-[2rem] p-6 md:p-8">
            
            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                <div>
                    <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1"><?= e(t('contact.fast_response_title')) ?></h4>
                    <p style="color: #022862;" class="text-base leading-relaxed"><?= e(t('contact.fast_response_desc')) ?></p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 border-y md:border-y-0 md:border-x border-blue-100/50">
                <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <div>
                    <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1"><?= e(t('contact.expert_advice_title')) ?></h4>
                    <p style="color: #022862;" class="text-base leading-relaxed"><?= e(t('contact.expert_advice_desc')) ?></p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <div>
                    <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1"><?= e(t('contact.full_system_support_title')) ?></h4>
                    <p style="color: #022862;" class="text-base leading-relaxed"><?= e(t('contact.full_system_support_desc')) ?></p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-white pb-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <h2 class="text-xl md:text-2xl font-bold text-dark mb-8 relative inline-block">
            <?= e(t('contact.location_title')) ?>
            <span class="absolute left-0 bottom-[-8px] w-10 h-1 bg-primary rounded-full"></span>
        </h2>

        <div class="w-full h-[400px] md:h-[500px] rounded-[2.5rem] overflow-hidden relative border border-slate-100 shadow-sm">
            <iframe class="w-full h-full border-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.311743152067!2d100.6172557!3d13.7901399!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d62445b2b2b11%3A0x67ee1c03c2a9bb4c!2sWEBPARK!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth" allowfullscreen="" loading="lazy"></iframe>

            <div class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 max-w-sm z-10 hidden sm:block">
                <div style="color: #021E4A;" class="flex items-center gap-2 font-bold text-sm mb-3">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <?= e(t('contact.company_name')) ?>
                </div>
                <p style="color: #022862;" class="text-xs md:text-[13px] leading-relaxed font-medium mb-4">
                    <?= e(t('contact.company_address')) ?>
                </p>
                <a href="https://maps.google.com" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                    <?= e(t('contact.cta_get_directions')) ?> <span>→</span>
                </a>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    gsap.registerPlugin(ScrollTrigger);

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // Helper function for reveal on scroll
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
                    trigger: "#contact-hero",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                }
            });
        });
    }

    // 2. Form & Info Cards Stagger
    revealOnScroll(".gsap-contact-form");
    revealOnScroll(".gsap-contact-info-card", { stagger: 0.1 });
});
</script>