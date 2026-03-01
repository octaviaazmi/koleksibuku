<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Pastikan hanya user yang sudah login yang bisa masuk sini
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan Dashboard dengan data sungguhan
     */
    public function index()
    {
        // Menghitung jumlah masing-content di database
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalBarang = Barang::count();
        $totalUser = User::count();

        // Melempar datanya ke file tampilan (view)
        return view('home', compact('totalBuku', 'totalKategori', 'totalBarang', 'totalUser'));
    }
}