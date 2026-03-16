@extends('layouts.master')

@section('style_page')
<link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Manipulasi Dropdown Kota </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 text-white">Select</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Kota:</label>
                    <div class="input-group">
                        <input type="text" id="inputKotaBiasa" class="form-control" placeholder="Ketik nama kota (ex: Surabaya)">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-gradient-primary" type="button" id="btnTambahBiasa">Tambahkan</button>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Select Kota:</label>
                    <select id="dropdownBiasa" class="form-control">
                        <option value="" selected disabled>-- Pilih Kota --</option>
                        <option value="Jakarta">Jakarta</option>
                    </select>
                </div>

                <div class="mt-3 p-3 bg-light rounded border border-primary">
                    <h5 class="mb-0 font-weight-bold">Kota Terpilih: 
                        <span id="hasilBiasa" class="text-primary">-</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="card-title mb-0 text-white">select 2</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Kota:</label>
                    <div class="input-group">
                        <input type="text" id="inputKotaSelect2" class="form-control" placeholder="Ketik nama kota (ex: Bandung)">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-gradient-info" type="button" id="btnTambahSelect2">Tambahkan</button>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Select Kota:</label>
                    <select id="dropdownSelect2" class="form-control" style="width:100%">
                        <option value="" selected disabled>-- Pilih Kota --</option>
                        <option value="Semarang">Semarang</option>
                    </select>
                </div>

                <div class="mt-3 p-3 bg-light rounded border border-info">
                    <h5 class="mb-0 font-weight-bold">Kota Terpilih: 
                        <span id="hasilSelect2" class="text-info">-</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript_page')
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

<script>
$(document).ready(function() {
    
    // ========================================================
    // BAGIAN 1: LOGIKA UNTUK SELECT BIASA
    // ========================================================
    
    // A. Saat tombol tambah diklik
    $('#btnTambahBiasa').on('click', function() {
        let kotaBaru = $('#inputKotaBiasa').val().trim(); 
        
        if (kotaBaru !== "") {
            // HTML DOM: Buat elemen <option> baru
            let opsiBaru = `<option value="${kotaBaru}">${kotaBaru}</option>`;
            
            // Injeksi ke dalam select
            $('#dropdownBiasa').append(opsiBaru);
            
            // Kosongkan inputannya lagi
            $('#inputKotaBiasa').val('');
            
            // Langsung buat kota itu terpilih
            $('#dropdownBiasa').val(kotaBaru).trigger('change');
        } else {
            alert("Nama kota tidak boleh kosong ya!");
        }
    });

    // B. Saat pilihan dropdown berubah, tampilkan namanya di bawah
    $('#dropdownBiasa').on('change', function() {
        let kotaTerpilih = $(this).val();
        $('#hasilBiasa').text(kotaTerpilih);
    });


    // ========================================================
    // BAGIAN 2: LOGIKA UNTUK PLUGIN SELECT2
    // ========================================================
    
    // 0. Wajib inisialisasi plugin Select2-nya dulu
    $('#dropdownSelect2').select2({
        theme: "bootstrap", 
        placeholder: "-- Pilih Kota --"
    });

    // A. Saat tombol tambah diklik
    $('#btnTambahSelect2').on('click', function() {
        let kotaBaru = $('#inputKotaSelect2').val().trim();
        
        if (kotaBaru !== "") {
            // Karena ini Select2, kita pakai API Option javascript
            let opsiBaru = new Option(kotaBaru, kotaBaru, false, true);
            
            // Injeksi ke dalam select, dan WAJIB ditambahkan .trigger('change') agar Select2 me-refresh tampilannya
            $('#dropdownSelect2').append(opsiBaru).trigger('change');
            
            $('#inputKotaSelect2').val('');
        } else {
            alert("Nama kota tidak boleh kosong ya!");
        }
    });

    // B. Saat pilihan dropdown berubah
    $('#dropdownSelect2').on('change', function() {
        let kotaTerpilih = $(this).val();
        $('#hasilSelect2').text(kotaTerpilih);
    });

});
</script>
@endsection