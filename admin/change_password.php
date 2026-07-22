<?php
/**
 * Admin change password page (standalone, no login required for demo).
 */
require_once __DIR__ . '/includes/functions.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    
    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบทุกช่อง';
    } elseif (!password_verify($currentPassword, ADMIN_PASSWORD_HASH)) {
        $error = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';
    } elseif (strlen($newPassword) < 8) {
        $error = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร';
    } else {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $configFile = __DIR__ . '/config/config.php';
        if (is_writable($configFile)) {
            $configContent = file_get_contents($configFile);
            $pattern = "/define\('ADMIN_PASSWORD_HASH',\s*'.*?'\);/";
            
            if (preg_match($pattern, $configContent)) {
                $newConfigContent = preg_replace_callback($pattern, function($matches) use ($newHash) {
                    return "define('ADMIN_PASSWORD_HASH', '" . addcslashes($newHash, "'\\") . "');";
                }, $configContent);
                if (file_put_contents($configFile, $newConfigContent)) {
                    // Update the database admins table for the user's reference (plain text as requested + updated_at)
                    try {
                        $db = Database::conn();
                        $stmt = $db->prepare("UPDATE admins SET password_hash = :plain, updated_at = NOW() WHERE id = 1");
                        $stmt->execute(['plain' => 'Plaintext: ' . $newPassword]);
                    } catch (Exception $e) {
                        // ignore db errors
                    }

                    // Redirect to login page on success
                    header('Location: login.php?password_changed=1');
                    exit;
                } else {
                    $error = 'ไม่สามารถบันทึกข้อมูลลงไฟล์ config.php ได้';
                }
            } else {
                $error = 'ไม่พบค่า ADMIN_PASSWORD_HASH ในไฟล์ config.php';
            }
        } else {
            $error = 'ไฟล์ config.php ไม่สามารถเขียนได้';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>เปลี่ยนรหัสผ่าน | <?= e(SITE_NAME) ?></title>
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
        .password-toggle:hover { color: #475569; }
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
                        <img src="/Corparate_Webpark/admin/assets/images/logo.png" alt="Logo" class="h-12 w-auto">
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                        เปลี่ยนรหัสผ่าน
                    </h1>
                </div>

                <?php if ($error): ?>
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" autocomplete="off" class="space-y-5">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">รหัสผ่านปัจจุบัน</label>
                        <div class="relative">
                            <input name="current_password" id="current_password" type="password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
                            <svg class="w-5 h-5 password-toggle" onclick="togglePassword('current_password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">รหัสผ่านใหม่</label>
                        <div class="relative">
                            <input name="new_password" id="new_password" type="password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
                            <svg class="w-5 h-5 password-toggle" onclick="togglePassword('new_password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">ยืนยันรหัสผ่านใหม่</label>
                        <div class="relative">
                            <input name="confirm_password" id="confirm_password" type="password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
                            <svg class="w-5 h-5 password-toggle" onclick="togglePassword('confirm_password', this)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition-all duration-300 hover:bg-blue-800">
                        บันทึกรหัสผ่านใหม่
                    </button>
                    
                    <a href="login.php" class="mt-4 flex w-full justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 font-semibold text-slate-700 transition-all duration-300 hover:bg-slate-50">
                        กลับไปหน้าเข้าสู่ระบบ
                    </a>
                </form>
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
