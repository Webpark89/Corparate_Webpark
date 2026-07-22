<?php
$pageTitle = 'เปลี่ยนรหัสผ่าน';
$page = 'change_password';
require_once __DIR__ . '/includes/header.php';

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
        // Generate new hash
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Read config.php
        $configFile = __DIR__ . '/config/config.php';
        if (is_writable($configFile)) {
            $configContent = file_get_contents($configFile);
            
            // Use regex to replace the hash safely
            $pattern = "/define\('ADMIN_PASSWORD_HASH',\s*'.*?'\);/";
            $replacement = "define('ADMIN_PASSWORD_HASH', '" . addcslashes($newHash, "'\\") . "');";
            
            if (preg_match($pattern, $configContent)) {
                $newConfigContent = preg_replace($pattern, $replacement, $configContent);
                if (file_put_contents($configFile, $newConfigContent)) {
                    $success = true;
                } else {
                    $error = 'ไม่สามารถบันทึกข้อมูลลงไฟล์ config.php ได้';
                }
            } else {
                $error = 'ไม่พบค่า ADMIN_PASSWORD_HASH ในไฟล์ config.php';
            }
        } else {
            $error = 'ไฟล์ config.php ไม่สามารถเขียนได้ กรุณาตรวจสอบ Permission (Chmod 666 หรือ 777 ชั่วคราว)';
        }
    }
}
?>

<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">เปลี่ยนรหัสผ่าน</h1>
        <p class="text-slate-500 mt-1">อัปเดตรหัสผ่านสำหรับเข้าสู่ระบบผู้ดูแลระบบ</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 md:p-8">
            <?php if ($success): ?>
                <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <div>
                        <p class="font-semibold">เปลี่ยนรหัสผ่านสำเร็จ!</p>
                        <p class="text-sm mt-1">รหัสผ่านของคุณถูกอัปเดตเรียบร้อยแล้ว ในการเข้าสู่ระบบครั้งต่อไป กรุณาใช้รหัสผ่านใหม่นี้</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <p class="text-sm font-medium"><?= e($error) ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <?= csrf_field() ?>
                
                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1.5">รหัสผ่านปัจจุบัน</label>
                        <input type="password" name="current_password" id="current_password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20">
                    </div>
                    
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1.5">รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)</label>
                        <input type="password" name="new_password" id="new_password" required minlength="8" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20">
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-slate-700 mb-1.5">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="confirm_password" id="confirm_password" required minlength="8" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-xl transition shadow-sm w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        เปลี่ยนรหัสผ่าน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
