<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Verifikasi Keamanan - Purple Admin</title>
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
  <style>
    .auth-form-light { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .otp-input {
      letter-spacing: 12px;
      font-size: 28px !important;
      font-weight: 800 !important;
      text-indent: 12px;
      border: 2px solid #ebedf2 !important;
      border-radius: 10px !important;
      transition: 0.3s;
    }
    .otp-input:focus { border-color: #b66dff !important; background: #f8f9fa; }
  </style>
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo text-center">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
              </div>
              <h4 class="text-center font-weight-bold">Verifikasi OTP</h4>
              <h6 class="font-weight-light text-center mb-4 text-muted">Cek inbox Mailtrap kamu dan masukkan 6 digit kode rahasia.</h6>
              
              <form class="pt-3" method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-group mb-4">
                  <input type="text" name="otp" class="form-control form-control-lg text-center otp-input" placeholder="000000" maxlength="6" required autofocus autocomplete="off">
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    <i class="mdi mdi-shield-check mr-2"></i> VERIFIKASI SEKARANG
                  </button>
                </div>

                @if(session('error'))
                  <div class="alert alert-danger mt-4 p-2 text-center small" style="border-radius: 8px;">
                    <i class="mdi mdi-alert-circle-outline"></i> {{ session('error') }}
                  </div>
                @endif
                
                <div class="text-center mt-4 font-weight-light small">
                  Tidak menerima kode? <a href="#" onclick="location.reload();" class="text-primary">Kirim ulang</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
</body>
</html>