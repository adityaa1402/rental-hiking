@echo off
echo ================================
echo  HIKETRIP RENTAL - Setup Script
echo ================================
echo.

echo [1/5] Checking Laravel installation...
php artisan --version
echo.

echo [2/5] Creating storage link...
php artisan storage:link
echo.

echo [3/5] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo.

echo [4/5] Installing NPM dependencies...
call npm install
call npm run build
echo.

echo [5/5] Setup complete!
echo.
echo ================================
echo  NEXT STEPS:
echo ================================
echo 1. Import database via HeidiSQL:
echo    File: C:\laragon\www\hikettrip-rental\database\hikettrip_rental.sql
echo.
echo 2. Start Laravel server:
echo    php artisan serve
echo.
echo 3. Open browser: http://localhost:8000
echo.
echo Login Demo:
echo - Admin: admin@hikettrip.com / admin123
echo - Customer: customer@hikettrip.com / customer123
echo ================================

pause
