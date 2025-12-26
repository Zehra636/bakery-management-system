<?php
require 'includes/db_connect.php';

echo "<h2>Yönetici Şifre Kontrolü</h2>";

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    die("HATA: 'admin' kullanicisi veritabaninda bulunamadi!");
}

echo "Kullanıcı bulundu: admin<br>";
echo "Mevcut Hash Uzunluğu: " . strlen($user['password']) . " karakter<br>";

// Test 1: Mevcut şifre 'admin' mi?
$check = password_verify('admin', $user['password']);
echo "Şifre 'admin' mi? : " . ($check ? "<span style='color:green; font-weight:bold;'>EVET DOĞRU</span>" : "<span style='color:red; font-weight:bold;'>HAYIR YANLIŞ</span>") . "<br>";

// Eğer yanlışsa düzelt
if (!$check) {
    echo "<hr>Şifre onarılıyor... (Yeni şifre: <b>admin</b>)<br>";
    $newHash = password_hash('admin', PASSWORD_DEFAULT);

    $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $update->execute([$newHash]);

    // Tekrar test et
    $check2 = password_verify('admin', $newHash);
    echo "Onarım sonrası kontrol: " . ($check2 ? "<span style='color:green; font-weight:bold;'>BAŞARILI! ŞİMDİ GİREBİLİRSİNİZ.</span>" : "HATA: Onarım başarısız oldu.");
} else {
    echo "<hr><span style='color:green'>Sistemdeki şifre zaten 'admin'. Lütfen boşluk bırakmadan yazdığınızdan emin olun.</span>";
}
?>