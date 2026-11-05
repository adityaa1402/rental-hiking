@echo off
echo ================================
echo  Clearing All Cache
echo ================================
echo.
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo.
echo ================================
echo  Cache cleared successfully!
echo ================================
pause
