# 🚀 GitHub'a Yükleme Rehberi - Lezzet Dünyası Pastanesi

## Adım 1: Git Kurulumu (Eğer yüklü değilse)
1. https://git-scm.com/download/win adresinden Git'i indirin
2. Kurulumu tamamlayın (varsayılan ayarlarla devam edin)

## Adım 2: GitHub Hesabı Oluşturma
1. https://github.com adresine gidin
2. "Sign Up" butonuna tıklayın
3. E-posta, şifre ve kullanıcı adı belirleyin

## Adım 3: Yeni Repository Oluşturma
1. GitHub'da sağ üstteki "+" butonuna tıklayın
2. "New repository" seçin
3. Repository adı: `lezzet-dunyasi-pastane` (veya istediğiniz bir isim)
4. Açıklama: "PHP ile geliştirilmiş pastane e-ticaret sitesi"
5. Public veya Private seçin
6. "Create repository" butonuna tıklayın

## Adım 4: Projeyi GitHub'a Yükleme

### PowerShell veya CMD'de şu komutları çalıştırın:

```powershell
# Proje klasörüne git
cd "c:\Users\Dell\Desktop\SQL OTOMASYON"

# Git repository başlat
git init

# Tüm dosyaları ekle
git add .

# İlk commit
git commit -m "🎂 Lezzet Dünyası Pastanesi - İlk Yükleme"

# Ana branch'i main olarak ayarla
git branch -M main

# GitHub remote ekle (KULLANICI_ADINIZ yerine GitHub kullanıcı adınızı yazın)
git remote add origin https://github.com/KULLANICI_ADINIZ/lezzet-dunyasi-pastane.git

# GitHub'a yükle
git push -u origin main
```

## Adım 5: Kimlik Doğrulama
- İlk push'ta GitHub kullanıcı adı ve şifre/token isteyecek
- Şifre yerine "Personal Access Token" kullanmanız gerekebilir
- Token oluşturmak için: GitHub > Settings > Developer settings > Personal access tokens > Generate new token

## 📁 .gitignore Dosyası (Hassas dosyaları hariç tutmak için)
Proje klasöründe `.gitignore` dosyası oluşturup şunları ekleyin:
```
logs/
*.log
.env
config_local.php
```

## ✅ Tamamlandı!
Projeniz artık GitHub'da! 🎉
