<?php
declare(strict_types=1);
/**
 * Contact page view — hero, inquiry form, company info, and map embed.
 */
$errors = $errors ?? [];
$submitted = $submitted ?? false;
$recaptchaSiteKey = '6Lcf_pAtAAAAAOVhatPPwrHSYXeb_0J4yXf5BrRO';
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
<section id="contact-hero" class="relative overflow-hidden font-sans bg-white border-none">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="hero-parallax-img w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-10 lg:pt-28 lg:pb-32 relative z-10 desktop-wide-container-contact">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="max-w-2xl px-4 md:px-0 lg:ml-12 ipad-pro-ml-0 xl:ml-24">
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
                        .hero-title-text { font-size: 5.5rem; line-height: 1.2; }
                    }
                    @media (min-width: 1280px) {
                        .hero-title-text { font-size: 7rem; line-height: 1.2; }
                    }
                    
                    /* สไตล์พิเศษสำหรับหน้าจอ Desktop (ใหญ่กว่า iPad Pro) */
                    @media (min-width: 1025px) {
                        .desktop-wide-container-contact {
                            max-width: 1720px !important;
                            padding-left: 2.5rem !important;
                            padding-right: 2.5rem !important;
                        }
                        .desktop-contact-hero-h1 {
                            font-size: 5.5rem !important;
                            line-height: 1.1 !important;
                        }
                        .desktop-contact-hero-p {
                            font-size: 1.25rem !important;
                            line-height: 1.75 !important;
                            max-width: 34rem !important;
                        }
                    }

                    @media (min-width: 1024px) and (max-width: 1279px) {
                        .ipad-pro-strict-nowrap {
                            white-space: nowrap !important;
                            font-size: 1.15rem !important;
                        }
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
                <h1 class="animate-fade-up delay-200 tracking-tight mb-2 leading-[1.1]">
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-2 md:py-2.5 whitespace-nowrap desktop-contact-hero-h1"><?= e(t('contact.hero_title')) ?></span><br>
                    <span class="hero-title-text font-bold bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block py-1 md:py-2 -mt-1 md:-mt-2 lg:-mt-2 whitespace-nowrap desktop-contact-hero-h1" style="animation-delay: -3s;">WEBPARK</span>
                </h1>
                <?php
                if (getCurrentLang() === 'th') {
                    $mobile_desc = "มาพูดคุยเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์ หรือ ERP/ERM และดิจิทัลโซลูชันสำหรับธุรกิจคุณ";
                } else {
                    $mobile_desc = "Let's talk about your project, system, website, or ERP/ERM and digital solutions for your business.";
                }
                ?>
                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium desktop-contact-hero-p">
                    <span class="block md:hidden leading-[1.75]">
                        <?= $mobile_desc ?>
                    </span>
                    <span class="hidden md:block leading-relaxed ipad-pro-strict-nowrap">
                        <?= e(getCurrentLang() === 'th' ? 'พูดคุยและปรึกษาเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์' : 'Let\'s talk about your project, system, website,') ?><br>
                        <?= e(getCurrentLang() === 'th' ? 'ERP / ERM และโซลูชันดิจิทัลเพื่อธุรกิจของคุณ' : 'or ERP/ERM and digital solutions for your business.') ?>
                    </span>
                </p>
                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start sm:items-center gap-2 md:gap-4">
                    <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-base font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5 whitespace-nowrap">
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
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6 desktop-wide-container-contact"> 
        <div class="lg:px-12 xl:px-24">
            <style>
                @media (min-width: 1024px) {
                    .align-with-right-cards { margin-top: 56px !important; }
                }
                @media (min-width: 1025px) {
                    .desktop-contact-section-title {
                        font-size: 2rem !important;
                        color: #0663F6 !important;
                        margin-bottom: 2rem !important;
                    }
                    .desktop-contact-card {
                        padding: 2rem !important;
                    }
                    .desktop-contact-icon {
                        width: 3.5rem !important;
                        height: 3.5rem !important;
                    }
                    .desktop-contact-icon svg {
                        width: 1.75rem !important;
                        height: 1.75rem !important;
                    }
                    .desktop-contact-btn-wrapper {
                        justify-content: flex-start !important;
                    }
                }
            </style>
            <style>
                @media (max-width: 1023px) {
                    .mobile-swap-container {
                        display: flex !important;
                        flex-direction: column-reverse !important;
                    }
                }
                @media (min-width: 768px) and (max-width: 1023px) {
                    .mobile-swap-container {
                        width: 100% !important;
                    }
                    .gsap-contact-form,
                    #company-info {
                        width: 100% !important;
                        max-width: 100% !important;
                    }
                    #contact_mobile-name-wrapper {
                        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                    }
                }
            </style>
            <div class="mobile-swap-container grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">
                <div class="gsap-contact-form lg:col-span-6 bg-white border border-slate-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] rounded-[2.5rem] p-6 md:p-10 flex flex-col align-with-right-cards opacity-0 translate-y-10">
                    <h2 class="text-2xl md:text-3xl font-bold text-dark mb-6 desktop-contact-section-title">
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
                        <form id="contactMainForm" method="post" class="flex flex-col flex-grow space-y-4">
                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" id="contact_firstname" name="firstname" placeholder="<?= e(t('common.form_label_firstname')) ?> *" value="<?= e($form['firstname'] ?? '') ?>" required maxlength="50"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                                </div>
                                <div>
                                    <input type="text" id="contact_lastname" name="lastname" placeholder="<?= e(t('common.form_label_lastname')) ?> *" value="<?= e($form['lastname'] ?? '') ?>" required maxlength="50"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                                </div>
                            </div>

                            <!-- Company Name (Mandatory, '-' if none) -->
                            <div>
                                <input type="text" name="company" placeholder="<?= e(t('common.form_label_company_optional')) ?>" value="<?= e($form['company'] ?? '') ?>" required maxlength="100"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                            </div>

                            <!-- Phone & Email -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" inputmode="numeric" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?> *" value="<?= e($form['phone'] ?? '') ?>" required maxlength="10" pattern="\d{9,10}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                                </div>
                                <div>
                                    <input type="email" name="email" placeholder="<?= e(t('common.form_label_email')) ?> *" value="<?= e($form['email'] ?? '') ?>" required maxlength="255"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="space-y-1">
                                <textarea id="contact_message_area" name="message" placeholder="<?= e(t('common.form_label_details')) ?> *" required rows="4"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-base text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none focus:shadow-inner"><?= e($form['message'] ?? '') ?></textarea>
                            </div>

                            <!-- Privacy Policy Scrollable Box -->
                            <div id="privacy_policy_box" class="mt-4 mb-2 p-4 md:p-5 rounded-xl border border-slate-200 bg-slate-50 overflow-y-auto max-h-48 text-sm text-slate-600 leading-relaxed custom-scrollbar shadow-inner">
                                <h4 class="font-bold text-slate-800 mb-2">นโยบายความเป็นส่วนตัว (Privacy Policy)</h4>
                                <p class="mb-4">
                                    WEBPARK Co., Ltd. ("เรา" หรือ "WebPark") ในฐานะผู้ควบคุมข้อมูลส่วนบุคคล (Data Controller) ตระหนักและให้ความสำคัญอย่างยิ่งต่อการคุ้มครองข้อมูลส่วนบุคคลและสิทธิความเป็นส่วนตัวของท่าน นโยบายฉบับนี้จัดทำขึ้นตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) เพื่อชี้แจงรายละเอียดเกี่ยวกับการเก็บรวบรวม ใช้ เปิดเผยข้อมูล และการใช้คุกกี้ บนเว็บไซต์ webpark.co.th ทั้งหมด
                                </p>
                                
                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">1</span> 
                                    ขอบเขตข้อมูลส่วนบุคคลที่เราเก็บรวบรวม
                               </h5>
                                <p class="mb-2">เราเก็บรวบรวมข้อมูลส่วนบุคคลของท่านผ่านการใช้งานเว็บไซต์ในกรณีต่างๆ เท่าที่จำเป็นดังนี้:</p>
                                <ul class="list-disc pl-5 mb-4 space-y-1">
                                    <li>ชื่อ-นามสกุล, เบอร์โทรศัพท์, และอีเมล ที่ท่านกรอกผ่านแบบฟอร์มติดต่อเรา</li>
                                    <li>ข้อมูลองค์กรหรือบริษัทของท่าน (หากมี)</li>
                                    <li>รายละเอียดข้อความหรือความต้องการที่ท่านส่งถึงเรา</li>
                                </ul>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">2</span> 
                                    วัตถุประสงค์ในการเก็บรวบรวมข้อมูล
                               </h5>
                                <p class="mb-4">
                                    ข้อมูลที่ท่านให้จะถูกนำไปใช้เพื่อติดต่อกลับ นำเสนอบริการที่ตรงกับความต้องการของท่าน และปรับปรุงประสิทธิภาพของเว็บไซต์เท่านั้น เราจะไม่มีการเปิดเผยข้อมูลของท่านแก่บุคคลที่สามโดยไม่ได้รับอนุญาต
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">3</span> 
                                    การเปิดเผยข้อมูลแก่บุคคลที่สาม
                               </h5>
                                <p class="mb-4">
                                    เราจะไม่ขาย ให้เช่า หรือเปิดเผยข้อมูลส่วนบุคคลของท่านให้แก่บุคคลภายนอก เว้นแต่กรณีที่จำเป็นเพื่อการให้บริการแก่ท่าน (เช่น ผู้ให้บริการระบบคลาวด์/เซิร์ฟเวอร์ที่ปลอดภัย หรือผู้ให้บริการจัดส่งเอกสาร) หรือในกรณีที่กฎหมายบังคับให้เปิดเผยเท่านั้น
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">4</span> 
                                    ระยะเวลาจัดเก็บและการรักษาความปลอดภัย
                               </h5>
                                <p class="mb-4">
                                    เราจะจัดเก็บข้อมูลส่วนบุคคลของท่านไว้เป็นเวลาตลอดระยะเวลาที่ให้บริการ เพื่อบรรลุวัตถุประสงค์ตามที่แจ้งไว้ โดยเราใช้มาตรการรักษาความปลอดภัยทางเทคนิคที่ได้มาตรฐาน (เช่น การเข้ารหัสข้อมูล SSL) เพื่อปกป้องข้อมูลของท่านจากการเข้าถึง แก้ไข หรือเปิดเผยโดยไม่ได้รับอนุญาต
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">5</span> 
                                    สิทธิของเจ้าของข้อมูลและช่องทางการติดต่อ
                               </h5>
                                <p class="mb-2">
                                    ท่านมีสิทธิ์ตามกฎหมายในการขอเข้าถึง ขอสำเนา ขอแก้ไข หรือขอให้ลบข้อมูลส่วนบุคคลของท่านได้ทุกเมื่อ หากท่านต้องการใช้สิทธิ์ดังกล่าว หรือมีข้อสงสัยเกี่ยวกับนโยบายนี้ สามารถติดต่อเราได้ที่:
                                </p>
                                <ul class="list-none mb-4 space-y-1">
                                    <li><strong>อีเมล:</strong> oraphan@webpark.co.th</li>
                                    <li><strong>โทรศัพท์:</strong> 095-539-2666</li>
                                </ul>
                            </div>
                            <style>
                                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                                .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                            </style>

                            <!-- PDPA Consent Checkbox -->
                            <div class="flex items-start gap-3 pt-2">
                                <input type="checkbox" id="privacy_consent_checkbox" name="pdpa_agreed" value="1" <?= !empty($form['pdpa_agreed']) ? 'checked' : '' ?> required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all duration-200">
                                <label for="privacy_consent_checkbox" class="text-sm md:text-base leading-relaxed cursor-pointer select-none">
                                    <span style="color: #022862;"><?= e(t('common.form_consent_prefix')) ?></span> <a href="#" style="color: #0663F6;" class="hover:underline transition-colors duration-200"><?= e(t('common.form_consent_privacy_policy')) ?></a> <span style="color: #0663F6;"><?= e(t('common.form_consent_terms_suffix')) ?></span>
                                </label>
                            </div>

                            <!-- Google reCAPTCHA v2 Widget -->
                            <div class="pt-2 pb-1 flex justify-center sm:justify-start">
                                <div class="g-recaptcha" data-sitekey="<?= e($recaptchaSiteKey) ?>"></div>
                            </div>

                            <!-- Error Message Alerts -->
                            <?php if (!empty($errors)): ?>
                                <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-600 space-y-1.5">
                                    <?php foreach ($errors as $error): ?>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span><?= e($error) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Submit Button (Disabled until PDPA checked) -->
                            <div class="pt-2 mt-auto flex justify-center desktop-contact-btn-wrapper">
                                <button type="submit" id="contact_submit_btn" disabled class="px-8 py-3.5 bg-primary hover:bg-blue-700 text-white font-bold text-base rounded-full shadow-md shadow-blue-500/10 transition-all duration-300 cursor-pointer hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 desktop-contact-btn disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none disabled:hover:translate-y-0">
                                    <?= e(t('contact.cta_send_message')) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const privacyCb = document.getElementById('privacy_consent_checkbox');
                                const submitBtn = document.getElementById('contact_submit_btn');
                                const policyBox = document.getElementById('privacy_policy_box');

                                // 1. Toggle submit button disabled state according to PDPA checkbox
                                function updateSubmitBtnState() {
                                    if (privacyCb && submitBtn) {
                                        submitBtn.disabled = !privacyCb.checked;
                                    }
                                }

                                if (privacyCb) {
                                    privacyCb.addEventListener('change', updateSubmitBtnState);
                                    updateSubmitBtnState();
                                }

                                // 2. Require scrolling to bottom to unlock PDPA checkbox
                                if (privacyCb && policyBox && !privacyCb.checked) {
                                    const cbWrapper = privacyCb.parentElement;
                                    privacyCb.disabled = true;
                                    cbWrapper.style.opacity = '0.6';
                                    cbWrapper.style.cursor = 'not-allowed';
                                    
                                    function checkScroll() {
                                        if (policyBox.scrollHeight - policyBox.scrollTop <= policyBox.clientHeight + 25) {
                                            privacyCb.disabled = false;
                                            cbWrapper.style.opacity = '1';
                                            cbWrapper.style.cursor = 'pointer';
                                            policyBox.removeEventListener('scroll', checkScroll);
                                        }
                                    }
                                    
                                    policyBox.addEventListener('scroll', checkScroll, { passive: true });
                                    // Trigger once in case text is short enough to not need scrolling
                                    setTimeout(checkScroll, 100);

                                    // If user taps the disabled checkbox area on mobile, auto-scroll to bottom to assist them
                                    cbWrapper.addEventListener('click', function() {
                                        if (privacyCb.disabled) {
                                            policyBox.scrollTo({ top: policyBox.scrollHeight, behavior: 'smooth' });
                                            setTimeout(checkScroll, 300);
                                        }
                                    });
                                }
                            });
                        </script>
                    <?php endif; ?>
                </div>
                <div id="company-info" class="lg:col-span-6 space-y-4">
                    <h2 class="text-xl md:text-2xl font-bold text-dark mb-6 desktop-contact-section-title"><?= e(t('contact.company_info_title')) ?></h2>
                    <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10 desktop-contact-card">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 desktop-contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div><h4 style="color: #054FC5;" class="font-bold text-[17px] md:text-lg"><?= e(t('contact.company_name')) ?></h4></div>
                    </div>
                    <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10 desktop-contact-card">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5 desktop-contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('contact.address_label')) ?></h4>
                            <p style="color: #054FC5;" class="text-base font-medium leading-relaxed"><?= e(t('contact.company_address')) ?></p>
                        </div>
                    </div>
                    <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10 desktop-contact-card">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5 desktop-contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('common.form_label_phone')) ?></h4>
                            <p style="color: #054FC5;" class="text-base font-semibold tracking-wide">095-539-2666</p>
                        </div>
                    </div>
                    <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10 desktop-contact-card">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5 desktop-contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('common.form_label_email')) ?></h4>
                            <p style="color: #054FC5;" class="text-base font-semibold">oraphan@webpark.co.th</p>
                        </div>
                    </div>
                    <div class="gsap-contact-info-card bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50 opacity-0 translate-y-10 desktop-contact-card">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5 desktop-contact-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 style="color: #021E4A;" class="font-bold text-sm uppercase mb-0.5"><?= e(t('contact.office_hours_label')) ?></h4>
                            <p style="color: #054FC5;" class="text-base font-medium"><?= e(t('contact.office_hours_value')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10 bg-[#eef4fc] border border-blue-100 rounded-xl py-6 px-6 flex flex-row items-center justify-center gap-6 transition-all duration-300 desktop-banner-center">
                <style>
                    @media (min-width: 1025px) {
                        .desktop-banner-center {
                            justify-content: center !important;
                        }
                        .desktop-features-container {
                            background-color: #ffffff !important;
                            border: 1px solid #f1f5f9 !important;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.02) !important;
                            padding: 3rem !important;
                            gap: 2rem !important;
                            border-radius: 1rem !important;
                        }
                        .desktop-feature-icon-wrapper {
                            background-color: #eef4fc !important;
                            color: #0663F6 !important;
                            border-radius: 1rem !important;
                            width: 4rem !important;
                            height: 4rem !important;
                        }
                        .desktop-feature-icon-wrapper svg {
                            width: 2rem !important;
                            height: 2rem !important;
                            color: #0663F6 !important;
                        }
                        .desktop-feature-title {
                            font-size: 1.25rem !important;
                            color: #0663F6 !important;
                        }
                        .desktop-feature-desc {
                            font-size: 1rem !important;
                            color: #64748b !important;
                        }
                        .desktop-border-none {
                            border: none !important;
                        }
                    }
                    @media (min-width: 760px) and (max-width: 819px) {
                        .ipad-mini-feature-item {
                            flex-direction: column !important;
                            align-items: center !important;
                            text-align: center !important;
                        }
                    }
                </style>
                <div class="text-[#043B94] shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12">
                        <path d="M3 16v-5a9 9 0 0 1 18 0v5"></path>
                        <rect x="3" y="14" width="4" height="6" rx="1"></rect>
                        <rect x="17" y="14" width="4" height="6" rx="1"></rect>
                        <path d="M19 18v2a3 3 0 0 1-3 3h-5"></path>
                        <path d="M12 21a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <h4 class="font-bold text-[#043B94] text-lg md:text-xl leading-relaxed mb-1">
                        <?= e(t('contact.response_time_badge')) ?>
                    </h4>
                    <p class="text-[#043B94] text-sm md:text-base leading-relaxed opacity-80">
                        <?= e(t('contact.team_support_desc')) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-white pb-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6 desktop-wide-container-contact"> 
        <div class="lg:px-12 xl:px-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-[#f4f9ff] border border-blue-50/50 rounded-[2rem] p-6 md:p-8 desktop-features-container">
                <div class="flex items-start gap-4 p-4 ipad-mini-feature-item">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm desktop-feature-icon-wrapper"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <div>
                        <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1 desktop-feature-title"><?= e(t('contact.fast_response_title')) ?></h4>
                        <p style="color: #022862;" class="text-base leading-relaxed desktop-feature-desc"><?= e(t('contact.fast_response_desc')) ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 border-y md:border-y-0 md:border-x border-blue-100/50 desktop-border-none ipad-mini-feature-item">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm desktop-feature-icon-wrapper"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <div>
                        <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1 desktop-feature-title"><?= e(t('contact.expert_advice_title')) ?></h4>
                        <p style="color: #022862;" class="text-base leading-relaxed desktop-feature-desc"><?= e(t('contact.expert_advice_desc')) ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 ipad-mini-feature-item">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm desktop-feature-icon-wrapper"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                    <div>
                        <h4 style="color: #0663F6;" class="font-bold text-[17px] md:text-lg mb-1 desktop-feature-title"><?= e(t('contact.full_system_support_title')) ?></h4>
                        <p style="color: #022862;" class="text-base leading-relaxed desktop-feature-desc"><?= e(t('contact.full_system_support_desc')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-white pb-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6 desktop-wide-container-contact"> 
        <div class="lg:px-12 xl:px-24">
            <style>
                @media (min-width: 1025px) {
                    .desktop-map-container {
                        height: 650px !important;
                        border-radius: 2rem !important;
                    }
                    .desktop-map-iframe {
                        pointer-events: auto !important;
                    }
                    .desktop-map-overlay-hide {
                        display: none !important;
                    }
                    .desktop-location-title-hide {
                        display: none !important;
                    }
                }
            </style>
            <h2 class="text-xl md:text-2xl font-bold text-dark mb-6 relative inline-block desktop-location-title-hide">
                <?= e(t('contact.location_title')) ?>
                <span class="absolute left-0 bottom-[-8px] w-10 h-1 bg-primary rounded-full"></span>
            </h2>
            
            <div class="w-full h-[260px] md:h-[320px] rounded-2xl overflow-hidden relative border border-slate-200 shadow-sm group desktop-map-container">
                
                <!-- Desktop Info Card Overlay removed to prevent overlap with native map bubble -->

                <!-- Full Overlay Link (Hidden on Desktop) -->
                <a href="https://www.google.com/maps/search/?api=1&query=บริษัท+เวบปาค+จำกัด+525/89+ซอยลาดพร้าว+126" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   title="เปิดใน Google Maps"
                   class="absolute inset-0 z-20 flex items-center justify-center bg-black/5 hover:bg-black/15 transition-all duration-300 desktop-map-overlay-hide">
                    <span class="bg-white/95 text-slate-800 text-xs md:text-sm font-bold px-4 py-2.5 rounded-full shadow-lg border border-slate-200 flex items-center gap-2 backdrop-blur-sm group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <?= e(getCurrentLang() === 'th' ? 'แตะเพื่อเปิดปักหมุดบน Google Maps ↗' : 'Tap to Open Google Maps Pin ↗') ?>
                    </span>
                </a>

                <!-- Embedded Background Map Preview -->
                <iframe style="pointer-events: none;" class="w-full h-full border-0 relative z-0 desktop-map-iframe" src="https://maps.google.com/maps?q=บริษัท%20เวบปาค%20จำกัด%20525/89%20ซอยลาดพร้าว%20126&t=&z=16&ie=UTF8&iwloc=&output=embed" loading="lazy"></iframe>
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
