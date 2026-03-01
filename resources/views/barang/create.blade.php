@extends('layouts.master')
@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Data Barang </h3>
</div>
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Informasi Barang Baru</h4>
        <p class="card-description"> ID Barang akan dibuat otomatis oleh sistem. </p>
        <form class="forms-sample" action="{{ route('barang.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
          </div>
          <div class="form-group">
            <label>Harga Barang (Rp)</label>
            <input type="number" name="harga" class="form-control" placeholder="Contoh: 15000" required>
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2 btn-rounded">Simpan Data</button>
          <a href="{{ route('barang.index') }}" class="btn btn-light btn-rounded">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection