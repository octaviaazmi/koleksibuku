<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// 1. Kalau buka web pertama kali (localhost:8000), langsung diarahkan ke halaman Login
Route::get('/', function () {
    return redirect('/login');
});

// 2. Ini rute otomatis bawaan Laravel Auth (untuk login, register, logout)
Auth::routes();

// 3. Ini rute untuk Dashboard, menggunakan HomeController yang tadi kita buat
Route::get('/home', [HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\GoogleController;

// Rute untuk mengarahkan ke halaman login Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');

// Rute callback setelah login dari Google berhasil
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

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

});
