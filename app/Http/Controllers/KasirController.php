<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // Mengarahkan ke halaman AJAX
    public function indexAjax() {
        return view('kasir.ajax');
    }

    // Mengarahkan ke halaman Axios
    public function indexAxios() {
        return view('kasir.axios');
    }

    // Fungsi API: Mencari barang saat ditekan ENTER
    public function getBarang($id) {
        $barang = Barang::where('id_barang', $id)->first();
        
        if($barang) {
            return response()->json(['status' => 'success', 'data' => $barang]);
        }
        return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan!']);
    }

    // Fungsi API: Menyimpan Keranjang Belanja ke Database
    public function simpanTransaksi(Request $request) {
        DB::beginTransaction(); // Fitur keamanan: Jika error di tengah jalan, database batal disimpan
        try {
            // 1. Simpan ke tabel penjualan dulu
            $penjualan = Penjualan::create([
                'total' => $request->total
            ]);

            // 2. Looping (Ulangi) untuk menyimpan setiap barang di keranjang ke tabel penjualan_detail
            foreach($request->keranjang as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit(); // Konfirmasi sukses
            return response()->json(['status' => 'success', 'message' => 'Transaksi berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollback(); // Batalkan semua jika ada error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}