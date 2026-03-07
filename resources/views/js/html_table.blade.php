@extends('layouts.master')

@section('content')
<style>
    #tabelBarang tbody tr {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    #tabelBarang tbody tr:hover {
        background-color: #f1f1f1 !important;
    }
</style>

<div class="page-header">
    <h3 class="page-title"> Tabel HTML DOM (Tanpa Database) </h3>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang Baru</h4>
                <form id="formTambah">
                    <div class="form-group">
                        <label>ID Barang</label>
                        <input type="text" class="form-control form-control-sm" id="addId" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control form-control-sm" id="addNama" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" class="form-control form-control-sm" id="addHarga" required>
                    </div>
                    <button type="button" class="btn btn-gradient-primary w-100 btn-rounded" id="btnTambah">Tambahkan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Barang</h4>
                <p class="card-description">Klik baris tabel untuk Edit / Hapus data.</p>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelBarang">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                            </tr>
                        </thead>
                        <tbody id="badanTabel">
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

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditLabel">Manajemen Data Row</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="formEdit">
              <div class="form-group">
                  <label>Nama barang :</label>
                  <input type="text" class="form-control" id="editNama" required>
              </div>
              <div class="form-group">
                  <label>Harga barang :</label>
                  <input type="number" class="form-control" id="editHarga" required>
              </div>
              <button type="button" class="btn btn-gradient-warning btn-sm btn-rounded mb-4" id="btnUbah">Ubah</button>

              <hr>

              <div class="form-group">
                  <label>ID barang :</label>
                  <input type="text" class="form-control bg-light" id="editId" readonly> 
              </div>
              <button type="button" class="btn btn-gradient-danger btn-sm btn-rounded" id="btnHapus">Hapus</button>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
    // Menyimpan baris tabel (TR) yang sedang diklik ke dalam variabel
    let barisTerpilih = null;

    // --- FUNGSI 1: SULAP TOMBOL JADI LOADING ---
    function jalankanDenganLoading(idTombol, teksAwal, idForm, fungsiEksekusi) {
        let form = document.getElementById(idForm);
        let tombol = document.getElementById(idTombol);

        // Validasi HTML5 reportValidity() (Syarat dari Dosen)
        if (!form.checkValidity()) {
            form.reportValidity(); // Memunculkan pop-up bawaan browser jika ada yang kosong
            return; 
        }

        // Ubah tombol jadi loading dan matikan agar tidak bisa di-klik dobel
        tombol.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        tombol.disabled = true;

        // Tunda 1 detik (1000ms) untuk mensimulasikan proses loading
        setTimeout(() => {
            fungsiEksekusi(); // Jalankan fungsi utamanya (Tambah/Ubah/Hapus)
            
            // Kembalikan tombol ke bentuk semula
            tombol.innerHTML = teksAwal;
            tombol.disabled = false;
        }, 1000);
    }


    // --- FUNGSI 2: TAMBAH DATA (CREATE) ---
    document.getElementById('btnTambah').addEventListener('click', function() {
        jalankanDenganLoading('btnTambah', 'Tambahkan', 'formTambah', function() {
            // Ambil nilai dari inputan
            let id = document.getElementById('addId').value;
            let nama = document.getElementById('addNama').value;
            let harga = document.getElementById('addHarga').value;

            // Buat elemen <tr> baru
            let barisBaru = document.createElement('tr');
            barisBaru.innerHTML = `<td>${id}</td><td>${nama}</td><td>${harga}</td>`;

            // Suntikkan <tr> baru tersebut ke dalam <tbody>
            document.getElementById('badanTabel').appendChild(barisBaru);

            // Kosongkan form setelah berhasil
            document.getElementById('formTambah').reset();
        });
    });


    // --- FUNGSI 3: KLIK BARIS MUNCULKAN MODAL (READ/HOVER) ---
    document.getElementById('badanTabel').addEventListener('click', function(e) {
        // Cari elemen <tr> terdekat dari titik yang diklik
        let tr = e.target.closest('tr');
        if (!tr) return; // Kalau yang diklik bukan baris, abaikan

        barisTerpilih = tr; // Simpan baris ini ke memori
        let kolom = tr.querySelectorAll('td');

        // Lempar isi tabel ke dalam form di Modal
        document.getElementById('editId').value = kolom[0].innerText;
        document.getElementById('editNama').value = kolom[1].innerText;
        document.getElementById('editHarga').value = kolom[2].innerText;

        // Tampilkan Modal
        $('#modalEdit').modal('show');
    });


    // --- FUNGSI 4: UBAH DATA (UPDATE) ---
    document.getElementById('btnUbah').addEventListener('click', function() {
        // Untuk ubah, kita validasi formEdit
        jalankanDenganLoading('btnUbah', 'Ubah', 'formEdit', function() {
            let kolom = barisTerpilih.querySelectorAll('td');
            
            // Timpa teks di dalam tabel dengan nilai baru dari inputan modal
            kolom[1].innerText = document.getElementById('editNama').value;
            kolom[2].innerText = document.getElementById('editHarga').value;

            // Tutup modal
            $('#modalEdit').modal('hide');
        });
    });


    // --- FUNGSI 5: HAPUS DATA (DELETE) ---
    document.getElementById('btnHapus').addEventListener('click', function() {
        // Untuk hapus, kita tidak perlu validasi checkValidity karena readonly
        let tombol = document.getElementById('btnHapus');
        
        tombol.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        tombol.disabled = true;

        setTimeout(() => {
            // Hapus baris dari layar
            barisTerpilih.remove();
            
            // Kembalikan tombol dan tutup modal
            tombol.innerHTML = 'Hapus';
            tombol.disabled = false;
            $('#modalEdit').modal('hide');
        }, 1000);
    });

</script>
@endsection