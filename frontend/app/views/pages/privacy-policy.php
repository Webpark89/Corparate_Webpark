<?php
declare(strict_types=1);

/**
 * Comprehensive Privacy & Cookie Policy (PDPA) — WebPark Co., Ltd.
 */
$company = config('company', []) ?: ($company ?? []);
$companyName = $company['name'] ?? 'บริษัท เว็บพาร์ค จำกัด';
$contactEmail = $company['contact']['email'] ?? 'oraphan@webpark.co.th';
$contactPhone = $company['contact']['phone'] ?? '095 539 2666';
$contactAddress = $company['contact']['address'] ?? '525/89 ซอยลาดพร้าว126 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310';
$lastUpdated = '24 สิงหาคม 2569';
?>

<style>
/* Scoped Styles for Privacy Policy Page — Guaranteed Pixel-Perfect Rendering */
.pdpa-wrapper {
    font-family: 'Noto Sans Thai', ui-sans-serif, system-ui, sans-serif;
    color: #334155;
    line-height: 1.85;
}

.pdpa-hero {
    background: linear-gradient(180deg, #f1f5f9 0%, #f8fafc 60%, #ffffff 100%);
    border-bottom: 1px solid #e2e8f0;
    padding-top: 3rem;
    padding-bottom: 3.5rem;
}

@media (min-width: 768px) {
    .pdpa-hero {
        padding-top: 4.5rem;
        padding-bottom: 4.5rem;
    }
}

.pdpa-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background-color: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #0663F6;
    font-weight: 700;
    font-size: 0.8125rem;
    padding: 0.4rem 1rem;
    border-radius: 9999px;
    margin-bottom: 1.25rem;
}

.pdpa-title {
    color: #001f5c;
    font-weight: 800;
    font-size: 2rem;
    line-height: 1.35;
    margin-bottom: 0.75rem;
}

@media (min-width: 640px) {
    .pdpa-title {
        font-size: 2.5rem;
    }
}

@media (min-width: 1024px) {
    .pdpa-title {
        font-size: 3rem;
    }
}

.pdpa-title-accent {
    color: #0663F6;
}

.pdpa-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

/* Card & Section Styles */
.pdpa-intro-card {
    background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 50%, #f8fafc 100%);
    border: 1px solid #dbeafe;
    border-radius: 1.5rem;
    padding: 1.75rem 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 2px 8px rgba(6, 99, 246, 0.04);
}

.pdpa-section {
    margin-bottom: 3rem;
    padding-bottom: 2.5rem;
    border-bottom: 1px solid #f1f5f9;
}

.pdpa-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1.5rem;
    padding-bottom: 0;
}

.pdpa-heading-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.pdpa-num-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    min-width: 2.25rem;
    border-radius: 0.75rem;
    background-color: #0663F6 !important;
    color: #ffffff !important;
    font-weight: 800;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(6, 99, 246, 0.25);
}

.pdpa-section-title {
    color: #001f5c !important;
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.4;
}

@media (min-width: 640px) {
    .pdpa-section-title {
        font-size: 1.5rem;
    }
}

.pdpa-text {
    color: #475569;
    font-size: 1rem;
    line-height: 1.85;
    margin-bottom: 1.25rem;
}

.pdpa-grid-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    padding: 1.5rem 1.75rem;
    height: 100%;
}

.pdpa-grid-box-title {
    color: #001f5c;
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.pdpa-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.pdpa-list-item {
    display: flex;
    align-items: flex-start;
    gap: 0.625rem;
    color: #475569;
    font-size: 0.9375rem;
    line-height: 1.7;
}

.pdpa-bullet-blue {
    color: #0663F6;
    font-weight: bold;
    font-size: 1.25rem;
    line-height: 1;
    margin-top: 0.15rem;
}

.pdpa-bullet-indigo {
    color: #4f46e5;
    font-weight: bold;
    font-size: 1.25rem;
    line-height: 1;
    margin-top: 0.15rem;
}

.pdpa-check-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    color: #334155;
    font-size: 0.975rem;
    line-height: 1.75;
}

.pdpa-check-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.35rem;
    height: 1.35rem;
    min-width: 1.35rem;
    border-radius: 9999px;
    background-color: #dbeafe;
    color: #0663F6;
    margin-top: 0.25rem;
}

.pdpa-info-row {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    margin-bottom: 0.75rem;
}

.pdpa-info-badge {
    background-color: #eff6ff;
    color: #0663F6;
    font-weight: 700;
    font-size: 0.875rem;
    padding: 0.35rem 0.65rem;
    border-radius: 0.625rem;
    min-width: 2.75rem;
    text-align: center;
    margin-top: 0.1rem;
}

.pdpa-rights-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    padding: 1.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    transition: all 0.2s ease;
}

.pdpa-rights-card:hover {
    border-color: #93c5fd;
    box-shadow: 0 6px 16px rgba(6, 99, 246, 0.08);
}

.pdpa-contact-box {
    background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
    border: 1px solid #bfdbfe;
    border-radius: 1.5rem;
    padding: 2rem 2.25rem;
    margin-top: 2rem;
    box-shadow: 0 4px 15px rgba(6, 99, 246, 0.05);
}

/* Primary Action Button */
.pdpa-btn-primary {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.625rem !important;
    background-color: #0663F6 !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    padding: 0.95rem 2.25rem !important;
    border-radius: 9999px !important;
    text-decoration: none !important;
    box-shadow: 0 6px 20px rgba(6, 99, 246, 0.28) !important;
    transition: all 0.25s ease !important;
    cursor: pointer !important;
    white-space: nowrap !important;
    border: none !important;
}

.pdpa-btn-primary:hover {
    background-color: #044dc4 !important;
    color: #ffffff !important;
    box-shadow: 0 8px 25px rgba(6, 99, 246, 0.38) !important;
    transform: translateY(-2px) !important;
}

.pdpa-btn-primary svg {
    color: #ffffff !important;
    stroke: #ffffff !important;
}
</style>

<div class="pdpa-wrapper">

    <!-- 1. Hero Header Section -->
    <section class="pdpa-hero">
        <div class="mx-auto w-full max-w-5xl px-5 sm:px-8 lg:px-10">
            
            <!-- Breadcrumb Navigation -->
            <nav class="mb-6 flex items-center gap-2 text-xs md:text-sm font-medium text-slate-500">
                <a href="<?= e(route_url('/')) ?>" class="hover:text-[#0663F6] transition-colors flex items-center gap-1.5" style="color: #64748b; text-decoration: none;">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    หน้าแรก
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-bold">นโยบายความเป็นส่วนตัวและคุกกี้</span>
            </nav>

            <!-- Compliance Badge -->
            <div class="pdpa-badge">
                <svg class="w-4 h-4 text-[#0663F6] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>PDPA Compliance • พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562</span>
            </div>

            <!-- Main Heading Title -->
            <h1 class="pdpa-title">
                นโยบายความเป็นส่วนตัว <br class="hidden sm:inline"><span class="pdpa-title-accent">(Privacy & Cookie Policy)</span>
            </h1>

            <!-- Update Date -->
            <div class="pdpa-date">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>ปรับปรุงล่าสุดเมื่อวันที่: <strong class="text-slate-700 font-bold"><?= e($lastUpdated) ?></strong></span>
            </div>

        </div>
    </section>

    <!-- 2. Main Content Section -->
    <section class="bg-white py-12 sm:py-16 md:py-20">
        <div class="mx-auto w-full max-w-5xl px-5 sm:px-8 lg:px-10">
            <div class="space-y-12 sm:space-y-16 text-slate-600 text-base leading-relaxed">

                <!-- Intro Statement Card -->
                <div class="pdpa-intro-card">
                    <p class="m-0 text-base sm:text-lg leading-relaxed text-slate-700">
                        <strong style="color: #001f5c; font-weight: 700;"><?= e($companyName) ?></strong> ("เรา" หรือ "WebPark") ในฐานะผู้ควบคุมข้อมูลส่วนบุคคล (Data Controller) ตระหนักและให้ความสำคัญอย่างยิ่งต่อการคุ้มครองข้อมูลส่วนบุคคลและสิทธิความเป็นส่วนตัวของท่าน นโยบายฉบับนี้จัดทำขึ้นตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) เพื่อชี้แจงรายละเอียดเกี่ยวกับการเก็บรวบรวม ใช้ เปิดเผยข้อมูล และการใช้คุกกี้บนเว็บไซต์ webpark.co.th ทั้งหมด
                    </p>
                </div>

                <!-- Section 1: ขอบเขตข้อมูลที่เก็บทั้งเว็บ -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">1</span>
                        <h2 class="pdpa-section-title">ขอบเขตข้อมูลส่วนบุคคลที่เราเก็บรวบรวมทั้งเว็บไซต์</h2>
                    </div>

                    <p class="pdpa-text">เราเก็บรวบรวมข้อมูลส่วนบุคคลของท่านผ่านการใช้งานเว็บไซต์ในกรณีต่าง ๆ เท่าที่จำเป็นดังนี้:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                        <!-- Column 1: ข้อมูลที่ท่านกรอก -->
                        <div class="pdpa-grid-box">
                            <div class="pdpa-grid-box-title">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #0663F6; display: inline-block;"></span>
                                ข้อมูลจากการกรอกแบบฟอร์ม (Form Inquiries)
                            </div>
                            <ul class="pdpa-list">
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-blue">•</span>
                                    <span><strong>ชื่อ - นามสกุล</strong> (First & Last Name)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-blue">•</span>
                                    <span><strong>ชื่อบริษัท / องค์กร</strong> (Company Name)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-blue">•</span>
                                    <span><strong>หมายเลขโทรศัพท์</strong> (Phone Number)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-blue">•</span>
                                    <span><strong>ที่อยู่อีเมล</strong> (Email Address)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-blue">•</span>
                                    <span><strong>ข้อความรายละเอียดโครงการ</strong> ที่ต้องการปรึกษา</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Column 2: ข้อมูลเชิงเทคนิคและบันทึกระบบ -->
                        <div class="pdpa-grid-box">
                            <div class="pdpa-grid-box-title">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #4f46e5; display: inline-block;"></span>
                                ข้อมูลเชิงเทคนิคและบันทึกระบบ (Server Logs)
                            </div>
                            <ul class="pdpa-list">
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-indigo">•</span>
                                    <span><strong>หมายเลข IP Address</strong> ของผู้เข้าใช้งาน</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-indigo">•</span>
                                    <span><strong>ประเภทเบราว์เซอร์และอุปกรณ์</strong> (User Agent)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-indigo">•</span>
                                    <span><strong>หน้าเว็บต้นทางที่เข้าชม</strong> (Source Page / URL)</span>
                                </li>
                                <li class="pdpa-list-item">
                                    <span class="pdpa-bullet-indigo">•</span>
                                    <span><strong>วันเวลาและประวัติการให้ความยินยอม</strong> (PDPA Consent Log)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Cookie Policy -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">2</span>
                        <h2 class="pdpa-section-title">นโยบายการใช้งานคุกกี้ (Cookie Policy)</h2>
                    </div>

                    <p class="pdpa-text">
                        คุกกี้ (Cookies) คือไฟล์ข้อความขนาดเล็กที่ถูกบันทึกลงในคอมพิวเตอร์หรืออุปกรณ์มือถือของท่านเมื่อเข้าชมเว็บไซต์ เพื่อช่วยให้เว็บไซต์ทำงานได้อย่างมีประสิทธิภาพและจดจำการตั้งค่าของท่าน
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 0.875rem; margin-top: 1rem;">
                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">2.1</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">คุกกี้ที่มีความจำเป็นอย่างยิ่ง (Strictly Necessary Cookies)</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">คุกกี้กลุ่มนี้จำเป็นต่อการทำงานพื้นฐานของเว็บไซต์ เช่น การจดจำเซสชัน (Session), การสลับภาษา (TH/EN), การป้องกันความปลอดภัยของแบบฟอร์ม (CSRF Token) และการบันทึกสถานะการยอมรับข้อตกลง โดยไม่สามารถปิดการใช้งานได้</div>
                            </div>
                        </div>

                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">2.2</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">คุกกี้เพื่อความปลอดภัยและการป้องกันสแปม (Security Cookies)</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">ใช้โดยบริการ Google reCAPTCHA เพื่อตรวจสอบว่าผู้กรอกแบบฟอร์มเป็นมนุษย์จริง ไม่ใช่โปรแกรมอัตโนมัติหรือบอทที่อาจก่อความเสียหายต่อระบบ</div>
                            </div>
                        </div>

                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">2.3</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">วิธีจัดการและปิดการใช้งานคุกกี้ (Managing Cookies)</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">ท่านสามารถเลือกตั้งค่า ปฏิเสธ หรือลบคุกกี้ได้ตลอดเวลาผ่านการตั้งค่าในเบราว์เซอร์ของท่าน (เช่น Chrome, Safari, Firefox, Edge) อย่างไรก็ตาม การปิดคุกกี้จำเป็นอาจส่งผลให้บางฟังก์ชันบนเว็บไซต์ทำงานได้ไม่สมบูรณ์</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: วัตถุประสงค์ในการเก็บรวบรวมและใช้ข้อมูล -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">3</span>
                        <h2 class="pdpa-section-title">วัตถุประสงค์ในการเก็บรวบรวมและประมวลผลข้อมูล</h2>
                    </div>

                    <p class="pdpa-text">เรานำข้อมูลส่วนบุคคลของท่านไปใช้ภายใต้วัตถุประสงค์ที่ชอบด้วยกฎหมาย ดังต่อไปนี้:</p>

                    <div class="pdpa-grid-box" style="padding: 1.75rem 2rem;">
                        <ul class="pdpa-list" style="gap: 1rem;">
                            <li class="pdpa-check-item">
                                <span class="pdpa-check-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>เพื่อติดต่อกลับ ให้คำปรึกษา แนะนำบริการ และจัดทำใบเสนอราคาตามบริการที่ท่านร้องขอ</span>
                            </li>
                            <li class="pdpa-check-item">
                                <span class="pdpa-check-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>เพื่อประสานงาน นัดหมายประชุม และชี้แจงรายละเอียดโครงการพัฒนาระบบ ซอฟต์แวร์ และเว็บไซต์</span>
                            </li>
                            <li class="pdpa-check-item">
                                <span class="pdpa-check-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>เพื่อตรวจสอบความถูกต้อง ป้องกันการส่งสแปม (Spam) และเสริมสร้างความปลอดภัยของระบบ</span>
                            </li>
                            <li class="pdpa-check-item">
                                <span class="pdpa-check-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span>เพื่อปฏิบัติตามข้อกำหนด กฎหมาย และระเบียบราชการที่เกี่ยวข้อง</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Section 4: Third-Party Services & Cross-Border -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">4</span>
                        <h2 class="pdpa-section-title">การเปิดเผยข้อมูลต่อบุคคลภายนอกและการโอนข้อมูลข้ามพรมแดน</h2>
                    </div>

                    <p class="pdpa-text">
                        เราไม่มีนโยบายจำหน่าย จ่าย แจก ข้อมูลส่วนบุคคลของท่านให้แก่บุคคลภายนอก เว้นแต่มีความจำเป็นในการให้บริการโดยผ่านผู้ให้บริการภายนอก (Third-Party Service Providers) ดังนี้:
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">4.1</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">ทีมงานและบุคลากรภายใน WebPark</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">เฉพาะผู้ที่มีหน้าที่รับผิดชอบโดยตรงในการดูแลลูกค้า ประเมินราคา และประสานงานโครงการ</div>
                            </div>
                        </div>

                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">4.2</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">ผู้ให้บริการคลาวด์และอีเมล (Cloud & Email Services)</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">เราใช้งานบริการ Google Workspace / Gmail SMTP และ Cloud Hosting ซึ่งอาจมีการส่งผ่านหรือประมวลผลข้อมูลบนเซิร์ฟเวอร์ที่มีมาตรฐานความปลอดภัยสากลในต่างประเทศ โดยเป็นไปตามมาตรการคุ้มครองข้อมูลที่เหมาะสม</div>
                            </div>
                        </div>

                        <div class="pdpa-info-row">
                            <span class="pdpa-info-badge">4.3</span>
                            <div>
                                <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.25rem;">ผู้ให้บริการ Google reCAPTCHA</div>
                                <div style="color: #64748b; font-size: 0.95rem; line-height: 1.6;">ข้อมูลการใช้งานเชิงเทคนิคจะถูกส่งไปยัง Google LLC เพื่อประเมินความเสี่ยงด้านความปลอดภัยตามนโยบายความเป็นส่วนตัวของ Google</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: ระยะเวลาและการคุ้มครองข้อมูลผู้เยาว์ -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">5</span>
                        <h2 class="pdpa-section-title">ระยะเวลาเก็บรักษาข้อมูลและการคุ้มครองข้อมูลผู้เยาว์</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="pdpa-grid-box">
                            <div class="pdpa-grid-box-title">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #0663F6; display: inline-block;"></span>
                                ระยะเวลาในการจัดเก็บ (Data Retention)
                            </div>
                            <p style="margin: 0; color: #475569; font-size: 0.95rem; line-height: 1.75;">
                                เราจะจัดเก็บข้อมูลส่วนบุคคลของท่านไว้เป็นระยะเวลา <strong>2 ปี</strong> นับจากวันที่ติดต่อเข้ามา หรือตลอดระยะเวลาที่มีความจำเป็นตามวัตถุประสงค์ในการให้บริการ เมื่อพ้นกำหนดระยะเวลาดังกล่าว เราจะดำเนินการลบ ทำลาย หรือทำให้ข้อมูลไม่สามารถระบุตัวตนได้อีกต่อไป
                            </p>
                        </div>

                        <div class="pdpa-grid-box">
                            <div class="pdpa-grid-box-title">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #f59e0b; display: inline-block;"></span>
                                ข้อมูลของผู้เยาว์ (Minor Protection)
                            </div>
                            <p style="margin: 0; color: #475569; font-size: 0.95rem; line-height: 1.75;">
                                เว็บไซต์ของเรามุ่งเน้นให้บริการแก่ลูกค้าระดับองค์กรและบุคคลทั่วไปที่มีนิติกรรม เราไม่มีเจตนาเก็บรวบรวมข้อมูลส่วนบุคคลของผู้เยาว์ที่มีอายุต่ำกว่า 20 ปี หากพบว่ามีการเก็บข้อมูลดังกล่าวโดยปราศจากความยินยอมของผู้ปกครอง เราจะดำเนินการลบข้อมูลนั้นทันที
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Security Measures -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">6</span>
                        <h2 class="pdpa-section-title">มาตรการรักษาความปลอดภัยของข้อมูล</h2>
                    </div>

                    <p class="pdpa-text">
                        เราได้กำหนดมาตรการรักษาความมั่นคงปลอดภัยทั้งทางเทคนิคและการบริหารจัดการ (Technical and Organizational Measures) เช่น การเข้ารหัสข้อมูลระหว่างรับส่งผ่านโปรโตคอล SSL/TLS (HTTPS), การจำกัดสิทธิ์การเข้าถึงฐานข้อมูลเฉพาะผู้ดูแลระบบที่มีสิทธิ์ และการเข้ารหัสรหัสผ่าน เพื่อป้องกันการสูญหาย เข้าถึง ทำลาย ใช้ ดัดแปลง หรือเปิดเผยข้อมูลโดยไม่ได้รับอนุญาต
                    </p>
                </div>

                <!-- Section 7: Data Subject Rights -->
                <div class="pdpa-section">
                    <div class="pdpa-heading-group">
                        <span class="pdpa-num-badge">7</span>
                        <h2 class="pdpa-section-title">สิทธิของเจ้าของข้อมูลส่วนบุคคล (Data Subject Rights)</h2>
                    </div>

                    <p class="pdpa-text">ภายใต้พระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 ท่านมีสิทธิต่าง ๆ เกี่ยวกับข้อมูลส่วนบุคคลของท่าน ดังนี้:</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                        <div class="pdpa-rights-card">
                            <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.35rem;">1. สิทธิขอเข้าถึงและรับสำเนาข้อมูล</div>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.6;">ขอรับสำเนาข้อมูลส่วนบุคคลที่อยู่ในความรับผิดชอบของเรา</p>
                        </div>
                        <div class="pdpa-rights-card">
                            <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.35rem;">2. สิทธิขอแก้ไขข้อมูลให้ถูกต้อง</div>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.6;">ขอปรับปรุงข้อมูลส่วนบุคคลให้เป็นปัจจุบัน สมบูรณ์ และถูกต้อง</p>
                        </div>
                        <div class="pdpa-rights-card">
                            <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.35rem;">3. สิทธิขอลบหรือทำลายข้อมูล</div>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.6;">ขอลบ ทำลาย หรือระงับการใช้ข้อมูลเมื่อหมดความจำเป็น</p>
                        </div>
                        <div class="pdpa-rights-card">
                            <div style="color: #001f5c; font-weight: 700; font-size: 1.05rem; margin-bottom: 0.35rem;">4. สิทธิในการเพิกถอนความยินยอม</div>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.6;">เพิกถอนความยินยอมที่ท่านเคยให้ไว้กับเราได้ตลอดเวลา</p>
                        </div>
                    </div>
                </div>

                <!-- Section 8: การเปลี่ยนแปลงนโยบายและผู้ควบคุมข้อมูล -->
                <div class="pdpa-section">
                    <div class="pdpa-contact-box">
                        <div class="pdpa-heading-group" style="margin-bottom: 1rem;">
                            <span class="pdpa-num-badge">8</span>
                            <h3 class="pdpa-section-title" style="font-size: 1.4rem;">
                                ข้อมูลผู้ควบคุมข้อมูลส่วนบุคคล (Data Controller) และการติดต่อ
                            </h3>
                        </div>

                        <p class="pdpa-text" style="margin-bottom: 1.5rem;">
                            เราอาจปรับปรุงนโยบายความเป็นส่วนตัวนี้เป็นครั้งคราวเพื่อให้สอดคล้องกับกฎหมายและการดำเนินงาน หากท่านมีข้อสงสัยหรือมีความประสงค์ที่จะใช้สิทธิของเจ้าของข้อมูลส่วนบุคคล สามารถติดต่อเราได้ที่:
                        </p>

                        <div style="display: flex; flex-direction: column; gap: 0.875rem; font-size: 0.95rem;">
                            <div style="display: flex; flex-wrap: wrap; align-items: baseline; gap: 0.5rem 1rem;">
                                <span style="color: #64748b; font-weight: 600; min-width: 8.5rem;">ผู้ควบคุมข้อมูล:</span>
                                <span style="color: #001f5c; font-weight: 700;"><?= e($companyName) ?></span>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; align-items: baseline; gap: 0.5rem 1rem;">
                                <span style="color: #64748b; font-weight: 600; min-width: 8.5rem;">สถานที่ติดต่อ:</span>
                                <span style="color: #334155;"><?= e($contactAddress) ?></span>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; align-items: baseline; gap: 0.5rem 1rem;">
                                <span style="color: #64748b; font-weight: 600; min-width: 8.5rem;">อีเมล (DPO):</span>
                                <a href="mailto:<?= e($contactEmail) ?>" style="color: #0663F6; font-weight: 700; text-decoration: underline;"><?= e($contactEmail) ?></a>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; align-items: baseline; gap: 0.5rem 1rem;">
                                <span style="color: #64748b; font-weight: 600; min-width: 8.5rem;">โทรศัพท์:</span>
                                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $contactPhone)) ?>" style="color: #0663F6; font-weight: 700; text-decoration: underline;"><?= e($contactPhone) ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Home Button -->
                <div style="display: flex; justify-content: center; align-items: center; padding-top: 2rem;">
                    <a href="<?= e(route_url('/')) ?>" class="pdpa-btn-primary">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>กลับสู่หน้าหลัก</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>
