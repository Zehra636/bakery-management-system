<?php
require_once 'includes/db_connect.php';
try {
    // Şifreyi 'admin' olarak güncelliyoruz.
    $new_pass = password_hash('admin', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$new_pass]);

    echo "<h1>Şifre Başarıyla Güncellendi</h1>";
    echo "<p>Admin şifreniz: <b>admin</b> olarak ayarlandı.</p>";
    echo "<p><a href='index.php'>Giriş Yap</a></p>";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>