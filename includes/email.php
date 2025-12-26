<?php
/**
 * E-POSTA BİLDİRİM SİSTEMİ - LEZZET DÜNYASI
 * HTML e-posta şablonları ve gönderim fonksiyonları
 * 
 * AYARLAR:
 * - EMAIL_MODE = 'log'      -> E-postalar logs/emails/ klasörüne kaydedilir (TEST)
 * - EMAIL_MODE = 'smtp'     -> Gmail SMTP ile gerçek e-posta gönderilir
 * - EMAIL_MODE = 'mail'     -> PHP mail() fonksiyonu kullanılır
 */

// =====================
// YAPILANDIRMA
// =====================
define('EMAIL_MODE', 'log'); // 'log', 'smtp', 'mail'

// Site ayarları
define('SITE_NAME', 'Lezzet Dünyası Pastanesi');
define('SITE_EMAIL', 'info@lezzetdunyasi.com');
define('SITE_URL', 'http://localhost/pastane');

// Gmail SMTP Ayarları (EMAIL_MODE = 'smtp' için)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com'); // Gmail adresiniz
define('SMTP_PASSWORD', 'your-app-password');     // Gmail App Password
define('SMTP_FROM_NAME', SITE_NAME);

/**
 * E-posta gönderme fonksiyonu
 */
function sendEmail($to, $subject, $body, $from_name = SITE_NAME)
{
    $result = false;

    switch (EMAIL_MODE) {
        case 'log':
            // E-postayı dosyaya kaydet (test modu)
            $result = logEmailToFile($to, $subject, $body);
            break;

        case 'smtp':
            // Gmail SMTP ile gönder
            $result = sendViaSMTP($to, $subject, $body, $from_name);
            break;

        case 'mail':
        default:
            // PHP mail() fonksiyonu
            $result = sendViaMail($to, $subject, $body, $from_name);
            break;
    }

    // Log
    if (function_exists('logSecurityEvent')) {
        $mode = EMAIL_MODE;
        logSecurityEvent('EMAIL_' . ($result ? 'SENT' : 'FAILED'), "Mode: {$mode} | To: {$to} | Subject: {$subject}");
    }

    return $result;
}

/**
 * E-postayı dosyaya kaydet (TEST MODU)
 */
function logEmailToFile($to, $subject, $body)
{
    $log_dir = __DIR__ . '/../logs/emails/';

    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $filename = date('Y-m-d_H-i-s') . '_' . md5($to . $subject) . '.html';
    $filepath = $log_dir . $filename;

    $content = "<!--
E-POSTA SİMÜLASYONU
===================
Tarih: " . date('Y-m-d H:i:s') . "
Kime: {$to}
Konu: {$subject}
===================
-->
" . $body;

    $result = file_put_contents($filepath, $content);

    // Ayrıca bir özet log dosyası tut
    $summary = "[" . date('Y-m-d H:i:s') . "] To: {$to} | Subject: {$subject} | File: {$filename}\n";
    file_put_contents($log_dir . 'email_log.txt', $summary, FILE_APPEND | LOCK_EX);

    return $result !== false;
}

/**
 * PHP mail() fonksiyonu ile gönder
 */
function sendViaMail($to, $subject, $body, $from_name)
{
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$from_name} <" . SITE_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . SITE_EMAIL . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    return @mail($to, $subject, $body, $headers);
}

/**
 * Gmail SMTP ile gönder (socket kullanarak)
 */
function sendViaSMTP($to, $subject, $body, $from_name)
{
    try {
        // SMTP bağlantısı aç
        $socket = @fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 30);

        if (!$socket) {
            error_log("SMTP Error: Could not connect to " . SMTP_HOST);
            return false;
        }

        // TLS başlat
        stream_set_timeout($socket, 30);

        // SMTP komutları
        $response = fgets($socket, 515);

        // EHLO
        fwrite($socket, "EHLO " . $_SERVER['SERVER_NAME'] . "\r\n");
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ')
                break;
        }

        // STARTTLS
        fwrite($socket, "STARTTLS\r\n");
        $response = fgets($socket, 515);

        // TLS'i etkinleştir
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

        // Tekrar EHLO
        fwrite($socket, "EHLO " . $_SERVER['SERVER_NAME'] . "\r\n");
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ')
                break;
        }

        // AUTH LOGIN
        fwrite($socket, "AUTH LOGIN\r\n");
        $response = fgets($socket, 515);

        // Kullanıcı adı
        fwrite($socket, base64_encode(SMTP_USERNAME) . "\r\n");
        $response = fgets($socket, 515);

        // Şifre
        fwrite($socket, base64_encode(SMTP_PASSWORD) . "\r\n");
        $response = fgets($socket, 515);

        // MAIL FROM
        fwrite($socket, "MAIL FROM:<" . SMTP_USERNAME . ">\r\n");
        $response = fgets($socket, 515);

        // RCPT TO
        fwrite($socket, "RCPT TO:<{$to}>\r\n");
        $response = fgets($socket, 515);

        // DATA
        fwrite($socket, "DATA\r\n");
        $response = fgets($socket, 515);

        // E-posta içeriği
        $headers = "From: {$from_name} <" . SMTP_USERNAME . ">\r\n";
        $headers .= "To: {$to}\r\n";
        $headers .= "Subject: {$subject}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "\r\n";

        fwrite($socket, $headers . $body . "\r\n.\r\n");
        $response = fgets($socket, 515);

        // QUIT
        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return strpos($response, '250') !== false;

    } catch (Exception $e) {
        error_log("SMTP Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Hoşgeldin E-postası (Kayıt sonrası)
 */
function sendWelcomeEmail($email, $username)
{
    $subject = "🎂 Hoş Geldiniz! - " . SITE_NAME;

    $body = getEmailTemplate('welcome', [
        'username' => $username,
        'site_url' => SITE_URL
    ]);

    return sendEmail($email, $subject, $body);
}

/**
 * Sipariş Onay E-postası
 */
function sendOrderConfirmationEmail($email, $order_data)
{
    $subject = "🧁 Siparişiniz Alındı! #" . $order_data['order_id'] . " - " . SITE_NAME;

    $body = getEmailTemplate('order_confirmation', $order_data);

    return sendEmail($email, $subject, $body);
}

/**
 * Sipariş Durumu Güncelleme E-postası
 */
function sendOrderStatusEmail($email, $order_id, $status, $username)
{
    $status_texts = [
        'preparing' => '👨‍🍳 Hazırlanıyor',
        'shipped' => '🏍️ Yola Çıktı',
        'delivered' => '✅ Teslim Edildi'
    ];

    $subject = $status_texts[$status] . " - Sipariş #" . $order_id . " - " . SITE_NAME;

    $body = getEmailTemplate('order_status', [
        'order_id' => $order_id,
        'status' => $status,
        'status_text' => $status_texts[$status] ?? $status,
        'username' => $username,
        'site_url' => SITE_URL
    ]);

    return sendEmail($email, $subject, $body);
}

/**
 * Şifre Sıfırlama E-postası
 */
function sendPasswordResetEmail($email, $reset_token)
{
    $subject = "🔐 Şifre Sıfırlama - " . SITE_NAME;

    $reset_link = SITE_URL . "/reset_password.php?token=" . $reset_token;

    $body = getEmailTemplate('password_reset', [
        'reset_link' => $reset_link,
        'site_url' => SITE_URL
    ]);

    return sendEmail($email, $subject, $body);
}

/**
 * E-posta şablonlarını oluştur
 */
function getEmailTemplate($template, $data = [])
{
    $header = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: "Segoe UI", Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 1.8rem; }
            .header .emoji { font-size: 3rem; display: block; margin-bottom: 10px; }
            .content { padding: 30px; }
            .content h2 { color: #333; margin-top: 0; }
            .content p { color: #666; line-height: 1.6; }
            .button { display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important; text-decoration: none; border-radius: 30px; font-weight: bold; margin: 20px 0; }
            .order-box { background: #f8f9fa; border-radius: 15px; padding: 20px; margin: 20px 0; }
            .order-box .item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
            .order-box .total { font-size: 1.3rem; font-weight: bold; color: #10b981; border-top: 2px solid #eee; padding-top: 15px; margin-top: 10px; }
            .status-badge { display: inline-block; padding: 10px 20px; border-radius: 20px; font-weight: bold; color: white; }
            .status-preparing { background: #3498db; }
            .status-shipped { background: #9b59b6; }
            .status-delivered { background: #27ae60; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #888; font-size: 0.9rem; }
            .footer a { color: #764ba2; }
        </style>
    </head>
    <body>
        <div class="container">';

    $footer = '
            <div class="footer">
                <p>Bu e-posta ' . SITE_NAME . ' tarafından gönderilmiştir.</p>
                <p><a href="' . SITE_URL . '">' . SITE_URL . '</a></p>
                <p>📞 0850 123 45 67 | 📍 Türkiye\'nin 81 ilinde hizmetinizdeyiz</p>
            </div>
        </div>
    </body>
    </html>';

    // Şablonları oluştur
    switch ($template) {
        case 'welcome':
            $content = '
            <div class="header">
                <span class="emoji">🎂</span>
                <h1>Hoş Geldiniz!</h1>
            </div>
            <div class="content">
                <h2>Merhaba ' . htmlspecialchars($data['username']) . '! 👋</h2>
                <p>
                    <strong>' . SITE_NAME . '</strong> ailesine katıldığınız için çok mutluyuz! 🎉
                </p>
                <p>
                    Artık en lezzetli tatlılarımızı, el yapımı pastalarımızı ve özel tariflerimizi 
                    keşfedebilir, siparişlerinizi kolayca takip edebilirsiniz.
                </p>
                <p>
                    🎁 <strong>İlk siparişinizde %10 indirim!</strong><br>
                    Kupon kodunuz: <strong style="color: #764ba2;">HOŞGELDIN</strong>
                </p>
                <center>
                    <a href="' . $data['site_url'] . '/menu.php" class="button">🛒 Alışverişe Başla</a>
                </center>
                <p style="margin-top: 30px;">
                    Afiyet olsun! 🍰<br>
                    <strong>' . SITE_NAME . ' Ekibi</strong>
                </p>
            </div>';
            break;

        case 'order_confirmation':
            $items_html = '';
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $items_html .= '<div class="item"><span>' . htmlspecialchars($item['name']) . ' x' . $item['qty'] . '</span><span>' . number_format($item['subtotal'], 2) . ' TL</span></div>';
                }
            }

            $content = '
            <div class="header">
                <span class="emoji">🧁</span>
                <h1>Siparişiniz Alındı!</h1>
            </div>
            <div class="content">
                <h2>Merhaba ' . htmlspecialchars($data['username'] ?? 'Değerli Müşterimiz') . '! 🎉</h2>
                <p>
                    Siparişiniz başarıyla alındı. En kısa sürede hazırlanmaya başlanacaktır.
                </p>
                
                <div class="order-box">
                    <h3 style="margin-top: 0;">📦 Sipariş #' . $data['order_id'] . '</h3>
                    ' . $items_html . '
                    <div class="total">Toplam: ' . number_format($data['total'] ?? 0, 2) . ' TL</div>
                </div>
                
                <p><strong>📍 Teslimat Adresi:</strong><br>' . htmlspecialchars($data['address'] ?? '') . '</p>
                <p><strong>⏰ Tahmini Teslimat:</strong> 25-40 dakika</p>
                
                <center>
                    <a href="' . SITE_URL . '/siparis_takip.php" class="button">📍 Siparişimi Takip Et</a>
                </center>
            </div>';
            break;

        case 'order_status':
            $status_class = 'status-' . $data['status'];
            $content = '
            <div class="header">
                <span class="emoji">' . ($data['status'] == 'delivered' ? '✅' : ($data['status'] == 'shipped' ? '🏍️' : '👨‍🍳')) . '</span>
                <h1>Sipariş Durumu Güncellendi</h1>
            </div>
            <div class="content">
                <h2>Merhaba ' . htmlspecialchars($data['username']) . '!</h2>
                <p>
                    <strong>#' . $data['order_id'] . '</strong> numaralı siparişinizin durumu güncellendi:
                </p>
                <center>
                    <span class="status-badge ' . $status_class . '">' . $data['status_text'] . '</span>
                </center>
                ' . ($data['status'] == 'delivered' ? '<p style="text-align: center; font-size: 1.2rem; margin-top: 20px;">🎂 Afiyet olsun!</p>' : '') . '
                <center>
                    <a href="' . $data['site_url'] . '/siparis_takip.php" class="button">📍 Detayları Gör</a>
                </center>
            </div>';
            break;

        case 'password_reset':
            $content = '
            <div class="header">
                <span class="emoji">🔐</span>
                <h1>Şifre Sıfırlama</h1>
            </div>
            <div class="content">
                <h2>Şifrenizi mi unuttunuz?</h2>
                <p>
                    Şifrenizi sıfırlamak için aşağıdaki butona tıklayın. 
                    Bu link 1 saat boyunca geçerlidir.
                </p>
                <center>
                    <a href="' . $data['reset_link'] . '" class="button">🔑 Şifremi Sıfırla</a>
                </center>
                <p style="color: #888; font-size: 0.9rem; margin-top: 30px;">
                    ⚠️ Bu isteği siz yapmadıysanız, bu e-postayı görmezden gelebilirsiniz.
                </p>
            </div>';
            break;

        default:
            $content = '<div class="content"><p>E-posta içeriği</p></div>';
    }

    return $header . $content . $footer;
}

/**
 * E-posta gönderimini test et
 */
function testEmailSystem($test_email)
{
    $result = sendEmail(
        $test_email,
        "🧪 Test E-postası - " . SITE_NAME,
        getEmailTemplate('welcome', ['username' => 'Test Kullanıcı', 'site_url' => SITE_URL])
    );

    return $result;
}

/**
 * Gönderilen e-postaları listele (TEST MODU için)
 */
function getEmailLogs()
{
    $log_dir = __DIR__ . '/../logs/emails/';
    $log_file = $log_dir . 'email_log.txt';

    if (file_exists($log_file)) {
        return file_get_contents($log_file);
    }

    return "Henüz e-posta gönderilmedi.";
}
?>