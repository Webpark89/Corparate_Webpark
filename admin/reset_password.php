<?php
/**
 * Admin Password Reset Handler & View
 */
require_once __DIR__ . '/includes/functions.php';

$token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));
$user = $token !== '' ? verify_password_reset_token($token) : null;

$errorMessage = null;

if (!$user) {
    $errorMessage = 'ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้อง หรือหมดอายุการใช้งานแล้ว (ลิงก์มีอายุ 15 นาที)';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    csrf_verify();

    $newPassword = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    if (strlen($newPassword) < 6) {
        $errorMessage = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';
    } else {
        // Update password hash in database
        try {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = db()->prepare('UPDATE admins SET password_hash = :hash, updated_at = NOW() WHERE id = :id');
            $stmt->execute([
                'hash' => $newHash,
                'id' => (int)$user['id'],
            ]);

            // Auto-unlock rate limit for this client IP & session
            $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $rateLimitKey = 'login_' . md5($clientIp);
            reset_rate_limit($rateLimitKey);

            $_SESSION['login_success_flash'] = 'ตั้งรหัสผ่านใหม่สำเร็จและปลดล็อกระบบเรียบร้อยแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่';
            header('Location: ' . ADMIN_URL . '/login.php');
            exit;
        } catch (Exception $e) {
            $errorMessage = 'เกิดข้อผิดพลาดในการบันทึกรหัสผ่านใหม่ กรุณาลองใหม่อีกครั้ง';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>ตั้งรหัสผ่านใหม่ | <?= defined('SITE_NAME') ? e(SITE_NAME) : 'Webpark' ?></title>
    <link rel="icon" type="image/png" href="<?= ADMIN_URL ?>/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --primary-hover: #1e293b;
            --blue-accent: #2563eb;
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
        }

        .ambient-grid {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(148, 163, 184, 0.25) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.6;
            pointer-events: none;
        }

        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 2.25rem 2rem;
            text-align: center;
        }

        .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 1.125rem;
            background: #f0fdf4;
            color: #16a34a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            border: 1px solid #bbf7d0;
        }

        .icon-wrapper.error {
            background: #fff1f2;
            color: #e11d48;
            border-color: #ffe4e6;
        }

        .title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.375rem;
            letter-spacing: -0.02em;
        }

        .subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            color: #475569;
            margin-bottom: 1.25rem;
        }

        .input-group {
            text-align: left;
            margin-bottom: 1.125rem;
        }

        .label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 0.375rem;
        }

        .input-field {
            width: 100%;
            height: 44px;
            padding: 0 1rem;
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            transition: all 0.15s ease;
        }

        .input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-submit {
            width: 100%;
            height: 44px;
            border-radius: 0.75rem;
            background: #0f172a;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: 1.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .back-link:hover {
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="ambient-grid"></div>

    <main class="card">
        <?php if ($user): ?>
            <div class="icon-wrapper">
                <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h1 class="title">ตั้งรหัสผ่านใหม่</h1>
            <p class="subtitle">กรุณากำหนดรหัสผ่านใหม่ที่มีความปลอดภัยสำหรับบัญชีของคุณ</p>

            <div class="user-badge">
                <span>บัญชี:</span>
                <strong style="color: #0f172a;"><?= e($user['username']) ?></strong>
                <span>(<?= e($user['email']) ?>)</span>
            </div>

            <?php if ($errorMessage): ?>
                <div class="alert-error">
                    <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?= e($errorMessage) ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="reset_password.php" autocomplete="off">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">

                <div class="input-group">
                    <label for="password" class="label">รหัสผ่านใหม่</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="6"
                        autofocus
                        placeholder="อย่างน้อย 6 ตัวอักษร"
                        class="input-field">
                </div>

                <div class="input-group">
                    <label for="confirm_password" class="label">ยืนยันรหัสผ่านใหม่</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        required
                        minlength="6"
                        placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                        class="input-field">
                </div>

                <button type="submit" class="btn-submit">
                    <span>บันทึกรหัสผ่านและเข้าสู่ระบบ</span>
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </form>
        <?php else: ?>
            <div class="icon-wrapper error">
                <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h1 class="title">ไม่สามารถดำเนินการได้</h1>
            <p class="subtitle"><?= e($errorMessage) ?></p>

            <a href="<?= ADMIN_URL ?>/forgot_password.php" class="btn-submit" style="text-decoration: none;">
                <span>ขอลิงก์รีเซ็ตรหัสผ่านใหม่</span>
            </a>
        <?php endif; ?>

        <a href="<?= ADMIN_URL ?>/login.php" class="back-link">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>กลับไปยังหน้าเข้าสู่ระบบ</span>
        </a>
    </main>
</body>
</html>
