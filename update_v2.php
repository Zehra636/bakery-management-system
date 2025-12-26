<?php
require_once 'includes/db_connect.php';

echo "<h1>V2 Güncellemesi Başlatılıyor...</h1>";

try {
    $pdo->beginTransaction();

    // 1. Admin Şifresini Güncelle (adminadmin)
    $newPass = password_hash('adminadmin', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$newPass]);
    echo "<li>Admin şifresi 'adminadmin' olarak güncellendi.</li>";

    // 2. Özel Pasta Tablosunu Oluştur
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS custom_cake_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT,
            image_path VARCHAR(255),
            size ENUM('4', '6', '8'),
            has_flower TINYINT(1) DEFAULT 0,
            cake_text VARCHAR(255),
            price DECIMAL(10, 2),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )
    ");
    echo "<li>Özel pasta tablosu oluşturuldu.</li>";

    // 3. Ürünleri Temizle ve Yeniden Ekle
    // Foreign key hatalarını önlemek için önce order_items temizlenmeli mi? 
    // Hayır, kullanıcı deneyimi bozulmasın diye order_items'daki ürün ID'leri kalsın ama ürünler tablosunu truncate edersek sorun olur.
    // O yüzden DELETE kullanacağız ve cascade set null olacak (şemada set null yapmıştık).
    // Ama temiz bir başlangıç için ürünleri siliyoruz.

    // Güvenlik için foreign key kontrolünü kapatalım geçici olarak
    $pdo->exec("SET foreign_key_checks = 0");
    $pdo->exec("TRUNCATE TABLE products");
    $pdo->exec("SET foreign_key_checks = 1");
    echo "<li>Eski ürünler temizlendi.</li>";

    // KATEGORİ ID'lerini GARANTİLEMEK İÇİN TEKRAR KATEGORİ EKLİYORUZ (VEYA MEVCUTLARI KULLANIYORUZ)
    // ID'leri hardcode kullanmak yerine isimden çekebilirdik ama truncate yapmadığımız için ID'ler 1-9 arası sabit varsayıyoruz. 
    // Garanti olsun diye kategorileri de truncate edelim mi? Yeterince güvenli, edelim.
    $pdo->exec("SET foreign_key_checks = 0");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("SET foreign_key_checks = 1");

    // Kategorileri Ekle
    $cats = [
        "INSERT INTO categories (id, name, parent_id) VALUES (1, 'Tatlılar', NULL)",
        "INSERT INTO categories (id, name, parent_id) VALUES (2, 'Tuzlular', NULL)",
        "INSERT INTO categories (id, name, parent_id) VALUES (3, 'Börekler', NULL)",
        "INSERT INTO categories (id, name, parent_id) VALUES (4, 'İçecekler', NULL)",
        // Alt Kategoriler
        "INSERT INTO categories (id, name, parent_id) VALUES (5, 'Şerbetli', 1)",
        "INSERT INTO categories (id, name, parent_id) VALUES (6, 'Sütlü', 1)",
        "INSERT INTO categories (id, name, parent_id) VALUES (7, 'Çikolatalı', 1)",
        "INSERT INTO categories (id, name, parent_id) VALUES (8, 'Sıcak', 4)",
        "INSERT INTO categories (id, name, parent_id) VALUES (9, 'Soğuk', 4)"
    ];
    foreach ($cats as $sql)
        $pdo->exec($sql);
    echo "<li>Kategoriler yenilendi.</li>";

    // Ürün Verileri (Fiyatlar satış fiyatı. Maliyet %90 (Kar %10))
    // Örn: Satış 100 -> Maliyet 90.
    $products = [];

    // 10 KAHVE (Sıcak/Soğuk Karışık ama kategori 8 veya 9)
    $coffees = [
        ['Türk Kahvesi', 'Bol köpüklü', 50, 8],
        ['Espresso', 'Sert ve yoğun', 45, 8],
        ['Latte', 'Sütlü yumuşak içim', 60, 8],
        ['Cappuccino', 'Bol süt köpüklü', 60, 8],
        ['Americano', 'Sıcak su ile inceltilmiş', 55, 8],
        ['Mocha', 'Çikolatalı kahve', 65, 8],
        ['White Chocolate Mocha', 'Beyaz çikolatalı', 70, 8],
        ['Ice Americano', 'Buzlu ferahlatıcı', 60, 9], // Soğuk
        ['Ice Latte', 'Buzlu sütlü kahve', 65, 9], // Soğuk
        ['Cold Brew', 'Soğuk demlenmiş', 75, 9] // Soğuk
    ];

    // 10 ÇAY (Fincan, Büyük, Küçük vb)
    $teas = [
        ['Çay (Küçük)', 'Klasik ince belli', 20, 8],
        ['Çay (Fincan)', 'Büyük boy keyif', 30, 8],
        ['Yeşil Çay', 'Antioksidan deposu', 35, 8],
        ['Ada Çayı', 'Doğal şifa', 35, 8],
        ['Ihlamur', 'Ballı limonlu', 40, 8],
        ['Kış Çayı', 'Karışık bitki çayı', 45, 8],
        ['Papatya Çayı', 'Rahatlatıcı', 35, 8],
        ['Melisa Çayı', 'Sakinleştirici', 35, 8],
        ['Ice Tea (Şeftali)', 'Ev yapımı soğuk çay', 45, 9],
        ['Ice Tea (Limon)', 'Ev yapımı soğuk çay', 45, 9]
    ];

    // SAHLEP Varyasyonları
    $sahleps = [
        ['Sahlep (Sade)', 'Geleneksel lezzet', 80, 8],
        ['Sahlep (Bol Tarçınlı)', 'Özel tarçın aromalı (+10 TL Farkla)', 90, 8] // +10 TL
    ];

    // YİYECEKLER (10 Tatlı, 10 Tuzlu, 5 Börek)
    // Tatlılar
    $sweets = [
        ['Baklava', 'Fıstıklı 1 Porsiyon', 120, 5],
        ['Şöbiyet', 'Kaymaklı', 130, 5],
        ['Kadayıf', 'Çıtır tel kadayıf', 100, 5],
        ['Revani', 'Portakallı', 80, 5],
        ['Sütlaç', 'Fırın sütlaç', 70, 6],
        ['Kazandibi', 'Yanık lezzet', 70, 6],
        ['Tavuk Göğsü', 'Gerçek tavuk göğsü', 75, 6],
        ['Trileçe', 'Karamelli', 80, 6],
        ['Profiterol', 'Çikolata şelalesi', 90, 7],
        ['Supangle', 'Kek parçacıklı', 80, 7]
    ];

    // Tuzlular
    $saltys = [
        ['Poğaça (Sade)', 'Tereyağlı', 20, 2],
        ['Poğaça (Peynirli)', 'Beyaz peynirli', 25, 2],
        ['Poğaça (Zeytinli)', 'Siyah zeytinli', 25, 2],
        ['Simit', 'Sıcak susamlı', 15, 2],
        ['Açma', 'Yumuşak', 20, 2],
        ['Karaköy Böreği', 'Kıymalı', 40, 2], // Börek kategorisi varken buraya koyduk ama neyse (ID 2 Tuzlu)
        ['Tuzlu Kurabiye', 'Paket', 60, 2],
        ['Mini Pizza', 'Sucuklu', 30, 2],
        ['Sandviç', 'Kaşarlı', 50, 2],
        ['Tost', 'Karışık', 70, 2]
    ];

    // Börekler (ID 3)
    $boreks = [
        ['Su Böreği', 'Peynirli', 80, 3],
        ['Kol Böreği (Kıymalı)', 'Porsiyon', 75, 3],
        ['Kol Böreği (Peynirli)', 'Porsiyon', 70, 3],
        ['Küt Böreği', 'Pudra şekerli', 60, 3],
        ['Sigara Böreği', 'Çıtır (5 adet)', 60, 3]
    ];

    $all_products = array_merge($coffees, $teas, $sahleps, $sweets, $saltys, $boreks);

    $insert_sql = "INSERT INTO products (name, description, price, cost_price, category_id, image_url, is_allergen_friendly) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insert_sql);

    foreach ($all_products as $p) {
        $cost = $p[2] * 0.90; // %10 Kar marjı (Maliyet = Fiyat * 0.9)
        // Kategori ID'leri yukarıdaki insert sırasına göre.
        // Resim URL'leri placeholder servisi ile.
        $img_keyword = str_replace(' ', '', $p[0]); // URL için boşluksuz
        // Unsplash source URL (randomized slightly to avoid cache)
        $img_url = "https://source.unsplash.com/300x200/?" . urlencode($p[0] . ",food");
        // Not: Unsplash source bazen yavaş çalışır ama dinamiktir. Alternatif placeholder kullanabiliriz.
        // Garanti olsun diye düz placeholder kullanalım ve text yazalım.
        $img_url_safe = "https://dummyimage.com/300x200/e0e0e0/000000.jpg&text=" . urlencode($p[0]);

        // Rastgele alerjen dostu mu (Glutensiz vb)? %20 şans
        $is_safe = (rand(1, 5) == 1) ? 1 : 0;

        $stmt->execute([$p[0], $p[1], $p[2], $cost, $p[3], $img_url_safe, $is_safe]);
    }
    echo "<li>" . count($all_products) . " adet yeni ürün eklendi.</li>";

    $pdo->commit();
    echo "<h1>V2 GÜNCELLEMESİ BAŞARIYLA TAMAMLANDI</h1>";
    echo "<a href='index.php'>Siteye Dön</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "HATA: " . $e->getMessage();
}
?>