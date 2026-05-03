@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 📷 Scan Barcode Barang </h3>
</div>

<div class="row">
    <!-- Kotak Scanner -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title text-primary">Arahkan Barcode ke Kamera</h4>
                <!-- Area Kamera Html5-Qrcode -->
                <div id="reader" width="100%"></div>
            </div>
        </div>
    </div>

    <!-- Kotak Hasil Scan -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">Hasil Scan</h4>
                <hr>
                
                <div id="hasilScanInfo" class="d-none">
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" class="form-control" id="res_id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" id="res_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="text" class="form-control font-weight-bold text-success" id="res_harga" readonly>
                    </div>
                    
                    <button class="btn btn-primary w-100 mt-3" onclick="window.location.reload()">Scan Barang Lain</button>
                </div>

                <div id="menungguScan" class="text-center text-muted mt-5">
                    <i class="mdi mdi-barcode-scan" style="font-size: 50px;"></i>
                    <p>Menunggu hasil scan...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Audio Beep -->
<audio id="beepSound" src="{{ asset('assets/audio/beep.mp3') }}" preload="auto"></audio>
@endsection

@section('javascript_page')
<!-- Menggunakan Library Html5-Qrcode sesuai pilihan di modul -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
$(document).ready(function() {
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: {width: 250, height: 150} },
        /* verbose= */ false
    );

    function onScanSuccess(decodedText, decodedResult) {
        // 1. Dikeluarkan bunyi "beep" pendek
        document.getElementById('beepSound').play();

        // 2. Scanner berhenti scan
        html5QrcodeScanner.clear().then(() => {
            console.log("Scanner berhenti.");
        }).catch(error => {
            console.error("Gagal menghentikan scanner.", error);
        });

        // 3. Menampilkan IDbarang, nama barang dan harga barang
        $('#menungguScan').html('<span class="spinner-border spinner-border-sm"></span> Mencari data...');
        
        $.ajax({
            url: "/api/barang/" + decodedText,
            type: "GET",
            success: function(response) {
                if(response.status === 'success') {
                    $('#menungguScan').addClass('d-none');
                    $('#hasilScanInfo').removeClass('d-none');
                    
                    // Isi form dengan data dari database
                    $('#res_id').val(response.data.id_barang);
                    $('#res_nama').val(response.data.nama_barang);
                    $('#res_harga').val('Rp ' + response.data.harga);
                    
                    Swal.fire('Berhasil!', 'Barang ditemukan', 'success');
                } else {
                    $('#menungguScan').html('<p class="text-danger">Barang tidak ditemukan di database!</p>');
                    Swal.fire('Gagal!', 'Barang dengan ID ' + decodedText + ' tidak ada.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        });
    }

    function onScanFailure(error) {
        // Abaikan error saat proses nyari barcode, karena ini berjalan tiap frame
    }

    // Mulai Scanner
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection