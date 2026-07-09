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
</style>

<section class="relative overflow-hidden font-sans bg-[#f7faff]">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <div class="animate-fade-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">OUR CONTACT</span>
                </div>

                <h1 class="animate-fade-up delay-200 text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
                    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent inline-block py-1">ติดต่อเรา</span><br>
                    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent inline-block py-1">WEBPARK</span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    <!-- Mobile Text -->
                    <span class="block md:hidden">
                        พูดคุยและปรึกษาเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์<br>
                        ERP / ERM และโซลูชันดิจิทัลเพื่อธุรกิจของคุณ
                    </span>
                    <!-- Desktop Text -->
                    <span class="hidden md:block">
                        พูดคุยและปรึกษาเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์ ERP / ERM <br>และโซลูชันดิจิทัลเพื่อธุรกิจของคุณ
                    </span>
                </p>

                <div class="animate-fade-up delay-400 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <!-- Mobile Buttons -->
                    <a href="<?= e(route_url('/services')) ?>" class="md:hidden inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ดูบริการของเรา
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#" class="md:hidden inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors">ดูวิดีโอแนะนำ</span>
                    </a>

                    <!-- Desktop Buttons -->
                    <a href="#contact-section" class="hidden md:inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาโปรเจกต์
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#company-info" class="hidden md:inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-slate-700 text-sm font-semibold rounded-full hover:bg-slate-50 transition-all shadow-sm border border-slate-200 hover:-translate-y-0.5">
                        ดูข้อมูลติดต่อ
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

<section id="contact-section" class="bg-white py-16 lg:py-24 font-sans">
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
            <div class="lg:col-span-6 bg-white border border-slate-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] rounded-[2.5rem] p-6 md:p-10 animate-fade-in-up flex flex-col align-with-right-cards">
                <h2 class="text-xl md:text-2xl font-bold text-dark mb-6">
                    ส่งข้อความถึงเรา
                </h2>

                <?php if ($submitted): ?>
                    <div class="text-center py-16 flex flex-col items-center justify-center flex-grow">
                        <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">ขอบคุณสำหรับข้อความ</h3>
                        <p class="text-slate-500 text-sm font-medium">ทีมของเราได้รับข้อมูลเรียบร้อยแล้ว และจะติดต่อกลับภายใน 24 ชั่วโมง</p>
                    </div>
                <?php else: ?>
                    <style>
                        .custom-placeholder::placeholder {
                            color: #022862 !important;
                            opacity: 0.9;
                        }
                    </style>
                    <form method="post" class="flex flex-col flex-grow space-y-4">
                        <!-- Desktop Name Field -->
                        <div id="contact_desktop-name-wrapper" class="hidden lg:block">
                            <input type="text" id="contact_name_desktop" name="name" placeholder="ชื่อ - นามสกุล" value="<?= e($form['name'] ?? '') ?>" required maxlength="100"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                        </div>

                        <!-- Mobile Name Fields -->
                        <div id="contact_mobile-name-wrapper" class="grid grid-cols-1 gap-4 lg:hidden">
                            <input type="text" id="contact_name_mobile_first" name="firstname" placeholder="ชื่อ" value="<?= e($form['firstname'] ?? '') ?>" required maxlength="50"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                            <input type="text" id="contact_name_mobile_last" name="lastname" placeholder="นามสกุล" value="<?= e($form['lastname'] ?? '') ?>" required maxlength="50"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
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
                            <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" value="<?= e($form['phone'] ?? '') ?>" required maxlength="40"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">

                            <input type="email" name="email" placeholder="อีเมล" value="<?= e($form['email'] ?? '') ?>" required maxlength="255"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary focus:shadow-inner">
                        </div>

                        <div>
                            <textarea name="message" placeholder="รายละเอียด" required maxlength="1000" rows="5"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition-all duration-300 custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none focus:shadow-inner"><?= e($form['message'] ?? '') ?></textarea>
                        </div>

                        <div class="flex items-start gap-3 pt-2">
                            <input type="checkbox" id="privacy" name="privacy_agreed" required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all duration-200">
                            <label for="privacy" class="text-xs md:text-[13px] text-slate-500 leading-relaxed cursor-pointer select-none">
                                ฉันยินยอมตาม <a href="#" class="text-primary hover:underline transition-colors duration-200">นโยบายความเป็นส่วนตัว</a> และข้อกำหนดและเงื่อนไขของเว็บไซต์
                            </label>
                        </div>

                        <?php if ($errors !== []): ?>
                            <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                        <?php endif; ?>

                        <div class="pt-2 mt-auto flex justify-start">
                            <button type="submit" class="px-8 py-3.5 bg-primary hover:bg-blue-700 text-white font-bold text-sm rounded-full shadow-md shadow-blue-500/10 transition-all duration-300 cursor-pointer hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                ส่งข้อความ
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div id="company-info" class="lg:col-span-6 space-y-4 animate-fade-in-up animation-delay-200">
                <h2 class="text-xl md:text-2xl font-bold text-dark mb-6">ข้อมูลบริษัท</h2>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-center gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div><h4 class="font-bold text-dark text-[15px]">บริษัท เวบปาค จำกัด</h4></div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">ที่อยู่</h4>
                        <p class="text-dark text-[13px] md:text-sm font-medium leading-relaxed">525/89 ซอยลาดพร้าว 126 (กรัณฑ์พร) แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">เบอร์โทรศัพท์</h4>
                        <p class="text-dark text-[13px] md:text-sm font-semibold tracking-wide">095-539-2666</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">อีเมล</h4>
                        <p class="text-dark text-[13px] md:text-sm font-semibold">oraphan@webpark.co.th</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4 transition-all duration-300 hover:shadow-md hover:-translate-y-1 hover:border-blue-50">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">เวลาทำการ</h4>
                        <p class="text-dark text-[13px] md:text-sm font-medium">วันจันทร์ - วันศุกร์ เวลา 09.00 น. - 18.00 น.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 bg-[#f0f4fa] border border-blue-100 rounded-2xl py-6 px-4 md:px-8 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 text-center sm:text-left transition-all duration-300">
            <div class="text-primary shrink-0">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M5.636 5.636a9 9 0 000 12.728m0 0l2.829-2.829m-2.829 2.829L3 21M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-primary text-[16px] mb-1">ตอบกลับภายใน 24 ชม. (ในเวลาทำการ)</h4>
                <p class="text-slate-500 text-[13px] md:text-[14px]">ทีมงานของเราพร้อมให้คำปรึกษาและดูแลธุรกิจของคุณอย่างเต็มที่</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white pb-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-[#f4f9ff] border border-blue-50/50 rounded-[2rem] p-6 md:p-8">
            
            <div class="flex items-start gap-4 p-4">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                <div>
                    <h4 class="font-bold text-dark text-[15px] mb-1">ตอบกลับรวดเร็ว</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">เราตอบกลับทุกข้อความและพร้อมดูแล<br>ช่วยเหลือปัญหาภายใน 24 ชั่วโมง</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 border-y md:border-y-0 md:border-x border-blue-100/50">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <div>
                    <h4 class="font-bold text-dark text-[15px] mb-1">ให้คำปรึกษาโดยผู้เชี่ยวชาญ</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">ทีมงานมืออาชีพที่มีประสบการณ์ตรง<br>พร้อมวิเคราะห์และวางแผนให้ธุรกิจคุณ</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <div>
                    <h4 class="font-bold text-dark text-[15px] mb-1">ดูแลครบทั้งระบบและเว็บไซต์</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">บริการครอบคลุมครบวงจร ตั้งแต่ระบบ ERP/ERM เว็บไซต์และโซลูชันดิจิทัล</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-white pb-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <h2 class="text-xl md:text-2xl font-bold text-dark mb-8 relative inline-block">
            ที่ตั้งของเรา
            <span class="absolute left-0 bottom-[-8px] w-10 h-1 bg-primary rounded-full"></span>
        </h2>

        <div class="w-full h-[400px] md:h-[500px] rounded-[2.5rem] overflow-hidden relative border border-slate-100 shadow-sm">
            <iframe class="w-full h-full border-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.311743152067!2d100.6172557!3d13.7901399!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d62445b2b2b11%3A0x67ee1c03c2a9bb4c!2sWEBPARK!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth" allowfullscreen="" loading="lazy"></iframe>

            <div class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 max-w-sm z-10 hidden sm:block">
                <div class="flex items-center gap-2 text-primary font-bold text-sm mb-3">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    บริษัท เวบปาค จำกัด
                </div>
                <p class="text-slate-500 text-xs md:text-[13px] leading-relaxed font-medium mb-4">
                    525/89 ซอยลาดพร้าว 126 (กรัณฑ์พร) แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310
                </p>
                <a href="https://maps.google.com" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                    ดูเส้นทาง <span>→</span>
                </a>
            </div>
        </div>
    </div>
</section>