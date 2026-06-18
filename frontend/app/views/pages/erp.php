<?php
declare(strict_types=1);

// ดึงข้อมูลโมดูล ERP และฟีเจอร์ย่อยจากฐานข้อมูล
try {
    $stmt = db()->prepare("SELECT * FROM erp_modules WHERE is_active = 1 ORDER BY sort_order ASC");
    $stmt->execute();
    $dbModules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $modulesData = [];
    foreach ($dbModules as $mod) {
        // ดึงรายละเอียดจาก erp_modules.description เท่านั้น (กรณีลบตารางย่อยแล้ว)
        $mod['items'] = [];
        $modulesData[] = $mod;
    }

} catch (Exception $e) {
    $modulesData = [];
}

// ดึงข้อมูลผลงานที่เป็นระบบ ERP (category_id = 5 หรือคำค้นหา ERP)
try {
    $pStmt = db()->prepare("SELECT * FROM portfolio WHERE status = 'published' AND (category_id = 5 OR tech_stack LIKE '%ERP%') ORDER BY created_at DESC LIMIT 3");
    $pStmt->execute();
    $erpPortfolios = $pStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $erpPortfolios = [];
}
?><style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-7.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
                <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>

    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-3xl">
                <div class="animate-fade-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">ERP SYSTEM</span>
                </div>

                <h1 class="animate-fade-up delay-200 text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
                    <span class="bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block mb-1">บริหารจัดการธุรกิจ</span><br>
                    <span class="bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block" style="animation-delay: -3s;">ระบบ ERP</span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    ระบบ Enterprise Resource Planning <br>อัจฉริยะที่เชื่อมต่อทุกกระบวนการธุรกิจ <br> แบบครบวงจรในแพลตฟอร์มเดียว
                </p>

                <div class="animate-fade-up delay-400 flex flex-wrap items-center gap-4">
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาผู้เชี่ยวชาญ
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
                        <span class="text-slate-800 text-lg font-semibold transition-colors duration-300 group-hover:text-primary">
                            ดูวิดีโอแนะนำ
                        </span>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>

<section class="bg-white py-20 lg:py-28 font-sans border-b border-slate-50">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-4 bg-[#f8fafc] rounded-3xl p-8 border border-slate-100 text-center lg:text-left transition-all duration-500 hover:shadow-md hover:border-blue-100 hover:bg-white relative overflow-hidden group cursor-default">
                <div class="relative z-10">
<span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        ระบบ ERP
    </span>                    <h2 class="text-3xl font-extrabold text-dark mt-4 mb-4 transition-colors duration-500 group-hover:text-primary">ระบบ ERP คืออะไร ?</h2>
                    <p class="text-slate-500 text-sm md:text-base leading-relaxed font-medium transition-colors duration-500 group-hover:text-slate-600">
                        ERP คือ ระบบที่รวบรวมและเชื่อมโยงกระบวนการทำงานหลักขององค์กร ไม่ว่าจะเป็นการขาย การจัดซื้อ คลังสินค้า บัญชีการเงิน และทรัพยากรบุคคล เข้าด้วยกันบนฐานข้อมูลเดียว ช่วยให้ผู้บริหารมองเห็นภาพรวมของธุรกิจได้แบบเรียลไทม์ และตอบสนองต่อการเปลี่ยนแปลงได้รวดเร็ว
                    </p>
                </div>
            </div>

            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start transition-all duration-500 ease-out hover:-translate-y-1.5 hover:shadow-[0_15px_40px_-15px_rgba(0,0,0,0.1)] hover:border-blue-100 group cursor-pointer relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-out"></div>
                    <div class="relative z-10 w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0 transition-all duration-500 ease-out group-hover:scale-110 group-hover:bg-primary group-hover:text-white group-hover:shadow-md group-hover:-rotate-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-dark mb-1 text-[15px] transition-colors duration-300 group-hover:text-primary">ข้อมูลเชื่อมโยง ครบทุกแผนก</h4>
                        <p class="text-slate-500 text-md leading-relaxed transition-colors duration-300 group-hover:text-slate-500">ข้อมูลเป็นหนึ่งเดียว ไม่ต้องทำซ้ำซ้อน</p>
                    </div>
                </div>

                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start transition-all duration-500 ease-out hover:-translate-y-1.5 hover:shadow-[0_15px_40px_-15px_rgba(0,0,0,0.1)] hover:border-blue-100 group cursor-pointer relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-out"></div>
                    <div class="relative z-10 w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0 transition-all duration-500 ease-out group-hover:scale-110 group-hover:bg-primary group-hover:text-white group-hover:shadow-md group-hover:-rotate-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-dark mb-1 text-[15px] transition-colors duration-300 group-hover:text-primary">ทำงานอัตโนมัติ ลดความผิดพลาด</h4>
                        <p class="text-slate-500 text-md leading-relaxed transition-colors duration-300 group-hover:text-slate-500">ลดขั้นตอนงานเอกสาร เพิ่มความแม่นยำ</p>
                    </div>
                </div>

                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start transition-all duration-500 ease-out hover:-translate-y-1.5 hover:shadow-[0_15px_40px_-15px_rgba(0,0,0,0.1)] hover:border-blue-100 group cursor-pointer relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-out"></div>
                    <div class="relative z-10 w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0 transition-all duration-500 ease-out group-hover:scale-110 group-hover:bg-primary group-hover:text-white group-hover:shadow-md group-hover:-rotate-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-dark mb-1 text-[15px] transition-colors duration-300 group-hover:text-primary">มองเห็นภาพรวม ตัดสินใจได้ไว</h4>
                        <p class="text-slate-500 text-md leading-relaxed transition-colors duration-300 group-hover:text-slate-500">รายงานและ Dashboard อัพเดทตลอดเวลา</p>
                    </div>
                </div>

                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start transition-all duration-500 ease-out hover:-translate-y-1.5 hover:shadow-[0_15px_40px_-15px_rgba(0,0,0,0.1)] hover:border-blue-100 group cursor-pointer relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-out"></div>
                    <div class="relative z-10 w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0 transition-all duration-500 ease-out group-hover:scale-110 group-hover:bg-primary group-hover:text-white group-hover:shadow-md group-hover:-rotate-3">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-dark mb-1 text-[15px] transition-colors duration-300 group-hover:text-primary">รองรับการเติบโตของธุรกิจ</h4>
                        <p class="text-slate-500 text-md leading-relaxed transition-colors duration-300 group-hover:text-slate-500">ขยายโมดูลและความต้องการเพิ่มได้ในอนาคต</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="bg-[#f8fafc] py-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="mb-14 text-center">
<span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        โมดูล ERP
    </span>            <h2 class="text-2xl md:text-3xl font-extrabold text-dark">ครบทุก <span class="text-primary">โมดูล</span> ตอบโจทย์ทุกการทำงานขององค์กร</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 lg:gap-8">
            <?php foreach ($modulesData as $mod): ?>
                
                <div class="bg-white border border-slate-100 p-2 lg:p-4 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] transition-all duration-300 ease-in-out hover:-translate-y-1.5 hover:shadow-xl hover:border-blue-100 active:scale-[0.98] cursor-pointer flex flex-col justify-between group">
                    <div class="p-6"> 
                        <div class="flex items-center gap-5">
                            
                            <div class="w-12 h-12 rounded-xl bg-blue-50/50 border border-blue-100/40 text-blue-600 flex items-center justify-center shrink-0 transition-all duration-300 ease-in-out group-hover:scale-110 group-hover:bg-blue-100/50">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= e($mod['icon_svg'] ?? 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z') ?>" />
                                </svg>
                            </div>
                            
                            <div class="flex flex-col justify-center">
<h3 class="text-[15px] font-extrabold text-slate-900 tracking-wide m-0 mb-1 transition-colors duration-300 ease-in-out group-hover:text-blue-600">
                                    <?= e($mod['title']) ?>
                                </h3>
                                <p class="text-slate-500 text-md leading-relaxed m-0">
                                    <?= e($mod['description']) ?>
                                </p>
                            </div>
                            
                        </div>

                        <?php if (!empty($mod['items'])): ?>
                            <ul class="space-y-2.5 border-t border-slate-100 pt-4 mt-5 ml-[68px]"> 
                                <?php foreach ($mod['items'] as $feat): ?>
                                    <li class="flex items-start gap-2.5 text-slate-600 text-[13px] font-medium">
                                        <span class="text-blue-600 text-sm leading-none mt-0.5">•</span>
                                        <span class="leading-normal"><?= e($feat) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-white py-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-3">
            <div>
<span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        ผลงานของเรา
    </span>                <h2 class="text-2xl md:text-3xl font-extrabold text-dark">ผลงานพัฒนาระบบ ERP</h2>
            </div>
            <a href="<?= e(route_url('/portfolio')) ?>" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-blue-700 text-white text-xs font-bold rounded-full transition-all shadow-sm">
                ดูทั้งหมด
            </a>
        </div>
    <p class="mb-3 text-sm md:text-base leading-relaxed text-slate-600 max-w-3xl">
        รวมผลงานที่เราช่วยออกแบบและพัฒนาโซลูชันดิจิทัล<br>ที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน
    </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <?php if (!empty($erpPortfolios)): ?>
                <?php foreach ($erpPortfolios as $portfolio): ?>
                    <?php
                        $cardTitle = $portfolio['meta_title'] ?? $portfolio['client_name'] ?? 'ERP Project';
                        $portImgPath = $portfolio['image_path'] ?? '';
                        $image = asset_url('images/story.png');
                        if ($portImgPath !== '') {
                            $portFullUrl = asset_url($portImgPath);
                            $portParsed = parse_url($portFullUrl, PHP_URL_PATH);
                            $portFilePath = $_SERVER['DOCUMENT_ROOT'] . $portParsed;
                            $image = file_exists($portFilePath) ? $portFullUrl : asset_url('images/story.png');
                        }
                        $desc = $portfolio['meta_description'] ?? strip_tags($portfolio['description'] ?? '');
                    ?>
                    <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="relative aspect-video overflow-hidden bg-slate-50">
                                <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="<?= e($image) ?>" alt="<?= e($cardTitle) ?>">

                                <span class="absolute top-4 left-4 bg-white/90 text-primary text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-sm border border-slate-100">ERP Implementation</span>
                            </div>

                            <div class="p-6 lg:p-8">
                                <p class="text-xs font-bold text-slate-400 mb-2 uppercase tracking-wide"><?= e($portfolio['client_name'] ?? 'Client') ?></p>
                                <h3 class="text-lg font-bold text-dark mb-3 group-hover:text-primary transition-colors line-clamp-1"><?= e($cardTitle) ?></h3>

                                <p class="text-md md:text-sm text-slate-500 font-medium leading-relaxed line-clamp-3 mb-4"><?= e(mb_strimwidth($desc, 0, 140, '...')) ?></p>
                            </div>
                        </div>

                            <div class="flex justify-between items-center border-t border-slate-50 py-4 px-6 lg:px-8 bg-slate-50/50">
                                <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400">ERP Case Study</span>
                                <a href="<?= e(route_url('/portfolio', ['id' => $portfolio['id']])) ?>" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                    อ่านต่อ <span>›</span>
                                </a>
                            </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-12 text-slate-400 text-sm border border-dashed border-slate-200 rounded-3xl bg-slate-50/50">
                    ยังไม่มีข้อมูลผลงานพัฒนา ERP ในขณะนี้
                </div>
            <?php endif; ?>
            </div>

    </div>
</section>
<style>
    /* 1. Aurora Background Flow */
    @keyframes aurora-bg {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-aurora {
        background: linear-gradient(-45deg, #f4f9ff, #eef5ff, #e6f0ff, #f4f9ff);
        background-size: 300% 300%;
        animation: aurora-bg 10s ease infinite;
    }

    /* 2. Glass Shimmer Sweep (แสงวาบผ่านการ์ด) */
    @keyframes shimmer-sweep {
        0% { transform: translateX(-150%) skewX(-20deg); }
        100% { transform: translateX(200%) skewX(-20deg); }
    }
    .shimmer-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 60%; height: 100%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%);
        transform: translateX(-150%) skewX(-20deg);
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .group:hover .shimmer-card::before {
        opacity: 1;
        animation: shimmer-sweep 1.2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    /* 3. Text Gradient Flow (ตัวอักษรสีไหล) */
    @keyframes text-flow {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }
    .animate-text-flow {
        background-size: 200% auto;
        animation: text-flow 2.5s linear infinite;
    }
</style>

<section class="bg-white py-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <div class="animate-aurora border border-blue-50/50 rounded-[2rem] py-10 px-6 md:p-12 relative shadow-[inset_0_0_60px_rgba(255,255,255,0.8)]">
            
            <span class="text-center text-xs font-bold uppercase tracking-widest text-primary block mb-1">ผลลัพธ์ทางธุรกิจ</span>
            
            <h2 class="text-xl md:text-2xl font-bold text-dark text-center mb-10 tracking-wide transition-all duration-500 ">
                ERP ที่ช่วยยกระดับธุรกิจของคุณ
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-5">
    
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.01)] text-center flex flex-col items-center justify-center group relative overflow-hidden shimmer-card transition-all duration-500 ease-out hover:scale-[1.04] hover:shadow-[0_5px_20px_rgba(59,130,246,0.08)] hover:border-blue-300 active:scale-[0.96] cursor-pointer">
        <div class="relative z-10 w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_4px_10px_rgba(37,99,235,0.2)] group-hover:scale-110">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </div>
        <h3 class="relative z-10 font-bold text-dark text-[14px] md:text-base mb-2 transition-all duration-300 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 group-hover:animate-text-flow">ข้อมูลครบถ้วน</h3>
        <p class="relative z-10 text-slate-400 text-xs md:text-[13px] leading-relaxed transition-colors duration-300 group-hover:text-slate-600">เชื่อมต่อทุกแผนก<br>ในระบบเดียว</p>
    </div>

    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.01)] text-center flex flex-col items-center justify-center group relative overflow-hidden shimmer-card transition-all duration-500 ease-out hover:scale-[1.04] hover:shadow-[0_5px_20px_rgba(59,130,246,0.08)] hover:border-blue-300 active:scale-[0.96] cursor-pointer">
        <div class="relative z-10 w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_4px_10px_rgba(37,99,235,0.2)] group-hover:scale-110">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.456-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.456-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
            </svg>
        </div>
        <h3 class="relative z-10 font-bold text-dark text-[14px] md:text-base mb-2 transition-all duration-300 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 group-hover:animate-text-flow">ลดงานซ้ำซ้อน</h3>
        <p class="relative z-10 text-slate-400 text-xs md:text-[13px] leading-relaxed transition-colors duration-300 group-hover:text-slate-600">เพิ่มประสิทธิภาพ<br>การทำงาน</p>
    </div>

    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.01)] text-center flex flex-col items-center justify-center group relative overflow-hidden shimmer-card transition-all duration-500 ease-out hover:scale-[1.04] hover:shadow-[0_5px_20px_rgba(59,130,246,0.08)] hover:border-blue-300 active:scale-[0.96] cursor-pointer">
        <div class="relative z-10 w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_4px_10px_rgba(37,99,235,0.2)] group-hover:scale-110">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <h3 class="relative z-10 font-bold text-dark text-[14px] md:text-base mb-2 transition-all duration-300 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 group-hover:animate-text-flow">ข้อมูลเรียลไทม์</h3>
        <p class="relative z-10 text-slate-400 text-xs md:text-[13px] leading-relaxed transition-colors duration-300 group-hover:text-slate-600">ตัดสินใจได้แม่นยำ<br>และรวดเร็ว</p>
    </div>

    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.01)] text-center flex flex-col items-center justify-center group relative overflow-hidden shimmer-card transition-all duration-500 ease-out hover:scale-[1.04] hover:shadow-[0_5px_20px_rgba(59,130,246,0.08)] hover:border-blue-300 active:scale-[0.96] cursor-pointer">
        <div class="relative z-10 w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_4px_10px_rgba(37,99,235,0.2)] group-hover:scale-110">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
        </div>
        <h3 class="relative z-10 font-bold text-dark text-[14px] md:text-base mb-2 transition-all duration-300 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 group-hover:animate-text-flow">ควบคุมความเสี่ยง</h3>
        <p class="relative z-10 text-slate-400 text-xs md:text-[13px] leading-relaxed transition-colors duration-300 group-hover:text-slate-600">ตรวจสอบและติดตาม<br>ได้ทุกขั้นตอน</p>
    </div>

    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.01)] text-center flex flex-col items-center justify-center group relative overflow-hidden shimmer-card transition-all duration-500 ease-out hover:scale-[1.04] hover:shadow-[0_5px_20px_rgba(59,130,246,0.08)] hover:border-blue-300 active:scale-[0.96] cursor-pointer">
        <div class="relative z-10 w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-0 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_4px_10px_rgba(37,99,235,0.2)] group-hover:scale-110">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
        <h3 class="relative z-10 font-bold text-dark text-[14px] md:text-base mb-2 transition-all duration-300 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-cyan-500 group-hover:animate-text-flow">ขยายได้ตามธุรกิจ</h3>
        <p class="relative z-10 text-slate-400 text-xs md:text-[13px] leading-relaxed transition-colors duration-300 group-hover:text-slate-600">รองรับการเติบโต<br>ในอนาคต</p>
    </div>

</div>
        </div>

    </div>
</section>