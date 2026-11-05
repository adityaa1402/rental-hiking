@echo off
echo ================================
echo  Fixing Session Table
echo ================================
echo.

echo Creating sessions table...
php artisan session:table
php artisan migrate --path=database/migrations/*_create_sessions_table.php
echo.

echo Clearing cache...
php artisan config:clear
php artisan cache:clear
echo.

echo ================================
echo  Session table created!
echo ================================
echo.
echo Now you can change back to:
echo SESSION_DRIVER=database in .env
echo.
pause
