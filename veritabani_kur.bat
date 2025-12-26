@echo off
chcp 65001 >nul
echo MySQL servisi baslatilmaya calisiliyor...

:: XAMPP MySQL baslatma scriptini arka planda calistir
if exist "C:\xampp\mysql_start.bat" (
    start /min "" "C:\xampp\mysql_start.bat"
    echo MySQL baslatma komutu gonderildi. 10 saniye bekleniyor...
    timeout /t 10
) else (
    echo XAMPP baslatma dosyasi bulunamadi. Lutfen XAMPP Control Panel'den MySQL'i 'Start' yapin.
    echo Yine de devam ediliyor...
)

echo Veritabani yukleniyor...
"C:\xampp\mysql\bin\mysql.exe" -u root < database.sql

if %errorlevel% equ 0 (
    echo.
    echo BASARILI! Veritabani kuruldu.
    echo Siteye girebilirsiniz: http://localhost/pastane
) else (
    echo.
    echo HATA! Veritabani yuklenemedi. Acaba MySQL calisiyor mu?
    echo Lutfen XAMPP Control Panel'i acip MySQL'in yanindaki START tusuna basin.
    echo Sonra bu dosyayi tekrar calistirin.
)
pause
