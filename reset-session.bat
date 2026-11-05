@echo off
echo ================================
echo  Reset Session & Cache
echo ================================
echo.
echo Clearing all cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo.
echo Clearing session files...
del /Q storage\framework\sessions\*
echo.
echo ================================
echo  Done! Please login again.
echo ================================
pause
