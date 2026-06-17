<?php

declare(strict_types=1);

$errors = $errors ?? [];
$submitted = $submitted ?? false;
$form = $form ?? [];

$contactTitle = $ctitle ?? 'พร้อมเริ่มต้นโครงการของคุณแล้วหรือยัง?';
$contactSubtitle = $csubtitle ?? 'พูดคุยกับทีมเรา วันนี้<br>รับคำปรึกษาฟรี ไม่มีค่าใช้จ่าย';
$contactButtonText = $cbuttonText ?? 'ติดต่อเรา';
$contactButtonUrl = $cbuttonUrl ?? '/contact';

?>

<section class="bg-white py-12 lg:py-12 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="relative w-full rounded-[2rem] p-8 md:p-12 lg:p-14 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center overflow-hidden shadow-xl">
            <div class="absolute inset-0 z-0 rounded-[2rem] overflow-hidden">
    <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="City Network Overlay" class="w-full h-full opacity-80 object-cover">
    
<div class="absolute inset-0 bg-gradient-to-r from-[#043B94]/90 via-[#054FC5]/80 to-white/95"></div>
</div>

            <div class="relative z-10 lg:col-span-5 flex flex-col items-start text-left">
                <h2 class="text-3xl md:text-4xl lg:text-[2.5rem] font-black leading-tight text-white tracking-tight">
                    <?= e($contactTitle) ?>
                </h2>

                <p class="mt-4 text-blue-100/90 text-sm md:text-base leading-relaxed font-medium">
                    <?= $contactSubtitle ?>
                </p>
            </div>

            <div class="relative z-10 lg:col-span-7 w-full">
                <div class="rounded-3xl bg-white p-6 md:p-8 shadow-2xl border border-slate-50">
                    
                    <?php if ($submitted): ?>
                        <div class="text-center py-12">
                            <div class="w-14 h-14 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-dark mb-1">ส่งข้อมูลสำเร็จ</h3>
                            <p class="text-slate-500 text-xs md:text-sm">ทีมงานผู้เชี่ยวชาญจะติดต่อกลับหาคุณโดยเร็วที่สุด</p>
                        </div>
                    <?php else: ?>
                        <form method="post" class="space-y-4">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="text" name="name" placeholder="ชื่อ - นามสกุล" value="<?= e($form['name'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary">

                                <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" value="<?= e($form['phone'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <input type="email" name="email" placeholder="อีเมล" value="<?= e($form['email'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <textarea name="message" rows="4" placeholder="รายละเอียด" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary resize-none"><?= e($form['message'] ?? '') ?></textarea>
                            </div>

                            <div class="flex items-start gap-2.5 pt-1">
                                <input type="checkbox" id="form-privacy" name="privacy_agreed" required class="mt-0.5 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer">
                                <label for="form-privacy" class="text-xs text-slate-500 leading-relaxed cursor-pointer select-none">
                                    ยินยอมรับ <a href="#" class="text-primary hover:underline">นโยบายความเป็นส่วนตัว</a> เพื่อการติดต่อกลับและนำเสนอข้อมูลที่เกี่ยวข้อง
                                </label>
                            </div>

                            <?php if ($errors !== []): ?>
                                <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                            <?php endif; ?>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 bg-primary hover:bg-blue-300 text-white font-bold text-sm rounded-full flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 transition-all cursor-pointer">
                                    ส่งข้อมูล
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>

                        </form>
                    <?php endif; ?>
                    
                </div>
            </div>

        </div>

    </div>
</section>