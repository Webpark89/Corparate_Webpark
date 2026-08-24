<?php
declare(strict_types=1);

/**
 * Lightweight Cookie Consent Banner Component — WebPark Co., Ltd.
 */
?>
<style>
/* Cookie Consent Banner Styling */
#cookieConsentBanner {
    position: fixed;
    bottom: 1.25rem;
    left: 1rem;
    right: 1rem;
    z-index: 99999;
    transition: opacity 0.4s ease, transform 0.4s ease;
}

@media (min-width: 640px) {
    #cookieConsentBanner {
        left: auto;
        right: 1.5rem;
        max-width: 28rem;
    }
}

.cookie-banner-card {
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid #cbd5e1;
    border-radius: 1.25rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 31, 92, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.cookie-banner-header {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
}

.cookie-banner-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    min-width: 2.5rem;
    border-radius: 0.875rem;
    background-color: #eff6ff;
    color: #0663F6;
}

.cookie-banner-title {
    color: #001f5c;
    font-weight: 700;
    font-size: 0.9375rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.cookie-banner-text {
    color: #475569;
    font-size: 0.8125rem;
    line-height: 1.6;
    margin: 0;
}

.cookie-banner-link {
    color: #0663F6;
    font-weight: 600;
    text-decoration: underline;
    transition: color 0.2s ease;
}

.cookie-banner-link:hover {
    color: #044dc4;
}

.cookie-banner-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.625rem;
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
}

.cookie-btn-dismiss {
    background: transparent;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-weight: 600;
    font-size: 0.8125rem;
    padding: 0.45rem 1rem;
    border-radius: 0.625rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cookie-btn-dismiss:hover {
    background-color: #f8fafc;
    color: #334155;
}

.cookie-btn-accept {
    background-color: #0663F6;
    border: 1px solid #0663F6;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.8125rem;
    padding: 0.45rem 1.25rem;
    border-radius: 0.625rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(6, 99, 246, 0.25);
    transition: all 0.2s ease;
}

.cookie-btn-accept:hover {
    background-color: #044dc4;
    border-color: #044dc4;
    box-shadow: 0 6px 16px rgba(6, 99, 246, 0.35);
}

.cookie-banner-hidden {
    display: none !important;
}
</style>

<div id="cookieConsentBanner" class="cookie-banner-hidden" role="dialog" aria-live="polite" aria-label="การแจ้งเตือนการใช้งานคุกกี้">
    <div class="cookie-banner-card">
        <div class="cookie-banner-header">
            <div class="cookie-banner-icon">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div style="flex: 1;">
                <div class="cookie-banner-title">การใช้งานคุกกี้ (Cookie Policy)</div>
                <p class="cookie-banner-text">
                    เว็บไซต์นี้ใช้งานคุกกี้เพื่อประสิทธิภาพและความปลอดภัยในการใช้งาน 
                    <a href="<?= e(route_url('/privacy-policy')) ?>" target="_blank" rel="noopener noreferrer" class="cookie-banner-link">
                        อ่านนโยบายความเป็นส่วนตัว
                    </a>
                </p>
            </div>
        </div>
        <div class="cookie-banner-actions">
            <button type="button" id="cookieDismissBtn" class="cookie-btn-dismiss">
                ปิด
            </button>
            <button type="button" id="cookieAcceptBtn" class="cookie-btn-accept">
                ยอมรับ
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    function initCookieBanner() {
        const banner = document.getElementById('cookieConsentBanner');
        const acceptBtn = document.getElementById('cookieAcceptBtn');
        const dismissBtn = document.getElementById('cookieDismissBtn');
        const consentKey = 'webpark_cookie_consent';

        if (!banner) return;

        // Check if consent has already been given or dismissed in this session
        const hasConsent = localStorage.getItem(consentKey);
        const hasSessionDismiss = sessionStorage.getItem(consentKey);

        if (!hasConsent && !hasSessionDismiss) {
            setTimeout(function() {
                banner.classList.remove('cookie-banner-hidden');
            }, 600);
        }

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function() {
                localStorage.setItem(consentKey, 'accepted');
                banner.classList.add('cookie-banner-hidden');
            });
        }

        if (dismissBtn) {
            dismissBtn.addEventListener('click', function() {
                sessionStorage.setItem(consentKey, 'dismissed');
                banner.classList.add('cookie-banner-hidden');
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCookieBanner);
    } else {
        initCookieBanner();
    }
})();
</script>
