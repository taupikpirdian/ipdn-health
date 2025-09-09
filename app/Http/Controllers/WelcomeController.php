<?php

namespace App\Http\Controllers;

use App\Models\MedicalCheckUp;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class WelcomeController extends Controller
{
    public function index()
    {
        $pdfDataUri = null;
        return view('welcome', compact('pdfDataUri'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'nomor_peserta' => 'required|string',
        ]);

        // cek data from db
        $data = MedicalCheckUp::select([
            'medical_check_ups.*',
            'members.name as nama_peserta',
            'members.nomor as nomor_peserta',
            'members.jk as jenis_kelamin',
            'indonesia_cities.name as kota',
        ])->join('members', 'medical_check_ups.id', '=', 'members.medical_check_up_id')
            ->join('indonesia_cities', 'members.city_id', '=', 'indonesia_cities.id')
            ->where('members.nomor', $request->nomor_peserta)
            ->first();

        if (!$data) {
            return redirect()->back()->withErrors(['nomor_peserta' => 'Data tidak ditemukan']);
        }

        // 2. Render PDF dari view
        $pdf = Pdf::loadView('pdf.result', [
            'data' => $data
        ]);

        // 3. Ambil output PDF sebagai string
        $output = $pdf->output();

        // 4. Encode ke base64
        $base64Pdf = base64_encode($output);

        // 5. Format untuk <embed>
        $pdfDataUri = "data:application/pdf;base64," . $base64Pdf;

        // 6. Kirim ke Blade
        return view('welcome', compact('pdfDataUri'));
    }
}
