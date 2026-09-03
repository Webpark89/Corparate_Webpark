<?php
/**
 * 403 Forbidden Error Page — Futuristic Clean Design matching Webpark Error System.
 */
if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
$pageTitle = '403 Forbidden';
$adminUrl = defined('ADMIN_URL') ? ADMIN_URL : '/Corparate_Webpark/admin';
$siteName = defined('SITE_NAME') ? SITE_NAME : 'Webpark';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <link rel="icon" type="image/png" href="<?= $adminUrl ?>/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --rose-accent: #e11d48;
            --rose-bg: #fff1f2;
            --rose-border: #ffe4e6;
            --slate-bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Noto Sans Thai', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient grid and glowing background blobs */
        .ambient-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .ambient-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(148, 163, 184, 0.2) 1px, transparent 1px);
            background-size: 28px 28px;
            opacity: 0.7;
        }

        .glow-sphere-1 {
            position: absolute;
            top: -10%;
            left: 20%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(244, 63, 94, 0.15) 0%, rgba(244, 63, 94, 0) 70%);
            filter: blur(40px);
            border-radius: 50%;
        }

        .glow-sphere-2 {
            position: absolute;
            bottom: -10%;
            right: 20%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, rgba(37, 99, 235, 0) 70%);
            filter: blur(40px);
            border-radius: 50%;
        }

        @keyframes floatBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: floatBounce 4s ease-in-out infinite;
        }

        @keyframes pingDot {
            0% { transform: scale(1); opacity: 1; }
            75%, 100% { transform: scale(2); opacity: 0; }
        }

        /* Card Container */
        .error-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 1.75rem;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 2.75rem 2.25rem;
            text-align: center;
            backdrop-filter: blur(16px);
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 2rem 1.25rem;
                border-radius: 1.25rem;
            }
        }

        /* Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            padding: 0.35rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #e11d48;
            position: relative;
        }

        .badge-dot::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background-color: #e11d48;
            animation: pingDot 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        /* Giant 403 Typography */
        .error-code {
            font-size: clamp(3.75rem, 12vw, 5.5rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.04em;
            color: #e11d48;
            margin-bottom: 0.75rem;
            text-shadow: 0 4px 16px rgba(225, 29, 72, 0.12);
        }

        /* Headings & Text */
        .error-title {
            font-size: clamp(1.25rem, 3.5vw, 1.5rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        .error-description {
            font-size: 0.9375rem;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Permission Required Box */
        .permission-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            font-size: 0.8125rem;
            color: #475569;
            margin-bottom: 1.75rem;
            max-width: 100%;
            word-break: break-word;
        }

        .permission-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 600;
            color: #2563eb;
            background: #eff6ff;
            padding: 0.125rem 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #dbeafe;
        }

        /* Action Buttons */
        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.375rem;
            border-radius: 0.875rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            border: 1px solid #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
            transform: translateY(-1px);
        }

        @media (max-width: 480px) {
            .action-group {
                flex-direction: column;
                width: 100%;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Background Ambient Atmosphere -->
    <div class="ambient-bg">
        <div class="ambient-grid"></div>
        <div class="glow-sphere-1"></div>
        <div class="glow-sphere-2"></div>
    </div>

    <!-- Error Card Container -->
    <main class="error-card">
        <!-- Badge -->
        <div class="status-badge">
            <span class="badge-dot"></span>
            <span>403 Access Forbidden</span>
        </div>

        <!-- 403 Giant Animated Heading -->
        <div class="error-code animate-float">
            403
        </div>

        <!-- Title -->
        <h1 class="error-title">
            คุณไม่มีสิทธิ์เข้าถึงส่วนนี้
        </h1>

        <!-- Description -->
        <p class="error-description">
            <?= !empty($errorMessage) ? e($errorMessage) : 'บัญชีของคุณไม่ได้รับอนุญาตให้เปิดดูหรือดำเนินการในหน้านี้ หากจำเป็นต้องใช้งานกรุณาติดต่อผู้ดูแลระบบสูงสุดเพื่อขอรับสิทธิ์' ?>
        </p>

        <!-- Permission Code Required (if specified) -->
        <?php if (!empty($requiredPermission)): ?>
            <div class="permission-box">
                <span>สิทธิ์ที่ระบบต้องการ:</span>
                <code class="permission-code"><?= e($requiredPermission) ?></code>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="action-group">
            <button type="button" onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='<?= $adminUrl ?>/index.php'; }" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>ย้อนกลับหน้าที่แล้ว</span>
            </button>

            <a href="<?= $adminUrl ?>/index.php" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>กลับหน้าแดชบอร์ด</span>
            </a>
        </div>
    </main>
</body>
</html>
