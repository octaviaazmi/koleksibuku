@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 📱 Scan QR Code Customer </h3>
</div>

<div class="row">
    <!-- Kotak Kamera Scanner -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title text-primary">Arahkan QR Code ke Kamera</h4>
                <div id="reader" width="100%"></div>
            </div>
        </div>
    </div>

    <!-- Kotak Hasil Scan -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">Hasil Verifikasi Pesanan</h4>
                <hr>
                
                <div id="hasilScanInfo" class="d-none">
                    <div class="form-group">
                        <label>ID Pesanan</label>
                        <input type="text" class="form-control" id="res_id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total Pembayaran</label>
                        <input type="text" class="form-control" id="res_total" readonly>
                    </div>
                    <div class="form-group">
                        <label>Status Bayar</label>
                        <input type="text" class="form-control font-weight-bold text-success" id="res_status" readonly>
                    </div>
                    
                    <button class="btn btn-primary w-100 mt-3" onclick="window.location.reload()">Scan Antrean Selanjutnya</button>
                </div>

                <div id="menungguScan" class="text-center text-muted mt-5">
                    <i class="mdi mdi-qrcode-scan" style="font-size: 50px;"></i>
                    <p>Menunggu QR Code di-scan...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menggunakan file audio Beep yang sama dari praktikum sebelumnya -->
<audio id="beepSound" src="{{ asset('assets/audio/beep.mp3') }}" preload="auto"></audio>
@endsection

@section('javascript_page')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
$(document).ready(function() {
    // Settingan kotak scan (tanpa qrbox biar full satu layar)
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10 },
        /* verbose= */ false
    );

    function onScanSuccess(decodedText, decodedResult) {
        // 1. Dikeluarkan bunyi "beep" pendek
        document.getElementById('beepSound').play();

        // 2. Scanner berhenti scan
        html5QrcodeScanner.clear().then(() => {
            console.log("Scanner dihentikan.");
        }).catch(error => {
            console.error("Gagal menghentikan scanner.", error);
        });

        // 3. Menampilkan status pesanan
        $('#menungguScan').html('<span class="spinner-border spinner-border-sm"></span> Memverifikasi data...');
        
        $.ajax({
            url: "/api/pesanan/" + decodedText,
            type: "GET",
            success: function(response) {
                if(response.status === 'success') {
                    $('#menungguScan').addClass('d-none');
                    $('#hasilScanInfo').removeClass('d-none');
                    
                    // Isi form dengan data dari database
                    $('#res_id').val(response.data.idpesanan);
                    $('#res_total').val('Rp ' + response.data.total);
                    
                    // Cek Lunas atau Belum
                    if(response.data.status_bayar === 'Lunas') {
                        $('#res_status').val('✅ LUNAS').removeClass('text-danger').addClass('text-success');
                        Swal.fire('Lunas!', 'Pesanan bisa diberikan ke Customer', 'success');
                    } else {
                        $('#res_status').val('❌ BELUM LUNAS').removeClass('text-success').addClass('text-danger');
                        Swal.fire('Perhatian!', 'Pesanan ini belum dibayar!', 'warning');
                    }
                    
                } else {
                    $('#menungguScan').html('<p class="text-danger">Pesanan tidak ditemukan!</p>');
                    Swal.fire('Gagal!', 'QR Code tidak valid atau pesanan tidak ada.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
            }
        });
    }

    function onScanFailure(error) {
        // Abaikan error saat proses nyari QR
    }

    // Mulai Scanner
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection