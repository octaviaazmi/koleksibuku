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

    // (Nanti fungsi editLokasi dan updateLokasi kita buat di Step 3)
}