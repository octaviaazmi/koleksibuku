<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Tampilkan daftar kategori
    public function index() {
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }

    // Form tambah kategori
    public function create() {
        return view('kategori.create');
    }

    // Simpan ke database
    public function store(Request $request) {
        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);
        return redirect('/kategori');
    }

    // Fungsi untuk nampilin halaman edit
    public function edit($id)
    {
        $kategori = \App\Models\Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    // Fungsi untuk proses update ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required',
        ]);

        $kategori = \App\Models\Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect('/kategori')->with('success', 'Kategori berhasil diubah!');
    }

    public function destroy($id)
    {
        $kategori = \App\Models\Kategori::findOrFail($id);
        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}