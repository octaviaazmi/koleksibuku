@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">🎫 Daftar Antrian</h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="mdi mdi-ticket-confirmation text-primary" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 font-weight-bold">Ambil Nomor Antrian</h4>
                    <p class="text-muted">Masukkan nama kamu untuk mendapatkan nomor antrian.</p>
                </div>

                <div class="form-group text-left">
                    <label class="font-weight-bold">Nama Lengkap</label>
                    <input type="text" id="input_nama" class="form-control form-control-lg"
                           placeholder="Contoh: Budi Santoso" autofocus>
                    <small class="text-danger d-none" id="err_nama">Nama tidak boleh kosong!</small>
                </div>

                <button class="btn btn-primary btn-lg btn-block mt-4 font-weight-bold" onclick="daftarAntrian()">
                    <i class="mdi mdi-ticket-account"></i> Ambil Nomor Antrian
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function daftarAntrian() {
    const nama = document.getElementById('input_nama').value.trim();
    const err  = document.getElementById('err_nama');

    if (!nama) {
        err.classList.remove('d-none');
        return;
    }
    err.classList.add('d-none');

    fetch('{{ route("antrian.daftar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nama })
    })
    .then(r => r.json())
    .then(data => {
        // Buka tab baru dengan nomor antrian
        const html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Nomor Antrian Kamu</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body {
                        font-family: 'Segoe UI', sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .ticket {
                        background: white;
                        border-radius: 24px;
                        padding: 60px 80px;
                        text-align: center;
                        box-shadow: 0 30px 80px rgba(0,0,0,0.3);
                        max-width: 420px;
                        width: 90%;
                    }
                    .label { color: #888; font-size: 14px; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
                    .nomor { font-size: 120px; font-weight: 900; color: #667eea; line-height: 1; margin: 16px 0; }
                    .nama  { font-size: 26px; font-weight: 700; color: #333; margin-bottom: 8px; }
                    .info  { color: #aaa; font-size: 14px; margin-top: 24px; padding-top: 24px; border-top: 2px dashed #eee; }
                    .dot   { width: 12px; height: 12px; background: #4caf50; border-radius: 50%; display: inline-block; margin-right: 6px; animation: pulse 1.5s infinite; }
                    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }
                </style>
            </head>
            <body>
                <div class="ticket">
                    <div class="label">Nomor Antrian Kamu</div>
                    <div class="nomor">${data.nomor}</div>
                    <div class="nama">${data.nama}</div>
                    <div class="info">
                        <span class="dot"></span>
                        Silakan tunggu, kamu akan dipanggil segera
                    </div>
                </div>
            </body>
            </html>
        `;
        const tab = window.open('', '_blank');
        tab.document.write(html);
        tab.document.close();

        // Reset form
        document.getElementById('input_nama').value = '';
    })
    .catch(() => alert('Gagal mendaftar, coba lagi.'));
}

// Enter key support
document.getElementById('input_nama').addEventListener('keydown', e => {
    if (e.key === 'Enter') daftarAntrian();
});
</script>
@endsection