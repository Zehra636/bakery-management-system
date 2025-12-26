<?php
require_once 'includes/db_connect.php';

echo "<h1>Tüm Ürün Görselleri Güncelleniyor...</h1>";

// Her ürün için doğru görsel URL'leri
$images = [
    // TATLILAR - ŞERBETLİ
    'Baklava' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/ev-baklavasi-yemekcom.jpg',
    'Şöbiyet' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2022/02/sobiyet-tarifi-yemekcom.jpg',
    'Burma Kadayıf' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/12/burma-kadayif-tarifi.jpg',
    'Tulumba' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/tulumba-yemekcom.jpg',
    'Revani' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/revani-yemekcom.jpg',
    'Şekerpare' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/sekerpare-yemekcom.jpg',
    'Kemalpaşa' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/10/kemalpasa-tatlisi.jpg',
    'Lokma' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/04/lokma-tatlisi-one-cikan.jpg',
    'Vezir Parmağı' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2016/10/vezir-parmagi-tarifi.jpg',
    'Ekmek Kadayıfı' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/12/ekmek-kadayifi-yemekcom.jpg',

    // TATLILAR - SÜTLÜ
    'Sütlaç' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/sutlac-yemekcom.jpg',
    'Kazandibi' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/kazandibi-yemekcom.jpg',
    'Tavuk Göğsü' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/tavuk-gogsu-yemekcom.jpg',
    'Muhallebi' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/muhallebi-one-cikan.jpg',
    'Keşkül' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/10/keskul-yemekcom.jpg',
    'Güllaç' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2022/03/gullac-tarifi-yemekcom.jpg',
    'Profiterol' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/profiterol-yemekcom.jpg',
    'Panna Cotta' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/02/panna-cotta-tarifi.jpg',
    'Magnolia' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/05/magnolia-tarifi-one-cikan.jpg',
    'Trileçe' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/03/trilece-yemekcom.jpg',

    // TATLILAR - ÇİKOLATALI
    'Çikolatalı Pasta' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/cikolatali-pasta-yemekcom.jpg',
    'Brownie' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/07/brownie-yemekcom.jpg',
    'Çikolatalı Sufle' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/09/cikolatali-sufle.jpg',
    'Tiramisu' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/tiramisu-yemekcom.jpg',
    'Çikolatalı Mousse' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/01/cikolatali-mus.jpg',
    'Cookie' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/04/damla-cikolatali-kurabiye-yemekcom.jpg',
    'Çikolatalı Cheesecake' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/09/cheesecake-yemekcom.jpg',
    'Çikolatalı Eclair' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2016/09/ekler-pasta-yemekcom.jpg',
    'Çikolatalı Tart' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/02/cikolatali-tart.jpg',
    'Lava Kek' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/01/lava-kek-yemekcom.jpg',

    // TUZLULAR
    'Peynirli Poğaça' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/pogaca-yemekcom.jpg',
    'Zeytinli Poğaça' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/zeytinli-pogaca.jpg',
    'Patatesli Poğaça' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/02/patatesli-pogaca.jpg',
    'Simit' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/simit-tarifi-yemekcom.jpg',
    'Açma' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/08/acma-yemekcom.jpg',
    'Çatal' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/01/catal-tarifi.jpg',
    'Peynirli Pide' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/04/kasarli-pide-yemekcom.jpg',
    'Lahmacun' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/lahmacun-yemekcom.jpg',
    'Kıymalı Pide' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/08/kiymali-pide-yemekcom.jpg',
    'Kaşarlı Tost' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/05/tost-yemekcom.jpg',

    // BÖREKLER
    'Su Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/su-boregi-yemekcom.jpg',
    'Kol Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/kol-boregi-yemekcom.jpg',
    'Sigara Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/sigara-boregi-yemekcom.jpg',
    'Tepsi Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/06/tepsi-boregi-yemekcom.jpg',
    'Gül Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/09/gul-boregi-yemekcom.jpg',
    'Muska Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2016/02/muska-boregi-yemekcom.jpg',
    'Laz Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/10/laz-boregi-yemekcom.jpg',
    'Puf Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/09/puf-boregi-one-cikan.jpg',
    'Talaş Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/10/talas-boregi-yemekcom.jpg',
    'Çarşaf Böreği' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/10/carsaf-boregi-yemekcom.jpg',

    // İÇECEKLER - SICAK
    'Türk Kahvesi' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/11/turk-kahvesi-yemekcom.jpg',
    'Filtre Kahve' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/10/filtre-kahve-yemekcom.jpg',
    'Latte' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2021/07/latte-tarifi-yemekcom.jpg',
    'Cappuccino' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2021/03/cappuccino-yemekcom.jpg',
    'Americano' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/10/filtre-kahve-yemekcom.jpg',
    'Sıcak Çikolata' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/12/sicak-cikolata-yemekcom.jpg',
    'Salep' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2017/12/salep-yemekcom.jpg',
    'Çay' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/05/cay-demleme-yemekcom.jpg',
    'Bitki Çayı' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/11/yesil-cay-yemekcom.jpg',
    'Mocha' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2021/03/mocha-yemekcom.jpg',

    // İÇECEKLER - SOĞUK
    'Limonata' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/07/limonata-yemekcom.jpg',
    'Ice Latte' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2021/07/ice-latte-yemekcom.jpg',
    'Frappe' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/07/frappe-yemekcom.jpg',
    'Milkshake' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2020/06/cikolatali-milkshake-yemekcom.jpg',
    'Smoothie' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/07/smoothie-yemekcom.jpg',
    'Ayran' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/08/ayran-yemekcom.jpg',
    'Meyve Suyu' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/05/portakal-suyu-yemekcom.jpg',
    'Şalgam' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2019/09/salgam-suyu-yemekcom.jpg',
    'Ice Tea' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2014/07/ice-tea-yemekcom.jpg',
    'Mojito' => 'https://cdn.yemek.com/mnresize/1250/833/uploads/2015/07/mojito-tarifi.jpg',
];

$stmt = $pdo->prepare("UPDATE products SET image_url = ? WHERE name = ?");
$count = 0;

foreach ($images as $name => $url) {
    $stmt->execute([$url, $name]);
    if ($stmt->rowCount() > 0) {
        echo "<li style='color:green;'>✅ $name güncellendi</li>";
        $count++;
    }
}

echo "<br><h2 style='color:green;'>✅ $count ürün görseli güncellendi!</h2>";
echo "<br><a href='menu.php' style='background:#764ba2; color:white; padding:15px 30px; text-decoration:none; border-radius:10px;'>Menüyü Gör</a>";
?>