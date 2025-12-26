<?php
session_start();
require_once 'includes/db_connect.php';

// Sepeti başlat
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == 'add' && isset($_POST['product_id'])) {
    $pid = $_POST['product_id'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]++;
    } else {
        $_SESSION['cart'][$pid] = 1;
    }
    header("Location: menu.php"); // Kaldığımız yere dön
} elseif ($action == 'increase' && isset($_GET['id'])) {
    $pid = $_GET['id'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]++;
    }
    header("Location: cart.php");
} elseif ($action == 'decrease' && isset($_GET['id'])) {
    $pid = $_GET['id'];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]--;
        if ($_SESSION['cart'][$pid] <= 0) {
            unset($_SESSION['cart'][$pid]);
        }
    }
    header("Location: cart.php");
} elseif ($action == 'remove' && isset($_GET['id'])) {
    $pid = $_GET['id'];
    unset($_SESSION['cart'][$pid]);
    header("Location: cart.php");
} elseif ($action == 'remove_custom' && isset($_GET['id'])) {
    $key = $_GET['id'];
    unset($_SESSION['custom_cart'][$key]);
    header("Location: cart.php");
} elseif ($action == 'clear') {
    $_SESSION['cart'] = [];
    $_SESSION['custom_cart'] = [];
    unset($_SESSION['aktif_kupon']);
    header("Location: cart.php");
} else {
    header("Location: menu.php");
}
?>