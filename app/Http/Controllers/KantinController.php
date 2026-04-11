<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KantinController extends Controller
{
    // 1. Tampilan Halaman Awal Kasir
    public function index()
    {
        $vendors = Vendor::all(); // Ambil semua data penjual (Bu Siti & Warkop)
        return view('kantin.index', compact('vendors'));
    }

    // 2. API untuk ambil Menu berdasarkan Vendor (Pakai AJAX nanti)
    public function getMenu($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)->get();
        return response()->json(['status' => 'success', 'data' => $menus]);
    }

    // 3. Proses Checkout & Bikin Order ID
    public function checkout(Request $request)
    {
        DB::beginTransaction(); // Biar aman kalau error
        try {
            // A. Bikin ID Pesanan Unik (Format: ORD-Tanggal-Random)
            // Contoh: ORD-20260411-ABC12
            $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            
            // B. Bikin Nama Guest Otomatis (Contoh: Guest_0000001)
            // Kita hitung jumlah pesanan yang udah ada, lalu tambah 1
            $urutan = Pesanan::count() + 1;
            $namaGuest = 'Guest_' . str_pad($urutan, 7, '0', STR_PAD_LEFT);

            // C. Simpan ke tabel pesanan (Master)
            $pesanan = Pesanan::create([
                'idpesanan' => $orderId,
                'nama' => $namaGuest,
                'total' => $request->total,
                'status_bayar' => 'Pending' // Defaultnya pending
            ]);

            // D. Looping simpan ke tabel detail_pesanan
            foreach($request->keranjang as $item) {
                DetailPesanan::create([
                    'idpesanan' => $orderId,
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit(); // Konfirmasi sukses

            // Karena kita belum pasang Midtrans, kita balikin JSON sukses dulu
            return response()->json([
                'status' => 'success', 
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $orderId // Kirim balik ID-nya buat dipakai nanti
            ]);

        } catch (\Exception $e) {
            DB::rollback(); // Batalin kalau error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}