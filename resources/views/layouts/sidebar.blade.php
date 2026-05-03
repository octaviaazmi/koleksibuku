<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
          <span class="text-secondary text-small">Mahasiswa Unair</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>

    <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/home') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::segment(1) == 'kategori' ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/kategori') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::segment(1) == 'buku' ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/buku') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('generator.index') }}">
        <span class="menu-title">Generator Dokumen</span>
        <i class="mdi mdi-file-document-outline menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('barang.index') }}">
        <span class="menu-title">Data Barang (Tag)</span>
        <i class="mdi mdi-tag-multiple menu-icon"></i>
      </a>
      <li class="nav-item">
    <a class="nav-link {{ Route::is('barang.scan') ? 'active' : '' }}" href="{{ route('barang.scan') }}">
        <span class="menu-title">Scan Barcode Barang</span>
        <i class="mdi mdi-barcode-scan menu-icon"></i>
    </a>
</li>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#js-menu" aria-expanded="false" aria-controls="js-menu">
        <span class="menu-title">Tugas Javascript</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-code-tags menu-icon"></i>
      </a>
      <div class="collapse" id="js-menu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('js.html') }}">Tabel HTML DOM</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('js.datatables') }}">DataTables DOM</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('js.select') }}">Select Kota</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#kasir-menu" aria-expanded="false" aria-controls="kasir-menu">
        <span class="menu-title">Aplikasi Kasir</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-cart menu-icon"></i>
      </a>
      <div class="collapse" id="kasir-menu">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('kasir.ajax') }}">Kasir AJAX</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('kasir.axios') }}">Kasir AXIOS</a></li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" href="{{ route('kantin.index') }}">
        <span class="menu-title">Kantin Online (Midtrans)</span>
        <i class="mdi mdi-food menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('vendor.index') }}">
        <span class="menu-title">Dashboard Vendor</span>
        <i class="mdi mdi-store menu-icon"></i>
      </a>
      <li class="nav-item">
    <a class="nav-link {{ Route::is('vendor.scan') ? 'active' : '' }}" href="{{ route('vendor.scan') }}">
        <span class="menu-title">Scan QR Vendor</span>
        <i class="mdi mdi-qrcode-scan menu-icon text-success"></i>
    </a>
</li>
    </li>

    <li class="nav-item {{ Request::is('customer*') ? 'active' : '' }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" aria-expanded="{{ Request::is('customer*') ? 'true' : 'false' }}" aria-controls="customer-menu">
            <span class="menu-title">Menu Customer (Kamera)</span>
            <i class="menu-arrow"></i>
            <i class="mdi mdi-camera menu-icon"></i>
        </a>
        <div class="collapse {{ Request::is('customer*') ? 'show' : '' }}" id="customer-menu">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link {{ Route::is('customer.index') ? 'active' : '' }}" href="{{ route('customer.index') }}">Data Customer</a></li>
                <li class="nav-item"> <a class="nav-link {{ Route::is('customer.blob') ? 'active' : '' }}" href="{{ route('customer.blob') }}">Tambah 1 (BLOB)</a></li>
                <li class="nav-item"> <a class="nav-link {{ Route::is('customer.file') ? 'active' : '' }}" href="{{ route('customer.file') }}">Tambah 2 (FILE)</a></li>
                
                <!-- INI MENU BARUNYA -->
                <li class="nav-item"> <a class="nav-link {{ Route::is('customer.riwayat') ? 'active' : '' }}" href="{{ route('customer.riwayat') }}">Riwayat Pesanan</a></li>
            </ul>
        </div>
    </li>
  </ul>
</nav>