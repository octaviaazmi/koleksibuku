<!DOCTYPE html>
<html>
<head>
    <title>Undangan Bukber</title>
    <style>
        @page { margin: 0; }
        body { margin: 0; font-family: 'Helvetica', sans-serif; }
        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .nama-undangan {
            position: absolute;
            top: 755px; /* Ditambah supaya turun ke kolom UNTUK */
            left: 420px; /* Digeser supaya lebih ke tengah garis */
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
            width: 500px; /* Memberi ruang agar tidak terpotong */
        }
    </style>
</head>
<body>
    <img src="{{ public_path('assets/images/undangan.png') }}" class="background">
    <div class="nama-undangan">{{ $nama }}</div>
</body>
</html>