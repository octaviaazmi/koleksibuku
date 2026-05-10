<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;

class TokoController extends Controller
{
    // Menampilkan halaman List Toko
    public function index()
    {
        $tokos = Toko::latest()->get();
        return view('toko.index', compact('tokos'));
    }

    // Menyimpan Toko Baru (Simulasi Admin nambah toko)
    public function store(Request $request)
    {
        Toko::create([
            'barcode'   => 'TK-' . strtoupper(uniqid()), // Generate barcode acak otomatis
            'nama_toko' => $request->nama_toko,
        ]);

        return redirect()->route('toko.index')->with('success', 'Toko baru berhasil ditambahkan!');
    }

    // Menampilkan halaman Set Lokasi (Titik Awal Toko)
    public function editLokasi($id)
    {
        $toko = Toko::findOrFail($id);
        return view('toko.lokasi', compact('toko'));
    }

    // Menyimpan koordinat GPS ke database
    public function updateLokasi(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        
        $toko->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        return redirect()->route('toko.index')->with('success', 'Titik lokasi Toko berhasil dikunci permanen!');
    }
}