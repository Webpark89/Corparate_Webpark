<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Mailer service wrapper using PHPMailer with Gmail SMTP.
 */
class Mailer
{
    /**
     * Send contact form notification email.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $settings
     * @return bool True if mail sent successfully, false otherwise.
     */
    public static function sendContactNotification(array $data, array $settings = []): bool
    {
        // Require composer autoloader if PHPMailer is not already loaded
        if (!class_exists(PHPMailer::class)) {
            $vendorAutoload = __DIR__ . '/../../../vendor/autoload.php';
            if (file_exists($vendorAutoload)) {
                require_once $vendorAutoload;
            }
        }

        if (!class_exists(PHPMailer::class)) {
            error_log('[Mailer] PHPMailer class not found.');
            return false;
        }

        $mailHost = $settings['mail_host'] ?? 'smtp.gmail.com';
        $mailPort = (int) ($settings['mail_port'] ?? 587);
        $mailUser = $settings['mail_user'] ?? 'webpark-contact-form';
        $mailPass = str_replace(' ', '', (string)($settings['mail_pass'] ?? 'pcvgpqnttmnmlvcw'));
        $mailTo   = $settings['mail_to'] ?? 'kidzmaioxe@gmail.com';
        $fromName = $settings['mail_from_name'] ?? 'WEBPARK Contact System';

        // For Gmail SMTP, username usually needs the full address
        $fromEmail = strpos($mailUser, '@') !== false ? $mailUser : ($mailUser . '@gmail.com');

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $mailHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $fromEmail;
            $mail->Password   = $mailPass;
            $mail->CharSet    = 'UTF-8';

            if ($mailPort === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $mailPort;
            $mail->Timeout    = 15;

            // Recipients
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($mailTo);
            $mail->addReplyTo($data['email'], $data['first_name'] . ' ' . $data['last_name']);

            // Content
            $mail->isHTML(true);
            $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name'], ENT_QUOTES, 'UTF-8');
            $company = htmlspecialchars(!empty($data['company_name']) ? $data['company_name'] : '-', ENT_QUOTES, 'UTF-8');
            $phone = htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
            $message = nl2br(htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8'));
            $pdpaTime = htmlspecialchars($data['pdpa_consent_at'] ?? date('Y-m-d H:i:s'), ENT_QUOTES, 'UTF-8');
            $ip = htmlspecialchars($data['ip_address'] ?? '-', ENT_QUOTES, 'UTF-8');
            $sourcePage = htmlspecialchars($data['source_page'] ?? '-', ENT_QUOTES, 'UTF-8');

            $mail->Subject = "📩 มีข้อความติดต่อใหม่จาก: {$fullName}" . (!empty($data['company_name']) ? " ({$company})" : "");

            $bodyHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background: #0663F6; color: #ffffff; padding: 24px 32px; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 700; }
        .header p { margin: 4px 0 0 0; opacity: 0.85; font-size: 13px; }
        .content { padding: 28px 32px; }
        .row { margin-bottom: 16px; }
        .label { font-size: 12px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 4px; }
        .value { font-size: 15px; color: #0f172a; font-weight: 500; }
        .message-box { background: #f1f5f9; border-left: 4px solid #0663F6; padding: 16px; border-radius: 8px; font-size: 14px; line-height: 1.6; color: #334155; }
        .meta-table { width: 100%; border-collapse: collapse; font-size: 12px; color: #64748b; margin-top: 24px; padding-top: 16px; border-top: 1px dashed #e2e8f0; }
        .meta-table td { padding: 4px 0; }
        .footer { background: #f8fafc; text-align: center; padding: 16px 32px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>📩 ข้อความติดต่อใหม่ (Website Contact Form)</h2>
            <p>ส่งเมื่อ: {$pdpaTime}</p>
        </div>
        <div class="content">
            <div class="row">
                <div class="label">ชื่อ - นามสกุล</div>
                <div class="value">{$fullName}</div>
            </div>
            <div class="row">
                <div class="label">ชื่อบริษัท / องค์กร</div>
                <div class="value">{$company}</div>
            </div>
            <div class="row">
                <div class="label">เบอร์โทรศัพท์</div>
                <div class="value"><a href="tel:{$phone}" style="color: #0663F6; text-decoration: none;">{$phone}</a></div>
            </div>
            <div class="row">
                <div class="label">อีเมล</div>
                <div class="value"><a href="mailto:{$email}" style="color: #0663F6; text-decoration: none;">{$email}</a></div>
            </div>
            <div class="row">
                <div class="label">รายละเอียดข้อความ</div>
                <div class="message-box">{$message}</div>
            </div>
            <table class="meta-table">
                <tr>
                    <td><strong>PDPA Consent:</strong> ยินยอมเมื่อ {$pdpaTime}</td>
                    <td style="text-align: right;"><strong>IP:</strong> {$ip}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>หน้าที่ส่ง:</strong> {$sourcePage}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            ข้อความนี้ส่งอัตโนมัติจากระบบ WEBPARK Contact Form
        </div>
    </div>
</body>
</html>
HTML;

            $mail->Body    = $bodyHtml;
            $mail->AltBody = "ข้อความติดต่อใหม่จาก: {$data['first_name']} {$data['last_name']}\nบริษัท: " . (!empty($data['company_name']) ? $data['company_name'] : '-') . "\nเบอร์โทร: {$data['phone']}\nอีเมล: {$data['email']}\n\nข้อความ:\n{$data['message']}\n\nPDPA Consent: ยินยอม ({$pdpaTime})\nIP: {$ip}";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("[Mailer Error] Message could not be sent. Mailer Error: {$mail->ErrorInfo} | Exception: {$e->getMessage()}");
            return false;
        }
    }
}
