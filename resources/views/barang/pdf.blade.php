<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tag Harga Presisi TnJ 108</title>
    <style>
        /* 1. Mengatur Ukuran Fisik Kertas Custom (21,1 cm x 16,5 cm) */
        @page {
            size: 211mm 165mm;
            margin: 4mm 0mm 0mm 4mm; /* Margin Atas 4mm, Kiri 4mm sesuai standar TnJ */
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* 2. Mengatur Tabel sebagai Jaring Penahan */
        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 205mm; /* 5 Kolom x 41mm (Lebar Stiker 38mm + Jarak 3mm) */
        }
        td {
            width: 41mm; /* Jarak dari ujung kiri stiker 1 ke ujung kiri stiker 2 */
            height: 20mm; /* Jarak dari ujung atas stiker 1 ke ujung atas stiker 2 */
            padding: 0;
            vertical-align: top;
        }
        
        /* 3. Mengatur Ukuran Stiker Asli (Area Cetak) */
        .isi-barang {
            width: 38mm; /* Lebar Asli Stiker */
            height: 18mm; /* Tinggi Asli Stiker */
            box-sizing: border-box;
            padding: 1mm; /* Jarak aman agar teks tidak memotong tepi stiker */
            text-align: center;
            overflow: hidden; /* Mencegah teks kepanjangan merusak layout */
            
            /* CATATAN: Border dimatikan agar garis putus-putusnya tidak ikut ter-print */
            /* border: 1px dashed #ccc; */ 
        }

        /* 4. Font dikecilkan drastis karena ukuran asli sangat kecil */
        .nama {
            font-size: 8px;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
            line-height: 1;
            white-space: nowrap; /* Memaksa teks jadi 1 baris */
            overflow: hidden; /* Menyembunyikan sisa teks yang kepanjangan */
        }
        .kode {
            font-size: 7px;
            color: #555;
            display: block;
            line-height: 1;
        }
        .harga {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            display: block;
            margin-top: 2px;
            line-height: 1;
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
                    if ($kolom % 5 == 0) echo '</tr><tr>';
                @endphp
            @endfor

            @foreach($barang as $item)
                <td>
                    <div class="isi-barang">
                        <span class="nama">{{ strlen($item->nama_barang) > 18 ? substr($item->nama_barang, 0, 18).'..' : $item->nama_barang }}</span>
                        <span class="kode">{{ $item->id_barang }}</span>
                        <span class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    </div>
                </td>
                @php 
                    $kolom++;
                    if ($kolom % 5 == 0) echo '</tr><tr>'; 
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