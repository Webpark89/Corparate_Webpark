<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Models/Service.php';

$company = config('company', []) ?: ($company ?? []);
$companyName = $company['name'] ?? '';

$email = $company['contact']['email'] ?? '';
$phone = $company['contact']['phone'] ?? '';
$address = $company['contact']['address'] ?? '';
$officeLabel = 'ที่ตั้ง :';
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
                <span class="pt-2 pb-1 text-[24px] font-xl tracking-[2px] text-[#002b7f] group-open:text-white transition-colors duration-300">SITEMAP</span>

                <!-- arrow (CSS border square) -->
                <span class="inline-block h-2 w-2 rotate-45 border-r-2 border-b-2 border-[#002b7f] transition-transform duration-300 group-open:border-white group-open:rotate-[-135deg]" aria-hidden="true"></span>

                <!-- divider line (hidden when open) -->
                <span class="mt-4 w-full max-w-[980px] border-t border-slate-300 group-open:hidden"></span>
            </summary>

            <!-- open/closed background + content -->
            <div class="pt-0 pb-5 mt-0 bg-white group-open:bg-[#001d4f] transition-colors duration-300" data-footer-content>
                <div class="pt-6 group-open:pt-7 transition-all duration-300">
                    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                        <?php foreach ($sitemapColumns as $column): ?>
                            <section>
                                <h3 class="text-[16px] font-extrabold uppercase tracking-wide text-white/95 group-open:text-white text-center sm:text-left"><?= e($column['title']) ?></h3>

                                <div class="mt-4 flex flex-col gap-5">
                                    <?php foreach ($column['groups'] as $group): ?>
                                        <div class="flex items-start gap-3">
                                            <!-- vertical left border -->
                                            <div class="mt-2 h-full w-1 bg-white/60"></div>

                                            <div class="flex-1">
                                                <h4 class="text-[14px] font-bold text-white"><?= e($group['title']) ?></h4>

                                                <ul class="mt-3 pl-4 list-disc text-white">

                                                    <?php foreach ($group['items'] as $item): ?>
                                                        <li class="text-[13px] leading-7">
                                                            <a class="inline-flex text-white hover:text-sky-200 transition-colors" href="<?= e($item['href']) ?>">
                                                                <?= e($item['label']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-10 w-full border-t border-white/20"></div>
            </div>
        </details>

        <hr class="mt-10 w-full border-0 border-t border-slate-300 my-4 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 150ms;">

        <div class="pb-5 pt-5 mb-4">     

        <div class="grid gap-10 text-left md:grid-cols-[1fr_2fr_1.5fr] items-center">
        
        <div class="flex items-center justify-start js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 200ms;">
            <div class="flex h-[150px] w-[150px] items-center justify-center overflow-hidden">
                <img src="<?= e(asset_url('images/logo.png')) ?>" alt="WEBPARK Logo" class="h-full w-full object-contain p-2 transition-transform duration-500 hover:scale-110">
            </div>
        </div>

        <div class="flex flex-col gap-y-1 js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 300ms;">
            <span class="font-bold text-primary leading-tight"><?= e($officeLabel) ?></span>
            <span class="text-sm leading-tight text-slate-700"><?= e($officeValue) ?></span>
        </div>

        <div class="flex justify-end js-scroll-animate opacity-0 translate-y-5 transition-all duration-700 ease-out" style="transition-delay: 400ms;">
            <div class="grid grid-cols-[max-content_max-content] gap-x-3 gap-y-1 text-left items-center">
                <span class="font-bold text-primary leading-tight">อีเมล :</span>
            <a class="text-sm leading-tight text-slate-700 transition hover:text-primary" href="mailto:<?= e($email) ?>"><?= e($email) ?></a>

                <span class="font-bold text-primary leading-tight">เบอร์โทร :</span>
                <a class="text-sm leading-tight text-slate-700 transition hover:text-primary" href="tel:<?= e($phoneHref) ?>"><?= e($phone) ?></a>
            </div>
                </div>

            </div>
        </div>


        <div class="mt-2 mb-2  flex flex-col justify-between gap-4 md:flex-row md:items-center js-scroll-animate transition-all duration-700 ease-out opacity-100 translate-y-0" style="transition-delay: 500ms;">                
            <a class="text-sm inline-block text-slate-400 transition-all duration-300 hover:text-primary hover:-translate-y-1 font-medium" href="#privacy-policy">Privacy Policy</a>
            <nav class="flex flex-wrap gap-8 md:gap-12" aria-label="Social media links">
    <?php foreach ($socialLinks as $socialLink): ?>
        <a class="text-sm inline-block text-slate-400 font-medium transition-all duration-300 hover:text-primary hover:-translate-y-1" href="<?= e($socialLink['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($socialLink['label']) ?></a>
    <?php endforeach; ?>
</nav>
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