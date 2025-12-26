<?php
require_once 'includes/header.php';

// Türkiye'nin 81 ili
$iller = [
    'Adana',
    'Adıyaman',
    'Afyonkarahisar',
    'Ağrı',
    'Amasya',
    'Ankara',
    'Antalya',
    'Artvin',
    'Aydın',
    'Balıkesir',
    'Bilecik',
    'Bingöl',
    'Bitlis',
    'Bolu',
    'Burdur',
    'Bursa',
    'Çanakkale',
    'Çankırı',
    'Çorum',
    'Denizli',
    'Diyarbakır',
    'Edirne',
    'Elazığ',
    'Erzincan',
    'Erzurum',
    'Eskişehir',
    'Gaziantep',
    'Giresun',
    'Gümüşhane',
    'Hakkari',
    'Hatay',
    'Isparta',
    'Mersin',
    'İstanbul',
    'İzmir',
    'Kars',
    'Kastamonu',
    'Kayseri',
    'Kırklareli',
    'Kırşehir',
    'Kocaeli',
    'Konya',
    'Kütahya',
    'Malatya',
    'Manisa',
    'Kahramanmaraş',
    'Mardin',
    'Muğla',
    'Muş',
    'Nevşehir',
    'Niğde',
    'Ordu',
    'Rize',
    'Sakarya',
    'Samsun',
    'Siirt',
    'Sinop',
    'Sivas',
    'Tekirdağ',
    'Tokat',
    'Trabzon',
    'Tunceli',
    'Şanlıurfa',
    'Uşak',
    'Van',
    'Yozgat',
    'Zonguldak',
    'Aksaray',
    'Bayburt',
    'Karaman',
    'Kırıkkale',
    'Batman',
    'Şırnak',
    'Bartın',
    'Ardahan',
    'Iğdır',
    'Yalova',
    'Karabük',
    'Kilis',
    'Osmaniye',
    'Düzce'
];

// Bölgelere göre iller
$bolgeler = [
    'Marmara' => ['İstanbul', 'Bursa', 'Kocaeli', 'Balıkesir', 'Tekirdağ', 'Edirne', 'Kırklareli', 'Çanakkale', 'Yalova', 'Bilecik', 'Sakarya'],
    'Ege' => ['İzmir', 'Aydın', 'Denizli', 'Muğla', 'Manisa', 'Afyonkarahisar', 'Kütahya', 'Uşak'],
    'Akdeniz' => ['Antalya', 'Adana', 'Mersin', 'Hatay', 'Isparta', 'Burdur', 'Kahramanmaraş', 'Osmaniye'],
    'İç Anadolu' => ['Ankara', 'Konya', 'Eskişehir', 'Kayseri', 'Sivas', 'Yozgat', 'Aksaray', 'Niğde', 'Nevşehir', 'Kırşehir', 'Kırıkkale', 'Çankırı', 'Karaman'],
    'Karadeniz' => ['Samsun', 'Trabzon', 'Ordu', 'Giresun', 'Rize', 'Artvin', 'Zonguldak', 'Kastamonu', 'Sinop', 'Amasya', 'Tokat', 'Çorum', 'Bolu', 'Düzce', 'Karabük', 'Bartın', 'Bayburt', 'Gümüşhane'],
    'Doğu Anadolu' => ['Erzurum', 'Malatya', 'Elazığ', 'Van', 'Ağrı', 'Kars', 'Erzincan', 'Bingöl', 'Muş', 'Bitlis', 'Hakkari', 'Tunceli', 'Iğdır', 'Ardahan'],
    'Güneydoğu Anadolu' => ['Gaziantep', 'Diyarbakır', 'Şanlıurfa', 'Mardin', 'Batman', 'Siirt', 'Şırnak', 'Adıyaman', 'Kilis']
];

$bolge_renkleri = [
    'Marmara' => '#667eea',
    'Ege' => '#06b6d4',
    'Akdeniz' => '#f59e0b',
    'İç Anadolu' => '#ef4444',
    'Karadeniz' => '#10b981',
    'Doğu Anadolu' => '#8b5cf6',
    'Güneydoğu Anadolu' => '#ec4899'
];

$bolge_emojiler = [
    'Marmara' => '🌉',
    'Ege' => '🏖️',
    'Akdeniz' => '🌴',
    'İç Anadolu' => '🏛️',
    'Karadeniz' => '🌲',
    'Doğu Anadolu' => '🏔️',
    'Güneydoğu Anadolu' => '🕌'
];
?>

<style>
    .subeler-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 50px 20px;
        text-align: center;
        color: white;
        margin-bottom: 40px;
        border-radius: 0 0 30px 30px;
    }

    .subeler-hero h1 {
        font-size: 2.5rem;
        margin: 0 0 10px 0;
    }

    .stats-row {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 25px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .bolge-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .bolge-section {
        margin-bottom: 40px;
    }

    .bolge-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px 20px;
        border-radius: 15px;
        color: white;
    }

    .bolge-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .bolge-emoji {
        font-size: 2rem;
    }

    .sube-count {
        margin-left: auto;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
    }

    .iller-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .il-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 4px solid;
    }

    .il-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .il-card .il-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }

    .il-card h3 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        color: #333;
    }

    .il-card .sube-info {
        font-size: 0.85rem;
        color: #666;
    }

    .il-card .adres {
        font-size: 0.8rem;
        color: #999;
        margin-top: 8px;
    }

    .search-box {
        max-width: 500px;
        margin: -30px auto 30px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 18px 25px 18px 55px;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        outline: none;
    }

    .search-box i {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        color: #764ba2;
        font-size: 1.2rem;
    }

    .il-card.hidden {
        display: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .il-card {
        animation: fadeIn 0.4s ease-out;
    }

    .harita-btn {
        display: inline-block;
        margin-top: 12px;
        padding: 8px 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        transition: all 0.3s;
    }

    .harita-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    /* Fırat Üniversitesi Özel Bölüm */
    .firat-ozel {
        max-width: 800px;
        margin: 0 auto 40px;
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        border-radius: 25px;
        padding: 30px;
        text-align: center;
        color: white;
        box-shadow: 0 10px 40px rgba(196, 69, 105, 0.3);
    }

    .firat-ozel h2 {
        margin: 0 0 10px 0;
        font-size: 1.8rem;
    }

    .firat-ozel p {
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .firat-btn {
        display: inline-block;
        padding: 15px 30px;
        background: white;
        color: #c44569;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .firat-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    .firat-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-bottom: 15px;
    }
</style>

<div class="subeler-hero">
    <h1><i class="fas fa-map-marker-alt"></i> Şubelerimiz</h1>
    <p style="opacity: 0.9;">Türkiye'nin her köşesinde sizlerleyiz!</p>

    <div class="stats-row">
        <div class="stat-item">
            <span class="stat-number">81</span>
            <span class="stat-label">İl</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">7</span>
            <span class="stat-label">Bölge</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">150+</span>
            <span class="stat-label">Şube</span>
        </div>
    </div>
</div>

<div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" id="il-search" placeholder="İl ara... (örn: İstanbul, Ankara)" onkeyup="searchIl()">
</div>

<!-- Fırat Üniversitesi Özel Bölüm -->
<div class="firat-ozel">
    <span class="firat-badge">⭐ Özel Şubemiz</span>
    <h2>🎓 Fırat Üniversitesi - Teknoloji Fakültesi</h2>
    <p>Elazığ'daki ana şubemiz! Kampüs içinde hizmetinizdeyiz.</p>
    <a href="https://www.google.com/maps/place/F%C4%B1rat+%C3%9Cniversitesi+Teknoloji+Fak%C3%BCltesi/@38.6747,39.1989,17z"
        target="_blank" class="firat-btn">
        <i class="fas fa-map-marker-alt"></i> Fırat Üniversitesi Şubesi - Haritada Aç
    </a>
    <div style="margin-top: 15px; font-size: 0.9rem; opacity: 0.9;">
        <i class="fas fa-clock"></i> 08:00 - 22:00 | <i class="fas fa-phone"></i> 0424 237 00 00
    </div>
</div>

<div class="bolge-container">
    <?php foreach ($bolgeler as $bolge => $bolge_illeri): ?>
        <div class="bolge-section" data-bolge="<?php echo $bolge; ?>">
            <div class="bolge-header" style="background: <?php echo $bolge_renkleri[$bolge]; ?>;">
                <span class="bolge-emoji"><?php echo $bolge_emojiler[$bolge]; ?></span>
                <h2><?php echo $bolge; ?> Bölgesi</h2>
                <span class="sube-count"><?php echo count($bolge_illeri); ?> İl</span>
            </div>

            <div class="iller-grid">
                <?php foreach ($bolge_illeri as $il): ?>
                    <div class="il-card" style="border-left-color: <?php echo $bolge_renkleri[$bolge]; ?>;"
                        data-il="<?php echo strtolower($il); ?>">
                        <span class="il-icon">📍</span>
                        <h3><?php echo $il; ?></h3>
                        <div class="sube-info">
                            <i class="fas fa-store"></i> <?php echo rand(1, 5); ?> Şube
                        </div>
                        <div class="adres">
                            <i class="fas fa-phone"></i> 0850 123 <?php echo str_pad(rand(10, 99), 2, '0', STR_PAD_LEFT); ?>
                            <?php echo str_pad(rand(10, 99), 2, '0', STR_PAD_LEFT); ?>
                        </div>
                        <a href="https://www.google.com/maps/search/Lezzet+Dünyası+Pastane+<?php echo urlencode($il); ?>"
                            target="_blank" class="harita-btn">
                            <i class="fas fa-map-marked-alt"></i> Haritada Bul
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    function searchIl() {
        const query = document.getElementById('il-search').value.toLowerCase();
        const cards = document.querySelectorAll('.il-card');
        const sections = document.querySelectorAll('.bolge-section');

        cards.forEach(card => {
            const ilName = card.getAttribute('data-il');
            if (ilName.includes(query)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Bölge başlıklarını gizle/göster
        sections.forEach(section => {
            const visibleCards = section.querySelectorAll('.il-card:not(.hidden)');
            if (visibleCards.length === 0) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>