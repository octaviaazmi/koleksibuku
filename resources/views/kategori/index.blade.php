@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-format-list-bulleted"></i>
    </span> Data Kategori
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ url('/kategori/create') }}" class="btn btn-gradient-primary btn-rounded btn-fw">
            <i class="mdi mdi-plus"></i> Tambah Kategori
        </a>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Kategori Buku</h4>
        <p class="card-description"> Kelola kategori koleksi buku kamu di sini </p>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> ID </th>
                <th> Nama Kategori </th>
                <th class="text-center"> Aksi </th>
              </tr>
            </thead>
            <tbody>
              @foreach($kategori as $k)
              <tr>
                <td class="py-1">
                  {{ $k->idkategori }}
                </td>
                <td> {{ $k->nama_kategori }} </td>
                <td class="text-center">
                <a href="{{ url('/kategori/'.$k->idkategori.'/edit') }}" class="btn btn-inverse-info btn-rounded btn-sm btn-icon d-inline-flex align-items-center justify-content-center">
                    <i class="mdi mdi-pencil"></i>
                </a>
                
                <form action="{{ url('/kategori/'.$k->idkategori) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-inverse-danger btn-rounded btn-sm btn-icon" onclick="return confirm('Yakin mau hapus kategori ini?')">
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