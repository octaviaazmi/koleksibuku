<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Apresiasi</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; font-family: 'Helvetica', sans-serif; }
        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .nama-sertifikat {
            position: absolute;
            top: 520px; /* Ditambah agar turun ke atas garis */
            width: 100%;
            text-align: center;
            font-size: 38px;
            font-weight: bold;
            color: #1a237e;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('assets/images/sertifikat.png') }}" class="background">
    <div class="nama-sertifikat">{{ $nama }}</div>
</body>
</html>