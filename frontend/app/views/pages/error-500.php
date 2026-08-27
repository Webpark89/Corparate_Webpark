<?php
declare(strict_types=1);
/**
 * 5xx server error page view (supports 500, 502, 503, 504).
 */
$lang = getCurrentLang();
$isTh = $lang === 'th';

$code = isset($statusCode) ? (int)$statusCode : 500;
if (!in_array($code, [500, 502, 503, 504], true)) {
    $code = 500;
}

$badge = $badgeText ?? ($isTh ? 'เกิดข้อผิดพลาดของระบบ' : 'Server Error');
$heading = $errorHeading ?? ($isTh ? 'เกิดข้อผิดพลาดของเซิร์ฟเวอร์' : 'Internal Server Error');
$description = $errorDescription ?? ($isTh 
    ? 'ขออภัยในความไม่สะดวก ระบบกำลังประสบปัญหาทางเทคนิคชั่วคราว ทีมงานได้รับทราบและกำลังดำเนินการแก้ไข กรุณาลองใหม่อีกครั้ง' 
    : 'Sorry for the inconvenience. Our server encountered an internal technical issue. Our team is working to fix it.');

// Theme colors based on status code (reliable cross-platform styling)
$themeColor = match ($code) {
    502 => '#ea580c', // Orange-600
    503 => '#2563eb', // Blue-600
    504 => '#9333ea', // Purple-600
    default => '#e11d48', // Rose-600
};

$badgeStyle = match ($code) {
    502 => 'background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;',
    503 => 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;',
    504 => 'background-color: #faf5ff; color: #7e22ce; border: 1px solid #f3e8ff;',
    default => 'background-color: #fff1f2; color: #be123c; border: 1px solid #ffe4e6;',
};

$badgeDotStyle = match ($code) {
    502 => 'background-color: #ea580c;',
    503 => 'background-color: #2563eb;',
    504 => 'background-color: #9333ea;',
    default => 'background-color: #e11d48;',
};
?>
<style>
    @keyframes floatBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-bounce {
        animation: floatBounce 4s ease-in-out infinite;
    }
</style>
<section class="bg-white py-16 lg:py-24 text-center overflow-hidden min-h-[70vh] flex items-center justify-center">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-28 relative z-10">
        <!-- Error Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider mb-4" style="<?= $badgeStyle ?>">
            <span class="w-2 h-2 rounded-full animate-ping" style="<?= $badgeDotStyle ?>"></span>
            <?= e($badge) ?>
        </div>

        <!-- 5xx Number with animated style -->
        <div class="animate-float-bounce mb-4 font-black tracking-tight" style="color: <?= $themeColor ?>; font-size: clamp(5rem, 15vw, 8.5rem); line-height: 1; text-shadow: 0 10px 25px rgba(0,0,0,0.06);">
            <?= $code ?>
        </div>

        <h1 class="text-[clamp(2rem,4vw,3.5rem)] font-extrabold leading-tight tracking-[-0.03em] text-slate-900 mb-4">
            <?= e($heading) ?>
        </h1>

        <p class="mx-auto mt-3 max-w-2xl text-base md:text-lg leading-relaxed text-slate-600">
            <?= e($description) ?>
        </p>

        <?php if (!empty($errorMessage) && (defined('ENVIRONMENT') && ENVIRONMENT === 'development')): ?>
            <div class="mt-5 mx-auto max-w-2xl p-4 bg-slate-900 text-rose-300 rounded-xl text-left text-xs font-mono overflow-x-auto shadow-inner">
                <strong>Debug Info:</strong> <?= e((string)$errorMessage) ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <button 
                type="button" 
                onclick="window.location.reload();"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 hover:bg-slate-800 px-7 py-3.5 font-bold text-white text-sm md:text-base shadow-md transition-all duration-300 hover:-translate-y-0.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <?= e($isTh ? 'ลองโหลดใหม่อีกครั้ง' : 'Reload Page') ?>
            </button>

            <a 
                class="inline-flex items-center justify-center gap-2 rounded-full bg-primary hover:bg-blue-700 px-7 py-3.5 font-bold text-white text-sm md:text-base shadow-md transition-all duration-300 hover:-translate-y-0.5" 
                href="<?= e(route_url('/')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <?= e($isTh ? 'กลับไปยังหน้าแรก' : 'Back to Home') ?>
            </a>

            <a 
                class="inline-flex items-center justify-center gap-2 rounded-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 px-7 py-3.5 font-bold text-sm md:text-base shadow-sm transition-all duration-300 hover:-translate-y-0.5" 
                href="<?= e(route_url('/contact')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <?= e($isTh ? 'ติดต่อเรา' : 'Contact Us') ?>
            </a>
        </div>
    </div>
</section>
