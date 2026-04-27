@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title"> 👥 Data Customer </h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Customer (Hasil Jepretan Kamera)</h4>
        <p class="card-description">Menampilkan foto hasil simpanan format BLOB maupun File Fisik.</p>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="5%">ID</th>
                        <th>Nama Customer</th>
                        <th width="30%" class="text-center">Foto (Format BLOB)</th>
                        <th width="30%" class="text-center">Foto (Format FILE)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td>{{ $c->idcustomer }}</td>
                            <td><b>{{ $c->nama }}</b></td>
                            <td class="text-center">
                                @if($c->foto_blob)
                                    <img src="{{ $c->foto_blob }}" alt="blob" style="width: 150px; height: auto; border-radius: 5px;">
                                @else
                                    <span class="text-muted"><i>Kosong</i></span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($c->foto_path)
                                    <img src="{{ asset('storage/' . $c->foto_path) }}" alt="file" style="width: 150px; height: auto; border-radius: 5px;">
                                @else
                                    <span class="text-muted"><i>Kosong</i></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data customer. Coba jepret foto pertamamu!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection