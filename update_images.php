<?php
require_once 'includes/db_connect.php';

echo "<h1>Ürün Görselleri Güncelleniyor...</h1>";

// Kullanıcının yüklediği yerel resimlerle güncelle
$updates = [
    "UPDATE products SET image_url = 'assets/images/baklava.png' WHERE name LIKE '%Baklava%'",
    "UPDATE products SET image_url = 'assets/images/sutlac.png' WHERE name LIKE '%Sütlaç%'",
    "UPDATE products SET image_url = 'assets/images/profiterol.png' WHERE name LIKE '%Profiterol%'",
    "UPDATE products SET image_url = 'assets/images/suboregi.png' WHERE name LIKE '%Börek%'",
    "UPDATE products SET image_url = 'assets/images/glutensiz_kek.png' WHERE name LIKE '%Kek%'",
    // Limonata için internetten görsel
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=400&h=300&fit=crop' WHERE name LIKE '%Limonata%'",
];

$count = 0;
foreach ($updates as $sql) {
    $affected = $pdo->exec($sql);
    $count += $affected;
}

echo "<h2>$count ürün güncellendi!</h2>";
echo "<ul>";
echo "<li>Baklava ✓</li>";
echo "<li>Sütlaç ✓</li>";
echo "<li>Profiterol ✓</li>";
echo "<li>Su Böreği ✓</li>";
echo "<li>Glutensiz Kek ✓</li>";
echo "<li>Limonata ✓</li>";
echo "</ul>";
echo "<br><a href='menu.php' style='font-size:20px; background:#764ba2; color:white; padding:15px 30px; text-decoration:none; border-radius:10px;'>Menüye Git →</a>";
?>