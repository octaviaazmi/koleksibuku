@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Koleksi Buku </h3>
</div>

<div class="row">
  <div class="col-md-8 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form class="forms-sample" method="POST" action="{{ url('/buku/store') }}">
          @csrf
          <div class="form-group">
            <label>Kode Buku</label>
            <input type="text" name="kode" class="form-control" placeholder="Contoh: NV-01" required>
          </div>
          <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" class="form-control" placeholder="Masukkan Judul" required>
          </div>
          <div class="form-group">
            <label>Pengarang</label>
            <input type="text" name="pengarang" class="form-control" placeholder="Nama Pengarang" required>
          </div>
          <div class="form-group">
            <label>Pilih Kategori</label>
            <select name="idkategori" class="form-control" required style="color: black;">
              <option value="">-- Pilih Kategori --</option>
              @foreach($kategori as $k)
                <option value="{{ $k->idkategori }}">{{ $k->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-gradient-primary btn-rounded me-2">Simpan Buku</button>
          <a href="{{ url('/buku') }}" class="btn btn-light btn-rounded">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection