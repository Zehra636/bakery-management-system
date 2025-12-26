<?php
session_start();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $size = $_POST['size'];
    $text = cleanInput($_POST['cake_text']);
    $has_flower = isset($_POST['has_flower']) ? 1 : 0;

    // Fiyat Hesapla
    $price = 300;
    if ($size == '6')
        $price += 50;
    if ($size == '8')
        $price += 100;
    if ($has_flower)
        $price += 150;

    // Resim Yükleme
    $image_path = '';
    if (isset($_FILES['cake_image']) && $_FILES['cake_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $target_file = $target_dir . time() . "_" . basename($_FILES["cake_image"]["name"]);
        move_uploaded_file($_FILES["cake_image"]["tmp_name"], $target_file);
        $image_path = $target_file;
    }

    // Sepete özel ürün olarak ekle
    // Standart sepet yapımız product_id -> quantity şeklindeydi.
    // Özel pastayı session'da ayrı bir dizide tutalım: custom_cart

    $custom_item = [
        'type' => 'custom_cake',
        'image_path' => $image_path,
        'size' => $size,
        'has_flower' => $has_flower,
        'text' => $text,
        'price' => $price,
        'name' => 'Özel Tasarım Pasta (' . $size . ' Kişilik)'
    ];

    if (!isset($_SESSION['custom_cart'])) {
        $_SESSION['custom_cart'] = [];
    }
    $_SESSION['custom_cart'][] = $custom_item;

    redirect('cart.php');
}
?>