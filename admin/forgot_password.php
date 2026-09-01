<?php
/**
 * Admin Forgot Password — 3-Step Interactive OTP Verification & Password Reset
 * Pixel-perfect UI with Custom Inline Validations and Password Visibility Toggles.
 */
require_once __DIR__ . '/includes/functions.php';

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Auto-redirect if already logged in
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . ADMIN_URL . '/index.php');
    exit;
}

$clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateLimitKey = 'login_' . md5($clientIp);

$step = trim($_GET['step'] ?? 'request');
if (!in_array($step, ['request', 'verify', 'reset'], true)) {
    $step = 'request';
}

$otpData = get_password_reset_otp_data();
$errorMessage = null;
$successMessage = null;

// Ensure proper step flow
if ($step === 'verify' && empty($otpData)) {
    header('Location: ' . ADMIN_URL . '/forgot_password.php?step=request');
    exit;
}
if ($step === 'reset' && (empty($otpData) || empty($otpData['verified']))) {
    header('Location: ' . ADMIN_URL . '/forgot_password.php?step=request');
    exit;
}

// -------------------------------------------------------------
// POST Handling by Step
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = trim($_POST['action'] ?? '');

    // Step 1: Request OTP
    if ($action === 'request_otp') {
        $loginInput = trim($_POST['login_input'] ?? '');

        if ($loginInput === '') {
            $errorMessage = 'กรุณากรอก Email หรือ Username ของคุณ';
        } else {
            $user = find_admin_by_login($loginInput);

            if ($user) {
                $otpCode = generate_otp_code(6);
                store_password_reset_otp((int)$user['id'], (string)$user['email'], (string)$user['username'], $otpCode);
                // Dispatch real email via SMTP / mail()
                send_password_reset_otp_email((string)$user['email'], $otpCode, (string)$user['username']);
                header('Location: ' . ADMIN_URL . '/forgot_password.php?step=verify');
                exit;
            } else {
                $errorMessage = 'ไม่พบบัญชีผู้ดูแลระบบที่ตรงกับข้อมูลที่ระบุ';
            }
        }
    }

    // Resend OTP
    if ($action === 'resend_otp') {
        if ($otpData) {
            $user = find_admin_by_id((int)$otpData['admin_id']);
            if ($user) {
                $otpCode = generate_otp_code(6);
                store_password_reset_otp((int)$user['id'], (string)$user['email'], (string)$user['username'], $otpCode);
                // Dispatch real email via SMTP / mail()
                send_password_reset_otp_email((string)$user['email'], $otpCode, (string)$user['username']);
                $successMessage = 'ส่งรหัส OTP ใหม่อีกครั้งเรียบร้อยแล้ว';
                $otpData = get_password_reset_otp_data();
            }
        } else {
            header('Location: ' . ADMIN_URL . '/forgot_password.php?step=request');
            exit;
        }
    }

    // Step 2: Verify OTP
    if ($action === 'verify_otp') {
        $digits = $_POST['otp_digit'] ?? [];
        $enteredOtp = is_array($digits) ? implode('', $digits) : trim((string)$_POST['otp_code'] ?? '');

        if (strlen($enteredOtp) !== 6 || !ctype_digit($enteredOtp)) {
            $errorMessage = 'กรุณากรอกรหัส OTP 6 หลักให้ครบถ้วนถูกต้อง';
        } else {
            if (verify_password_reset_otp($enteredOtp)) {
                header('Location: ' . ADMIN_URL . '/forgot_password.php?step=reset');
                exit;
            } else {
                $otpData = get_password_reset_otp_data();
                $attemptsLeft = max(0, 5 - ($otpData['attempts'] ?? 0));
                if ($attemptsLeft <= 0) {
                    clear_password_reset_otp();
                    $errorMessage = 'คุณกรอกรหัสผิดเกินกำหนด กรุณาขอรหัสใหม่อีกครั้ง';
                    header('Location: ' . ADMIN_URL . '/forgot_password.php?step=request&expired=1');
                    exit;
                } else {
                    $errorMessage = 'รหัส OTP ไม่ถูกต้อง (เหลือโอกาสลองอีก ' . $attemptsLeft . ' ครั้ง)';
                }
            }
        }
    }

    // Step 3: Reset Password
    if ($action === 'reset_password') {
        if (empty($otpData) || empty($otpData['verified'])) {
            header('Location: ' . ADMIN_URL . '/forgot_password.php?step=request');
            exit;
        }

        $password = (string)($_POST['password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        if (strlen($password) < 6) {
            $errorMessage = 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
        } elseif ($password !== $confirmPassword) {
            $errorMessage = 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';
        } else {
            try {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = db()->prepare('UPDATE admins SET password_hash = :hash, updated_at = NOW() WHERE id = :id');
                $stmt->execute([
                    'hash' => $newHash,
                    'id' => (int)$otpData['admin_id'],
                ]);

                // Auto-unlock Rate Limit
                reset_rate_limit($rateLimitKey);
                clear_password_reset_otp();

                $_SESSION['login_success_flash'] = 'ตั้งรหัสผ่านใหม่สำเร็จและปลดล็อกระบบเรียบร้อยแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่';
                header('Location: ' . ADMIN_URL . '/login.php');
                exit;
            } catch (Exception $e) {
                $errorMessage = 'เกิดข้อผิดพลาดในการบันทึกรหัสผ่าน กรุณาลองใหม่อีกครั้ง';
            }
        }
    }
}

// Mask email helper
function mask_email(string $email): string {
    $parts = explode('@', $email);
    if (count($parts) !== 2) return $email;
    $name = $parts[0];
    $len = strlen($name);
    if ($len <= 2) {
        $masked = $name . '***';
    } else {
        $masked = substr($name, 0, 1) . str_repeat('*', max(3, $len - 2)) . substr($name, -1);
    }
    return $masked . '@' . $parts[1];
}

$otpRemainingSeconds = 0;
if ($otpData && !empty($otpData['expires_at'])) {
    $otpRemainingSeconds = max(0, $otpData['expires_at'] - time());
}
?>
<!doctype html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลืมรหัสผ่าน | <?= e(SITE_NAME) ?></title>
    <link rel="icon" type="image/png" href="<?= ADMIN_URL ?>/assets/images/logo.png">
    <link rel="apple-touch-icon" href="<?= ADMIN_URL ?>/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --glow-cyan: #38bdf8;
            --glow-cyan-bright: #67e8f9;
            --robot-dark-grad: radial-gradient(circle at 35% 35%, #2a3342 0%, #0f131a 100%);
            --robot-white-grad: radial-gradient(circle at 32% 28%, #ffffff 0%, #f1f5f9 45%, #cbd5e1 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Plus Jakarta Sans', 'Noto Sans Thai', sans-serif;
            background: #eef2f6;
            color: #1e293b;
        }

        /* Ambient background */
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
            background-image: radial-gradient(rgba(148, 163, 184, 0.25) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.6;
        }
        .ambient-glow-1 {
            position: absolute;
            top: 2rem;
            left: 2rem;
            width: 24rem;
            height: 24rem;
            background: rgba(186, 230, 253, 0.35);
            border-radius: 9999px;
            filter: blur(64px);
        }
        .ambient-glow-2 {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            width: 28rem;
            height: 28rem;
            background: rgba(224, 242, 254, 0.4);
            border-radius: 9999px;
            filter: blur(64px);
        }

        /* 2-Column Responsive Layout */
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            padding: 1.5rem;
            position: relative;
            z-index: 10;
        }

        .login-card {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 960px;
            min-height: 620px;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.9) inset;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .scene-side {
            flex: 1 1 50%;
            width: 50%;
            min-width: 320px;
            background: radial-gradient(circle at 40% 35%, #ffffff 0%, #f1f5f9 55%, #e2e8f0 100%);
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
            user-select: none;
        }

        .form-side {
            flex: 1 1 50%;
            width: 50%;
            min-width: 320px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2.5rem;
            position: relative;
        }

        .form-inner {
            width: 100%;
            max-width: 360px;
        }

        @media (max-width: 860px) {
            .login-card {
                flex-direction: column;
                max-width: 440px;
                min-height: auto;
                border-radius: 1.5rem;
            }
            .scene-side {
                width: 100%;
                min-height: 330px;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 1.5rem;
            }
            .form-side {
                width: 100%;
                padding: 2.5rem 1.5rem;
            }
        }

        /* Floating Idle Animations */
        @keyframes floatR1 { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-7px) rotate(1deg); } }
        @keyframes floatR2 { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-9px) rotate(-1.5deg); } }
        @keyframes floatR3 { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-6px) rotate(1deg); } }
        @keyframes floatR4 { 0%, 100% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-8px) rotate(-1deg); } }
        @keyframes pulseGlow { 0%, 100% { opacity: 0.9; filter: drop-shadow(0 0 5px var(--glow-cyan)); } 50% { opacity: 1; filter: drop-shadow(0 0 10px var(--glow-cyan-bright)); } }
        @keyframes platformGlow { 0%, 100% { opacity: 0.4; transform: scale(1); } 50% { opacity: 0.7; transform: scale(1.04); } }
        @keyframes circuitFlow { 0% { stroke-dashoffset: 60; } 100% { stroke-dashoffset: 0; } }
        @keyframes formShake { 0%, 100% { transform: translateX(0); } 20%, 60% { transform: translateX(-6px); } 40%, 80% { transform: translateX(6px); } }
        @keyframes robotJump { 0%, 100% { transform: translateY(0); } 40% { transform: translateY(-24px) scale(1.05); } 70% { transform: translateY(4px); } }

        .anim-float-1 { animation: floatR1 3.4s ease-in-out infinite; }
        .anim-float-2 { animation: floatR2 2.9s ease-in-out infinite 0.3s; }
        .anim-float-3 { animation: floatR3 3.8s ease-in-out infinite 0.6s; }
        .anim-float-4 { animation: floatR4 4.2s ease-in-out infinite 0.9s; }
        .anim-glow { animation: pulseGlow 2.2s ease-in-out infinite; }
        .anim-platform { animation: platformGlow 4s ease-in-out infinite; }
        .circuit-line { stroke-dasharray: 8 6; animation: circuitFlow 3s linear infinite; }

        .shake-animation { animation: formShake 0.4s ease-in-out; }
        .jump-animation { animation: robotJump 0.6s ease-out; }

        .pupil-track {
            transition: transform 0.08s ease-out;
            will-change: transform;
        }

        .robot-body-track {
            transition: transform 0.12s ease-out;
            will-change: transform;
        }

        .platform-base {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(241, 245, 249, 0.85) 100%);
            box-shadow: 
                0 25px 50px -12px rgba(15, 23, 42, 0.12),
                0 0 0 1px rgba(255, 255, 255, 0.8) inset,
                0 15px 30px rgba(56, 189, 248, 0.1);
        }

        /* Steps Indicator */
        .step-progress {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .step-pill {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            background: #f1f5f9;
            color: #94a3b8;
            transition: all 0.2s;
        }
        .step-pill.active {
            background: #0f172a;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
        }
        .step-pill.completed {
            background: #dcfce7;
            color: #15803d;
        }
        .step-divider {
            width: 16px;
            height: 2px;
            background: #e2e8f0;
        }

        /* 6-Digit OTP Box Grid */
        .otp-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0.5rem;
            margin: 0.75rem 0;
        }
        .otp-digit {
            width: 100%;
            height: 52px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: #0f172a;
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.15s ease;
        }
        .otp-digit:focus {
            background: #ffffff;
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
            transform: translateY(-2px);
        }
        .otp-digit.filled {
            border-color: #0f172a;
            background: #ffffff;
        }

        /* Futuristic Form Input Controls */
        .input-futuristic {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }
        .input-futuristic:hover {
            border-color: #cbd5e1;
            background-color: #ffffff;
        }
        .input-futuristic:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
            background-color: #ffffff;
        }

        /* Custom Validation Styles (Matching Image 3) */
        .input-futuristic.is-invalid, .otp-digit.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
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
            margin-top: 0.25rem;
            animation: fadeInError 0.2s ease-in-out;
        }

        @keyframes fadeInError {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-action {
            width: 100%;
            border-radius: 0.75rem;
            background: #0f172a;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }
        .btn-action:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    <!-- Background Ambient Layers -->
    <div class="ambient-bg">
        <div class="ambient-grid"></div>
        <div class="ambient-glow-1"></div>
        <div class="ambient-glow-2"></div>
    </div>

    <!-- Main 2-Column Container -->
    <div class="login-wrapper">
        <div class="login-card">

            <!-- ============================================================ -->
            <!-- LEFT COLUMN: Interactive Futuristic Scene with 4 Robots      -->
            <!-- ============================================================ -->
            <div id="interactive-stage" class="scene-side">
                
                <!-- Matrix Dots (Top Left) -->
                <div style="position: absolute; top: 1.5rem; left: 1.5rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.375rem; opacity: 0.35;">
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                </div>

                <!-- Matrix Dots (Bottom Left) -->
                <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.375rem; opacity: 0.35;">
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background: #94a3b8;"></span>
                </div>

                <!-- Floating Geometric Accents -->
                <div style="position: absolute; top: 3.5rem; left: 6.5rem; width: 10px; height: 10px; border-radius: 9999px; border: 2px solid rgba(148, 163, 184, 0.5);"></div>
                <div class="anim-glow" style="position: absolute; top: 5rem; right: 3.5rem; width: 7px; height: 7px; border-radius: 9999px; background: #38bdf8;"></div>
                <div style="position: absolute; bottom: 6.5rem; left: 2rem; width: 6px; height: 6px; border-radius: 9999px; background: #94a3b8;"></div>

                <!-- SVG Circuit Traces around Platform -->
                <svg style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; opacity: 0.55;" viewBox="0 0 500 500" fill="none">
                    <path class="circuit-line" d="M30 250 H90 L120 280 H170" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="170" cy="280" r="3" fill="#38bdf8" class="anim-glow"/>
                    <path class="circuit-line" d="M470 270 H390 L360 300 H320" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="320" cy="300" r="3" fill="#38bdf8" class="anim-glow"/>
                    <path class="circuit-line" d="M330 430 H410 L440 400 H480" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round"/>
                    <circle cx="330" cy="430" r="3" fill="#94a3b8"/>
                </svg>

                <!-- 3D Scene Wrapper (Stage) -->
                <div id="scene-container" style="position: relative; width: 340px; height: 340px; display: flex; align-items: center; justify-content: center;">
                    
                    <!-- Circular Glow Under Platform -->
                    <div class="anim-platform" style="position: absolute; bottom: 1.5rem; width: 19rem; height: 5.5rem; border-radius: 9999px; background: rgba(56, 189, 248, 0.28); filter: blur(20px); pointer-events: none;"></div>

                    <!-- 3D Stepped Circular Dais / Platform Base -->
                    <div class="platform-base" style="position: absolute; bottom: 2rem; width: 17.5rem; height: 5.5rem; border-radius: 9999px; border: 1px solid #ffffff; display: flex; align-items: center; justify-content: center;">
                        <!-- Inner platform ring with cyan reflection -->
                        <div style="width: 15.5rem; height: 4.5rem; border-radius: 9999px; background: linear-gradient(to bottom, rgba(255,255,255,0.95), rgba(240, 249, 255, 0.6)); border: 1px solid rgba(186, 230, 253, 0.5); box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);"></div>
                    </div>

                    <!-- 4 Robots Composition Group -->
                    <div id="robots-group" style="position: relative; z-index: 10; width: 100%; height: 100%;">

                        <!-- ========================================== -->
                        <!-- ROBOT 1: Spherical Glossy White (Top/Back) -->
                        <!-- ========================================== -->
                        <div id="robot-1" class="anim-float-1 robot-body-track" style="position: absolute; top: 30px; left: 140px; width: 114px; height: 114px;">
                            <div style="position: relative; width: 100%; height: 100%; border-radius: 9999px; background: var(--robot-white-grad); border: 1px solid rgba(255,255,255,0.95); box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15);">
                                <div style="position: absolute; top: 8px; left: 12px; width: 30px; height: 14px; border-radius: 9999px; background: rgba(255,255,255,0.85); filter: blur(2px); transform: rotate(-45deg);"></div>

                                <div style="position: absolute; top: 42px; left: 18px; width: 78px; height: 42px; border-radius: 9999px; background: #121620; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; gap: 14px; padding: 0 10px;">
                                    <div style="position: relative; width: 16px; height: 16px; border-radius: 6px; background: #0284c7; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 8px #38bdf8; overflow: hidden;">
                                        <div class="r1-pupil pupil-track" style="width: 10px; height: 10px; border-radius: 3px; background: #ffffff; box-shadow: 0 0 6px #ffffff;"></div>
                                    </div>
                                    <div style="position: relative; width: 16px; height: 16px; border-radius: 6px; background: #0284c7; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 8px #38bdf8; overflow: hidden;">
                                        <div class="r1-pupil pupil-track" style="width: 10px; height: 10px; border-radius: 3px; background: #ffffff; box-shadow: 0 0 6px #ffffff;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- ROBOT 2: Spherical Matte Black (Left/Front)-->
                        <!-- ========================================== -->
                        <div id="robot-2" class="anim-float-2 robot-body-track" style="position: absolute; bottom: 60px; left: 24px; width: 104px; height: 104px;">
                            <div style="position: relative; width: 100%; height: 100%; border-radius: 9999px; background: var(--robot-dark-grad); border: 1px solid rgba(255,255,255,0.12); box-shadow: 0 25px 30px -5px rgba(2, 6, 23, 0.35);">
                                <div style="position: absolute; top: 10px; left: 14px; width: 24px; height: 12px; border-radius: 9999px; background: rgba(255,255,255,0.22); filter: blur(1px); transform: rotate(-45deg);"></div>

                                <div style="position: absolute; top: 50px; left: 28px; display: flex; align-items: center; gap: 8px;">
                                    <div class="r2-pupil pupil-track" style="width: 20px; height: 6px; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 8px #38bdf8;"></div>
                                    <div class="r2-pupil pupil-track" style="width: 20px; height: 6px; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 8px #38bdf8;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- ROBOT 3: Minimal Dark Cube (Right/Mid)     -->
                        <!-- ========================================== -->
                        <div id="robot-3" class="anim-float-3 robot-body-track" style="position: absolute; bottom: 108px; right: 70px; width: 78px; height: 78px;">
                            <div style="position: relative; width: 100%; height: 100%; border-radius: 16px; background: var(--robot-dark-grad); border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 20px 25px -5px rgba(2, 6, 23, 0.28); display: flex; align-items: center; justify-content: center; padding: 10px;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: rgba(255,255,255,0.22); border-top-left-radius: 16px; border-top-right-radius: 16px;"></div>

                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="position: relative; width: 14px; height: 14px; border-radius: 4px; background: #0369a1; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 6px #38bdf8; overflow: hidden;">
                                        <div class="r3-pupil pupil-track" style="width: 8px; height: 8px; border-radius: 2px; background: #38bdf8; box-shadow: 0 0 4px #ffffff;"></div>
                                    </div>
                                    <div style="position: relative; width: 14px; height: 14px; border-radius: 4px; background: #0369a1; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 6px #38bdf8; overflow: hidden;">
                                        <div class="r3-pupil pupil-track" style="width: 8px; height: 8px; border-radius: 2px; background: #38bdf8; box-shadow: 0 0 4px #ffffff;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- ROBOT 4: White Arch/Capsule (Right/Front)  -->
                        <!-- ========================================== -->
                        <div id="robot-4" class="anim-float-4 robot-body-track" style="position: absolute; bottom: 48px; right: 30px; width: 72px; height: 90px;">
                            <div style="position: relative; width: 100%; height: 100%; border-top-left-radius: 9999px; border-top-right-radius: 9999px; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; background: var(--robot-white-grad); border: 1px solid rgba(255,255,255,0.95); box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15); padding: 8px; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; padding-bottom: 12px;">
                                <div style="position: absolute; top: 6px; left: 12px; right: 12px; height: 10px; border-radius: 9999px; background: rgba(255,255,255,0.75); filter: blur(1px);"></div>

                                <div style="width: 100%; height: 58px; border-top-left-radius: 9999px; border-top-right-radius: 9999px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; background: #141822; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 0 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);">
                                    <div style="position: relative; width: 10px; height: 20px; border-radius: 9999px; background: #0284c7; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 6px #38bdf8; overflow: hidden;">
                                        <div class="r4-pupil pupil-track" style="width: 6px; height: 12px; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 4px #ffffff;"></div>
                                    </div>
                                    <div style="position: relative; width: 10px; height: 20px; border-radius: 9999px; background: #0284c7; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 6px #38bdf8; overflow: hidden;">
                                        <div class="r4-pupil pupil-track" style="width: 6px; height: 12px; border-radius: 9999px; background: #38bdf8; box-shadow: 0 0 4px #ffffff;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Technology Scene Badge -->
                <div style="margin-top: 1.5rem; text-align: center;">
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.875rem; border-radius: 9999px; background: #ffffff; border: 1px solid #e2e8f0; font-size: 0.6875rem; font-weight: 600; color: #475569; letter-spacing: 0.05em; text-transform: uppercase; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                        <span style="width: 6px; height: 6px; border-radius: 9999px; background: #0284c7;"></span>
                        INTERACTIVE AI NODE
                    </span>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- RIGHT COLUMN: 3-Step Verification & Reset Form               -->
            <!-- ============================================================ -->
            <div class="form-side">
                <div class="form-inner" style="display: flex; flex-direction: column; gap: 1.25rem;">

                    <!-- Brand Header -->
                    <div style="text-align: center;">
                        <div style="display: flex; justify-content: center; margin-bottom: 0.75rem;">
                            <img src="<?= ADMIN_URL ?>/assets/images/logo.png" alt="<?= e(SITE_NAME) ?>" style="width: 52px; height: 52px; object-fit: contain;">
                        </div>

                        <!-- 3-Step Pill Progress -->
                        <div class="step-progress">
                            <div class="step-pill <?= $step === 'request' ? 'active' : 'completed' ?>">1. ขอรหัส</div>
                            <div class="step-divider"></div>
                            <div class="step-pill <?= $step === 'verify' ? 'active' : ($step === 'reset' ? 'completed' : '') ?>">2. ยืนยัน OTP</div>
                            <div class="step-divider"></div>
                            <div class="step-pill <?= $step === 'reset' ? 'active' : '' ?>">3. รหัสใหม่</div>
                        </div>

                        <?php if ($step === 'request'): ?>
                            <h1 style="font-size: 1.625rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.025em;">ลืมรหัสผ่าน?</h1>
                            <p style="font-size: 0.8125rem; color: #64748b; margin: 0;">กรอก Email หรือ Username เพื่อรับรหัส OTP 6 หลัก</p>
                        <?php elseif ($step === 'verify'): ?>
                            <h1 style="font-size: 1.625rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.025em;">กรอกรหัสยืนยัน OTP</h1>
                            <p style="font-size: 0.8125rem; color: #64748b; margin: 0;">
                                ส่งรหัสไปที่ <strong style="color: #0f172a;"><?= e(mask_email($otpData['email'] ?? '')) ?></strong>
                            </p>
                        <?php else: ?>
                            <h1 style="font-size: 1.625rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem 0; letter-spacing: -0.025em;">ตั้งรหัสผ่านใหม่</h1>
                            <p style="font-size: 0.8125rem; color: #64748b; margin: 0;">กำหนดรหัสผ่านใหม่สำหรับบัญชี <?= e($otpData['username'] ?? '') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Alerts (Server Error / Success) -->
                    <?php if ($errorMessage): ?>
                        <div class="shake-animation" style="border-radius: 1rem; border: 1px solid #fecaca; background: #fef2f2; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.625rem; color: #b91c1c; font-size: 0.8125rem;">
                            <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?= e($errorMessage) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($successMessage): ?>
                        <div style="border-radius: 1rem; border: 1px solid #bbf7d0; background: #f0fdf4; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.625rem; color: #15803d; font-size: 0.8125rem;">
                            <svg style="width: 18px; height: 18px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?= e($successMessage) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- STEP 1 FORM: Request OTP                                     -->
                    <!-- ============================================================ -->
                    <?php if ($step === 'request'): ?>
                        <form id="request-form" method="post" action="forgot_password.php?step=request" autocomplete="off" novalidate style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="request_otp">

                            <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                                <label id="login_input-label" for="login_input" style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; transition: color 0.15s;">
                                    Email / Username
                                </label>
                                <div style="position: relative; width: 100%;">
                                    <input
                                        id="login_input"
                                        name="login_input"
                                        type="text"
                                        autofocus
                                        value="<?= e($_POST['login_input'] ?? '') ?>"
                                        placeholder="เช่น admin@webpark.co.th หรือ admin_webpark"
                                        class="input-futuristic"
                                        style="padding-left: 1rem;">
                                </div>
                                <div id="login_input-error" class="inline-error-msg">
                                    <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="error-text">กรุณากรอก Email หรือ Username</span>
                                </div>
                            </div>

                            <button type="submit" id="request-submit-btn" class="btn-action">
                                <span>ขอรับรหัส OTP 6 หลัก</span>
                                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>

                    <!-- ============================================================ -->
                    <!-- STEP 2 FORM: 6-Digit Smart OTP Inputs                        -->
                    <!-- ============================================================ -->
                    <?php elseif ($step === 'verify'): ?>
                        <form id="otp-form" method="post" action="forgot_password.php?step=verify" autocomplete="off" novalidate style="display: flex; flex-direction: column; gap: 0.75rem; margin: 0;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="verify_otp">
                            <input type="hidden" id="full-otp-code" name="otp_code" value="">

                            <!-- 6 Separate Input Boxes -->
                            <div class="otp-grid" id="otp-inputs-wrapper">
                                <?php for ($i = 0; $i < 6; $i++): ?>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        maxlength="1"
                                        class="otp-digit"
                                        data-index="<?= $i ?>"
                                        name="otp_digit[]"
                                        autocomplete="one-time-code"
                                        <?= $i === 0 ? 'autofocus' : '' ?>>
                                <?php endfor; ?>
                            </div>

                            <div id="otp-error" class="inline-error-msg" style="justify-content: center; margin-bottom: 0.25rem;">
                                <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="error-text">กรุณากรอกรหัส OTP ให้ครบทั้ง 6 หลัก</span>
                            </div>

                            <!-- Expiry Countdown & Resend Button -->
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; color: #64748b; padding: 0.25rem 0;">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <span>รหัสหมดอายุใน:</span>
                                    <span id="otp-countdown" data-seconds="<?= $otpRemainingSeconds ?>" style="font-family: monospace; font-weight: 700; color: #b91c1c;">
                                        --:--
                                    </span>
                                </div>
                                <button type="submit" form="resend-form" id="resend-otp-btn" style="background: none; border: none; font-size: inherit; color: #0284c7; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0;">
                                    ขอรหัสใหม่อีกครั้ง
                                </button>
                            </div>

                            <button type="submit" id="verify-btn" class="btn-action">
                                <span>ยืนยันรหัส OTP</span>
                                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>

                        <form id="resend-form" method="post" action="forgot_password.php?step=verify" style="display: none;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="resend_otp">
                        </form>

                    <!-- ============================================================ -->
                    <!-- STEP 3 FORM: Reset New Password                              -->
                    <!-- ============================================================ -->
                    <?php else: ?>
                        <form id="reset-form" method="post" action="forgot_password.php?step=reset" autocomplete="off" novalidate style="display: flex; flex-direction: column; gap: 1rem; margin: 0;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="reset_password">

                            <!-- New Password -->
                            <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                                <label id="password-label" for="password" style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; transition: color 0.15s;">
                                    รหัสผ่านใหม่
                                </label>
                                <div style="position: relative; width: 100%;">
                                    <span style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; padding-left: 0.875rem; color: #94a3b8; pointer-events: none;">
                                        <svg style="width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        autofocus
                                        placeholder="อย่างน้อย 6 ตัวอักษร"
                                        class="input-futuristic"
                                        style="padding-right: 2.75rem;">
                                    
                                    <!-- Toggle Show/Hide Button for Password -->
                                    <button
                                        type="button"
                                        id="toggle-password"
                                        aria-label="Toggle password visibility"
                                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.875rem; color: #94a3b8; background: none; border: none; cursor: pointer; transition: color 0.15s ease;"
                                        onmouseover="this.style.color='#0f172a'"
                                        onmouseout="this.style.color='#94a3b8'">
                                        <!-- Eye Open -->
                                        <svg id="eye-open-1" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <!-- Eye Closed (Slash) -->
                                        <svg id="eye-closed-1" style="width: 18px; height: 18px; display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                    </button>
                                </div>
                                <div id="password-error" class="inline-error-msg">
                                    <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="error-text">กรุณากรอกรหัสผ่านใหม่อย่างน้อย 6 ตัวอักษร</span>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                                <label id="confirm_password-label" for="confirm_password" style="display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; transition: color 0.15s;">
                                    ยืนยันรหัสผ่านใหม่
                                </label>
                                <div style="position: relative; width: 100%;">
                                    <span style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; padding-left: 0.875rem; color: #94a3b8; pointer-events: none;">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </span>
                                    <input
                                        id="confirm_password"
                                        name="confirm_password"
                                        type="password"
                                        placeholder="กรอกรหัสผ่านใหม่อีกครั้ง"
                                        class="input-futuristic"
                                        style="padding-right: 2.75rem;">
                                    
                                    <!-- Toggle Show/Hide Button for Confirm Password -->
                                    <button
                                        type="button"
                                        id="toggle-confirm-password"
                                        aria-label="Toggle confirm password visibility"
                                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.875rem; color: #94a3b8; background: none; border: none; cursor: pointer; transition: color 0.15s ease;"
                                        onmouseover="this.style.color='#0f172a'"
                                        onmouseout="this.style.color='#94a3b8'">
                                        <!-- Eye Open -->
                                        <svg id="eye-open-2" style="width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <!-- Eye Closed (Slash) -->
                                        <svg id="eye-closed-2" style="width: 18px; height: 18px; display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                    </button>
                                </div>
                                <div id="confirm_password-error" class="inline-error-msg">
                                    <svg style="width: 14px; height: 14px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="error-text">รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน</span>
                                </div>
                            </div>

                            <button type="submit" id="reset-submit-btn" class="btn-action">
                                <span>บันทึกรหัสผ่านและปลดล็อกระบบ</span>
                                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Footer Back to Sign In Link -->
                    <div style="text-align: center; padding-top: 0.5rem;">
                        <a href="<?= ADMIN_URL ?>/login.php" style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 600; color: #64748b; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#0f172a';" onmouseout="this.style.color='#64748b';">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>กลับไปยังหน้าเข้าสู่ระบบ</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- JAVASCRIPT: High-Performance Eye Tracking & Validation       -->
    <!-- ============================================================ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stage = document.getElementById('interactive-stage');
            const robotsGroup = document.getElementById('robots-group');
            const r1Pupils = document.querySelectorAll('.r1-pupil');
            const r2Pupils = document.querySelectorAll('.r2-pupil');
            const r3Pupils = document.querySelectorAll('.r3-pupil');
            const r4Pupils = document.querySelectorAll('.r4-pupil');

            let mouseX = window.innerWidth / 2;
            let mouseY = window.innerHeight / 2;
            let targetX = mouseX;
            let targetY = mouseY;
            let isPasswordFocused = false;

            // Global mouse tracker
            window.addEventListener('mousemove', (e) => {
                targetX = e.clientX;
                targetY = e.clientY;
            }, { passive: true });

            // Touch support for mobile devices
            window.addEventListener('touchmove', (e) => {
                if (e.touches.length > 0) {
                    targetX = e.touches[0].clientX;
                    targetY = e.touches[0].clientY;
                }
            }, { passive: true });

            // Core Animation Loop (60fps requestAnimationFrame)
            function animateScene() {
                mouseX += (targetX - mouseX) * 0.1;
                mouseY += (targetY - mouseY) * 0.1;

                if (stage && robotsGroup) {
                    const rect = stage.getBoundingClientRect();
                    const stageCenterX = rect.left + rect.width / 2;
                    const stageCenterY = rect.top + rect.height / 2;

                    const stageTiltX = ((mouseX - stageCenterX) / window.innerWidth) * 6;
                    const stageTiltY = ((mouseY - stageCenterY) / window.innerHeight) * 6;
                    robotsGroup.style.transform = `translate3d(${stageTiltX * 0.6}px, ${stageTiltY * 0.6}px, 0)`;

                    function updatePupils(pupils, robotEl, maxOffset = 6, bodyTiltFactor = 4) {
                        if (!robotEl || pupils.length === 0) return;
                        const rRect = robotEl.getBoundingClientRect();
                        const rCenterX = rRect.left + rRect.width / 2;
                        const rCenterY = rRect.top + rRect.height / 2;

                        const dx = mouseX - rCenterX;
                        const dy = mouseY - rCenterY;
                        const dist = Math.hypot(dx, dy) || 1;

                        const clampedX = (dx / dist) * Math.min(maxOffset, dist * 0.04);
                        const clampedY = (dy / dist) * Math.min(maxOffset, dist * 0.04);

                        if (!isPasswordFocused) {
                            pupils.forEach(p => {
                                p.style.transform = `translate3d(${clampedX}px, ${clampedY}px, 0)`;
                            });
                        }
                    }

                    updatePupils(r1Pupils, document.getElementById('robot-1'), 5, 3);
                    updatePupils(r2Pupils, document.getElementById('robot-2'), 4, -2.5);
                    updatePupils(r3Pupils, document.getElementById('robot-3'), 4.5, 3.5);
                    updatePupils(r4Pupils, document.getElementById('robot-4'), 4, -3);
                }

                requestAnimationFrame(animateScene);
            }

            requestAnimationFrame(animateScene);

            // Helper to show inline custom validation error
            function setError(inputEl, labelEl, errorEl, msg) {
                if (!inputEl) return;
                inputEl.classList.add('is-invalid');
                if (labelEl) labelEl.classList.add('label-invalid');
                if (errorEl) {
                    if (msg) {
                        const span = errorEl.querySelector('.error-text');
                        if (span) span.textContent = msg;
                    }
                    errorEl.style.display = 'flex';
                }
            }

            function clearError(inputEl, labelEl, errorEl) {
                if (!inputEl) return;
                inputEl.classList.remove('is-invalid');
                if (labelEl) labelEl.classList.remove('label-invalid');
                if (errorEl) errorEl.style.display = 'none';
            }

            // ============================================================
            // STEP 1 VALIDATION
            // ============================================================
            const requestForm = document.getElementById('request-form');
            const loginInput = document.getElementById('login_input');
            const loginLabel = document.getElementById('login_input-label');
            const loginError = document.getElementById('login_input-error');

            if (requestForm && loginInput) {
                loginInput.addEventListener('input', () => {
                    if (loginInput.value.trim() !== '') {
                        clearError(loginInput, loginLabel, loginError);
                    }
                });

                requestForm.addEventListener('submit', (e) => {
                    if (loginInput.value.trim() === '') {
                        e.preventDefault();
                        setError(loginInput, loginLabel, loginError, 'กรุณากรอก Email หรือ Username');
                        loginInput.focus();
                    }
                });
            }

            // ============================================================
            // STEP 2: SMART 6-DIGIT OTP CONTROLS & VALIDATION
            // ============================================================
            const otpInputs = Array.from(document.querySelectorAll('.otp-digit'));
            const fullOtpInput = document.getElementById('full-otp-code');
            const otpForm = document.getElementById('otp-form');
            const otpError = document.getElementById('otp-error');

            if (otpInputs.length === 6) {
                setTimeout(() => otpInputs[0].focus(), 150);

                function syncFullOtp() {
                    const code = otpInputs.map(input => input.value).join('');
                    if (fullOtpInput) fullOtpInput.value = code;
                    return code;
                }

                otpInputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        const val = input.value.replace(/[^0-9]/g, '');
                        input.value = val ? val.slice(-1) : '';

                        if (input.value !== '') {
                            input.classList.add('filled');
                            input.classList.remove('is-invalid');
                            if (index < otpInputs.length - 1) {
                                otpInputs[index + 1].focus();
                                otpInputs[index + 1].select();
                            }
                        } else {
                            input.classList.remove('filled');
                        }

                        const code = syncFullOtp();
                        if (code.length === 6) {
                            if (otpError) otpError.style.display = 'none';
                            setTimeout(() => {
                                if (otpForm) otpForm.submit();
                            }, 200);
                        }
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace') {
                            if (input.value === '' && index > 0) {
                                otpInputs[index - 1].focus();
                                otpInputs[index - 1].value = '';
                                otpInputs[index - 1].classList.remove('filled');
                                e.preventDefault();
                            } else {
                                input.value = '';
                                input.classList.remove('filled');
                            }
                            syncFullOtp();
                        } else if (e.key === 'ArrowLeft' && index > 0) {
                            otpInputs[index - 1].focus();
                            e.preventDefault();
                        } else if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                            e.preventDefault();
                        }
                    });

                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                        const cleanDigits = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                        if (cleanDigits.length > 0) {
                            cleanDigits.split('').forEach((char, i) => {
                                if (otpInputs[i]) {
                                    otpInputs[i].value = char;
                                    otpInputs[i].classList.add('filled');
                                    otpInputs[i].classList.remove('is-invalid');
                                }
                            });
                            syncFullOtp();

                            const focusIndex = Math.min(cleanDigits.length, otpInputs.length - 1);
                            otpInputs[focusIndex].focus();

                            if (cleanDigits.length === 6 && otpForm) {
                                if (otpError) otpError.style.display = 'none';
                                setTimeout(() => otpForm.submit(), 250);
                            }
                        }
                    });
                });

                if (otpForm) {
                    otpForm.addEventListener('submit', (e) => {
                        const code = syncFullOtp();
                        if (code.length < 6) {
                            e.preventDefault();
                            otpInputs.forEach(input => {
                                if (!input.value) input.classList.add('is-invalid');
                            });
                            if (otpError) otpError.style.display = 'flex';
                        }
                    });
                }
            }

            // OTP Countdown Timer
            const countdownEl = document.getElementById('otp-countdown');
            if (countdownEl && countdownEl.dataset.seconds) {
                const initialSeconds = parseInt(countdownEl.dataset.seconds, 10) || 0;
                const targetEndTime = Date.now() + (initialSeconds * 1000);

                function formatTime(totalSeconds) {
                    const m = Math.floor(totalSeconds / 60);
                    const s = totalSeconds % 60;
                    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                }

                function updateCountdown() {
                    const now = Date.now();
                    const rem = Math.max(0, Math.ceil((targetEndTime - now) / 1000));
                    if (rem <= 0) {
                        clearInterval(timerInterval);
                        countdownEl.textContent = '00:00 (หมดอายุ)';
                        return;
                    }
                    countdownEl.textContent = formatTime(rem);
                }

                updateCountdown();
                const timerInterval = setInterval(updateCountdown, 1000);
            }

            // ============================================================
            // STEP 3: SHOW/HIDE PASSWORD TOGGLES & VALIDATION
            // ============================================================
            const resetForm = document.getElementById('reset-form');
            const passwordInput = document.getElementById('password');
            const passwordLabel = document.getElementById('password-label');
            const passwordError = document.getElementById('password-error');

            const confirmPasswordInput = document.getElementById('confirm_password');
            const confirmPasswordLabel = document.getElementById('confirm_password-label');
            const confirmPasswordError = document.getElementById('confirm_password-error');

            const togglePasswordBtn = document.getElementById('toggle-password');
            const eyeOpen1 = document.getElementById('eye-open-1');
            const eyeClosed1 = document.getElementById('eye-closed-1');

            const toggleConfirmBtn = document.getElementById('toggle-confirm-password');
            const eyeOpen2 = document.getElementById('eye-open-2');
            const eyeClosed2 = document.getElementById('eye-closed-2');

            // Password Toggle 1
            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', () => {
                    const isPass = passwordInput.type === 'password';
                    passwordInput.type = isPass ? 'text' : 'password';
                    if (eyeOpen1 && eyeClosed1) {
                        eyeOpen1.style.display = isPass ? 'none' : 'block';
                        eyeClosed1.style.display = isPass ? 'block' : 'none';
                    }
                });
            }

            // Password Toggle 2
            if (toggleConfirmBtn && confirmPasswordInput) {
                toggleConfirmBtn.addEventListener('click', () => {
                    const isPass = confirmPasswordInput.type === 'password';
                    confirmPasswordInput.type = isPass ? 'text' : 'password';
                    if (eyeOpen2 && eyeClosed2) {
                        eyeOpen2.style.display = isPass ? 'none' : 'block';
                        eyeClosed2.style.display = isPass ? 'block' : 'none';
                    }
                });
            }

            // Step 3 Real-time Clear
            if (passwordInput) {
                passwordInput.addEventListener('input', () => {
                    if (passwordInput.value.length >= 6) {
                        clearError(passwordInput, passwordLabel, passwordError);
                    }
                });
            }

            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', () => {
                    if (confirmPasswordInput.value === passwordInput.value && confirmPasswordInput.value !== '') {
                        clearError(confirmPasswordInput, confirmPasswordLabel, confirmPasswordError);
                    }
                });
            }

            // Step 3 Form Submit Check
            if (resetForm && passwordInput && confirmPasswordInput) {
                resetForm.addEventListener('submit', (e) => {
                    let hasError = false;

                    // Password Check
                    if (passwordInput.value === '') {
                        e.preventDefault();
                        setError(passwordInput, passwordLabel, passwordError, 'กรุณากรอกรหัสผ่านใหม่');
                        hasError = true;
                    } else if (passwordInput.value.length < 6) {
                        e.preventDefault();
                        setError(passwordInput, passwordLabel, passwordError, 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร');
                        hasError = true;
                    } else {
                        clearError(passwordInput, passwordLabel, passwordError);
                    }

                    // Confirm Password Check
                    if (confirmPasswordInput.value === '') {
                        e.preventDefault();
                        setError(confirmPasswordInput, confirmPasswordLabel, confirmPasswordError, 'กรุณายืนยันรหัสผ่านใหม่อีกครั้ง');
                        hasError = true;
                    } else if (confirmPasswordInput.value !== passwordInput.value) {
                        e.preventDefault();
                        setError(confirmPasswordInput, confirmPasswordLabel, confirmPasswordError, 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน');
                        hasError = true;
                    } else {
                        clearError(confirmPasswordInput, confirmPasswordLabel, confirmPasswordError);
                    }

                    if (hasError) {
                        if (passwordInput.value.length < 6) {
                            passwordInput.focus();
                        } else {
                            confirmPasswordInput.focus();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>
