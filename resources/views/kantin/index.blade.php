@extends('layouts.master')

@section('style_page')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> 🍔 Mini Kantin Online </h3>
</div>

<div class="row">
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">Pilih Pesanan</h4>
                
                <div class="form-group">
                    <label>Pilih Penjual (Vendor)</label>
                    <select class="form-control form-control-lg" id="selectVendor">
                        <option value="" selected disabled>-- Pilih Vendor Dulu --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="areaMenu" class="d-none">
                    <div class="form-group">
                        <label>Pilih Menu</label>
                        <select class="form-control" id="selectMenu">
                            </select>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" class="form-control bg-light" id="hargaMenu" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" class="form-control" id="jumlahMenu" value="1" min="1">
                    </div>
                    <button type="button" class="btn btn-gradient-primary w-100 btn-rounded" id="btnTambah">Tambahkan ke Keranjang</button>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="tabelKeranjang">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th width="15%">Jml</th>
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
                    <button type="button" class="btn btn-gradient-success btn-lg btn-rounded" id="btnCheckout">Checkout Sekarang!</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
$(document).ready(function() {
    
    // (Script Select Vendor & Tambah Keranjang Tetap Sama)
    $('#selectVendor').on('change', function() {
        let idvendor = $(this).val();
        $('#selectMenu').html('<option value="">Memuat menu...</option>');
        $('#areaMenu').removeClass('d-none');
        $.ajax({
            type: "GET", url: "/api/menu/" + idvendor,
            success: function(response) {
                if(response.status === 'success') {
                    let opsiMenu = '<option value="" selected disabled>-- Pilih Menu --</option>';
                    response.data.forEach(function(menu) {
                        opsiMenu += `<option value="${menu.idmenu}" data-nama="${menu.nama_menu}" data-harga="${menu.harga}">${menu.nama_menu} - Rp ${menu.harga}</option>`;
                    });
                    $('#selectMenu').html(opsiMenu);
                    $('#hargaMenu').val(''); $('#jumlahMenu').val(1);
                }
            }
        });
    });

    $('#selectMenu').on('change', function() {
        let harga = $(this).find(':selected').data('harga');
        $('#hargaMenu').val(harga);
    });

    $('#btnTambah').on('click', function() {
        let idmenu = $('#selectMenu').val();
        let nama = $('#selectMenu').find(':selected').data('nama');
        let harga = parseInt($('#hargaMenu').val());
        let jumlah = parseInt($('#jumlahMenu').val());

        if(!idmenu) { Swal.fire('Oops', 'Pilih menu dulu ya!', 'warning'); return; }

        let subtotal = harga * jumlah;
        let existingRow = $('#tr-' + idmenu);
        
        if(existingRow.length > 0) {
            let oldJumlah = parseInt(existingRow.find('.input-jumlah').val());
            let newJumlah = oldJumlah + jumlah;
            existingRow.find('.input-jumlah').val(newJumlah);
            existingRow.find('.subtotal-teks').text(newJumlah * harga);
            existingRow.find('.subtotal-val').val(newJumlah * harga);
        } else {
            let tr = `
                <tr id="tr-${idmenu}">
                    <td>${nama} <input type="hidden" class="idmenu-val" value="${idmenu}"></td>
                    <td class="td-harga">${harga}</td>
                    <td><input type="number" class="form-control form-control-sm input-jumlah" value="${jumlah}" min="1"></td>
                    <td><span class="subtotal-teks">${subtotal}</span><input type="hidden" class="subtotal-val" value="${subtotal}"></td>
                    <td><button class="btn btn-sm btn-danger btn-hapus">Hapus</button></td>
                </tr>
            `;
            $('#tabelKeranjang tbody').append(tr);
        }
        updateTotal(); $('#selectMenu').val(''); $('#hargaMenu').val(''); $('#jumlahMenu').val(1);
    });

    $(document).on('change keyup', '.input-jumlah', function() {
        let tr = $(this).closest('tr');
        let harga = parseInt(tr.find('.td-harga').text());
        let jumlah = parseInt($(this).val());
        if(jumlah < 1 || isNaN(jumlah)) { jumlah = 1; $(this).val(1); }
        let subtotal = harga * jumlah;
        tr.find('.subtotal-teks').text(subtotal); tr.find('.subtotal-val').val(subtotal);
        updateTotal();
    });

    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove(); updateTotal();
    });

    function updateTotal() {
        let total = 0;
        $('.subtotal-val').each(function() { total += parseInt($(this).val()); });
        $('#labelTotal').text(total);
    }

    // ===============================================
    // FITUR CHECKOUT DENGAN POP-UP SNAP MIDTRANS
    // ===============================================
    $('#btnCheckout').on('click', function() {
        let totalBelanja = parseInt($('#labelTotal').text());
        if(totalBelanja === 0) { Swal.fire('Oops!', 'Keranjang masih kosong!', 'warning'); return; }

        let btn = $(this);
        let teksAsli = btn.text();
        btn.html('<span class="spinner-border spinner-border-sm"></span> Menghubungkan ke Midtrans...');
        btn.prop('disabled', true);

        let dataKeranjang = [];
        $('#tabelKeranjang tbody tr').each(function() {
            dataKeranjang.push({
                idmenu: $(this).find('.idmenu-val').val(),
                harga: parseInt($(this).find('.td-harga').text()),
                jumlah: parseInt($(this).find('.input-jumlah').val()),
                subtotal: parseInt($(this).find('.subtotal-val').val())
            });
        });

        $.ajax({
            type: "POST",
            url: "{{ route('kantin.checkout') }}",
            data: { _token: '{{ csrf_token() }}', total: totalBelanja, keranjang: dataKeranjang },
            success: function(response) {
                if(response.status === 'success') {
                    // MUNCULKAN POP-UP PEMBAYARAN MIDTRANS
                    window.snap.pay(response.snap_token, {
                        onSuccess: function(result){
                            $.ajax({
                                type: "POST",
                                url: "{{ route('kantin.success') }}",
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    order_id: result.order_id
                                },
                                success: function(res) {
                                    if(res.status === 'success') {
                                        Swal.fire({
                                            title: 'LUNAS!',
                                            html: 'Pembayaran berhasil. Silakan simpan QR Code ini:<br><br>' +
                                                  '<img src="' + res.qr_code + '" style="border:2px solid #ccc; border-radius:10px; width:200px;">',
                                            icon: 'success',
                                            confirmButtonText: 'Tutup'
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    }
                                },
                                // INI PENANGKAP ERROR-NYA
                                error: function(err) {
                                    console.log(err.responseText);
                                    Swal.fire('Waduh Error!', 'Gagal generate QR. Coba tekan F12 lalu cek tab Console.', 'error');
                                }
                            });
                        },
                        onPending: function(result){
                            Swal.fire('Menunggu...', 'Silakan selesaikan pembayaranmu.', 'info');
                        },
                        onError: function(result){
                            Swal.fire('Gagal!', 'Pembayaran gagal.', 'error');
                        },
                        onClose: function(){
                            Swal.fire('Dibatalkan', 'Kamu menutup pop-up sebelum menyelesaikan pembayaran.', 'warning');
                            btn.html(teksAsli); btn.prop('disabled', false);
                        }
                    });
                }
            },
            error: function() {
                Swal.fire('Error!', 'Gagal terhubung ke server', 'error');
                btn.html(teksAsli); btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection