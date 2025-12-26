<?php require_once 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lezzet Dünyası - Hoşgeldiniz</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .top-nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(0, 0, 0, 0.3);
        }

        .logo {
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nav-btns {
            display: flex;
            gap: 15px;
        }

        .nav-btn {
            padding: 12px 25px;
            border: 2px solid white;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
        }

        .nav-btn:hover {
            background: white;
            color: #764ba2;
        }

        /* Ana İçerik */
        .main-content {
            display: flex;
            min-height: 100vh;
        }

        /* Sol Taraf - Görseller */
        .visual-side {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            padding: 100px 20px 20px 20px;
            gap: 20px;
            align-content: center;
            justify-content: center;
            position: relative;
        }

        /* Tatlış arka plan partikülleri */
        .visual-side::before {
            content: '✨🍰🎂🧁🍪💕';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 8rem;
            opacity: 0.05;
            pointer-events: none;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-15px) rotate(2deg);
            }

            50% {
                transform: translateY(-10px) rotate(-2deg);
            }

            75% {
                transform: translateY(-20px) rotate(1deg);
            }
        }

        @keyframes shine {

            0%,
            100% {
                box-shadow: 0 10px 30px rgba(255, 182, 193, 0.4);
            }

            50% {
                box-shadow: 0 10px 50px rgba(255, 105, 180, 0.7), 0 0 30px rgba(255, 182, 193, 0.5);
            }
        }

        .food-card {
            width: 200px;
            height: 200px;
            border-radius: 25px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            animation: float 6s ease-in-out infinite, shine 3s ease-in-out infinite;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .food-card:nth-child(1) {
            animation-delay: 0s;
        }

        .food-card:nth-child(2) {
            animation-delay: 0.5s;
        }

        .food-card:nth-child(3) {
            animation-delay: 1s;
        }

        .food-card:nth-child(4) {
            animation-delay: 1.5s;
        }

        .food-card:nth-child(5) {
            animation-delay: 2s;
        }

        .food-card:nth-child(6) {
            animation-delay: 2.5s;
        }

        .food-card::before {
            content: '✨';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2rem;
            z-index: 2;
            opacity: 0;
            transform: scale(0) rotate(0deg);
            transition: all 0.4s ease-out;
        }

        .food-card:hover::before {
            opacity: 1;
            transform: scale(1) rotate(360deg);
        }

        .food-card:hover {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 20px 60px rgba(255, 105, 180, 0.6), 0 0 40px rgba(255, 182, 193, 0.8);
            border-color: rgba(255, 255, 255, 0.8);
        }

        .food-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .food-card:hover img {
            transform: scale(1.1) rotate(-5deg);
        }

        /* Sağ Taraf - Form */
        .form-side {
            width: 450px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.2);
        }

        .form-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: #888;
            margin-bottom: 30px;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }

        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            color: #aaa;
            font-weight: bold;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            color: #764ba2;
            border-bottom-color: #764ba2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #764ba2;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.5);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }

        .register-link a {
            color: #764ba2;
            text-decoration: none;
            font-weight: bold;
        }

        .error-msg {
            background: #ffe0e0;
            color: #c00;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body>

    <div class="main-content">
        <!-- Sol Taraf - Görseller -->
        <div class="visual-side">
            <div class="food-card"><img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=300"
                    alt="Pasta"></div>
            <div class="food-card"><img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?w=300"
                    alt="Donut"></div>
            <div class="food-card"><img src="https://images.unsplash.com/photo-1587314168485-3236d6710814?w=300"
                    alt="Kahve"></div>
            <div class="food-card"><img src="https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?w=300"
                    alt="Kurabiye"></div>
            <div class="food-card"><img src="https://images.unsplash.com/photo-1562440499-64c9a111f713?w=300"
                    alt="Cupcake"></div>
            <div class="food-card"><img
                    src="https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=300&h=300&fit=crop" alt="Kek">
            </div>
        </div>

        <!-- Sağ Taraf - Form -->
        <div class="form-side">
            <h1 class="form-title">Lezzet Dünyası</h1>
            <p class="form-subtitle">Hoşgeldiniz! Giriş yaparak devam edin.</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-msg">Kullanıcı adı veya şifre hatalı!</div>
            <?php endif; ?>

            <!-- Sekmeler -->
            <div class="tabs">
                <div class="tab active" onclick="showTab('customer')">Müşteri Girişi</div>
                <div class="tab" onclick="showTab('admin')">Yönetici Girişi</div>
            </div>

            <!-- Müşteri Formu -->
            <div id="customer" class="tab-content active">
                <form action="login_action.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Şifre" required>
                    </div>
                    <button type="submit" class="submit-btn">Giriş Yap</button>
                </form>
                <div class="register-link">
                    Hesabınız yok mu? <a href="register.php">Kayıt Olun</a>
                </div>
            </div>

            <!-- Yönetici Formu -->
            <div id="admin" class="tab-content">
                <form action="login_action.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Yönetici Adı" value="admin" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Şifre" required>
                    </div>
                    <button type="submit" class="submit-btn">Paneli Aç</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>

</body>

</html>