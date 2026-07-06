<?php

declare(strict_types=1);

$categories = is_array($categories ?? null) ? $categories : [];
$activeCategorySlug = (string) ($activeCategorySlug ?? 'all');
$fallbackImage = asset_url('images/story.png');
$heroImage = asset_url('images/bg-6.png');
$ctaImage = asset_url('images/bg-cta.jpg');

/**
 * ERP product page view — modules, benefits, and portfolio showcase.
 */


 $mockModules = [
    [
        'id' => 1,
        'name_th' => 'ระบบบริหารการขาย',
        'name_en' => 'Sales Management',
        'description_th' => 'จัดการงานขาย กำหนดราคา บริการลูกค้า และติดตามสถานะการขาย',
        'description_en' => 'Manage sales operations, pricing, customer service, and sales tracking.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
        </svg>'
    ],
    [
        'id' => 2,
        'name_th' => 'ระบบจัดซื้อ',
        'name_en' => 'Purchase Management',
        'description_th' => 'เพิ่มประสิทธิภาพการจัดซื้อและการบริหารผู้ขาย',
        'description_en' => 'Procurement efficiency and supplier management.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>'
    ],
    [
        'id' => 3,
        'name_th' => 'ระบบบริหารสินค้าคงคลัง',
        'name_en' => 'Stock Management',
        'description_th' => 'ควบคุมสต็อกสินค้า ตรวจสอบการเคลื่อนไหว และแจ้งเตือนเมื่อถึงจุดสั่งซื้อ',
        'description_en' => 'Inventory control, stock monitoring, and reorder alerts.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />       
        </svg>'
    ],
    [
        'id' => 4,
        'name_th' => 'ระบบบัญชีและการเงิน',
        'name_en' => 'Accounting & Finance',
        'description_th' => 'บันทึกรายรับรายจ่าย ออกเอกสารทางการเงิน และสรุปงบการเงิน',
        'description_en' => 'Financial and accounting processes, invoicing, and reporting.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
        </svg>'
    ],
    [
        'id' => 5,
        'name_th' => 'ระบบการผลิต',
        'name_en' => 'Production Management',
        'description_th' => 'วางแผนการผลิต ควบคุมทรัพยากร ลดต้นทุน และเพิ่มประสิทธิภาพ',
        'description_en' => 'Production planning, resource control, cost reduction, efficiency.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
        </svg>'
    ],
    [
        'id' => 6,
        'name_th' => 'ระบบบริหารทรัพยากรบุคคล',
        'name_en' => 'Human Resource Management',
        'description_th' => 'จัดการข้อมูลพนักงาน ประเมินผล และติดตามการทำงาน',
        'description_en' => 'Employee data management and performance evaluation.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600 group-hover:text-white">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
        </svg>'
    ],
    [
        'id' => 7,
        'name_th' => 'ระบบอนุมัติและเวิร์กโฟลว์',
        'name_en' => 'Workflow Approval',
        'description_th' => 'กำหนดขั้นตอนการอนุมัติเอกสารและงานต่างๆ เพิ่มความโปร่งใส',
        'description_en' => 'Approval processes and workflow control.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-workflow-icon lucide-workflow text-blue-600 group-hover:text-white"><rect width="8" height="8" x="3" y="3" rx="2"/>
            <path d="M7 11v4a2 2 0 0 0 2 2h4"/><rect width="8" height="8" x="13" y="13" rx="2"/>
        </svg>'
    ],
    [
        'id' => 8,
        'name_th' => 'ระบบลูกค้าสัมพันธ์',
        'name_en' => 'Customer Relationship Management',
        'description_th' => 'จัดการข้อมูลลูกค้าและติดตามความสัมพันธ์เพื่อเพิ่มโอกาสการขาย',
        'description_en' => 'Customer data and relationship tracking.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-handshake-icon lucide-heart-handshake text-blue-600 group-hover:text-white">
            <path d="M19.414 14.414C21 12.828 22 11.5 22 9.5a5.5 5.5 0 0 0-9.591-3.676.6.6 0 0 1-.818.001A5.5 5.5 0 0 0 2 9.5c0 2.3 1.5 4 3 5.5l5.535 5.362a2 2 0 0 0 2.879.052 2.12 2.12 0 0 0-.004-3 2.124 2.124 0 1 0 3-3 2.124 2.124 0 0 0 3.004 0 2 2 0 0 0 0-2.828l-1.881-1.882a2.41 2.41 0 0 0-3.409 0l-1.71 1.71a2 2 0 0 1-2.828 0 2 2 0 0 1 0-2.828l2.823-2.762"/>
        </svg>'
    ],
    [
        'id' => 9,
        'name_th' => 'ระบบควบคุมคุณภาพ',
        'name_en' => 'Quality Control',
        'description_th' => 'ตรวจสอบคุณภาพการผลิต ลดของเสีย และรักษามาตรฐานสินค้า',
        'description_en' => 'Production quality assurance and inspection.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check text-blue-600 group-hover:text-white">
            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
            <path d="m9 12 2 2 4-4"/>
        </svg>'
    ],
    [
        'id' => 10,
        'name_th' => 'ระบบซ่อมบำรุง',
        'name_en' => 'Maintenance',
        'description_th' => 'จัดการการซ่อมบำรุงเครื่องจักร ลด Downtime และยืดอายุการใช้งาน',
        'description_en' => 'Equipment maintenance and downtime reduction.',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wrench-icon lucide-wrench text-blue-600 group-hover:text-white">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"/>
        </svg>'
    ],
];


$mockErpPortfolios = [
    [
        'id' => 1,
        'title' => 'ระบบ ERP บริหารโรงงานผลิตชิ้นส่วน',
        'description' => 'พัฒนาระบบ ERP ครบวงจร เชื่อมโยงฝ่ายจัดซื้อ ฝ่ายผลิต และบัญชี ลดข้อผิดพลาดและต้นทุนสูญเปล่ากว่า 30%',
        'image_path' => 'images/erp.png'
    ],
    [
        'id' => 2,
        'title' => 'ระบบบริหารจัดการสต็อกธุรกิจค้าปลีก',
        'description' => 'เชื่อมต่อข้อมูลหลายสาขาแบบ Real-time พร้อมระบบ POS และสรุปยอดขายรายวันอัตโนมัติ',
        'image_path' => 'images/bg-cta.jpg'
    ],
    [
        'id' => 3,
        'title' => 'แพลตฟอร์มบริหารงานโลจิสติกส์',
        'description' => 'ระบบติดตามสถานะการขนส่ง เชื่อมต่อกับคลังสินค้า พร้อมแดชบอร์ดสรุปประสิทธิภาพการจัดส่ง',
        'image_path' => 'images/bg-hand.jpg'
    ]
];

// ใช้ Mock Data แทน Database ชั่วคราว
$modulesData = $mockModules;
$erpPortfolios = $mockErpPortfolios;
?>
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-entrance {
        opacity: 0;
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    /* 1. แอนิเมชันสำหรับสไลด์ขึ้นจากด้านล่าง (Entrance) */
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up {
        opacity: 0; /* ซ่อนไว้ก่อนเริ่ม */
        animation: fadeSlideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 2. แอนิเมชันสำหรับตัวอักษรสีเหลือบ (Gradient Flow) */
    @keyframes text-gradient-pan {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    .animate-text-gradient {
        background-size: 200% auto;
        animation: text-gradient-pan 6s linear infinite;
    }

    /* คลาสหน่วงเวลา เพื่อให้เนื้อหาไล่ลำดับกันขึ้นมา */
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    .delay-400 { animation-delay: 400ms; }
</style>



<!-- <section class="relative font-sans bg-gradient-to-b from-blue-50/80 to-white overflow-hidden pt-24 pb-20 lg:pt-32 lg:pb-28">
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="animate-entrance inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-blue-200 bg-white mb-6 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            <span class="text-xs md:text-sm font-bold text-primary uppercase tracking-wide">Enterprise Resource Planning</span>
        </div>

        <h1 class="animate-entrance delay-100 text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.2] text-[#022862] mb-6 tracking-tight">
            ยกระดับองค์กรด้วยระบบ <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">ERP</span><br class="hidden sm:block">
            เชื่อมต่อทุกแผนกให้เป็นหนึ่งเดียว
        </h1>

        <p class="animate-entrance delay-200 text-base md:text-lg text-slate-500 max-w-3xl mx-auto leading-relaxed mb-10 font-medium">
            ระบบบริหารจัดการทรัพยากรองค์กรที่ออกแบบมาเพื่อธุรกิจของคุณโดยเฉพาะ ช่วยลดต้นทุน 
            เพิ่มประสิทธิภาพการทำงาน และเปลี่ยนข้อมูลที่ซับซ้อนให้เป็นการตัดสินใจที่แม่นยำ
        </p>

        <div class="animate-entrance delay-300 flex flex-wrap items-center justify-center gap-4">
            <a href="#modules" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                ดูโมดูลทั้งหมด
            </a>
            <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-[#022862] border border-slate-200 text-sm font-semibold rounded-full hover:bg-slate-50 transition-all shadow-sm hover:-translate-y-0.5">
                ปรึกษาผู้เชี่ยวชาญ
            </a>
        </div>
    </div>
</section> -->

<section class="relative overflow-hidden font-sans">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="<?= e($heroImage) ?>" alt="WEBPARK Solutions Background" 
            class="w-full h-full object-cover object-center opacity-100 mix-blend-screen">
            
        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/70 to-white/5"></div>
        <div class="absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-white to-transparent z-10"></div>
    </div>
    <style>
        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideLeft {
            0% { opacity: 0; transform: translateX(50px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        .animate-entrance-up { opacity: 0; animation: fadeSlideUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards; }
        .animate-entrance-left { opacity: 0; animation: fadeSlideLeft 1s cubic-bezier(0.16,1,0.3,1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-scroll { animation: scroll 20s linear infinite; }
        .animate-scroll:hover { animation-play-state: paused; }
    </style>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-12 pb-24 lg:pt-28 lg:pb-32 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-12 lg:gap-20 items-center">
            
            <div class="max-w-2xl">
                <p class="mb-6 tracking-[0.5em] text-xs font-semibold text-[#1a2b6d] uppercase md:text-lg">ระบบนำทางแบบเศษขนมปัง</p>
                
                <h1 class="animate-fade-up delay-200 leading-[1.1] mb-2 tracking-tighter">
                    <span class="text-5xl md:text-6xl lg:text-8xl font-bold bg-gradient-to-r from-[#898F98] via-[#5d636b] to-[#000208] bg-clip-text text-transparent animate-text-gradient inline-block py-3">
                        ERP Systems
                    </span><br>

                    <span class="text-2xl md:text-3xl lg:text-5xl font-medium bg-gradient-to-r from-[#003380] via-[#2563eb] to-[#0055ff] bg-clip-text text-transparent animate-text-gradient inline-block mt-2 py-3" style="animation-delay: -3s;">
                        เชื่อมต่อทุกกระบวนการธุรกิจแบบครบวงจรในแพลตฟอร์มเดียว
                    </span>
                </h1>

                <p class="animate-fade-up delay-300 mt-6 text-[#022862] text-base md:text-lg leading-relaxed max-w-lg mb-10 font-medium">
                    รวบรวมบทความรู้ เทคโนโลยี นวัตกรรม และแนวทางการทำธุรกิจ <br>
                    ครอบคลุม ERP ระบบธุรกิจดิจิทัล การตลาดออนไลน์ AI และโซลูชัน<br>
                    ที่ช่วยพัฒนาองค์กรให้เติบโตได้อย่างยั่งยืน
                </p>
                <div class="animate-entrance-up delay-400 flex flex-wrap items-center gap-4">
                    <a href="<?= e(route_url('/service')) ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-semibold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ดูบริการของเรา
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#about" class="inline-flex items-center gap-4 transition-all hover:-translate-y-0.5 group">
                        <div class="h-14 w-14 bg-white flex items-center justify-center rounded-full shadow-lg border border-slate-200 transition-all group-hover:bg-slate-50 group-hover:shadow-xl group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 fill-current" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="text-slate-800 text-lg font-semibold group-hover:text-primary transition-colors">ดูวิดีโอแนะนำ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6 relative z-20 -mt-10 lg:-mt-18 pb-6 lg:pb-16">
        <div class="w-full rounded-[1rem] bg-white flex flex-col lg:flex-row items-stretch shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-gray-100 overflow-hidden">

            <div class="group flex-1 lg:max-w-[300px] xl:max-w-[320px] flex flex-col justify-between p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-gray-100 shrink-0 bg-white transition-all duration-300 hover:bg-slate-50/50 cursor-pointer">
                <div>
                    <span class="text-primary font-bold text-sm tracking-wide block mb-3">เกี่ยวกับเรา</span>
                    <h2 class="text-[#043B94] text-xl xl:text-2xl font-bold leading-tight mb-4 transition-colors duration-300 group-hover:text-blue-700">
                        เราคือ พาร์ทเนอร์<br>ด้านเทคโนโลยี
                    </h2>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">
                        มุ่งมั่นพัฒนาโซลูชันดิจิทัลที่ตอบโจทย์ธุรกิจยุคใหม่ ด้วยทีมงานมืออาชีพพร้อมแนวคิดและเทคโนโลยีในการยกระดับการทำงานของคุณ
                    </p>
                </div>
            </div>

            <div class="flex-[4] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 w-full">
                <?php
                $serviceCards = [
                    ['icon' => 'icon-3.png', 'title' => 'ERP / ERM',        'desc' => 'ระบบบริหารจัดการองค์กรและควบคุมระบบ เพื่อเพิ่มทุกกระบวนการทำงานอย่างมีประสิทธิภาพ', 'href' => route_url('/erp')],
                    ['icon' => 'icon-2.png', 'title' => 'Digital Platform', 'desc' => 'พัฒนาแพลตฟอร์มดิจิทัลทั้งออนไลน์และออฟไลน์ รองรับการเติบโตและการขยายตัว',              'href' => '#'],
                    ['icon' => 'icon-4.png', 'title' => 'Online Marketing', 'desc' => 'วางกลยุทธ์และทำการตลาดออนไลน์ เพื่อการเข้าถึงกลุ่มเป้าหมาย และผลลัพธ์ที่วัดผลได้',   'href' => '#'],
                    ['icon' => 'icon-1.png', 'title' => 'Creative / Design','desc' => 'ออกแบบและสร้างสรรค์ภาพลักษณ์ของแบรนด์ให้โดดเด่น สร้างการจดจำและตอบโจทย์แคมเปญ',    'href' => '#'],
                ];
                $lastIdx = count($serviceCards) - 1;
                foreach ($serviceCards as $i => $card):
                    $borderClass = $i < $lastIdx ? 'border-b sm:border-b-0 sm:border-r' : '';
                ?>
                    <div class="relative group cursor-pointer flex flex-col justify-between p-6 lg:p-8 <?= $borderClass ?> border-gray-100 bg-white transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 hover:z-10 hover:rounded-xl">
                        <div>
                            <div class="h-14 w-14 mx-auto mb-5 flex items-center justify-center transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover:-translate-y-2 group-hover:scale-110">
                                <img src="<?= e(asset_url('images/' . $card['icon'])) ?>" alt="<?= e($card['title']) ?>" class="h-full w-full object-contain">
                            </div>
                            <h2 class="text-[#043B94] font-bold text-[15px] xl:text-[16px] text-center mb-3 whitespace-nowrap tracking-tight transition-colors duration-300 group-hover:text-blue-600">
                                <?= e($card['title']) ?>
                            </h2>
                            <p class="text-gray-500 text-xs xl:text-sm leading-relaxed mb-6 text-left transition-colors duration-300 group-hover:text-gray-600">
                                <?= e($card['desc']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- <section class="bg-white py-16 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ลดต้นทุน เพิ่มกำไร</h3>
                <p class="text-sm text-slate-500 leading-relaxed">มองเห็นรอยรั่วไหลของต้นทุน ควบคุมงบประมาณและสต็อกสินค้าได้อย่างมีประสิทธิภาพสูงสุด ลดความสูญเสียที่ไม่จำเป็น</p>
            </div>
            
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ข้อมูล Real-Time</h3>
                <p class="text-sm text-slate-500 leading-relaxed">ข้อมูลทุกแผนกเชื่อมโยงถึงกัน อัปเดตแบบวินาทีต่อวินาที ทำให้ผู้บริหารตัดสินใจได้ทันท่วงทีบนฐานข้อมูลที่ถูกต้อง</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#043B94] mb-3">ลดความซ้ำซ้อน</h3>
                <p class="text-sm text-slate-500 leading-relaxed">ทำงานผ่านแพลตฟอร์มเดียว บอกลาการใช้ Excel หลายไฟล์หรือระบบที่ไม่เชื่อมต่อกัน ลดข้อผิดพลาดจากการกรอกข้อมูลซ้ำ (Human Error)</p>
            </div>
        </div>
    </div>
</section> -->

<section id="modules" class="bg-slate-50 py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-600 tracking-tight mb-4">
                ERP modules
            </h2>
            <span class="text-blue 400 font-bold text-xs md:text-sm tracking-widest uppercase mb-3 block">ระบบครอบคลุมทุกกระบวนการทำงาน</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <?php foreach ($modulesData as $module): ?>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-blue-50/60 rounded-full flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-300">
                            <?= $module['icon'] ?>
                        </div>
                        <h3 class="text-lg font-bold text-[#043B94] mb-3 group-hover:text-primary transition-colors">
                            <?= e($module['name_th']) ?> 
                        </h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            <?= e($module['description_th']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php if (!empty($erpPortfolios)): ?>
<!-- <section class="bg-white py-20 font-sans">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between border-b border-slate-200 pb-5 mb-10 gap-4">
            <div>
                <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-1 block">OUR SUCCESS</span>
                <h2 class="text-2xl md:text-3xl font-extrabold leading-none tracking-tight text-[#022862] m-0">
                    ผลงานพัฒนาระบบ ERP
                </h2>
            </div>
            <a href="<?= e(route_url('/portfolio')) ?>" class="group flex items-center gap-1.5 text-sm font-bold text-primary hover:text-blue-700 transition-colors">
                ดูผลงานทั้งหมด
                <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($erpPortfolios as $port): 
                $imgSrc = asset_url($port['image_path']);
                $detailUrl = isset($port['slug']) ? route_url('/portfolio/' . $port['slug']) : route_url('/portfolio');
            ?>
                <a href="<?= e($detailUrl) ?>" class="block">
                <article class="group rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col hover:-translate-y-1">
                    <div class="h-[220px] w-full overflow-hidden bg-slate-100 relative">
                        <img src="<?= e($imgSrc) ?>" alt="<?= e($port['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        <span class="absolute bottom-3 left-3 bg-primary/95 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm">ERP SYSTEM</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-base font-bold text-[#0b1b42] leading-snug line-clamp-2 group-hover:text-primary transition-colors mb-3">
                            <?= e($port['title']) ?>
                        </h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed line-clamp-3 mb-5 flex-1">
                            <?= e($port['description']) ?>
                        </p>
                        <div class="mt-auto pt-4 border-t border-slate-50">
                            <span class="inline-flex items-center text-xs font-bold text-primary group-hover:text-blue-700 transition-colors">
                                อ่านเคสสตั๊ดดี้
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </article>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section> -->
<?php endif; ?>

<!-- <section class="bg-white py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-primary mb-3 uppercase tracking-wide">QUALITY CONTROL</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    ควบคุมคุณภาพผลผลิตและกระบวนการผลิตได้มาตรฐานและตรวจสอบได้
                </p>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ตั้งมาตรฐานคุณภาพ (QC Plan)
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ตรวจสอบคุณภาพ / บันทึกผล 3 ขั้นตอน
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        จัดการข้อบกพร่องพร้อมแนวทางการแก้ไข (CAPA)
                    </li>
                </ul>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all duration-300">
                <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-primary mb-3 uppercase tracking-wide">MAINTENANCE</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    บริหารจัดการงานบำรุงรักษาเครื่องจักรและอุปกรณ์ ลด Downtime เพิ่มประสิทธิภาพการทำงาน
                </p>
                <ul class="space-y-2.5">
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        แผนบำรุงรักษาเชิงป้องกัน (PM)
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        แจ้งซ่อม / ติดตามงาน / ประวัติการซ่อม
                    </li>
                    <li class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ควบคุมค่าใช้จ่ายและเวลาการบำรุงรักษา
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section> -->

<section class="bg-slate-50 py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-extrabold text-center text-[#022862] tracking-tight mb-20">
            ERP ที่ช่วยยกระดับธุรกิจของคุณ
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">
            <?php
            $erpBenefits = [
                [
                    'title' => 'ข้อมูลครบถ้วน',
                    'desc' => 'รวมทุกแผนกไว้ในระบบเดียว',
                    'icon' => '<svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.071-3.694 3.75-8.25 3.75s-8.25-1.679-8.25-3.75M20.25 6.375c0-2.071-3.694-3.75-8.25-3.75s-8.25 1.679-8.25 3.75M20.25 6.375v11.25C20.25 19.71 16.556 21.375 12 21.375s-8.25-1.664-8.25-3.75V6.375"/></svg>',
                ],
                [
                    'title' => 'ลดงานซ้ำซ้อน',
                    'desc' => 'เพิ่มประสิทธิภาพการทำงาน',
                    'icon' => '<svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>',
                ],
                [
                    'title' => 'ข้อมูลเรียลไทม์',
                    'desc' => 'ตัดสินใจได้แม่นยำและรวดเร็ว',
                    'icon' => '<svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                ],
                [
                    'title' => 'ควบคุมความเสี่ยง',
                    'desc' => 'ตรวจสอบและติดตามได้ทุกขั้นตอน',
                    'icon' => '<svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>',
                ],
                [
                    'title' => 'ขยายได้ตามธุรกิจ',
                    'desc' => 'รองรับการเติบโตในอนาคต',
                    'icon' => '<svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>',
                ],
            ];
            ?>
            <?php foreach ($erpBenefits as $benefit): ?>
                <div class="bg-white rounded-2xl p-6 text-center border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 mx-auto bg-blue-50/70 rounded-full flex items-center justify-center mb-4">
                        <?= $benefit['icon'] ?>
                    </div>
                    <h4 class="text-sm font-bold text-[#043B94] mb-1"><?= e($benefit['title']) ?></h4>
                    <p class="text-xs text-slate-500 leading-relaxed"><?= e($benefit['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="relative font-sans py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-[#021a4a] via-[#03245c] to-[#0b3f9e]">
        <img src="<?= e(asset_url('images/bg-cta.jpg')) ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-r from-[#021a4a]/90 via-[#021a4a]/70 to-[#0b3f9e]/60"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-white leading-tight mb-4 drop-shadow-sm">
                    พร้อมเริ่มต้นโครงการ ERP<br class="hidden sm:block">
                    ยกระดับองค์กรของคุณแล้วหรือยัง?
                </h2>
                <p class="text-white/90 text-sm md:text-base leading-relaxed mb-8 max-w-xl">
                    ทีมผู้เชี่ยวชาญของเราพร้อมให้คำปรึกษาและออกแบบระบบ
                    ที่เหมาะสมกับธุรกิจของคุณ
                </p>
                <a href="<?= e(route_url('/contact')) ?>" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-[#022862] text-sm font-bold rounded-full hover:bg-blue-50 transition-all shadow-md hover:-translate-y-0.5">
                    ปรึกษาผู้เชี่ยวชาญ
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <img src="<?= e(asset_url('images/bg-hand.jpg')) ?>" alt="ทีมงาน ERP"
                    class="rounded-2xl w-full h-[280px] object-cover shadow-2xl border border-white/20"
                    onerror="this.closest('div').innerHTML='<div class=&quot;w-full h-[280px] rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center&quot;><svg class=&quot;w-16 h-16 text-white/40&quot; fill=&quot;none&quot; viewBox=&quot;0 0 24 24&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;1.5&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; d=&quot;M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 16.5V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18v-1.5m-18 0V6A2.25 2.25 0 015.25 3.75h13.5A2.25 2.25 0 0121 6v10.5m-18 0h18M8.25 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z&quot;/></svg></div>'">
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-20 font-sans border-t border-slate-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-primary font-bold text-xs md:text-sm tracking-widest uppercase mb-2 block">GET IN TOUCH</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#022862] tracking-tight mb-4">
                    ปรึกษาโซลูชัน ERP<br>กับผู้เชี่ยวชาญของเรา
                </h2>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed mb-8 max-w-md">
                    กรอกข้อมูลเพื่อให้ทีมงานติดต่อกลับ
                    และวางแผนการใช้งานระบบที่เหมาะกับธุรกิจของคุณ
                </p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 shadow-sm">
                <form action="<?= e(route_url('/contact/submit')) ?>" method="post" class="space-y-4">
                    <?php if (function_exists('csrf_field')): ?>
                        <?= csrf_field() ?>
                    <?php endif; ?>
                    <input type="hidden" name="source" value="erp_page">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="full_name" required placeholder="ชื่อ - นามสกุล"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <input type="tel" name="phone" required placeholder="เบอร์โทรศัพท์"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="email" name="email" required placeholder="อีเมล"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <input type="text" name="company" placeholder="บริษัท"
                            class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                    </div>

                    <textarea name="message" rows="4" placeholder="รายละเอียด / ความต้องการ"
                        class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"></textarea>

                    <label class="flex items-start gap-2 text-xs text-slate-500">
                        <input type="checkbox" name="consent" required class="mt-0.5 rounded border-slate-300 text-primary focus:ring-primary/40">
                        <span>
                            ฉันยินยอมตาม <a href="<?= e(route_url('/privacy-policy')) ?>" class="text-primary underline">นโยบายความเป็นส่วนตัว</a>
                            และข้อกำหนดและเงื่อนไขของเว็บไซต์
                        </span>
                    </label>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-blue-700 transition-all shadow-md hover:-translate-y-0.5">
                        ส่งข้อมูล
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>