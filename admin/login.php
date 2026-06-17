<?php
require_once __DIR__ . '/includes/functions.php';

// Security headers for login page
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_verify();

  // Rate limiting: 5 attempts per 10 minutes
  $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $rateLimitKey = 'login_' . $clientIp;

  if (!check_rate_limit($rateLimitKey, 5, 600)) {
    $remaining = get_rate_limit_remaining($rateLimitKey, 5, 600);
    $err = 'จำนวนครั้งการลองเข้าสู่ระบบเกินกำหนด กรุณารอ 10 นาทีก่อนลองอีกครั้ง (ความพยายามที่เหลือ: ' . $remaining . ')';
  } else {
    $u = trim($_POST['username'] ?? '');
    $p = (string)($_POST['password'] ?? '');

    if ($u === '' || $p === '') {
      $err = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } else {
      if ($u === ADMIN_USERNAME && password_verify($p, ADMIN_PASSWORD_HASH)) {
        // Clear rate limit on successful login
        unset($_SESSION['ratelimit_' . $rateLimitKey]);

        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = ADMIN_USERNAME;
        $_SESSION['admin_full_name'] = 'Administrator';
        $_SESSION['admin_role'] = 'admin';
        $_SESSION['last_activity'] = time();

        header('Location: ' . ADMIN_URL . '/index.php');
        exit;
      }
      $err = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
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

  <!-- Google Fonts: Inter & Noto Sans Thai -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Noto+Sans+Thai:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="<?= ADMIN_URL ?>/assets/css/dist/tailwind.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-slate-100 overflow-hidden">

  <!-- Background Glow -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-[-120px] left-[-120px] w-[320px] h-[320px] bg-cyan-300/40 blur-3xl rounded-full"></div>
    <div class="absolute bottom-[-120px] right-[-120px] w-[320px] h-[320px] bg-indigo-300/40 blur-3xl rounded-full"></div>
  </div>

  <main class="relative z-10 flex items-center justify-center min-h-screen px-6">
    <div class="w-full max-w-md">
      <!-- Card -->
      <div class="rounded-3xl bg-white border border-slate-200 shadow-2xl p-8">

        <!-- Header -->
        <div class="text-center mb-8">
          <div class="flex justify-center mb-5">
            <div class="w-20 h-20 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-sm">
              <img
                src="<?= ADMIN_URL ?>/assets/images/logo.png"
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

        <!-- Error -->
        <?php if ($err || !empty($_GET['timeout'])): ?>
          <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <?= e($err ?: 'Session timed out. Please log in again.') ?>
          </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="post" autocomplete="off" class="space-y-5">
          <?= csrf_field() ?>

          <!-- Username -->
          <div>
            <label
              for="username"
              class="block text-sm font-medium text-slate-700 mb-2">
              ชื่อผู้ใช้
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <i class="fa-regular fa-user"></i>
              </span>
              <input
                id="username"
                name="username"
                type="text"
                required
                autofocus
                placeholder="กรอกชื่อผู้ใช้"
                class="w-full rounded-xl border border-slate-300 bg-white px-11 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
            </div>
          </div>

          <!-- Password -->
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-slate-700 mb-2">
              รหัสผ่าน
            </label>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <i class="fa-solid fa-lock"></i>
              </span>
              <input
                id="password"
                name="password"
                type="password"
                required
                placeholder="••••••••"
                class="w-full rounded-xl border border-slate-300 bg-white px-11 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15">
            </div>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition-all duration-300 hover:bg-blue-800 hover:shadow-lg">
            <span class="flex items-center justify-center gap-2">
              <i class="fa-solid fa-shield-check"></i>
              เข้าสู่ระบบ
            </span>
          </button>
        </form>
        <!-- Footer -->
        <div class="mt-8 border-t border-slate-200 pt-5 text-center text-xs text-slate-500">
          <div>Secure Authentication System</div>
          <div class="mt-1">
            © <?= date('Y') ?> <?= e(SITE_NAME) ?>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>