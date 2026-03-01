@extends('layouts.master')
@section('content')
<div class="page-header">
  <h3 class="page-title"> Edit Data Barang </h3>
</div>
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Informasi Barang</h4>
        <p class="card-description"> Mengedit data untuk ID: <strong>{{ $barang->id_barang }}</strong> </p>
        <form class="forms-sample" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
          </div>
          <div class="form-group">
            <label>Harga Barang (Rp)</label>
            <input type="number" name="harga" class="form-control" value="{{ $barang->harga }}" required>
          </div>
          <button type="submit" class="btn btn-gradient-warning me-2 btn-rounded">Update Data</button>
          <a href="{{ route('barang.index') }}" class="btn btn-light btn-rounded">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection