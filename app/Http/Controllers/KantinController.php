<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// WAJIB DITAMBAHKAN UNTUK MEMANGGIL MIDTRANS
use Midtrans\Config;
use Midtrans\Snap;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;

class KantinController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all(); 
        return view('kantin.index', compact('vendors'));
    }

    public function getMenu($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)->get();
        return response()->json(['status' => 'success', 'data' => $menus]);
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction(); 
        try {
            $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $urutan = Pesanan::count() + 1;
            $namaGuest = 'Guest_' . str_pad($urutan, 7, '0', STR_PAD_LEFT);

            // 1. Simpan Pesanan (Master)
            $pesanan = Pesanan::create([
                'idpesanan' => $orderId,
                'nama' => $namaGuest,
                'total' => $request->total,
                'status_bayar' => 'Pending' 
            ]);

            // 2. Simpan Detail Pesanan (Keranjang)
            foreach($request->keranjang as $item) {
                DetailPesanan::create([
                    'idpesanan' => $orderId,
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            // ==========================================
            // 3. KONFIGURASI MIDTRANS & REQUEST TOKEN
            // ==========================================
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
            Config::$is3ds = env('MIDTRANS_IS_3DS', true);

            // Susun data yang mau dikirim ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $request->total, // Total harga
                ],
                'customer_details' => [
                    'first_name' => $namaGuest, // Nama pemesan
                ],
            ];

            // Minta Token Snap ke Midtrans!
            $snapToken = Snap::getSnapToken($params);

            // Simpan token tersebut ke database kita
            $pesanan->update(['snap_token' => $snapToken]);

            DB::commit(); // Konfirmasi sukses

            // Balikin Token-nya ke Frontend biar bisa ditampilin Pop-upnya
            return response()->json([
                'status' => 'success', 
                'snap_token' => $snapToken 
            ]);

        } catch (\Exception $e) {
            DB::rollback(); 
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Fungsi untuk mengubah status jadi Lunas
    public function paymentSuccess(Request $request)
    {
        $pesanan = Pesanan::where('idpesanan', $request->order_id)->first();
        
        if ($pesanan) {
            $pesanan->update(['status_bayar' => 'Lunas']);

            // --- GENERATE QR CODE (VERSI ENDROID 5.x) ---
            $builder = new Builder(
                writer: new SvgWriter(),
                data: $pesanan->idpesanan, // Isi QR Code
                size: 250,
                margin: 10
            );

            $result = $builder->build();
            $base64DataUri = $result->getDataUri();

            return response()->json([
                'status' => 'success',
                'qr_code' => $base64DataUri
            ]);
        }
        
        return response()->json(['status' => 'error']);
    }
}