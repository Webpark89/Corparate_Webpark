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
?>
<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-30 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-200 bg-white mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">ERP SYSTEM</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-[4.5rem] font-bold text-dark leading-[1.1] mb-2 tracking-tight">
                    บริหารจัดการธุรกิจ<br>
                    <span class="text-primary">ระบบ ERP</span>
                </h1>

                <p class="mt-6 text-slate-700 text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                ระบบ Enterprise Resource Planning <br>อัจฉริยะที่เชื่อมต่อทุกกระบวนการธุรกิจ <br> แบบครบวงจรในแพลตฟอร์มเดียว
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาผู้เชี่ยวชาญ
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#erp" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-slate-700 text-sm font-semibold rounded-full hover:bg-slate-50 transition-all shadow-sm border border-slate-200 hover:-translate-y-0.5">
                        ERP
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="relative w-full h-full flex justify-center lg:justify-end">
                <img src="<?= e(asset_url('images/bg-3.png')) ?>" alt="WEBPARK Solutions Centerpiece" class="w-full max-w-[400px] object-contain drop-shadow-2xl">
            </div>
            
        </div>
    </div>
</section>

<section class="bg-white py-20 lg:py-28 font-sans border-b border-slate-50">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-4 bg-[#f8fafc] rounded-3xl p-8 border border-slate-100 text-center lg:text-left shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-primary bg-blue-50 px-3 py-1 rounded-full">ERP SYSTEM</span>
                <h2 class="text-3xl font-extrabold text-dark mt-4 mb-4">ระบบ ERP คืออะไร ?</h2>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed font-medium">
                    ERP คือ ระบบที่รวบรวมและเชื่อมโยงกระบวนการทำงานหลักขององค์กร ไม่ว่าจะเป็นการขาย การจัดซื้อ คลังสินค้า บัญชีการเงิน และทรัพยากรบุคคล เข้าด้วยกันบนฐานข้อมูลเดียว ช่วยให้ผู้บริหารมองเห็นภาพรวมของธุรกิจได้แบบเรียลไทม์ และตอบสนองต่อการเปลี่ยนแปลงได้รวดเร็ว
                </p>
            </div>

            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></div>
                    <div><h4 class="font-bold text-dark mb-1 text-[15px]">ข้อมูลเชื่อมโยง ครบทุกแผนก</h4><p class="text-slate-400 text-xs leading-relaxed">ข้อมูลเป็นหนึ่งเดียว ไม่ต้องทำซ้ำซ้อน</p></div>
                </div>
                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                    <div><h4 class="font-bold text-dark mb-1 text-[15px]">ทำงานอัตโนมัติ ลดความผิดพลาด</h4><p class="text-slate-400 text-xs leading-relaxed">ลดขั้นตอนงานเอกสาร เพิ่มความแม่นยำ</p></div>
                </div>
                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg></div>
                    <div><h4 class="font-bold text-dark mb-1 text-[15px]">มองเห็นภาพรวม ตัดสินใจได้ไว</h4><p class="text-slate-400 text-xs leading-relaxed">รายงานและ Dashboard อัพเดทตลอดเวลา</p></div>
                </div>
                <div class="p-6 bg-white border border-slate-100 shadow-sm rounded-2xl flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
                    <div><h4 class="font-bold text-dark mb-1 text-[15px]">รองรับการเติบโต ของธุรกิจ</h4><p class="text-slate-400 text-xs leading-relaxed">ขยายโมดูลและความต้องการเพิ่มได้ในอนาคต</p></div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-[#f8fafc] py-20 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="mb-14 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-primary block mb-2">ERP MODULE</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-dark">ครบทุกโมดูล ตอบโจทย์ทุกการทำงานขององค์กร</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 lg:gap-8">
            <?php foreach ($modulesData as $mod): ?>
                <div class="bg-white border border-slate-100 p-6 lg:p-8 rounded-3xl shadow-[0_4px_25px_rgba(0,0,0,0.02)] hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50/50 border border-blue-100/40 text-primary flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= e($mod['icon_svg'] ?? 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4') ?>" />
                                </svg>
                            </div>
                            <h3 class="text-[15px] font-extrabold text-dark tracking-wide uppercase">
                                <?= e($mod['title']) ?>
                            </h3>
                        </div>
                        
                        <p class="text-slate-400 text-xs leading-relaxed mb-6">
                            <?= e($mod['description']) ?>
                        </p>

                        <?php if (!empty($mod['items'])): ?>
                            <ul class="space-y-2.5 border-t border-slate-50 pt-5">
                                <?php foreach ($mod['items'] as $feat): ?>
                                    <li class="flex items-start gap-2.5 text-slate-600 text-[13px] font-medium">
                                        <span class="text-primary text-sm leading-none mt-0.5">•</span>
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

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-primary block mb-1">SUCCESS STORIES</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-dark">ผลงานพัฒนาและวางระบบ ERP</h2>
            </div>
            <a href="<?= e(route_url('/portfolio')) ?>" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary hover:bg-blue-700 text-white text-xs font-bold rounded-full transition-all shadow-sm">
                ดูทั้งหมด
            </a>
        </div>

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

                                <p class="text-xs md:text-[13px] text-slate-500 font-medium leading-relaxed line-clamp-3 mb-4"><?= e(mb_strimwidth($desc, 0, 140, '...')) ?></p>
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

<section class="bg-white py-16 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <div class="bg-[#f4f9ff] border border-blue-50/50 rounded-[2rem] py-10 px-6 md:p-12">
            <span class="text-center text-xs font-bold uppercase tracking-widest text-primary block mb-1">SUCCESS STORIES</span>

            
            <h2 class="text-xl md:text-2xl font-bold text-dark text-center mb-10 tracking-wide">
                ERP ที่ช่วยยกระดับธุรกิจของคุณ
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-5">
                
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-center flex flex-col items-center justify-center group hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 group-hover:scale-105 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-dark text-[14px] md:text-base mb-2">ข้อมูลครบถ้วน</h3>
                    <p class="text-slate-400 text-xs md:text-[13px] leading-relaxed">เชื่อมต่อทุกแผนก<br>ในระบบเดียว</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-center flex flex-col items-center justify-center group hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 group-hover:scale-105 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H17m-3.337-1c-.413-.236-.856-.43-1.328-.574m-2.67 0a8.16 8.16 0 00-1.328.574" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-dark text-[14px] md:text-base mb-2">ลดงานซ้ำซ้อน</h3>
                    <p class="text-slate-400 text-xs md:text-[13px] leading-relaxed">เพิ่มประสิทธิภาพ<br>การทำงาน</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-center flex flex-col items-center justify-center group hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 group-hover:scale-105 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-dark text-[14px] md:text-base mb-2">ข้อมูลเรียลไทม์</h3>
                    <p class="text-slate-400 text-xs md:text-[13px] leading-relaxed">ตัดสินใจได้แม่นยำ<br>และรวดเร็ว</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-center flex flex-col items-center justify-center group hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 group-hover:scale-105 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-dark text-[14px] md:text-base mb-2">ควบคุมความเสี่ยง</h3>
                    <p class="text-slate-400 text-xs md:text-[13px] leading-relaxed">ตรวจสอบและติดตาม<br>ได้ทุกขั้นตอน</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.02)] text-center flex flex-col items-center justify-center group hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 text-primary flex items-center justify-center bg-blue-50/60 rounded-full mb-4 group-hover:scale-105 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-dark text-[14px] md:text-base mb-2">ขยายได้ตามธุรกิจ</h3>
                    <p class="text-slate-400 text-xs md:text-[13px] leading-relaxed">รองรับการเติบโต<br>ในอนาคต</p>
                </div>

            </div>
        </div>

    </div>
</section>