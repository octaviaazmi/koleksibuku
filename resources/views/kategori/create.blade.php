@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-plus"></i>
    </span> Tambah Kategori
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/kategori') }}">Kategori</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Form Kategori Baru</h4>
        <p class="card-description"> Masukkan nama kategori buku yang ingin ditambahkan </p>
        
        <form class="forms-sample" method="POST" action="{{ url('/kategori/store') }}">
          @csrf
          <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" 
                   name="nama_kategori" 
                   class="form-control @error('nama_kategori') is-invalid @enderror" 
                   id="nama_kategori" 
                   placeholder="Contoh: Novel, Komik, Biografi" 
                   required>
            @error('nama_kategori')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-gradient-primary btn-rounded me-2">
            <i class="mdi mdi-content-save"></i> Simpan
          </button>
          
          <a href="{{ url('/kategori') }}" class="btn btn-light btn-rounded">
            Batal
          </a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection