<?php
require_once 'includes/db_connect.php';

// Admin şifresini "admin" olarak ayarla (basit ve hatırlanabilir)
$pass = password_hash('admin', PASSWORD_DEFAULT);

// Önce admin var mı kontrol et
$check = $pdo->query("SELECT id FROM users WHERE username = 'admin'")->fetch();

if ($check) {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$pass]);
    echo "Admin şifresi 'admin' olarak güncellendi!";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES ('admin', ?, 'admin@site.com', 'admin')");
    $stmt->execute([$pass]);
    echo "Admin hesabı oluşturuldu!";
}

echo "<br><br><b>Giriş Bilgileri:</b><br>";
echo "Kullanıcı Adı: admin<br>";
echo "Şifre: admin<br>";
echo "<br><a href='index.php'>Giriş Sayfasına Git</a>";
?>