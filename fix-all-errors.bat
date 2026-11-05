@echo off
echo ========================================
echo  FIXING ALL ERRORS - HIKETRIP RENTAL
echo ========================================
echo.

echo [1/5] Clearing all cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo Done!
echo.

echo [2/5] Clearing compiled files...
php artisan clear-compiled
echo Done!
echo.

echo [3/5] Optimizing autoload...
composer dump-autoload
echo Done!
echo.

echo [4/5] Recreating bootstrap cache...
php artisan optimize:clear
echo Done!
echo.

echo [5/5] Deleting old session files...
del /Q storage\framework\sessions\* 2>nul
del /Q storage\framework\views\* 2>nul
echo Done!
echo.

echo ========================================
echo  ALL FIXED! Now refresh your browser.
echo ========================================
echo.
echo Next steps:
echo 1. Close your browser completely
echo 2. Open browser again
echo 3. Go to: http://localhost:8000
echo 4. Clear browser cache (Ctrl+Shift+Delete)
echo 5. Login again
echo.
pause
