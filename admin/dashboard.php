<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli - Lezzet Dünyası</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }

        .admin-container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-card .icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-card .label {
            color: #888;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .card h3 {
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .badge-danger {
            background: #ffe0e0;
            color: #c00;
        }

        .badge-success {
            background: #e0ffe0;
            color: #080;
        }

        .profit {
            color: #27ae60;
            font-weight: bold;
        }

        .cost {
            color: #e74c3c;
        }
    </style>
</head>

<body>

    <div class="admin-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> Yönetici Paneli</h1>
        </div>
        <div class="admin-profile">
            <span>Hoşgeldin, Admin</span>
            <img src="../assets/images/admin_profile.png" alt="Admin" onclick="openProfileModal()"
                style="cursor:pointer;">
            <a href="../logout.php" style="color:white; margin-left:20px;"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
        </div>
    </div>

    <!-- Profil Resmi Modal -->
    <div id="profileModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:white; padding:30px; border-radius:20px; text-align:center; max-width:400px;">
            <img src="../assets/images/admin_profile.png"
                style="width:200px; height:200px; border-radius:50%; object-fit:cover; border:5px solid #764ba2; margin-bottom:20px;">
            <h3 style="margin-bottom:20px;">Profil Resmi</h3>
            <div style="display:flex; gap:10px; justify-content:center;">
                <label
                    style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:12px 25px; border-radius:30px; cursor:pointer;">
                    <i class="fas fa-camera"></i> Değiştir
                    <input type="file" id="newProfilePic" style="display:none;" accept="image/*"
                        onchange="changeProfile()">
                </label>
                <button onclick="deleteProfile()"
                    style="background:#e74c3c; color:white; padding:12px 25px; border-radius:30px; border:none; cursor:pointer;">
                    <i class="fas fa-trash"></i> Sil
                </button>
            </div>
            <button onclick="closeProfileModal()"
                style="margin-top:20px; background:#eee; padding:10px 20px; border:none; border-radius:20px; cursor:pointer;">Kapat</button>
        </div>
    </div>

    <script>
        function openProfileModal() {
            document.getElementById('profileModal').style.display = 'flex';
        }
        function closeProfileModal() {
            document.getElementById('profileModal').style.display = 'none';
        }
        function changeProfile() {
            var input = document.getElementById('newProfilePic');
            if (input.files && input.files[0]) {
                var formData = new FormData();
                formData.append('profile_image', input.files[0]);

                fetch('upload_profile.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text()).then(data => {
                    alert('Profil resmi güncellendi!');
                    location.reload();
                });
            }
        }
        function deleteProfile() {
            if (confirm('Profil resmini silmek istediğinize emin misiniz?')) {
                fetch('delete_profile.php').then(() => {
                    alert('Profil resmi silindi!');
                    location.reload();
                });
            }
        }
    </script>

    <div class="admin-container">
        <?php
        // İstatistikleri hesapla
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
        $total_customers = $stmt->fetch()['total'];

        $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
        $total_orders = $stmt->fetch()['total'];

        $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
        $total_sales = $stmt->fetch()['total'] ?? 0;

        // %10 kar
        $total_profit = $total_sales * 0.10;
        $total_cost = $total_sales * 0.90;
        ?>

        <!-- Özet Kartları -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon" style="color:#3498db;"><i class="fas fa-users"></i></div>
                <div class="number"><?php echo $total_customers; ?></div>
                <div class="label">Toplam Müşteri</div>
            </div>
            <div class="stat-card">
                <div class="icon" style="color:#9b59b6;"><i class="fas fa-shopping-bag"></i></div>
                <div class="number"><?php echo $total_orders; ?></div>
                <div class="label">Toplam Sipariş</div>
            </div>
            <div class="stat-card">
                <div class="icon" style="color:#e74c3c;"><i class="fas fa-coins"></i></div>
                <div class="number"><?php echo number_format($total_sales, 2); ?> ₺</div>
                <div class="label">Toplam Satış</div>
            </div>
            <div class="stat-card">
                <div class="icon" style="color:#27ae60;"><i class="fas fa-chart-pie"></i></div>
                <div class="number profit"><?php echo number_format($total_profit, 2); ?> ₺</div>
                <div class="label">Net Kar (%10)</div>
            </div>
        </div>

        <!-- Mali Özet -->
        <div class="card">
            <h3><i class="fas fa-calculator"></i> Mali Özet (Tüm Satışlar)</h3>
            <table>
                <tr>
                    <th>Açıklama</th>
                    <th>Tutar</th>
                </tr>
                <tr>
                    <td>Toplam Satış (Ciro)</td>
                    <td><strong><?php echo number_format($total_sales, 2); ?> ₺</strong></td>
                </tr>
                <tr>
                    <td>Toplam Maliyet (%90)</td>
                    <td class="cost"><?php echo number_format($total_cost, 2); ?> ₺</td>
                </tr>
                <tr>
                    <td>Net Kar (%10)</td>
                    <td class="profit"><?php echo number_format($total_profit, 2); ?> ₺</td>
                </tr>
            </table>
        </div>

        <!-- Müşteri Listesi -->
        <div class="card">
            <h3><i class="fas fa-users"></i> Kayıtlı Müşteriler ve Alerji Bilgileri</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı Adı</th>
                    <th>E-posta</th>
                    <th>Alerji Bilgisi</th>
                    <th>Kayıt Tarihi</th>
                </tr>
                <?php
                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'customer' ORDER BY id DESC");
                $customers = $stmt->fetchAll();
                foreach ($customers as $c):
                    ?>
                    <tr>
                        <td>#<?php echo $c['id']; ?></td>
                        <td><?php echo htmlspecialchars($c['username']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td>
                            <?php if (!empty($c['allergy_info'])): ?>
                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i>
                                    <?php echo htmlspecialchars($c['allergy_info']); ?></span>
                            <?php else: ?>
                                <span class="badge badge-success">Yok</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $c['created_at'] ?? '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Ürün Fiyatları -->
        <div class="card">
            <h3><i class="fas fa-tags"></i> Ürün Maliyet ve Satış Fiyatları</h3>
            <table>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Maliyet (%90)</th>
                    <th>Satış Fiyatı</th>
                    <th>Kar (%10)</th>
                </tr>
                <?php
                $stmt = $pdo->query("SELECT name, price FROM products ORDER BY name LIMIT 20");
                $products = $stmt->fetchAll();
                foreach ($products as $p):
                    $cost = $p['price'] * 0.90;
                    $profit = $p['price'] * 0.10;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                        <td class="cost"><?php echo number_format($cost, 2); ?> ₺</td>
                        <td><strong><?php echo number_format($p['price'], 2); ?> ₺</strong></td>
                        <td class="profit"><?php echo number_format($profit, 2); ?> ₺</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Son Siparişler -->
        <div class="card">
            <h3><i class="fas fa-clipboard-list"></i> Son Siparişler (Detaylı)</h3>
            <table>
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Adres</th>
                    <th>Sipariş İçeriği</th>
                    <th>Tutar</th>
                    <th>Ödeme</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                </tr>
                <?php
                $stmt = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 15");
                $orders = $stmt->fetchAll();
                foreach ($orders as $o):
                    // Sipariş zamanına göre durumu otomatik hesapla
                    $order_time = strtotime($o['created_at']);
                    $now = time();
                    $diff_minutes = floor(($now - $order_time) / 60);
                    
                    // Zamana göre durum belirleme (40 dakikada teslim)
                    if ($diff_minutes >= 40) {
                        $auto_status = 'delivered';
                    } elseif ($diff_minutes >= 25) {
                        $auto_status = 'shipped';
                    } elseif ($diff_minutes >= 10) {
                        $auto_status = 'preparing';
                    } else {
                        $auto_status = 'pending';
                    }
                    ?>
                    <tr>
                        <td><strong>#<?php echo $o['id']; ?></strong></td>
                        <td>
                            <i class="fas fa-user" style="color: #764ba2;"></i>
                            <?php echo htmlspecialchars($o['username']); ?>
                        </td>
                        <td style="max-width: 200px; font-size: 0.85rem;">
                            <i class="fas fa-map-marker-alt" style="color: #ef4444;"></i>
                            <?php echo htmlspecialchars($o['shipping_address'] ?? 'Belirtilmedi'); ?>
                        </td>
                        <td style="max-width: 250px; font-size: 0.85rem;">
                            <i class="fas fa-utensils" style="color: #10b981;"></i>
                            <?php echo htmlspecialchars($o['order_items'] ?? 'Standart Sipariş'); ?>
                        </td>
                        <td><strong style="color: #10b981;"><?php echo number_format($o['total_amount'], 2); ?> ₺</strong>
                        </td>
                        <td>
                            <span style="background: #f0f0f0; padding: 3px 8px; border-radius: 10px; font-size: 0.8rem;">
                                <i class="fas fa-credit-card"></i>
                                <?php echo isset($o['payment_method']) ? htmlspecialchars($o['payment_method']) : 'Kapıda'; ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $status_colors = ['pending' => '#f39c12', 'preparing' => '#3498db', 'shipped' => '#9b59b6', 'delivered' => '#27ae60', 'cancelled' => '#e74c3c'];
                            $status_texts = ['pending' => 'Beklemede', 'preparing' => 'Hazırlanıyor', 'shipped' => 'Yolda', 'delivered' => 'Teslim Edildi ✓', 'cancelled' => 'İptal'];
                            $color = $status_colors[$auto_status] ?? '#888';
                            $text = $status_texts[$auto_status] ?? $auto_status;
                            ?>
                            <span
                                style="background: <?php echo $color; ?>; color: white; padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: bold;">
                                <?php echo $text; ?>
                            </span>
                        </td>
                        <td style="font-size: 0.85rem;"><?php echo date('d.m.Y H:i', strtotime($o['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

</body>

</html>