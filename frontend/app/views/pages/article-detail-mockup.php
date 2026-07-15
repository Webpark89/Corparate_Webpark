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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            
            <!-- Left Column: Text & Meta -->
            <div class="max-w-xl">
                <nav aria-label="Breadcrumb" class="animate-fade-up delay-100 mb-8">
                    <ol class="inline-flex flex-wrap items-center text-sm md:text-base font-medium text-slate-400">
                        <li>
                            <a href="<?= e(route_url('/')) ?>" class="hover:text-primary transition-colors duration-200"><?= e(getCurrentLang() === 'th' ? 'หน้าแรก' : 'Home') ?></a>
                        </li>
                        <li><span class="mx-4">/</span></li>
                        <li>
                            <a href="<?= e(route_url('/article')) ?>" class="hover:text-primary transition-colors duration-200"><?= e(getCurrentLang() === 'th' ? 'บทความ' : 'Articles') ?></a>
                        </li>
                        <li><span class="mx-4">/</span></li>
                        <li aria-current="page">
                            <span class="text-slate-400">ERP System</span>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="animate-fade-up delay-200 leading-snug mb-6 tracking-tight">
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-slate-500 mb-2">
                        <?= e(getCurrentLang() === 'th' ? 'ระบบ ERP คืออะไร?' : 'What is an ERP System?') ?>
                    </span>
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-[#022862]">
                        <?= e(getCurrentLang() === 'th' ? 'สรุปครบ จบในที่เดียว!' : 'All You Need to Know!') ?>
                    </span>
                </h1>
                
                <div class="animate-fade-up delay-300 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-[#0663F6] font-medium mb-6">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <?= e(getCurrentLang() === 'th' ? '24 พฤษภาคม 2567' : 'May 24, 2024') ?>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                        Webpark Team
                    </span>
                </div>
                
                <p class="animate-fade-up delay-400 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium">
                    <?= e(getCurrentLang() === 'th' ? 'ทำความเข้าใจระบบ ERP แบบครบทุกมิติ ตั้งแต่ความหมาย ประโยชน์ ฟังก์ชันหลัก ประเภทของ ERP ความแตกต่างระหว่าง ERP กับ CRM และแนวทางการเลือกใช้ให้เหมาะกับธุรกิจของคุณ' : 'Understand the ERP system in all dimensions, from its meaning, benefits, core functions, types of ERP, the difference between ERP and CRM, to guidelines for choosing the right one for your business.') ?>
                </p>
            </div>
            
            <!-- Right Column: Image -->
            <div class="animate-fade-up delay-300 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e(asset_url('images/erp-system.png')) ?>" alt="ERP System Illustration" 
                    class="w-full h-auto object-cover aspect-[4/3] hover:scale-105 transition-transform duration-700" onerror="this.src='<?= e(asset_url('images/story.png')) ?>'">
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
                    
                    <h2 id="toc-1" class="!text-[#0663F6] !mt-0"><?= e(getCurrentLang() === 'th' ? 'ทำความรู้จักกับระบบ ENTERPRISE RESOURCE PLANNING (ERP)' : 'Get to Know Enterprise Resource Planning (ERP)') ?></h2>
                    <p><?= e(getCurrentLang() === 'th' ? 'ระบบ Enterprise Resource Planning (ERP) คือระบบบริหารจัดการทรัพยากรขององค์กรแบบบูรณาการ ที่เชื่อมโยงข้อมูลในทุกแผนกเข้าด้วยกันแบบเรียลไทม์ เพื่อช่วยให้องค์กรทำงานได้อย่างมีประสิทธิภาพ ลดความซ้ำซ้อน ลดต้นทุน และช่วยให้ผู้บริหารตัดสินใจได้อย่างแม่นยำ' : 'An Enterprise Resource Planning (ERP) system is an integrated organizational resource management system that connects data across all departments in real time to help organizations operate efficiently, reduce redundancy, cut costs, and enable executives to make accurate decisions.') ?></p>
                    
                    <h2 id="toc-2" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'ระบบ ERP ถูกพัฒนาขึ้นมาเพื่อแก้ปัญหาในด้านใด?' : 'What problems was the ERP system developed to solve?') ?></h2>
                    <p><?= e(getCurrentLang() === 'th' ? 'ERP ถูกออกแบบมาเพื่อแก้ไขปัญหาการทำงานแบบแยกส่วน กระบวนการซ้ำซ้อน ข้อมูลไม่ตรงกัน และการพลาดข้อมูลสำหรับการวิเคราะห์เชิงลึก โดยช่วยสร้างระบบกลางที่รวมทุกกระบวนการเข้าด้วยกันอย่างเป็นเอกภาพ' : 'ERP is designed to solve the problems of fragmented work, redundant processes, inconsistent data, and missed data for in-depth analysis by creating a central system that unifies all processes.') ?></p>
                    
                    <h2 id="toc-3" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'เม็ดเงินภาษีที่พบบ่อยคืออะไร?' : 'What are the common hidden costs?') ?></h2>
                    <ul class="list-disc marker:text-[#0663F6] space-y-0">
                        <li><?= e(getCurrentLang() === 'th' ? 'ต้นทุนแฝงจากความผิดพลาดของข้อมูลและการทำงานซ้ำ' : 'Hidden costs from data errors and repetitive work') ?></li>
                        <li><?= e(getCurrentLang() === 'th' ? 'ค่าใช้จ่ายในการจัดการข้อมูลแบบแยกส่วน' : 'Expenses in fragmented data management') ?></li>
                        <li><?= e(getCurrentLang() === 'th' ? 'โอกาสทางธุรกิจที่เสียไปจากข้อมูลที่ไม่ถูกต้อง' : 'Lost business opportunities due to inaccurate data') ?></li>
                        <li><?= e(getCurrentLang() === 'th' ? 'ค่าใช้จ่ายด้านแรงงานที่เพิ่มจากกระบวนการทำงานที่ไม่มีประสิทธิภาพ' : 'Increased labor costs from inefficient workflows') ?></li>
                        <li><?= e(getCurrentLang() === 'th' ? 'ต้นทุนด้านการจัดเก็บและบำรุงรักษาระบบหลายระบบ' : 'Costs of storing and maintaining multiple systems') ?></li>
                    </ul>
                    
                    <h2 id="toc-4" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'มีฟังก์ชัน หรือโมดูลระบบ ENTERPRISE RESOURCE PLANNING' : 'Functions or Modules of Enterprise Resource Planning') ?></h2>
                    <p><?= e(getCurrentLang() === 'th' ? 'ระบบ ERP โดยทั่วไปประกอบด้วยโมดูลหลักที่ครอบคลุมทุกกระบวนการขององค์กร ดังนี้' : 'An ERP system generally consists of core modules covering all organizational processes as follows:') ?></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                        <!-- Card 1 -->
                        <div class="bg-white rounded-2xl px-5 pb-5 pt-0 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-start hover:-translate-y-1">
                            <div class="flex flex-row items-center gap-4">
                            <div class="w-[50px] h-[50px] bg-slate-50 rounded-full flex items-center justify-center shrink-0">
                                <img src="<?= e(asset_url('images/ERP_10.svg')) ?>" class="w-7 h-7 object-contain" alt="Icon">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบบริหารการขาย' : 'Sales Management System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'จัดการงานขาย กำหนดราคา บริการลูกค้า และติดตามสถานะการขาย' : 'Manage sales, set pricing, customer service, and track sales status') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบจัดซื้อ' : 'Purchasing System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'เพิ่มประสิทธิภาพการจัดซื้อและการบริหารผู้ขาย' : 'Enhance purchasing efficiency and vendor management') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบบริหารสินค้าคงคลัง' : 'Inventory Management System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'ควบคุมสต็อกสินค้า ตรวจสอบการเคลื่อนไหว และแจ้งเตือนเมื่อถึงจุดสั่งซื้อ' : 'Control stock, monitor movement, and alert at reorder points') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบบัญชีและการเงิน' : 'Accounting and Finance System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'บันทึกรายรับรายจ่าย ออกเอกสารทางการเงิน และสรุปงบการเงิน' : 'Record income/expenses, issue financial documents, and summarize financial statements') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบการผลิต' : 'Production System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'วางแผนการผลิต ควบคุมทรัพยากร ลดต้นทุน และเพิ่มประสิทธิภาพ' : 'Plan production, control resources, reduce costs, and increase efficiency') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบบริหารทรัพยากรบุคคล' : 'Human Resource Management System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'จัดการข้อมูลพนักงาน ประเมินผล และติดตามการทำงาน' : 'Manage employee data, evaluate performance, and track work') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบอนุมัติและเวิร์กโฟลว์' : 'Approval and Workflow System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'กำหนดขั้นตอนการอนุมัติเอกสารและงานต่างๆ เพิ่มความโปร่งใส' : 'Define document approval steps and tasks, increasing transparency') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบลูกค้าสัมพันธ์' : 'Customer Relationship Management') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'จัดการข้อมูลลูกค้าและติดตามความสัมพันธ์เพื่อเพิ่มโอกาสการขาย' : 'Manage customer data and track relationships to increase sales opportunities') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบควบคุมคุณภาพ' : 'Quality Control System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'ตรวจสอบคุณภาพการผลิต ลดของเสีย และรักษามาตรฐานสินค้า' : 'Check production quality, reduce waste, and maintain product standards') ?></p>
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
                                <h3 class="text-lg font-bold text-[#002B7F] mb-1"><?= e(getCurrentLang() === 'th' ? 'ระบบซ่อมบำรุง' : 'Maintenance System') ?></h3>
                                <p class="text-base text-slate-500 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'จัดการการซ่อมบำรุงเครื่องจักร ลด Downtime และยืดอายุการใช้งาน' : 'Manage machine maintenance, reduce downtime, and extend lifespan') ?></p>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                    <h2 id="toc-5" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'ทำความรู้จักกับระบบ ERP ทั้ง 2 ประเภท' : 'Get to Know the 2 Types of ERP Systems') ?></h2>
                    
                    <h3 class="!text-[#022862] !mt-4 !text-[16px] !font-bold !mb-0"><?= e(getCurrentLang() === 'th' ? '1. ERP แบบ On-Premise' : '1. On-Premise ERP') ?></h3>
                    <p class="!mt-0 !text-[15px] !text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ติดตั้งบนเซิร์ฟเวอร์ภายในองค์กร มีความยืดหยุ่นสูง เหมาะกับองค์กรที่ต้องการควบคุมระบบและข้อมูลด้วยตนเอง' : 'Installed on internal servers, highly flexible, suitable for organizations that want to control systems and data themselves') ?></p>
                    
                    <h3 class="!text-[#022862] !mt-4 !text-[16px] !font-bold !mb-0"><?= e(getCurrentLang() === 'th' ? '2. ERP แบบ Cloud' : '2. Cloud ERP') ?></h3>
                    <p class="!mt-0 !text-[15px] !text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ให้บริการบนคลาวด์ เข้าถึงได้ทุกที่ทุกเวลา ลดค่าบำรุงรักษา เหมาะกับธุรกิจที่ต้องการความคล่องตัวและรวดเร็ว' : 'Cloud-based service, accessible anywhere anytime, reduces maintenance costs, suitable for businesses needing agility and speed') ?></p>

                    <h2 id="toc-6" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'ใช้ ERP หรือ CRM ดีกว่ากัน?' : 'Is it better to use ERP or CRM?') ?></h2>
                    <p class="!mt-4 !text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ERP มุ่งเน้นการบริหารจัดการภายในองค์กรแบบครบวงจร ขณะที่ CRM เน้นการบริหารลูกค้าสัมพันธ์และการขาย ธุรกิจส่วนใหญ่มักเลือกใช้ร่วมกัน เพื่อให้ได้ทั้งประสิทธิภาพภายในและประสบการณ์ที่ดีกับลูกค้า' : 'ERP focuses on comprehensive internal management, while CRM focuses on customer relationship and sales management. Most businesses choose to use both to achieve internal efficiency and a good customer experience.') ?></p>
                    
                    <h2 id="toc-7" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'ERP และ CRM ใช้เทคโนโลยีอะไร' : 'What technologies do ERP and CRM use?') ?></h2>
                    <ul class="list-disc !text-[#002B7F] marker:text-[#002B7F] !mt-6">
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ฐานข้อมูลแบบ Relational เช่น SQL Server, Oracle' : 'Relational databases such as SQL Server, Oracle') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ระบบคลาวด์ AWS, Azure, Google Cloud' : 'Cloud systems like AWS, Azure, Google Cloud') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'เทคโนโลยี API สำหรับเชื่อมต่อระบบภายนอก' : 'API technologies for connecting external systems') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ระบบวิเคราะห์ข้อมูล Business Intelligence & Analytics' : 'Business Intelligence & Analytics systems') ?></span></li>
                    </ul>

                    <div class="bg-[#EEF2FC] rounded-lg p-8 md:p-12 text-center relative mt-16 mb-16 overflow-hidden">
                        <div class="absolute top-4 left-6 text-7xl text-white font-serif leading-none opacity-80">“</div>
                        <div class="absolute bottom-[-20px] right-6 text-7xl text-white font-serif leading-none opacity-80">”</div>
                        <p class="text-[#0663F6] font-bold text-[16px] min-[375px]:text-[17px] sm:text-[20px] md:text-[24px] lg:text-[26px] leading-[1.9] md:leading-[2] relative z-10 m-0">
                            <?php if(getCurrentLang() === 'th'): ?>
                            เทคโนโลยีที่ดีไม่เพียงช่วยให้องค์กรที่ใช้<br>
                            ERP และ CRM สามารถทำงานร่วมกันได้<br>
                            อย่างมีประสิทธิภาพ<br>
                            และช่วยให้องค์กรก้าวสู่การเป็น<br>
                            Data-Driven Organization อย่างแท้จริง
                            <?php else: ?>
                            Good technology not only helps organizations using<br>
                            ERP and CRM to work together efficiently,<br>
                            but also truly drives the organization to become a<br>
                            Data-Driven Organization.
                            <?php endif; ?>
                        </p>
                    </div>

                    <h2 id="toc-8" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'บริษัทใดไม่ต้อง ERP คุณภาพ' : 'Which companies do not need a quality ERP?') ?></h2>
                    <p class="!mt-4 !text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ธุรกิจขนาดเล็กที่มีกระบวนการไม่ซับซ้อน อาจยังไม่จำเป็นต้องใช้ ERP เต็มรูปแบบ แต่เมื่อธุรกิจเติบโตขึ้น มีข้อมูลเพิ่มขึ้น และมีหลายแผนกทำงานร่วมกัน ระบบ ERP จะช่วยยกระดับองค์กรได้อย่างชัดเจน' : 'Small businesses with uncomplicated processes may not yet need a full ERP system. But as the business grows, data increases, and multiple departments collaborate, an ERP system will clearly elevate the organization.') ?></p>

                    <h2 id="toc-9" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'ประโยชน์ในการพัฒนาของ ERP' : 'Benefits of ERP Development') ?></h2>
                    
                    <h3 class="!text-[#002B7F] !mt-8 !text-[17px] !font-bold"><?= e(getCurrentLang() === 'th' ? '1. เพิ่มประสิทธิภาพการทำงาน ลดเวลาและต้นทุน' : '1. Increase operational efficiency, reduce time and costs') ?></h3>
                    <p class="!mt-2 !text-slate-600 pl-5"><?= e(getCurrentLang() === 'th' ? 'ข้อมูลเชื่อมโยงกันแบบเรียลไทม์ ลดการทำงานซ้ำซ้อน และลดความผิดพลาด' : 'Real-time data connection, reducing redundancy and errors.') ?></p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold"><?= e(getCurrentLang() === 'th' ? '2. ช่วยให้ผู้บริหารตัดสินใจได้ดีขึ้น' : '2. Help executives make better decisions') ?></h3>
                    <p class="!mt-2 !text-slate-600 pl-5"><?= e(getCurrentLang() === 'th' ? 'มีข้อมูลครบถ้วนและถูกต้องสำหรับการวิเคราะห์และวางแผน' : 'Complete and accurate data for analysis and planning.') ?></p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold"><?= e(getCurrentLang() === 'th' ? '3. เพิ่มความโปร่งใสและตรวจสอบได้' : '3. Increase transparency and traceability') ?></h3>
                    <p class="!mt-2 !text-slate-600 pl-5"><?= e(getCurrentLang() === 'th' ? 'ระบบบันทึกข้อมูลทุกขั้นตอน ตรวจสอบย้อนหลังได้ง่าย' : 'The system records all steps, making it easy to trace back.') ?></p>
                    
                    <h3 class="!text-[#002B7F] !mt-6 !text-[17px] !font-bold"><?= e(getCurrentLang() === 'th' ? '4. รองรับการเติบโตของธุรกิจในอนาคต' : '4. Support future business growth') ?></h3>
                    <p class="!mt-2 !text-slate-600 pl-5"><?= e(getCurrentLang() === 'th' ? 'ระบบที่ยืดหยุ่น รองรับการขยายตัวและการเปลี่ยนแปลงทางธุรกิจ' : 'A flexible system that supports expansion and business changes.') ?></p>

                    <h2 id="toc-10" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'บริษัทไหนที่เหมาะกับระบบ ERP' : 'Which companies are suitable for an ERP system?') ?></h2>
                    <ul class="list-disc !text-[#002B7F] marker:text-[#002B7F] !mt-6">
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ธุรกิจขนาดกลางถึงขนาดใหญ่' : 'Medium to large businesses') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ธุรกิจที่มีหลายสาขาหรือหลายแผนก' : 'Businesses with multiple branches or departments') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ธุรกิจที่ต้องการข้อมูลเชิงลึกเพื่อการตัดสินใจ' : 'Businesses needing in-depth data for decision-making') ?></span></li>
                        <li><span class="text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ธุรกิจที่ต้องการลดต้นทุนและเพิ่มประสิทธิภาพการดำเนินงาน' : 'Businesses wanting to reduce costs and increase operational efficiency') ?></span></li>
                    </ul>

                    <h2 id="toc-11" class="!text-[#0663F6] !mt-16"><?= e(getCurrentLang() === 'th' ? 'สรุปท้ายบท' : 'Conclusion') ?></h2>
                    <p class="!mt-4 !text-slate-600"><?= e(getCurrentLang() === 'th' ? 'ระบบ ERP คือเครื่องมือสำคัญในการบริหารจัดการองค์กรยุคดิจิทัล ช่วยรวมทุกกระบวนการเข้าด้วยกัน เพิ่มประสิทธิภาพ ลดต้นทุน และช่วยให้ธุรกิจเติบโตได้อย่างมั่นคงและยั่งยืน การเลือกใช้ระบบที่เหมาะสม พร้อมพาร์ทเนอร์ที่เชี่ยวชาญ จะช่วยให้การลงทุนใน ERP เกิดผลตอบแทนสูงสุด' : 'The ERP system is a crucial tool for managing organizations in the digital age. It helps unify all processes, increases efficiency, cuts costs, and supports stable and sustainable business growth. Choosing the right system with expert partners will maximize the return on your ERP investment.') ?></p>
                    
                </article>
            </div>
            
            <!-- Sidebar Right: Related Articles -->
            <div class="lg:col-span-4 relative h-full">
                <div class="bg-white rounded-[2rem] p-5 lg:p-6 border border-slate-100 shadow-sm sticky top-[100px] h-max z-20 max-h-[calc(100vh-140px)] overflow-y-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" style="position: sticky; top: 100px;">
                    <h4 class="text-[19px] font-bold text-[#0663F6] mb-4">
                        <?= e(getCurrentLang() === 'th' ? 'บทความที่เกี่ยวข้อง' : 'Related Articles') ?>
                    </h4>
                    <div class="space-y-4">
                        
                        <!-- Mockup Related Article 1 -->
                        <div class="bg-white border border-slate-100 rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <img src="<?= e(asset_url('images/story.png')) ?>" class="w-full aspect-[16/9] object-cover" alt="AI Robot">
                            <div class="p-5">
                                <h5 class="text-[16px] font-bold text-[#0663F6] mb-2"><?= e(getCurrentLang() === 'th' ? 'AI ช่วยธุรกิจได้จริงอย่างไร?' : 'How does AI actually help businesses?') ?></h5>
                                <p class="text-[13px] text-slate-500 mb-4 line-clamp-2 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'รวมตัวอย่างการใช้ AI ในงานต่าง ๆ เช่น วิเคราะห์ข้อมูล บริการลูกค้า การตลาด และการผลิต...' : 'Examples of using AI in various tasks such as data analysis, customer service, marketing, and production...') ?></p>
                                <div class="text-right">
                                    <a href="#" class="text-[#0663F6] text-[13px] font-bold hover:underline"><?= e(getCurrentLang() === 'th' ? 'อ่านเพิ่มเติม &rarr;' : 'Read more &rarr;') ?></a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mockup Related Article 2 -->
                        <div class="bg-white border border-slate-100 rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <img src="<?= e(asset_url('images/story.png')) ?>" class="w-full aspect-[16/9] object-cover" alt="AI Robot">
                            <div class="p-5">
                                <h5 class="text-[16px] font-bold text-[#0663F6] mb-2"><?= e(getCurrentLang() === 'th' ? 'AI ช่วยธุรกิจได้จริงอย่างไร?' : 'How does AI actually help businesses?') ?></h5>
                                <p class="text-[13px] text-slate-500 mb-4 line-clamp-2 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'รวมตัวอย่างการใช้ AI ในงานต่าง ๆ เช่น วิเคราะห์ข้อมูล บริการลูกค้า การตลาด และการผลิต...' : 'Examples of using AI in various tasks such as data analysis, customer service, marketing, and production...') ?></p>
                                <div class="text-right">
                                    <a href="#" class="text-[#0663F6] text-[13px] font-bold hover:underline"><?= e(getCurrentLang() === 'th' ? 'อ่านเพิ่มเติม &rarr;' : 'Read more &rarr;') ?></a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


