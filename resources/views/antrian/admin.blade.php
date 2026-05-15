@extends('layouts.master')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">🖥️ Dashboard Admin Antrian</h3>
    <button class="btn btn-danger btn-sm font-weight-bold" onclick="resetAntrian()">
        <i class="mdi mdi-refresh"></i> Reset Semua
    </button>
</div>

<div class="row">
    {{-- Nomor Sedang Dipanggil --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="card-body text-white text-center py-4">
                <p class="mb-1" style="letter-spacing:3px; font-size:12px; opacity:0.8;">SEDANG DIPANGGIL</p>
                <div id="nomor_dipanggil" style="font-size: 5rem; font-weight: 900; line-height:1;">—</div>
                <div id="nama_dipanggil" style="font-size: 1.4rem; opacity: 0.9;">Belum ada</div>
                <div class="mt-3">
                    <button class="btn btn-light btn-lg font-weight-bold px-5" onclick="panggilBerikutnya()">
                        <i class="mdi mdi-account-voice"></i> Panggil Berikutnya
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Menunggu --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-clock-outline text-warning"></i> Menunggu
                    <span id="badge_menunggu" class="badge badge-warning ml-2">0</span>
                </h4>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead><tr>
                            <th>No</th><th>Nama</th><th>Aksi</th>
                        </tr></thead>
                        <tbody id="list_menunggu">
                            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada antrian</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Terlambat --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-account-off text-danger"></i> Terlambat / Tidak Hadir
                    <span id="badge_terlambat" class="badge badge-danger ml-2">0</span>
                </h4>
                <p class="text-muted small">Double klik nama untuk memanggil kembali.</p>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead><tr>
                            <th>No</th><th>Nama</th><th>Aksi</th>
                        </tr></thead>
                        <tbody id="list_terlambat">
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';

// ── SSE ─────────────────────────────────────────────
const source = new EventSource('{{ route("antrian.stream") }}');
source.addEventListener('queue-update', e => {
    const data = JSON.parse(e.data);
    renderAdmin(data);
});
source.onerror = () => console.warn('SSE terputus, mencoba reconnect...');

function renderAdmin(data) {
    // Nomor dipanggil
    if (data.dipanggil) {
        document.getElementById('nomor_dipanggil').innerText = data.dipanggil.nomor;
        document.getElementById('nama_dipanggil').innerText  = data.dipanggil.nama;
    } else {
        document.getElementById('nomor_dipanggil').innerText = '—';
        document.getElementById('nama_dipanggil').innerText  = 'Belum ada';
    }

    // List menunggu
    document.getElementById('badge_menunggu').innerText = data.menunggu.length;
    const tbodyM = document.getElementById('list_menunggu');
    if (data.menunggu.length === 0) {
        tbodyM.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">Belum ada antrian</td></tr>`;
    } else {
        tbodyM.innerHTML = data.menunggu.map((a, i) => `
            <tr>
                <td><span class="badge badge-primary">${a.nomor}</span></td>
                <td class="font-weight-bold">${a.nama}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="tandaiTerlambat(${a.id})">
                        <i class="mdi mdi-account-remove"></i> Terlambat
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // List terlambat
    document.getElementById('badge_terlambat').innerText = data.terlambat.length;
    const tbodyT = document.getElementById('list_terlambat');
    if (data.terlambat.length === 0) {
        tbodyT.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-3">Tidak ada</td></tr>`;
    } else {
        tbodyT.innerHTML = data.terlambat.map(a => `
            <tr ondblclick="panggilTerlambat(${a.id})" style="cursor:pointer;" title="Double klik untuk panggil kembali">
                <td><span class="badge badge-danger">${a.nomor}</span></td>
                <td class="font-weight-bold">${a.nama}</td>
                <td>
                    <button class="btn btn-warning btn-sm text-dark" onclick="panggilTerlambat(${a.id})">
                        <i class="mdi mdi-account-voice"></i> Panggil
                    </button>
                </td>
            </tr>
        `).join('');
    }
}

// ── Actions ──────────────────────────────────────────
function post(url, body = {}) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(body)
    }).then(r => r.json());
}

function panggilBerikutnya() {
    post('{{ route("antrian.panggil") }}')
        .then(d => { if (d.message) alert(d.message); });
}

function tandaiTerlambat(id) {
    if (confirm('Tandai sebagai tidak hadir?')) {
        post('{{ route("antrian.terlambat") }}', { id });
    }
}

function panggilTerlambat(id) {
    post('{{ route("antrian.panggilTerlambat") }}', { id });
}

function resetAntrian() {
    if (confirm('Reset semua antrian? Data akan dihapus permanen!')) {
        post('{{ route("antrian.reset") }}');
    }
}
</script>
@endsection