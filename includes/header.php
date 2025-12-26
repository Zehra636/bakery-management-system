<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
require_once 'security.php';

// Session süresi kontrolü (1 saat)
if (isSessionExpired(3600)) {
    session_destroy();
    header('Location: index.php?error=session_expired');
    exit;
}

// Sepet toplamını hesapla
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasta Dükkanı Otomasyonu</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- Font Awesome (CDN İyileştirmesi) -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <nav class="navbar">
        <a href="menu.php" class="logo"><i class="fas fa-cookie-bite"></i> Lezzet Dünyası</a>

        <div class="nav-links">
            <a href="menu.php">Menü</a>
            <a href="ozel_pasta.php"><i class="fas fa-birthday-cake"></i> Özel Pasta</a>
            <a href="subeler.php"><i class="fas fa-map-marker-alt"></i> Şubeler</a>
            <a href="hakkimizda.php"><i class="fas fa-heart"></i> Hakkımızda</a>
            <?php if (isLoggedIn()): ?>
                <a href="siparis_takip.php">Sipariş Takibi</a>
                <a href="cart.php">Sepetim <span class="badge"><?php echo $cart_count; ?></span></a>
                <a href="logout.php">Çıkış (<?php echo $_SESSION['username']; ?>)</a>
            <?php else: ?>
                <a href="index.php">Giriş Yap</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">