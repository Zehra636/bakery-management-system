@echo off
chcp 65001 >nul
echo ===================================================
echo SITEYI BASLATMA VE ONARMA ARACI
echo ===================================================

echo.
echo 1. Eski servisler kapatiliyor (Temiz baslangic icin)...
taskkill /F /IM httpd.exe >nul 2>&1
taskkill /F /IM mysqld.exe >nul 2>&1

echo.
echo 2. Port kontrolu yapiliyor...
netstat -ano | findstr :80 >nul
if %errorlevel% equ 0 (
    echo UYARI: Port 80 baska bir program tarafindan kullaniliyor olabilir.
    echo Apache baslamazsa Skype, IIS veya baska bir web sunucusunu kapatin.
)

echo.
echo 3. Dosyalar kontrol ediliyor...
if not exist "C:\xampp\htdocs\pastane" (
    echo DIKKAT: 'pastane' klasoru bulunamadi!
    echo Dosyalar tekrar kopyalaniyor...
    mkdir "C:\xampp\htdocs\pastane"
    xcopy /E /Y /I . "C:\xampp\htdocs\pastane"
)

echo.
echo 4. Sunucular BASLATILIYOR...
echo Lutfen acilan pencerelere izin verin (Erisim izni isterse).

:: Apache Baslat
if exist "C:\xampp\apache_start.bat" (
    start /min "Apache" "C:\xampp\apache_start.bat"
    echo Apache baslatma komutu verildi.
) else (
    echo HATA: C:\xampp\apache_start.bat bulunamadi! XAMPP kurulu mu?
)

:: MySQL Baslat
if exist "C:\xampp\mysql_start.bat" (
    start /min "MySQL" "C:\xampp\mysql_start.bat"
    echo MySQL baslatma komutu verildi.
) else (
    echo HATA: C:\xampp\mysql_start.bat bulunamadi!
)

echo.
echo 5. Site aciliyor...
timeout /t 5 >nul
start http://localhost/pastane

echo.
echo ISLEM TAMAMLANDI.
echo Eger site hala acilmiyorsa lutfen XAMPP Control Panel'i acip manuel olarak START tuslarina basin.
echo.
pause
