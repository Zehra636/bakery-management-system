<?php
require_once 'includes/header.php';

if (!isLoggedIn())
    redirect('index.php');
if (empty($_SESSION['cart']) && empty($_SESSION['custom_cart']))
    redirect('menu.php');

// Toplam Hesapla
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $p) {
        $total += $p['price'] * $_SESSION['cart'][$p['id']];
    }
}
if (!empty($_SESSION['custom_cart'])) {
    foreach ($_SESSION['custom_cart'] as $item) {
        $total += $item['price'];
    }
}

// 81 İl ve Meşhur Özellikleri
$iller = [
    'Adana' => ['Kebap', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800'],
    'Adıyaman' => ['Nemrut Dağı', 'https://images.unsplash.com/photo-1590059390047-f5e617e70671?w=800'],
    'Afyonkarahisar' => ['Kaymak', 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800'],
    'Ağrı' => ['Ağrı Dağı', 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800'],
    'Amasya' => ['Elma', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=800'],
    'Ankara' => ['Kızılay', 'https://images.unsplash.com/photo-1589824783299-e8ada5c2c9e4?w=800'],
    'Antalya' => ['Sahil', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800'],
    'Artvin' => ['Doğa', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800'],
    'Aydın' => ['İncir', 'https://images.unsplash.com/photo-1601379760883-1bb497c558f8?w=800'],
    'Balıkesir' => ['Zeytin', 'https://images.unsplash.com/photo-1515516969-d4008cc6241a?w=800'],
    'Bilecik' => ['Tarih', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Bingöl' => ['Dağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Bitlis' => ['Van Gölü', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800'],
    'Bolu' => ['Orman', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Burdur' => ['Göl', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800'],
    'Bursa' => ['İskender', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800'],
    'Çanakkale' => ['Şehitlik', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Çankırı' => ['Tuz Mağarası', 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=800'],
    'Çorum' => ['Leblebi', 'https://images.unsplash.com/photo-1599599810769-bcde5a160d32?w=800'],
    'Denizli' => ['Pamukkale', 'https://images.unsplash.com/photo-1570939274717-7eda259b50ed?w=800'],
    'Diyarbakır' => ['Surlar', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Edirne' => ['Selimiye', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Elazığ' => ['Harput Kalesi', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800'],
    'Erzincan' => ['Dağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Erzurum' => ['Cağ Kebabı', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800'],
    'Eskişehir' => ['Porsuk', 'https://images.unsplash.com/photo-1504701954957-2010ec3bcec1?w=800'],
    'Gaziantep' => ['Baklava', 'https://images.unsplash.com/photo-1519676867240-f03562e64548?w=800'],
    'Giresun' => ['Fındık', 'https://images.unsplash.com/photo-1574856344991-aaa31b6f4ce3?w=800'],
    'Gümüşhane' => ['Pestil', 'https://images.unsplash.com/photo-1574856344991-aaa31b6f4ce3?w=800'],
    'Hakkari' => ['Dağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Hatay' => ['Künefe', 'https://images.unsplash.com/photo-1579954115545-a95591f28bfc?w=800'],
    'Isparta' => ['Gül', 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=800'],
    'Mersin' => ['Limon', 'https://images.unsplash.com/photo-1590502593747-42a996133562?w=800'],
    'İstanbul' => ['Boğaz', 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?w=800'],
    'İzmir' => ['Saat Kulesi', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800'],
    'Kars' => ['Ani Harabeleri', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Kastamonu' => ['Orman', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Kayseri' => ['Mantı', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800'],
    'Kırklareli' => ['Orman', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Kırşehir' => ['Bağlama', 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800'],
    'Kocaeli' => ['Sanayi', 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800'],
    'Konya' => ['Mevlana', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Kütahya' => ['Çini', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'],
    'Malatya' => ['Kayısı Diyarı', 'assets/images/malatya.png'],
    'Manisa' => ['Üzüm', 'https://images.unsplash.com/photo-1596363505729-4190a9506133?w=800'],
    'Kahramanmaraş' => ['Dondurma', 'https://images.unsplash.com/photo-1501443762994-82bd5dace89a?w=800'],
    'Mardin' => ['Taş Evler', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Muğla' => ['Mavi Tur', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800'],
    'Muş' => ['Dağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Nevşehir' => ['Kapadokya', 'https://images.unsplash.com/photo-1641128324972-af3212f0f6bd?w=800'],
    'Niğde' => ['Aladağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Ordu' => ['Fındık', 'https://images.unsplash.com/photo-1574856344991-aaa31b6f4ce3?w=800'],
    'Rize' => ['Çay', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800'],
    'Sakarya' => ['Doğa', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800'],
    'Samsun' => ['Bandırma', 'https://images.unsplash.com/photo-1504701954957-2010ec3bcec1?w=800'],
    'Siirt' => ['Büryan', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800'],
    'Sinop' => ['Sahil', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800'],
    'Sivas' => ['Kangal', 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800'],
    'Tekirdağ' => ['Köfte', 'https://images.unsplash.com/photo-1529042410759-befb1204b468?w=800'],
    'Tokat' => ['Kebap', 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800'],
    'Trabzon' => ['Karadeniz', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800'],
    'Tunceli' => ['Munzur', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800'],
    'Şanlıurfa' => ['Balıklı Göl', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800'],
    'Uşak' => ['Halı', 'https://images.unsplash.com/photo-1531835551805-16d864c8d311?w=800'],
    'Van' => ['Kahvaltı', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=800'],
    'Yozgat' => ['Çamlık', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Zonguldak' => ['Maden', 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800'],
    'Aksaray' => ['Ihlara', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Bayburt' => ['Kale', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Karaman' => ['Elma', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=800'],
    'Kırıkkale' => ['Sanayi', 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800'],
    'Batman' => ['Hasankeyf', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Şırnak' => ['Dağlar', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'],
    'Bartın' => ['Orman', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Ardahan' => ['Kars Kalesi', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Iğdır' => ['Ağrı Dağı', 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800'],
    'Yalova' => ['Termal', 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=800'],
    'Karabük' => ['Safranbolu', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Kilis' => ['Zeytinyağı', 'https://images.unsplash.com/photo-1515516969-d4008cc6241a?w=800'],
    'Osmaniye' => ['Kaleler', 'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800'],
    'Düzce' => ['Orman', 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800'],
    'Fırat Üniversitesi' => ['Teknoloji Fakültesi', 'assets/images/firat_uni.png']
];

$iller_json = json_encode($iller);
?>

<style>
    body { background: transparent !important; }
    .container { background: transparent !important; box-shadow: none !important; }
    
    #cityBackground {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0.85;
        z-index: -1;
        transition: all 0.5s ease;
        filter: brightness(0.7);
    }

    .city-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        margin-top: 10px;
        display: none;
        animation: fadeIn 0.5s;
    }
    
    .checkout-form {
        background: rgba(255,255,255,0.95);
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        backdrop-filter: blur(10px);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<div id="cityBackground" style="position:fixed; top:0; left:0; width:100%; height:100%; background-image:url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200'); background-size:cover; background-position:center; z-index:-1;"></div>

<div style="max-width: 600px; margin: 0 auto; position: relative; z-index: 1; padding-top: 20px;">
    <h2 style="color: white; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">Ödeme ve Teslimat</h2>
    <div class="checkout-form">
        <form action="complete_order.php" method="POST">

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> İl Seçiniz</label>
                <select name="city" id="citySelect" class="form-control" onchange="changeCity()" required>
                    <option value="">-- İl Seçin --</option>
                    <?php foreach ($iller as $il => $data): ?>
                        <option value="<?php echo $il; ?>"><?php echo $il; ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="cityInfo" class="city-info"></div>
            </div>

            <div class="form-group">
                <label>Teslimat Adresi</label>
                <textarea name="address" class="form-control" rows="3" required
                    placeholder="Açık adresinizi giriniz..."></textarea>
            </div>

            <div class="form-group">
                <label>Ödeme Yöntemi</label>
                <select name="payment_method" class="form-control">
                    <option value="cc">Kredi Kartı</option>
                    <option value="cash">Kapıda Ödeme</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kargo ve Teslimat Seçeneği</label>
                <div style="display:flex; flex-direction:column; gap:10px; margin-top:10px;">
                    <label
                        style="background:#e8f5e9; padding:15px; border-radius:10px; cursor:pointer; border:2px solid #4caf50;">
                        <input type="radio" name="shipping" value="-10" onchange="updateTotal(-10)">
                        <b><i class="fas fa-store"></i> Gel Al (Götür)</b> (-10 TL) - Mağazadan alın
                    </label>
                    <label style="background:#f9f9f9; padding:15px; border-radius:10px; cursor:pointer;">
                        <input type="radio" name="shipping" value="0" checked onchange="updateTotal(0)">
                        <b><i class="fas fa-truck"></i> Standart</b> (Ücretsiz) - 45-60 dk
                    </label>
                    <label style="background:#f9f9f9; padding:15px; border-radius:10px; cursor:pointer;">
                        <input type="radio" name="shipping" value="20" onchange="updateTotal(20)">
                        <b><i class="fas fa-motorcycle"></i> Hızlı</b> (+20 TL) - 30 dk
                    </label>
                    <label
                        style="background:#fff0f0; padding:15px; border-radius:10px; cursor:pointer; border:2px solid #e91e63;">
                        <input type="radio" name="shipping" value="50" onchange="updateTotal(50)">
                        <b><i class="fas fa-rocket"></i> VIP</b> (+50 TL) - 15 dk
                    </label>
                </div>
            </div>

            <hr style="margin:20px 0; border-top:1px solid #eee;">

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Toplam: <span id="final_total"><?php echo number_format($total, 2); ?></span> TL</h3>
                <button type="submit" class="btn btn-primary">Siparişi Tamamla</button>
            </div>
        </form>
    </div>
</div>

<script>
    const iller = <?php echo $iller_json; ?>;
    let rawTotal = <?php echo $total; ?>;

    function updateTotal(shippingCost) {
        let final = rawTotal + parseFloat(shippingCost);
        document.getElementById('final_total').innerText = final.toFixed(2);
    }

    function changeCity() {
        const select = document.getElementById('citySelect');
        const city = select.value;
        const bg = document.getElementById('cityBackground');
        const info = document.getElementById('cityInfo');

        if (city && iller[city]) {
            bg.style.backgroundImage = `url('${iller[city][1]}')`;
            info.style.display = 'block';
            info.innerHTML = `<i class="fas fa-map-marker-alt"></i> <strong>${city}</strong> - ${iller[city][0]}`;
        } else {
            bg.style.backgroundImage = "url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=1200')";
            info.style.display = 'none';
        }
    }
</script>