<?php
declare(strict_types=1);

// ตัวแปรตั้งต้นจากระบบ (สามารถนำไปใช้งานต่อได้)
$values = $values ?? [];
$timeline = $timeline ?? [];
$team = $team ?? [];
$partners = $partners ?? [];
$trustLogos = $trustLogos ?? [];
$company = $company ?? [];
$contactEmail = $company['contact']['email'] ?? 'oraphan@webpark.co.th';
$contactPhone = $company['contact']['phone'] ?? '095 539 2666';
$contactAddress = $company['contact']['address'] ?? '525/89 ซอยลาดพร้าว126 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310';
?>

<style>
    /* 1. แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง (Entrance) */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0; /* ซ่อนไว้ก่อนเริ่ม */
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 2. แอนิเมชันสำหรับตัวอักษรสีเหลือบ (Gradient Flow) */
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }

    /* คลาสหน่วงเวลา เพื่อให้เนื้อหาไล่ลำดับกันขึ้นมา */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-6.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white to-white/5"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <div class="animate-fade-up delay-100 inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">ABOUT US</span>
                </div>
                
                <h1 class="animate-fade-up delay-200 text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
                    <span class="bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">ผู้ให้บริการด้าน</span><br>
                    <span class="bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block" style="animation-delay: -3s;">ERP / ERM</span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    WEBPARK ผู้เชี่ยวชาญด้าน ERP/ERM และระบบดิจิทัล<br>ครบวงจร เราช่วยให้องค์กรของคุณทำงานอย่างชาญฉลาด<br>ด้วยเทคโนโลยีล้ำสมัยแพลตฟอร์มดจิทัลและ AI <br>เพื่อการเติบโตที่ยั่งยืนในยุคดิจิทัล
                </p>

                <div class="animate-fade-up delay-400 flex flex-wrap items-center gap-4">
                    <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
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

<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-16 items-center">
            
            <div class="max-w-xl">
<span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        เกี่ยวกับเรา
    </span>
                
                <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-dark leading-tight mb-8">
                    เรา คือ <span class="text-primary">WEBPARK</span><br>
                    ผู้นำด้านโซลูชันธุรกิจดิจิทัล
                </h2>
                
                <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-6">
                    WEBPARK ก่อตั้งขึ้นด้วยวิสัยทัศน์ที่มุ่งมั่นในการยกระดับศักยภาพธุรกิจไทยผ่านเทคโนโลยีและนวัตกรรมดิจิทัล เราเชี่ยวชาญในการพัฒนาระบบ ERP / ERM แพลตฟอร์มดิจิทัล และโซลูชันที่ตอบโจทย์ทุกความต้องการขององค์กรในทุกอุตสาหกรรม
                </p>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-10">
                    ด้วยทีมงานผู้เชี่ยวชาญ ประสบการณ์ยาวนาน และความเข้าใจธุรกิจอย่างลึกซึ้ง เราพร้อมเป็นพาร์ทเนอร์ที่เชื่อถือได้ เพื่อช่วยให้องค์กรของคุณก้าวสู่อนาคตได้อย่างมั่นคงและยั่งยืน
                </p>
                
                <a href="<?= e(route_url('/about')) ?>" class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all duration-300">
                    อ่านเพิ่มเติม
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-[2rem] p-6 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 lg:gap-12 relative">
                    
                    <div class="hidden sm:block absolute top-1/2 left-0 w-full h-px bg-slate-100 -translate-y-1/2"></div>
                    <div class="hidden sm:block absolute top-0 left-1/2 w-px h-full bg-slate-100 -translate-x-1/2"></div>

                    <div class="flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 text-primary group-hover:scale-110 transition-transform">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base">ประสบการณ์ยาวนาน<br>มากกว่า 12 ปี</h4>
                    </div>
                    
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 text-primary group-hover:scale-110 transition-transform">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base">ทีมผู้เชี่ยวชาญ<br>พร้อมดูแลคุณ</h4>
                    </div>

                    <div class="flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 text-primary group-hover:scale-110 transition-transform">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base">โซลูชันที่เชื่อถือได้<br>ปลอดภัย มั่นคง</h4>
                    </div>

                    <div class="flex flex-col items-center text-center group">
                        <div class="w-16 h-16 mb-4 text-primary group-hover:scale-110 transition-transform">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h4 class="text-dark font-bold text-sm md:text-base">มุ่งมั่นผลลัพธ์ที่สร้าง<br>การเติบโตให้ธุรกิจ</h4>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-white py-12 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="bg-dark rounded-[2rem] overflow-hidden relative shadow-2xl flex flex-col md:flex-row items-center min-h-[300px] lg:min-h-[400px]">
            
            <div class="absolute inset-0 bg-gradient-to-r from-[#0b162c] to-transparent z-10"></div>
            
            <div class="relative z-20 p-8 md:p-12 lg:p-16 w-full md:w-1/2">
                <span class="text-white font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        บริการของเรา
    </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-4">
                    บริการของเรา
                </h2>
                <p class="text-slate-300 text-sm md:text-base leading-relaxed mb-8 max-w-sm">
                    กว่า 120 องค์กรชั้นนำไว้วางใจ WEBPARK ในการพัฒนาระบบและโซลูชันดิจิทัล ที่ช่วยยกระดับประสิทธิภาพและขับเคลื่อนธุรกิจ
                </p>
                <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-dark text-sm font-bold rounded-full hover:bg-blue-50 transition-all">
                    ดูบริการของเรา
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="absolute right-0 bottom-0 inset-0 z-0">
                <img src="<?= e(asset_url('images/contact.png')) ?>" alt="Portfolio" class="w-full h-full object-cover">
            </div>
            
        </div>
    </div>
</section>

<section class="bg-white py-16 lg:py-24 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 lg:grid-cols-[40%_55%] gap-12 lg:gap-0 justify-between items-start">
            
            <div class="sticky top-24">
                <h2 class="text-3xl md:text-4xl font-bold text-dark leading-tight mb-6">
                    <span class="text-primary">แนวคิด</span>ในการทำงานของเรา
                </h2>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed max-w-md">
                    เราเชื่อว่าการพัฒนาระบบและโซลูชันดิจิทัลที่ดี ไม่ได้เริ่มจากเทคโนโลยีเพียงอย่างเดียว แต่เริ่มจากความเข้าใจธุรกิจของคุณ เราทำงานแบบพาร์ทเนอร์ร่วมคิด ร่วมสร้าง เพื่อให้ทุกโซลูชันที่เราส่งมอบ สามารถใช้งานได้ สร้างคุณค่า และช่วยให้ธุรกิจของคุณเติบโตได้อย่างยั่งยืน
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <?php
                $processes = [
                    ['step' => '01', 'title' => 'เข้าใจธุรกิจของคุณ', 'desc' => 'เราศึกษาและทำความเข้าใจความต้องการของธุรกิจ เพื่อออกแบบโซลูชันที่ตรงจุด', 'icon' => 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z'],
                    ['step' => '02', 'title' => 'ออกแบบให้ใช้งานได้จริง', 'desc' => 'การออกแบบระบบที่ใช้งานง่าย รองรับการขยายตัว และสอดคล้องกับกระบวนการธุรกิจ', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['step' => '03', 'title' => 'ดูแลอย่างต่อเนื่อง', 'desc' => 'ให้คำปรึกษาและบริการหลังการขายอย่างต่อเนื่อง เพื่อความมั่นใจตลอดการใช้งาน', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['step' => '04', 'title' => 'รองรับการเติบโต', 'desc' => 'ระบบและแพลตฟอร์มที่ยืดหยุ่น พร้อมเติบโตไปพร้อมกับธุรกิจของคุณ', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
                ];
                ?>
                
                <?php foreach ($processes as $item): ?>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex items-start gap-6 hover:border-blue-200 hover:shadow-md transition-all group">
                        <div class="w-14 h-14 shrink-0 rounded-full bg-[#f0f7ff] flex flex-col items-center justify-center text-primary border border-blue-100 group-hover:scale-110 transition-transform">
                            <span class="text-xs font-bold leading-none mb-0.5"><?= $item['step'] ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['icon'] ?>" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-dark font-bold text-base mb-2 group-hover:text-primary transition-colors"><?= $item['title'] ?></h4>
                            <p class="text-slate-500 text-md leading-relaxed"><?= $item['desc'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>

<section class="bg-[#f8fafc] py-16 lg:py-24 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <div class="mb-2">
            <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">
        บริการของเรา
    </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
                บริการของเราครอบคลุมทุกด้าน
            </h2>
        </div>
        <p class=" mb-3 text-sm md:text-base leading-relaxed text-slate-600 max-w-3xl">
        ระบบที่ช่วยพัฒนาโซลูชันดิจิทัลที่ช่วยให้ธุรกิจเติบโตอย่างยั่งยืน
    </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $services = [
                ['title' => 'พัฒนาระบบซอฟต์แวร์', 'desc' => 'ออกแบบและพัฒนาระบบที่ตอบโจทย์ความต้องการ<br>เฉพาะขององค์กร', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                ['title' => 'ระบบสำหรับองค์กร', 'desc' => 'วางโครงสร้างระบบองค์กรที่แข็งแกร่ง<br>รองรับการทำงานและประสิทธิภาพที่เพิ่มขึ้น', 'icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01'],
                ['title' => 'การตลาดออนไลน์', 'desc' => 'วางกลยุทธ์การตลาดออนไลน์ครบวงจร<br>เพิ่มการมองเห็นและสร้างโอกาสทางธุรกิจ', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                ['title' => 'เว็บไซต์และแอปพลิเคชัน', 'desc' => 'พัฒนาเว็บไซต์และแอปพลิเคชันที่สวยงาม<br>ใช้งานง่าย รองรับทุกอุปกรณ์', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['title' => 'AI Agent และระบบอัตโนมัติ', 'desc' => 'ผสานพลัง AI และระบบอัตโนมัติ<br>เพื่อเพิ่มประสิทธิภาพและลดต้นทุนการทำงาน', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['title' => 'งานออกแบบดิจิทัล', 'desc' => 'สร้างสรรค์งานออกแบบดิจิทัลคุณภาพสูง<br>สื่อสารแบรนด์อย่างมืออาชีพ', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z']
            ];
            ?>
            
            <?php foreach ($services as $srv): ?>
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group cursor-pointer
                            hover:bg-primary group-hover:bg-primary">
                    <div class="w-12 h-12 mb-6 text-primary group-hover:text-white group-hover:scale-110 transition-transform">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $srv['icon'] ?>" />
                        </svg>
                    </div>
                    <h3 class="text-dark font-bold text-lg mb-3 group-hover:text-white transition-colors"><?= $srv['title'] ?></h3>
                    <p class="text-slate-500 text-md leading-relaxed group-hover:text-white transition-colors"><?= $srv['desc'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<section class="bg-white py-12 lg:py-20 font-sans mb-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-[#f4f9ff] rounded-[1.5rem] p-8 md:p-10 text-center flex flex-col items-center justify-center border border-blue-50">
                <span class="text-5xl md:text-6xl font-black text-primary mb-2 tracking-tight">12</span>
                <h4 class="text-dark font-bold text-base md:text-lg mb-2">ปีที่สร้างระบบ</h4>
                <p class="text-slate-500 text-[13px] md:text-sm">ประสบการณ์ที่มั่นคง เชื่อถือได้</p>
            </div>

            <div class="bg-[#f4f9ff] rounded-[1.5rem] p-8 md:p-10 text-center flex flex-col items-center justify-center border border-blue-50">
                <span class="text-5xl md:text-6xl font-black text-primary mb-2 tracking-tight">374</span>
                <h4 class="text-dark font-bold text-base md:text-lg mb-2">ระบบที่ส่งมอบ</h4>
                <p class="text-slate-500 text-[13px] md:text-sm">ครอบคลุมทุกอุตสาหกรรม</p>
            </div>

            <div class="bg-[#f4f9ff] rounded-[1.5rem] p-8 md:p-10 text-center flex flex-col items-center justify-center border border-blue-50">
                <span class="text-5xl md:text-6xl font-black text-primary mb-2 tracking-tight">50+</span>
                <h4 class="text-dark font-bold text-base md:text-lg mb-2">บริษัทที่วางใจเรา</h4>
                <p class="text-slate-500 text-[13px] md:text-sm">พันธมิตรที่เติบโตไปด้วยกัน</p>
            </div>

        </div>
    </div>
</section>