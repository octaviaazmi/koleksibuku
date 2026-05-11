@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">📍 Titik Kunjungan Sales</h3>
</div>

<div class="row">
    {{-- Kolom Kiri: Scan & Input --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="card-title">Scan Barcode Toko</h4>
                <p class="card-description">Scan barcode toko yang akan dikunjungi.</p>

                {{-- Area Scanner --}}
                <div id="reader" style="width:100%; border-radius:8px; overflow:hidden;"></div>

                <div class="form-group mt-3">
                    <label>Atau masukkan Barcode manual:</label>
                    <div class="input-group">
                        <input type="text" id="barcode_input" class="form-control" placeholder="Contoh: TOKO-XXXXXXXX">
                        <button class="btn btn-primary" onclick="cariToko()">Cari</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Toko --}}
        <div class="card shadow-sm border-0 mb-4" id="card_toko" style="display:none;">
            <div class="card-body">
                <h4 class="card-title">Info Toko</h4>
                <table class="table table-sm">
                    <tr><th>Nama Toko</th><td id="info_nama">-</td></tr>
                    <tr><th>Latitude Toko</th><td id="info_lat">-</td></tr>
                    <tr><th>Longitude Toko</th><td id="info_lng">-</td></tr>
                    <tr><th>Accuracy Toko</th><td id="info_acc">-</td></tr>
                </table>
                <button class="btn btn-secondary w-100" onclick="ambilLokasiSales()">
                    <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Saya Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Hasil --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0" id="card_hasil" style="display:none;">
            <div class="card-body text-center">
                <h4 class="card-title">Hasil Kunjungan</h4>

                <div id="hasil_badge" class="my-4">
                    {{-- Badge DITERIMA / DITOLAK muncul di sini --}}
                </div>

                <table class="table table-sm text-left mt-3">
                    <tr><th>Posisi Sales (Lat)</th><td id="res_lat_sales">-</td></tr>
                    <tr><th>Posisi Sales (Lng)</th><td id="res_lng_sales">-</td></tr>
                    <tr><th>Accuracy Sales</th><td id="res_acc_sales">-</td></tr>
                    <tr><th>Jarak ke Toko</th><td id="res_jarak">-</td></tr>
                    <tr><th>Threshold Efektif</th><td id="res_threshold">-</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const THRESHOLD = 300; // meter, bisa diubah

let dataTokoAktif = null;

// ── Scanner ──────────────────────────────────────────
const html5QrCode = new Html5Qrcode("reader");
html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: { width: 250, height: 250 } },
    (decodedText) => {
        document.getElementById('barcode_input').value = decodedText;
        html5QrCode.stop();
        cariToko();
    },
    (err) => {}
).catch(err => console.log('Kamera tidak tersedia:', err));

// ── Cari Toko by Barcode ─────────────────────────────
function cariToko() {
    const barcode = document.getElementById('barcode_input').value.trim();
    if (!barcode) return alert('Masukkan barcode dulu!');

    fetch('{{ route("toko.cekJarak") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ barcode })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) return alert(data.error);
        dataTokoAktif = data;

        document.getElementById('info_nama').innerText = data.nama_toko;
        document.getElementById('info_lat').innerText  = data.lat_toko;
        document.getElementById('info_lng').innerText  = data.lng_toko;
        document.getElementById('info_acc').innerText  = data.acc_toko + ' m';
        document.getElementById('card_toko').style.display = 'block';
    })
    .catch(() => alert('Gagal menghubungi server.'));
}

// ── Ambil Lokasi Sales ───────────────────────────────
function getAccuratePosition(onSuccess, onError, options = {}) {
    const { desiredAccuracy = 50, maxWait = 10000 } = options;
    let best = null;
    let watchId;
    const timer = setTimeout(() => {
        navigator.geolocation.clearWatch(watchId);
        best ? onSuccess(best) : onError({ code: 3, message: 'Timeout.' });
    }, maxWait);

    watchId = navigator.geolocation.watchPosition(pos => {
        if (!best || pos.coords.accuracy < best.coords.accuracy) best = pos;
        if (best.coords.accuracy <= desiredAccuracy) {
            clearTimeout(timer);
            navigator.geolocation.clearWatch(watchId);
            onSuccess(best);
        }
    }, onError, { enableHighAccuracy: true });
}

function ambilLokasiSales() {
    if (!dataTokoAktif) return alert('Cari toko dulu!');
    alert('📍 Mengambil lokasi kamu, tunggu sebentar...');

    getAccuratePosition(
        pos => {
            const latSales = pos.coords.latitude;
            const lngSales = pos.coords.longitude;
            const accSales = pos.coords.accuracy;

            const jarak = haversine(latSales, lngSales, dataTokoAktif.lat_toko, dataTokoAktif.lng_toko);
            const thresholdEfektif = THRESHOLD + parseFloat(dataTokoAktif.acc_toko) + accSales;
            const diterima = jarak <= thresholdEfektif;

            // Tampilkan hasil
            document.getElementById('res_lat_sales').innerText  = latSales.toFixed(7);
            document.getElementById('res_lng_sales').innerText  = lngSales.toFixed(7);
            document.getElementById('res_acc_sales').innerText  = accSales.toFixed(2) + ' m';
            document.getElementById('res_jarak').innerText      = jarak.toFixed(2) + ' m';
            document.getElementById('res_threshold').innerText  = thresholdEfektif.toFixed(2) + ' m';

            document.getElementById('hasil_badge').innerHTML = diterima
                ? `<span class="badge badge-success" style="font-size:1.5rem; padding:1rem 2rem;">✅ DITERIMA</span>`
                : `<span class="badge badge-danger" style="font-size:1.5rem; padding:1rem 2rem;">❌ DITOLAK</span>`;

            document.getElementById('card_hasil').style.display = 'block';
        },
        err => {
            const pesan = {
                1: 'Izin lokasi ditolak.',
                2: 'Lokasi tidak tersedia.',
                3: 'Timeout. GPS terlalu lama.'
            };
            alert('❌ Error ' + err.code + ': ' + (pesan[err.code] || err.message));
        }
    );
}

// ── Formula Haversine ────────────────────────────────
function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 +
              Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
              Math.sin(dLng/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
</script>
@endsection