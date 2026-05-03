<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;

class VendorController extends Controller
{
    // Menampilkan Dashboard Vendor
    public function index(Request $request)
    {
        // Biar gampang ngetes, kita bisa milih lagi login sebagai vendor mana (Default: 1 / Bu Siti)
        $vendor_id = $request->vendor_id ?? 1; 
        $vendor_aktif = Vendor::find($vendor_id);
        $semua_vendor = Vendor::all();

        // 1. Ambil daftar menu khusus milik vendor ini
        $menus = Menu::where('idvendor', $vendor_id)->get();

        // 2. Ambil daftar pesanan yang LUNAS dan HANYA berisi menu dari vendor ini
        $pesanan_lunas = Pesanan::with(['detail.menu'])
            ->where('status_bayar', 'Lunas')
            ->whereHas('detail.menu', function($q) use ($vendor_id) {
                $q->where('idvendor', $vendor_id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('vendor.index', compact('menus', 'pesanan_lunas', 'vendor_aktif', 'semua_vendor', 'vendor_id'));
    }

    // Fungsi untuk menambah Menu Baru
    public function storeMenu(Request $request)
    {
        Menu::create([
            'idvendor' => $request->idvendor,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga
        ]);

        return back()->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // Menampilkan halaman scanner QR Code
    public function scan()
    {
        return view('vendor.scan');
    }

    // Mengambil data pesanan via AJAX setelah QR di-scan
    public function getPesananById($idpesanan)
    {
        // Cari pesanan berdasarkan idpesanan
        $pesanan = \App\Models\Pesanan::where('idpesanan', $idpesanan)->first();
        
        if ($pesanan) {
            return response()->json([
                'status' => 'success', 
                'data' => $pesanan
            ]);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan!']);
    }
}