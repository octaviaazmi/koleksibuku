<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tag Harga Presisi TnJ 108</title>
    <style>
        /* atur ukuran tnj */
        @page {
            size: 211mm 165mm;
            margin: 4mm 0mm 0mm 4mm; 
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* atur tabelnya */
        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 205mm;
        }
        td {
            width: 41mm; 
            height: 20mm; 
            padding: 0;
            vertical-align: center;
        }
        
        /* mengatur ukuran stiker */
        .isi-barang {
            width: 38mm;
            height: 18mm; 
            box-sizing: border-box;
            padding: 1mm; 
            text-align: center;
            overflow: hidden;
            
        }

        .nama {
            font-size: 8px;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
            line-height: 1;
            white-space: nowrap; 
            overflow: hidden; 
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