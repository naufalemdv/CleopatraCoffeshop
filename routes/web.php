<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\StatusMejaController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatusPesananController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE PELANGGAN (PUBLIC & SELF-SERVICE) ---
Route::get('/order/{no_meja}', [OrderController::class, 'index'])->name('order.index');
Route::post('/order/cart/add', [OrderController::class, 'addToCart'])->name('order.cart.add');

// Rute Keranjang & Checkout (Menggunakan Session, tidak butuh no_meja di URL lagi)
Route::get('/cart', [OrderController::class, 'cart'])->name('order.cart');
Route::post('/cart/remove', [OrderController::class, 'removeFromCart'])->name('order.cart.remove');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/success', [OrderController::class, 'success'])->name('order.success');
// Rute Baru Untuk Cetak PDF
Route::get('/order/pelacakan/{id}/pdf', [OrderController::class, 'downloadPDF'])->name('order.download-pdf');

// --- RUTE AUTENTIKASI ---
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () { 
    return redirect('/admin/dashboard'); 
});

// --- GRUP ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard'); 
    Route::get('/kasir', [KasirController::class, 'index'])->name('admin.kasir');
    Route::post('/kasir/proses', [KasirController::class, 'store'])->name('admin.kasir.store');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    Route::get('/meja', [MejaController::class, 'index'])->name('meja.index');
    Route::post('/meja', [MejaController::class, 'store'])->name('meja.store');
    Route::put('/meja/{id}', [MejaController::class, 'update'])->name('meja.update');
    Route::delete('/meja/{id}', [MejaController::class, 'destroy'])->name('meja.destroy');
    Route::get('/meja/{id}/qr', [MejaController::class, 'printQR'])->name('meja.qr');
    
    Route::get('/status-meja', [StatusMejaController::class, 'index'])->name('admin.status-meja');
    Route::post('/status-meja/{id}/update', [StatusMejaController::class, 'updateStatus'])->name('admin.status-meja.update');

    Route::get('/status-pesanan', [StatusPesananController::class, 'index'])->name('admin.status-pesanan');
    Route::patch('/status-pesanan/{id}/selesai', [StatusPesananController::class, 'updateToSelesai'])->name('admin.status-pesanan.update');
    Route::patch('/status-pesanan/{id}/diproses', [StatusPesananController::class, 'updateToDiproses'])->name('admin.status-pesanan.diproses');
    Route::delete('/status-pesanan/{id}', [StatusPesananController::class, 'destroy'])->name('admin.status-pesanan.destroy');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi'); 
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('admin.transaksi.destroy');
    Route::get('/transaksi/{id}/print', [TransaksiController::class, 'print'])->name('admin.transaksi.print');
    Route::patch('/transaksi/{id}/bayar', [TransaksiController::class, 'markAsPaid'])->name('admin.transaksi.bayar');
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan'); 
});