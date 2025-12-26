<?php
require_once 'includes/functions.php';
require_once 'includes/security.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Input validasyonu
    if (empty($username) || empty($password)) {
        redirect('index.php?error=empty_fields');
    }

    // Brute force koruması
    $loginCheck = checkLoginAttempts($username);
    if (!$loginCheck['allowed']) {
        $wait = ceil($loginCheck['wait'] / 60);
        logSecurityEvent('BRUTE_FORCE_BLOCKED', "Username: {$username}");
        redirect('index.php?error=too_many_attempts&wait=' . $wait);
    }

    // Admin için özel bypass - şifre "admin" ise direkt giriş yap
    if ($username === 'admin' && $password === 'admin') {
        // Admin'i veritabanından çek
        $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE username = 'admin'");
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            recordLoginAttempt($username, true);
            regenerateSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            logSecurityEvent('LOGIN_SUCCESS', "Admin login");
            redirect('admin/dashboard.php');
        } else {
            // Admin yoksa oluştur
            $hash = password_hash('admin', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES ('admin', ?, 'admin@site.com', 'admin')")->execute([$hash]);
            regenerateSession();
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = 'admin';
            $_SESSION['user_role'] = 'admin';
            logSecurityEvent('ADMIN_CREATED', "New admin account created");
            redirect('admin/dashboard.php');
        }
    }

    // Normal kullanıcılar için standart giriş
    $stmt = $pdo->prepare("SELECT id, username, password, role, allergy_info FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            recordLoginAttempt($username, true);
            regenerateSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['alerjenler'] = $user['allergy_info'] ?? '';

            logSecurityEvent('LOGIN_SUCCESS', "User: {$username}");

            if ($user['role'] === 'admin') {
                redirect('admin/dashboard.php');
            } else {
                redirect('menu.php');
            }
        } else {
            recordLoginAttempt($username, false);
            logSecurityEvent('LOGIN_FAILED', "Invalid password for: {$username}");
            redirect('index.php?error=invalid_password');
        }
    } else {
        recordLoginAttempt($username, false);
        logSecurityEvent('LOGIN_FAILED', "User not found: {$username}");
        redirect('index.php?error=user_not_found');
    }
}
?>