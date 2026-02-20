@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-pencil"></i>
    </span> Edit Kategori
  </h3>
</div>

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Form Edit Kategori</h4>
        
        <form class="forms-sample" method="POST" action="{{ url('/kategori/'.$kategori->idkategori) }}">
          @csrf
          @method('PUT') <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
          </div>

          <button type="submit" class="btn btn-gradient-primary btn-rounded me-2">Update</button>
          <a href="{{ url('/kategori') }}" class="btn btn-light btn-rounded">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection