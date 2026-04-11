<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GeneratorController;

Route::get('/', function () {
    return redirect('/login');
});


Auth::routes();

// rute untuk dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\JsController;
use App\Http\Controllers\KasirController; 
use App\Http\Controllers\KantinController; // Taruh di baris paling atas (use section)

// rute untuk mengarahkan ke halaman login Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');

// rute callback setelah login dari Google berhasil
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// route untuk menampilkan halaman input OTP
Route::get('/otp-verification', function () {
    return view('auth.otp');
})->name('otp.view');

// route untuk memproses pengecekan OTP
Route::post('/verify-otp', [App\Http\Controllers\Auth\GoogleController::class, 'verifyOtp'])->name('otp.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/create', [KategoriController::class, 'create']);
    Route::post('/kategori/store', [KategoriController::class, 'store']);
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit']);
    Route::put('/kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

    Route::get('/buku', [BukuController::class, 'index']);
    Route::get('/buku/create', [BukuController::class, 'create']);
    Route::post('/buku/store', [BukuController::class, 'store']);
    Route::delete('/buku/{id}', [BukuController::class, 'destroy']);
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit']);
    Route::put('/buku/{id}', [BukuController::class, 'update']);

// Pastikan ini ada di DALAM Route::middleware(['auth'])->group(function () { ... });
Route::resource('barang', BarangController::class);
Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTag'])->name('barang.cetak_tag');
Route::get('/js/html', [JsController::class, 'indexHtml'])->name('js.html');
    Route::get('/js/datatables', [JsController::class, 'indexDatatables'])->name('js.datatables');
    Route::get('/js/select', [JsController::class, 'indexSelect'])->name('js.select');

Route::resource('buku', BukuController::class);

Route::get('/generator', [GeneratorController::class, 'index'])->name('generator.index');
Route::get('/generator/undangan', [GeneratorController::class, 'cetakUndangan'])->name('undangan.cetak');
Route::get('/generator/sertifikat', [GeneratorController::class, 'cetakSertifikat'])->name('sertifikat.cetak');

// --- RUTE APLIKASI KASIR MODUL 5 ---
Route::get('/kasir/ajax', [KasirController::class, 'indexAjax'])->name('kasir.ajax');
Route::get('/kasir/axios', [KasirController::class, 'indexAxios'])->name('kasir.axios');

// --- RUTE API (Untuk ngambil & nyimpen data di background) ---
Route::get('/api/barang/{id}', [KasirController::class, 'getBarang']);
Route::post('/api/transaksi', [KasirController::class, 'simpanTransaksi']);


// --- RUTE KANTIN ONLINE MODUL 6 ---
Route::get('/kantin', [KantinController::class, 'index'])->name('kantin.index');
Route::get('/api/menu/{idvendor}', [KantinController::class, 'getMenu']); // Buat narik menu pakai AJAX
Route::post('/kantin/checkout', [KantinController::class, 'checkout'])->name('kantin.checkout');

});
