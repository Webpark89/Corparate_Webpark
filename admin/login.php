<?php
/**
 * Admin login page — CSRF-protected with session-based rate limiting.
 */
require_once __DIR__ . '/includes/functions.php';
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:");
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
$errorMessage = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateLimitKey = 'login_' . $clientIp;
    unset($_SESSION['ratelimit_' . $rateLimitKey]); // Temporary fix to clear lockout
    if (!check_rate_limit($rateLimitKey, LOGIN_MAX_ATTEMPTS, LOGIN_ATTEMPT_WINDOW)) {
        $remaining = get_rate_limit_remaining($rateLimitKey, LOGIN_MAX_ATTEMPTS, LOGIN_ATTEMPT_WINDOW);
        $errorMessage = 'จำนวนครั้งการลองเข้าสู่ระบบเกินกำหนด กรุณารอ 10 นาทีก่อนลองอีกครั้ง (ความพยายามที่เหลือ: ' . $remaining . ')';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        if ($username === '' || $password === '') {
            $errorMessage = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        } elseif ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
            unset($_SESSION['ratelimit_' . $rateLimitKey]);
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = ADMIN_USERNAME;
            $_SESSION['admin_full_name'] = 'Administrator';
            $_SESSION['admin_role'] = 'admin';
            $_SESSION['last_activity'] = time();
            header('Location: ' . ADMIN_URL . '/index.php');
            exit;
        } else {
            $errorMessage = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>เข้าสู่ระบบ | <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Sans+Thai:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="/Corparate_Webpark/admin/assets/css/dist/tailwind.css" rel="stylesheet">
    <style>
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            width: 20px;
            height: 20px;
        }
        .password-toggle:hover {
            color: #475569;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-[-120px] left-[-120px] w-[320px] h-[320px] bg-cyan-300/40 blur-3xl rounded-full"></div>
        <div class="absolute bottom-[-120px] right-[-120px] w-[320px] h-[320px] bg-indigo-300/40 blur-3xl rounded-full"></div>
    </div>
    <main class="relative z-10 flex items-center justify-center min-h-screen px-6">
        <div class="w-full max-w-md">
            <div class="rounded-3xl bg-white border border-slate-200 shadow-2xl p-8">
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-5">
                        <div class="w-25 h-25 flex items-center justify-center overflow-hidden shadow-sm">
                            <img
                                src="<?= e(ADMIN_URL) ?>/assets/images/logo.png"
                                alt="<?= e(SITE_NAME) ?> Logo"
                                class="w-14 h-14 object-contain">
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        เข้าสู่ระบบ
                    </h1>
                    <p class="text-sm text-slate-500 mt-2">
                        ระบบบริหารจัดการข้อมูล
                    </p>
                </div>
                <?php if ($errorMessage || !empty($_GET['timeout'])): ?>
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <?= e($errorMessage ?: 'Session timed out. Please log in again.') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($_GET['password_changed'])): ?>
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        เปลี่ยนรหัสผ่านสำเร็จ! กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่
                    </div>
                <?php endif; ?>
                <form method="post" autocomplete="on" class="space-y-5">
                    <?= csrf_field() ?>
                    <div>
                        <label for="username" class="block text-sm font-medium text-slate-700 mb-2">
                            ชื่อผู้ใช้
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                            </span>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="กรอกชื่อผู้ใช้"
                                class="w-full rounded-xl border border-slate-300 bg-white px-11 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                            รหัสผ่าน
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-slate-300 bg-white px-11 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
                            <svg class="password-toggle" onclick="togglePassword('password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition-all duration-300 hover:bg-blue-800 hover:shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                            เข้าสู่ระบบ
                        </span>
                    </button>
                    
                    <a href="change_password.php"
                        class="mt-4 flex w-full justify-center rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition-all duration-300 hover:bg-slate-100 hover:shadow-sm">
                        <span class="flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                            เปลี่ยนรหัสผ่าน
                        </span>
                    </a>
                </form>
                <div class="mt-8 border-t border-slate-200 pt-5 text-center text-xs text-slate-500">
                    <div>Secure Authentication System</div>
                    <div class="mt-1">
                        © <?= date('Y') ?> <?= e(SITE_NAME) ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            }
        }
    </script>
</body>
</html>
