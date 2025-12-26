<?php
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<style>
    body {
        background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);
    }

    .tracking-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 30px;
        border-radius: 0 0 30px 30px;
    }

    .tracking-header h2 {
        margin: 0;
        font-size: 2rem;
    }

    .orders-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px 50px;
    }

    .order-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.15);
        border-left: 5px solid #667eea;
        transition: transform 0.3s;
    }

    .order-card:hover {
        transform: translateX(5px);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px dashed #eee;
    }

    .order-header h3 {
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-header h3 .order-num {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 1rem;
    }

    .order-date {
        color: #888;
        font-size: 0.9rem;
    }

    .status-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 30px 0;
        padding: 0 10px;
    }

    .status-timeline::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 40px;
        right: 40px;
        height: 4px;
        background: #eee;
        z-index: 1;
    }

    .status-step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .status-step .icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.3rem;
        color: #999;
        transition: all 0.3s;
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .status-step.active .icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        animation: pulse 1.5s infinite;
    }

    .status-step.completed .icon {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(245, 87, 108, 0.4);
        }

        50% {
            box-shadow: 0 0 0 15px rgba(245, 87, 108, 0);
        }
    }

    .status-step .label {
        font-size: 0.8rem;
        color: #888;
        font-weight: 500;
    }

    .status-step.active .label,
    .status-step.completed .label {
        color: #333;
        font-weight: bold;
    }

    .delivery-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        text-align: center;
        margin: 20px 0;
    }

    .delivery-box .time {
        font-size: 2rem;
        font-weight: bold;
    }

    .delivery-box.delivered {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .order-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-item i {
        color: #764ba2;
        font-size: 1.2rem;
    }

    .empty-orders {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 20px;
    }

    .empty-orders .emoji {
        font-size: 60px;
        margin-bottom: 20px;
    }
</style>

<div class="tracking-header">
    <h2><i class="fas fa-truck"></i> Sipariş Takibi</h2>
    <p style="opacity: 0.8; margin-top: 10px;">Siparişlerinizin durumunu buradan takip edebilirsiniz</p>
</div>

<div class="orders-container">
    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <div class="emoji">🛒</div>
            <h3>Henüz siparişiniz yok!</h3>
            <p style="color: #888; margin: 20px 0;">Hadi bir şeyler sipariş edelim!</p>
            <a href="menu.php"
                style="display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 30px; font-weight: bold;">Menüye
                Git</a>
        </div>
    <?php endif; ?>

    <?php foreach ($orders as $order):
        // Sipariş zamanını hesapla
        $order_time = strtotime($order['created_at']);
        $now = time();
        $diff_minutes = floor(($now - $order_time) / 60);

        // Maksimum 40 dakikada teslim edilsin
        if ($diff_minutes >= 40) {
            $current_status = 'teslim';
            $status_text = 'Teslim Edildi';
            $remaining = 0;
        } elseif ($diff_minutes >= 25) {
            $current_status = 'yolda';
            $status_text = 'Yola Çıktı';
            $remaining = 40 - $diff_minutes;
        } elseif ($diff_minutes >= 10) {
            $current_status = 'hazirlaniyor';
            $status_text = 'Hazırlanıyor';
            $remaining = 40 - $diff_minutes;
        } else {
            $current_status = 'alindi';
            $status_text = 'Sipariş Alındı';
            $remaining = 40 - $diff_minutes;
        }
        ?>
        <div class="order-card">
            <div class="order-header">
                <h3><span class="order-num">#<?php echo $order['id']; ?></span> Sipariş</h3>
                <span class="order-date"><i class="far fa-clock"></i>
                    <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></span>
            </div>

            <!-- Durum Zaman Çizelgesi -->
            <div class="status-timeline">
                <div
                    class="status-step <?php echo in_array($current_status, ['alindi', 'hazirlaniyor', 'yolda', 'teslim']) ? 'completed' : ''; ?>">
                    <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                    <div class="label">Alındı</div>
                </div>
                <div
                    class="status-step <?php echo in_array($current_status, ['hazirlaniyor', 'yolda', 'teslim']) ? 'completed' : ($current_status == 'alindi' ? 'active' : ''); ?>">
                    <div class="icon"><i class="fas fa-utensils"></i></div>
                    <div class="label">Hazırlanıyor</div>
                </div>
                <div
                    class="status-step <?php echo in_array($current_status, ['yolda', 'teslim']) ? 'completed' : ($current_status == 'hazirlaniyor' ? 'active' : ''); ?>">
                    <div class="icon"><i class="fas fa-motorcycle"></i></div>
                    <div class="label">Yolda</div>
                </div>
                <div
                    class="status-step <?php echo $current_status == 'teslim' ? 'completed' : ($current_status == 'yolda' ? 'active' : ''); ?>">
                    <div class="icon"><i class="fas fa-home"></i></div>
                    <div class="label">Teslim</div>
                </div>
            </div>

            <?php if ($current_status != 'teslim'):
                // Maksimum 40 dakika ile sınırla
                $remaining = min(40, max(5, $remaining));
                $min_time = max(5, $remaining - 10);
                ?>
                <div class="delivery-box">
                    <div><i class="fas fa-clock"></i> Tahmini Teslimat</div>
                    <div class="time"><?php echo $min_time; ?> - <?php echo $remaining; ?> dakika</div>
                </div>
            <?php else: ?>
                <div class="delivery-box delivered">
                    <div class="time"><i class="fas fa-check-circle"></i> Afiyet Olsun!</div>
                    <small>Siparişiniz teslim edildi</small>
                </div>
            <?php endif; ?>

            <!-- Sipariş Detayları -->
            <div class="order-details" style="grid-template-columns: 1fr;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 10px;">
                    <h4 style="margin: 0 0 15px 0; color: #333;"><i class="fas fa-shopping-bag"></i> Sipariş Detayları</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="detail-item" style="background: white; padding: 10px; border-radius: 8px;">
                            <i class="fas fa-user"></i>
                            <div>
                                <small style="color: #888;">Müşteri</small><br>
                                <strong><?php echo $_SESSION['username']; ?></strong>
                            </div>
                        </div>
                        
                        <div class="detail-item" style="background: white; padding: 10px; border-radius: 8px;">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>
                                <small style="color: #888;">Toplam Tutar</small><br>
                                <strong style="color: #10b981;"><?php echo number_format($order['total_amount'], 2); ?> TL</strong>
                            </div>
                        </div>
                        
                        <div class="detail-item" style="background: white; padding: 10px; border-radius: 8px;">
                            <i class="fas fa-credit-card"></i>
                            <div>
                                <small style="color: #888;">Ödeme Yöntemi</small><br>
                                <strong><?php echo isset($order['payment_method']) ? htmlspecialchars($order['payment_method']) : 'Kapıda Ödeme'; ?></strong>
                            </div>
                        </div>
                        
                        <div class="detail-item" style="background: white; padding: 10px; border-radius: 8px;">
                            <i class="fas fa-clock"></i>
                            <div>
                                <small style="color: #888;">Sipariş Tarihi</small><br>
                                <strong><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item" style="background: white; padding: 12px; border-radius: 8px; margin-top: 12px;">
                        <i class="fas fa-map-marker-alt" style="color: #ef4444;"></i>
                        <div style="flex: 1;">
                            <small style="color: #888;">Teslimat Adresi</small><br>
                            <strong><?php echo htmlspecialchars($order['shipping_address']); ?></strong>
                        </div>
                    </div>
                    
                    <?php if (!empty($order['order_items'])): ?>
                    <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 12px;">
                        <small style="color: #888;"><i class="fas fa-utensils"></i> Sipariş İçeriği</small><br>
                        <div style="margin-top: 8px; color: #333;">
                            <?php echo htmlspecialchars($order['order_items']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    // Sayfayı her 60 saniyede yenile
    setTimeout(function () {
        location.reload();
    }, 60000);
</script>