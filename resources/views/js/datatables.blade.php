@extends('layouts.master')

@section('style_page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    /* Kursor jari saat hover di baris tabel */
    #tabelBarangDT tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    #tabelBarangDT tbody tr:hover {
        background-color: #f1f1f1 !important;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title"> Tabel DataTables DOM (Tanpa Database) </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang Baru</h4>
                <form id="formTambahDT">
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" class="form-control form-control-sm" id="dtAddId" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control form-control-sm" id="dtAddNama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" class="form-control form-control-sm" id="dtAddHarga" required>
                    </div>
                    <button type="button" class="btn btn-gradient-info w-100 btn-rounded" id="btnTambahDT">Tambahkan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Barang (DataTables)</h4>
                <p class="card-description">Klik baris tabel untuk Edit / Hapus data.</p>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelBarangDT">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BRG001</td>
                                <td>Buku Tulis Sinar Dunia</td>
                                <td>45000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditDT" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manajemen Data Row (DataTables)</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="formEditDT">
              <div class="form-group">
                  <label>Nama barang :</label>
                  <input type="text" class="form-control" id="dtEditNama" required>
              </div>
              <div class="form-group">
                  <label>Harga barang :</label>
                  <input type="number" class="form-control" id="dtEditHarga" required>
              </div>
              <button type="button" class="btn btn-gradient-warning btn-sm btn-rounded mb-4" id="btnUbahDT">Ubah</button>

              <hr>

              <div class="form-group">
                  <label>ID barang :</label>
                  <input type="text" class="form-control bg-light" id="dtEditId" readonly> 
              </div>
              <button type="button" class="btn btn-gradient-danger btn-sm btn-rounded" id="btnHapusDT">Hapus</button>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript_page')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    console.log("✅ SISTEM AMAN: JQuery dari Template Bekerja!");

    let tabelDT = $('#tabelBarangDT').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
    });
    let rowTerpilihDT = null;

    // FUNGSI LOADING
    function jalankanDenganLoadingDT(idTombol, teksAwal, idForm, fungsiEksekusi) {
        let form = document.getElementById(idForm);
        let tombol = document.getElementById(idTombol);

        if (!form.checkValidity()) {
            form.reportValidity();
            return; 
        }

        tombol.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        tombol.disabled = true;

        setTimeout(() => {
            try {
                fungsiEksekusi();
            } catch (error) {
                console.error("Error:", error);
            }
            tombol.innerHTML = teksAwal;
            tombol.disabled = false;
        }, 1000);
    }

    // --- EVENT TAMBAH ---
    $('#btnTambahDT').on('click', function(e) {
        e.preventDefault();
        jalankanDenganLoadingDT('btnTambahDT', 'Tambahkan', 'formTambahDT', function() {
            let id = $('#dtAddId').val();
            let nama = $('#dtAddNama').val();
            let harga = $('#dtAddHarga').val();

            // API DataTables
            tabelDT.row.add([ id, nama, harga ]).draw(false);
            
            $('#formTambahDT')[0].reset();
        });
    });

    // --- KLIK BARIS MUNCULKAN MODAL ---
    $('#tabelBarangDT tbody').on('click', 'tr', function () {
        if ($(this).find('.dataTables_empty').length > 0) return;

        rowTerpilihDT = tabelDT.row(this);
        let dataArray = rowTerpilihDT.data();

        $('#dtEditId').val(dataArray[0]);
        $('#dtEditNama').val(dataArray[1]);
        $('#dtEditHarga').val(dataArray[2]);

        $('#modalEditDT').modal('show');
    });

    // --- EVENT UBAH ---
    $('#btnUbahDT').on('click', function(e) {
        e.preventDefault();
        jalankanDenganLoadingDT('btnUbahDT', 'Ubah', 'formEditDT', function() {
            let id = $('#dtEditId').val();
            let nama = $('#dtEditNama').val();
            let harga = $('#dtEditHarga').val();

            rowTerpilihDT.data([ id, nama, harga ]).draw(false);
            $('#modalEditDT').modal('hide');
        });
    });

    // --- EVENT HAPUS ---
    $('#btnHapusDT').on('click', function(e) {
        e.preventDefault();
        let tombol = document.getElementById('btnHapusDT');
        
        tombol.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        tombol.disabled = true;

        setTimeout(() => {
            try {
                rowTerpilihDT.remove().draw(false);
                $('#modalEditDT').modal('hide');
            } catch (error) {}
            tombol.innerHTML = 'Hapus';
            tombol.disabled = false;
        }, 1000);
    });

});
</script>
@endsection