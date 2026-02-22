<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuController extends Controller
{
    public function index() {
        $buku = Buku::with('kategori')->get(); // Mengambil data buku beserta kategorinya
        return view('buku.index', compact('buku'));
    }

    public function create() {
        $kategori = Kategori::all(); // Untuk pilihan di dropdown
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request) {
        Buku::create($request->all());
        return redirect('/buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function destroy($id) {
        Buku::findOrFail($id)->delete();
        return redirect('/buku');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all(); // Tetap butuh list kategori buat dropdown
        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        return redirect('/buku')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function cetak_pdf()
    {
        // Mengambil semua data dari tabel buku
        $buku = buku::all();

        // Memanggil view bernama pdf.blade.php yang ada di folder resources/views/buku/
        $pdf = Pdf::loadview('buku.pdf', ['buku' => $buku]);

        // Mendownload file PDF-nya
        return $pdf->download('laporan-buku-perpustakaan.pdf');
    }
}