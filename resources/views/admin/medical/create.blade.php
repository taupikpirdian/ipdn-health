@extends('layouts.admin')
@section('content-header')
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
        @if($is_edit)
            <div class="col-sm-6"><h3 class="mb-0">Edit Medical</h3></div>
        @else
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data</h3></div>
        @endif
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.medical.index') }}">Medical</a></li>
            @if($is_edit)
            <li class="breadcrumb-item"><a href="{{ route('dashboard.medical.edit', $data->id) }}">Edit Medical</a></li>
            @else
            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
            @endif
            </ol>
        </div>
    </div>
    <!--end::Row-->
  </div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        @if($is_edit)
                        <h3 class="card-title m-0">Form Edit Data</h3>
                        @else
                        <h3 class="card-title m-0">Form Tambah Data</h3>
                        @endif
                        <a href="{{ route('dashboard.medical.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan!</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($is_edit)
                        <form action="{{ route('dashboard.medical.update', $data->id) }}" method="POST">
                        @method('PUT')
                    @else
                        <form action="{{ route('dashboard.medical.store') }}" method="POST">
                    @endif
                        @csrf
                        <!-- IDENTITAS PASIEN -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" name="nomor" class="form-control" placeholder="Contoh: R/SKM-001/IX/2025/Biddokkes" value="{{ old('nomor',$data->nomor ?? '') }}" required>
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Klasifikasi <span class="text-danger">*</span></label>
                                <select class="form-select @error('klasifikasi') is-invalid @enderror" 
                                        id="klasifikasi" name="klasifikasi" required>
                                    <option value="">Pilih Klasifikasi</option>
                                    <option value="rahasia" {{ old('klasifikasi',$data->klasifikasi ?? '') == 'rahasi' ? 'selected' : '' }}>Rahasia</option>
                                    <option value="umum" {{ old('klasifikasi',$data->klasifikasi ?? '') == 'umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                                @error('klasifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                <input type="text" name="hal" class="form-control" placeholder="Masukan Perihal" value="{{ old('hal',$data->hal ?? '') }}" required>
                                @error('hal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Peserta <span class="text-danger">*</span></label>
                                <input type="text" name="no_peserta" class="form-control" placeholder="Masukan No Peserta" value="{{ old('no_peserta',$data->member->nomor ?? '') }}" required>
                                @error('no_peserta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Masukan Nama Peserta" value="{{ old('name',$data->member->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender',$data->member->jk ?? '') == 'L' ? 'selected' : '' }}>Pria</option>
                                    <option value="P" {{ old('gender',$data->member->jk ?? '') == 'P' ? 'selected' : '' }}>Wanita</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <select class="form-select @error('city') is-invalid @enderror select2" 
                                        id="city" name="city" required>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city',$data->member->city_id ?? '') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- PEMERIKSAAN FISIK -->
                        <h6 class="fw-bold mt-4">Pemeriksaan</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Kesehatan Tahap I <span class="text-danger">*</span></label>
                                <input type="text" name="no_kesehatan_tahap1" class="form-control" placeholder="Masukan No Kesehatan Tahap I" value="{{ old('no_kesehatan_tahap1',$data->member->no_kesehatan_tahap1 ?? '') }}" required>
                                @error('no_kesehatan_tahap1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Kesehatan Tahap II </label>
                                <input type="text" name="no_kesehatan_tahap2" class="form-control" placeholder="Masukan No Kesehatan Tahap II" value="{{ old('no_kesehatan_tahap2',$data->member->no_kesehatan_tahap2 ?? '') }}">
                                @error('no_kesehatan_tahap2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Hal khusus yang ditemukan --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Hal Khusus yang Ditemukan</label>
                                <textarea name="hal_khusus" rows="3" class="form-control">{{ old('hal_khusus',$data->hal_khusus ?? '') }}</textarea>
                                @error('hal_khusus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Nilai status kesehatan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nilai Status Kesehatan <span class="text-danger">*</span></label>
                                <textarea name="nilai" rows="3" class="form-control" required>{{ old('nilai',$data->nilai ?? '') }}</textarea>
                                @error('nilai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- SARAN -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Saran</label>
                                <textarea name="saran" rows="3" class="form-control">{{ old('saran',$data->saran ?? '') }}</textarea>
                                @error('saran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard.medical.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0"><i class="fas fa-info-circle"></i> Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb"></i> Petunjuk Pengisian:</h6>
                        <ul class="mb-0 small">
                            <li><b>Nomor Surat</b> diisi dengan format resmi, contoh: <code>R/SKM-001/IX/2025/Biddokkes</code>.</li>
                            <li><b>Klasifikasi</b> pilih sesuai kebutuhan (Rahasia atau Umum).</li>
                            <li><b>Perihal</b> wajib diisi sesuai dengan tujuan surat.</li>
                            <li><b>No Peserta</b> diisi dengan nomor peserta yang sah.</li>
                            <li><b>Nama</b> wajib diisi sesuai identitas peserta.</li>
                            <li><b>Jenis Kelamin</b> pilih sesuai identitas peserta (Pria/Wanita).</li>
                            <li><b>Kota/Kabupaten</b> pilih domisili peserta.</li>
                            <li><b>No Kesehatan Tahap I & II</b> wajib diisi sesuai hasil pemeriksaan.</li>
                            <li><b>Hal Khusus yang Ditemukan</b> hanya diisi jika ada temuan khusus pada pemeriksaan.</li>
                            <li><b>Nilai Status Kesehatan</b> wajib diisi dengan hasil penilaian (contoh: <code>77 Baik (B)</code>).</li>
                            <li><b>Saran</b> boleh diisi dengan catatan atau rekomendasi tambahan (gunakan enter jika lebih dari satu poin).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var is_edit = {!! json_encode($is_edit) !!};
        // when $is_edit == true, remove required on password and confirmation_password
        if (is_edit) {
            $('#password-label').text('Password');
            $('#password-confirmation-label').text('Konfirmasi Password');
            $('#password').attr('required', false);
            $('#password_confirmation').attr('required', false);
        }
    });
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            return false;
        }
    });
</script>
@endsection