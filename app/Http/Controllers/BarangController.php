<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf; // Nanti kita aktifkan di langkah cetak PDF

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function cetakTag(Request $request)
    {
        // 1. Cek apakah ada barang yang dicentang
        if (!$request->has('id_barang')) {
            return redirect()->back()->with('error', 'Pilih minimal 1 barang untuk dicetak!');
        }

        // 2. Ambil detail data barang dari database
        $barangDipilih = Barang::whereIn('id_barang', $request->id_barang)->get();

        // 3. Algoritma mencari jumlah Kotak Kosong (Skip)
        // Kertas TnJ 108 memiliki 5 kolom per baris
        $skip = (($request->y - 1) * 5) + ($request->x - 1);

        // 4. Bungkus data untuk dikirim ke PDF
        $data = [
            'barang' => $barangDipilih,
            'skip' => $skip
        ];

        // 5. Cetak ke PDF
        $pdf = Pdf::loadView('barang.pdf', $data)->setPaper('a4', 'portrait');
        
        // Pakai stream agar PDF-nya terbuka langsung di browser, tidak otomatis terdownload
        return $pdf->stream('Tag_Harga_TnJ108.pdf');
    }

    // ... (kodingan index dan cetakTag biarkan saja di atas) ...

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        // id_barang tidak perlu diisi karena sudah otomatis dibuatkan oleh Trigger Database
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Cari barang berdasarkan id_barang (karena primary key kita bentuknya string, pakai where)
        $barang = Barang::where('id_barang', $id)->firstOrFail();
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        $barang = Barang::where('id_barang', $id)->firstOrFail();
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::where('id_barang', $id)->firstOrFail();
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }
}