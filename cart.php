<?php
require_once 'includes/header.php';

// Kupon işleme
$kupon_indirim = 0;
$kupon_mesaj = '';
$hediye_kahve = false;

// Kampanyalar
$kampanyalar = [
    'YILBASI2026' => ['tip' => 'yuzde', 'deger' => 15, 'aciklama' => '🎄 Yılbaşı Özel %15 İndirim!'],
    'HOŞGELDIN' => ['tip' => 'yuzde', 'deger' => 10, 'aciklama' => '🎉 Hoşgeldin İndirimi %10!'],
    'TATLI50' => ['tip' => 'sabit', 'deger' => 50, 'aciklama' => '🍰 50 TL İndirim!'],
    'KAHVEHEDIYE' => ['tip' => 'hediye', 'deger' => 'kahve', 'aciklama' => '☕ 1 Tatlıya 1 Kahve Hediye!'],
];

if (isset($_POST['kupon_uygula']) && !empty($_POST['kupon_kodu'])) {
    $kupon = strtoupper(trim($_POST['kupon_kodu']));
    if (isset($kampanyalar[$kupon])) {
        $_SESSION['aktif_kupon'] = $kupon;
    } else {
        $kupon_mesaj = '❌ Geçersiz kupon kodu!';
    }
}

if (isset($_POST['kupon_kaldir'])) {
    unset($_SESSION['aktif_kupon']);
}

$standard_cart_empty = empty($_SESSION['cart']);
$custom_cart_empty = empty($_SESSION['custom_cart']);

if ($standard_cart_empty && $custom_cart_empty) {
    ?>
    <style>
        .empty-cart-container {
            background: linear-gradient(135deg, #fff5f7 0%, #ffe8ec 100%);
            border-radius: 30px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(255, 105, 180, 0.15);
            max-width: 600px;
            margin: 50px auto;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .empty-cart-icon {
            font-size: 100px;
            animation: bounce 2s infinite;
            display: inline-block;
            margin-bottom: 20px;
        }

        .empty-cart-title {
            color: #ff4757;
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff4757 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .empty-cart-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 35px;
        }

        .cute-btn {
            display: inline-block;
            padding: 15px 35px;
            margin: 10px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cute-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .cute-btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .cute-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .sparkle {
            display: inline-block;
            animation: sparkle 1.5s infinite;
        }

        @keyframes sparkle {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.2);
            }
        }
    </style>

    <div class="empty-cart-container">
        <div class="empty-cart-icon">🛒✨</div>
        <h2 class="empty-cart-title">Sepetiniz Boş <span class="sparkle">💫</span></h2>
        <p class="empty-cart-subtitle">
            Lezzetli ürünlerimizi keşfedin ve sepetinizi doldurun! 🍰🎂
        </p>

        <a href="menu.php" class="cute-btn cute-btn-primary">
            <i class="fas fa-utensils"></i> Ürünlere Göz At
        </a>
        <a href="ozel_pasta.php" class="cute-btn cute-btn-secondary">
            <i class="fas fa-birthday-cake"></i> Kendi Pastanı Tasarla
        </a>
    </div>
    <?php
    require_once 'includes/footer.php';
    exit;
}

$total = 0;
$tatli_var = false;
?>

<style>
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .qty-btn-minus {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
    }

    .qty-btn-plus {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .qty-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .qty-value {
        min-width: 35px;
        text-align: center;
        font-weight: bold;
        font-size: 1.1rem;
        color: #333;
    }

    .kupon-box {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8ec 100%);
        padding: 20px;
        border-radius: 15px;
        margin: 20px 0;
    }

    .kupon-input {
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 25px;
        font-size: 1rem;
        width: 200px;
        text-transform: uppercase;
    }

    .kupon-btn {
        padding: 12px 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: bold;
        margin-left: 10px;
        transition: all 0.3s;
    }

    .kupon-btn:hover {
        transform: scale(1.05);
    }

    .kampanya-card {
        display: inline-block;
        background: white;
        padding: 10px 20px;
        border-radius: 15px;
        margin: 5px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .kampanya-card:hover {
        transform: translateY(-3px);
        border-color: #667eea;
    }

    .kampanya-card.aktif {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .indirim-satir {
        color: #10b981;
        font-weight: bold;
    }

    .hediye-satir {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
        text-align: center;
    }
</style>

<h2>🛒 Alışveriş Sepeti</h2>

<!-- Kampanyalar Bölümü -->
<div class="kupon-box">
    <h4 style="margin: 0 0 15px 0; color: #c44569;"><i class="fas fa-gift"></i> 🎄 Yılbaşı Kampanyaları</h4>

    <div style="margin-bottom: 15px;">
        <?php foreach ($kampanyalar as $kod => $detay): ?>
            <div class="kampanya-card <?php echo (isset($_SESSION['aktif_kupon']) && $_SESSION['aktif_kupon'] == $kod) ? 'aktif' : ''; ?>"
                onclick="document.getElementById('kupon_input').value='<?php echo $kod; ?>'">
                <strong><?php echo $kod; ?></strong><br>
                <small><?php echo $detay['aciklama']; ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
        <input type="text" name="kupon_kodu" id="kupon_input" class="kupon-input" placeholder="Kupon Kodu"
            value="<?php echo isset($_SESSION['aktif_kupon']) ? $_SESSION['aktif_kupon'] : ''; ?>">
        <button type="submit" name="kupon_uygula" class="kupon-btn">
            <i class="fas fa-tag"></i> Uygula
        </button>
        <?php if (isset($_SESSION['aktif_kupon'])): ?>
            <button type="submit" name="kupon_kaldir" class="kupon-btn" style="background: #e74c3c;">
                <i class="fas fa-times"></i> Kaldır
            </button>
        <?php endif; ?>
    </form>

    <?php if ($kupon_mesaj): ?>
        <div style="margin-top: 10px; color: #e74c3c;"><?php echo $kupon_mesaj; ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['aktif_kupon'])): ?>
        <div style="margin-top: 10px; color: #10b981; font-weight: bold;">
            ✅ <?php echo $kampanyalar[$_SESSION['aktif_kupon']]['aciklama']; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #eee; text-align:left;">
                <th style="padding:10px;">Ürün</th>
                <th>Detay</th>
                <th>Fiyat</th>
                <th>Adet</th>
                <th>Tutar</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <!-- Standart Ürünler -->
            <?php if (!$standard_cart_empty): ?>
                <?php
                $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
                $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
                $products = $stmt->fetchAll();

                foreach ($products as $p):
                    $qty = $_SESSION['cart'][$p['id']];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;

                    // Tatlı kategorisi kontrolü
                    if ($p['category'] == 'Tatlılar') {
                        $tatli_var = true;
                    }
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding:10px;">
                            <img src="<?php echo $p['image_url']; ?>"
                                style="width:50px; height:50px; object-fit:cover; border-radius:5px; vertical-align:middle; margin-right:10px;">
                            <?php echo htmlspecialchars($p['name']); ?>
                        </td>
                        <td>Standart</td>
                        <td><?php echo number_format($p['price'], 2); ?> TL</td>
                        <td>
                            <div class="qty-controls">
                                <a href="cart_action.php?action=decrease&id=<?php echo $p['id']; ?>"
                                    class="qty-btn qty-btn-minus">−</a>
                                <span class="qty-value"><?php echo $qty; ?></span>
                                <a href="cart_action.php?action=increase&id=<?php echo $p['id']; ?>"
                                    class="qty-btn qty-btn-plus">+</a>
                            </div>
                        </td>
                        <td><strong><?php echo number_format($subtotal, 2); ?> TL</strong></td>
                        <td><a href="cart_action.php?action=remove&id=<?php echo $p['id']; ?>" style="color:var(--danger);"><i
                                    class="fas fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Özel Pastalar -->
            <?php if (!$custom_cart_empty): ?>
                <?php foreach ($_SESSION['custom_cart'] as $key => $item):
                    $total += $item['price'];
                    $tatli_var = true;
                    ?>
                    <tr style="border-bottom: 1px solid #eee; background-color:#fffdf0;">
                        <td style="padding:10px;">
                            <i class="fas fa-birthday-cake" style="color:var(--primary-color); margin-right:10px;"></i>
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td style="font-size:0.9rem;">
                            <?php if ($item['has_flower'])
                                echo '<i class="fas fa-flower" style="color:pink"></i> Çiçekli<br>'; ?>
                            <?php if ($item['text'])
                                echo 'Not: ' . htmlspecialchars($item['text']); ?>
                        </td>
                        <td><?php echo number_format($item['price'], 2); ?> TL</td>
                        <td><span class="qty-value">1</span></td>
                        <td><strong><?php echo number_format($item['price'], 2); ?> TL</strong></td>
                        <td><a href="cart_action.php?action=remove_custom&id=<?php echo $key; ?>"
                                style="color:var(--danger);"><i class="fas fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    // Kupon indirimi hesapla
    $ara_toplam = $total;

    if (isset($_SESSION['aktif_kupon']) && isset($kampanyalar[$_SESSION['aktif_kupon']])) {
        $kupon = $kampanyalar[$_SESSION['aktif_kupon']];

        if ($kupon['tip'] == 'yuzde') {
            $kupon_indirim = $total * ($kupon['deger'] / 100);
        } elseif ($kupon['tip'] == 'sabit') {
            $kupon_indirim = min($kupon['deger'], $total);
        } elseif ($kupon['tip'] == 'hediye' && $tatli_var) {
            $hediye_kahve = true;
        }

        $total -= $kupon_indirim;
    }
    ?>

    <!-- Hediye Kahve Bildirimi -->
    <?php if ($hediye_kahve): ?>
        <div class="hediye-satir">
            <span style="font-size: 2rem;">☕</span><br>
            <strong>Tebrikler! 1 Türk Kahvesi Hediye!</strong><br>
            <small>KAHVEHEDIYE kuponu ile tatlı siparişinize kahve hediye!</small>
        </div>
    <?php endif; ?>

    <div style="text-align:right; margin-top:20px; padding-top: 20px; border-top: 2px dashed #eee;">
        <?php if ($kupon_indirim > 0): ?>
            <div style="color: #888; text-decoration: line-through; font-size: 1rem;">
                Ara Toplam: <?php echo number_format($ara_toplam, 2); ?> TL
            </div>
            <div class="indirim-satir" style="font-size: 1.1rem; margin: 5px 0;">
                🎁 İndirim: -<?php echo number_format($kupon_indirim, 2); ?> TL
            </div>
        <?php endif; ?>

        <h3 style="color: #10b981;">Toplam: <?php echo number_format($total, 2); ?> TL</h3>
        <br>
        <a href="cart_action.php?action=clear" class="btn btn-secondary" style="background:gray;">Sepeti Temizle</a>
        <a href="checkout.php" class="btn btn-primary">Ödemeye Geç <i class="fas fa-arrow-right"></i></a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>