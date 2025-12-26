<?php
require_once 'includes/db_connect.php';

echo "<h1>Yönetici Hesabı Onarımı</h1>";

try {
    // 1. Önce eski admini silelim (Temiz başlangıç)
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = 'admin'");
    $stmt->execute();
    echo "<li>Eski admin kaydı temizlendi.</li>";

    // 2. Yeni admini ekleyelim
    // Şifre: admin
    $pass_hash = password_hash('admin', PASSWORD_DEFAULT);
    $email = 'admin@profiterol.com';

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute(['admin', $pass_hash, $email]);

    echo "<li>Yeni admin hesabı oluşturuldu.</li>";
    echo "<li>Kullanıcı Adı: <b>admin</b></li>";
    echo "<li>Şifre: <b>admin</b></li>";

    // 3. Test edelim
    if (password_verify('admin', $pass_hash)) {
        echo "<li style='color:green'><b>Doğrulama Testi: BAŞARILI. Şifre mekanizması düzgün çalışıyor.</b></li>";
    } else {
        echo "<li style='color:red'>HATA: Hash doğrulama başarısız! Sunucunuzda sorun olabilir.</li>";
    }

    echo "<br><a href='index.php' style='font-size:20px; background:blue; color:white; padding:10px; text-decoration:none;'>Logine Git</a>";

} catch (PDOException $e) {
    echo "Veritabanı Hatası: " . $e->getMessage();
}
?>