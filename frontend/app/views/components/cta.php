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
                            .is-invalid-cta {
                                border-color: #ef4444 !important;
                                background-color: #fef2f2 !important;
                                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
                            }
                            .cta-error-text {
                                color: #ef4444 !important;
                                font-size: 0.75rem !important;
                                font-weight: 500 !important;
                                margin-top: 0.25rem !important;
                                padding-left: 0.25rem !important;
                                text-align: left !important;
                            }
                        </style>
                        <form id="ctaContactForm" method="post" novalidate class="space-y-4">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" id="contact_firstname_cta" name="firstname" placeholder="<?= e(t('common.form_label_firstname')) ?> *" value="<?= e($form['firstname'] ?? '') ?>" maxlength="50"
                                        oninput="this.value = this.value.replace(/[0-9]/g, '');"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                    <p id="contact_firstname_cta_error" class="hidden cta-error-text"></p>
                                </div>

                                <div>
                                    <input type="text" id="contact_lastname_cta" name="lastname" placeholder="<?= e(t('common.form_label_lastname')) ?> *" value="<?= e($form['lastname'] ?? '') ?>" maxlength="50"
                                        oninput="this.value = this.value.replace(/[0-9]/g, '');"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                    <p id="contact_lastname_cta_error" class="hidden cta-error-text"></p>
                                </div>
                            </div>

                            <div>
                                <input type="text" id="contact_company_cta" name="company" placeholder="<?= e(t('common.form_label_company_optional')) ?> *" value="<?= e($form['company'] ?? '') ?>" maxlength="100"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                <p id="contact_company_cta_error" class="hidden cta-error-text"></p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" inputmode="numeric" id="contact_phone_cta" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?> *" value="<?= e($form['phone'] ?? '') ?>" maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                    <p id="contact_phone_cta_error" class="hidden cta-error-text"></p>
                                </div>

                                <div>
                                    <input type="email" id="contact_email_cta" name="email" placeholder="<?= e(t('common.form_label_email')) ?> *" value="<?= e($form['email'] ?? '') ?>" maxlength="255"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary">
                                    <p id="contact_email_cta_error" class="hidden cta-error-text"></p>
                                </div>
                            </div>

                            <div>
                                <textarea id="contact_message_cta" name="message" rows="4" maxlength="250" placeholder="<?= e(t('common.form_label_details')) ?> *"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none"><?= e($form['message'] ?? '') ?></textarea>
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%; margin-top: 4px; padding: 0 4px;">
                                    <p id="contact_message_cta_error" class="hidden cta-error-text" style="margin: 0;"></p>
                                    <span id="contact_cta_msg_counter" style="margin-left: auto; text-align: right; color: #94a3b8; font-size: 0.75rem; white-space: nowrap;">0/250</span>
                                </div>
                            </div>

                            <!-- Privacy Policy Scrollable Box -->
                            <div id="privacy_policy_box_cta" class="mt-4 mb-2 p-4 md:p-5 rounded-xl border border-slate-200 bg-slate-50 overflow-y-auto max-h-48 text-sm text-slate-600 leading-relaxed custom-scrollbar shadow-inner text-left">
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
                            <div class="pt-2">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" id="privacy_consent_checkbox_cta" name="pdpa_agreed" value="1" class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition-all duration-200">
                                    <label for="privacy_consent_checkbox_cta" class="text-sm md:text-base leading-relaxed cursor-pointer select-none text-left">
                                        <span style="color: #022862;"><?= e(t('common.form_consent_prefix')) ?></span> <a href="#" id="ctaPrivacyModalTrigger" style="color: #0663F6;" class="hover:underline transition-colors duration-200"><?= e(t('common.form_consent_privacy_policy')) ?></a> <span style="color: #0663F6;"><?= e(t('common.form_consent_terms_suffix')) ?></span>
                                    </label>
                                </div>
                                <p id="contact_pdpa_cta_error" class="hidden cta-error-text" style="padding-left: 1.75rem !important;"></p>
                            </div>

                            <!-- Google reCAPTCHA v2 Widget -->
                            <div class="pt-2 pb-1 flex justify-center sm:justify-start">
                                <div class="g-recaptcha" data-sitekey="<?= e($recaptchaSiteKey) ?>"></div>
                            </div>

                            <?php if ($errors !== []): ?>
                                <p class="text-xs font-bold text-red-500 pt-1"><?= e($errors[0]) ?></p>
                            <?php endif; ?>

                            <style>
                                @media (min-width: 768px) { .desktop-btn-left { justify-content: flex-start !important; } }
                            </style>
                            <div class="pt-2 flex justify-center desktop-btn-left">
                                <button type="submit" id="cta_submit_btn" disabled class="px-8 py-3.5 bg-primary hover:bg-blue-600 text-white font-bold text-base rounded-full flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none">
                                    <?= e(t('erp.cta_submit') !== 'erp.cta_submit' ? t('erp.cta_submit') : (getCurrentLang() === 'th' ? 'ส่งข้อมูล' : 'Submit')) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>

                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const ctaForm = document.getElementById('ctaContactForm');
                                const privacyCbCta = document.getElementById('privacy_consent_checkbox_cta');
                                const submitBtnCta = document.getElementById('cta_submit_btn');
                                const policyBoxCta = document.getElementById('privacy_policy_box_cta');
                                const privacyTrigger = document.getElementById('ctaPrivacyModalTrigger');

                                const fnCta = document.getElementById('contact_firstname_cta');
                                const lnCta = document.getElementById('contact_lastname_cta');
                                const compCta = document.getElementById('contact_company_cta');
                                const phoneCta = document.getElementById('contact_phone_cta');
                                const emailCta = document.getElementById('contact_email_cta');
                                const msgCta = document.getElementById('contact_message_cta');

                                const fnCtaErr = document.getElementById('contact_firstname_cta_error');
                                const lnCtaErr = document.getElementById('contact_lastname_cta_error');
                                const compCtaErr = document.getElementById('contact_company_cta_error');
                                const phoneCtaErr = document.getElementById('contact_phone_cta_error');
                                const emailCtaErr = document.getElementById('contact_email_cta_error');
                                const msgCtaErr = document.getElementById('contact_message_cta_error');
                                const pdpaCtaErr = document.getElementById('contact_pdpa_cta_error');

                                function setCtaError(inputEl, errorEl, msg) {
                                    if (inputEl) inputEl.classList.add('is-invalid-cta');
                                    if (errorEl) {
                                        errorEl.textContent = msg;
                                        errorEl.classList.remove('hidden');
                                    }
                                }

                                function clearCtaError(inputEl, errorEl) {
                                    if (inputEl) inputEl.classList.remove('is-invalid-cta');
                                    if (errorEl) {
                                        errorEl.textContent = '';
                                        errorEl.classList.add('hidden');
                                    }
                                }

                                const ctaMsgCounter = document.getElementById('contact_cta_msg_counter');

                                function updateCtaMsgCounter() {
                                    if (msgCta && ctaMsgCounter) {
                                        ctaMsgCounter.textContent = `${msgCta.value.length}/250`;
                                        if (msgCta.value.length >= 250) {
                                            ctaMsgCounter.classList.add('text-red-500', 'font-bold');
                                            ctaMsgCounter.classList.remove('text-slate-400');
                                        } else {
                                            ctaMsgCounter.classList.remove('text-red-500', 'font-bold');
                                            ctaMsgCounter.classList.add('text-slate-400');
                                        }
                                    }
                                }

                                if (msgCta) {
                                    updateCtaMsgCounter();
                                    msgCta.addEventListener('input', updateCtaMsgCounter);
                                }

                                [fnCta, lnCta, compCta, phoneCta, emailCta, msgCta].forEach(inp => {
                                    if (!inp) return;
                                    inp.addEventListener('input', () => {
                                        if (inp === fnCta) clearCtaError(fnCta, fnCtaErr);
                                        if (inp === lnCta) clearCtaError(lnCta, lnCtaErr);
                                        if (inp === compCta) clearCtaError(compCta, compCtaErr);
                                        if (inp === phoneCta) clearCtaError(phoneCta, phoneCtaErr);
                                        if (inp === emailCta) clearCtaError(emailCta, emailCtaErr);
                                        if (inp === msgCta) clearCtaError(msgCta, msgCtaErr);
                                    });
                                });

                                if (ctaForm) {
                                    ctaForm.addEventListener('submit', function (e) {
                                        let isValid = true;
                                        let firstInvalid = null;

                                        const fnVal = fnCta.value.trim();
                                        if (!fnVal) {
                                            setCtaError(fnCta, fnCtaErr, 'กรุณากรอกชื่อ');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = fnCta;
                                        } else if (/\d/.test(fnVal)) {
                                            setCtaError(fnCta, fnCtaErr, 'ชื่อต้องเป็นตัวอักษรเท่านั้น (ห้ามมีตัวเลข)');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = fnCta;
                                        }

                                        const lnVal = lnCta.value.trim();
                                        if (!lnVal) {
                                            setCtaError(lnCta, lnCtaErr, 'กรุณากรอกนามสกุล');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = lnCta;
                                        } else if (/\d/.test(lnVal)) {
                                            setCtaError(lnCta, lnCtaErr, 'นามสกุลต้องเป็นตัวอักษรเท่านั้น (ห้ามมีตัวเลข)');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = lnCta;
                                        }

                                        if (!compCta.value.trim()) {
                                            setCtaError(compCta, compCtaErr, 'กรุณากรอกชื่อบริษัท (หากไม่มีให้ใส่ -)');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = compCta;
                                        }

                                        const phoneVal = phoneCta.value.trim();
                                        if (!phoneVal) {
                                            setCtaError(phoneCta, phoneCtaErr, 'กรุณากรอกเบอร์โทรศัพท์');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = phoneCta;
                                        } else if (phoneVal.length < 9 || phoneVal.length > 10) {
                                            setCtaError(phoneCta, phoneCtaErr, 'เบอร์โทรศัพท์ต้องเป็นตัวเลข 9-10 หลัก');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = phoneCta;
                                        }

                                        const emailVal = emailCta.value.trim();
                                        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                                        if (!emailVal) {
                                            setCtaError(emailCta, emailCtaErr, 'กรุณากรอกอีเมล');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = emailCta;
                                        } else if (!emailRegex.test(emailVal)) {
                                            setCtaError(emailCta, emailCtaErr, 'รูปแบบอีเมลไม่ถูกต้อง (เช่น name@example.com)');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = emailCta;
                                        }

                                        const msgVal = msgCta.value.trim();
                                        if (!msgVal) {
                                            setCtaError(msgCta, msgCtaErr, 'กรุณากรอกรายละเอียดข้อความ');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = msgCta;
                                        } else if (msgVal.length > 250) {
                                            setCtaError(msgCta, msgCtaErr, 'รายละเอียดข้อความต้องไม่เกิน 250 ตัวอักษร');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = msgCta;
                                        }

                                        if (!privacyCbCta.checked) {
                                            setCtaError(null, pdpaCtaErr, 'กรุณายอมรับนโยบายความเป็นส่วนตัว');
                                            isValid = false;
                                            if (!firstInvalid) firstInvalid = privacyCbCta;
                                        }

                                        if (!isValid) {
                                            e.preventDefault();
                                            if (firstInvalid) firstInvalid.focus();
                                        }
                                    });
                                }

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