<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Memastikan halaman ini cuma bisa dibuka kalau sudah login (Sesuai modul poin 4.c.ii)
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan halaman dashboard (home.blade.php)
    public function index()
    {
        return view('home');
    }
}