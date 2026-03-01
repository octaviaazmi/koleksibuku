@extends('layouts.master')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-tag-multiple"></i>
    </span> Data Barang & Cetak Tag Harga
  </h3>
  <nav aria-label="breadcrumb">
    <a href="{{ route('barang.create') }}" class="btn btn-gradient-primary btn-rounded btn-fw">
      <i class="mdi mdi-plus"></i> Tambah Barang
    </a>
  </nav>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal!</strong> {{ session('error') }}
    </div>
@endif

<div class="card">
  <div class="card-body">
    <form action="{{ route('barang.cetak_tag') }}" method="POST" target="_blank">
        @csrf
        
        <div class="row mb-4 bg-light p-3 rounded border">
            <div class="col-md-4">
                <label class="font-weight-bold">Mulai Cetak di Kolom (X):</label>
                <input type="number" name="x" class="form-control form-control-sm" min="1" max="5" value="1" required>
            </div>
            <div class="col-md-4">
                <label class="font-weight-bold">Mulai Cetak di Baris (Y):</label>
                <input type="number" name="y" class="form-control form-control-sm" min="1" max="8" value="1" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-gradient-info btn-rounded btn-fw w-100">
                    <i class="mdi mdi-printer"></i> Cetak Tag Harga
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tableBarang">
                <thead class="bg-primary text-white">
                    <tr>
                        <th style="width: 50px;" class="text-center">Pilih</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga (Rp)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $item)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="id_barang[]" value="{{ $item->id_barang }}">
                        </td>
                        <td class="font-weight-bold">{{ $item->id_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-sm btn-gradient-warning btn-rounded">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            
                            <button type="button" class="btn btn-sm btn-gradient-danger btn-rounded" onclick="if(confirm('Yakin ingin menghapus barang ini?')) { document.getElementById('form-delete-{{ $item->id_barang }}').submit(); }">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    @foreach($barang as $item)
    <form id="form-delete-{{ $item->id_barang }}" action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    @endforeach

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableBarang').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
            "columnDefs": [ { "orderable": false, "targets": [0, 4] } ]
        });
    });
</script>
@endsection