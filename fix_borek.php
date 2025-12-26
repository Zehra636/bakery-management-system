<?php
require_once 'includes/db_connect.php';

echo "<h1>Su Böreği Görseli Güncelleniyor...</h1>";

// Su böreği için özel güncelleme
$sql = "UPDATE products SET image_url = 'assets/images/suboregi.png' WHERE name LIKE '%Su Böreği%' OR name LIKE '%Su böreği%' OR name LIKE '%Böreği%'";
$affected = $pdo->exec($sql);

echo "<li>Su Böreği güncellendi: $affected kayıt</li>";

// Tüm börekleri de güncelle
$sql2 = "UPDATE products SET image_url = 'assets/images/suboregi.png' WHERE name LIKE '%Börek%'";
$affected2 = $pdo->exec($sql2);
echo "<li>Diğer börekler güncellendi: $affected2 kayıt</li>";

// Kontrol için ürünleri listele
$stmt = $pdo->query("SELECT id, name, image_url FROM products WHERE name LIKE '%Börek%' OR name LIKE '%börek%'");
$products = $stmt->fetchAll();

echo "<h3>Börek Ürünleri:</h3>";
foreach ($products as $p) {
    echo "<li>{$p['name']} -> {$p['image_url']}</li>";
}

echo "<br><a href='menu.php'>Menüye Git</a>";
?>