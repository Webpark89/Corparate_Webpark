<?php

declare(strict_types=1);
?>
<section class="bg-white py-16 lg:py-24 text-center">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <div class="mb-4 text-[0.6875rem] font-extrabold uppercase tracking-[0.22em] text-brand-teal">404</div>
        <h1 class="text-[clamp(2.25rem,5vw,4.75rem)] font-extrabold leading-none tracking-[-0.03em] text-slate-900">ไม่พบหน้าที่คุณต้องการ</h1>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">ลองกลับไปยังหน้าแรกหรือเลือกเมนูด้านบน</p>
        <a class="mt-6 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brand-cyan to-sky-700 px-7 py-3 font-extrabold text-white shadow-lg shadow-cyan-500/20 transition hover:-translate-y-0.5" href="<?= e(route_url('/')) ?>">Go Home</a>
    </div>
</section>