<?php
$host = 'localhost';
$dbname = 'pastry_shop';
$username = 'root';
$password = ''; // Varsayılan XAMPP şifresi boştur

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Hata Raporlamayı Aç (Geliştirme aşamasında faydalı)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>