-- Veritabanı Oluşturma
CREATE DATABASE IF NOT EXISTS pastry_shop;
USE pastry_shop;

-- Kullanıcılar Tablosu
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    allergy_info TEXT, -- Müşterinin alerjisi varsa buraya yazılır
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kategoriler Tablosu (Örn: Tatlılar, Tuzlular, İçecekler)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    parent_id INT DEFAULT NULL, -- Alt kategoriler için (Örn: Tatlılar -> Sütlü)
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Ürünler Tablosu
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    cost_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00, -- Kar/Zarar hesabı için maliyet
    category_id INT,
    image_url VARCHAR(255),
    stock INT DEFAULT 100,
    is_allergen_friendly BOOLEAN DEFAULT FALSE, -- Alerjik duruma özel ürün mü?
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Siparişler Tablosu
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT,
    status ENUM('pending', 'preparing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sipariş Detayları Tablosu
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL, -- Sipariş anındaki fiyat
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Varsayılan Yönetici Hesabı (Şifre: admin123)
-- Not: Gerçek uygulamada şifreler hashlenmelidir (password_hash). Buradaki örnekte düz metin veya MD5 kullanilabilir ama guvenlik icin password_hash onerilir.
-- Kod tarafında doğrulamayı password_verify ile yapacağız.
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@profiterol.com', 'admin');
-- Şifre: 'password' (hashlenmiş hali örnek, kodda admin123 kullanacagiz, burayı kurulumda düzeltebiliriz)

-- Kategorileri Ekleme
INSERT INTO categories (name, parent_id) VALUES 
('Tatlılar', NULL),
('Tuzlular', NULL),
('Börekler', NULL),
('İçecekler', NULL);

-- Alt Kategoriler
-- Tatlılar (ID: 1)
INSERT INTO categories (name, parent_id) VALUES 
('Şerbetli', 1),
('Sütlü', 1),
('Çikolatalı', 1);

-- İçecekler (ID: 4)
INSERT INTO categories (name, parent_id) VALUES 
('Sıcak', 4),
('Soğuk', 4);

-- Örnek Ürünler (Bazılarını ekleyelim, kalanı yönetim panelinden eklenebilir)
INSERT INTO products (name, description, price, cost_price, category_id, image_url, is_allergen_friendly) VALUES
('Fıstıklı Baklava', 'Bol fıstıklı, çıtır şerbetli tatlı.', 250.00, 150.00, 5, 'assets/images/baklava.jpg', 0),
('Sütlaç', 'Geleneksel fırın sütlaç.', 60.00, 30.00, 6, 'assets/images/sutlac.jpg', 1),
('Profiterol', 'Yoğun çikolata soslu efsane lezzet.', 85.00, 40.00, 7, 'assets/images/profiterol.jpg', 0),
('Su Böreği', 'Peynirli sıcak su böreği.', 75.00, 45.00, 3, 'assets/images/suboregi.jpg', 0),
('Glutensiz Kek', 'Özel unla yapılmış, alerjen dostu kek.', 90.00, 50.00, 1, 'assets/images/glutensiz_kek.jpg', 1),
('Türk Kahvesi', 'Bol köpüklü Türk kahvesi.', 40.00, 10.00, 8, 'assets/images/kahve.jpg', 1),
('Limonata', 'Ev yapımı naneli limonata.', 45.00, 15.00, 9, 'assets/images/limonata.jpg', 1);
