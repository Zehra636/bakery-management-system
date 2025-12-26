<?php
session_start();
require_once 'includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    redirect('index.php');
}
if (empty($_SESSION['cart']) && empty($_SESSION['custom_cart'])) {
    redirect('menu.php');
}

$user_id = $_SESSION['user_id'];
$address = $_POST['address'];
$shipping_cost = floatval($_POST['shipping']);
$total_products = 0;

// Toplam Hesapla
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $p) {
        $total_products += $p['price'] * $_SESSION['cart'][$p['id']];
    }
}

if (!empty($_SESSION['custom_cart'])) {
    foreach ($_SESSION['custom_cart'] as $item) {
        $total_products += $item['price'];
    }
}

$final_total = $total_products + $shipping_cost;

try {
    $pdo->beginTransaction();

    // 1. Siparişi Oluştur
    $status = 'preparing'; // Takip için varsayılan 'Hazırlanıyor' olsun
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $final_total, $address, $status]);
    $order_id = $pdo->lastInsertId();

    // 2. Standart Ürünleri Ekle
    if (!empty($_SESSION['cart'])) {
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $stmt_item->execute([$order_id, $p['id'], $qty, $p['price']]);
        }
    }

    // 3. Özel Pastaları Ekle (custom_cake_orders tablosuna)
    if (!empty($_SESSION['custom_cart'])) {
        $stmt_cake = $pdo->prepare("INSERT INTO custom_cake_orders (order_id, image_path, size, has_flower, cake_text, price) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($_SESSION['custom_cart'] as $c) {
            $stmt_cake->execute([$order_id, $c['image_path'], $c['size'], $c['has_flower'], $c['text'], $c['price']]);
        }
    }

    $pdo->commit();

    // Sipariş onay e-postası gönder
    require_once 'includes/email.php';

    // Kullanıcının e-postasını al
    $user_stmt = $pdo->prepare("SELECT email, username FROM users WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $user_data = $user_stmt->fetch();

    if ($user_data) {
        // Sipariş detaylarını hazırla
        $order_items = [];
        if (!empty($products)) {
            foreach ($products as $p) {
                $qty = $_SESSION['cart'][$p['id']] ?? 1;
                $order_items[] = [
                    'name' => $p['name'],
                    'qty' => $qty,
                    'subtotal' => $p['price'] * $qty
                ];
            }
        }

        sendOrderConfirmationEmail($user_data['email'], [
            'order_id' => $order_id,
            'username' => $user_data['username'],
            'items' => $order_items,
            'total' => $final_total,
            'address' => $address
        ]);
    }

    // Temizlik
    unset($_SESSION['cart']);
    unset($_SESSION['custom_cart']);

    // Kutlama sayfası göster
    ?>
    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <meta charset="UTF-8">
        <title>Siparişiniz Alındı! 🎉</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Segoe UI', sans-serif;
                overflow: hidden;
            }

            .celebration-box {
                background: white;
                padding: 50px;
                border-radius: 30px;
                text-align: center;
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
                animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                position: relative;
                z-index: 10;
            }

            @keyframes bounceIn {
                0% {
                    transform: scale(0.3);
                    opacity: 0;
                }

                50% {
                    transform: scale(1.05);
                }

                70% {
                    transform: scale(0.9);
                }

                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .emoji {
                font-size: 80px;
                margin-bottom: 20px;
                animation: bounce 1s infinite;
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

            h1 {
                color: #333;
                margin-bottom: 15px;
                font-size: 2.5rem;
            }

            .message {
                color: #666;
                font-size: 1.2rem;
                margin-bottom: 30px;
                line-height: 1.6;
            }

            .order-id {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 30px;
                border-radius: 50px;
                display: inline-block;
                font-size: 1.3rem;
                margin-bottom: 20px;
            }

            .btn {
                display: inline-block;
                padding: 15px 40px;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                color: white;
                text-decoration: none;
                border-radius: 50px;
                font-size: 1.1rem;
                font-weight: bold;
                transition: transform 0.3s;
            }

            .btn:hover {
                transform: scale(1.05);
            }

            /* Havai Fişek */
            .firework {
                position: fixed;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                animation: explode 1s ease-out forwards;
            }

            @keyframes explode {
                0% {
                    transform: scale(1);
                    opacity: 1;
                }

                100% {
                    transform: scale(50);
                    opacity: 0;
                }
            }

            .particle {
                position: fixed;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                animation: fall 2s ease-out forwards;
            }

            @keyframes fall {
                0% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                100% {
                    opacity: 0;
                    transform: translateY(200px) scale(0);
                }
            }

            .confetti {
                position: fixed;
                width: 10px;
                height: 20px;
                animation: confettiFall 3s linear forwards;
            }

            @keyframes confettiFall {
                0% {
                    transform: translateY(-100px) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translateY(100vh) rotate(720deg);
                    opacity: 0;
                }
            }
        </style>
    </head>

    <body>
        <div class="celebration-box">
            <div class="emoji">🎂🎉</div>
            <h1>Tebrikler, Tatlı Canım!</h1>
            <div class="order-id">Sipariş #<?php echo $order_id; ?></div>
            <p class="message">
                Siparişiniz mutfağımıza uçtu! 🚀<br>
                Şeflerimiz şu an tatlı bir telaş içinde...<br>
                <strong>Birazdan kapınızda olacak!</strong> 🍰
            </p>
            <a href="siparis_takip.php" class="btn"><i class="fas fa-truck"></i> Siparişimi Takip Et</a>
        </div>

        <script>
            // Havai fişek efekti
            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#ff9900', '#ff0099'];

            function createFirework() {
                const x = Math.random() * window.innerWidth;
                const y = Math.random() * window.innerHeight * 0.5;

                for (let i = 0; i < 30; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = x + 'px';
                    particle.style.top = y + 'px';
                    particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.transform = `translate(${(Math.random() - 0.5) * 200}px, ${(Math.random() - 0.5) * 200}px)`;
                    document.body.appendChild(particle);

                    setTimeout(() => particle.remove(), 2000);
                }
            }

            // Konfeti
            function createConfetti() {
                for (let i = 0; i < 50; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.className = 'confetti';
                        confetti.style.left = Math.random() * window.innerWidth + 'px';
                        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.animationDuration = (2 + Math.random() * 2) + 's';
                        document.body.appendChild(confetti);

                        setTimeout(() => confetti.remove(), 4000);
                    }, i * 50);
                }
            }

            // Başlat
            createConfetti();
            setInterval(createFirework, 500);
            setTimeout(() => createFirework(), 100);
            setTimeout(() => createFirework(), 300);
        </script>
    </body>

    </html>
    <?php

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Sipariş hatası: " . $e->getMessage();
}
?>