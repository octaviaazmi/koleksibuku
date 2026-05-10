@extends('layouts.master')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title"> 🏪 Data Toko & Titik Kunjungan </h3>
    <button type="button" class="btn btn-primary font-weight-bold" data-bs-toggle="modal" data-bs-target="#modalTambahToko">
        <i class="mdi mdi-plus"></i> Tambah Toko Baru
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h4 class="card-title">List Toko (Klien)</h4>
        <p class="card-description">Daftar toko untuk dikunjungi oleh sales beserta titik koordinatnya.</p>
        
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%">No</th>
                        <th>Barcode ID</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy (m)</th>
                        <th class="text-center" width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokos as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-dark">{{ $t->barcode }}</span></td>
                            <td class="font-weight-bold">{{ $t->nama_toko }}</td>
                            
                            @if($t->latitude && $t->longitude)
                                <td class="text-success">{{ $t->latitude }}</td>
                                <td class="text-success">{{ $t->longitude }}</td>
                                <td><span class="badge badge-success">{{ round($t->accuracy) }} m</span></td>
                            @else
                                <td colspan="3" class="text-center text-danger font-italic">Lokasi Belum Diatur</td>
                            @endif

                            <td class="text-center">
                                <button class="btn btn-info btn-sm">
                                    <i class="mdi mdi-printer"></i> Cetak Barcode
                                </button>
                                
                                <a href="{{ route('toko.lokasi', $t->id) }}" class="btn btn-warning btn-sm text-dark">
                                    <i class="mdi mdi-map-marker"></i> Set Lokasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data toko. Silakan tambahkan toko baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahToko" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('toko.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Tambah Data Toko</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Nama Toko</label>
            <input type="text" name="nama_toko" class="form-control" placeholder="Contoh: Toko Maju Jaya" required>
        </div>
        <p class="text-muted small">*Barcode akan di-generate secara otomatis oleh sistem.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Toko</button>
      </div>
    </form>
  </div>
</div>
@endsection