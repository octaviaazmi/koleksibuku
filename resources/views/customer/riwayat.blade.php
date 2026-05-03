@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 🛒 Riwayat Pesanan Saya </h3>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan</h4>
                <p class="card-description">Klik tombol QR Code untuk verifikasi pesanan ke Vendor</p>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <!-- Panggil nama kolom yang benar: idpesanan -->
                                <td>#{{ $p->idpesanan }}</td>
                                
                                <!-- Bonus: Bikin status bayarnya dinamis -->
                                <td>
                                    @if($p->status_bayar == 'Lunas')
                                        <label class="badge badge-success">{{ $p->status_bayar }}</label>
                                    @else
                                        <label class="badge badge-warning">{{ $p->status_bayar }}</label>
                                    @endif
                                </td>
                                
                                <td>
                                    <!-- Parsing idpesanan ke dalam fungsi QR Code -->
                                    <button type="button" class="btn btn-info btn-sm" onclick="tampilkanQR('{{ $p->idpesanan }}')">
                                        <i class="mdi mdi-qrcode"></i> Lihat QR Code
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if($pesanan->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">Belum ada pesanan nih.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-up untuk menampilkan QR Code -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="modalQRLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalQRLabel">📱 Verifikasi Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p class="text-muted">Tunjukkan QR Code ini ke Vendor</p>
        
        <!-- Kotak tempat QR Code akan digambar -->
        <div id="tempatQRCode" class="d-flex justify-content-center p-3 bg-white rounded border"></div>
        
        <h3 class="mt-3 text-primary" id="teksIdPesanan"></h3>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript_page')
<!-- Library QR Code Generator Frontend -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    function tampilkanQR(idPesanan) {
        // Bersihkan QR Code yang sebelumnya (kalau ada)
        document.getElementById('tempatQRCode').innerHTML = "";
        
        // Bikin tulisan ID di bawah QR
        document.getElementById('teksIdPesanan').innerText = "Order ID: #" + idPesanan;

        // Generate QR Code baru (berisi teks idPesanan)
        new QRCode(document.getElementById("tempatQRCode"), {
            text: idPesanan.toString(),
            width: 200,
            height: 200,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });

        // Munculkan Modalnya
        $('#modalQR').modal('show');
    }
</script>
@endsection