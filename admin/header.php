<?php
session_start();
// Path düzeltmesi için kök dizine göre ayarla
require_once '../includes/functions.php';

if (!isAdmin()) {
    die("Erişim reddedildi. Sadece yöneticiler girebilir.");
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli - Pasta Dükkanı</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-nav {
            background-color: var(--dark-bg);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <nav class="admin-nav">
        <div style="font-size: 1.5rem; font-weight: bold;">Yönetici Paneli</div>
        <div>
            <a href="../index.php" target="_blank">Siteyi Görüntüle</a>
            <a href="../logout.php">Çıkış Yap</a>
        </div>
    </nav>

    <div class="container">