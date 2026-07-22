<?php
declare(strict_types=1);
/**
 * Call-to-action section component with inline contact form.
 */
$errors = $errors ?? [];
$submitted = $submitted ?? false;
$form = $form ?? [];
$contactTitle = $ctitle ?? (getCurrentLang() === 'th' ? 'พร้อมเริ่มต้นโครงการของคุณแล้วหรือยัง?' : 'Ready to start your project?');
$contactSubtitle = $csubtitle ?? (getCurrentLang() === 'th' ? 'พูดคุยกับทีมเราวันนี้<br>รับคำปรึกษาฟรี ไม่มีค่าใช้จ่าย' : 'Talk to our team today<br>Get a free consultation, no hidden fees');
$contactButtonText = $cbuttonText ?? t('common.nav_contact');
$contactButtonUrl = $cbuttonUrl ?? '/contact';
?>
<section class="bg-white py-10 lg:py-10 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="relative w-full rounded-[2rem] p-8 md:p-12 lg:p-14 grid grid-cols-1 lg:grid-cols-12 gap-10 items-start overflow-hidden shadow-xl">
            <div class="absolute inset-0 z-0 rounded-[2rem] overflow-hidden">
                <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="City Network Overlay" class="w-full h-full opacity-80 object-cover">
                <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(1, 47, 122, 0.95) 0%, rgba(0, 79, 207, 0.6) 100%);"></div>
            </div>
            <div class="relative z-10 lg:col-span-5 flex flex-col items-start text-left lg:pt-2">
                <div class="mb-4 relative">
                    <span class="text-white font-black text-4xl md:text-5xl lg:text-[3rem] tracking-tight block">
                        <?= e(t('common.nav_contact')) ?>
                    </span>
                    <div class="w-12 h-[3px] bg-white mt-3"></div>
                </div>
                    <span class="mt-4 text-white text-base md:text-lg leading-relaxed font-medium">
                        <?= e($contactTitle) ?>
                    </span>
                <p class="mt-4 text-white text-base md:text-lg leading-relaxed font-medium">
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
                            <h3 class="text-lg font-bold text-dark mb-1"><?= e(getCurrentLang() === 'th' ? 'ส่งข้อมูลสำเร็จ' : 'Submission Successful') ?></h3>
                            <p class="text-slate-500 text-xs md:text-sm"><?= e(getCurrentLang() === 'th' ? 'ทีมงานผู้เชี่ยวชาญจะติดต่อกลับหาคุณโดยเร็วที่สุด' : 'Our experts will get back to you as soon as possible.') ?></p>
                        </div>
                    <?php else: ?>
                        <style>
                            .custom-placeholder::placeholder {
                                color: #043B94 !important;
                                opacity: 0.9;
                            }
                        </style>
                        <form method="post" class="space-y-4">
                            <div id="desktop-name-wrapper">
                                <input type="text" id="name_desktop" name="name" placeholder="<?= e(t('common.form_label_fullname')) ?>" value="<?= e($form['name'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div id="mobile-name-wrapper" class="space-y-4 hidden">
                                <input type="text" id="name_mobile_first" name="firstname" placeholder="<?= e(getCurrentLang() === 'th' ? 'ชื่อ' : 'First Name') ?>" value="<?= e($form['firstname'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                <input type="text" id="name_mobile_last" name="lastname" placeholder="<?= e(getCurrentLang() === 'th' ? 'สกุล' : 'Last Name') ?>" value="<?= e($form['lastname'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <input type="text" inputmode="numeric" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?>" value="<?= e($form['phone'] ?? '') ?>" required maxlength="10" pattern="\d{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                <input type="email" name="email" placeholder="<?= e(t('common.form_label_email')) ?>" value="<?= e($form['email'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <script>
                                function updateCtaFormLayout() {
                                    const isDesktop = window.innerWidth >= 768;
                                    const nameDesktop = document.getElementById('name_desktop');
                                    const nameDesktopWrap = document.getElementById('desktop-name-wrapper');
                                    const nameMobileFirst = document.getElementById('name_mobile_first');
                                    const nameMobileLast = document.getElementById('name_mobile_last');
                                    const nameMobileWrap = document.getElementById('mobile-name-wrapper');
                                    if (isDesktop) {
                                        nameDesktop.disabled = false;
                                        nameDesktopWrap.style.display = 'block';
                                        nameMobileFirst.disabled = true;
                                        nameMobileLast.disabled = true;
                                        nameMobileWrap.style.display = 'none';
                                    } else {
                                        nameDesktop.disabled = true;
                                        nameDesktopWrap.style.display = 'none';
                                        nameMobileFirst.disabled = false;
                                        nameMobileLast.disabled = false;
                                        nameMobileWrap.style.display = 'block';
                                    }
                                }
                                window.addEventListener('resize', updateCtaFormLayout);
                                window.addEventListener('DOMContentLoaded', updateCtaFormLayout);
                                // Run immediately in case DOM is already loaded
                                updateCtaFormLayout();
                            </script>
                            <div>
                                <textarea name="message" rows="4" placeholder="<?= e(t('common.form_label_details')) ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none"><?= e($form['message'] ?? '') ?></textarea>
                            </div>
                            <style>
                                .privacy-text { color: #022862 !important; }
                            </style>
                            <div class="flex items-start gap-2.5 pt-1">
                                <input type="checkbox" id="form-privacy" name="privacy_agreed" required class="mt-0.5 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer">
                                <label for="form-privacy" class="text-sm leading-relaxed cursor-pointer select-none">
                                    <span class="privacy-text"><?= e(t('common.form_consent_prefix')) ?></span> <a href="#" class="text-primary hover:underline"><?= e(t('common.form_consent_privacy_policy')) ?> <?= e(t('common.form_consent_terms_suffix')) ?></a>
                                </label>
                            </div>
                            <?php if ($errors !== []): ?>
                                <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                            <?php endif; ?>
                            <style>
                                @media (min-width: 768px) { .desktop-btn-left { justify-content: flex-start !important; } }
                            </style>
                            <div class="pt-2 flex justify-center desktop-btn-left">
                                <button type="submit" class="px-8 py-3.5 bg-primary hover:bg-blue-600 text-white font-bold text-base rounded-full flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 transition-all cursor-pointer">
                                    <?= e(t('erp.cta_submit') !== 'erp.cta_submit' ? t('erp.cta_submit') : (getCurrentLang() === 'th' ? 'ส่งข้อมูล' : 'Submit')) ?>
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