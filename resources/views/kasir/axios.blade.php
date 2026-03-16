@extends('layouts.master')

@section('style_page')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Aplikasi Kasir (Versi AXIOS) </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-info">Input Barang</h4>
                <p class="card-description">Ketik Kode lalu tekan <b>Enter</b></p>
                
                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" class="form-control" id="axKodeBarang" placeholder="Contoh: BRG001">
                    <small id="axPesanError" class="text-danger d-none">Barang tidak ditemukan!</small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control bg-light" id="axNamaBarang" readonly>
                </div>
                <div class="form-group">
                    <label>Harga Barang</label>
                    <input type="number" class="form-control bg-light" id="axHargaBarang" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" class="form-control" id="axJumlahBarang" value="1" min="1">
                </div>
                
                <button type="button" class="btn btn-gradient-info w-100 btn-rounded" id="axBtnTambah" disabled>Tambahkan</button>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="axTabelKeranjang">
                        <thead class="bg-info text-white">
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

                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded border border-info">
                    <h3 class="mb-0 text-dark">Total: Rp <span id="axLabelTotal">0</span></h3>
                    <button type="button" class="btn btn-gradient-success btn-lg btn-rounded" id="axBtnBayar">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    
    // --- 1. FITUR PENCARIAN (MENGGUNAKAN AXIOS GET) ---
    $('#axKodeBarang').on('keypress', function(e) {
        if(e.which == 13) { 
            e.preventDefault();
            let kode = $(this).val();

            // SINTAKS AXIOS GET (Lebih modern pakai .then dan .catch)
            axios.get('/api/barang/' + kode)
            .then(function (response) {
                // Axios otomatis memasukkan data dari server ke dalam object 'data'
                let hasil = response.data; 

                if(hasil.status === 'success') {
                    $('#axNamaBarang').val(hasil.data.nama_barang);
                    $('#axHargaBarang').val(hasil.data.harga);
                    $('#axJumlahBarang').val(1); 
                    $('#axBtnTambah').prop('disabled', false); 
                    $('#axPesanError').addClass('d-none');
                } else {
                    resetFormAxios();
                    $('#axPesanError').removeClass('d-none');
                }
            })
            .catch(function (error) {
                console.error("Terjadi kesalahan:", error);
                resetFormAxios();
            });
        }
    });

    // Validasi tombol Tambah
    $('#axJumlahBarang').on('change keyup', function() {
        let jumlah = $(this).val();
        if(jumlah < 1 || $('#axNamaBarang').val() === "") {
            $('#axBtnTambah').prop('disabled', true);
        } else {
            $('#axBtnTambah').prop('disabled', false);
        }
    });

    function resetFormAxios() {
        $('#axNamaBarang').val('');
        $('#axHargaBarang').val('');
        $('#axJumlahBarang').val(1);
        $('#axBtnTambah').prop('disabled', true);
    }

    // --- 2. FITUR TAMBAH KE KERANJANG (Logika Client-Side) ---
    $('#axBtnTambah').on('click', function() {
        let kode = $('#axKodeBarang').val();
        let nama = $('#axNamaBarang').val();
        let harga = parseInt($('#axHargaBarang').val());
        let jumlah = parseInt($('#axJumlahBarang').val());
        let subtotal = harga * jumlah;

        let existingRow = $('#trAx-' + kode);
        
        if(existingRow.length > 0) {
            let oldJumlah = parseInt(existingRow.find('.input-jumlah').val());
            let newJumlah = oldJumlah + jumlah;
            existingRow.find('.input-jumlah').val(newJumlah);
            existingRow.find('.subtotal-teks').text(newJumlah * harga);
            existingRow.find('.subtotal-val').val(newJumlah * harga);
        } else {
            let tr = `
                <tr id="trAx-${kode}">
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
            $('#axTabelKeranjang tbody').append(tr);
        }

        updateTotalAxios();
        $('#axKodeBarang').val(''); 
        resetFormAxios();
    });

    // --- 3. UBAH JUMLAH & HAPUS ---
    $(document).on('change keyup', '.input-jumlah', function() {
        let tr = $(this).closest('tr');
        let harga = parseInt(tr.find('.td-harga').text());
        let jumlah = parseInt($(this).val());
        
        if(jumlah < 1 || isNaN(jumlah)) { jumlah = 1; $(this).val(1); }
        
        let subtotal = harga * jumlah;
        tr.find('.subtotal-teks').text(subtotal);
        tr.find('.subtotal-val').val(subtotal);
        updateTotalAxios();
    });

    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        updateTotalAxios();
    });

    function updateTotalAxios() {
        let total = 0;
        $('.subtotal-val').each(function() {
            total += parseInt($(this).val());
        });
        $('#axLabelTotal').text(total);
    }

    // --- 4. CHECKOUT MENGGUNAKAN AXIOS POST ---
    $('#axBtnBayar').on('click', function() {
        let totalBelanja = parseInt($('#axLabelTotal').text());
        
        if(totalBelanja === 0) {
            Swal.fire('Oops!', 'Keranjang masih kosong!', 'warning');
            return;
        }

        let btn = $(this);
        let teksAsli = btn.text();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
        btn.prop('disabled', true);

        let dataKeranjang = [];
        $('#axTabelKeranjang tbody tr').each(function() {
            dataKeranjang.push({
                id_barang: $(this).find('.td-kode').text(),
                harga: parseInt($(this).find('.td-harga').text()),
                jumlah: parseInt($(this).find('.input-jumlah').val()),
                subtotal: parseInt($(this).find('.subtotal-val').val())
            });
        });

        // SINTAKS AXIOS POST
        axios.post('/api/transaksi', {
            _token: '{{ csrf_token() }}',
            total: totalBelanja,
            keranjang: dataKeranjang
        })
        .then(function (response) {
            let hasil = response.data;
            if(hasil.status === 'success') {
                Swal.fire('Berhasil!', hasil.message, 'success').then(() => {
                    $('#axTabelKeranjang tbody').empty(); 
                    updateTotalAxios();
                    btn.html(teksAsli);
                    btn.prop('disabled', false);
                });
            }
        })
        .catch(function (error) {
            Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
            btn.html(teksAsli);
            btn.prop('disabled', false);
        });
    });

});
</script>
@endsection