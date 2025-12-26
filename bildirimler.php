<?php
/**
 * E-POSTA BİLDİRİMLERİ GÖRÜNTÜLEME
 * Bu sayfa gönderilen e-postaları gösterir
 */
session_start();
require_once 'includes/db_connect.php';

// logs/emails klasörünü kontrol et ve oluştur
$email_dir = __DIR__ . '/logs/emails/';
if (!is_dir($email_dir)) {
    mkdir($email_dir, 0755, true);
}

// E-posta dosyalarını listele
$email_files = [];
if (is_dir($email_dir)) {
    $files = glob($email_dir . '*.html');
    if ($files) {
        rsort($files); // En yeniler önce
        $email_files = array_slice($files, 0, 30); // Son 30 e-posta
    }
}

// Tek e-posta görüntüle
if (isset($_GET['view'])) {
    $file = $email_dir . basename($_GET['view']);
    if (file_exists($file)) {
        echo file_get_contents($file);
        exit;
    }
}

// E-posta sil
if (isset($_GET['delete']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    $file = $email_dir . basename($_GET['delete']);
    if (file_exists($file)) {
        unlink($file);
        header('Location: bildirimler.php?deleted=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📧 E-posta Bildirimleri - Lezzet Dünyası</title>
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
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .email-list {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .email-item {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .email-item:hover {
            background: #f8f9fa;
        }

        .email-item:last-child {
            border-bottom: none;
        }

        .email-info {
            flex: 1;
        }

        .email-info .subject {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .email-info .meta {
            font-size: 0.85rem;
            color: #888;
        }

        .email-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
            margin-right: 10px;
        }

        .badge-welcome {
            background: #10b981;
        }

        .badge-order {
            background: #3b82f6;
        }

        .badge-status {
            background: #8b5cf6;
        }

        .email-actions a {
            padding: 10px 18px;
            background: #764ba2;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-left: 10px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .email-actions a:hover {
            background: #667eea;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #764ba2;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .test-btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin-top: 15px;
        }

        .test-btn:hover {
            transform: scale(1.05);
        }

        .alert {
            padding: 15px 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="menu.php" class="back-link"><i class="fas fa-arrow-left"></i> Menüye Dön</a>

        <h1>📧 E-posta Bildirimleri</h1>

        <div class="info-box">
            <h3>📬 E-posta Sistemi Aktif</h3>
            <p>Tüm bildirimler burada görüntülenir. Test modunda e-postalar dosyaya kaydedilir.</p>
            <p style="margin-top: 10px;"><strong>Toplam:</strong> <?php echo count($email_files); ?> e-posta</p>
        </div>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert">✅ E-posta silindi!</div>
        <?php endif; ?>

        <div class="email-list">
            <?php if (empty($email_files)): ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <h3>Henüz e-posta yok</h3>
                    <p>Kayıt olun veya sipariş verin, e-postalar burada görünecek!</p>
                    <a href="register.php" class="test-btn"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
                </div>
            <?php else: ?>
                <?php foreach ($email_files as $file):
                    $filename = basename($file);
                    $content = file_get_contents($file);

                    // Konu satırını bul
                    preg_match('/Konu: (.+)/', $content, $matches);
                    $subject = $matches[1] ?? 'E-posta';

                    // Tarih
                    $date = substr($filename, 0, 10) . ' ' . str_replace('-', ':', substr($filename, 11, 8));

                    // E-posta tipi
                    $badge = 'order';
                    $badge_text = 'Sipariş';
                    if (strpos($subject, 'Hoş Geldiniz') !== false) {
                        $badge = 'welcome';
                        $badge_text = 'Hoşgeldin';
                    } elseif (strpos($subject, 'Durumu') !== false || strpos($subject, 'Yola') !== false) {
                        $badge = 'status';
                        $badge_text = 'Durum';
                    }
                    ?>
                    <div class="email-item">
                        <div class="email-info">
                            <span class="email-badge badge-<?php echo $badge; ?>"><?php echo $badge_text; ?></span>
                            <span class="subject"><?php echo htmlspecialchars($subject); ?></span>
                            <div class="meta">
                                <i class="fas fa-clock"></i> <?php echo $date; ?>
                                &nbsp;|&nbsp;
                                <i class="fas fa-file"></i> <?php echo $filename; ?>
                            </div>
                        </div>
                        <div class="email-actions">
                            <a href="?view=<?php echo urlencode($filename); ?>" target="_blank">
                                <i class="fas fa-eye"></i> Görüntüle
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>