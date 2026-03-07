<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf; 

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function cetakTag(Request $request)
    {
        // cek ada barang yan dicentang/ngga
        if (!$request->has('id_barang')) {
            return redirect()->back()->with('error', 'Pilih minimal 1 barang untuk dicetak!');
        }

        // ambil detail barang dr db
        $barangDipilih = Barang::whereIn('id_barang', $request->id_barang)->get();

        // kertas tnj 5 kolom per baris
        $skip = (($request->y - 1) * 5) + ($request->x - 1);

        // bungkus datanya untuk dikirim ke pdf
        $data = [
            'barang' => $barangDipilih,
            'skip' => $skip
        ];

        // cetak ke pdf
        $pdf = Pdf::loadView('barang.pdf', $data);
        
        // supaya pdfnya lgsg kebuka, ga lgsg kedownload
        return $pdf->stream('Tag_Harga_TnJ108.pdf');
    }

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

        // id_barang tidak perlu diisi karena udah otomatis dibuatin trigger
        Barang::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // cari barang berdasarkan id barang
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