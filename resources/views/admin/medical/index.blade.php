@extends('layouts.admin')
@section('content-header')
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Medical Check</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Medical Check</li>
        </ol>
      </div>
    </div>
    <!--end::Row-->
  </div>
@endsection
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center w-100">
                <h3 class="card-title m-0">Data Medical</h3>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('dashboard.medical.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <a href="{{ route('dashboard.medical.export-excel') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
        <table id="example" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Nomor Peserta</th>
                <th>Nama</th>
                <th>Kota</th>
                <th>Nilai</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
        </div>  
    </div>
</div>

@endsection
@section('scripts')
<script>
    var dataTable = $("#example").DataTable({
        //   "scrollX": true,
          processing: true,
          serverSide: true,
          autoWidth: true,
          orderCellsTop: true,
          fixedHeader: true,
        //   sDom: 'lrtip',
          fixedColumns: {
              right: 1,
              left: 0,
          },
          ajax: "{{ route('dashboard.medical.datatable') }}",
          columns: [
              {
                  data: 'DT_RowIndex',
                  orderable: false
              },
              {
                  data: 'nomor',
                  name: 'nomor'
              },
              {
                  data: 'member_nomor',
                  name: 'member_nomor'
              },
              {
                  data: 'member_name',
                  name: 'member_name'
              },
              {
                  data: 'city_name',
                  name: 'city_name'
              },
              {
                  data: 'nilai',
                  name: 'nilai'
              },
              {
                  data: 'created_at',
                  name: 'created_at'
              },
              {
                  data: 'action',
                  orderable: false
              }
          ],
          order: [
              [6, 'desc']
          ]
    });

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
