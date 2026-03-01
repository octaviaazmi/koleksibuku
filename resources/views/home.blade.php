@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-home"></i>
    </span> Dashboard Koleksi Buku
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  
  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-danger card-img-holder text-white btn-rounded">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Total Buku <i class="mdi mdi-book-open-page-variant mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalBuku }} Data</h2>
      </div>
    </div>
  </div>

  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-info card-img-holder text-white btn-rounded">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-bookmark-multiple mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalKategori }} Data</h2>
      </div>
    </div>
  </div>

  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-success card-img-holder text-white btn-rounded">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Barang UMKM <i class="mdi mdi-tag-multiple mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalBarang }} Data</h2>
      </div>
    </div>
  </div>

  <div class="col-md-3 stretch-card grid-margin">
    <div class="card bg-gradient-warning card-img-holder text-white btn-rounded">
      <div class="card-body">
        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
        <h4 class="font-weight-normal mb-3">Pengguna <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
        </h4>
        <h2 class="mb-5">{{ $totalUser }} Akun</h2>
      </div>
    </div>
  </div>

</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card btn-rounded">
            <div class="card-body text-center py-5">
                <h4 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 🚀</h4>
                <p class="card-text text-muted">Sistem Manajemen Koleksi Buku dan Generator Dokumen telah siap digunakan.</p>
            </div>
        </div>
    </div>
</div>
@endsection