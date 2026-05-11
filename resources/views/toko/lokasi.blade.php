@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 📍 Set Titik Kordinat Toko </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="card-title mb-4">Penguncian Lokasi Asli (Titik A)</h4>
                
                <div class="p-3 bg-light rounded mb-4 border">
                    <h3 class="text-primary">{{ $toko->nama_toko }}</h3>
                    <p class="text-dark font-weight-bold mb-0">Barcode: {{ $toko->barcode }}</p>
                    <p class="text-muted small mt-2">Pastikan Admin berada tepat di lokasi toko ini sebelum mengambil titik kordinat.</p>
                </div>

                <form action="{{ route('toko.update_lokasi', $toko->id) }}" method="POST" id="formLokasi">
                    @csrf
                    
                    <div class="row text-left">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Latitude</label>
                            <input type="text" class="form-control bg-white" name="latitude" id="lat" value="{{ $toko->latitude }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Longitude</label>
                            <input type="text" class="form-control bg-white" name="longitude" id="lng" value="{{ $toko->longitude }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Akurasi (Meter)</label>
                            <input type="text" class="form-control bg-white text-danger font-weight-bold" name="accuracy" id="acc" value="{{ $toko->accuracy ?? '50' }}" required>
                        </div>
                    </div>

                    <div id="loadingInfo" class="d-none mt-3 mb-3">
                        <div class="spinner-grow text-danger" role="status" style="width: 3rem; height: 3rem;"></div>
                        <h5 class="text-danger mt-3" id="loadingText">Mencari sinyal satelit paling akurat...</h5>
                        <p class="text-muted small">Jangan tutup halaman ini.</p>
                    </div>

                    <p class="text-muted small mt-2">💡 Tips: Jika tombol otomatis gagal/tidak bereaksi, Anda bisa copy-paste koordinat dari Google Maps secara manual.</p>

                    <button type="button" id="btnAmbilLokasi" class="btn btn-warning btn-lg w-100 mb-3 font-weight-bold text-dark">
                        <i class="mdi mdi-crosshairs-gps"></i> Coba Ambil Otomatis via GPS
                    </button>

                    <button type="submit" id="btnSimpan" class="btn btn-primary btn-lg w-100 font-weight-bold">
                        <i class="mdi mdi-content-save"></i> Kunci Permanen & Simpan
                    </button>
                    
                    <a href="{{ route('toko.index') }}" class="btn btn-light btn-lg w-100 mt-2">Batal / Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // FUNGSI JURUS 1: NYARI SINYAL AKURAT (Dari Lampiran 1 Modul)
    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
        return new Promise((resolve, reject) => {
            let bestResult = null;
            const startTime = Date.now();

            const watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const acc = position.coords.accuracy;
                    document.getElementById('loadingText').innerText = "Mendeteksi sinyal... Akurasi saat ini: " + Math.round(acc) + " meter";
                    
                    if (!bestResult || acc < bestResult.coords.accuracy) {
                        bestResult = position;
                    }
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        resolve(bestResult);
                    }
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) resolve(bestResult);
                        else reject(new Error("Timeout GPS. Gagal mencari sinyal."));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
        });
    }

    // Saat tombol "Ambil Titik Lokasi" diklik
    document.getElementById('btnAmbilLokasi').addEventListener('click', async function() {
        this.classList.add('d-none'); // Sembunyikan tombol
        document.getElementById('loadingInfo').classList.remove('d-none'); // Munculkan loading

        try {
            const pos = await getAccuratePosition(50, 20000);
            
            // Masukkan data ke form
            const latAsli = pos.coords.latitude;
            const lngAsli = pos.coords.longitude;
            const accAsli = Math.round(pos.coords.accuracy);

            if(!latAsli || !lngAsli) {
                document.getElementById('loadingInfo').classList.add('d-none');
                document.getElementById('btnAmbilLokasi').classList.remove('d-none');
                Swal.fire('Sensor Null', 'GPS merespons tapi tidak mengirim titik kordinat. Laptop Anda memblokir data Latitude. Silakan pakai Google Maps manual.', 'warning');
            } else {
                document.getElementById('lat').value = latAsli;
                document.getElementById('lng').value = lngAsli;
                document.getElementById('acc').value = accAsli;

                document.getElementById('loadingInfo').classList.add('d-none');
                Swal.fire('Sinyal Didapat!', 'Kordinat sukses diambil. Silakan klik Kunci & Simpan.', 'success');
            }

        } catch (error) {
            document.getElementById('loadingInfo').classList.add('d-none');
            document.getElementById('btnAmbilLokasi').classList.remove('d-none');
            
            Swal.fire('Gagal GPS', 'Tolong nyalakan Location di pengaturan Windows Anda atau copy-paste manual dari Google Maps. Error: ' + error.message, 'error');
        }
    });
</script>
@endsection