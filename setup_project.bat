@echo off
chcp 65001 >nul
echo Kurulum baslatiliyor...

:: 1. Hedef Klasoru Olustur
set TARGET_DIR=C:\xampp\htdocs\pastane
if not exist "%TARGET_DIR%" mkdir "%TARGET_DIR%"

:: 2. Dosyalari Kopyala
echo Dosyalar kopyalaniyor...
xcopy /E /Y /I . "%TARGET_DIR%"

:: 3. Veritabanini Ice Aktar
echo Veritabani kuruluyor...
if exist "C:\xampp\mysql\bin\mysql.exe" (
    "C:\xampp\mysql\bin\mysql.exe" -u root < database.sql
    echo Veritabani basariyla ice aktarildi.
) else (
    echo HATA: MySQL bulunamadi. Lutfen XAMPP'in C:\xampp klasorune kurulu oldugundan emin olun.
    pause
    exit /b
)

echo.
echo ==========================================
echo KURULUM TAMAMLANDI!
echo ==========================================
echo.
echo Tarayicinizdan su adrese giderek siteyi gorebilirsiniz:
echo http://localhost/pastane
echo.
pause
