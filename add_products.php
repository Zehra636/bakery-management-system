<?php
require_once 'includes/db_connect.php';

echo "<h1>Gerçek Yemek Görselleri Yükleniyor...</h1>";

// Tabloyu güncelle
try {
    $pdo->exec("ALTER TABLE products ADD COLUMN category VARCHAR(100) DEFAULT NULL");
} catch (Exception $e) {
}

try {
    $pdo->exec("ALTER TABLE products ADD COLUMN subcategory VARCHAR(100) DEFAULT NULL");
} catch (Exception $e) {
}

// Tüm ürünleri sil
$pdo->exec("DELETE FROM products");
$pdo->exec("ALTER TABLE products AUTO_INCREMENT = 1");

// Her ürün için özel seçilmiş gerçek yemek görselleri
$products = [
    // TATLILAR - ŞERBETLİ
    ['Baklava', 'Antep fıstıklı baklava', 120, 'Tatlılar', 'Şerbetli', 'assets/images/baklava.jpg'],
    ['Şöbiyet', 'Kaymak dolgulu', 130, 'Tatlılar', 'Şerbetli', 'assets/images/sobiyet.jpg'],
    ['Burma Kadayıf', 'Tel kadayıf cevizli', 100, 'Tatlılar', 'Şerbetli', 'assets/images/burma_kadayif.jpg'],
    ['Tulumba', 'Çıtır tulumba', 60, 'Tatlılar', 'Şerbetli', 'assets/images/tulumba.jpg'],
    ['Revani', 'İrmikli revani', 55, 'Tatlılar', 'Şerbetli', 'assets/images/revani.jpg'],
    ['Şekerpare', 'Şerbetli', 50, 'Tatlılar', 'Şerbetli', 'assets/images/sekerpare.jpg'],
    ['Kemalpaşa', 'Peynirli tatlı', 65, 'Tatlılar', 'Şerbetli', 'assets/images/kemalpasa.jpg'],
    ['Lokma', 'Taze lokma', 40, 'Tatlılar', 'Şerbetli', 'assets/images/lokma.jpg'],
    ['Vezir Parmağı', 'Cevizli', 110, 'Tatlılar', 'Şerbetli', 'assets/images/vezir_parmagi.jpg'],
    ['Ekmek Kadayıfı', 'Kaymaklı', 85, 'Tatlılar', 'Şerbetli', 'assets/images/ekmek_kadayifi.jpg'],

    // TATLILAR - SÜTLÜ
    ['Sütlaç', 'Fırın sütlaç', 45, 'Tatlılar', 'Sütlü', 'assets/images/sutlac.jpg'],
    ['Kazandibi', 'Karamelize', 50, 'Tatlılar', 'Sütlü', 'assets/images/kazandibi.jpg'],
    ['Tavuk Göğsü', 'Geleneksel', 55, 'Tatlılar', 'Sütlü', 'assets/images/tavuk_gogsu.jpg'],
    ['Muhallebi', 'Sade muhallebi', 35, 'Tatlılar', 'Sütlü', 'assets/images/muhallebi.jpg'],
    ['Keşkül', 'Bademli', 60, 'Tatlılar', 'Sütlü', 'assets/images/keskul.jpg'],
    ['Güllaç', 'Ramazan tatlısı', 65, 'Tatlılar', 'Sütlü', 'assets/images/gullac.jpg'],
    ['Profiterol', 'Çikolatalı', 75, 'Tatlılar', 'Sütlü', 'assets/images/profiterol.jpg'],
    ['Panna Cotta', 'İtalyan', 70, 'Tatlılar', 'Sütlü', 'assets/images/panna_cotta.jpg'],
    ['Magnolia', 'Meyveli', 80, 'Tatlılar', 'Sütlü', 'assets/images/magnolia.jpg'],
    ['Trileçe', 'Üç süt', 70, 'Tatlılar', 'Sütlü', 'assets/images/trilece.jpg'],

    // TATLILAR - ÇİKOLATALI
    ['Çikolatalı Pasta', 'Yoğun çikolata', 150, 'Tatlılar', 'Çikolatalı', 'assets/images/cikolata_pasta.jpg'],
    ['Brownie', 'Cevizli', 55, 'Tatlılar', 'Çikolatalı', 'assets/images/brownie.jpg'],
    ['Çikolatalı Sufle', 'Sıcak', 85, 'Tatlılar', 'Çikolatalı', 'assets/images/cikolata_sufle.jpg'],
    ['Tiramisu', 'İtalyan', 90, 'Tatlılar', 'Çikolatalı', 'assets/images/tiramisu.jpg'],
    ['Çikolatalı Mousse', 'Hafif', 65, 'Tatlılar', 'Çikolatalı', 'assets/images/cikolata_mousse.jpg'],
    ['Cookie', 'Parçalı', 35, 'Tatlılar', 'Çikolatalı', 'assets/images/cookie.jpg'],
    ['Cheesecake', 'New York', 95, 'Tatlılar', 'Çikolatalı', 'assets/images/cheesecake.jpg'],
    ['Eclair', 'Fransız', 60, 'Tatlılar', 'Çikolatalı', 'assets/images/eclair.jpg'],
    ['Çikolatalı Tart', 'Bitter', 75, 'Tatlılar', 'Çikolatalı', 'assets/images/cikolata_tart.jpg'],
    ['Lava Kek', 'İçi akan', 80, 'Tatlılar', 'Çikolatalı', 'assets/images/lava_kek.jpg'],

    // TUZLULAR
    ['Peynirli Poğaça', 'Taze', 25, 'Tuzlular', '', 'assets/images/peynirli_pogaca.jpg'],
    ['Zeytinli Poğaça', 'Zeytin dolgulu', 25, 'Tuzlular', '', 'assets/images/zeytinli_pogaca.jpg'],
    ['Patatesli Poğaça', 'Patates', 25, 'Tuzlular', '', 'assets/images/patatesli_pogaca.jpg'],
    ['Simit', 'Gevrek', 15, 'Tuzlular', '', 'assets/images/simit.jpg'],
    ['Açma', 'Yumuşak', 18, 'Tuzlular', '', 'assets/images/acma.jpg'],
    ['Çatal', 'Geleneksel', 20, 'Tuzlular', '', 'assets/images/catal.jpg'],
    ['Peynirli Pide', 'Kaşarlı', 75, 'Tuzlular', '', 'assets/images/peynirli_pide.jpg'],
    ['Lahmacun', 'İnce', 45, 'Tuzlular', '', 'assets/images/lahmacun.jpg'],
    ['Kıymalı Pide', 'Kapalı', 85, 'Tuzlular', '', 'assets/images/kiymali_pide.jpg'],
    ['Kaşarlı Tost', 'Çift kaşar', 55, 'Tuzlular', '', 'assets/images/tost.jpg'],

    // BÖREKLER
    ['Su Böreği', 'El açması', 90, 'Börekler', '', 'assets/images/su_boregi.jpg'],
    ['Kol Böreği', 'Peynirli', 80, 'Börekler', '', 'assets/images/kol_boregi.jpg'],
    ['Sigara Böreği', 'Çıtır', 45, 'Börekler', '', 'assets/images/sigara_boregi.jpg'],
    ['Tepsi Böreği', 'Ispanaklı', 85, 'Börekler', '', 'assets/images/tepsi_boregi.jpg'],
    ['Gül Böreği', 'Patatesli', 70, 'Börekler', '', 'assets/images/gul_boregi.jpg'],
    ['Muska Böreği', 'Kıymalı', 65, 'Börekler', '', 'assets/images/muska_boregi.jpg'],
    ['Laz Böreği', 'Tatlı', 75, 'Börekler', '', 'assets/images/laz_boregi.jpg'],
    ['Puf Böreği', 'Kabartmalı', 55, 'Börekler', '', 'assets/images/puf_boregi.jpg'],
    ['Talaş Böreği', 'Tavuklu', 95, 'Börekler', '', 'assets/images/talas_boregi.jpg'],
    ['Çarşaf Böreği', 'İnce yufka', 80, 'Börekler', '', 'assets/images/carsaf_boregi.png'],

    // İÇECEKLER - SICAK
    ['Türk Kahvesi', 'Orta şeker', 30, 'İçecekler', 'Sıcak', 'assets/images/turk_kahvesi.jpg'],
    ['Filtre Kahve', 'Taze', 35, 'İçecekler', 'Sıcak', 'assets/images/filtre_kahve.jpg'],
    ['Latte', 'Kremalı', 45, 'İçecekler', 'Sıcak', 'assets/images/latte.jpg'],
    ['Cappuccino', 'Köpüklü', 45, 'İçecekler', 'Sıcak', 'assets/images/cappuccino.jpg'],
    ['Americano', 'Güçlü', 35, 'İçecekler', 'Sıcak', 'assets/images/americano.jpg'],
    ['Sıcak Çikolata', 'Gerçek çikolata', 40, 'İçecekler', 'Sıcak', 'assets/images/sicak_cikolata.jpg'],
    ['Salep', 'Tarçınlı', 35, 'İçecekler', 'Sıcak', 'assets/images/salep.jpg'],
    ['Çay', 'Demli', 15, 'İçecekler', 'Sıcak', 'assets/images/cay.jpg'],
    ['Bitki Çayı', 'Karışık', 25, 'İçecekler', 'Sıcak', 'assets/images/bitki_cayi.jpg'],
    ['Mocha', 'Çikolatalı kahve', 50, 'İçecekler', 'Sıcak', 'assets/images/mocha.jpg'],

    // İÇECEKLER - SOĞUK
    ['Limonata', 'Taze sıkılmış', 30, 'İçecekler', 'Soğuk', 'assets/images/limonata.jpg'],
    ['Ice Latte', 'Buzlu', 50, 'İçecekler', 'Soğuk', 'assets/images/ice_latte.jpg'],
    ['Frappe', 'Buzlu kahve', 45, 'İçecekler', 'Soğuk', 'assets/images/frappe.jpg'],
    ['Milkshake', 'Çikolatalı', 55, 'İçecekler', 'Soğuk', 'assets/images/milkshake.jpg'],
    ['Smoothie', 'Meyveli', 50, 'İçecekler', 'Soğuk', 'assets/images/smoothie.jpg'],
    ['Ayran', 'Köpüklü', 15, 'İçecekler', 'Soğuk', 'assets/images/ayran.jpg'],
    ['Meyve Suyu', 'Portakal', 35, 'İçecekler', 'Soğuk', 'assets/images/meyve_suyu.jpg'],
    ['Şalgam', 'Acılı', 20, 'İçecekler', 'Soğuk', 'assets/images/salgam.jpg'],
    ['Ice Tea', 'Şeftalili', 25, 'İçecekler', 'Soğuk', 'assets/images/ice_tea.jpg'],
    ['Mojito', 'Naneli', 40, 'İçecekler', 'Soğuk', 'assets/images/mojito.jpg'],
];

$stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, subcategory, image_url) VALUES (?, ?, ?, ?, ?, ?)");

$count = 0;
foreach ($products as $p) {
    $stmt->execute($p);
    $count++;
    echo "<li style='color:green;'>✅ {$p[0]} eklendi</li>";
}

echo "<br><h2 style='color:green;'>🎉 $count ürün gerçek görselleriyle eklendi!</h2>";
echo "<p><strong>Artık her ürünün kendi yemek fotoğrafı var!</strong></p>";
echo "<br><a href='menu.php' style='background:#764ba2; color:white; padding:15px 30px; text-decoration:none; border-radius:10px; font-size:1.2rem;'>Menüyü Gör 🍰</a>";
?>