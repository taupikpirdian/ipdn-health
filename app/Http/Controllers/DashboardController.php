<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\MedicalCheckUp;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // total data medical
        $totalData = MedicalCheckUp::count();
        // Jumlah Peserta Unique
        $totalMember = Member::select('nomor')->distinct()->count();
        // Jumlah Peserta Rahasia
        $totalRahasia = MedicalCheckUp::where('klasifikasi', 'Rahasia')->count();
        // Jumlah Peserta Umum
        $totalUmum = MedicalCheckUp::where('klasifikasi', '!=', 'Rahasia')->count();
        // Distribusi Jenis Kelamin
        // data: [90, 60]
        // labels: ['Laki-laki', 'Perempuan']
        // Ambil distribusi dari DB
        $distribusiJenisKelamin = Member::select('jk', DB::raw('count(*) as total'))
            ->groupBy('jk')
            ->pluck('total', 'jk'); // hasil: ['L' => 5] misalnya

        // Siapkan default
        $jkLabel = ['Laki-laki', 'Perempuan'];
        $jkData = [
            $distribusiJenisKelamin['L'] ?? 0, // default 0 kalau tidak ada
            $distribusiJenisKelamin['P'] ?? 0,
        ];

        // peserta per kota
        // label: ["Bandung", "Garut", "Tasikmalaya", "Bekasi", "Jakarta"]
        // data: [30, 25, 20, 15, 10]
        $kotaData = Member::select('indonesia_cities.name as kota', DB::raw('count(*) as total'))
            ->join('indonesia_cities', 'members.city_id', '=', 'indonesia_cities.id')
            ->groupBy('kota')
            ->pluck('total', 'kota');
        // Ubah ke struktur array chart-friendly
        $kotaChart = [
            'labels' => $kotaData->keys()->toArray(),  // ["Bandung", "Garut", "Tasikmalaya", ...]
            'data'   => $kotaData->values()->toArray() // [30, 25, 20, ...]
        ];

        // Klasifikasi Peserta
        $klasifikasiData = [
            'Rahasia' => $totalRahasia,
            'Umum' => $totalUmum
        ];

        // Histogram Nilai Kesehatan
        $healthData = MedicalCheckUp::selectRaw("
            CASE 
                WHEN CAST(REGEXP_SUBSTR(nilai, '[0-9]+') AS UNSIGNED) BETWEEN 50 AND 60 THEN '50-60'
                WHEN CAST(REGEXP_SUBSTR(nilai, '[0-9]+') AS UNSIGNED) BETWEEN 61 AND 70 THEN '61-70'
                WHEN CAST(REGEXP_SUBSTR(nilai, '[0-9]+') AS UNSIGNED) BETWEEN 71 AND 80 THEN '71-80'
                WHEN CAST(REGEXP_SUBSTR(nilai, '[0-9]+') AS UNSIGNED) BETWEEN 81 AND 90 THEN '81-90'
                WHEN CAST(REGEXP_SUBSTR(nilai, '[0-9]+') AS UNSIGNED) BETWEEN 91 AND 100 THEN '91-100'
                ELSE '101+'
            END AS kategori,
            COUNT(*) as total
        ")
            ->groupBy('kategori')
            ->orderByRaw("
            FIELD(kategori, '50-60','61-70','71-80','81-90','91-100','101+')
        ")
            ->pluck('total', 'kategori');

        // Ubah ke struktur array chart-friendly
        $healthData = [
            $healthData['50-60'] ?? 0,
            $healthData['61-70'] ?? 0,
            $healthData['71-80'] ?? 0,
            $healthData['81-90'] ?? 0,
            $healthData['91-100'] ?? 0,
            $healthData['101+'] ?? 0,
        ];

        return view('admin.index', compact('totalData', 'totalMember', 'totalRahasia', 'totalUmum', 'distribusiJenisKelamin', 'jkData', 'jkLabel', 'kotaChart', 'klasifikasiData', 'healthData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
