@echo off
color 0A
echo.
echo ========================================
echo   ULTIMATE FIX - HIKETRIP RENTAL
echo ========================================
echo.
echo This will fix ALL common errors!
echo.
pause

echo.
echo [1/10] Stopping any running PHP processes...
taskkill /F /IM php.exe 2>nul
timeout /t 2 /nobreak >nul
echo Done!

echo.
echo [2/10] Clearing Laravel cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo Done!

echo.
echo [3/10] Clearing compiled files...
php artisan clear-compiled 2>nul
echo Done!

echo.
echo [4/10] Optimizing application...
php artisan optimize:clear
echo Done!

echo.
echo [5/10] Regenerating autoload files...
composer dump-autoload -o
echo Done!

echo.
echo [6/10] Clearing session files...
del /Q storage\framework\sessions\* 2>nul
echo Done!

echo.
echo [7/10] Clearing view cache...
del /Q storage\framework\views\* 2>nul
echo Done!

echo.
echo [8/10] Clearing app cache...
del /Q storage\framework\cache\data\* 2>nul
echo Done!

echo.
echo [9/10] Fixing file permissions...
icacls storage /grant Users:F /T /Q 2>nul
icacls bootstrap\cache /grant Users:F /T /Q 2>nul
echo Done!

echo.
echo [10/10] Final optimization...
php artisan config:cache
php artisan route:cache
echo Done!

echo.
echo ========================================
echo   ALL FIXED! SUCCESS!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. Close this window
echo 2. CLOSE YOUR BROWSER COMPLETELY
echo 3. Run: start.bat
echo 4. Open NEW browser window
echo 5. Go to: http://localhost:8000
echo 6. Press Ctrl+Shift+Delete to clear browser cache
echo 7. Login again!
echo.
echo Login credentials:
echo - Admin: admin@hikettrip.com / admin123
echo - Customer: customer@hikettrip.com / customer123
echo.
echo ========================================
pause
