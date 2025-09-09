@extends('layouts.admin')
@section('styles')
<style>
  body {
    background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  }
  .card {
    border-radius: 1rem;
  }
  .stat-card {
    color: #fff;
  }
  .bg1 { background: #36A2EB; }
  .bg2 { background: #FF6384; }
  .bg3 { background: #FF9F40; }
  .bg4 { background: #4BC0C0; }
</style>
@endsection

@section('content-header')
  <div class="container-fluid">
    <!--begin::Row-->
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </div>
    </div>
    <!--end::Row-->
  </div>
@endsection

@section('content')
<div class="container-fluid py-4">
  <!-- Row: Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm stat-card bg1 text-center">
        <div class="card-body">
          <h6>Total Data Medical</h6>
          <h3 id="totalData">{{ $totalData }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm stat-card bg2 text-center">
        <div class="card-body">
          <h6>Jumlah Peserta (Unique)</h6>
          <h3 id="jumlahPeserta">{{ $totalMember }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm stat-card bg3 text-center">
        <div class="card-body">
          <h6>Jumlah Peserta Rahasia</h6>
          <h3 id="pesertaRahasia">{{ $totalRahasia }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm stat-card bg4 text-center">
        <div class="card-body">
          <h6>Jumlah Peserta Umum</h6>
          <h3 id="pesertaUmum">{{ $totalUmum }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Row: Charts -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title">Distribusi Jenis Kelamin</h6>
          <div id="genderChart"></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title">Peserta per Kota</h6>
          <div id="cityChart"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-2">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title">Klasifikasi Peserta</h6>
          <div id="klasifikasiChart"></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="card-title">Histogram Nilai Kesehatan</h6>
          <div id="healthChart"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<!-- apexcharts -->
<script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"
></script>

<!-- ApexCharts Script -->
<script>
  // Dummy Data
  const genderData = @json($jkData);
  const kotaChart = @json($kotaChart);
  const klsData = @json($klasifikasiData);
  const cityLabels = kotaChart.labels;
  const cityData = kotaChart.data;
  const klasifikasiData = [klsData['Rahasia'], klsData['Umum']]; // Rahasia, Umum
  const healthData = @json($healthData); // contoh histogram kategori

  // Gender Chart
  new ApexCharts(document.querySelector("#genderChart"), {
    chart: { type: 'pie', height: 300 },
    series: genderData,
    labels: ["Pria", "Wanita"],
    colors: ["#36A2EB", "#FF6384"]
  }).render();

  // City Chart
  new ApexCharts(document.querySelector("#cityChart"), {
    chart: { type: 'bar', height: 300 },
    series: [{ name: "Jumlah Peserta", data: cityData }],
    xaxis: { categories: cityLabels },
    colors: ["#4BC0C0"]
  }).render();

  // Klasifikasi Chart
  new ApexCharts(document.querySelector("#klasifikasiChart"), {
    chart: { type: 'donut', height: 300 },
    series: klasifikasiData,
    labels: ["Rahasia", "Umum"],
    colors: ["#FF9F40", "#9966FF"]
  }).render();

  // Health Chart (Histogram)
  new ApexCharts(document.querySelector("#healthChart"), {
    chart: { type: 'bar', height: 300 },
    series: [{ name: "Jumlah Peserta", data: healthData }],
    xaxis: { categories: ["50-60", "61-70", "71-80", "81-90", "91-100", "101+"] },
    colors: ["#FFCE56"]
  }).render();
</script>
@endsection
