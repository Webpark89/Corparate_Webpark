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
    @media (min-width: 1025px) {
        .desktop-subpage-hero-h1 {
            font-size: 5.5rem !important;
            line-height: 1.1 !important;
        }
        .desktop-subpage-hero-p {
            font-size: 1.25rem !important;
            line-height: 1.75 !important;
            max-width: 34rem !important;
        }
    }
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
                            <span class="text-slate-400">Creative / Design</span>
                        </li>
                    </ol>
                </nav>
                <h1 class="animate-fade-up delay-200 leading-snug mb-6 tracking-tight">
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-slate-500 mb-2 desktop-subpage-hero-h1">
                        <?= e(getCurrentLang() === 'th' ? 'งานออกแบบสร้างสรรค์' : 'Creative / Design') ?>
                    </span>
                    <span class="block text-3xl md:text-4xl lg:text-[44px] font-bold text-[#022862] desktop-subpage-hero-h1">
                        <?= e(getCurrentLang() === 'th' ? 'สะกดสายตาและเล่าเรื่องแบรนด์' : 'Captivate Eyes and Tell Brand Stories') ?>
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
                <p class="animate-fade-up delay-400 mt-6 text-[#022862] text-lg md:text-xl leading-relaxed max-w-lg mb-10 font-medium desktop-subpage-hero-p">
                    <?= e(getCurrentLang() === 'th' ? 'สร้างสรรค์ภาพลักษณ์แบรนด์ที่โดดเด่นและมีเอกลักษณ์ ตั้งแต่ CI Design, UX/UI, ไปจนถึง Motion Graphics เพื่อสร้างประสบการณ์ที่น่าประทับใจให้กับลูกค้า' : 'Create outstanding and unique brand identities, from CI Design, UX/UI, to Motion Graphics to deliver impressive customer experiences.') ?>
                </p>
            </div>
            <!-- Right Column: Image -->
            <div class="animate-fade-up delay-300 relative w-full rounded-[2rem] overflow-hidden shadow-2xl">
                <img src="<?= e(asset_url('images/creative-design-bg.jpg')) ?>" alt="Creative Design Illustration" class="w-full h-auto object-cover aspect-[4/3] hover:scale-105 transition-transform duration-700" onerror="this.src='<?= e(asset_url('images/story.png')) ?>'">
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
                    <h2 id="toc-1" class="!text-[#0663F6] !mt-0"><?= e(getCurrentLang() === 'th' ? 'การออกแบบเชิงกลยุทธ์ที่สร้างคุณค่าให้ธุรกิจ' : 'Strategic Design to Build Business Value') ?></h2>
                    <p><?= e(getCurrentLang() === 'th' ? 'งานออกแบบไม่ใช่แค่เรื่องความสวยงาม แต่เป็นเครื่องมือในการสื่อสารสารัตถะ จุดยืน และคุณค่าขององค์กรไปยังกลุ่มเป้าหมาย งานออกแบบที่ดีจะช่วยเพิ่มความน่าเชื่อถือ ตอกย้ำความเป็นผู้นำ และทำให้ธุรกิจแตกต่างจากคู่แข่งอย่างชัดเจน' : 'Design is not just about aesthetics, but a tool to communicate corporate essence, positioning, and values to target audiences. Good design elevates credibility, reinforces leadership, and distinguishes business from competitors.') ?></p>
                    
                    <h2 id="toc-2" class="!text-[#0663F6]"><?= e(getCurrentLang() === 'th' ? 'บริการงานออกแบบสร้างสรรค์ของเรา' : 'Our Creative & Design Services') ?></h2>
                    <ul class="list-disc marker:text-[#0663F6] space-y-2">
                        <li><strong>Brand Identity & CI Design:</strong> ออกแบบโลโก้ แบรนด์ไกด์ไลน์ และอัตลักษณ์แบรนด์ให้มีทิศทางเดียวกัน</li>
                        <li><strong>UI/UX Design:</strong> ออกแบบส่วนต่อประสานและประสบการณ์ผู้ใช้งานสำหรับเว็บไซต์และโมบายแอป</li>
                        <li><strong>Marketing Collaterals:</strong> ออกแบบสื่อการขาย โบรชัวร์ บรรจุภัณฑ์ และกราฟิกสำหรับสื่อโซเชียลมีเดีย</li>
                        <li><strong>Motion Graphics:</strong> สร้างสรรค์วิดีโอแอนิเมชันเพื่ออธิบายบริการหรือพรีเซนต์ภาพลักษณ์องค์กร</li>
                    </ul>
                </article>
            </div>
            <!-- Sidebar / Widgets -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-8">
                    <!-- CTA Box -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-[2rem] p-8 shadow-md relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-2xl font-bold mb-4"><?= e(getCurrentLang() === 'th' ? 'ต้องการยกระดับภาพลักษณ์แบรนด์?' : 'Want to Elevate Brand Identity?') ?></h3>
                            <p class="text-blue-100 text-sm mb-6 leading-relaxed"><?= e(getCurrentLang() === 'th' ? 'เริ่มงานออกแบบแบรนด์และปรึกษากับดีไซเนอร์มืออาชีพ' : 'Start your brand design and consult with professional designers.') ?></p>
                            <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center w-full px-6 py-3 bg-white text-primary font-bold rounded-xl shadow-md hover:bg-blue-50 transition-all duration-300"><?= e(getCurrentLang() === 'th' ? 'ติดต่อทีมงานสร้างสรรค์' : 'Contact Creative Team') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
