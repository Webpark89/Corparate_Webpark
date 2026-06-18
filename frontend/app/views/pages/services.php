<?php
declare(strict_types=1);

$services = [];

// ดึงข้อมูลจากฐานข้อมูล (อ้างอิงตาราง service และ service_features)
try {
    $serviceModel = new Service();

    // ดึงบริการหลัก (Service)
    $stmt = db()->prepare("SELECT * FROM service WHERE is_active = 1 ORDER BY id ASC");
    $stmt->execute();
    $dbServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // จัดเตรียมชุดไอคอน (ถ้าใน DB ไม่มีคอลัมน์ icon ให้ใช้ไอคอนมาตรฐานแทน)
    $defaultIcons = [
        'online-marketing' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
        'creative-design' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
        'digital-platform' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
        'erp' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4'
    ];

    foreach ($dbServices as $row) {
        $slug = (string) ($row['slug'] ?? '');
        $icon = $defaultIcons[$slug ?: 'erp'] ?? $defaultIcons['erp'];

        if ((int) ($row['id'] ?? 0) === 4) {
            $erpItems = [];

            $erpModules = $serviceModel->getAllErpModules();
            foreach ($erpModules as $mod) {
                $moduleId = (int) ($mod['id'] ?? 0);

                $moduleTitle = (string) ($mod['title'] ?? $mod['name'] ?? $mod['feature_name'] ?? '');
                if ($moduleTitle === '') {
                    $moduleTitle = (string) ($mod['label'] ?? 'ERP Module');
                }

                if ($moduleId <= 0) {
                    continue;
                }

                $moduleFeatures = $serviceModel->getErpModuleFeatures($moduleId);

                foreach ($moduleFeatures as $feat) {
                    $featName = (string) ($feat['feature_name'] ?? $feat['title'] ?? '');
                    if ($featName === '') {
                        continue;
                    }

                    $erpItems[] = [
                        'title' => $featName,
                        // ให้ใช้ description ระดับ feature ตามที่ผู้ใช้ต้องการ (column description ของ erp_modules)
                        'summary' => (string) ($mod['description'] ?? ''),
                        'description' => (string) ($mod['description'] ?? ''),
                    ];


                }
            }

            $services[] = [
                'id' => (int) ($row['id'] ?? 0),
                'title' => (string) ($row['title'] ?? 'ERP / ERM'),
                'description' => (string) ($row['summary'] ?? ''),
                'image' => !empty($row['image']) ? asset_url($row['image']) : asset_url('images/service-placeholder.jpg'),
                'icon' => $icon,
                'items' => $erpItems,
            ];

            continue;
        }

        // ดึงฟีเจอร์ย่อย (Service Features) ที่ตรงกับ service_id (ไม่ใช่ ERP)
        $featStmt = db()->prepare("SELECT * FROM service_features WHERE service_id = ? AND is_active = 1 ORDER BY sort_order ASC");
        $featStmt->execute([$row['id']]);
        $features = $featStmt->fetchAll(PDO::FETCH_ASSOC);


        $services[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['summary'],
            'image' => !empty($row['image']) ? asset_url($row['image']) : asset_url('images/service-placeholder.jpg'),
            'icon' => $icon,
            'items' => $features,
        ];
    }
} catch (Exception $e) {
    // กรณีต่อ DB ไม่ได้ หรือไม่มีข้อมูล จะใช้ข้อมูลนี้โชว์แทน (Mockup)
    $services = [
        [
            'title' => 'ERP / ERM',
            'description' => 'พัฒนาระบบบริหารจัดการองค์กร เพื่อเพิ่มประสิทธิภาพการทำงาน เชื่อมโยงข้อมูล และรองรับการเติบโตของธุรกิจ',
            'image' => asset_url('images/erp-hand.jpg'),
            'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
            'items' => [
                ['title' => 'ERP & Business Management', 'summary' => 'ระบบบริหารจัดการทรัพยากรองค์กรแบบครบวงจร'],
                ['title' => 'ERM & CRM Systems', 'summary' => 'บริหารความสัมพันธ์ลูกค้าและควบคุมความเสี่ยง'],
                ['title' => 'HR & Workflow Systems', 'summary' => 'ระบบจัดการบุคลากรและอนุมัติเอกสารออนไลน์']
            ]
        ],
        // ... (เพิ่มบริการอื่นๆ เผื่อไว้ได้ตามต้องการ)
    ];
}
?>

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0">
        <img src="<?= e(asset_url('images/bg-5.png')) ?>" alt="WEBPARK Solutions Background" class="w-full h-full object-cover object-center opacity-70 mix-blend-screen">
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary  mb-6 shadow-sm">
                    <span class="text-blue-500 font-bold">+</span>
                    <span class="text-xs md:text-sm font-semibold text-primary uppercase tracking-wide">OUR SERVICE</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-8xl font-lg leading-[1.1] mb-2 tracking-tighter">
    <span class="bg-gradient-to-r from-[#898F98] to-[#000208] bg-clip-text text-transparent">ความเชี่ยวชาญ</span><br>
    <span class="bg-gradient-to-r from-[#003380] to-[#0055ff] bg-clip-text text-transparent">และจุดเด่น</span>
</h1>

                <p class="mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    มากกว่า 20 ปี ที่เราสร้างสรรค์โซลูชันดิจิทัลครบวงจร ผสานเทคโนโลยี ความเชี่ยวชาญ และความเข้าใจธุรกิจ เพื่อเพิ่มประสิทธิภาพ สร้างการเติบโต และยกระดับองค์กรสู่อนาคตอย่างยั่งยืน
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ปรึกษาผู้เชี่ยวชาญ
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    
                    <a href="#about" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5">
    <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all hover:bg-slate-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z" />
        </svg>
    </div>
    <span class="text-slate-800 text-lg font-semibold hover:text-slate-900 transition-colors">
        ดูวิดีโอแนะนำ
    </span>
</a>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="services" class="bg-white py-20 lg:py-28 font-sans">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        
        <div class="mb-16 max-w-4xl">
            <div class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-4 block">OUR SERVICES</div>
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-dark leading-tight mb-4">
                บริการของเรา ครอบคลุมทุกมิติธุรกิจดิจิทัล
            </h2>
            <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                WEBPARK ให้บริการแบบครบวงจร ตั้งแต่การวางแผน ออกแบบ พัฒนา ไปจนถึงการดูแลหลังการใช้งาน<br>
                เพื่อช่วยให้องค์กรเพิ่มประสิทธิภาพ ลดต้นทุน และเติบโตได้อย่างยั่งยืนในยุคดิจิทัล
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
            <?php foreach ($services as $service): ?>
                
                <article class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden group hover:shadow-lg hover:border-blue-100 transition-all duration-300">
                    
                    <div class="w-full aspect-[21/9] bg-slate-100 overflow-hidden relative m-4 md:m-6 md:mb-0 rounded-2xl w-[calc(100%-2rem)] md:w-[calc(100%-3rem)]">
                        <img src="<?= e(asset_url('images/service-home.png')) ?>" alt="<?= e($service['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>

                    <div class="p-6 md:p-8">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl border border-blue-100 flex items-center justify-center text-primary shrink-0 bg-blue-50/50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= e($service['icon']) ?>" />
                                </svg>
                            </div>
                            <h3 class="text-xl md:text-2xl font-bold text-dark group-hover:text-primary transition-colors">
                                <?= e($service['title']) ?>
                            </h3>
                        </div>
                        
                        <p class="text-slate-500 text-[13px] md:text-sm leading-relaxed mb-6 h-auto lg:h-16">
                            <?= e($service['description']) ?>
                        </p>

                        <?php if (!empty($service['items']) && is_array($service['items'])): ?>
                            <div class="space-y-0 divide-y divide-slate-100 border-t border-slate-100">
                                <?php foreach ($service['items'] as $item): ?>
                                    <?php
                                        // จัดการข้อมูล Feature
                                        $itemTitle = is_array($item) ? ($item['title'] ?? '') : $item;
                                        // ดึงข้อมูล summary เพื่อมาแสดงตอนกด Dropdown
                                        $itemSummary = is_array($item) ? ($item['summary'] ?? 'บริการและให้คำปรึกษาสำหรับหมวดหมู่นี้แบบครบวงจร') : '';
                                    ?>
                                    
                                    <div class="group/feature">
                                        <button type="button" onclick="toggleServiceDropdown(this)" class="w-full flex items-center justify-between py-4 text-dark text-[13px] md:text-sm font-medium hover:text-primary transition-colors cursor-pointer focus:outline-none">
                                            <span class="text-left"><?= e($itemTitle) ?></span>
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary transition-transform duration-300 transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                        
                                        <div class="hidden px-2 pb-4 text-slate-500 text-[13px] leading-relaxed transition-all">
                                            <?php
                                                $itemDescription = is_array($item) ? ($item['description'] ?? '') : '';
                                            ?>
                                            <?= e(($itemDescription !== '' ? $itemDescription : $itemSummary)) ?>
                                        </div>

                                    </div>

                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </article>

            <?php endforeach; ?>
        </div>

    </div>
</section>

<script>
    function toggleServiceDropdown(button) {
        // หา Content ที่ถูกซ่อนไว้ใต้ปุ่ม
        const content = button.nextElementSibling;
        // หาไอคอนลูกศรในปุ่ม
        const icon = button.querySelector('svg');
        
        // สลับสถานะเปิด-ปิด
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-90'); // หมุนลูกศรลง
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-90'); // คืนค่าลูกศร
        }
    }
</script>