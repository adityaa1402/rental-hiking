@echo off
setlocal
cd /d "%~dp0"

echo HIKETRIP RENTAL - Setup
echo.
where php >nul 2>nul || (echo PHP tidak ditemukan. Aktifkan PHP melalui Laragon lalu coba lagi.& exit /b 1)
where composer >nul 2>nul || (echo Composer tidak ditemukan. Instal Composer lalu coba lagi.& exit /b 1)
where npm >nul 2>nul || (echo Node.js/npm tidak ditemukan. Instal Node.js lalu coba lagi.& exit /b 1)

if not exist .env (
    copy .env.example .env >nul
    echo File .env dibuat. Isi koneksi database sebelum melanjutkan.
    pause
)

findstr /R /C:"^APP_KEY=base64:." .env >nul
if errorlevel 1 php artisan key:generate
if errorlevel 1 exit /b 1

call composer install
if errorlevel 1 exit /b 1
php artisan migrate
if errorlevel 1 exit /b 1
php artisan storage:link
if errorlevel 1 exit /b 1
call npm install
if errorlevel 1 exit /b 1
call npm run build
if errorlevel 1 exit /b 1

echo.
echo Setup selesai. Jalankan start.bat untuk membuka server.
echo Panduan lengkap tersedia di PANDUAN_PENGGUNA.md.
pause
endlocal
