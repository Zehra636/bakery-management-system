<?php
session_start();
require_once 'includes/db_connect.php';

// Hata mesajları
$error_messages = [
    'empty_fields' => '❌ Lütfen tüm alanları doldurun!',
    'invalid_username' => '❌ Kullanıcı adı 3-30 karakter olmalı (harf, rakam, alt çizgi)!',
    'invalid_email' => '❌ Geçerli bir e-posta adresi girin!',
    'weak_password' => '❌ Şifre en az 6 karakter olmalı!',
    'user_exists' => '❌ Bu kullanıcı adı veya e-posta zaten kayıtlı!',
    'registration_failed' => '❌ Kayıt başarısız oldu. Tekrar deneyin!',
    'too_many_attempts' => '❌ Çok fazla deneme! 5 dakika bekleyin.'
];

$error = isset($_GET['error']) && isset($error_messages[$_GET['error']])
    ? $error_messages[$_GET['error']]
    : '';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol - Lezzet Dünyası</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            max-width: 480px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }

        .login-card .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 25px;
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

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #764ba2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .alerjen-box {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe8ec 100%);
            padding: 18px;
            border-radius: 15px;
        }

        .alerjen-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .alerjen-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 10px 12px;
            border-radius: 10px;
            background: white;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .alerjen-item:hover {
            border-color: #c44569;
        }

        .alerjen-item input {
            accent-color: #c44569;
            width: 18px;
            height: 18px;
        }

        .error-box {
            background: #fee2e2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: shake 0.5s ease-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #764ba2;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .emoji-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="emoji-title">🎂</div>
        <h2>Aramıza Katılın!</h2>
        <p class="subtitle">Lezzet Dünyası ailesine hoş geldiniz</p>

        <?php if ($error): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="register_action.php" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Kullanıcı Adı</label>
                <input type="text" name="username" class="form-control" placeholder="örn: ahmet_123" required
                    minlength="3" maxlength="30">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> E-posta</label>
                <input type="email" name="email" class="form-control" placeholder="ornek@email.com" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Şifre</label>
                <input type="password" name="password" class="form-control" placeholder="En az 6 karakter" required
                    minlength="6">
            </div>
            <div class="form-group">
                <label style="margin-bottom: 12px;">
                    <i class="fas fa-leaf" style="color: #10b981;"></i>
                    Alerjen Durumu <small style="color: #888;">(Varsa seçiniz)</small>
                </label>
                <div class="alerjen-box">
                    <div class="alerjen-grid">
                        <label class="alerjen-item">
                            <input type="checkbox" name="alerjen[]" value="gluten">
                            <span>🌾 Gluten</span>
                        </label>
                        <label class="alerjen-item">
                            <input type="checkbox" name="alerjen[]" value="sut">
                            <span>🥛 Süt</span>
                        </label>
                        <label class="alerjen-item">
                            <input type="checkbox" name="alerjen[]" value="fistik">
                            <span>🥜 Fıstık</span>
                        </label>
                        <label class="alerjen-item">
                            <input type="checkbox" name="alerjen[]" value="yumurta">
                            <span>🥚 Yumurta</span>
                        </label>
                        <label class="alerjen-item">
                            <input type="checkbox" name="alerjen[]" value="cikolata">
                            <span>🍫 Çikolata</span>
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Kayıt Ol
            </button>
        </form>
        <div class="back-link">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Giriş Ekranına Dön</a>
        </div>
    </div>
</body>

</html>