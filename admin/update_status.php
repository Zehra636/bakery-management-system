<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
session_start();

if (!isAdmin())
    die("Erişim yok");

if (isset($_GET['id']) && isset($_GET['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_GET['status'], $_GET['id']]);
}

redirect('dashboard.php');
?>