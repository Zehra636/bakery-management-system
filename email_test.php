<?php
/**
 * E-POSTA TEST VE GÖRÜNTÜLEME PANELİ
 * Admin kullanıcıları e-postaları test edebilir ve logları görebilir
 */
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/email.php';

// Sadece admin erişebilir
if (!isAdmin()) {
    header('Location: index.php');
    exit;
}

$message = '';
$test_result = null;

// Test e-postası gönder
if (isset($_POST['send_test'])) {
    $test_email = trim($_POST['test_email']);
    if (filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
        $test_result = testEmailSystem($test_email);
        $message = $test_result ? '✅ Test e-postası gönderildi!' : '❌ E-posta gönderilemedi!';
    } else {
        $message = '❌ Geçersiz e-posta adresi!';
    }
}

// E-posta dosyalarını listele
$email_files = [];
$email_dir = __DIR__ . '/logs/emails/';
if (is_dir($email_dir)) {
    $files = glob($email_dir . '*.html');
    rsort($files); // En yeniler önce
    $email_files = array_slice($files, 0, 20); // Son 20 e-posta
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📧 E-posta Test Paneli - Lezzet Dünyası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #764ba2;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .mode-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }

        .mode-log {
            background: #3498db;
        }

        .mode-smtp {
            background: #27ae60;
        }

        .mode-mail {
            background: #9b59b6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #764ba2;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
        }

        .email-list {
            list-style: none;
        }

        .email-list li {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .email-list li:hover {
            background: #f8f9fa;
        }

        .email-list .info {
            flex: 1;
        }

        .email-list .info .date {
            color: #888;
            font-size: 0.9rem;
        }

        .email-list .actions a {
            padding: 8px 15px;
            background: #764ba2;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .email-list .actions a:hover {
            background: #667eea;
        }

        .preview-frame {
            width: 100%;
            height: 500px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }

        .info-box {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3498db;
        }

        .info-box h4 {
            color: #2980b9;
            margin-bottom: 10px;
        }

        .info-box code {
            background: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #764ba2;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="admin/dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Admin Paneline Dön
        </a>

        <h1><i class="fas fa-envelope"></i> E-posta Test Paneli</h1>

        <!-- Mevcut Mod -->
        <div class="card">
            <h2><i class="fas fa-cog"></i> E-posta Ayarları</h2>

            <p>Mevcut Mod:
                <span class="mode-badge mode-<?php echo EMAIL_MODE; ?>">
                    <?php
                    $modes = [
                        'log' => '📁 LOG (Test Modu)',
                        'smtp' => '📤 SMTP (Gmail)',
                        'mail' => '📧 PHP Mail'
                    ];
                    echo $modes[EMAIL_MODE] ?? EMAIL_MODE;
                    ?>
                </span>
            </p>

            <div class="info-box">
                <h4><i class="fas fa-info-circle"></i> Mod Bilgileri</h4>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>LOG:</strong> E-postalar <code>logs/emails/</code> klasörüne HTML olarak kaydedilir
                        (test için)</li>
                    <li><strong>SMTP:</strong> Gmail SMTP ile gerçek e-posta gönderilir</li>
                    <li><strong>MAIL:</strong> PHP'nin mail() fonksiyonu kullanılır</li>
                </ul>
                <p style="margin-top: 15px;">
                    Modu değiştirmek için: <code>includes/email.php</code> dosyasında <code>EMAIL_MODE</code> değerini
                    değiştirin.
                </p>
            </div>
        </div>

        <!-- Test Gönderimi -->
        <div class="card">
            <h2><i class="fas fa-paper-plane"></i> Test E-postası Gönder</h2>

            <?php if ($message): ?>
                <div class="message <?php echo $test_result ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="test_email">E-posta Adresi:</label>
                    <input type="email" name="test_email" id="test_email" placeholder="test@example.com" required>
                </div>
                <button type="submit" name="send_test" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Test E-postası Gönder
                </button>
            </form>
        </div>

        <!-- E-posta Önizleme (LOG modu için) -->
        <?php if (EMAIL_MODE == 'log' && !empty($email_files)): ?>
            <div class="card">
                <h2><i class="fas fa-inbox"></i> Gönderilen E-postalar (Son 20)</h2>

                <ul class="email-list">
                    <?php foreach ($email_files as $file):
                        $filename = basename($file);
                        $date = substr($filename, 0, 19);
                        $date = str_replace('_', ' ', $date);
                        $date = str_replace('-', ':', substr($date, 11));
                        $date = substr($filename, 0, 10) . ' ' . substr($date, 11);
                        ?>
                        <li>
                            <div class="info">
                                <strong><i class="fas fa-envelope"></i> <?php echo $filename; ?></strong>
                                <div class="date"><?php echo $date; ?></div>
                            </div>
                            <div class="actions">
                                <a href="?view=<?php echo urlencode($filename); ?>" target="_blank">
                                    <i class="fas fa-eye"></i> Görüntüle
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- E-posta Önizleme -->
        <?php if (isset($_GET['view']) && EMAIL_MODE == 'log'):
            $view_file = $email_dir . basename($_GET['view']);
            if (file_exists($view_file)):
                ?>
                <div class="card">
                    <h2><i class="fas fa-eye"></i> E-posta Önizleme: <?php echo htmlspecialchars($_GET['view']); ?></h2>
                    <iframe src="logs/emails/<?php echo htmlspecialchars(basename($_GET['view'])); ?>"
                        class="preview-frame"></iframe>
                </div>
            <?php endif; endif; ?>

        <!-- Gmail SMTP Kurulum Rehberi -->
        <div class="card">
            <h2><i class="fab fa-google"></i> Gmail SMTP Kurulum Rehberi</h2>

            <div class="info-box" style="background: #fff3cd; border-color: #ffc107;">
                <h4 style="color: #856404;"><i class="fas fa-exclamation-triangle"></i> Gmail SMTP Kullanmak İçin:</h4>
                <ol style="margin-left: 20px; margin-top: 10px; line-height: 2;">
                    <li>Gmail hesabınızda <strong>2 Adımlı Doğrulama</strong>'yı aktifleştirin</li>
                    <li><a href="https://myaccount.google.com/apppasswords" target="_blank">Google App Passwords</a>
                        sayfasına gidin</li>
                    <li>"Mail" uygulaması için yeni bir şifre oluşturun</li>
                    <li>Oluşturulan 16 karakterlik şifreyi kopyalayın</li>
                    <li><code>includes/email.php</code> dosyasını açın</li>
                    <li>Aşağıdaki değerleri güncelleyin:
                        <pre style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px;">
define('EMAIL_MODE', 'smtp');
define('SMTP_USERNAME', 'sizin-email@gmail.com');
define('SMTP_PASSWORD', 'xxxx-xxxx-xxxx-xxxx'); // App Password
                        </pre>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</body>

</html>