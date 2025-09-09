@extends('layouts.admin')

@section('content-header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Detail Medical</h3></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard.medical.index') }}">Medical</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title m-0">Informasi Medical</h3>
                    <a href="{{ route('dashboard.medical.index') }}" class="btn btn-sm btn-secondary ms-auto">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nomor Surat</dt>
                        <dd class="col-sm-8">{{ $data->nomor }}</dd>

                        <dt class="col-sm-4">Klasifikasi</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $data->klasifikasi == 'rahasia' ? 'danger' : 'success' }}">
                                {{ ucfirst($data->klasifikasi) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Perihal</dt>
                        <dd class="col-sm-8">{{ $data->hal }}</dd>

                        <dt class="col-sm-4">No Peserta</dt>
                        <dd class="col-sm-8">{{ $data->member->nomor ?? '-' }}</dd>

                        <dt class="col-sm-4">Nama Peserta</dt>
                        <dd class="col-sm-8">{{ $data->member->name ?? '-' }}</dd>

                        <dt class="col-sm-4">Jenis Kelamin</dt>
                        <dd class="col-sm-8">
                            {{ $data->member->jk == 'L' ? 'Pria' : 'Wanita' }}
                        </dd>

                        <dt class="col-sm-4">Kota/Kabupaten</dt>
                        <dd class="col-sm-8">{{ $data->member->city->name ?? '-' }}</dd>

                        <dt class="col-sm-4">No Kesehatan Tahap I</dt>
                        <dd class="col-sm-8">{{ $data->member->no_kesehatan_tahap1 ?? '-' }}</dd>

                        <dt class="col-sm-4">No Kesehatan Tahap II</dt>
                        <dd class="col-sm-8">{{ $data->member->no_kesehatan_tahap2 ?? '-' }}</dd>

                        <dt class="col-sm-4">Hal Khusus</dt>
                        <dd class="col-sm-8">{{ $data->hal_khusus ?? '-' }}</dd>

                        <dt class="col-sm-4">Nilai Kesehatan</dt>
                        <dd class="col-sm-8">{{ $data->nilai }}</dd>

                        <dt class="col-sm-4">Saran</dt>
                        <dd class="col-sm-8">{{ $data->saran ?? '-' }}</dd>

                        <dt class="col-sm-4">Dibuat Oleh</dt>
                        <dd class="col-sm-8">{{ $data->user->name ?? 'System' }}</dd>

                        <dt class="col-sm-4">Tanggal Dibuat</dt>
                        <dd class="col-sm-8">{{ $data->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title m-0"><i class="fas fa-info-circle"></i> Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('dashboard.medical.edit', $data->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="submit" class="btn btn-danger w-100" onclick="deleteData({{ $data->id }})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function destroy(id) {
        var url = "{{ route('dashboard.medical.destroy', ':id') }}".replace(':id', id);
        callDataWithAjax(url, 'POST', {
            _method: "DELETE"
        }).then((data) => {
            Swal.fire({
                title: 'Success',
                text: `Data berhasil dihapus`,
                icon: 'success',
                confirmButtonText: 'OK'
            });
            setTimeout(function() {
                location.reload();
            }, 500);
        }).catch((xhr) => {
            alert('Error: ' + xhr.responseText);
        })
    }
</script>
@endsection