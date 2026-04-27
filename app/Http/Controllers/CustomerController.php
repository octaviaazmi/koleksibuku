<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    // 1. Tampilkan Tabel Data Customer
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    // 2. Tampilkan Halaman Kamera Blob
    public function createBlob()
    {
        return view('customer.create-blob');
    }

    // 3. Tampilkan Halaman Kamera File
    public function createFile()
    {
        return view('customer.create-file');
    }

    // --- FUNGSI UNTUK MENYIMPAN GAMBAR BLOB (BASE64) ---
    public function storeBlob(Request $request)
    {
        Customer::create([
            'nama' => $request->nama,
            'foto_blob' => $request->foto_base64 // Langsung simpan string base64 panjangnya
        ]);
        return redirect()->route('customer.index')->with('success', 'Foto Customer (BLOB) berhasil disimpan!');
    }

    // --- FUNGSI UNTUK MENYIMPAN GAMBAR SEBAGAI FILE FISIK ---
    public function storeFile(Request $request)
    {
        $fotoPath = null;

        if ($request->foto_base64) {
            // Karena dari JS kita dapatnya teks Base64, kita harus "Decode" jadi file beneran
            $image_parts = explode(";base64,", $request->foto_base64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // Bikin nama file random biar nggak bentrok
            $fileName = 'customer_' . Str::random(10) . '.' . $image_type;

            // Simpan ke storage Laravel (Folder: public/storage/customers/)
            Storage::disk('public')->put('customers/' . $fileName, $image_base64);
            
            $fotoPath = 'customers/' . $fileName;
        }

        Customer::create([
            'nama' => $request->nama,
            'foto_path' => $fotoPath // Simpan alamat URL foldernya saja
        ]);

        return redirect()->route('customer.index')->with('success', 'Foto Customer (FILE) berhasil disimpan!');
    }
}