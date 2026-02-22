@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-book-open-page-variant"></i>
    </span> Data Buku
  </h3>
  
  <nav aria-label="breadcrumb">
    <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-rounded btn-fw">
      <i class="mdi mdi-plus"></i> Tambah Buku
    </a>
  </nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Koleksi Buku Kamu</h4>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> Kode </th>
                <th> Judul </th>
                <th> Pengarang </th>
                <th> Kategori </th>
                <th class="text-center"> Aksi </th>
              </tr>
            </thead>
            <tbody>
              @foreach($buku as $b)
              <tr>
                <td> {{ $b->kode }} </td>
                <td> {{ $b->judul }} </td>
                <td> {{ $b->pengarang }} </td>
                <td> 
                  <label class="badge badge-info">{{ $b->kategori->nama_kategori }}</label>
                </td>
                <td class="text-center">
                <a href="{{ url('/buku/'.$b->idbuku.'/edit') }}" class="btn btn-inverse-info btn-rounded btn-sm btn-icon d-inline-flex align-items-center justify-content-center">
                    <i class="mdi mdi-pencil"></i>
                </a>
                
                <form action="{{ url('/buku/'.$b->idbuku) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-inverse-danger btn-rounded btn-sm btn-icon" onclick="return confirm('Hapus buku ini?')">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection