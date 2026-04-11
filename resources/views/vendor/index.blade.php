@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 🏪 Dashboard Vendor: <b class="text-primary">{{ $vendor_aktif->nama_vendor }}</b> </h3>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('vendor.index') }}" method="GET" class="d-flex align-items-center">
            <label class="mb-0 mr-3 text-muted">Simulasi Login Sebagai: </label>
            <select name="vendor_id" class="form-control form-control-sm w-25 mr-2" onchange="this.form.submit()">
                @foreach($semua_vendor as $v)
                    <option value="{{ $v->idvendor }}" {{ $vendor_id == $v->idvendor ? 'selected' : '' }}>
                        {{ $v->nama_vendor }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-info">➕ Tambah Master Menu</h4>
                <p class="card-description">Masukkan menu baru untuk dijual</p>
                
                <form action="{{ route('vendor.menu.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="idvendor" value="{{ $vendor_id }}">
                    
                    <div class="form-group">
                        <label>Nama Menu</label>
                        <input type="text" class="form-control" name="nama_menu" required placeholder="Contoh: Nasi Uduk">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" required placeholder="Contoh: 12000">
                    </div>
                    <button type="submit" class="btn btn-gradient-info w-100">Simpan Menu</button>
                </form>

                <hr>
                <h5 class="mt-4">Menu Anda Saat Ini:</h5>
                <ul class="list-arrow">
                    @foreach($menus as $m)
                        <li>{{ $m->nama_menu }} - <b>Rp {{ number_format($m->harga, 0, ',', '.') }}</b></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-success">✅ Pesanan Masuk (Status LUNAS)</h4>
                <p class="card-description">Pesanan yang sudah dibayar oleh Customer</p>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="bg-success text-white">
                                <th>Order ID</th>
                                <th>Nama Customer</th>
                                <th>Menu yg Dipesan</th>
                                <th>Waktu Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan_lunas as $pesanan)
                                <tr>
                                    <td><b>{{ $pesanan->idpesanan }}</b></td>
                                    <td>{{ $pesanan->nama }}</td>
                                    <td>
                                        <ul class="pl-3 mb-0 text-muted">
                                            @foreach($pesanan->detail as $detail)
                                                @if($detail->menu->idvendor == $vendor_id)
                                                    <li>{{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $pesanan->updated_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada pesanan lunas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection