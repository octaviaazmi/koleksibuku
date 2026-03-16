@extends('layouts.master')

@section('style_page')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Aplikasi Kasir (AJAX JQuery) </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">Input Barang</h4>
                <p class="card-description">Ketik Kode lalu tekan <b>Enter</b></p>
                
                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" class="form-control" id="kodeBarang" placeholder="Contoh: BRG001">
                    <small id="pesanError" class="text-danger d-none">Barang tidak ditemukan!</small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control bg-light" id="namaBarang" readonly>
                </div>
                <div class="form-group">
                    <label>Harga Barang</label>
                    <input type="number" class="form-control bg-light" id="hargaBarang" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" id="jumlahBarang" value="1" min="1">
                </div>
                
                <button type="button" class="btn btn-gradient-primary w-100 btn-rounded" id="btnTambah" disabled>Tambahkan</button>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="tabelKeranjang">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                    <h3 class="mb-0 text-dark">Total: Rp <span id="labelTotal">0</span></h3>
                    <button type="button" class="btn btn-gradient-success btn-lg btn-rounded" id="btnBayar">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // 1. CARI BARANG DENGAN TOMBOL ENTER
    $('#kodeBarang').on('keypress', function(e) {
        if(e.which == 13) { // 13 = Kode tombol Enter
            e.preventDefault();
            let kode = $(this).val();

            // Minta data ke Backend pakai AJAX
            $.ajax({
                type: "GET",
                url: "/api/barang/" + kode,
                success: function(response) {
                    if(response.status === 'success') {
                        // Kalau ketemu, isi form & aktifkan tombol
                        $('#namaBarang').val(response.data.nama_barang);
                        $('#hargaBarang').val(response.data.harga);
                        $('#jumlahBarang').val(1);
                        $('#btnTambah').prop('disabled', false);
                        $('#pesanError').addClass('d-none');
                    } else {
                        // Kalau nggak ketemu, kosongkan & kunci tombol
                        resetFormBarang();
                        $('#pesanError').removeClass('d-none');
                    }
                }
            });
        }
    });

    // Validasi tombol tambah (harus > 0)
    $('#jumlahBarang').on('change keyup', function() {
        let jumlah = $(this).val();
        if(jumlah < 1 || $('#namaBarang').val() === "") {
            $('#btnTambah').prop('disabled', true);
        } else {
            $('#btnTambah').prop('disabled', false);
        }
    });

    function resetFormBarang() {
        $('#namaBarang').val('');
        $('#hargaBarang').val('');
        $('#jumlahBarang').val(1);
        $('#btnTambah').prop('disabled', true);
    }

    // 2. TAMBAH KE KERANJANG
    $('#btnTambah').on('click', function() {
        let kode = $('#kodeBarang').val();
        let nama = $('#namaBarang').val();
        let harga = parseInt($('#hargaBarang').val());
        let jumlah = parseInt($('#jumlahBarang').val());
        let subtotal = harga * jumlah;

        let barisAda = $('#tr-' + kode);
        
        // Cek kalau barang udah ada di keranjang, tambah jumlahnya aja
        if(barisAda.length > 0) {
            let jumlahLama = parseInt(barisAda.find('.input-jumlah').val());
            let jumlahBaru = jumlahLama + jumlah;
            barisAda.find('.input-jumlah').val(jumlahBaru);
            barisAda.find('.subtotal-teks').text(jumlahBaru * harga);
            barisAda.find('.subtotal-val').val(jumlahBaru * harga);
        } else {
            // Kalau belum ada, bikin baris baru
            let tr = `
                <tr id="tr-${kode}">
                    <td class="td-kode">${kode}</td>
                    <td>${nama}</td>
                    <td class="td-harga">${harga}</td>
                    <td><input type="number" class="form-control form-control-sm input-jumlah" value="${jumlah}" min="1"></td>
                    <td>
                        <span class="subtotal-teks">${subtotal}</span>
                        <input type="hidden" class="subtotal-val" value="${subtotal}">
                    </td>
                    <td><button class="btn btn-sm btn-danger btn-hapus">Hapus</button></td>
                </tr>
            `;
            $('#tabelKeranjang tbody').append(tr);
        }

        updateTotal();
        $('#kodeBarang').val(''); 
        resetFormBarang();
    });

    // 3. UBAH JUMLAH & HAPUS DARI KERANJANG
    $(document).on('change keyup', '.input-jumlah', function() {
        let tr = $(this).closest('tr');
        let harga = parseInt(tr.find('.td-harga').text());
        let jumlah = parseInt($(this).val());
        
        if(jumlah < 1 || isNaN(jumlah)) { jumlah = 1; $(this).val(1); }
        
        let subtotal = harga * jumlah;
        tr.find('.subtotal-teks').text(subtotal);
        tr.find('.subtotal-val').val(subtotal);
        updateTotal();
    });

    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    function updateTotal() {
        let total = 0;
        $('.subtotal-val').each(function() {
            total += parseInt($(this).val());
        });
        $('#labelTotal').text(total);
    }

    // 4. CHECKOUT (BAYAR) KE DATABASE
    $('#btnBayar').on('click', function() {
        let totalBelanja = parseInt($('#labelTotal').text());
        
        if(totalBelanja === 0) {
            Swal.fire('Oops!', 'Keranjang masih kosong!', 'warning');
            return;
        }

        // Efek Tombol Loading
        let btn = $(this);
        let teksAsli = btn.text();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
        btn.prop('disabled', true);

        // Kumpulkan data keranjang
        let dataKeranjang = [];
        $('#tabelKeranjang tbody tr').each(function() {
            dataKeranjang.push({
                id_barang: $(this).find('.td-kode').text(),
                harga: parseInt($(this).find('.td-harga').text()),
                jumlah: parseInt($(this).find('.input-jumlah').val()),
                subtotal: parseInt($(this).find('.subtotal-val').val())
            });
        });

        // Tembak POST AJAX ke Backend
        $.ajax({
            type: "POST",
            url: "/api/transaksi",
            data: {
                _token: '{{ csrf_token() }}', // Syarat keamanan wajib Laravel
                total: totalBelanja,
                keranjang: dataKeranjang
            },
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        $('#tabelKeranjang tbody').empty(); // Bersihkan keranjang
                        updateTotal();
                        btn.html(teksAsli);
                        btn.prop('disabled', false);
                    });
                }
            },
            error: function() {
                Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                btn.html(teksAsli);
                btn.prop('disabled', false);
            }
        });
    });

});
</script>
@endsection