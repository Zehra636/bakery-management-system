<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/security.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Rate limiting - kayıt spam koruması
    if (!checkRateLimit('register', 5, 300)) {
        logSecurityEvent('RATE_LIMIT_EXCEEDED', 'Registration attempt blocked');
        header('Location: register.php?error=too_many_attempts');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Input validasyonu
    if (empty($username) || empty($email) || empty($password)) {
        header('Location: register.php?error=empty_fields');
        exit;
    }

    // Kullanıcı adı validasyonu (3-30 karakter, harf, rakam, alt çizgi)
    if (!preg_match('/^[a-zA-Z0-9_öüçğışÖÜÇĞİŞ]{3,30}$/u', $username)) {
        header('Location: register.php?error=invalid_username');
        exit;
    }

    // E-posta validasyonu
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: register.php?error=invalid_email');
        exit;
    }

    // Şifre validasyonu (en az 6 karakter)
    if (strlen($password) < 6) {
        header('Location: register.php?error=weak_password');
        exit;
    }

    // Şifreyi hashle
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Alerjen checkbox'larını virgülle birleştir
    $alerjenler = '';
    if (isset($_POST['alerjen']) && is_array($_POST['alerjen'])) {
        $alerjenler = implode(',', array_map('htmlspecialchars', $_POST['alerjen']));
    }

    try {
        // Kullanıcı adı veya email zaten var mı kontrol et
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->fetch()) {
            header('Location: register.php?error=user_exists');
            exit;
        }

        // Kullanıcıyı kaydet
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, allergy_info) VALUES (?, ?, ?, 'customer', ?)");
        $stmt->execute([$username, $password_hash, $email, $alerjenler]);

        // Otomatik giriş yap
        $last_id = $pdo->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $last_id;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = 'customer';
        $_SESSION['alerjenler'] = $alerjenler;

        // Hoşgeldin e-postası gönder
        require_once 'includes/email.php';
        sendWelcomeEmail($email, $username);

        // Log kaydet
        logSecurityEvent('REGISTRATION_SUCCESS', "New user: {$username} ({$email})");

        // Menüye yönlendir
        header('Location: menu.php?welcome=1');
        exit;

    } catch (PDOException $e) {
        logSecurityEvent('REGISTRATION_FAILED', "Error: " . $e->getMessage());
        header('Location: register.php?error=registration_failed');
        exit;
    }
} else {
    // POST değilse ana sayfaya yönlendir
    header('Location: index.php');
    exit;
}
?>