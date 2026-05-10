@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 📍 Set Titik Lokasi Toko (Titik A) </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="card-title mb-4">Informasi Toko / Klien</h4>
                
                <div class="p-3 bg-light rounded mb-4 border">
                    <h3 class="text-primary">{{ $customer->nama }}</h3>
                    <p class="text-muted mb-0">Pastikan Anda sedang berada tepat di lokasi toko ini sebelum mengambil titik kordinat.</p>
                </div>

                <form action="{{ route('customer.update_lokasi', $customer->id) }}" method="POST" id="formLokasi">
                    @csrf
                    
                    <div class="row text-left">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Latitude</label>
                            <input type="text" class="form-control form-control-lg bg-white" name="latitude" id="lat" value="{{ $customer->latitude }}" readonly placeholder="Belum ada data" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Longitude</label>
                            <input type="text" class="form-control form-control-lg bg-white" name="longitude" id="lng" value="{{ $customer->longitude }}" readonly placeholder="Belum ada data" required>
                        </div>
                    </div>

                    <div id="loadingInfo" class="d-none mt-3 mb-3">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-primary mt-2">Sedang mencari sinyal satelit paling akurat... Mohon tunggu & jangan tutup halaman.</p>
                    </div>

                    <button type="button" id="btnAmbilLokasi" class="btn btn-primary btn-lg w-100 mb-2 font-weight-bold">
                        <i class="mdi mdi-crosshairs-gps"></i> Kunci Titik Lokasi Sekarang!
                    </button>

                    <button type="submit" id="btnSimpan" class="btn btn-success btn-lg w-100 d-none font-weight-bold">
                        <i class="mdi mdi-content-save"></i> Simpan Lokasi Permanen
                    </button>
                    
                    <a href="{{ route('customer.index') }}" class="btn btn-light btn-lg w-100 mt-2">Batal / Kembali</a>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
    // FUNGSI JURUS 1: NYARI SINYAL AKURAT (Dari Lampiran 1 Modul)
    function getAccuratePosition(targetAccuracy = 50, maxWait = 10000) {
        return new Promise((resolve, reject) => {
            let watchId;
            let bestResult = null;
            const startTime = Date.now();

            watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const acc = position.coords.accuracy;
                    console.log("Mendeteksi sinyal... Akurasi saat ini: " + acc + " meter");
                    
                    // Kalau belum ada bestResult, atau akurasi sekarang lebih baik (nilainya lebih kecil)
                    if (!bestResult || acc < bestResult.coords.accuracy) {
                        bestResult = position;
                    }

                    // Kalau sudah cukup akurat sesuai target, hentikan pencarian!
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        resolve(bestResult);
                    }

                    // Kalau terlalu lama (timeout), pakai hasil terbaik yang sempat ketangkap
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) resolve(bestResult);
                        else reject(new Error("Waktu habis, gagal mendapatkan posisi yang akurat."));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
        });
    }

    // Saat tombol diklik
    document.getElementById('btnAmbilLokasi').addEventListener('click', async function() {
        // Tampilkan loading, sembunyikan tombol
        this.classList.add('d-none');
        document.getElementById('loadingInfo').classList.remove('d-none');

        try {
            // Target akurasi 50 meter, maksimal nunggu 10 detik
            const pos = await getAccuratePosition(50, 10000);
            
            // Masukkan data ke dalam form input
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lng').value = pos.coords.longitude;

            // Sembunyikan loading, munculkan tombol Simpan
            document.getElementById('loadingInfo').classList.add('d-none');
            document.getElementById('btnSimpan').classList.remove('d-none');
            
            Swal.fire('Berhasil!', 'Sinyal didapatkan dengan tingkat akurasi: ' + Math.round(pos.coords.accuracy) + ' meter.', 'success');

        } catch (error) {
            // Sembunyikan loading, kembalikan tombol awal
            document.getElementById('loadingInfo').classList.add('d-none');
            this.classList.remove('d-none');
            
            Swal.fire('Gagal GPS', 'Tolong pastikan fitur Lokasi/GPS di HP atau laptop Anda menyala, dan beri izin browser. Error: ' + error.message, 'error');
        }
    });
</script>
@endsection