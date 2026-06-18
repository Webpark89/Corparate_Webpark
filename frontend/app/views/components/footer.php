<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Service.php';

$company = config('company', []) ?: ($company ?? []);
$companyName = $company['name'] ?? '';

$email = $company['contact']['email'] ?? '';
$phone = $company['contact']['phone'] ?? '';
$address = $company['contact']['address'] ?? '';
$officeLabel = 'สำนักงานใหญ่ :';
$officeValue = $company['contact']['address'] ?? '';
$phoneHref = preg_replace('/[^0-9+]/', '', $phone) ?? '';
$serviceModel = new Service();
$dbServices = $serviceModel->getAllActive();

// Build sitemap columns from database
$sitemapColumns = [
    [
        'title' => 'SITEMAP',
        'groups' => [
            [
                'title' => 'Page',
                'items' => [
                    ['label' => 'หน้าแรก', 'href' => '/'],
                    ['label' => 'เกี่ยวกับเรา', 'href' => '/about'],
                    ['label' => 'บริการของเรา', 'href' => '/services'],
                    ['label' => 'ระบบ ERP', 'href' => '/erp'],
                    ['label' => 'บทความ', 'href' => '/article'],
                    ['label' => 'ติดต่อเรา', 'href' => '/contact'],
                ],
            ],
        ],
    ],
];
// Add ERP modules from erp_modules table
$erpModules = $serviceModel->getAllErpModules();
if (!empty($erpModules)) {
    $erpItems = [];
    foreach ($erpModules as $module) {
        $label = $module['title'] ?? $module['module_name'] ?? '';
        $slug = $module['slug'] ?? strtolower(preg_replace('/[^a-z0-9]+/i', '-', $label));
        
        // นำชื่อโมดูลมาเป็นรายการย่อย (Item) แทน
        $erpItems[] = [
            'label' => strtoupper($label),
            'href' => '/erp#' . $slug,
        ];
    }

    if (!empty($erpItems)) {
        $sitemapColumns[] = [
            'title' => 'ERP / ERM', // หัวข้อคอลัมน์หลัก
            'groups' => [
                [
                    'title' => 'ERP / ERM', // ชื่อหัวข้อย่อยสำหรับกด Dropdown
                    'items' => $erpItems,
                ],
            ],
        ];
    }
}

// Add service categories from database
foreach ($dbServices as $service) {
    $features = $serviceModel->getFeaturesByServiceId($service['id']);
    $groupItems = [];
    foreach ($features as $feature) {
        $groupItems[] = [
            'label' => $feature['title'],
            'href' => '/services/' . $service['slug'] . '#' . $feature['slug'],
        ];
    }
    if (!empty($groupItems)) {
        $sitemapColumns[] = [
            'title' => $service['title'],
            'groups' => [
                [
                    'title' => $service['title'],
                    'items' => $groupItems,
                ],
            ],
        ];
    }
}

$socialLinks = [
    ['label' => 'Facebook', 'href' => 'https://www.facebook.com/'],
    ['label' => 'Instagram', 'href' => 'https://www.instagram.com/'],
    ['label' => 'Line', 'href' => 'https://line.me/'],
    ['label' => 'X', 'href' => 'https://x.com/'],
];
?>

<footer class="bg-white text-dark overflow-hidden">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <details class="group js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" id="mainSitemapAccordion" style="transition-delay: 100ms;">
            <summary class="flex w-full cursor-pointer list-none flex-col items-center justify-center bg-transparent py-3 text-center transition-all duration-300 hover:opacity-80">
                <span class="mb-2 text-[24px] font-xl tracking-[2px]">SITEMAP</span>
                <span class="inline-block h-2 w-2 rotate-45 border-r-2 border-b-2 border-dark transition-transform duration-300 group-open:rotate-[225deg]" aria-hidden="true"></span>
                <span class="inline-block h-2 w-2 rotate-45 border-r-2 border-b-2 border-dark transition-transform duration-300 group-open:rotate-[225deg]" aria-hidden="true"></span>
            </summary>

            <div class="pt-5 transition-all duration-500" data-footer-content>
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
                    <?php foreach ($sitemapColumns as $column): ?>
                        <section>
                            <h3 class="text-sm font-semibold text-dark"><?= e($column['title']) ?></h3>
                            <div class="flex flex-col">
                                <?php foreach ($column['groups'] as $group): ?>
                                    <details class="group">
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 bg-transparent py-2 text-sm text-dark font-medium transition-colors hover:text-primary">
                                            <span><?= e($group['title']) ?></span>
                                            <span class="inline-block h-3 w-3 shrink-0 transition-transform duration-300 group-open:rotate-90" aria-hidden="true">›</span>
                                        </summary>
                                        <ul class="my-1.5 ml-0 list-none p-0">
                                            <?php foreach ($group['items'] as $item): ?>
                                                <li>
                                                    <a class="inline-block py-1.5 text-[13px] text-slate-600 transition-all duration-300 hover:text-primary hover:translate-x-1.5" href="<?= e($item['href']) ?>">
                                                        <?= e($item['label']) ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </details>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
                
                </div>
        </details>

        <hr class="mt-10 w-full border-0 border-t border-slate-300 my-4 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 150ms;">

        <div class="pb-5 pt-5">            
            <div class="grid gap-10 text-left md:grid-cols-[1fr_2fr_1.5fr]">
                <div class="flex items-center justify-start js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 200ms;">
                    <div class="flex h-[150px] w-[150px] items-center justify-center overflow-hidden">
                        <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK Logo" class="h-full w-full object-contain p-2 transition-transform duration-500 hover:scale-110">
                    </div>
                </div>

                <div class="pt-10 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 300ms;">
                    <p class="mb-2 font-bold text-primary"><?= e($officeLabel) ?></p>
                    <p class="leading-6 text-slate-700"><?= e($officeValue) ?></p>
                </div>

                <div class="pt-10 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 400ms;">
                    <p class="mb-3 flex items-center gap-2">
                        <span class="font-bold text-primary">อีเมล :</span>
                            <a class="leading-6 text-slate-700 transition hover:text-primary" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="font-bold text-primary">เบอร์โทร :</span>
                        <a class="leading-6 text-slate-700 transition hover:text-primary" href="tel:<?= e($phoneHref) ?>"><?= e($phone) ?></a>
                    </p>
                </div>
            </div>

<div class="mt-2 flex flex-col justify-between gap-4 md:flex-row md:items-center js-scroll-animate transition-all duration-700 ease-out opacity-100 translate-y-0" style="transition-delay: 500ms;">                <a class="inline-block text-slate-600 transition-all duration-300 hover:text-primary hover:-translate-y-1 font-medium" href="#privacy-policy">Privacy Policy</a>
                <nav class="flex flex-wrap gap-3 md:gap-0" aria-label="Social media links">
                    <?php foreach ($socialLinks as $socialLink): ?>
                        <a class="ml-0 inline-block text-slate-600 font-medium transition-all duration-300 hover:text-primary hover:-translate-y-1 md:ml-5" href="<?= e($socialLink['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($socialLink['label']) ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>

    </div>

    <div class="bg-dark py-3 text-center border-t border-slate-200 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 600ms;">
        <p class="m-0 text-sm text-white font-medium">Copyright © <?= date('Y') ?> WEBPARK All rights reserved.</p>
    </div>
</footer>

<script>
    (() => {
        // Logic สำหรับ Sitemap Accordion เปิดแล้วเลื่อนหน้าจอ
        const mainAccordion = document.getElementById('mainSitemapAccordion');

        if (mainAccordion) {
            mainAccordion.addEventListener('toggle', () => {
                if (!mainAccordion.open) return;

                setTimeout(() => {
                    const content = mainAccordion.querySelector('[data-footer-content]');
                    if (content) {
                        content.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                }, 150); 
            });
        }

        // JS สลับ Tailwind Classes เพื่อทำ Fade-in animation แทนการใช้ <style>
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.05
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // เอาคลาสที่ซ่อนไว้ออก
                    entry.target.classList.remove('opacity-0', 'translate-y-5');
                    // ใส่คลาสที่ทำให้มองเห็นและเลื่อนกลับที่เดิม
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    
                    observer.unobserve(entry.target); 
                }
            });
        }, observerOptions);

        document.querySelectorAll('.js-scroll-animate').forEach((el) => {
            observer.observe(el);
        });
    })();
</script>