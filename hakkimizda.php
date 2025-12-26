<?php require_once 'includes/header.php'; ?>

<style>
    .about-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 60px 20px;
        text-align: center;
        color: white;
    }

    .about-hero h1 {
        font-size: 3rem;
        margin-bottom: 10px;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
    }

    .about-hero p {
        font-size: 1.3rem;
        opacity: 0.9;
    }

    .team-section {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .team-card {
        background: white;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-wrap: wrap;
    }

    .team-image {
        flex: 1;
        min-width: 300px;
        max-height: 400px;
        overflow: hidden;
    }

    .team-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .team-image:hover img {
        transform: scale(1.05);
    }

    .team-info {
        flex: 1;
        min-width: 300px;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .team-info h2 {
        color: #764ba2;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .thanks-box {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
        color: white;
        padding: 30px;
        border-radius: 20px;
        margin-top: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
    }

    .thanks-box .emoji {
        font-size: 50px;
        margin-bottom: 15px;
    }

    .thanks-box h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .fun-facts {
        background: #f8f9fa;
        padding: 50px 20px;
        margin-top: 50px;
    }

    .fun-facts h2 {
        text-align: center;
        color: #333;
        margin-bottom: 40px;
    }

    .facts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .fact-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s;
    }

    .fact-card:hover {
        transform: translateY(-10px);
    }

    .fact-card .icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .fact-card h4 {
        color: #333;
        margin-bottom: 10px;
    }

    .fact-card p {
        color: #666;
        font-size: 0.95rem;
    }

    .quote-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        margin-top: 50px;
    }

    .quote-section blockquote {
        font-size: 1.8rem;
        font-style: italic;
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .quote-section .author {
        margin-top: 20px;
        font-size: 1.2rem;
        opacity: 0.8;
    }
</style>

<div class="about-hero">
    <h1>🍰 Lezzet Dünyası</h1>
    <p>Tatlı hayaller, gerçek lezzetler!</p>
</div>

<div class="team-section">
    <div class="team-card">
        <div class="team-image">
            <img src="assets/images/ekip.jpg" alt="Ekibimiz">
        </div>
        <div class="team-info">
            <h2>Biz Kimiz? 🎂</h2>
            <p style="color: #666; line-height: 1.8; font-size: 1.1rem;">
                Bir grup tatlı delisi olarak yola çıktık! Kahve kokulu sabahlar, baklava kokusu tüten akşamlar...
                Amacımız basit: <strong>Sizin yüzünüze tatlı bir gülümseme kondumak!</strong>
            </p>
            <p style="color: #666; line-height: 1.8; margin-top: 15px;">
                🍩 Her siparişte aşkla hazırlanan tatlılar<br>
                ☕ Kahvemiz kadar sıcak bir müşteri ilişkisi<br>
                🚀 Süper hızlı teslimat (kapınıza uçarak gelen kuryeler)
            </p>

            <div class="thanks-box">
                <div class="emoji">🎓❤️🙏</div>
                <h3>Fırat Üniversitesi</h3>
                <p style="font-size: 1.1rem;">
                    Bu harika işletmeyi hayata geçirmemize olanak sağlayan,
                    bize kod yazmayı öğretirken sabırla "syntax error" çığlıklarımıza katlanan,
                    <strong>sevgili hocamıza sonsuz teşekkürlerimizi sunuyoruz!</strong> 🌟
                </p>
                <p style="margin-top: 15px; font-size: 0.9rem; opacity: 0.9;">
                    (Hoca, bu projeye A+ verirsen menüden bedava baklava! 😄)
                </p>
            </div>
        </div>
    </div>
</div>

<div class="fun-facts">
    <h2>🎉 Eğlenceli Gerçekler</h2>
    <div class="facts-grid">
        <div class="fact-card">
            <div class="icon">🍰</div>
            <h4>1000+ Pasta</h4>
            <p>Bugüne kadar hazırladığımız pasta sayısı (ve hala saymaya devam ediyoruz!)</p>
        </div>
        <div class="fact-card">
            <div class="icon">😋</div>
            <h4>%100 Mutluluk</h4>
            <p>Müşterilerimizin tamamı mutlu ayrılıyor (çünkü mutsuz olanları geri göndermiyoruz 😂)</p>
        </div>
        <div class="fact-card">
            <div class="icon">☕</div>
            <h4>5000 Fincan Kahve</h4>
            <p>Bu projeyi geliştirirken içtiğimiz kahve miktarı. Uyku nedir bilmiyoruz!</p>
        </div>
        <div class="fact-card">
            <div class="icon">💻</div>
            <h4>10.000 Satır Kod</h4>
            <p>Ve bunların yarısı "neden çalışmıyor" diye söylenerek yazıldı...</p>
        </div>
    </div>
</div>

<div class="quote-section">
    <blockquote>
        "Hayat kısa, tatlısız geçmesin!"
    </blockquote>
    <div class="author">- Lezzet Dünyası Ekibi 🍩</div>
</div>

<?php require_once 'includes/footer.php'; ?>