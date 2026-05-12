@extends('layouts.master')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">🏪 Data Master Toko</h3>
    <button type="button" class="btn btn-primary font-weight-bold" data-bs-toggle="modal" data-bs-target="#modalTambahToko">
        <i class="mdi mdi-plus"></i> Tambah Toko
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h4 class="card-title">List Toko</h4>
        <p class="card-description">Daftar toko beserta titik koordinat GPS-nya. Klik QR untuk memperbesar.</p>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%">No</th>
                        <th class="text-center">QR Code</th> <th>Barcode ID</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy (m)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalQR{{ $t->id }}">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ $t->barcode }}" 
                                         alt="QR" class="border rounded shadow-sm bg-white">
                                </a>
                            </td>
                            <td><span class="badge badge-dark">{{ $t->barcode }}</span></td>
                            <td class="font-weight-bold">{{ $t->nama_toko }}</td>
                            <td class="text-success">{{ $t->latitude }}</td>
                            <td class="text-success">{{ $t->longitude }}</td>
                            <td><span class="badge badge-success">{{ round($t->accuracy) }} m</span></td>
                        </tr>

                        <div class="modal fade" id="modalQR{{ $t->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content text-center">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Scan QR Kunjungan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 bg-white">
                                        <h4 class="mb-3 text-primary">{{ $t->nama_toko }}</h4>
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $t->barcode }}" 
                                             alt="QR" class="img-fluid border p-2 shadow-sm">
                                        <p class="mt-3 mb-0 font-weight-bold">{{ $t->barcode }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data toko.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Toko --}}
<div class="modal fade" id="modalTambahToko" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('toko.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Toko Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control" placeholder="Contoh: Toko Maju Jaya" required>
                </div>
                <div class="form-group mb-3">
                    <label>Latitude</label>
                    <input type="text" id="lat_input" name="latitude" class="form-control" placeholder="Contoh: -7.257472" required>
                </div>
                <div class="form-group mb-3">
                    <label>Longitude</label>
                    <input type="text" id="lng_input" name="longitude" class="form-control" placeholder="Contoh: 112.752088" required>
                </div>
                <div class="form-group mb-3">
                    <label>Accuracy (m)</label>
                    <input type="text" id="acc_input" name="accuracy" class="form-control" placeholder="Otomatis terisi saat ambil lokasi" required>
                </div>
                <button type="button" class="btn btn-secondary w-100" onclick="ambilLokasi()">
                    <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi GPS Otomatis
                </button>
                <p class="text-muted small mt-2">*Barcode di-generate otomatis oleh sistem.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function getAccuratePosition(onSuccess, onError, options = {}) {
    const { desiredAccuracy = 50, maxWait = 10000 } = options;
    let best = null;
    let watchId;
    const timer = setTimeout(() => {
        navigator.geolocation.clearWatch(watchId);
        best ? onSuccess(best) : onError({ message: 'Timeout, coba lagi.' });
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

function ambilLokasi() {
    alert('📍 Sedang mengambil lokasi, tunggu sebentar...');
    getAccuratePosition(
        pos => {
            document.getElementById('lat_input').value = pos.coords.latitude;
            document.getElementById('lng_input').value = pos.coords.longitude;
            document.getElementById('acc_input').value = pos.coords.accuracy.toFixed(2);
            alert('✅ Lokasi berhasil diambil!');
        },
        err => {
            const pesan = {
                1: 'Izin lokasi ditolak. Cek pengaturan izin browser kamu.',
                2: 'Lokasi tidak tersedia. Coba di tempat yang ada sinyal GPS.',
                3: 'Timeout. GPS terlalu lama merespons.'
            };
            alert('❌ Error ' + err.code + ': ' + (pesan[err.code] || err.message));
        }
    );
}
</script>
@endsection