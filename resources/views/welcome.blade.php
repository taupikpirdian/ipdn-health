<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Institut Pemerintahan Dalam Negeri (IPDN)</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-dark text-light">

<div class="container py-5 text-center" id="login-section">
  <!-- Header -->
  <h5 class="fw-bold">SISTEM AKSES TERINTEGRASI SECARA CEPAT <br/>FEEDBACK HASIL RIKKES</h5>

  <!-- Logo -->
  <img src="{{ asset('assets/images/logo/logo.png') }}" class="my-4" style="width:180px;" alt="Logo DOKKES">

  <h3 class="fw-bold">Institut Pemerintahan Dalam Negeri (IPDN)</h3>

  <!-- Login Card -->
  <div class="card mx-auto mt-4" style="max-width:430px;">
    <div class="card-body">
      <form id="check-form" method="POST" action="{{ route('welcome.check') }}">
        @csrf
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input 
              type="text" 
              class="form-control @error('nomor_peserta') is-invalid @enderror" 
              name="nomor_peserta" 
              value="{{ old('nomor_peserta') }}"
              placeholder="Masukkan Nomor Peserta">
        
          @error('nomor_peserta')
            <div class="invalid-feedback" style="text-align: left">
              {{ $message }}
            </div>
          @enderror
        </div>
        <button type="submit" class="btn btn-warning w-100">Cek Pemeriksaan</button>
        <a type="button" href="{{ route('dashboard.index') }}" class="btn btn-danger w-100 mt-3">Halaman Login</a>
      </form>
    </div>
  </div>

  @if($pdfDataUri)
  <div id="result-section" class="row justify-content-center mt-5">
    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title">Hasil Pemeriksaan</h6>
          <p class="card-text">Cek kesehatan Anda dengan mudah dan cepat.</p>
          <embed src="{{ $pdfDataUri }}" width="100%" height="500px" type="application/pdf">
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Footer -->
  <p class="position-fixed bottom-0 start-50 translate-middle-x mb-2 small text-light">
    Copyright © KESMAPTA DOKKES POLRI All right reserved | Version :
  </p>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
