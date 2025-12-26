<?php
require_once 'includes/db_connect.php';

echo "<h1>Admin Şifresi Sıfırlanıyor...</h1>";

// Şifreyi 'admin' olarak ayarla
$password = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);

// Önce admin var mı kontrol et
$stmt = $pdo->query("SELECT id FROM users WHERE username = 'admin'");
$admin = $stmt->fetch();

if ($admin) {
    $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'")->execute([$hash]);
    echo "<li>Admin şifresi güncellendi!</li>";
} else {
    $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES ('admin', ?, 'admin@lezzet.com', 'admin')")->execute([$hash]);
    echo "<li>Admin hesabı oluşturuldu!</li>";
}

echo "<h2>Giriş Bilgileri:</h2>";
echo "<p><strong>Kullanıcı Adı:</strong> admin</p>";
echo "<p><strong>Şifre:</strong> admin</p>";
echo "<br><a href='index.php' style='background:#764ba2; color:white; padding:15px 30px; text-decoration:none; border-radius:10px;'>Giriş Sayfasına Git</a>";
?>