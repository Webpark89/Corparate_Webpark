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

        .input-field.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }

        .label-invalid {
            color: #ef4444 !important;
        }

        .inline-error-msg {
            display: none;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #ef4444;
            font-weight: 500;
            margin-top: 0.35rem;
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

            <form id="reset-pwd-form" method="post" action="reset_password.php" autocomplete="off" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">

                <div class="input-group">
                    <label id="label_pwd" for="password" class="label">รหัสผ่านใหม่</label>
                    <div style="position: relative;">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autofocus
                            placeholder="อย่างน้อย 6 ตัวอักษร"
                            class="input-field"
                            style="padding-right: 2.75rem;">
                        <button
                            type="button"
                            id="toggle_pwd_btn"
                            aria-label="Toggle password"
                            style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.875rem; color: #94a3b8; background: none; border: none; cursor: pointer; transition: color 0.15s ease;"
                            onmouseover="this.style.color='#0f172a'"
                            onmouseout="this.style.color='#94a3b8'">
                            <svg id="eye_open_1" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye_closed_1" style="width: 18px; height: 18px; display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </button>
                    </div>
                    <div id="pwd_err" class="inline-error-msg">
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="err-text">กรุณากรอกรหัสผ่านใหม่ (อย่างน้อย 6 ตัวอักษร)</span>
                    </div>
                </div>

                <div class="input-group">
                    <label id="label_conf" for="confirm_password" class="label">ยืนยันรหัสผ่านใหม่</label>
                    <div style="position: relative;">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                            class="input-field"
                            style="padding-right: 2.75rem;">
                        <button
                            type="button"
                            id="toggle_conf_btn"
                            aria-label="Toggle confirm password"
                            style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.875rem; color: #94a3b8; background: none; border: none; cursor: pointer; transition: color 0.15s ease;"
                            onmouseover="this.style.color='#0f172a'"
                            onmouseout="this.style.color='#94a3b8'">
                            <svg id="eye_open_2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye_closed_2" style="width: 18px; height: 18px; display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </button>
                    </div>
                    <div id="conf_err" class="inline-error-msg">
                        <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="err-text">รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>บันทึกรหัสผ่านและเข้าสู่ระบบ</span>
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const form = document.getElementById('reset-pwd-form');
                    const pwdInp = document.getElementById('password');
                    const confInp = document.getElementById('confirm_password');
                    const lblPwd = document.getElementById('label_pwd');
                    const lblConf = document.getElementById('label_conf');
                    const errPwd = document.getElementById('pwd_err');
                    const errConf = document.getElementById('conf_err');

                    const btnTogglePwd = document.getElementById('toggle_pwd_btn');
                    const eyeOpen1 = document.getElementById('eye_open_1');
                    const eyeClosed1 = document.getElementById('eye_closed_1');

                    const btnToggleConf = document.getElementById('toggle_conf_btn');
                    const eyeOpen2 = document.getElementById('eye_open_2');
                    const eyeClosed2 = document.getElementById('eye_closed_2');

                    if (btnTogglePwd && pwdInp) {
                        btnTogglePwd.addEventListener('click', () => {
                            const isPass = pwdInp.type === 'password';
                            pwdInp.type = isPass ? 'text' : 'password';
                            if (eyeOpen1 && eyeClosed1) {
                                eyeOpen1.style.display = isPass ? 'none' : 'block';
                                eyeClosed1.style.display = isPass ? 'block' : 'none';
                            }
                        });
                    }

                    if (btnToggleConf && confInp) {
                        btnToggleConf.addEventListener('click', () => {
                            const isPass = confInp.type === 'password';
                            confInp.type = isPass ? 'text' : 'password';
                            if (eyeOpen2 && eyeClosed2) {
                                eyeOpen2.style.display = isPass ? 'none' : 'block';
                                eyeClosed2.style.display = isPass ? 'block' : 'none';
                            }
                        });
                    }

                    function setErr(inp, lbl, errBox, msg) {
                        if (inp) inp.classList.add('is-invalid');
                        if (lbl) lbl.classList.add('label-invalid');
                        if (errBox) {
                            if (msg) {
                                const span = errBox.querySelector('.err-text');
                                if (span) span.textContent = msg;
                            }
                            errBox.style.display = 'flex';
                        }
                    }

                    function clearErr(inp, lbl, errBox) {
                        if (inp) inp.classList.remove('is-invalid');
                        if (lbl) lbl.classList.remove('label-invalid');
                        if (errBox) errBox.style.display = 'none';
                    }

                    if (pwdInp) {
                        pwdInp.addEventListener('input', () => {
                            if (pwdInp.value.length >= 6) clearErr(pwdInp, lblPwd, errPwd);
                        });
                    }

                    if (confInp) {
                        confInp.addEventListener('input', () => {
                            if (confInp.value === pwdInp.value && confInp.value !== '') clearErr(confInp, lblConf, errConf);
                        });
                    }

                    if (form) {
                        form.addEventListener('submit', (e) => {
                            let hasErr = false;
                            if (!pwdInp.value) {
                                e.preventDefault();
                                setErr(pwdInp, lblPwd, errPwd, 'กรุณากรอกรหัสผ่านใหม่');
                                hasErr = true;
                            } else if (pwdInp.value.length < 6) {
                                e.preventDefault();
                                setErr(pwdInp, lblPwd, errPwd, 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร');
                                hasErr = true;
                            } else {
                                clearErr(pwdInp, lblPwd, errPwd);
                            }

                            if (!confInp.value) {
                                e.preventDefault();
                                setErr(confInp, lblConf, errConf, 'กรุณายืนยันรหัสผ่านใหม่อีกครั้ง');
                                hasErr = true;
                            } else if (confInp.value !== pwdInp.value) {
                                e.preventDefault();
                                setErr(confInp, lblConf, errConf, 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน');
                                hasErr = true;
                            } else {
                                clearErr(confInp, lblConf, errConf);
                            }

                            if (hasErr) {
                                if (pwdInp.value.length < 6) pwdInp.focus();
                                else confInp.focus();
                            }
                        });
                    }
                });
            </script>
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
