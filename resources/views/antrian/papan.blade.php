@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">📺 Papan Antrian</h3>
</div>

{{-- Tombol aktivasi suara (wajib diklik sekali karena browser policy) --}}
<div id="overlay_aktivasi" style="
    position: fixed; inset: 0; background: rgba(0,0,0,0.85);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999; flex-direction: column; gap: 16px;">
    <div style="color:white; font-size: 1.4rem; font-weight: 600;">📺 Papan Antrian</div>
    <div style="color:#ccc; font-size: 1rem;">Klik tombol di bawah untuk mengaktifkan tampilan & suara</div>
    <button class="btn btn-primary btn-lg px-5 font-weight-bold" onclick="aktivasiPapan()">
        <i class="mdi mdi-play-circle"></i> Aktifkan Papan
    </button>
</div>

<div class="row">
    {{-- Nomor Dipanggil (besar) --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow" style="background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460); min-height: 280px;">
            <div class="card-body text-white text-center d-flex flex-column justify-content-center py-5">
                <p style="letter-spacing:4px; font-size:13px; opacity:0.6; text-transform:uppercase; margin-bottom:8px;">
                    Nomor Antrian Dipanggil
                </p>
                <div id="papan_nomor" style="font-size: 8rem; font-weight: 900; line-height: 1;
                     text-shadow: 0 0 40px rgba(102,126,234,0.8); color: #a78bfa;">
                    —
                </div>
                <div id="papan_nama" style="font-size: 2rem; font-weight: 600; opacity: 0.85; margin-top: 8px;">
                    Menunggu panggilan...
                </div>
            </div>
        </div>
    </div>

    {{-- List Menunggu --}}
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-account-multiple text-primary"></i> Antrian Menunggu
                    <span id="papan_badge_menunggu" class="badge badge-primary ml-2">0</span>
                </h4>
                <div id="papan_list_menunggu" class="d-flex flex-wrap gap-2 mt-3">
                    <span class="text-muted">Belum ada antrian</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h4 class="card-title">Status Sistem</h4>
                <div id="sse_status" class="badge badge-success p-2 mt-2" style="font-size:0.85rem;">
                    <i class="mdi mdi-wifi"></i> Terhubung
                </div>
                <hr>
                <p class="text-muted small mb-1">Total menunggu</p>
                <h2 id="total_menunggu" class="font-weight-bold text-primary">0</h2>
            </div>
        </div>
    </div>
</div>

<script>
let nomor_sebelumnya = null;
let sudahAktif = false;

function aktivasiPapan() {
    sudahAktif = true;
    document.getElementById('overlay_aktivasi').style.display = 'none';
}

// ── SSE ─────────────────────────────────────────────
const source = new EventSource('{{ route("antrian.stream") }}');

source.addEventListener('queue-update', e => {
    const data = JSON.parse(e.data);
    renderPapan(data);
});

source.onerror = () => {
    document.getElementById('sse_status').className = 'badge badge-danger p-2 mt-2';
    document.getElementById('sse_status').innerHTML = '<i class="mdi mdi-wifi-off"></i> Terputus...';
};

source.onopen = () => {
    document.getElementById('sse_status').className = 'badge badge-success p-2 mt-2';
    document.getElementById('sse_status').innerHTML = '<i class="mdi mdi-wifi"></i> Terhubung';
};

function renderPapan(data) {
    // Nomor dipanggil
    if (data.dipanggil) {
        const nomorBaru = data.dipanggil.nomor;
        document.getElementById('papan_nomor').innerText = nomorBaru;
        document.getElementById('papan_nama').innerText  = data.dipanggil.nama;

        // Bunyikan suara hanya kalau nomor berubah
        if (sudahAktif && nomorBaru !== nomor_sebelumnya) {
            nomor_sebelumnya = nomorBaru;
            bunyikanPanggilan(nomorBaru, data.dipanggil.nama);
        }
    } else {
        document.getElementById('papan_nomor').innerText = '—';
        document.getElementById('papan_nama').innerText  = 'Menunggu panggilan...';
    }

    // List menunggu (tampil sebagai badge)
    document.getElementById('papan_badge_menunggu').innerText = data.menunggu.length;
    document.getElementById('total_menunggu').innerText       = data.menunggu.length;

    const container = document.getElementById('papan_list_menunggu');
    if (data.menunggu.length === 0) {
        container.innerHTML = '<span class="text-muted">Belum ada antrian</span>';
    } else {
        container.innerHTML = data.menunggu.map(a => `
            <span class="badge badge-light border" style="font-size:1rem; padding: 8px 16px; margin: 4px;">
                <strong>${a.nomor}</strong> — ${a.nama}
            </span>
        `).join('');
    }
}

// ── Suara Panggilan ──────────────────────────────────
function bunyikanPanggilan(nomor, nama) {
    if (!('speechSynthesis' in window)) return;

    window.speechSynthesis.cancel();

    const pesan = new SpeechSynthesisUtterance(
        `Nomor antrian ${nomor}. ${nama}, silakan masuk.`
    );
    pesan.lang   = 'id-ID';
    pesan.rate   = 0.85;
    pesan.pitch  = 1.0;
    pesan.volume = 1.0;

    // Coba pakai ding dong dari browser dulu, fallback ke langsung speak
    try {
        const ctx  = new AudioContext();
        const osc  = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.setValueAtTime(880, ctx.currentTime);
        osc.frequency.setValueAtTime(660, ctx.currentTime + 0.2);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.6);
        osc.onended = () => {
            ctx.close();
            setTimeout(() => window.speechSynthesis.speak(pesan), 200);
        };
    } catch {
        window.speechSynthesis.speak(pesan);
    }
}
</script>
@endsection