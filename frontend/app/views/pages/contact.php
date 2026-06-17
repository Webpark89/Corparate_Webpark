<?php
declare(strict_types=1);

$errors = $errors ?? [];
$submitted = $submitted ?? false;
$form = $form ?? [];
?>

<section class="relative overflow-hidden font-sans bg-[#f7faff]">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-30 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-200 bg-white mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">OUR CONTACT</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-[4.5rem] font-bold text-dark leading-[1.1] mb-2 tracking-tight">
                    ติดต่อกับ<br>
                    <span class="text-primary">WEBPARK</span>
                </h1>

                <p class="mt-6 text-slate-500 text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    พูดคุยและปรึกษาเกี่ยวกับโปรเจกต์ ระบบ เว็บไซต์ ERP / ERM และโซลูชันดิจิทัล เพื่อธุรกิจของคุณ
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="#contact-section" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาโปรเจกต์
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#company-info" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-slate-700 text-sm font-semibold rounded-full hover:bg-slate-50 transition-all shadow-sm border border-slate-200 hover:-translate-y-0.5">
                        ดูข้อมูลติดต่อ
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="relative w-full h-full hidden lg:flex justify-end">
                <img src="<?= e(asset_url('images/bg-3.png')) ?>" alt="WEBPARK Solutions Centerpiece" class="w-full max-w-[400px] object-contain drop-shadow-2xl">
            </div>
            
        </div>
    </div>
</section>

<section id="contact-section" class="bg-white py-16 lg:py-24 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">
            
            <div class="lg:col-span-6 bg-white border border-slate-100 shadow-[0_4px_30px_rgba(0,0,0,0.02)] rounded-[2.5rem] p-6 md:p-10">
                <h2 class="text-xl md:text-2xl font-bold text-dark mb-6">
                    ส่งข้อความถึงเรา
                </h2>

                <?php if ($submitted): ?>
                    <div class="text-center py-16">
                        <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2">ขอบคุณสำหรับข้อความ</h3>
                        <p class="text-slate-500 text-sm font-medium">ทีมของเราได้รับข้อมูลเรียบร้อยแล้ว และจะติดต่อกลับภายใน 24 ชั่วโมง</p>
                    </div>
                <?php else: ?>
                    <form method="post" class="space-y-4">
                        <div>
                            <input type="text" name="name" placeholder="ชื่อ - นามสกุล" value="<?= e($form['name'] ?? '') ?>" required maxlength="100"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        
                        <div>
                            <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" value="<?= e($form['phone'] ?? '') ?>" required maxlength="40"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>

                        <div>
                            <input type="email" name="email" placeholder="อีเมล" value="<?= e($form['email'] ?? '') ?>" required maxlength="255"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>

                        <div>
                            <input type="text" name="subject" placeholder="หัวข้อ" value="<?= e($form['subject'] ?? '') ?>" maxlength="200"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>

                        <div>
                            <textarea name="message" placeholder="รายละเอียด" required rows="4" maxlength="1000"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:ring-1 focus:ring-primary resize-none"><?= e($form['message'] ?? '') ?></textarea>
                        </div>

                        <div class="flex items-start gap-3 pt-1">
                            <input type="checkbox" id="privacy" name="privacy_agreed" required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer">
                            <label for="privacy" class="text-xs md:text-[13px] text-slate-500 leading-relaxed cursor-pointer select-none">
                                ฉันยินยอมตาม <a href="#" class="text-primary hover:underline">นโยบายความเป็นส่วนตัว</a> และข้อกำหนดและเงื่อนไขของเว็บไซต์
                            </label>
                        </div>

                        <?php if ($errors !== []): ?>
                            <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                        <?php endif; ?>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-primary hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/10 transition-all cursor-pointer">
                                ส่งข้อความ
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div id="company-info" class="lg:col-span-6 space-y-4">
                <h2 class="text-xl md:text-2xl font-bold text-dark mb-6">ข้อมูลบริษัท</h2>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div><h4 class="font-bold text-dark text-[15px]">บริษัท เว็บปาร์ค จำกัด</h4></div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">ที่อยู่</h4>
                        <p class="text-dark text-[13px] md:text-sm font-medium leading-relaxed">525/89 ซอยลาดพร้าว 126 (กรัณฑ์พร) แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">เบอร์โทรศัพท์</h4>
                        <p class="text-dark text-[13px] md:text-sm font-semibold tracking-wide">095-539-2666, 02-075-9855</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">อีเมล</h4>
                        <p class="text-dark text-[13px] md:text-sm font-semibold">oraphan@WEBPARK.co.th</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-11 h-11 bg-blue-50 text-primary rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-400 text-xs uppercase mb-0.5">เวลาทำการ</h4>
                        <p class="text-dark text-[13px] md:text-sm font-medium">วันจันทร์ - วันศุกร์ / 09.00 น. - 18.00 น.</p>
                    </div>
                </div>

                <div class="bg-blue-50/60 border border-blue-100/40 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-11 h-11 bg-primary text-white rounded-xl flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-[14px] mb-0.5">ตอบกลับภายใน 24 ชั่วโมง</h4>
                        <p class="text-slate-500 text-xs md:text-[13px] leading-relaxed">ทีมงานของเราพร้อมให้คำปรึกษาและดูแลธุรกิจของคุณอย่างเต็มที่</p>
                    </div>
                </div>

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
                    <p class="text-slate-400 text-xs leading-relaxed">เราตอบกลับทุกข้อความและพร้อมดูแลช่วยเหลือปัญหาภายใน 24 ชั่วโมง</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 border-y md:border-y-0 md:border-x border-blue-100/50">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <div>
                    <h4 class="font-bold text-dark text-[15px] mb-1">ให้คำปรึกษาโดยผู้เชี่ยวชาญ</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">ทีมงานมืออาชีพที่มีประสบการณ์ตรง พร้อมวิเคราะห์และวางแผนให้ธุรกิจคุณ</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4">
                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shrink-0 shadow-sm"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <div>
                    <h4 class="font-bold text-dark text-[15px] mb-1">ดูแลครบทั้งระบบและเว็บไซต์</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">บริการครอบคลุมครบวงจร ตั้งแต่ระบบ ERP/ERM เว็บไซต์ และโซลูชันดิจิทัล</p>
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
            <iframe class="w-full h-full border-0 grayscale" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.311743152067!2d100.6172557!3d13.7901399!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d62445b2b2b11%3A0x67ee1c03c2a9bb4c!2sWEBPARK!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth" allowfullscreen="" loading="lazy"></iframe>

            <div class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 bg-white rounded-3xl p-6 shadow-xl border border-slate-100 max-w-sm z-10 hidden sm:block">
                <div class="flex items-center gap-2 text-primary font-bold text-sm mb-3">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    บริษัท เว็บปาร์ค จำกัด
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