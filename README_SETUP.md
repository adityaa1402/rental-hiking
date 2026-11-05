# 🚀 HIKETRIP RENTAL - Laravel Setup Guide

## 📋 Prerequisites
✅ Laragon sudah terinstall dan running  
✅ MySQL service aktif di Laragon  
✅ PHP 8.2+ dan Composer tersedia  

---

## 🎯 Setup Steps

### 1️⃣ **Import Database**

**Pilih salah satu cara:**

#### Via HeidiSQL (RECOMMENDED):
1. Buka Laragon → Klik kanan icon → **MySQL** → **HeidiSQL**
2. Di HeidiSQL: **File** → **Run SQL file...**
3. Browse ke: `C:\laragon\www\hikettrip-rental\database\hikettrip_rental.sql`
4. Klik **Open** → Tunggu sampai selesai ✅

#### Via MySQL CLI:
1. Buka Command Prompt di folder ini
2. Jalankan:
```bash
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe -u root < C:\laragon\www\hikettrip-rental\database\hikettrip_rental.sql
```

#### Via phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Login (user: root, pass: kosong)
3. Klik **Import** → Browse file SQL → **Go**

---

### 2️⃣ **Install Dependencies**

Buka Command Prompt/PowerShell di folder ini, lalu:

```bash
# Install PHP dependencies
composer install

# Install Node dependencies & build assets
npm install
npm run build
```

Atau jalankan file batch:
```bash
setup.bat
```

---

### 3️⃣ **Generate Storage Link**

```bash
php artisan storage:link
```

---

### 4️⃣ **Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

### 5️⃣ **Start Server**

```bash
php artisan serve
```

Atau double-click file: **`start.bat`**

---

## 🌐 Akses Website

Buka browser: **http://localhost:8000**

### Login Demo:
- **Admin:** admin@hikettrip.com / admin123
- **Customer:** customer@hikettrip.com / customer123

---

## 📁 Struktur Project

```
rental_hiking/
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Eloquent models
│   └── Helpers/helpers.php   # Custom helper functions
├── resources/views/
│   ├── layouts/              # Master layouts
│   ├── auth/                 # Login & register
│   ├── katalog/              # Product catalog
│   ├── paket/                # Package rental
│   └── booking/              # Booking form
├── public/
│   ├── assets/               # CSS, JS, images
│   └── uploads/              # User uploads
├── routes/web.php            # All routes
└── .env                      # Environment config
```

---

## 🔧 Troubleshooting

### Database Connection Error
```bash
# Check .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hikettrip_rental
DB_USERNAME=root
DB_PASSWORD=
```

### Class Not Found
```bash
composer dump-autoload
```

### View Not Found
```bash
php artisan view:clear
```

### Permission Denied (storage/cache)
```bash
# Windows (run as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

---

## ✨ Fitur Utama

✅ **Katalog Produk** - Search, filter, sort dengan pagination  
✅ **Detail Alat** - Kalkulator harga real-time  
✅ **Paket Sewa** - Bundle packages hemat  
✅ **Booking System** - Anti double booking  
✅ **Stock Management** - Real-time availability check  
✅ **Customer Dashboard** - Track transaksi  
✅ **Admin Panel** - Kelola semua data  
✅ **Upload Dokumen** - Jaminan KTP/SIM  
✅ **Responsive Design** - Mobile-friendly  

---

## 🆘 Need Help?

Jika ada masalah, check:
1. Laragon MySQL service running
2. Database sudah diimport
3. File .env sudah benar
4. Composer dan NPM dependencies terinstall

---

**Happy Coding! 🎉**

Website HIKETRIP RENTAL siap digunakan!
