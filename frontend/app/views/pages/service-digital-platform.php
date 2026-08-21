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
<!-- Top Reading Progress Bar -->
<div id="reading-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 z-[9999] transition-all duration-150 ease-out" style="width: 0%;"></div>
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
                            <a href="<?= e(route_url('/services')) ?>" class="hover:text-primary transition-colors duration-200"><?= e(getCurrentLang() === 'th' ? 'บริการของเรา' : 'Services') ?></a>
                        </li>
                        <li><span class="mx-4">/</span></li>
                        <li aria-current="page">
                            <span class="text-slate-400">Digital Platform</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="animate-fade-up delay-200 leading-snug mb-6 tracking-tight">
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-slate-500 mb-2">
                        <?= e(getCurrentLang() === 'th' ? 'แพลตฟอร์มดิจิทัล' : 'Digital Platform') ?>
                    </span>
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-[#022862]">
                        <?= e(getCurrentLang() === 'th' ? 'ขับเคลื่อนธุรกิจสู่อนาคต' : 'Drive Business to the Future') ?>
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
                    <?= e(getCurrentLang() === 'th' ? 'พัฒนาซอฟต์แวร์และแพลตฟอร์มดิจิทัลที่ตอบโจทย์ธุรกิจแบบครบวงจร ตั้งแต่ Web Application, Mobile App ไปจนถึง Custom SaaS เพื่อเพิ่มขีดความสามารถในการแข่งขัน' : 'Develop software and digital platforms that meet end-to-end business needs, from Web Applications and Mobile Apps to Custom SaaS to increase competitiveness.') ?>
                </p>
            </div>
            <!-- Right Column: Image -->
            <div class="animate-fade-up delay-300 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e(asset_url('images/story.png')) ?>" alt="Digital Platform Illustration" class="w-full h-auto object-cover aspect-[4/3] hover:scale-105 transition-transform duration-700">
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
                    <h2 id="toc-1" class="!text-[#0663F6] !mt-0"><?= e(getCurrentLang() === 'th' ? 'ทำไมองค์กรยุคใหม่ต้องมี Digital Platform ของตัวเอง?' : 'Why Modern Organizations Need Their Own Digital Platform?') ?></h2>
                    <p><?= e(getCurrentLang() === 'th' ? 'การพึ่งพาแพลตฟอร์มสำเร็จรูปหรือการทำงานแบบแมนนวลในยุคปัจจุบันอาจไม่เพียงพอสำหรับการเติบโตที่ยั่งยืน การสร้างแพลตฟอร์มดิจิทัลเฉพาะตัวจะช่วยให้องค์กรควบคุมข้อมูล ปรับแต่งกระบวนการทำงาน และสร้างประสบการณ์ที่ดีที่สุดให้กับลูกค้าได้อย่างอิสระ' : 'Relying on ready-made platforms or manual work today may not be enough for sustainable growth. Building a custom digital platform helps organizations control data, customize workflows, and freely deliver the best experience to customers.') ?></p>
                    
                    <h2 id="toc-2" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'บริการพัฒนาแพลตฟอร์มดิจิทัลของ WEBPARK ครอบคลุมอะไรบ้าง?' : 'What does WEBPARK Digital Platform Development Service cover?') ?></h2>
                    <ul class="list-disc marker:text-[#0663F6] space-y-2">
                        <li><strong>Custom Web Application:</strong> พัฒนาระบบเว็บแอปพลิเคชันสำหรับใช้งานเฉพาะทางในองค์กร</li>
                        <li><strong>Mobile Application Development:</strong> ออกแบบและพัฒนาแอปพลิเคชันทั้งระบบ iOS และ Android</li>
                        <li><strong>SaaS (Software as a Service) Development:</strong> พัฒนาซอฟต์แวร์รูปแบบบอกรับสมาชิกเพื่อต่อยอดธุรกิจ</li>
                        <li><strong>System Integration & API:</strong> เชื่อมต่อระบบการทำงานหลังบ้านให้เชื่อมโยงกันอย่างเป็นระบบ</li>
                    </ul>
                </article>
            </div>
            <!-- Sidebar / Widgets -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-8">
                    <!-- CTA Box -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-[2rem] p-8 shadow-md relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-2xl font-bold mb-4"><?= e(getCurrentLang() === 'th' ? 'สนใจพัฒนา Digital Platform?' : 'Interested in Digital Platform?') ?></h3>
                            <p class="text-blue-100 text-sm mb-6 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'ให้คำปรึกษาโดยทีมวิศวกรผู้เชี่ยวชาญ พร้อมประเมินราคาทันที' : 'Get advice from expert engineers, with instant price estimation.') ?></p>
                            <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center w-full px-6 py-3 bg-white text-primary font-bold rounded-xl shadow-md hover:bg-blue-50 transition-all duration-300"><?= e(getCurrentLang() === 'th' ? 'ติดต่อขอรับคำปรึกษา' : 'Contact for Consultation') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
