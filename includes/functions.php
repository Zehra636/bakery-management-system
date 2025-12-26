<?php
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Güvenli girdi temizleme
function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Kullanıcı giriş yapmış mı kontrolü
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Yönetici mi kontrolü
function isAdmin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Alerji kontrolü (Basit metin bazlı)
function checkAllergy($product_id, $user_id)
{
    global $pdo;

    // Kullanıcı alerjilerini al
    $stmt = $pdo->prepare("SELECT allergy_info FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || empty($user['allergy_info'])) {
        return false; // Alerji kaydı yok
    }

    // Ürün alerjen dostu mu? (Basit mantık: Ürün 'is_allergen_friendly' ise güvenli kabul edelim şimdilik, 
    // daha detaylı eşleşme gerekirse ürünlere de 'contains_allergen' alanı eklenmeli.)
    // Şuan sadece Glutensiz vb. özel ürünleri 'is_allergen_friendly' olarak işaretledik.
    // Eğer kullanıcıda alerji varsa ve ürün dostu değilse uyarı ver.

    $stmt = $pdo->prepare("SELECT is_allergen_friendly FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $product['is_allergen_friendly'] == 0) {
        return true; // Risk var!
    }

    return false;
}

// Basit yönlendirme
function redirect($url)
{
    header("Location: $url");
    exit();
}
?>