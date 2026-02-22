@extends('layouts.master')
@section('content')
<div class="page-header">
  <h3 class="page-title"> Generator Dokumen </h3>
</div>
<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body text-center">
        <i class="mdi mdi-email-outline text-primary" style="font-size: 50px;"></i>
        <h4 class="mt-3">Cetak Undangan</h4>
        <p class="text-muted">Format A4 Portrait</p>
        <a href="{{ route('undangan.cetak') }}" class="btn btn-gradient-primary btn-rounded">Download PDF</a>
      </div>
    </div>
  </div>
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body text-center">
        <i class="mdi mdi-certificate text-danger" style="font-size: 50px;"></i>
        <h4 class="mt-3">Cetak Sertifikat</h4>
        <p class="text-muted">Format A4 Landscape</p>
        <a href="{{ route('sertifikat.cetak') }}" class="btn btn-gradient-danger btn-rounded">Download PDF</a>
      </div>
    </div>
  </div>
</div>
@endsection