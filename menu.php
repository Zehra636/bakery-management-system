<?php
require_once 'includes/header.php';

// Kullanıcının alerjen bilgisi
$userAlerjenler = isset($_SESSION['alerjenler']) ? explode(',', $_SESSION['alerjenler']) : [];

// Alerjen içeren ürün anahtar kelimeleri
$alerjenUrunler = [
    'gluten' => ['börek', 'poğaça', 'simit', 'pide', 'lahmacun', 'pasta', 'kek', 'brownie', 'cookie', 'eclair', 'tart', 'kurabiye', 'açma', 'çatal'],
    'sut' => ['sütlaç', 'kazandibi', 'muhallebi', 'keşkül', 'profiterol', 'panna', 'magnolia', 'trileçe', 'latte', 'cappuccino', 'cheesecake', 'tavuk göğsü', 'güllaç'],
    'fistik' => ['baklava', 'şöbiyet', 'brownie', 'keşkül', 'cookie', 'vezir'],
    'yumurta' => ['pasta', 'kek', 'börek', 'poğaça', 'sufle', 'eclair', 'cookie', 'brownie'],
    'cikolata' => ['çikolata', 'brownie', 'tiramisu', 'mousse', 'cookie', 'eclair', 'mocha', 'profiterol', 'lava']
];

// Ürünün kullanıcının alerjenlerini içerip içermediğini kontrol eden fonksiyon
function urunAlerjenliMi($urunAdi, $userAlerjenler, $alerjenUrunler)
{
    $urunAdiLower = mb_strtolower($urunAdi, 'UTF-8');
    foreach ($userAlerjenler as $alerjen) {
        $alerjen = trim($alerjen);
        if (isset($alerjenUrunler[$alerjen])) {
            foreach ($alerjenUrunler[$alerjen] as $kelime) {
                if (mb_strpos($urunAdiLower, $kelime) !== false) {
                    return true;
                }
            }
        }
    }
    return false;
}

// Filtreleme Parametreleri
$cat = isset($_GET['cat']) ? $_GET['cat'] : null;
$subcat = isset($_GET['sub']) ? $_GET['sub'] : null;

// SQL Sorgusunu Hazırla
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($cat) {
    $sql .= " AND category = ?";
    $params[] = $cat;
}
if ($subcat) {
    $sql .= " AND subcategory = ?";
    $params[] = $subcat;
}

$sql .= " ORDER BY category, subcategory, name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<style>
    .menu-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
        margin-bottom: 30px;
        border-radius: 0 0 30px 30px;
    }

    .menu-hero h1 {
        font-size: 2.5rem;
        margin: 0;
    }

    .menu-layout {
        display: flex;
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .sidebar {
        flex: 0 0 280px;
        background: white;
        padding: 25px;
        border-radius: 20px;
        height: fit-content;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 20px;
    }

    .sidebar h3 {
        color: #333;
        margin-bottom: 20px;
        font-size: 1.3rem;
    }

    .category-group {
        margin-bottom: 20px;
    }

    .category-title {
        display: block;
        padding: 12px 15px;
        background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);
        color: #333;
        text-decoration: none;
        border-radius: 10px;
        font-weight: bold;
        margin-bottom: 5px;
        transition: all 0.3s;
    }

    .category-title:hover,
    .category-title.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .subcategory {
        display: block;
        padding: 8px 15px 8px 30px;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .subcategory:hover,
    .subcategory.active {
        color: #764ba2;
        font-weight: bold;
    }

    .products-grid {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .product-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);
    }

    .product-info {
        padding: 20px;
        text-align: center;
    }

    .product-info h3 {
        color: #333;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    .product-info p {
        color: #888;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .price {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .add-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .add-btn:hover {
        transform: scale(1.02);
    }

    .category-badge {
        background: #764ba222;
        color: #764ba2;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        margin-bottom: 10px;
        display: inline-block;
    }

    .no-products {
        grid-column: 1/-1;
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 20px;
    }

    .no-products .emoji {
        font-size: 60px;
    }
</style>

<div class="menu-hero">
    <div style="max-width: 1200px; margin: 0 auto; position: relative;">
        <!-- Kalite Onay Rozeti - Sağ Üst Köşe -->
        <img src="assets/images/kalite_onay.jpg" alt="Kalite Onaylı"
            style="position: absolute; top: -30px; right: 10px; width: 160px; height: 160px; border-radius: 50%; box-shadow: 0 6px 25px rgba(255,215,0,0.5); border: 4px solid rgba(255,255,255,0.3);"
            title="Kalite Onaylı - Test Edilmiştir">

        <h1>🍰 Lezzetli Menümüz</h1>
        <p style="opacity: 0.9; margin-top: 10px;">Taze ve lezzetli ürünlerimizi keşfedin!</p>
    </div>
</div>

<div class="menu-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h3><i class="fas fa-filter"></i> Kategoriler</h3>

        <a href="menu.php" class="category-title <?php echo !$cat ? 'active' : ''; ?>">
            <i class="fas fa-th"></i> Tümü
        </a>

        <div class="category-group">
            <a href="?cat=Tatlılar"
                class="category-title <?php echo $cat == 'Tatlılar' && !$subcat ? 'active' : ''; ?>">
                🍯 Tatlılar
            </a>
            <a href="?cat=Tatlılar&sub=Şerbetli"
                class="subcategory <?php echo $subcat == 'Şerbetli' ? 'active' : ''; ?>">- Şerbetli</a>
            <a href="?cat=Tatlılar&sub=Sütlü" class="subcategory <?php echo $subcat == 'Sütlü' ? 'active' : ''; ?>">-
                Sütlü</a>
            <a href="?cat=Tatlılar&sub=Çikolatalı"
                class="subcategory <?php echo $subcat == 'Çikolatalı' ? 'active' : ''; ?>">- Çikolatalı</a>
        </div>

        <a href="?cat=Tuzlular" class="category-title <?php echo $cat == 'Tuzlular' ? 'active' : ''; ?>">
            🥨 Tuzlular
        </a>

        <a href="?cat=Börekler" class="category-title <?php echo $cat == 'Börekler' ? 'active' : ''; ?>">
            🥧 Börekler
        </a>

        <div class="category-group">
            <a href="?cat=İçecekler"
                class="category-title <?php echo $cat == 'İçecekler' && !$subcat ? 'active' : ''; ?>">
                ☕ İçecekler
            </a>
            <a href="?cat=İçecekler&sub=Sıcak" class="subcategory <?php echo $subcat == 'Sıcak' ? 'active' : ''; ?>">-
                Sıcak</a>
            <a href="?cat=İçecekler&sub=Soğuk" class="subcategory <?php echo $subcat == 'Soğuk' ? 'active' : ''; ?>">-
                Soğuk</a>
        </div>
    </aside>

    <!-- Products -->
    <main class="products-grid">
        <?php if (empty($products)): ?>
            <div class="no-products">
                <div class="emoji">🔍</div>
                <h3>Ürün Bulunamadı</h3>
                <p style="color:#888;">Bu kategoride henüz ürün yok.</p>
                <a href="add_products.php"
                    style="display:inline-block; margin-top:20px; padding:15px 30px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; text-decoration:none; border-radius:30px;">Ürünleri
                    Yükle</a>
            </div>
        <?php else: ?>
            <?php foreach ($products as $p):
                $alerjenVar = urunAlerjenliMi($p['name'], $userAlerjenler, $alerjenUrunler);
                ?>
                <div class="product-card" <?php if ($alerjenVar): ?>style="border: 2px solid #ff6b6b;" <?php endif; ?>>
                    <?php if ($alerjenVar): ?>
                        <div
                            style="position: absolute; top: 10px; right: 10px; background: #ff6b6b; color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 10px rgba(255,107,107,0.4);">
                            ⚠️ Alerjen İçerir
                        </div>
                    <?php endif; ?>
                    <img src="<?php echo $p['image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($p['name']); ?>"
                        alt="<?php echo $p['name']; ?>" class="product-img"
                        onerror="this.src='https://via.placeholder.com/300x200?text=<?php echo urlencode($p['name']); ?>'">
                    <div class="product-info">
                        <span
                            class="category-badge"><?php echo $p['category']; ?><?php echo $p['subcategory'] ? ' - ' . $p['subcategory'] : ''; ?></span>
                        <h3><?php echo htmlspecialchars($p['name']); ?>         <?php if ($alerjenVar): ?><span
                                    style="color: #ff6b6b;">⚠️</span><?php endif; ?></h3>
                        <p><?php echo htmlspecialchars($p['description']); ?></p>
                        <div class="price"><?php echo number_format($p['price'], 2); ?> TL</div>

                        <form action="cart_action.php" method="POST">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="add-btn">
                                <i class="fas fa-cart-plus"></i> Sepete Ekle
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>