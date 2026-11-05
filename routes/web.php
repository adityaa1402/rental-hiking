<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\TransaksiController as CustomerTransaksi;
use App\Http\Controllers\Customer\ProfilController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PaketAdminController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksi;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [HomeController::class, 'kontakSend'])->name('kontak.send');

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{slug}', [KatalogController::class, 'show'])->name('katalog.show');

Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
Route::get('/paket/{slug}', [PaketController::class, 'show'])->name('paket.show');

// ============================================================
// AUTH ROUTES (Guest Only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// BOOKING (Auth required)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// ============================================================
// CUSTOMER ROUTES
// ============================================================
Route::prefix('customer')->name('customer.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [CustomerTransaksi::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [CustomerTransaksi::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{id}/batalkan', [CustomerTransaksi::class, 'batalkan'])->name('transaksi.batalkan');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Barang (Alat Hiking)
    Route::resource('barang', BarangController::class)->parameters(['barang' => 'id']);

    // Kategori
    Route::resource('kategori', KategoriController::class)->parameters(['kategori' => 'id']);

    // Paket Sewa
    Route::resource('paket', PaketAdminController::class)->parameters(['paket' => 'id']);

    // Transaksi
    Route::get('/transaksi', [AdminTransaksi::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [AdminTransaksi::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{id}/approve', [AdminTransaksi::class, 'approve'])->name('transaksi.approve');
    Route::post('/transaksi/{id}/reject', [AdminTransaksi::class, 'reject'])->name('transaksi.reject');
    Route::post('/transaksi/{id}/start', [AdminTransaksi::class, 'start'])->name('transaksi.start');
    Route::post('/transaksi/{id}/complete', [AdminTransaksi::class, 'complete'])->name('transaksi.complete');

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::post('/pengembalian/{id}/proses', [PengembalianController::class, 'proses'])->name('pengembalian.proses');

    // Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});
