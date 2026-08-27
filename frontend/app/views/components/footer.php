<?php

declare(strict_types=1);

/**
 * Site footer component with expandable sitemap and company contact info.
 */

require_once __DIR__ . '/../../Models/Service.php';

$company = config('company', []) ?: ($company ?? []);
$companyName = $company['name'] ?? '';

$email = $company['contact']['email'] ?? '';
$phone = $company['contact']['phone'] ?? '';
$phone = str_replace(' ', '-', $phone);
$address = $company['contact']['address'] ?? '';
$officeLabel = t('footer.office_headquarters_label');
$officeValue = t('footer.office_address');
$phoneHref = preg_replace('/[^0-9+]/', '', $phone) ?? '';

$structuredSitemap = [
    'PAGE' => [
        'groups' => [
            [
                'title' => 'Page',
                'items' => [
                    ['label' => t('common.nav_home'), 'href' => route_url('/')],
                    ['label' => t('common.nav_about'), 'href' => route_url('/about')],
                    ['label' => t('common.nav_services'), 'href' => route_url('/services')],
                    ['label' => t('common.nav_erp'), 'href' => route_url('/erp')],
                    ['label' => getCurrentLang() === 'th' ? 'ผลงาน' : 'Portfolio', 'href' => route_url('/portfolio')],
                    ['label' => t('common.nav_contact'), 'href' => route_url('/contact')],
                ],
            ],
        ],
    ],
    'ERP / ERM' => [
        'groups' => [
            [
                'title' => 'ERP & Business Management',
                'items' => [
                    ['label' => 'ERP System', 'href' => route_url('/erp#erp-system')],
                    ['label' => 'Accounting & Finance', 'href' => route_url('/article', ['id' => 39])],
                    ['label' => 'Sales / Purchase', 'href' => route_url('/article', ['id' => 40])],
                    ['label' => 'Inventory / Warehouse', 'href' => route_url('/article', ['id' => 41])],
                ],
            ],
            [
                'title' => 'ERM & CRM Systems',
                'items' => [
                    ['label' => 'Customer Management', 'href' => route_url('/article', ['id' => 42])],
                    ['label' => 'Lead Management', 'href' => route_url('/erp#lead-management')],
                    ['label' => 'Customer Service', 'href' => route_url('/article', ['id' => 29])],
                    ['label' => 'Partner / Supplier Management', 'href' => route_url('/article', ['id' => 30])],
                ],
            ],
            [
                'title' => 'HR & Workflow Systems',
                'items' => [
                    ['label' => 'HRM System', 'href' => route_url('/article', ['id' => 31])],
                    ['label' => 'Attendance / Leave', 'href' => route_url('/article', ['id' => 32])],
                    ['label' => 'Payroll', 'href' => route_url('/article', ['id' => 14])],
                    ['label' => 'Workflow Approval', 'href' => route_url('/article', ['id' => 34])],
                ],
            ],
        ],
    ],
    'DIGITAL PLATFORM' => [
        'groups' => [
            [
                'title' => 'Digital Platforms & Business Systems',
                'items' => [
                    ['label' => 'Website / Responsive / CMS', 'href' => route_url('/article', ['id' => 35])],
                    ['label' => 'Mobile App / Mobile Site', 'href' => route_url('/article', ['id' => 36])],
                    ['label' => 'E-commerce', 'href' => route_url('/article', ['id' => 37])],
                    ['label' => 'Custom Web Application', 'href' => route_url('/services/digital-platform#custom-web')],
                    ['label' => 'Membership / Portal System', 'href' => route_url('/services/digital-platform#membership')],
                ],
            ],
            [
                'title' => 'Communication & Engagement',
                'items' => [
                    ['label' => 'SMS Service', 'href' => route_url('/services/digital-platform#sms')],
                    ['label' => 'Email Marketing', 'href' => route_url('/services/digital-platform#email')],
                    ['label' => 'Chatbot / Live Chat', 'href' => route_url('/services/digital-platform#chatbot')],
                    ['label' => 'Game / Interactive Campaign', 'href' => route_url('/services/digital-platform#game')],
                ],
            ],
            [
                'title' => 'Data & Learning Systems',
                'items' => [
                    ['label' => 'Big Data', 'href' => route_url('/services/digital-platform#bigdata')],
                    ['label' => 'E-learning', 'href' => route_url('/services/digital-platform#elearning')],
                    ['label' => 'Dashboard', 'href' => route_url('/services/digital-platform#dashboard')],
                    ['label' => 'Data Management', 'href' => route_url('/services/digital-platform#data-management')],
                ],
            ],
        ],
    ],
    'ONLINE MARKETING' => [
        'groups' => [
            [
                'title' => 'Strategy & Growth',
                'items' => [
                    ['label' => 'Digital Marketing Consultant', 'href' => route_url('/services/online-marketing#consultant')],
                    ['label' => 'Media Planner / PR & Media Strategy', 'href' => route_url('/services/online-marketing#media-planner')],
                    ['label' => 'SEO', 'href' => route_url('/article-detail-mockup')],
                    ['label' => 'Social Network', 'href' => route_url('/services/online-marketing#social')],
                    ['label' => 'Online Campaign', 'href' => route_url('/services/online-marketing#campaign')],
                ],
            ],
            [
                'title' => 'Performance & Analytics',
                'items' => [
                    ['label' => 'Monitoring & Analysis', 'href' => route_url('/services/online-marketing#monitoring')],
                    ['label' => 'Campaign Performance Report', 'href' => route_url('/services/online-marketing#report')],
                    ['label' => 'Return on Investment (ROI)', 'href' => route_url('/services/online-marketing#roi')],
                    ['label' => 'Productivity Analysis', 'href' => route_url('/services/online-marketing#productivity')],
                ],
            ],
            [
                'title' => 'Content & Advertising',
                'items' => [
                    ['label' => 'Content Strategy', 'href' => route_url('/services/online-marketing#content-strategy')],
                    ['label' => 'Ads Management', 'href' => route_url('/services/online-marketing#ads')],
                    ['label' => 'Social Media Content', 'href' => route_url('/services/online-marketing#social-content')],
                    ['label' => 'Search Engine Marketing', 'href' => route_url('/services/online-marketing#sem')],
                ],
            ],
        ],
    ],
    'CREATIVE / DESIGN' => [
        'groups' => [
            [
                'title' => 'Design & Digital Experience',
                'items' => [
                    ['label' => 'Web Design', 'href' => route_url('/services/creative-design#web-design')],
                    ['label' => 'UX/UI Design', 'href' => route_url('/services/creative-design#ux-ui')],
                    ['label' => 'Cartoon & Character Design', 'href' => route_url('/services/creative-design#cartoon')],
                    ['label' => 'Infographic', 'href' => route_url('/services/creative-design#infographic')],
                ],
            ],
            [
                'title' => 'Motion & Video Production',
                'items' => [
                    ['label' => 'Animation TV & YouTube Online', 'href' => route_url('/services/creative-design#animation')],
                    ['label' => 'Motion VDO', 'href' => route_url('/article', ['id' => 14])],
                    ['label' => 'Video Editing', 'href' => route_url('/services/creative-design#video-editing')],
                    ['label' => 'Presentation Video', 'href' => route_url('/services/creative-design#presentation')],
                ],
            ],
            [
                'title' => 'Media & Publishing',
                'items' => [
                    ['label' => 'E-Magazine', 'href' => route_url('/services/creative-design#emagazine')],
                    ['label' => 'Print Ads', 'href' => route_url('/services/creative-design#print-ads')],
                    ['label' => 'Online Banner', 'href' => route_url('/services/creative-design#online-banner')],
                    ['label' => 'Key Visual Design', 'href' => route_url('/services/creative-design#key-visual')],
                ],
            ],
        ],
    ],
];

$socialLinks = [
    ['label' => 'Facebook', 'href' => 'https://www.facebook.com/'],
    ['label' => 'Instagram', 'href' => 'https://www.instagram.com/'],
    ['label' => 'Line', 'href' => 'https://line.me/'],
    ['label' => 'X', 'href' => 'https://x.com/'],
];
?>

<footer class="overflow-hidden">

    <div id="footerSitemapSection" style="background-color: #ffffff; transition: background-color 0.3s ease;">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6">

            <div class="js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 100ms;">

                <button
                    type="button"
                    id="footerSitemapToggle"
                    class="group mx-auto flex w-full cursor-pointer flex-col items-center justify-center bg-transparent py-6 text-center transition-all duration-300 hover:opacity-80 focus:outline-none"
                    aria-expanded="false"
                    aria-controls="footerSitemapPanel"
                >
                    <span id="footerSitemapLabel" class="mb-2 tracking-[2px]" style="color: #043B94; font-size: 25px; font-weight: 700;">SITEMAP</span>

                    <span
                        id="footerSitemapArrow"
                        class="inline-flex items-center justify-center transition-transform duration-300"
                        style="width: 28px; height: 28px;"
                        aria-hidden="true"
                    >
                        <svg id="footerSitemapArrowSvg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #043B94;">
                            <polyline points="7 6 12 11 17 6"></polyline>
                            <polyline points="7 13 12 18 17 13"></polyline>
                        </svg>
                    </span>
                </button>

                <div id="footerSitemapPanel" class="overflow-hidden" style="height: 0px;">
                    <div class="px-4 sm:px-4 lg:px-0 pt-5 pb-6" id="footerSitemapPanelInner">
                        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5 dt-sitemap-grid" data-footer-content>
                            <?php
                            $targetTitles = [
                                'PAGE',
                                'ERP / ERM',
                                'DIGITAL PLATFORM',
                                'ONLINE MARKETING',
                                'CREATIVE / DESIGN',
                            ];

                            $renderColumn = function($title, $column) {
                                $groups = $column['groups'] ?? [];
                                ?>
                                <section class="space-y-4 lg:space-y-0 mt-4 lg:mt-0">
                                    <h3 class="text-2xl font-bold tracking-wider text-[#043B94] border-b-0 pb-2 dt-sitemap-title"><?= e($title) ?></h3>
                                    <div class="flex flex-col gap-0 dt-sitemap-groups">
                                        <?php foreach ($groups as $group): ?>
                                            <div class="space-y-0">
                                                <ul class="list-none border-l-2 border-[#94a3b8] pl-4 m-0 space-y-3 py-0 dt-sitemap-list">
                                                    <?php foreach (($group['items'] ?? []) as $item): ?>
                                                        <li class="flex items-center gap-2">
                                                            <span class="text-[#043B94] shrink-0 text-sm dt-sitemap-bullet">&bull;</span>
                                                            <a class="inline-block py-0.5 text-[#043B94] transition-all duration-300 hover:text-[#0663F6] hover:translate-x-1 text-[17px] dt-sitemap-link" href="<?= $item['href'] ?? '#' ?>">
                                                                <?= e($item['label'] ?? '') ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                                <?php
                            };

                            foreach ($targetTitles as $tTitle) {
                                $col = $structuredSitemap[$tTitle] ?? null;
                                if ($col) {
                                    $renderColumn($tTitle, $col);
                                } else {
                                    $renderColumn($tTitle, ['groups' => [['title' => $tTitle, 'items' => []]]]);
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div></div></div>    <style>
        #footerInfoSection { background-color: #022862 !important; }
        #footerSitemapToggle:hover #footerSitemapLabel,
        #footerSitemapToggle:hover #footerSitemapArrowSvg { color: #0663F6 !important; transition: color 0.3s ease; }
        #footerInfoGrid { display: grid; gap: 2rem; align-items: center; grid-template-columns: 1fr; }
        .footer-bottom-bar { margin-top: 3rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; }
        .dt-sitemap-list { border-left: 2px solid #94a3b8; }
        @media (min-width: 768px) { 
            #footerInfoGrid { grid-template-columns: 1fr 2fr 1.5fr; } 
            .footer-bottom-bar { padding: 0; }
            .address-text { white-space: nowrap !important; }
        }
        @media (min-width: 1024px) {
            .dt-sitemap-grid { grid-template-columns: repeat(5, minmax(0, 1fr)) !important; gap: 1rem !important; }
            .dt-sitemap-title { font-size: 14.5px !important; font-weight: 700 !important; white-space: nowrap !important; border-bottom: none !important; margin-bottom: 0.5rem !important; padding-bottom: 0 !important; color: #043B94 !important; }
            .dt-sitemap-groups { gap: 0 !important; }
            .dt-sitemap-list { border-left: 1px solid #94a3b8 !important; padding-left: 8px !important; margin-top: 0 !important; margin-bottom: 0 !important; padding-top: 4px !important; padding-bottom: 4px !important; gap: 8px !important; display: flex !important; flex-direction: column !important; }
            .dt-sitemap-list > li { margin: 0 !important; }
            .dt-sitemap-link { font-size: 12.5px !important; font-weight: 500 !important; color: #043B94 !important; line-height: 1.3 !important; }
            .dt-sitemap-link:hover { color: #0663F6 !important; }
            .dt-sitemap-bullet { font-size: 16px !important; color: #043B94 !important; }
        }
        /* Responsiveness for iPad Mini / Tablets (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            #footerInfoGrid { grid-template-columns: 1fr 1.8fr 1.5fr !important; gap: 1rem !important; }
            .address-text { white-space: normal !important; }
            .footer-contact-block { margin-left: auto !important; }
        }
    </style>
    <div id="footerInfoSection" style="background-color: #FFFFFFE5 !important; color: #e2e8f0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 2.5rem 1.5rem;">
            <hr style="border: 0; border-top: 1px solid #cbd5e1; margin-bottom: 2rem;">
            <div style="display: grid; gap: 2rem; grid-template-columns: 1fr; align-items: center;">
            <div id="footerInfoGrid">

                    <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                        <div style="height: 120px; width: 160px; overflow: hidden; display: flex; justify-content: center;">
                            <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK Logo" style="height: 100%; width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <span style="font-weight: 700; color: #0663F6; font-size: 1.125rem;"><?= e($officeLabel) ?></span>
                        <span class="address-text" style="font-size: 0.9rem; color: #054FC5; line-height: 1.5;"><?= e($officeValue) ?></span>
                    </div>

                    <div class="footer-contact-block" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; align-items: baseline;">
                            <span style="font-weight: 700; color: #0663F6; font-size: 1.125rem;"><?= e(t('footer.email_label')) ?></span>
                            <a style="font-size: 0.9rem; color: #054FC5; text-decoration: none;" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; align-items: baseline;">
                            <span style="font-weight: 700; color: #0663F6; font-size: 1.125rem;"><?= e(t('footer.phone_label')) ?></span>
                            <a style="font-size: 0.9rem; color: #054FC5; text-decoration: none;" href="tel:<?= e($phoneHref) ?>"><?= e($phone) ?></a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-bottom-bar">
                <style>
                    .footer-bottom-link { font-size: 0.75rem; color: #043B94 !important; text-decoration: none; transition: color 0.3s ease; cursor: pointer; }
                    .footer-bottom-link:hover { color: #0663F6 !important; }
                </style>
                <a class="footer-bottom-link" id="footerPrivacyPolicyBtn">Privacy Policy</a>
                <nav style="display: flex; flex-wrap: wrap; gap: 1rem;" aria-label="Social media links">
                    <?php foreach ($socialLinks as $socialLink): ?>
                        <a class="footer-bottom-link" href="<?= e($socialLink['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($socialLink['label']) ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>
            
            <!-- Privacy Policy Modal -->
            <div id="footerPrivacyModal" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300" style="background-color: rgba(0,0,0,0.5);">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[85vh] flex flex-col transform scale-95 transition-transform duration-300 relative">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-5 md:p-6 border-b border-slate-100">
                        <h3 class="text-xl font-bold text-slate-800">นโยบายความเป็นส่วนตัว (Privacy Policy)</h3>
                        <button id="closeFooterPrivacyModalBtn" class="text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-full transition-colors outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body (Scrollable) -->
                    <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar text-sm md:text-base text-slate-600 leading-relaxed text-left">
                        <p class="mb-4">
                            WEBPARK Co., Ltd. ("เรา" หรือ "WebPark") ในฐานะผู้ควบคุมข้อมูลส่วนบุคคล (Data Controller) ตระหนักและให้ความสำคัญอย่างยิ่งต่อการคุ้มครองข้อมูลส่วนบุคคลและสิทธิความเป็นส่วนตัวของท่าน นโยบายฉบับนี้จัดทำขึ้นตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) เพื่อชี้แจงรายละเอียดเกี่ยวกับการเก็บรวบรวม ใช้ เปิดเผยข้อมูล และการใช้คุกกี้ บนเว็บไซต์ webpark.co.th ทั้งหมด
                        </p>
                        
                        <h5 class="font-bold text-slate-800 mt-6 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shrink-0">1</span> 
                            ขอบเขตข้อมูลส่วนบุคคลที่เราเก็บรวบรวม
                        </h5>
                        <p class="mb-2">เราเก็บรวบรวมข้อมูลส่วนบุคคลของท่านผ่านการใช้งานเว็บไซต์ในกรณีต่างๆ เท่าที่จำเป็นดังนี้:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-1">
                            <li>ชื่อ-นามสกุล, เบอร์โทรศัพท์, และอีเมล ที่ท่านกรอกผ่านแบบฟอร์มติดต่อเรา</li>
                            <li>ข้อมูลองค์กรหรือบริษัทของท่าน (หากมี)</li>
                            <li>รายละเอียดข้อความหรือความต้องการที่ท่านส่งถึงเรา</li>
                        </ul>

                        <h5 class="font-bold text-slate-800 mt-6 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shrink-0">2</span> 
                            วัตถุประสงค์ในการเก็บรวบรวมข้อมูล
                        </h5>
                        <p class="mb-4">
                            ข้อมูลที่ท่านให้จะถูกนำไปใช้เพื่อติดต่อกลับ นำเสนอบริการที่ตรงกับความต้องการของท่าน และปรับปรุงประสิทธิภาพของเว็บไซต์เท่านั้น เราจะไม่มีการเปิดเผยข้อมูลของท่านแก่บุคคลที่สามโดยไม่ได้รับอนุญาต
                        </p>

                        <h5 class="font-bold text-slate-800 mt-6 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shrink-0">3</span> 
                            การเปิดเผยข้อมูลแก่บุคคลที่สาม
                        </h5>
                        <p class="mb-4">
                            เราจะไม่ขาย ให้เช่า หรือเปิดเผยข้อมูลส่วนบุคคลของท่านให้แก่บุคคลภายนอก เว้นแต่กรณีที่จำเป็นเพื่อการให้บริการแก่ท่าน (เช่น ผู้ให้บริการระบบคลาวด์/เซิร์ฟเวอร์ที่ปลอดภัย หรือผู้ให้บริการจัดส่งเอกสาร) หรือในกรณีที่กฎหมายบังคับให้เปิดเผยเท่านั้น
                        </p>

                        <h5 class="font-bold text-slate-800 mt-6 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shrink-0">4</span> 
                            ระยะเวลาจัดเก็บและการรักษาความปลอดภัย
                        </h5>
                        <p class="mb-4">
                            เราจะจัดเก็บข้อมูลส่วนบุคคลของท่านไว้เป็นเวลาตลอดระยะเวลาที่ให้บริการ เพื่อบรรลุวัตถุประสงค์ตามที่แจ้งไว้ โดยเราใช้มาตรการรักษาความปลอดภัยทางเทคนิคที่ได้มาตรฐาน (เช่น การเข้ารหัสข้อมูล SSL) เพื่อปกป้องข้อมูลของท่านจากการเข้าถึง แก้ไข หรือเปิดเผยโดยไม่ได้รับอนุญาต
                        </p>

                        <h5 class="font-bold text-slate-800 mt-6 mb-3 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center text-xs shrink-0">5</span> 
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
                    
                    <!-- Modal Footer -->
                    <div class="p-4 md:p-5 border-t border-slate-100 flex justify-end bg-slate-50 rounded-b-2xl">
                        <button id="closeFooterPrivacyModalBtnBottom" class="px-6 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:bg-blue-600 transition-colors shadow-sm">
                            ปิด (Close)
                        </button>
                    </div>
                </div>
            </div>

        </div>
        
        <div style="background-color: #022862; padding: 1rem 0; text-align: center; width: 100%;">
            <p style="margin: 0; font-size: 0.75rem; color: #ffffff;">Copyright © <?= date('Y') ?> WEBPARK All rights reserved.</p>
        </div>
    </div></footer>

<script>
    (() => {
        const footerSitemapToggle   = document.getElementById('footerSitemapToggle');
        const footerSitemapPanel    = document.getElementById('footerSitemapPanel');
        const footerSitemapPanelInner = document.getElementById('footerSitemapPanelInner');
        const footerSitemapArrow    = document.getElementById('footerSitemapArrow');
        const footerSitemapSection  = document.getElementById('footerSitemapSection');
        const footerSitemapLabel    = document.getElementById('footerSitemapLabel');
        const footerSitemapArrowSvg = document.getElementById('footerSitemapArrowSvg');
        const durationMs = 350;

        const setPanelHeight = () => {
            if (!footerSitemapPanelInner) return;
            footerSitemapPanel.style.height = footerSitemapPanelInner.scrollHeight + 'px';
        };

        const setSitemapState = (isExpanded) => {
            if (footerSitemapToggle) footerSitemapToggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
            if (footerSitemapArrow) footerSitemapArrow.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
            
            if (footerSitemapSection) footerSitemapSection.style.backgroundColor = '#ffffff';
            if (footerSitemapLabel) footerSitemapLabel.style.color = '#043B94';
            if (footerSitemapArrowSvg) footerSitemapArrowSvg.style.color = '#043B94';
        };

        const collapsePanel = () => {
            if (!footerSitemapPanel) return;
            footerSitemapPanel.style.transition = `height ${durationMs}ms ease`;
            footerSitemapPanel.style.height = '0px';
            setSitemapState(false);
        };

        const expandPanel = () => {
            if (!footerSitemapPanel) return;
            footerSitemapPanel.style.transition = `height ${durationMs}ms ease`;
            setPanelHeight();
            setSitemapState(true);
        };

        const initPanel = () => {
            if (!footerSitemapToggle || !footerSitemapPanel) return;
            collapsePanel();

            footerSitemapToggle.addEventListener('click', () => {
                const isExpanded = footerSitemapToggle.getAttribute('aria-expanded') === 'true';
                isExpanded ? collapsePanel() : expandPanel();
            });

            window.addEventListener('resize', () => {
                const isExpanded = footerSitemapToggle.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    setPanelHeight();
                    setSitemapState(true);
                } else {
                    setSitemapState(false);
                }
            });
        };

        initPanel();

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('opacity-0', 'translate-y-5');
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    obs.unobserve(entry.target);
                }
            });
        }, { root: null, rootMargin: '0px', threshold: 0.05 });

        document.querySelectorAll('.js-scroll-animate').forEach(el => observer.observe(el));
        
        // Privacy Policy Modal Logic
        const privacyModal = document.getElementById('footerPrivacyModal');
        const openPrivacyBtn = document.getElementById('footerPrivacyPolicyBtn');
        const closeBtns = [
            document.getElementById('closeFooterPrivacyModalBtn'),
            document.getElementById('closeFooterPrivacyModalBtnBottom')
        ];
        
        if (privacyModal && openPrivacyBtn) {
            const modalContent = privacyModal.querySelector('div.bg-white');
            
            function openModal() {
                privacyModal.classList.remove('hidden');
                // Small delay to allow display:block to apply before animating opacity/transform
                setTimeout(() => {
                    privacyModal.classList.remove('opacity-0');
                    privacyModal.classList.add('opacity-100');
                    if (modalContent) {
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    }
                }, 10);
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
            
            function closeModal() {
                privacyModal.classList.remove('opacity-100');
                privacyModal.classList.add('opacity-0');
                if (modalContent) {
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                }
                setTimeout(() => {
                    privacyModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300); // Wait for transition
            }
            
            openPrivacyBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
            
            closeBtns.forEach(btn => {
                if (btn) btn.addEventListener('click', closeModal);
            });
            
            // Close on click outside
            privacyModal.addEventListener('click', (e) => {
                if (e.target === privacyModal) {
                    closeModal();
                }
            });
        }
    })();
</script>