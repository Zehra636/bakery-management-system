<?php
require_once 'includes/db_connect.php';

echo "<h1>Veritabanı Onarımı</h1>";

try {
    // 1. custom_cake_orders tablosunu oluştur
    $sql = "CREATE TABLE IF NOT EXISTS custom_cake_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        image_path VARCHAR(255),
        size ENUM('4', '6', '8'),
        has_flower TINYINT(1) DEFAULT 0,
        cake_text VARCHAR(255),
        price DECIMAL(10, 2),
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    )";

    $pdo->exec($sql);
    echo "<li>✓ custom_cake_orders tablosu oluşturuldu/kontrol edildi</li>";

    echo "<h2>Onarım Tamamlandı!</h2>";
    echo "<a href='menu.php'>Menüye Git</a>";

} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>