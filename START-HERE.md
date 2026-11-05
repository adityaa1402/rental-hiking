# 🚀 START HERE - HIKETRIP RENTAL

## ⚡ QUICK START (3 Steps)

### Step 1: Import Database ✅
**Via HeidiSQL (RECOMMENDED):**
1. Buka **Laragon** → Klik kanan icon tray → **MySQL** → **HeidiSQL**
2. Di HeidiSQL: **File** → **Run SQL file...**
3. Browse: `C:\laragon\www\hikettrip-rental\database\hikettrip_rental.sql`
4. Click **Open** dan tunggu selesai

### Step 2: Fix All Errors ✅
**Double-click file:**
```
ULTIMATE-FIX.bat
```

### Step 3: Start Server ✅
**Double-click file:**
```
start.bat
```

**Open browser:**
```
http://localhost:8000
```

---

## 🔐 Login Credentials

**Admin Dashboard:**
- Email: `admin@hikettrip.com`
- Password: `admin123`
- URL: http://localhost:8000/login

**Customer Account:**
- Email: `customer@hikettrip.com`
- Password: `customer123`
- URL: http://localhost:8000/login

---

## 🆘 Having Errors?

### ❌ Error: "Undefined variable"
**Solution:**
```
1. Double-click: ULTIMATE-FIX.bat
2. Close browser COMPLETELY
3. Double-click: start.bat
4. Open NEW browser window
5. Clear browser cache (Ctrl+Shift+Delete)
6. Login again
```

### ❌ Error: "Database connection failed"
**Solution:**
1. Pastikan **Laragon** sudah running (klik Start All)
2. Import database via HeidiSQL (lihat Step 1)
3. Check file `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hikettrip_rental
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### ❌ Error: "Session error" or "Table sessions not found"
**Solution:**
File `.env` sudah benar, jika masih error:
```bash
php artisan session:table
php artisan migrate
```

### ❌ Error: "Class not found"
**Solution:**
```
composer dump-autoload
```

### ❌ Error: "Permission denied"
**Solution:** Run as Administrator:
```
ULTIMATE-FIX.bat (right-click → Run as Administrator)
```

---

## 📁 Important Files

### Start Server:
- **start.bat** ← Double-click this to start

### Fix Errors:
- **ULTIMATE-FIX.bat** ← Fix all errors
- **fix-all-errors.bat** ← Alternative fix
- **clear-cache.bat** ← Clear cache only
- **reset-session.bat** ← Reset sessions

### Documentation:
- **README_SETUP.md** ← Full setup guide
- **TROUBLESHOOTING.md** ← Common errors & fixes
- **START-HERE.md** ← This file!

---

## 🌐 Website Pages

### Public Pages (No Login):
- Homepage: http://localhost:8000
- Katalog: http://localhost:8000/katalog
- Paket: http://localhost:8000/paket
- Tentang: http://localhost:8000/tentang
- Kontak: http://localhost:8000/kontak

### Customer Pages (Login as Customer):
- Dashboard: http://localhost:8000/customer/dashboard
- Transaksi: http://localhost:8000/customer/transaksi
- Profil: http://localhost:8000/customer/profil
- Booking: http://localhost:8000/booking

### Admin Pages (Login as Admin):
- Dashboard: http://localhost:8000/admin/dashboard
- Barang: http://localhost:8000/admin/barang
- Kategori: http://localhost:8000/admin/kategori
- Paket: http://localhost:8000/admin/paket
- Transaksi: http://localhost:8000/admin/transaksi
- Pengembalian: http://localhost:8000/admin/pengembalian
- Customer: http://localhost:8000/admin/customer
- Laporan: http://localhost:8000/admin/laporan
- Pengaturan: http://localhost:8000/admin/pengaturan

---

## ✨ Features

✅ **Katalog Produk** - Search, filter, sort, pagination  
✅ **Detail Alat** - Real-time price calculator  
✅ **Paket Sewa** - Bundle packages  
✅ **Booking System** - Anti double booking  
✅ **Stock Management** - Real-time availability  
✅ **Upload Dokumen** - KTP/SIM/Paspor  
✅ **Customer Dashboard** - Track transactions  
✅ **Admin Panel** - Full management system  
✅ **Responsive Design** - Mobile & desktop  

---

## 🔧 Development Commands

### Start development server:
```bash
php artisan serve
# Or double-click: start.bat
```

### Build assets (if modified):
```bash
npm run build
# Or for watch mode:
npm run dev
```

### Clear all cache:
```bash
# Quick way:
ULTIMATE-FIX.bat

# Or manual:
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Database commands:
```bash
# Check connection:
php artisan db:show

# Run migrations:
php artisan migrate

# Rollback:
php artisan migrate:rollback
```

---

## 📊 Tech Stack

- **Laravel 12** - PHP Framework
- **MySQL** - Database
- **Bootstrap 5.3** - CSS Framework
- **Blade** - Template Engine
- **JavaScript** - Interactivity
- **Composer** - PHP Dependencies
- **NPM** - Node Dependencies

---

## 🎯 Project Structure

```
rental_hiking/
├── app/
│   ├── Http/Controllers/     ← All controllers
│   ├── Models/               ← Database models
│   └── Helpers/              ← Custom functions
├── resources/views/          ← Blade templates
├── routes/web.php            ← All routes
├── public/
│   ├── assets/               ← CSS, JS, images
│   └── uploads/              ← User uploads
├── .env                      ← Configuration
├── start.bat                 ← START HERE!
└── ULTIMATE-FIX.bat          ← Fix errors
```

---

## ✅ Checklist

Before starting, make sure:
- [x] Laragon installed & running
- [x] Database imported via HeidiSQL
- [x] `.env` configured
- [x] Composer dependencies installed
- [x] NPM dependencies installed
- [x] All cache cleared
- [x] Server started

---

## 🎉 Ready to Go!

1. **Double-click:** `ULTIMATE-FIX.bat`
2. **Double-click:** `start.bat`
3. **Open browser:** http://localhost:8000
4. **Login** and enjoy!

---

**Website HIKETRIP RENTAL siap digunakan!** 🚀

Need help? Check **TROUBLESHOOTING.md**
