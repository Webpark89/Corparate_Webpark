<?php

declare(strict_types=1);

/**
 * Services listing page — grid 2-column card layout.
 *
 * Layout  : Hero Section + heading + 2-column card grid + CTA
 * ข้อมูล  : $mockServices (เปลี่ยนเป็น DB query ได้ภายหลัง)
 * รูป     : ใช้ image_placeholder เดิมจาก mockServices
 */

$mockServices = [
    [
        'id'                => 1,
        'icon_emoji'        => '🖥️',
        'title'             => 'ERP / ERM',
        'summary'           => 'พัฒนาระบบบริหารจัดการองค์กร เพื่อเพิ่มประสิทธิภาพการทำงาน เชื่อมโยงข้อมูล และรองรับการเติบโตของธุรกิจ',
        'image_placeholder' => 'images/erp.png',
        'subcategories'     => [
            ['label' => 'ERP & Business Management', 'href' => '/Corparate_Webpark/erp#erp-system'],
            ['label' => 'ERM & CRM Systems',         'href' => '/Corparate_Webpark/erp#crm'],
            ['label' => 'HR & Workflow Systems',      'href' => '/Corparate_Webpark/erp#hrm'],
        ],
    ],
    [
        'id'                => 2,
        'icon_emoji'        => '🌐',
        'title'             => 'Digital Platform',
        'summary'           => 'ออกแบบและพัฒนาแพลตฟอร์มดิจิทัล เว็บไซต์ และระบบธุรกิจออนไลน์ที่ใช้งานง่าย ยืดหยุ่น และตอบโจทย์องค์กร',
        'image_placeholder' => 'images/bg-cta.jpg',
        'subcategories'     => [
            ['label' => 'Digital Platforms & Business Systems', 'href' => '/Corparate_Webpark/services/digital-platform#website'],
            ['label' => 'Communication & Engagement',           'href' => '/Corparate_Webpark/services/digital-platform#chatbot'],
            ['label' => 'Data & Learning Systems',              'href' => '/Corparate_Webpark/services/digital-platform#bigdata'],
        ],
    ],
    [
        'id'                => 3,
        'icon_emoji'        => '📣',
        'title'             => 'Online Marketing',
        'summary'           => 'วางกลยุทธ์การตลาดออนไลน์ เพื่อเพิ่มการมองเห็น สร้างโอกาสทางธุรกิจ และเพิ่มยอดขายได้อย่างวัดผลได้จริง',
        'image_placeholder' => 'images/bg-hand.jpg',
        'subcategories'     => [
            ['label' => 'Strategy & Growth',       'href' => '/Corparate_Webpark/services/online-marketing#consultant'],
            ['label' => 'Performance & Analytics', 'href' => '/Corparate_Webpark/services/online-marketing#monitoring'],
            ['label' => 'Content & Advertising',   'href' => '/Corparate_Webpark/services/online-marketing#ads'],
        ],
    ],
    [
        'id'                => 4,
        'icon_emoji'        => '🎨',
        'title'             => 'Creative / Design',
        'summary'           => 'สร้างสรรค์งานออกแบบดิจิทัลและคอนเทนต์ที่ช่วยสื่อสารแบรนด์ ทั้ง UI/UX, Graphic, Motion และสื่อสารแบรนด์',
        'image_placeholder' => 'images/women-office.jpg',
        'subcategories'     => [
            ['label' => 'Design & Digital Experience', 'href' => '/Corparate_Webpark/services/creative-design#web-design'],
            ['label' => 'Motion & Video Production',   'href' => '/Corparate_Webpark/services/creative-design#animation'],
            ['label' => 'Media & Publishing',          'href' => '/Corparate_Webpark/services/creative-design#emagazine'],
        ],
    ],
];

$services = $mockServices;
?>

<section class="relative bg-slate-50 pt-16 pb-12 lg:pt-24 lg:pb-20 font-sans overflow-hidden">
    <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[500px] h-[500px] bg-blue-200/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[300px] h-[300px] bg-indigo-100/40 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            
            <div class="flex flex-col items-start text-left">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4 border" 
                      style="background-color: #eff6ff; color: #043B94; border-color: #bfdbfe;">
                    + SOLUTIONS by Webpark
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight mb-4" style="color: #022862;">
                    ความเชี่ยวชาญ และจุดเด่น
                </h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-8 max-w-xl">
                    เราผสานเทคโนโลยีและนวัตกรรมดิจิทัลเพื่อขับเคลื่อนธุรกิจของคุณอย่างยั่งยืน ครอบคลุมการยกระดับการทำงานด้วยระบบ ERP/ERM, การสร้างสรรค์ Digital Platform, ขยายการเติบوةด้วย Online Marketing และสื่อสารภาพลักษณ์ที่โดดเด่นผ่านเทรนด์ Creative & Design ล่าสุด
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#our-services" 
                       class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full text-white transition-all duration-200"
                       style="background-color: #043B94; box-shadow: 0 4px 14px rgba(4,59,148,0.25);"
                       onmouseover="this.style.backgroundColor='#022862';"
                       onmouseout="this.style.backgroundColor='#043B94';">
                        ดูบริการของเรา
                    </a>
                    <a href="<?= e(route_url('/contact')) ?>" 
                       class="inline-flex items-center gap-2 font-bold text-sm px-6 py-3 rounded-full border transition-all duration-200"
                       style="background-color: #ffffff; color: #043B94; border-color: #cbd5e1;"
                       onmouseover="this.style.backgroundColor='#f8fafc';"
                       onmouseout="this.style.backgroundColor='#ffffff';">
                        ติดต่อเรา
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-md lg:max-w-lg aspect-square flex items-center justify-center">
                    <img 
                        src="<?= e(asset_url('images/hero-hologram.png')) ?>" 
                        alt="Webpark 3D Hologram Solutions" 
                        class="w-full h-auto object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-102"
                        loading="eager"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80'; this.className='w-full h-full object-cover rounded-3xl shadow-xl';"
                    >
                </div>
            </div>

        </div>
    </div>
</section>

<section id="our-services" class="bg-white pt-16 pb-6 font-sans scroll-mt-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #043B94;">OUR SERVICES</p>
        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-3" style="color: #022862;">
            บริการของเรา ครอบคลุมทุกมิติธุรกิจดิจิทัล
        </h1>
        <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl">
            Webpark ให้บริการแบบครบวงจร ตั้งแต่การวางแผน ออกแบบ พัฒนา ไปจนถึงการดูแลหลังการใช้งาน
            เพื่อช่วยให้องค์กรเพิ่มประสิทธิภาพ ลดต้นทุน และเติบโตได้อย่างยั่งยืนในยุคดิจิทัล
        </p>
    </div>
</section>

<section class="bg-white pb-16 font-sans">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

            <?php foreach ($services as $service):
                $sTitle  = (string)($service['title'] ?? '');
                $sSummary= (string)($service['summary'] ?? '');
                $sEmoji  = (string)($service['icon_emoji'] ?? '');
                $imgSrc  = asset_url($service['image_placeholder'] ?? '');
                $subcats = (array)($service['subcategories'] ?? []);
            ?>

            <div class="group rounded-2xl border border-slate-100 bg-white overflow-hidden flex flex-col"
                 style="box-shadow: 0 2px 12px 0 rgba(4,59,148,0.07);">

                <div class="relative w-full overflow-hidden bg-slate-100" style="aspect-ratio: 16/9;">
                    <img
                        src="<?= e($imgSrc) ?>"
                        alt="<?= e($sTitle) ?>"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        loading="lazy"
                    >
                </div>

                <div class="flex flex-col flex-1 p-5 lg:p-6">

                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl leading-none"><?= e($sEmoji) ?></span>
                        <h2 class="text-lg lg:text-xl font-extrabold" style="color: #022862;"><?= e($sTitle) ?></h2>
                    </div>

                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                        <?= e($sSummary) ?>
                    </p>

                    <?php if (!empty($subcats)): ?>
                    <div class="mt-auto border-t border-slate-100 pt-2 space-y-0.5">
                        <?php foreach ($subcats as $sub):
                            $subLabel = (string)($sub['label'] ?? '');
                            $subHref  = (string)($sub['href'] ?? '#');
                        ?>
                        <a
                            href="<?= e($subHref) ?>"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm text-slate-600 transition-colors duration-150 group/row"
                            style="font-weight: 500;"
                            onmouseover="this.style.backgroundColor='#f0f5ff'; this.style.color='#043B94';"
                            onmouseout="this.style.backgroundColor=''; this.style.color='';"
                        >
                            <span><?= e($subLabel) ?></span>
                            <svg class="w-4 h-4 shrink-0" style="color: #cbd5e1;"
                                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<section class="font-sans pb-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden"
             style="background: linear-gradient(120deg, #011431 0%, #043B94 55%, #1e40af 100%); min-height: 200px;">

            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/2"
                     style="background: url('<?= e(asset_url('images/bg-cta.jpg')) ?>') center/cover no-repeat; opacity: 0.18;"></div>
                <div class="absolute inset-0"
                     style="background: linear-gradient(to right, #011431 40%, transparent 100%);"></div>
            </div>

            <div class="relative px-8 py-14 md:py-16 text-center" style="z-index: 10;">
                <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-4 leading-tight">
                    พร้อมขับเคลื่อนธุรกิจของคุณไปข้างหน้าหรือยัง?
                </h2>
                <p class="text-sm md:text-base max-w-xl mx-auto mb-8 leading-relaxed" style="color: #bfdbfe;">
                    มาคุยกับทีม Webpark เพื่อค้นหาโซลูชันที่เหมาะกับธุรกิจของคุณ
                    ทั้ง Digital Platform, ระบบ AI และ ERP / ERM ในมุมที่ใช้สำหรับองค์กร
                </p>
                <a
                    href="<?= e(route_url('/contact')) ?>"
                    class="inline-flex items-center gap-2 font-bold text-sm px-7 py-3 rounded-full transition-colors duration-200"
                    style="background: #ffffff; color: #043B94; box-shadow: 0 4px 14px rgba(0,0,0,0.15);"
                    onmouseover="this.style.background='#eff6ff';"
                    onmouseout="this.style.background='#ffffff';"
                >
                    เริ่มต้นปรึกษากับเรา
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16 font-sans">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-12">
            <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color: #043B94;">OUR APPROACH</p>
            <h2 class="text-2xl md:text-4xl font-extrabold mb-5 leading-tight" style="color: #022862;">
                แนวคิดในการทำงานของเรา
            </h2>
            <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
                กระบวนการทำงานที่เป็นระบบ เพื่อส่งมอบโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจ และความยั่งยืนของข้อมูลธุรกิจที่องค์กรถือครอง
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $approachSteps = [
                [
                    'number' => '01',
                    'icon'   => asset_url('images/icon-1.png'),
                    'title'  => 'เข้าใจธุรกิจของคุณ',
                    'desc'   => 'ศึกษาความต้องการ วิเคราะห์ปัญหา และกำหนดแนวทางที่เหมาะสมกับธุรกิจของท่านอย่างแท้จริง',
                ],
                [
                    'number' => '02',
                    'icon'   => asset_url('images/icon-2.png'),
                    'title'  => 'ออกแบบให้ใช้งานได้จริง',
                    'desc'   => 'ออกแบบประสบการณ์ใช้งานที่เน้นความง่าย และประสิทธิภาพ ตอบโจทย์ผู้ใช้งานทุกระดับ',
                ],
                [
                    'number' => '03',
                    'icon'   => asset_url('images/icon-3.png'),
                    'title'  => 'ดูแลอย่างต่อเนื่อง',
                    'desc'   => 'ให้บริการหลังการขาย พร้อมทีมซัพพอร์ต และอัปเดตระบบอย่างสม่ำเสมอ',
                ],
                [
                    'number' => '04',
                    'icon'   => asset_url('images/icon-4.png'),
                    'title'  => 'รองรับการเติบโต',
                    'desc'   => 'พัฒนาระบบที่ยืดหยุ่น สามารถขยายตัว และปรับตามธุรกิจที่เติบโตในอนาคต',
                ],
            ];

            foreach ($approachSteps as $step):
            ?>
            <div class="flex flex-col items-start rounded-2xl border border-slate-100 bg-white p-6 transition-all duration-300"
                 style="box-shadow: 0 4px 20px 0 rgba(4,59,148,0.05);">

                <div class="w-14 h-14 shrink-0 rounded-xl bg-blue-50/50 flex items-center justify-center mb-4">
                    <img src="<?= e($step['icon']) ?>"
                         alt="<?= e($step['title']) ?>"
                         class="w-8 h-8 object-contain"
                         onerror="this.onerror=null;this.style.display='none'">
                </div>

                <div class="flex flex-col gap-1.5">
                    <span class="text-xl font-extrabold" style="color: #043B94;"><?= e($step['number']) ?></span>
                    <h3 class="text-base font-extrabold" style="color: #022862;"><?= e($step['title']) ?></h3>
                    <p class="text-slate-500 text-xs md:text-sm leading-relaxed"><?= e($step['desc']) ?></p>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>