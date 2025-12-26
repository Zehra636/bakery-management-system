<?php require_once 'includes/header.php'; ?>

<style>
    .cake-hero {
        background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
        padding: 60px 20px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .cake-hero::before {
        content: '🎂';
        position: absolute;
        font-size: 200px;
        opacity: 0.1;
        right: -50px;
        top: -50px;
    }

    .cake-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    }

    .cake-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .cake-container {
        max-width: 1100px;
        margin: -40px auto 50px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .cake-builder {
        background: white;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .builder-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 500px;
    }

    @media (max-width: 768px) {
        .builder-grid {
            grid-template-columns: 1fr;
        }
    }

    .upload-section {
        background: linear-gradient(135deg, #667eea22 0%, #764ba222 100%);
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .upload-box {
        width: 100%;
        max-width: 350px;
        height: 300px;
        border: 3px dashed #764ba2;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .upload-box:hover {
        border-color: #667eea;
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }

    .upload-box i {
        font-size: 4rem;
        color: #764ba2;
        margin-bottom: 15px;
    }

    .upload-box p {
        color: #666;
        font-size: 1.1rem;
    }

    .upload-box small {
        color: #999;
        margin-top: 10px;
    }

    #imagePreview {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .options-section {
        padding: 40px;
    }

    .section-title {
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title .num {
        background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .size-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .size-option {
        background: #f8f9fa;
        border: 3px solid transparent;
        border-radius: 15px;
        padding: 20px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .size-option:hover {
        border-color: #764ba2;
    }

    .size-option.selected {
        border-color: #764ba2;
        background: linear-gradient(135deg, #667eea11 0%, #764ba211 100%);
    }

    .size-option .persons {
        font-size: 2rem;
        font-weight: bold;
        color: #764ba2;
    }

    .size-option .label {
        color: #666;
        font-size: 0.9rem;
    }

    .size-option .price {
        color: #27ae60;
        font-weight: bold;
        margin-top: 5px;
    }

    .size-option input {
        display: none;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .form-group input[type="text"] {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #eee;
        border-radius: 12px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #764ba2;
    }

    .addon-option {
        background: linear-gradient(135deg, #ff6b6b11 0%, #feca5711 100%);
        border: 2px solid #feca57;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .addon-option:hover {
        transform: translateX(5px);
    }

    .addon-option .emoji {
        font-size: 2.5rem;
    }

    .addon-option .text {
        flex: 1;
    }

    .addon-option .text strong {
        display: block;
        color: #333;
    }

    .addon-option .text small {
        color: #888;
    }

    .addon-option .price-tag {
        background: #ff6b6b;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
    }

    .flower-option {
        background: #f8f9fa;
        border: 3px solid transparent;
        border-radius: 15px;
        padding: 15px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .flower-option:hover {
        border-color: #ff6b6b;
        transform: translateY(-3px);
    }

    .flower-option.selected {
        border-color: #ff6b6b;
        background: linear-gradient(135deg, #ff6b6b11 0%, #feca5711 100%);
    }

    .flower-emoji {
        font-size: 2rem;
        margin-bottom: 5px;
    }

    .flower-name {
        font-size: 0.8rem;
        color: #333;
        font-weight: bold;
    }

    .flower-price {
        font-size: 0.75rem;
        color: #27ae60;
        margin-top: 5px;
    }

    .price-display {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 25px;
        border-radius: 20px;
        text-align: center;
        margin-top: 20px;
    }

    .price-display .label {
        font-size: 1rem;
        opacity: 0.9;
    }

    .price-display .amount {
        font-size: 3rem;
        font-weight: bold;
    }

    .submit-btn {
        width: 100%;
        padding: 20px;
        background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
        border: none;
        border-radius: 15px;
        color: white;
        font-size: 1.3rem;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        margin-top: 20px;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
    }

    .features-row {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .feature {
        text-align: center;
    }

    .feature .icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .feature .text {
        color: #666;
        font-size: 0.9rem;
    }
</style>

<div class="cake-hero">
    <h1>🎨 Hayalindeki Pastayı Tasarla!</h1>
    <p>Fotoğrafını yükle, detayları seç, biz yapalım!</p>
</div>

<div class="cake-container">
    <form action="ozel_pasta_action.php" method="POST" enctype="multipart/form-data">
        <div class="cake-builder">
            <div class="builder-grid">
                <!-- Sol: Resim Yükleme -->
                <div class="upload-section">
                    <h3 class="section-title"><span class="num">1</span> Pasta Görselini Yükle</h3>
                    <label for="cake_image" class="upload-box">
                        <img id="imagePreview" src="" alt="Preview">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Tıkla veya Sürükle</p>
                        <small>PNG, JPG (Max 5MB)</small>
                        <input type="file" name="cake_image" id="cake_image" accept="image/*" style="display:none;">
                    </label>
                    <p style="color: #888; margin-top: 20px; text-align: center; font-size: 0.9rem;">
                        💡 İnternetten beğendiğiniz pastanın resmini yükleyebilirsiniz
                    </p>
                </div>

                <!-- Sağ: Seçenekler -->
                <div class="options-section">
                    <h3 class="section-title"><span class="num">2</span> Pasta Boyutunu Seç</h3>

                    <div class="size-options">
                        <label class="size-option selected" onclick="selectSize(this, 0)">
                            <input type="radio" name="size" value="4" checked>
                            <div class="persons">4</div>
                            <div class="label">Kişilik</div>
                            <div class="price">300 TL</div>
                        </label>
                        <label class="size-option" onclick="selectSize(this, 100)">
                            <input type="radio" name="size" value="6">
                            <div class="persons">6</div>
                            <div class="label">Kişilik</div>
                            <div class="price">400 TL</div>
                        </label>
                        <label class="size-option" onclick="selectSize(this, 200)">
                            <input type="radio" name="size" value="8">
                            <div class="persons">8</div>
                            <div class="label">Kişilik</div>
                            <div class="price">500 TL</div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-pen"></i> Pasta Üzerine Yazı</label>
                        <input type="text" name="cake_text" placeholder="Örn: İyi ki Doğdun Ayşe! 🎉">
                    </div>

                    <h3 class="section-title" style="margin-top: 10px;"><span class="num">3</span> Çiçek Seçenekleri
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px;">
                        <label class="flower-option" onclick="selectFlower(this, 0, 'yok')">
                            <input type="radio" name="flower" value="0" checked style="display:none;">
                            <div class="flower-emoji">❌</div>
                            <div class="flower-name">Yok</div>
                            <div class="flower-price">0 TL</div>
                        </label>
                        <label class="flower-option" onclick="selectFlower(this, 100, 'gul')">
                            <input type="radio" name="flower" value="gul" style="display:none;">
                            <div class="flower-emoji">🌹</div>
                            <div class="flower-name">Gül</div>
                            <div class="flower-price">+100 TL</div>
                        </label>
                        <label class="flower-option" onclick="selectFlower(this, 150, 'papatya')">
                            <input type="radio" name="flower" value="papatya" style="display:none;">
                            <div class="flower-emoji">🌼</div>
                            <div class="flower-name">Papatya</div>
                            <div class="flower-price">+150 TL</div>
                        </label>
                        <label class="flower-option" onclick="selectFlower(this, 200, 'orkide')">
                            <input type="radio" name="flower" value="orkide" style="display:none;">
                            <div class="flower-emoji">🪻</div>
                            <div class="flower-name">Orkide</div>
                            <div class="flower-price">+200 TL</div>
                        </label>
                        <label class="flower-option" onclick="selectFlower(this, 250, 'buket')">
                            <input type="radio" name="flower" value="buket" style="display:none;">
                            <div class="flower-emoji">💐</div>
                            <div class="flower-name">Karışık</div>
                            <div class="flower-price">+250 TL</div>
                        </label>
                    </div>

                    <div class="price-display">
                        <div class="label">Toplam Tutar</div>
                        <div class="amount" id="totalPrice">300 TL</div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-shopping-cart"></i> Sepete Ekle
                    </button>
                </div>
            </div>
        </div>

        <div class="features-row">
            <div class="feature">
                <div class="icon">🎨</div>
                <div class="text">Özel Tasarım</div>
            </div>
            <div class="feature">
                <div class="icon">🚚</div>
                <div class="text">Hızlı Teslimat</div>
            </div>
            <div class="feature">
                <div class="icon">✨</div>
                <div class="text">Taze Malzeme</div>
            </div>
            <div class="feature">
                <div class="icon">💯</div>
                <div class="text">%100 Memnuniyet</div>
            </div>
        </div>
    </form>
</div>

<script>
    let basePrice = 300;
    let sizeExtra = 0;
    let flowerPrice = 0;

    function updatePrice() {
        let total = basePrice + sizeExtra + flowerPrice;
        document.getElementById('totalPrice').textContent = total + ' TL';
    }

    function selectSize(el, extra) {
        document.querySelectorAll('.size-option').forEach(s => s.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        sizeExtra = extra;
        updatePrice();
    }

    function selectFlower(el, price, type) {
        document.querySelectorAll('.flower-option').forEach(f => f.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        flowerPrice = price;
        updatePrice();
    }
    
    // İlk çiçeği seç (Yok)
    document.querySelector('.flower-option').classList.add('selected');

    // Resim önizleme
    document.getElementById('cake_image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('imagePreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.querySelector('.upload-box i').style.display = 'none';
                document.querySelector('.upload-box p').style.display = 'none';
                document.querySelector('.upload-box small').style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });
</script>