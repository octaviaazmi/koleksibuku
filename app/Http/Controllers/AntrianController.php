<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // Halaman guest (form daftar antrian)
    public function guest()
    {
        return view('antrian.guest');
    }

    // Simpan antrian baru
    public function daftar(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);

        $nomor = (Antrian::max('nomor') ?? 0) + 1;

        $antrian = Antrian::create([
            'nomor'  => $nomor,
            'nama'   => $request->nama,
            'status' => 'menunggu',
        ]);

        // Simpan ke cache biar SSE bisa baca
        $this->refreshCache();

        return response()->json([
            'nomor' => $antrian->nomor,
            'nama'  => $antrian->nama,
        ]);
    }

    // Halaman admin
    public function admin()
    {
        return view('antrian.admin');
    }

    // Halaman papan antrian
    public function papan()
    {
        return view('antrian.papan');
    }

    // Panggil nomor antrian berikutnya
    public function panggil(Request $request)
    {
        $antrian = Antrian::where('status', 'menunggu')
                          ->orderBy('nomor')
                          ->first();

        if (!$antrian) {
            return response()->json(['message' => 'Tidak ada antrian'], 404);
        }

        $antrian->update([
            'status'       => 'dipanggil',
            'dipanggil_at' => now(),
        ]);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }

    // Panggil antrian yang terlambat (dari list terlambat)
    public function panggilTerlambat(Request $request)
    {
        $antrian = Antrian::findOrFail($request->id);
        $antrian->update([
            'status'       => 'dipanggil',
            'dipanggil_at' => now(),
        ]);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }

    // Tandai sebagai terlambat
    public function terlambat(Request $request)
    {
        $antrian = Antrian::findOrFail($request->id);
        $antrian->update(['status' => 'terlambat']);

        $this->refreshCache();

        return response()->json(['success' => true]);
    }

    // Reset semua antrian (opsional, buat admin)
    public function reset()
    {
        Antrian::truncate();
        Cache::forget('antrian_data');

        return response()->json(['success' => true]);
    }

    // SSE Stream — ini yang dikirim ke browser secara real-time
    public function stream()
    {
        return response()->stream(function () {
            set_time_limit(0);

            while (true) {
                $data = Cache::get('antrian_data', [
                    'menunggu'  => [],
                    'dipanggil' => null,
                    'terlambat' => [],
                ]);

                echo 'event: queue-update' . PHP_EOL;
                echo 'data: ' . json_encode($data) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();

                if (connection_aborted()) break;

                sleep(1);
            }
        }, 200, [
            'Content-Type'       => 'text/event-stream',
            'Cache-Control'      => 'no-cache',
            'X-Accel-Buffering'  => 'no',
        ]);
    }

    // Helper: update cache dari DB
    private function refreshCache()
    {
        $dipanggil = Antrian::where('status', 'dipanggil')
                            ->orderByDesc('dipanggil_at')
                            ->first();

        Cache::put('antrian_data', [
            'menunggu'  => Antrian::where('status', 'menunggu')
                                  ->orderBy('nomor')
                                  ->get(['id', 'nomor', 'nama'])
                                  ->toArray(),
            'dipanggil' => $dipanggil ? [
                'id'    => $dipanggil->id,
                'nomor' => $dipanggil->nomor,
                'nama'  => $dipanggil->nama,
            ] : null,
            'terlambat' => Antrian::where('status', 'terlambat')
                                  ->orderBy('nomor')
                                  ->get(['id', 'nomor', 'nama'])
                                  ->toArray(),
        ]);
    }
}