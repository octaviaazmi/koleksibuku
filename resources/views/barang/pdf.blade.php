<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tag Harga</title>
    <style>
        /* Mengatur margin kertas agar pas dengan stiker */
        @page {
            margin: 15px; 
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Kunci utamanya ada di Tabel ini */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Memaksa ukuran kolom sama rata */
        }
        td {
            width: 20%; /* 100% dibagi 5 kolom = 20% */
            height: 125px; /* Tinggi disesuaikan agar muat 8 baris */
            vertical-align: middle;
            text-align: center;
            padding: 4px;
        }
        
        /* Desain dalam kotak tag */
        .isi-barang {
            border: 1px dashed #666;
            border-radius: 6px;
            padding: 10px 2px;
            background-color: #fafafa;
        }
        .nama {
            font-size: 11px;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .kode {
            font-size: 9px;
            color: #555;
            display: block;
        }
        .harga {
            font-size: 14px;
            font-weight: bold;
            color: #d9534f;
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            @php $kolom = 0; @endphp

            @for($i = 0; $i < $skip; $i++)
                <td></td>
                @php 
                    $kolom++;
                    // Kalau sudah 5 kotak, turun ke baris baru
                    if ($kolom % 5 == 0) {
                        echo '</tr><tr>';
                    }
                @endphp
            @endfor

            @foreach($barang as $item)
                <td>
                    <div class="isi-barang">
                        <span class="nama">{{ $item->nama_barang }}</span>
                        <span class="kode">{{ $item->id_barang }}</span>
                        <span class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    </div>
                </td>
                @php 
                    $kolom++;
                    // Kalau sudah 5 kotak, turun ke baris baru
                    if ($kolom % 5 == 0) {
                        echo '</tr><tr>'; 
                    }
                @endphp
            @endforeach

            @while($kolom % 5 != 0)
                <td></td>
                @php $kolom++; @endphp
            @endwhile
        </tr>
    </table>
</body>
</html>