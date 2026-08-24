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
$siteKey = $siteKey ?? '6Lcf_pAtAAAAAOVhatPPwrHSYXeb_0J4yXf5BrRO';
?>
<style>
/* iPad Pro (1024px) override to keep CTA layout stacked */
@media (max-width: 1024px) {
    .ipad-pro-cta-grid-override { grid-template-columns: repeat(1, minmax(0, 1fr)) !important; }
    .ipad-pro-cta-col-override { grid-column: span 1 / span 1 !important; }
}
/* Increase font sizes specifically for iPad Pro */
@media (min-width: 1024px) and (max-width: 1279px) {
    .ipad-pro-cta-title { font-size: 1.5rem !important; line-height: 2.25rem !important; }
    .ipad-pro-cta-subtitle { font-size: 1.25rem !important; line-height: 2rem !important; }
    .ipad-pro-cta-input { font-size: 1.25rem !important; padding: 1rem 1.25rem !important; }
    .ipad-pro-cta-input::placeholder { font-size: 1.25rem !important; }
    .ipad-pro-cta-privacy { font-size: 1.125rem !important; }
}
</style>
<section id="cta-contact-section" class="bg-white py-10 lg:py-10 font-sans">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-6 lg:px-10 relative z-10">
        <div class="relative w-full rounded-[2rem] p-8 md:p-12 lg:p-14 grid grid-cols-1 lg:grid-cols-12 gap-10 items-start overflow-hidden shadow-xl ipad-pro-cta-grid-override">
            <div class="absolute inset-0 z-0 rounded-[2rem] overflow-hidden">
                <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="City Network Overlay" class="w-full h-full opacity-80 object-cover">
                <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(1, 47, 122, 0.95) 0%, rgba(0, 79, 207, 0.6) 100%);"></div>
            </div>
            <div class="relative z-10 lg:col-span-5 flex flex-col items-start text-left lg:pt-2 ipad-pro-cta-col-override">
                <div class="mb-4 relative">
                    <span class="text-white font-black text-4xl md:text-5xl lg:text-[3rem] tracking-tight block">
                        <?= e(t('common.nav_contact')) ?>
                    </span>
                    <div class="w-12 h-[3px] bg-white mt-3"></div>
                </div>
                <span class="mt-4 text-white text-base md:text-lg leading-relaxed font-medium ipad-pro-cta-title">
                    <?= e($contactTitle) ?>
                </span>
                <p class="mt-1 text-white text-base md:text-lg leading-snug font-medium ipad-pro-cta-subtitle">
                    <?= $contactSubtitle ?>
                </p>
            </div>
            <div class="relative z-10 lg:col-span-7 w-full ipad-pro-cta-col-override">
                <div class="rounded-3xl bg-white p-6 md:p-8 shadow-2xl border border-slate-50">
                    <!-- Success Box (Visible upon successful submission) -->
                    <div id="cta-success-box" class="<?= $submitted ? '' : 'hidden' ?> text-center py-12">
                        <div class="w-14 h-14 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-dark mb-1"><?= e(getCurrentLang() === 'th' ? 'ส่งข้อมูลสำเร็จ' : 'Submission Successful') ?></h3>
                        <p class="text-slate-500 text-xs md:text-sm"><?= e(getCurrentLang() === 'th' ? 'ทีมงานผู้เชี่ยวชาญได้รับข้อมูลแล้ว และจะติดต่อกลับหาคุณโดยเร็วที่สุด' : 'Our experts have received your inquiry and will get back to you as soon as possible.') ?></p>
                    </div>

                    <!-- Form Container -->
                    <div id="cta-form-box" class="<?= $submitted ? 'hidden' : '' ?>">
                        <style>
                            .custom-placeholder::placeholder {
                                color: #043B94 !important;
                                opacity: 0.9;
                            }
                            .privacy-text { color: #022862 !important; }
                            @media (min-width: 768px) { .desktop-btn-left { justify-content: flex-start !important; } }
                            .input-error {
                                border-color: #f87171 !important;
                                box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.2) !important;
                            }
                            .input-error:focus {
                                border-color: #ef4444 !important;
                                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.25) !important;
                            }
                            .cta-field-error-msg {
                                color: #e05263;
                                font-size: 0.8125rem;
                                line-height: 1.25rem;
                                margin-top: 0.375rem;
                                font-weight: 400;
                                display: block;
                            }
                            .cta-field-error-msg.hidden {
                                display: none !important;
                            }
                        </style>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <form id="ctaContactForm" method="post" action="<?= e(route_url('/contact/submit')) ?>" class="space-y-4" novalidate>
                            <input type="hidden" name="source_page" value="<?= e($_SERVER['REQUEST_URI'] ?? '') ?>">
                            <input type="hidden" name="is_ajax" value="1">

                            <!-- First Name & Last Name (2 columns) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" id="cta_first_name" name="first_name" placeholder="<?= e(t('common.form_label_firstname')) ?> *" value="<?= e($form['first_name'] ?? '') ?>" maxlength="30"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary ipad-pro-cta-input"
                                        oninput="this.value = this.value.replace(/\s+/g, '');">
                                    <p id="error_cta_first_name" class="cta-field-error-msg hidden"></p>
                                </div>
                                <div>
                                    <input type="text" id="cta_last_name" name="last_name" placeholder="<?= e(t('common.form_label_lastname')) ?> *" value="<?= e($form['last_name'] ?? '') ?>" maxlength="30"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary ipad-pro-cta-input"
                                        oninput="this.value = this.value.replace(/\s+/g, '');">
                                    <p id="error_cta_last_name" class="cta-field-error-msg hidden"></p>
                                </div>
                            </div>

                            <!-- Company Name (Optional) -->
                            <div>
                                <input type="text" id="cta_company_name" name="company_name" placeholder="<?= e(t('common.form_label_company_optional')) ?>" value="<?= e($form['company_name'] ?? '') ?>" maxlength="100"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary ipad-pro-cta-input">
                                <p id="error_cta_company_name" class="cta-field-error-msg hidden"></p>
                            </div>

                            <!-- Phone & Email -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" inputmode="numeric" id="cta_phone" name="phone" placeholder="<?= e(t('common.form_label_phone')) ?> *" value="<?= e($form['phone'] ?? '') ?>" maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary ipad-pro-cta-input">
                                    <p id="error_cta_phone" class="cta-field-error-msg hidden"></p>
                                </div>
                                <div>
                                    <input type="email" id="cta_email" name="email" placeholder="<?= e(t('common.form_label_email')) ?> *" value="<?= e($form['email'] ?? '') ?>" maxlength="255"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary ipad-pro-cta-input">
                                    <p id="error_cta_email" class="cta-field-error-msg hidden"></p>
                                </div>
                            </div>

                            <!-- Message with Character Counter -->
                            <div class="space-y-1">
                                <textarea id="cta_message_area" name="message" rows="3" placeholder="<?= e(t('common.form_label_details')) ?> *" maxlength="500"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 outline-none transition custom-placeholder focus:border-primary focus:ring-1 focus:ring-primary resize-none ipad-pro-cta-input"><?= e($form['message'] ?? '') ?></textarea>
                                <div class="flex justify-between items-center px-1 text-xs text-slate-400">
                                    <span>* ความยาวไม่เกิน 500 ตัวอักษร</span>
                                    <span id="cta_word_count_display" class="font-semibold text-slate-500">0/500</span>
                                </div>
                                <p id="error_cta_message_area" class="cta-field-error-msg hidden"></p>
                            </div>

                            <!-- PDPA Consent Checkbox -->
                            <div>
                                <div class="flex items-start gap-2.5 pt-1">
                                    <input type="checkbox" id="cta_privacy_consent" name="pdpa_agreed" value="1" class="mt-0.5 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary cursor-pointer transition">
                                    <label for="cta_privacy_consent" class="text-sm leading-relaxed cursor-pointer select-none ipad-pro-cta-privacy">
                                        <span class="privacy-text"><?= e(t('common.form_consent_prefix')) ?></span> <a href="<?= e(route_url('/privacy-policy')) ?>" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline"><?= e(t('common.form_consent_privacy_policy')) ?> <?= e(t('common.form_consent_terms_suffix')) ?></a>
                                    </label>
                                </div>
                                <p id="error_cta_privacy_consent" class="cta-field-error-msg hidden"></p>
                            </div>

                            <!-- Google reCAPTCHA v2 Widget -->
                            <div>
                                <div class="pt-1 flex justify-center sm:justify-start">
                                    <div id="cta_recaptcha_container" class="g-recaptcha" data-sitekey="<?= e($siteKey) ?>"></div>
                                </div>
                                <p id="error_cta_recaptcha" class="cta-field-error-msg hidden"></p>
                            </div>

                            <!-- Error Message Alerts Container -->
                            <div id="cta-error-box" class="hidden rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-600 space-y-1"></div>

                            <!-- Submit Button -->
                            <div class="pt-2 flex justify-center desktop-btn-left">
                                <button type="submit" id="cta_submit_btn" class="px-8 py-3.5 bg-primary hover:bg-blue-600 text-white font-bold text-base rounded-full flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-none">
                                    <span id="cta_btn_text"><?= e(t('erp.cta_submit') !== 'erp.cta_submit' ? t('erp.cta_submit') : (getCurrentLang() === 'th' ? 'ส่งข้อมูล' : 'Submit')) ?></span>
                                    <svg id="cta_btn_icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    <svg id="cta_btn_spinner" class="hidden animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ctaContactForm');
    const firstNameInput = document.getElementById('cta_first_name');
    const lastNameInput = document.getElementById('cta_last_name');
    const companyInput = document.getElementById('cta_company_name');
    const phoneInput = document.getElementById('cta_phone');
    const emailInput = document.getElementById('cta_email');
    const messageArea = document.getElementById('cta_message_area');
    const privacyCb = document.getElementById('cta_privacy_consent');
    
    const errorFirstName = document.getElementById('error_cta_first_name');
    const errorLastName = document.getElementById('error_cta_last_name');
    const errorCompany = document.getElementById('error_cta_company_name');
    const errorPhone = document.getElementById('error_cta_phone');
    const errorEmail = document.getElementById('error_cta_email');
    const errorMessage = document.getElementById('error_cta_message_area');
    const errorPrivacy = document.getElementById('error_cta_privacy_consent');
    const errorRecaptcha = document.getElementById('error_cta_recaptcha');

    const submitBtn = document.getElementById('cta_submit_btn');
    const btnText = document.getElementById('cta_btn_text');
    const btnIcon = document.getElementById('cta_btn_icon');
    const btnSpinner = document.getElementById('cta_btn_spinner');
    const wordCountDisplay = document.getElementById('cta_word_count_display');
    const errorBox = document.getElementById('cta-error-box');
    const formBox = document.getElementById('cta-form-box');
    const successBox = document.getElementById('cta-success-box');

    const isTh = '<?= getCurrentLang() ?>' === 'th';
    const letterOnlyRegex = /^[a-zA-Z\u0E00-\u0E7F]+$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function setError(inputEl, errorEl, message) {
        if (inputEl) {
            inputEl.classList.add('input-error');
        }
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
    }

    function clearError(inputEl, errorEl) {
        if (inputEl) {
            inputEl.classList.remove('input-error');
        }
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }
    }

    function validateFirstName() {
        const val = (firstNameInput.value || '').trim();
        if (!val) {
            setError(firstNameInput, errorFirstName, isTh ? 'กรุณากรอกชื่อจริง' : 'Please enter your first name');
            return false;
        }
        if (!letterOnlyRegex.test(val)) {
            setError(firstNameInput, errorFirstName, isTh ? 'กรุณากรอกชื่อเป็นตัวอักษรเท่านั้น' : 'Please enter a valid name using letters only');
            return false;
        }
        if (val.length > 30) {
            setError(firstNameInput, errorFirstName, isTh ? 'ชื่อจริงต้องไม่เกิน 30 ตัวอักษร' : 'First name must not exceed 30 characters');
            return false;
        }
        clearError(firstNameInput, errorFirstName);
        return true;
    }

    function validateLastName() {
        const val = (lastNameInput.value || '').trim();
        if (!val) {
            setError(lastNameInput, errorLastName, isTh ? 'กรุณากรอกนามสกุล' : 'Please enter your last name');
            return false;
        }
        if (!letterOnlyRegex.test(val)) {
            setError(lastNameInput, errorLastName, isTh ? 'กรุณากรอกนามสกุลเป็นตัวอักษรเท่านั้น' : 'Please enter a valid last name using letters only');
            return false;
        }
        if (val.length > 30) {
            setError(lastNameInput, errorLastName, isTh ? 'นามสกุลต้องไม่เกิน 30 ตัวอักษร' : 'Last name must not exceed 30 characters');
            return false;
        }
        clearError(lastNameInput, errorLastName);
        return true;
    }

    function validateCompany() {
        const val = (companyInput.value || '').trim();
        if (val && val.length > 100) {
            setError(companyInput, errorCompany, isTh ? 'ชื่อบริษัทต้องไม่เกิน 100 ตัวอักษร' : 'Company name must not exceed 100 characters');
            return false;
        }
        clearError(companyInput, errorCompany);
        return true;
    }

    function validatePhone() {
        const val = (phoneInput.value || '').trim();
        if (!val) {
            setError(phoneInput, errorPhone, isTh ? 'กรุณากรอกเบอร์โทรศัพท์' : 'Please enter your phone number');
            return false;
        }
        if (!/^[0-9]{9,10}$/.test(val)) {
            setError(phoneInput, errorPhone, isTh ? 'กรุณากรอกเบอร์โทรศัพท์ 9-10 หลักให้ถูกต้อง' : 'Please enter a valid 9-10 digit phone number');
            return false;
        }
        clearError(phoneInput, errorPhone);
        return true;
    }

    function validateEmail() {
        const val = (emailInput.value || '').trim();
        if (!val) {
            setError(emailInput, errorEmail, isTh ? 'กรุณากรอกอีเมล' : 'Please enter your email');
            return false;
        }
        if (!emailRegex.test(val)) {
            setError(emailInput, errorEmail, isTh ? 'กรุณากรอกรูปแบบอีเมลให้ถูกต้อง' : 'Please enter a valid email address');
            return false;
        }
        if (val.length > 255) {
            setError(emailInput, errorEmail, isTh ? 'อีเมลยาวเกินกำหนด' : 'Email is too long');
            return false;
        }
        clearError(emailInput, errorEmail);
        return true;
    }

    function validateMessage() {
        const val = (messageArea.value || '').trim();
        if (!val) {
            setError(messageArea, errorMessage, isTh ? 'กรุณากรอกรายละเอียด' : 'Please enter your message');
            return false;
        }
        if (val.length > 500) {
            setError(messageArea, errorMessage, isTh ? 'ข้อความต้องไม่เกิน 500 ตัวอักษร' : 'Message must not exceed 500 characters');
            return false;
        }
        clearError(messageArea, errorMessage);
        return true;
    }

    function validatePrivacy() {
        if (!privacyCb.checked) {
            setError(null, errorPrivacy, isTh ? 'กรุณายินยอมตามนโยบายความเป็นส่วนตัว' : 'Please accept the Privacy Policy');
            return false;
        }
        clearError(null, errorPrivacy);
        return true;
    }

    function validateRecaptcha() {
        if (typeof grecaptcha !== 'undefined') {
            const resp = grecaptcha.getResponse();
            if (!resp || resp.length === 0) {
                setError(null, errorRecaptcha, isTh ? 'กรุณายืนยันตัวตน reCAPTCHA' : 'Please complete the reCAPTCHA verification');
                return false;
            }
        }
        clearError(null, errorRecaptcha);
        return true;
    }

    // Real-time events
    firstNameInput.addEventListener('input', () => { if (errorFirstName.textContent) validateFirstName(); });
    firstNameInput.addEventListener('blur', validateFirstName);

    lastNameInput.addEventListener('input', () => { if (errorLastName.textContent) validateLastName(); });
    lastNameInput.addEventListener('blur', validateLastName);

    companyInput.addEventListener('input', () => { if (errorCompany.textContent) validateCompany(); });
    companyInput.addEventListener('blur', validateCompany);

    phoneInput.addEventListener('input', () => { if (errorPhone.textContent) validatePhone(); });
    phoneInput.addEventListener('blur', validatePhone);

    emailInput.addEventListener('input', () => { if (errorEmail.textContent) validateEmail(); });
    emailInput.addEventListener('blur', validateEmail);

    messageArea.addEventListener('input', () => {
        updateCtaWordCount();
        if (errorMessage.textContent) validateMessage();
    });
    messageArea.addEventListener('blur', validateMessage);

    privacyCb.addEventListener('change', validatePrivacy);

    // Character counter for message (max 500 characters)
    function updateCtaWordCount() {
        if (!messageArea || !wordCountDisplay) return;
        const count = messageArea.value.length;
        wordCountDisplay.textContent = count + '/500';
        if (count > 500) {
            wordCountDisplay.classList.add('text-red-500');
            wordCountDisplay.classList.remove('text-slate-500');
        } else {
            wordCountDisplay.classList.remove('text-red-500');
            wordCountDisplay.classList.add('text-slate-500');
        }
    }
    updateCtaWordCount();

    function validateAll() {
        const validFirst = validateFirstName();
        const validLast = validateLastName();
        const validCompany = validateCompany();
        const validPhone = validatePhone();
        const validEmail = validateEmail();
        const validMsg = validateMessage();
        const validPrivacy = validatePrivacy();
        const validRecaptcha = validateRecaptcha();

        if (!validFirst) { firstNameInput.focus(); return false; }
        if (!validLast) { lastNameInput.focus(); return false; }
        if (!validCompany) { companyInput.focus(); return false; }
        if (!validPhone) { phoneInput.focus(); return false; }
        if (!validEmail) { emailInput.focus(); return false; }
        if (!validMsg) { messageArea.focus(); return false; }
        if (!validPrivacy) { privacyCb.focus(); return false; }
        if (!validRecaptcha) { return false; }

        return true;
    }

    // AJAX Submission handler
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateAll()) {
                return;
            }

            // Clear previous errors
            errorBox.classList.add('hidden');
            errorBox.innerHTML = '';

            // Set loading state
            submitBtn.disabled = true;
            btnSpinner.classList.remove('hidden');
            btnIcon.classList.add('hidden');
            const originalBtnText = btnText.textContent;
            btnText.textContent = '<?= getCurrentLang() === 'th' ? 'กำลังส่งข้อมูล...' : 'Submitting...' ?>';

            const formData = new FormData(form);
            formData.set('source_page', window.location.href);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    formBox.classList.add('hidden');
                    successBox.classList.remove('hidden');
                    form.reset();
                } else {
                    throw data;
                }
            })
            .catch(err => {
                // Reset loading state
                submitBtn.disabled = false;
                btnSpinner.classList.add('hidden');
                btnIcon.classList.remove('hidden');
                btnText.textContent = originalBtnText;

                // Reset reCAPTCHA if available
                if (typeof grecaptcha !== 'undefined') {
                    try { grecaptcha.reset(); } catch(e) {}
                }

                // Render error messages
                const errors = err.errors || [err.message || '<?= getCurrentLang() === 'th' ? 'เกิดข้อผิดพลาดในการส่งข้อมูล กรุณาลองใหม่อีกครั้ง' : 'Failed to submit inquiry. Please try again.' ?>'];
                errorBox.innerHTML = errors.map(errText => `
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>${errText}</span>
                    </div>
                `).join('');
                errorBox.classList.remove('hidden');
            });
        });
    }
});
</script>