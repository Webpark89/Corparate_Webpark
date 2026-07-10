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
$officeLabel = 'สำนักงานใหญ่ :';
$officeValue = $company['contact']['address'] ?? '525/89 ซอยลาดพร้าว126 แขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310';
$phoneHref = preg_replace('/[^0-9+]/', '', $phone) ?? '';

$structuredSitemap = [
    'PAGE' => [
        'groups' => [
            [
                'title' => 'Page',
                'items' => [
                    ['label' => t('common.nav_home'), 'href' => '/Corparate_Webpark'],
                    ['label' => t('common.nav_about'), 'href' => '/Corparate_Webpark/about'],
                    ['label' => t('common.nav_services'), 'href' => '/Corparate_Webpark/services'],
                    ['label' => t('common.nav_erp'), 'href' => '/Corparate_Webpark/erp'],
                    ['label' => getCurrentLang() === 'th' ? 'ผลงาน' : 'Portfolio', 'href' => '/Corparate_Webpark/portfolio'],
                    ['label' => t('common.nav_contact'), 'href' => '/Corparate_Webpark/contact'],
                ],
            ],
        ],
    ],
    'ERP / ERM' => [
        'groups' => [
            [
                'title' => 'ERP & Business Management',
                'items' => [
                    ['label' => 'ERP System', 'href' => '/Corparate_Webpark/erp#erp-system'],
                    ['label' => 'Accounting & Finance', 'href' => '/Corparate_Webpark/erp#accounting'],
                    ['label' => 'Sales / Purchase', 'href' => '/Corparate_Webpark/erp#sales'],
                    ['label' => 'Inventory / Warehouse', 'href' => '/Corparate_Webpark/erp#inventory'],
                ],
            ],
            [
                'title' => 'ERM & CRM Systems',
                'items' => [
                    ['label' => 'Customer Management', 'href' => '/Corparate_Webpark/erp#crm'],
                    ['label' => 'Lead Management', 'href' => '/Corparate_Webpark/erp#lead-management'],
                    ['label' => 'Customer Service', 'href' => '/Corparate_Webpark/erp#customer-service'],
                    ['label' => 'Partner / Supplier Management', 'href' => '/Corparate_Webpark/erp#partner'],
                ],
            ],
            [
                'title' => 'HR & Workflow Systems',
                'items' => [
                    ['label' => 'HRM System', 'href' => '/Corparate_Webpark/erp#hrm'],
                    ['label' => 'Attendance / Leave', 'href' => '/Corparate_Webpark/erp#attendance'],
                    ['label' => 'Payroll', 'href' => '/Corparate_Webpark/erp#payroll'],
                    ['label' => 'Workflow Approval', 'href' => '/Corparate_Webpark/erp#workflow'],
                ],
            ],
        ],
    ],
    'DIGITAL PLATFORM' => [
        'groups' => [
            [
                'title' => 'Digital Platforms & Business Systems',
                'items' => [
                    ['label' => 'Website / Responsive / CMS', 'href' => '/Corparate_Webpark/services/digital-platform#website'],
                    ['label' => 'Mobile App / Mobile Site', 'href' => '/Corparate_Webpark/services/digital-platform#mobile'],
                    ['label' => 'E-commerce', 'href' => '/Corparate_Webpark/services/digital-platform#ecommerce'],
                    ['label' => 'Custom Web Application', 'href' => '/Corparate_Webpark/services/digital-platform#custom-web'],
                    ['label' => 'Membership / Portal System', 'href' => '/Corparate_Webpark/services/digital-platform#membership'],
                ],
            ],
            [
                'title' => 'Communication & Engagement',
                'items' => [
                    ['label' => 'SMS Service', 'href' => '/Corparate_Webpark/services/digital-platform#sms'],
                    ['label' => 'Email Marketing', 'href' => '/Corparate_Webpark/services/digital-platform#email'],
                    ['label' => 'Chatbot / Live Chat', 'href' => '/Corparate_Webpark/services/digital-platform#chatbot'],
                    ['label' => 'Game / Interactive Campaign', 'href' => '/Corparate_Webpark/services/digital-platform#game'],
                ],
            ],
            [
                'title' => 'Data & Learning Systems',
                'items' => [
                    ['label' => 'Big Data', 'href' => '/Corparate_Webpark/services/digital-platform#bigdata'],
                    ['label' => 'E-learning', 'href' => '/Corparate_Webpark/services/digital-platform#elearning'],
                    ['label' => 'Dashboard', 'href' => '/Corparate_Webpark/services/digital-platform#dashboard'],
                    ['label' => 'Data Management', 'href' => '/Corparate_Webpark/services/digital-platform#data-management'],
                ],
            ],
        ],
    ],
    'ONLINE MARKETING' => [
        'groups' => [
            [
                'title' => 'Strategy & Growth',
                'items' => [
                    ['label' => 'Digital Marketing Consultant', 'href' => '/Corparate_Webpark/services/online-marketing#consultant'],
                    ['label' => 'Media Planner / PR & Media Strategy', 'href' => '/Corparate_Webpark/services/online-marketing#media-planner'],
                    ['label' => 'SEO', 'href' => '/Corparate_Webpark/services/online-marketing#seo'],
                    ['label' => 'Social Network', 'href' => '/Corparate_Webpark/services/online-marketing#social'],
                    ['label' => 'Online Campaign', 'href' => '/Corparate_Webpark/services/online-marketing#campaign'],
                ],
            ],
            [
                'title' => 'Performance & Analytics',
                'items' => [
                    ['label' => 'Monitoring & Analysis', 'href' => '/Corparate_Webpark/services/online-marketing#monitoring'],
                    ['label' => 'Campaign Performance Report', 'href' => '/Corparate_Webpark/services/online-marketing#report'],
                    ['label' => 'Return on Investment (ROI)', 'href' => '/Corparate_Webpark/services/online-marketing#roi'],
                    ['label' => 'Productivity Analysis', 'href' => '/Corparate_Webpark/services/online-marketing#productivity'],
                ],
            ],
            [
                'title' => 'Content & Advertising',
                'items' => [
                    ['label' => 'Content Strategy', 'href' => '/Corparate_Webpark/services/online-marketing#content-strategy'],
                    ['label' => 'Ads Management', 'href' => '/Corparate_Webpark/services/online-marketing#ads'],
                    ['label' => 'Social Media Content', 'href' => '/Corparate_Webpark/services/online-marketing#social-content'],
                    ['label' => 'Search Engine Marketing', 'href' => '/Corparate_Webpark/services/online-marketing#sem'],
                ],
            ],
        ],
    ],
    'CREATIVE / DESIGN' => [
        'groups' => [
            [
                'title' => 'Design & Digital Experience',
                'items' => [
                    ['label' => 'Web Design', 'href' => '/Corparate_Webpark/services/creative-design#web-design'],
                    ['label' => 'UX/UI Design', 'href' => '/Corparate_Webpark/services/creative-design#ux-ui'],
                    ['label' => 'Cartoon & Character Design', 'href' => '/Corparate_Webpark/services/creative-design#cartoon'],
                    ['label' => 'Infographic', 'href' => '/Corparate_Webpark/services/creative-design#infographic'],
                ],
            ],
            [
                'title' => 'Motion & Video Production',
                'items' => [
                    ['label' => 'Animation TV & YouTube Online', 'href' => '/Corparate_Webpark/services/creative-design#animation'],
                    ['label' => 'Motion VDO', 'href' => '/Corparate_Webpark/services/creative-design#motion-vdo'],
                    ['label' => 'Video Editing', 'href' => '/Corparate_Webpark/services/creative-design#video-editing'],
                    ['label' => 'Presentation Video', 'href' => '/Corparate_Webpark/services/creative-design#presentation'],
                ],
            ],
            [
                'title' => 'Media & Publishing',
                'items' => [
                    ['label' => 'E-Magazine', 'href' => '/Corparate_Webpark/services/creative-design#emagazine'],
                    ['label' => 'Print Ads', 'href' => '/Corparate_Webpark/services/creative-design#print-ads'],
                    ['label' => 'Online Banner', 'href' => '/Corparate_Webpark/services/creative-design#online-banner'],
                    ['label' => 'Key Visual Design', 'href' => '/Corparate_Webpark/services/creative-design#key-visual'],
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
                                                            <a class="inline-block py-0.5 text-[#043B94] transition-all duration-300 hover:text-[#0663F6] hover:translate-x-1 text-[17px] dt-sitemap-link" href="<?= e($item['href'] ?? '#') ?>">
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
                            <span style="font-weight: 700; color: #0663F6; font-size: 1.125rem;">อีเมล :</span>
                            <a style="font-size: 0.9rem; color: #054FC5; text-decoration: none;" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.25rem; align-items: baseline;">
                            <span style="font-weight: 700; color: #0663F6; font-size: 1.125rem;">เบอร์โทร :</span>
                            <a style="font-size: 0.9rem; color: #054FC5; text-decoration: none;" href="tel:<?= e($phoneHref) ?>"><?= e($phone) ?></a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-bottom-bar">
                <style>
                    .footer-bottom-link { font-size: 0.75rem; color: #043B94 !important; text-decoration: none; transition: color 0.3s ease; }
                    .footer-bottom-link:hover { color: #0663F6 !important; }
                </style>
                <a class="footer-bottom-link" href="#privacy-policy">Privacy Policy</a>
                <nav style="display: flex; flex-wrap: wrap; gap: 1rem;" aria-label="Social media links">
                    <?php foreach ($socialLinks as $socialLink): ?>
                        <a class="footer-bottom-link" href="<?= e($socialLink['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($socialLink['label']) ?></a>
                    <?php endforeach; ?>
                </nav>
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

        const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const durationMs = reduceMotion ? 0 : 350;

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
    })();
</script>