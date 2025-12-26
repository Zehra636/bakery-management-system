<?php
require_once 'includes/db_connect.php';

echo "<h1>Görseller Güncelleniyor (Statik URL)...</h1>";

// Tüm ürünleri tek tek güncelle - %100 çalışan statik linkler
$updates = [
    // Tatlılar
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1519676867240-f03562e64548?w=400&h=300&fit=crop' WHERE name LIKE '%Baklava%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=300&fit=crop' WHERE name LIKE '%Sütlaç%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1541783245831-57d6fb0926d3?w=400&h=300&fit=crop' WHERE name LIKE '%Profiterol%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=300&fit=crop' WHERE name LIKE '%Börek%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop' WHERE name LIKE '%Kek%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=300&fit=crop' WHERE name LIKE '%Kahve%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?w=400&h=300&fit=crop' WHERE name LIKE '%Limonata%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1571115177098-24ec42ed204d?w=400&h=300&fit=crop' WHERE name LIKE '%Trileçe%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400&h=300&fit=crop' WHERE name LIKE '%Supangle%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1579954115545-a95591f28bfc?w=400&h=300&fit=crop' WHERE name LIKE '%Kadayıf%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1586985289688-ca3cf47d3e6e?w=400&h=300&fit=crop' WHERE name LIKE '%Revani%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=300&fit=crop' WHERE name LIKE '%Kazandibi%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=300&fit=crop' WHERE name LIKE '%Tavuk Göğsü%'",
    // Kahveler
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=400&h=300&fit=crop' WHERE name LIKE '%Espresso%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop' WHERE name LIKE '%Latte%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400&h=300&fit=crop' WHERE name LIKE '%Cappuccino%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1551030173-122aabc4489c?w=400&h=300&fit=crop' WHERE name LIKE '%Americano%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=400&h=300&fit=crop' WHERE name LIKE '%Mocha%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop' WHERE name LIKE '%Cold Brew%'",
    // Çaylar
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop' WHERE name LIKE '%Çay%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=400&h=300&fit=crop' WHERE name LIKE '%Yeşil%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop' WHERE name LIKE '%Ada%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop' WHERE name LIKE '%Ihlamur%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop' WHERE name LIKE '%Papatya%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop' WHERE name LIKE '%Melisa%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1499638673689-79a0b5115d87?w=400&h=300&fit=crop' WHERE name LIKE '%Ice Tea%'",
    // Sahlep
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400&h=300&fit=crop' WHERE name LIKE '%Sahlep%'",
    // Poğaça ve diğerleri
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop' WHERE name LIKE '%Poğaça%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1585478259715-876ace1ee8e9?w=400&h=300&fit=crop' WHERE name LIKE '%Simit%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop' WHERE name LIKE '%Açma%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=300&fit=crop' WHERE name LIKE '%Pizza%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&h=300&fit=crop' WHERE name LIKE '%Sandviç%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&h=300&fit=crop' WHERE name LIKE '%Tost%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=400&h=300&fit=crop' WHERE name LIKE '%Kurabiye%'",
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=400&h=300&fit=crop' WHERE name LIKE '%Kış Çayı%'",
    // Şerbetli tatlılar
    "UPDATE products SET image_url = 'https://images.unsplash.com/photo-1519676867240-f03562e64548?w=400&h=300&fit=crop' WHERE name LIKE '%Şöbiyet%'",
];

$count = 0;
foreach ($updates as $sql) {
    $affected = $pdo->exec($sql);
    if ($affected > 0) {
        $count += $affected;
        echo "<li>Güncellendi ✓</li>";
    }
}

// Kalan ürünleri varsayılan ile doldur
$default = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop';
$pdo->exec("UPDATE products SET image_url = '$default' WHERE image_url IS NULL OR image_url = '' OR image_url LIKE '%source.unsplash%' OR image_url LIKE '%dummyimage%'");

echo "<h2>$count ürün güncellendi!</h2>";
echo "<br><a href='menu.php' style='font-size:24px; background:#764ba2; color:white; padding:15px 30px; text-decoration:none; border-radius:10px;'>Menüye Git →</a>";
?>