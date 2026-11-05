# 🔧 TROUBLESHOOTING GUIDE - HIKETRIP RENTAL

## 🚨 Error "Undefined variable"

Jika kamu mendapat error **"Undefined variable"**, ikuti langkah berikut:

### ✅ Step 1: Clear All Cache
Double-click file:
```
fix-all-errors.bat
```

Atau manual:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear
composer dump-autoload
```

### ✅ Step 2: Clear Browser Cache
1. Tekan `Ctrl + Shift + Delete`
2. Pilih **"Cookies and other site data"**
3. Pilih **"Cached images and files"**
4. Klik **Clear data**

### ✅ Step 3: Delete Session Files
```bash
# Manual delete
del storage\framework\sessions\*
del storage\framework\views\*
```

### ✅ Step 4: Restart Everything
1. **Stop server** (Ctrl+C jika sedang running)
2. **Close browser completely**
3. **Start server** lagi: `php artisan serve`
4. **Open browser** (new window)
5. **Login** lagi

---

## 🔍 Common Variable Issues & Fixes

### Issue 1: User ID tidak ditemukan
**Error:** `Column 'id' not found`

**Fix:** Model User sudah diupdate pakai `id_user` sebagai primary key.

### Issue 2: Undefined $transaksis / $transaksiList
**Fix:** Semua controller sudah diupdate pakai variable name yang konsisten: `$transaksis`

### Issue 3: Relationship tidak ditemukan
**Error:** `Call to undefined relationship [alatHiking]`

**Fix:** Semua model relationship sudah ditambahkan alias:
- `DetailPaket` → ada `alatHiking()`
- `DetailTransaksi` → ada `alatHiking()`

### Issue 4: Session error
**Error:** `Table 'sessions' doesn't exist`

**Fix:** `.env` sudah diubah ke `SESSION_DRIVER=file`

---

## 📝 Variable Name Consistency

Berikut adalah variable names yang BENAR di semua file:

### Customer Controllers:
- **DashboardController**: `$stats`, `$recentTransaksis`
- **TransaksiController**: `$transaksis` (index), `$transaksi` (show)
- **ProfilController**: `$user`

### Admin Controllers:
- **DashboardController**: `$stats`, `$unitDisewa`, `$kembaliHariIni`, `$grafikData`, `$recentTransaksis`
- **TransaksiController**: `$transaksis` (index), `$transaksi` (show)
- **BarangController**: `$items` atau `$barangs`
- **KategoriController**: `$kategoris`

### Public Controllers:
- **HomeController**: `$kategoriList`, `$produkPopuler`, `$paketList`
- **KatalogController**: `$items`, `$allKategori`
- **PaketController**: `$packages`
- **BookingController**: `$selectedItem`, `$selectedPackage`, `$packageItems`

---

## 🔑 Database Primary Keys

Pastikan menggunakan primary key yang benar:

```php
User          → id_user
AlatHiking    → id_alat
Kategori      → id_kategori
Transaksi     → id_transaksi
DetailTransaksi → id_detail
PaketSewa     → id_paket
DetailPaket   → id_detail_paket
Pengembalian  → id_pengembalian
DokumenJaminan → id_dokumen
Pengaturan    → id_pengaturan
```

---

## 🎯 Common Fixes

### Clear everything at once:
```bash
# Run this batch file
fix-all-errors.bat
```

### Force reload without cache:
1. Browser: `Ctrl + Shift + R` (hard reload)
2. Or: `Ctrl + F5`

### Check if MySQL running:
- Open Laragon
- Click "Start All"
- Make sure MySQL service is green

### Test database connection:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

---

## 📞 Still Having Issues?

### Check these files:
1. `.env` → Database config correct?
2. `composer.json` → Autoload helpers?
3. `app/Models/User.php` → Primary key = id_user?

### Debug mode:
```env
# .env
APP_DEBUG=true
```

### View error details:
Error akan tampil lengkap di browser saat `APP_DEBUG=true`

---

## ✅ Final Checklist

- [ ] Laragon MySQL running
- [ ] Database imported
- [ ] `.env` configured correctly
- [ ] `composer install` done
- [ ] `npm run build` done
- [ ] All cache cleared
- [ ] Browser cache cleared
- [ ] Session files deleted
- [ ] Server restarted
- [ ] Logged in with fresh session

---

**If all else fails:**
1. Stop server
2. Run `fix-all-errors.bat`
3. Close browser completely
4. Start server: `php artisan serve`
5. Open new browser window
6. Try again!

🎉 **Website seharusnya sudah bisa jalan!**
