@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 📸 Tambah Customer (Simpan as FILE Fisik) </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title text-primary">Webcam Aktif</h4>
                <p class="card-description text-muted">Arahkan wajah ke kamera dan klik Jepret</p>
                
                <div class="mb-3 d-flex justify-content-center bg-dark rounded" style="min-height: 240px; overflow: hidden;">
                    <video id="videoElement" autoplay playsinline style="width: 100%; max-width: 320px; border-radius: 8px;"></video>
                </div>
                
                <button type="button" class="btn btn-warning btn-icon-text" id="btnJepret">
                    <i class="mdi mdi-camera btn-icon-prepend"></i> Jepret Foto
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">Hasil Tangkapan & Form</h4>
                
                <form action="{{ route('customer.store_file') }}" method="POST">
                    @csrf
                    <div class="text-center mb-4">
                        <canvas id="canvasElement" style="display:none;"></canvas>
                        <img id="hasilFoto" src="https://via.placeholder.com/320x240?text=Belum+Ada+Foto" class="img-fluid rounded border" alt="Hasil Foto">
                        
                        <input type="hidden" name="foto_base64" id="inputBase64" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    
                    <button type="submit" class="btn btn-gradient-success w-100" id="btnSimpan" disabled>Simpan Data (FILE FISIK)</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script>
    $(document).ready(function() {
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvasElement');
        const hasilFoto = document.getElementById('hasilFoto');
        const inputBase64 = document.getElementById('inputBase64');
        const btnJepret = document.getElementById('btnJepret');
        const btnSimpan = document.getElementById('btnSimpan');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(err) {
                alert("Gagal akses kamera.");
                console.error(err);
            });
        }

        btnJepret.addEventListener('click', function() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            let dataUrl = canvas.toDataURL('image/png');
            hasilFoto.src = dataUrl;
            inputBase64.value = dataUrl;
            btnSimpan.disabled = false;
        });
    });
</script>
@endsection