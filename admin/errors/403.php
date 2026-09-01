<?php
/**
 * 403 Forbidden Error Page
 */
$pageTitle = 'ไม่มีสิทธิ์เข้าถึง (403 Forbidden)';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?= e($pageTitle) ?> | <?= defined('SITE_NAME') ? e(SITE_NAME) : 'Webpark' ?></title>
    <link rel="icon" type="image/png" href="<?= defined('ADMIN_URL') ? ADMIN_URL : '/admin' ?>/assets/images/logo.png">
    <link href="<?= defined('ADMIN_URL') ? ADMIN_URL : '/admin' ?>/assets/css/dist/tailwind.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Noto Sans Thai", system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 antialiased text-slate-800">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10 text-center relative overflow-hidden">
        <!-- Background Glow Accent -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-rose-100 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-100 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

        <!-- Icon Shield -->
        <div class="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 border border-rose-100 shadow-sm">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <!-- Error Code & Heading -->
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 mb-3">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            ข้อผิดพลาด 403 Forbidden
        </span>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-3 tracking-tight">คุณไม่มีสิทธิ์เข้าถึงส่วนนี้</h1>
        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
            <?= !empty($errorMessage) ? e($errorMessage) : 'บัญชีของคุณไม่ได้รับอนุญาตให้เปิดดูหรือดำเนินการในหน้านี้ หากจำเป็นต้องใช้งานกรุณาติดต่อผู้ดูแลระบบสูงสุดเพื่อขอรับสิทธิ์' ?>
        </p>

        <?php if (!empty($requiredPermission)): ?>
            <div class="mb-6 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-center justify-center gap-2">
                <span class="text-slate-400">รหัสสิทธิ์ที่ต้องการ:</span>
                <code class="font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded"><?= e($requiredPermission) ?></code>
            </div>
        <?php endif; ?>

        <!-- Action Buttons with Generous Padding and Spacing -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <button onclick="window.history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 active:bg-slate-100 transition shadow-sm cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                ย้อนกลับหน้าที่แล้ว
            </button>
            <a href="<?= defined('ADMIN_URL') ? ADMIN_URL : '/admin' ?>/index.php"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 active:bg-blue-800 transition shadow-md shadow-blue-500/20 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                กลับหน้าแดชบอร์ด
            </a>
        </div>
    </div>
</body>
</html>
