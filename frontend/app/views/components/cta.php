<?php

declare(strict_types=1);

/**
 * Call-to-action section component with inline contact form.
 */

$errors = $errors ?? [];
$submitted = $submitted ?? false;
$form = $form ?? [];

$recaptchaSiteKey = '6Lcf_pAtAAAAAOVhatPPwrHSYXeb_0J4yXf5BrRO';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (isset($_POST['pdpa_agreed']) || isset($_POST['privacy_agreed']))) {
    $form = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'firstname' => trim((string) ($_POST['firstname'] ?? '')),
        'lastname' => trim((string) ($_POST['lastname'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];
    
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptchaResponse)) {
        $errors[] = getCurrentLang() === 'th' ? 'กรุณาเลือกช่องยืนยันตัวตน (I\'m not a robot)' : 'Please verify that you are not a robot';
    } else {
        $submitted = true;
    }
}

$contactTitle = $ctitle ?? (getCurrentLang() === 'th' ? 'พร้อมเริ่มต้นโครงการของคุณแล้วหรือยัง?' : 'Ready to start your project?');
$contactSubtitle = $csubtitle ?? (getCurrentLang() === 'th' ? 'พูดคุยกับทีมเราวันนี้<br>รับคำปรึกษาฟรี ไม่มีค่าใช้จ่าย' : 'Talk to our team today<br>Get a free consultation, no hidden fees');
$contactButtonText = $cbuttonText ?? t('common.nav_contact');
$contactButtonUrl = $cbuttonUrl ?? '/contact';

?>

<section class="bg-white py-10 lg:py-10 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="relative w-full rounded-[2rem] p-8 md:p-12 lg:p-14 grid grid-cols-1 lg:grid-cols-12 gap-10 items-start overflow-hidden shadow-xl cta-main-container">
            <div class="absolute inset-0 z-0 rounded-[2rem] overflow-hidden">
                <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="City Network Overlay" class="w-full h-full opacity-80 object-cover">
                <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(1, 47, 122, 0.95) 0%, rgba(0, 79, 207, 0.6) 100%);"></div>
            </div>

            <div class="relative z-10 lg:col-span-5 flex flex-col items-start text-left lg:pt-2 cta-left-col">
                <div class="mb-4 relative">
                    <span class="text-white font-black text-4xl md:text-5xl lg:text-[3rem] tracking-tight block cta-main-title">
                        <?= e(t('common.nav_contact')) ?>
                    </span>
                    <div class="w-12 h-[3px] bg-white mt-3"></div>
                </div>
                    <span class="mt-4 text-white text-base md:text-lg leading-relaxed font-medium cta-main-subtitle">
                        <?= e($contactTitle) ?>
                    </span>
                
                <p class="mt-4 text-white text-base md:text-lg leading-relaxed font-medium cta-main-subtitle">
                    <?= $contactSubtitle ?>
                </p>
            </div>

            <div class="relative z-10 lg:col-span-7 w-full">
                <div class="rounded-3xl bg-white p-6 md:p-8 shadow-2xl border border-slate-50 cta-white-card">
                    
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
                            
                            /* iPad Mini (760px - 1024px) Compact Scaling for CTA Consultation Section */
                            @media (min-width: 760px) and (max-width: 1024px) {
                                .cta-main-container {
                                    padding: 2rem 2rem !important;
                                    gap: 1.5rem !important;
                                }
                                .cta-main-title {
                                    font-size: 2.25rem !important;
                                }
                                .cta-main-subtitle {
                                    font-size: 0.95rem !important;
                                    line-height: 1.45 !important;
                                }
                                .cta-white-card {
                                    padding: 1.25rem 1.5rem !important;
                                    border-radius: 1.5rem !important;
                                }
                                .cta-form {
                                    gap: 0.5rem !important;
                                }
                                .cta-input {
                                    padding: 0.5rem 0.75rem !important;
                                    font-size: 0.85rem !important;
                                    border-radius: 0.65rem !important;
                                }
                                .cta-textarea {
                                    padding: 0.5rem 0.75rem !important;
                                    font-size: 0.85rem !important;
                                    border-radius: 0.65rem !important;
                                    min-height: 3.25rem !important;
                                    max-height: 4rem !important;
                                    rows: 2 !important;
                                }
                                .cta-pdpa-box {
                                    max-height: 5rem !important;
                                    padding: 0.5rem 0.75rem !important;
                                    font-size: 0.725rem !important;
                                    line-height: 1.4 !important;
                                    margin-top: 0.35rem !important;
                                    margin-bottom: 0.25rem !important;
                                }
                                .cta-consent-label {
                                    font-size: 0.75rem !important;
                                    line-height: 1.35 !important;
                                }
                                .cta-recaptcha-wrap {
                                    transform: scale(0.82);
                                    transform-origin: left center;
                                    padding-top: 0.25rem !important;
                                    padding-bottom: 0 !important;
                                    margin-bottom: -0.5rem !important;
                                }
                                .cta-submit-btn {
                                    padding: 0.5rem 1.75rem !important;
                                    font-size: 0.875rem !important;
                                }
                            }
                        </style>
                        <form method="post" class="space-y-4 cta-form">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" id="contact_firstname_cta" name="firstname" placeholder="<?= e(t('common.form_label_firstname')) ?>" value="<?= e($form['firstname'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary cta-input">

                                <input type="text" id="contact_lastname_cta" name="lastname" placeholder="<?= e(t('common.form_label_lastname')) ?>" value="<?= e($form['lastname'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary cta-input">
                            </div>

                            <div>
                                <input type="text" id="contact_company_cta" name="company" placeholder="<?= e(t('common.form_label_company_optional')) ?>" value="<?= e($form['company'] ?? '') ?>" required maxlength="100"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary cta-input">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" inputmode="numeric" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?>" value="<?= e($form['phone'] ?? '') ?>" required maxlength="10" pattern="\d{9,10}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary cta-input">

                                <input type="email" name="email" placeholder="<?= e(t('common.form_label_email')) ?>" value="<?= e($form['email'] ?? '') ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary cta-input">
                            </div>

                            <div>
                                <textarea name="message" rows="4" placeholder="<?= e(t('common.form_label_details')) ?>" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none cta-textarea"><?= e($form['message'] ?? '') ?></textarea>
                            </div>

                            <!-- Privacy Policy Scrollable Box -->
                            <div id="privacy_policy_box_cta" class="mt-4 mb-2 p-4 md:p-5 rounded-xl border border-slate-200 bg-slate-50 overflow-y-auto max-h-48 text-sm text-slate-600 leading-relaxed custom-scrollbar shadow-inner text-left cta-pdpa-box">
                                <h4 class="font-bold text-slate-800 mb-2">นโยบายความเป็นส่วนตัว (Privacy Policy)</h4>
                                <p class="mb-4">
                                    WEBPARK Co., Ltd. ("เรา" หรือ "WebPark") ในฐานะผู้ควบคุมข้อมูลส่วนบุคคล (Data Controller) ตระหนักและให้ความสำคัญอย่างยิ่งต่อการคุ้มครองข้อมูลส่วนบุคคลและสิทธิความเป็นส่วนตัวของท่าน นโยบายฉบับนี้จัดทำขึ้นตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) เพื่อชี้แจงรายละเอียดเกี่ยวกับการเก็บรวบรวม ใช้ เปิดเผยข้อมูล และการใช้คุกกี้ บนเว็บไซต์ webpark.co.th ทั้งหมด
                                </p>
                                
                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">1</span> 
                                    ขอบเขตข้อมูลส่วนบุคคลที่เราเก็บรวบรวม
                                </h5>
                                <p class="mb-2">เราเก็บรวบรวมข้อมูลส่วนบุคคลของท่านผ่านการใช้งานเว็บไซต์ในกรณีต่างๆ เท่าที่จำเป็นดังนี้:</p>
                                <ul class="list-disc pl-5 mb-4 space-y-1">
                                    <li>ชื่อ-นามสกุล, เบอร์โทรศัพท์, และอีเมล ที่ท่านกรอกผ่านแบบฟอร์มติดต่อเรา</li>
                                    <li>ข้อมูลองค์กรหรือบริษัทของท่าน (หากมี)</li>
                                    <li>รายละเอียดข้อความหรือความต้องการที่ท่านส่งถึงเรา</li>
                                </ul>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">2</span> 
                                    วัตถุประสงค์ในการเก็บรวบรวมข้อมูล
                                </h5>
                                <p class="mb-4">
                                    ข้อมูลที่ท่านให้จะถูกนำไปใช้เพื่อติดต่อกลับ นำเสนอบริการที่ตรงกับความต้องการของท่าน และปรับปรุงประสิทธิภาพของเว็บไซต์เท่านั้น เราจะไม่มีการเปิดเผยข้อมูลของท่านแก่บุคคลที่สามโดยไม่ได้รับอนุญาต
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">3</span> 
                                    การเปิดเผยข้อมูลแก่บุคคลที่สาม
                                </h5>
                                <p class="mb-4">
                                    เราจะไม่ขาย ให้เช่า หรือเปิดเผยข้อมูลส่วนบุคคลของท่านให้แก่บุคคลภายนอก เว้นแต่กรณีที่จำเป็นเพื่อการให้บริการแก่ท่าน (เช่น ผู้ให้บริการระบบคลาวด์/เซิร์ฟเวอร์ที่ปลอดภัย หรือผู้ให้บริการจัดส่งเอกสาร) หรือในกรณีที่กฎหมายบังคับให้เปิดเผยเท่านั้น
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">4</span> 
                                    ระยะเวลาจัดเก็บและการรักษาความปลอดภัย
                                </h5>
                                <p class="mb-4">
                                    เราจะจัดเก็บข้อมูลส่วนบุคคลของท่านไว้เป็นเวลาตลอดระยะเวลาที่ให้บริการ เพื่อบรรลุวัตถุประสงค์ตามที่แจ้งไว้ โดยเราใช้มาตรการรักษาความปลอดภัยทางเทคนิคที่ได้มาตรฐาน (เช่น การเข้ารหัสข้อมูล SSL) เพื่อปกป้องข้อมูลของท่านจากการเข้าถึง แก้ไข หรือเปิดเผยโดยไม่ได้รับอนุญาต
                                </p>

                                <h5 class="font-bold text-slate-800 mt-4 mb-2 flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-primary text-white flex items-center justify-center text-xs">5</span> 
                                    สิทธิของเจ้าของข้อมูลและช่องทางการติดต่อ
                                </h5>
                                <p class="mb-2">
                                    ท่านมีสิทธิ์ตามกฎหมายในการขอเข้าถึง ขอสำเนา ขอแก้ไข หรือขอให้ลบข้อมูลส่วนบุคคลของท่านได้ทุกเมื่อ หากท่านต้องการใช้สิทธิ์ดังกล่าว หรือมีข้อสงสัยเกี่ยวกับนโยบายนี้ สามารถติดต่อเราได้ที่:
                                </p>
                                <ul class="list-none mb-4 space-y-1">
                                    <li><strong>อีเมล:</strong> oraphan@webpark.co.th</li>
                                    <li><strong>โทรศัพท์:</strong> 095-539-2666</li>
                                </ul>
                            </div>
                            <style>
                                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                                .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
                                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                            </style>

                            <!-- PDPA Consent Checkbox -->
                            <div class="flex items-start gap-3 pt-2">
                                <input type="checkbox" id="privacy_consent_checkbox_cta" name="pdpa_agreed" value="1" required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all duration-200">
                                <label for="privacy_consent_checkbox_cta" class="text-sm md:text-base leading-relaxed cursor-pointer select-none text-left cta-consent-label">
                                    <span style="color: #022862;"><?= e(t('common.form_consent_prefix')) ?></span> <a href="#" id="ctaPrivacyModalTrigger" style="color: #0663F6;" class="hover:underline transition-colors duration-200"><?= e(t('common.form_consent_privacy_policy')) ?></a> <span style="color: #0663F6;"><?= e(t('common.form_consent_terms_suffix')) ?></span>
                                </label>
                            </div>

                            <!-- Google reCAPTCHA v2 Widget -->
                            <div class="pt-2 pb-1 flex justify-center sm:justify-start cta-recaptcha-wrap">
                                <div class="g-recaptcha" data-sitekey="<?= e($recaptchaSiteKey) ?>"></div>
                            </div>

                            <?php if ($errors !== []): ?>
                                <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                            <?php endif; ?>

                            <style>
                                @media (min-width: 768px) { .desktop-btn-left { justify-content: flex-start !important; } }
                            </style>
                            <div class="pt-2 flex justify-center desktop-btn-left">
                                <button type="submit" id="cta_submit_btn" disabled class="px-8 py-3.5 bg-primary hover:bg-blue-600 text-white font-bold text-base rounded-full flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none cta-submit-btn">
                                    <?= e(t('erp.cta_submit') !== 'erp.cta_submit' ? t('erp.cta_submit') : (getCurrentLang() === 'th' ? 'ส่งข้อมูล' : 'Submit')) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>

                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const privacyCbCta = document.getElementById('privacy_consent_checkbox_cta');
                                const submitBtnCta = document.getElementById('cta_submit_btn');
                                const policyBoxCta = document.getElementById('privacy_policy_box_cta');
                                const privacyTrigger = document.getElementById('ctaPrivacyModalTrigger');

                                // 1. Toggle submit button disabled state
                                function updateCtaSubmitBtnState() {
                                    if (privacyCbCta && submitBtnCta) {
                                        submitBtnCta.disabled = !privacyCbCta.checked;
                                    }
                                }

                                if (privacyCbCta) {
                                    privacyCbCta.addEventListener('change', updateCtaSubmitBtnState);
                                    updateCtaSubmitBtnState();
                                }

                                // 2. Require scrolling to bottom to unlock PDPA checkbox
                                if (privacyCbCta && policyBoxCta && !privacyCbCta.checked) {
                                    const cbWrapper = privacyCbCta.parentElement;
                                    privacyCbCta.disabled = true;
                                    cbWrapper.style.opacity = '0.6';
                                    cbWrapper.style.cursor = 'not-allowed';
                                    
                                    function checkCtaScroll() {
                                        if (policyBoxCta.scrollHeight - policyBoxCta.scrollTop <= policyBoxCta.clientHeight + 25) {
                                            privacyCbCta.disabled = false;
                                            cbWrapper.style.opacity = '1';
                                            cbWrapper.style.cursor = 'pointer';
                                            policyBoxCta.removeEventListener('scroll', checkCtaScroll);
                                        }
                                    }
                                    
                                    policyBoxCta.addEventListener('scroll', checkCtaScroll, { passive: true });
                                    setTimeout(checkCtaScroll, 100);

                                    // If user taps the disabled checkbox area on mobile, auto-scroll to bottom to assist them
                                    cbWrapper.addEventListener('click', function() {
                                        if (privacyCbCta.disabled) {
                                            policyBoxCta.scrollTo({ top: policyBoxCta.scrollHeight, behavior: 'smooth' });
                                            setTimeout(checkCtaScroll, 300);
                                        }
                                    });
                                }

                                // 3. Link triggers modal if footer modal exists
                                if (privacyTrigger) {
                                    privacyTrigger.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        const footerPrivacyBtn = document.getElementById('footerPrivacyPolicyBtn');
                                        if (footerPrivacyBtn) {
                                            footerPrivacyBtn.click();
                                        }
                                    });
                                }
                            });
                        </script>
                    <?php endif; ?>
                    
                </div>
            </div>

        </div>

    </div>
</section>