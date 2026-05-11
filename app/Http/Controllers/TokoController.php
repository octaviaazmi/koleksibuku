<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Str;

class TokoController extends Controller
{
    // Halaman list toko
    public function index()
    {
        $tokos = Toko::all();
        return view('toko.index', compact('tokos'));
    }

    // Simpan toko baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric',
        ]);

        Toko::create([
            'barcode'   => 'TOKO-' . strtoupper(Str::random(8)),
            'nama_toko' => $request->nama_toko,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    // Halaman kunjungan (form scan + cek jarak)
    public function kunjungan()
    {
        return view('toko.kunjungan');
    }

    // Proses cek jarak saat kunjungan
    public function cekJarak(Request $request)
    {
        $toko = Toko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json(['error' => 'Toko tidak ditemukan'], 404);
        }

        return response()->json([
            'nama_toko' => $toko->nama_toko,
            'lat_toko'  => $toko->latitude,
            'lng_toko'  => $toko->longitude,
            'acc_toko'  => $toko->accuracy,
        ]);
    }
}