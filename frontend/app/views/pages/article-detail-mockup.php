<?php
declare(strict_types=1);
?>

<style>
    /* Article Typography */
    .article-format {
        color: #475569; /* slate-600 */
        font-size: 1.125rem;
        line-height: 1.8;
    }
    .article-format h2, 
    .article-format h3 {
        color: #0d6efd; 
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        scroll-margin-top: 6rem;
    }
    .article-format h2 {
        font-size: 1.75rem;
    }
    .article-format h3 {
        font-size: 1.35rem;
    }
    .article-format p {
        margin-bottom: 1.25rem;
    }
    .article-format img {
        border-radius: 0.75rem;
        margin: 2rem auto;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .article-format ul, .article-format ol {
        margin-bottom: 1.5rem;
        padding-left: 0;
        list-style-position: inside;
    }
    .article-format li {
        margin-bottom: 0;
        line-height: 1.8;
    }
    
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
</style>

<section class="relative overflow-hidden font-sans bg-[#F4F7FB] pt-12 pb-6 lg:pt-20 lg:pb-8">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col gap-8 items-start max-w-4xl mx-auto">
            
            <!-- Top Column: Image -->
            <div class="animate-fade-up delay-100 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e(asset_url('images/erp-system.png')) ?>" alt="ERP System Illustration" 
                    class="w-full h-[300px] sm:h-[400px] lg:h-[500px] object-cover hover:scale-105 transition-transform duration-700" onerror="this.src='<?= e(asset_url('images/story.png')) ?>'">
            </div>

            <!-- Bottom Column: Text & Meta -->
            <div class="w-full mt-4">
                <h1 class="animate-fade-up delay-200 leading-snug mb-6 tracking-tight">
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-slate-600 mb-2">
                        ระบบ ERP คืออะไร?
                    </span>
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-[#0663F6]">
                        สรุปครบ จบในที่เดียว!
                    </span>
                </h1>
                
                <div class="animate-fade-up delay-300 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-[#0663F6] font-medium mb-6">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        24 พฤษภาคม 2567
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                        Webpark Team
                    </span>
                </div>
                
                <p class="animate-fade-up delay-400 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-4xl mb-6 font-medium">
                    ทำความเข้าใจระบบ ERP แบบครบทุกมิติ ตั้งแต่ความหมาย ประโยชน์ ฟังก์ชันหลัก ประเภทของ ERP ความแตกต่างระหว่าง ERP กับ CRM และแนวทางการเลือกใช้ให้เหมาะกับธุรกิจของคุณ
                </p>
            </div>
            
        </div>
    </div>
</section>

<div class="bg-[#FAFAFC]">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-6 pb-12 lg:pt-8 lg:pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16">
            
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <article class="article-format bg-white rounded-[2rem] p-8 lg:p-12 shadow-sm border border-slate-100">
                    
                    <h2 id="toc-1" class="!text-[#0663F6] !mt-0">ทำความรู้จักกับระบบ ENTERPRISE RESOURCE PLANNING (ERP)</h2>
                    <p>ระบบ Enterprise Resource Planning (ERP) คือระบบบริหารจัดการทรัพยากรขององค์กรแบบบูรณาการ ที่เชื่อมโยงข้อมูลในทุกแผนกเข้าด้วยกันแบบเรียลไทม์ เพื่อช่วยให้องค์กรทำงานได้อย่างมีประสิทธิภาพ ลดความซ้ำซ้อน ลดต้นทุน และช่วยให้ผู้บริหารตัดสินใจได้อย่างแม่นยำ</p>
                    
                    <h2 id="toc-2" class="!text-[#0663F6]">ระบบ ERP ถูกพัฒนาขึ้นมาเพื่อแก้ปัญหาในด้านใด?</h2>
                    <p>ERP ถูกออกแบบมาเพื่อแก้ไขปัญหาการทำงานแบบแยกส่วน กระบวนการซ้ำซ้อน ข้อมูลไม่ตรงกัน และการพลาดข้อมูลสำหรับการวิเคราะห์เชิงลึก โดยช่วยสร้างระบบกลางที่รวมทุกกระบวนการเข้าด้วยกันอย่างเป็นเอกภาพ</p>
                    
                    <h2 id="toc-3" class="!text-[#0663F6]">เม็ดเงินภาษีที่พบบ่อยคืออะไร?</h2>
                    <ul class="list-disc marker:text-[#0663F6] space-y-0">
                        <li>ต้นทุนแฝงจากความผิดพลาดของข้อมูลและการทำงานซ้ำ</li>
                        <li>ค่าใช้จ่ายในการจัดการข้อมูลแบบแยกส่วน</li>
                        <li>โอกาสทางธุรกิจที่เสียไปจากข้อมูลที่ไม่ถูกต้อง</li>
                        <li>ค่าใช้จ่ายด้านแรงงานที่เพิ่มจากกระบวนการทำงานที่ไม่มีประสิทธิภาพ</li>
                        <li>ต้นทุนด้านการจัดเก็บและบำรุงรักษาระบบหลายระบบ</li>
                    </ul>
                    
                    <h2 id="toc-4" class="!text-[#0663F6]">มีฟังก์ชัน หรือโมดูลระบบ ENTERPRISE RESOURCE PLANNING</h2>
                    <p>ระบบ ERP โดยทั่วไปประกอบด้วยโมดูลหลักที่ครอบคลุมทุกกระบวนการขององค์กร ดังนี้</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                        <!-- Card 1 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_10.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบบริหารการขาย</h3>
                                <p class="text-base text-slate-500 leading-relaxed">จัดการงานขาย กำหนดราคา บริการลูกค้า และติดตามสถานะการขาย</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 2 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_11.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบจัดซื้อ</h3>
                                <p class="text-base text-slate-500 leading-relaxed">เพิ่มประสิทธิภาพการจัดซื้อและการบริหารผู้ขาย</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 3 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_12.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบบริหารสินค้าคงคลัง</h3>
                                <p class="text-base text-slate-500 leading-relaxed">ควบคุมสต็อกสินค้า ตรวจสอบการเคลื่อนไหว และแจ้งเตือนเมื่อถึงจุดสั่งซื้อ</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 4 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_13.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบบัญชีและการเงิน</h3>
                                <p class="text-base text-slate-500 leading-relaxed">บันทึกรายรับรายจ่าย ออกเอกสารทางการเงิน และสรุปงบการเงิน</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 5 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_14.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบการผลิต</h3>
                                <p class="text-base text-slate-500 leading-relaxed">วางแผนการผลิต ควบคุมทรัพยากร ลดต้นทุน และเพิ่มประสิทธิภาพ</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 6 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_15.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบบริหารทรัพยากรบุคคล</h3>
                                <p class="text-base text-slate-500 leading-relaxed">จัดการข้อมูลพนักงาน ประเมินผล และติดตามการทำงาน</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 7 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_16.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบอนุมัติและเวิร์กโฟลว์</h3>
                                <p class="text-base text-slate-500 leading-relaxed">กำหนดขั้นตอนการอนุมัติเอกสารและงานต่างๆ เพิ่มความโปร่งใส</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 8 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_17.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบลูกค้าสัมพันธ์</h3>
                                <p class="text-base text-slate-500 leading-relaxed">จัดการข้อมูลลูกค้าและติดตามความสัมพันธ์เพื่อเพิ่มโอกาสการขาย</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 9 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_18.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบควบคุมคุณภาพ</h3>
                                <p class="text-base text-slate-500 leading-relaxed">ตรวจสอบคุณภาพการผลิต ลดของเสีย และรักษามาตรฐานสินค้า</p>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Card 10 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_19.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1">ระบบซ่อมบำรุง</h3>
                                <p class="text-base text-slate-500 leading-relaxed">จัดการการซ่อมบำรุงเครื่องจักร ลด Downtime และยืดอายุการใช้งาน</p>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                    <h2 id="toc-5" class="!text-[#0663F6] !mt-16">ทำความรู้จักกับระบบ ERP ทั้ง 2 ประเภท</h2>
                    
                    <h3 class="!text-[#002B7F] !mt-8 !text-[17px] !font-bold">1. ERP แบบ On-Premise</h3>
                    <p class="!mt-2 !text-slate-600">ติดตั้งบนเซิร์ฟเวอร์ภายในองค์กร มีความยืดหยุ่นสูง เหมาะกับองค์กรที่ต้องการควบคุมระบบและข้อมูลด้วยตนเอง</p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold">2. ERP แบบ Cloud</h3>
                    <p class="!mt-2 !text-slate-600">ให้บริการบนคลาวด์ เข้าถึงได้ทุกที่ทุกเวลา ลดค่าบำรุงรักษา เหมาะกับธุรกิจที่ต้องการความคล่องตัวและรวดเร็ว</p>

                    <h2 id="toc-6" class="!text-[#0663F6] !mt-16">ใช้ ERP หรือ CRM ดีกว่ากัน?</h2>
                    <p class="!mt-4 !text-slate-600">ERP มุ่งเน้นการบริหารจัดการภายในองค์กรแบบครบวงจร ขณะที่ CRM เน้นการบริหารลูกค้าสัมพันธ์และการขาย ธุรกิจส่วนใหญ่มักเลือกใช้ร่วมกัน เพื่อให้ได้ทั้งประสิทธิภาพภายในและประสบการณ์ที่ดีกับลูกค้า</p>
                    
                    <h2 id="toc-7" class="!text-[#0663F6] !mt-16">ERP และ CRM ใช้เทคโนโลยีอะไร</h2>
                    <ul class="list-disc !text-[#002B7F] marker:text-[#002B7F] !mt-6">
                        <li><span class="text-slate-600">ฐานข้อมูลแบบ Relational เช่น SQL Server, Oracle</span></li>
                        <li><span class="text-slate-600">ระบบคลาวด์ AWS, Azure, Google Cloud</span></li>
                        <li><span class="text-slate-600">เทคโนโลยี API สำหรับเชื่อมต่อระบบภายนอก</span></li>
                        <li><span class="text-slate-600">ระบบวิเคราะห์ข้อมูล Business Intelligence & Analytics</span></li>
                    </ul>

                    <div class="bg-[#EEF2FC] rounded-lg p-8 md:p-12 text-center relative mt-16 mb-16 overflow-hidden">
                        <div class="absolute top-4 left-6 text-7xl text-white font-serif leading-none opacity-80">“</div>
                        <div class="absolute bottom-[-20px] right-6 text-7xl text-white font-serif leading-none opacity-80">”</div>
                        <p class="text-[#0663F6] font-bold text-[16px] min-[375px]:text-[17px] sm:text-[20px] md:text-[24px] lg:text-[26px] leading-[1.9] md:leading-[2] relative z-10 m-0">
                            เทคโนโลยีที่ดีไม่เพียงช่วยให้องค์กรที่ใช้<br>
                            ERP และ CRM สามารถทำงานร่วมกันได้<br>
                            อย่างมีประสิทธิภาพ<br>
                            และช่วยให้องค์กรก้าวสู่การเป็น<br>
                            Data-Driven Organization อย่างแท้จริง
                        </p>
                    </div>

                    <h2 id="toc-8" class="!text-[#0663F6]">บริษัทใดไม่ต้อง ERP คุณภาพ</h2>
                    <p class="!mt-4 !text-slate-600">ธุรกิจขนาดเล็กที่มีกระบวนการไม่ซับซ้อน อาจยังไม่จำเป็นต้องใช้ ERP เต็มรูปแบบ แต่เมื่อธุรกิจเติบโตขึ้น มีข้อมูลเพิ่มขึ้น และมีหลายแผนกทำงานร่วมกัน ระบบ ERP จะช่วยยกระดับองค์กรได้อย่างชัดเจน</p>

                    <h2 id="toc-9" class="!text-[#0663F6] !mt-16">ประโยชน์ในการพัฒนาของ ERP</h2>
                    
                    <h3 class="!text-[#002B7F] !mt-8 !text-[17px] !font-bold">1. เพิ่มประสิทธิภาพการทำงาน ลดเวลาและต้นทุน</h3>
                    <p class="!mt-2 !text-slate-600 pl-5">ข้อมูลเชื่อมโยงกันแบบเรียลไทม์ ลดการทำงานซ้ำซ้อน และลดความผิดพลาด</p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold">2. ช่วยให้ผู้บริหารตัดสินใจได้ดีขึ้น</h3>
                    <p class="!mt-2 !text-slate-600 pl-5">มีข้อมูลครบถ้วนและถูกต้องสำหรับการวิเคราะห์และวางแผน</p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold">3. เพิ่มความโปร่งใสและตรวจสอบได้</h3>
                    <p class="!mt-2 !text-slate-600 pl-5">ระบบบันทึกข้อมูลทุกขั้นตอน ตรวจสอบย้อนหลังได้ง่าย</p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold">4. รองรับการเติบโตของธุรกิจในอนาคต</h3>
                    <p class="!mt-2 !text-slate-600 pl-5">ระบบที่ยืดหยุ่น รองรับการขยายตัวและการเปลี่ยนแปลงทางธุรกิจ</p>

                    <h2 id="toc-10" class="!text-[#0663F6] !mt-16">บริษัทไหนที่เหมาะกับระบบ ERP</h2>
                    <ul class="list-disc !text-[#002B7F] marker:text-[#002B7F] !mt-6">
                        <li><span class="text-slate-600">ธุรกิจขนาดกลางถึงขนาดใหญ่</span></li>
                        <li><span class="text-slate-600">ธุรกิจที่มีหลายสาขาหรือหลายแผนก</span></li>
                        <li><span class="text-slate-600">ธุรกิจที่ต้องการข้อมูลเชิงลึกเพื่อการตัดสินใจ</span></li>
                        <li><span class="text-slate-600">ธุรกิจที่ต้องการลดต้นทุนและเพิ่มประสิทธิภาพการดำเนินงาน</span></li>
                    </ul>

                    <h2 id="toc-11" class="!text-[#0663F6] !mt-16">สรุปท้ายบท</h2>
                    <p class="!mt-4 !text-slate-600">ระบบ ERP คือเครื่องมือสำคัญในการบริหารจัดการองค์กรยุคดิจิทัล ช่วยรวมทุกกระบวนการเข้าด้วยกัน เพิ่มประสิทธิภาพ ลดต้นทุน และช่วยให้ธุรกิจเติบโตได้อย่างมั่นคงและยั่งยืน การเลือกใช้ระบบที่เหมาะสม พร้อมพาร์ทเนอร์ที่เชี่ยวชาญ จะช่วยให้การลงทุนใน ERP เกิดผลตอบแทนสูงสุด</p>
                    
                </article>
            </div>
            
            <!-- Sidebar Right: Related Articles -->
            <div class="lg:col-span-4 relative h-full">
                <div class="bg-white rounded-[2rem] p-5 lg:p-6 border border-slate-100 shadow-sm sticky top-[100px] h-max z-20 max-h-[calc(100vh-140px)] overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" style="position: sticky; top: 100px;">
                    <h4 class="text-[19px] font-bold text-[#0663F6] mb-4">
                        บทความที่เกี่ยวข้อง
                    </h4>
                    <div class="space-y-4">
                        
                        <!-- Mockup Related Article 1 -->
                        <div class="bg-white border border-slate-100 rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <img src="<?= e(asset_url('images/story.png')) ?>" class="w-full aspect-[16/9] object-cover" alt="AI Robot">
                            <div class="p-5">
                                <h5 class="text-[16px] font-bold text-[#0663F6] mb-2">AI ช่วยธุรกิจได้จริงอย่างไร?</h5>
                                <p class="text-[13px] text-slate-500 mb-4 line-clamp-2 leading-relaxed">รวมตัวอย่างการใช้ AI ในงานต่าง ๆ เช่น วิเคราะห์ข้อมูล บริการลูกค้า การตลาด และการผลิต...</p>
                                <div class="text-right">
                                    <a href="#" class="text-[#0663F6] text-[13px] font-bold hover:underline">อ่านเพิ่มเติม &rarr;</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mockup Related Article 2 -->
                        <div class="bg-white border border-slate-100 rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <img src="<?= e(asset_url('images/story.png')) ?>" class="w-full aspect-[16/9] object-cover" alt="AI Robot">
                            <div class="p-5">
                                <h5 class="text-[16px] font-bold text-[#0663F6] mb-2">AI ช่วยธุรกิจได้จริงอย่างไร?</h5>
                                <p class="text-[13px] text-slate-500 mb-4 line-clamp-2 leading-relaxed">รวมตัวอย่างการใช้ AI ในงานต่าง ๆ เช่น วิเคราะห์ข้อมูล บริการลูกค้า การตลาด และการผลิต...</p>
                                <div class="text-right">
                                    <a href="#" class="text-[#0663F6] text-[13px] font-bold hover:underline">อ่านเพิ่มเติม &rarr;</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


