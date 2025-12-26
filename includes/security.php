<?php
/**
 * GÜVENLİK SİSTEMİ - LEZZET DÜNYASI
 * Bu dosya tüm güvenlik fonksiyonlarını içerir
 */

// Session güvenliği - sadece session başlamadan önce ayarla
if (session_status() === PHP_SESSION_NONE) {
    @ini_set('session.cookie_httponly', 1);
    @ini_set('session.cookie_secure', 0); // HTTPS kullanılıyorsa 1 yapın
    @ini_set('session.use_strict_mode', 1);
}

// =====================
// CSRF TOKEN SİSTEMİ
// =====================
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token)
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

// =====================
// RATE LIMITING
// =====================
function checkRateLimit($action = 'default', $max_attempts = 10, $time_window = 60)
{
    $key = 'rate_limit_' . $action . '_' . getClientIP();

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }

    $data = $_SESSION[$key];

    // Zaman penceresi geçtiyse sıfırla
    if (time() - $data['first_attempt'] > $time_window) {
        $_SESSION[$key] = [
            'attempts' => 1,
            'first_attempt' => time()
        ];
        return true;
    }

    // Limit aşıldı mı?
    if ($data['attempts'] >= $max_attempts) {
        return false;
    }

    $_SESSION[$key]['attempts']++;
    return true;
}

function getClientIP()
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ?: '0.0.0.0';
}

// =====================
// XSS KORUMASI
// =====================
function cleanOutput($data)
{
    if (is_array($data)) {
        return array_map('cleanOutput', $data);
    }
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($data)
{
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// =====================
// INPUT VALİDASYON
// =====================
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateUsername($username)
{
    // 3-30 karakter, sadece harf, rakam ve alt çizgi
    return preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username);
}

function validatePassword($password)
{
    // En az 6 karakter
    return strlen($password) >= 6;
}

function validatePhone($phone)
{
    // Türk telefon numarası formatı
    return preg_match('/^(05)[0-9]{9}$/', preg_replace('/\s+/', '', $phone));
}

// =====================
// SQL INJECTION KORUMASI
// =====================
function escapeString($pdo, $string)
{
    return $pdo->quote($string);
}

// Sayısal değer kontrolü
function validateInt($value)
{
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

function validateFloat($value)
{
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
}

// =====================
// SESSION GÜVENLİĞİ
// =====================
function regenerateSession()
{
    session_regenerate_id(true);
}

function isSessionExpired($max_lifetime = 3600)
{
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }

    if (time() - $_SESSION['last_activity'] > $max_lifetime) {
        return true;
    }

    $_SESSION['last_activity'] = time();
    return false;
}

// =====================
// GÜVENLİK BAŞLIKLARI
// =====================
function setSecurityHeaders()
{
    // XSS koruma
    header('X-XSS-Protection: 1; mode=block');
    // Content type sniffing engelleme
    header('X-Content-Type-Options: nosniff');
    // Clickjacking koruma
    header('X-Frame-Options: SAMEORIGIN');
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// =====================
// BRUTE FORCE KORUMASI
// =====================
function checkLoginAttempts($username)
{
    $key = 'login_attempts_' . md5($username);

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'locked_until' => 0
        ];
    }

    $data = $_SESSION[$key];

    // Hesap kilitli mi?
    if ($data['locked_until'] > time()) {
        $remaining = $data['locked_until'] - time();
        return ['allowed' => false, 'wait' => $remaining];
    }

    return ['allowed' => true, 'attempts' => $data['attempts']];
}

function recordLoginAttempt($username, $success)
{
    $key = 'login_attempts_' . md5($username);

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'locked_until' => 0
        ];
    }

    if ($success) {
        // Başarılı giriş - sıfırla
        $_SESSION[$key] = [
            'attempts' => 0,
            'locked_until' => 0
        ];
    } else {
        // Başarısız giriş
        $_SESSION[$key]['attempts']++;

        // 5 başarısız denemeden sonra 5 dakika kilitle
        if ($_SESSION[$key]['attempts'] >= 5) {
            $_SESSION[$key]['locked_until'] = time() + 300; // 5 dakika
        }
    }
}

// =====================
// DOSYA UPLOAD GÜVENLİĞİ
// =====================
function validateUploadedFile($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 5242880)
{
    $errors = [];

    // Dosya var mı?
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $errors[] = 'Dosya yüklenemedi.';
        return ['valid' => false, 'errors' => $errors];
    }

    // Boyut kontrolü (varsayılan 5MB)
    if ($file['size'] > $max_size) {
        $errors[] = 'Dosya boyutu çok büyük. Maksimum: ' . ($max_size / 1024 / 1024) . 'MB';
    }

    // Uzantı kontrolü
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_types)) {
        $errors[] = 'Geçersiz dosya tipi. İzin verilenler: ' . implode(', ', $allowed_types);
    }

    // MIME type kontrolü
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];

    if (isset($allowed_mimes[$ext]) && $mime !== $allowed_mimes[$ext]) {
        $errors[] = 'Dosya içeriği uzantısıyla eşleşmiyor.';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'extension' => $ext,
        'mime' => $mime
    ];
}

// =====================
// LOG SİSTEMİ
// =====================
function logSecurityEvent($event_type, $details = '')
{
    $log_file = __DIR__ . '/../logs/security.log';
    $log_dir = dirname($log_file);

    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIP();
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';

    $log_entry = "[{$timestamp}] [{$event_type}] IP: {$ip} | User: {$user} | {$details}\n";

    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Otomatik güvenlik başlıklarını ayarla
setSecurityHeaders();
?>