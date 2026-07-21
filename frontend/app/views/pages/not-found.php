<?php
declare(strict_types=1);
/**
 * 404 not found page view.
 */
?>
<style>
    @keyframes floatBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-bounce {
        animation: floatBounce 4s ease-in-out infinite;
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 10px 25px -5px rgba(6, 99, 246, 0.3); }
        50% { box-shadow: 0 15px 35px 5px rgba(6, 99, 246, 0.5); }
    }
    .animate-pulse-glow {
        animation: pulseGlow 3s ease-in-out infinite;
    }
</style>
<section class="bg-white py-16 lg:py-24 text-center overflow-hidden">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <div class="animate-float-bounce mb-4 text-7xl md:text-9xl font-black tracking-tight text-blue-600">404</div>
        <h1 class="text-[clamp(2.25rem,5vw,4.75rem)] font-extrabold leading-none tracking-[-0.03em] text-slate-900 mb-4">
            <?= e(getCurrentLang() === 'th' ? 'ไม่พบหน้าที่คุณต้องการ' : 'Page Not Found') ?>
        </h1>
        <p class="mx-auto mt-4 max-w-2xl text-base md:text-lg leading-7 text-slate-600">
            <?= e(getCurrentLang() === 'th' ? 'ขออภัย หน้าเว็บที่คุณกำลังค้นหาอาจถูกย้าย ลบออก หรือไม่เคยมีอยู่จริง' : 'Sorry, the page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') ?>
        </p>
        <div class="mt-8">
            <a class="animate-pulse-glow inline-flex items-center justify-center gap-2 rounded-full bg-primary hover:bg-blue-700 px-8 py-3.5 font-bold text-white text-base shadow-lg transition-all duration-300 hover:-translate-y-1" href="<?= e(route_url('/')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <?= e(getCurrentLang() === 'th' ? 'กลับไปยังหน้าแรก' : 'Back to Home') ?>
            </a>
        </div>
    </div>
</section>