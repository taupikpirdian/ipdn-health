<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\MedicalCheckUp;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicalCheckUpExport;

class MedicalCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.medical.index');
    }

    public function datatable(Request $request)
    {
        if (request()->ajax()) {
            /**
             * column shown in the table
             */
            // check from model Report
            $columns = [
                'medical_check_ups.id',
                'medical_check_ups.nomor',
                'members.nomor',
                'members.name',
                'city_name',
                'medical_check_ups.nilai',
                'medical_check_ups.created_at',
            ];

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $posts = MedicalCheckUp::select([
                'medical_check_ups.*',
                'members.nomor as member_nomor',
                'members.name as member_name',
                'indonesia_cities.name as city_name',
            ])->join('members', 'medical_check_ups.id', '=', 'members.medical_check_up_id')
                ->join('indonesia_cities', 'members.city_id', '=', 'indonesia_cities.id')
                ->orderBy('created_at', 'desc');

            if ($request->search['value']) {
                $posts = $posts->where('reporter_name', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('reporter_phone', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('issue', 'like', '%' . $request->search['value'] . '%');
            }

            $totalData = $posts->count();
            $posts = $posts->skip($start)->take($limit)->orderBy($order, $dir)->get();
            $data = array();
            if (!empty($posts)) {
                foreach ($posts as $key => $post) {
                    $button = '';
                    $button .= '<a href="' . route('dashboard.medical.show', $post->id) . '" type="button" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>';

                    $button .= '<a href="' . route('dashboard.medical.edit', $post->id) . '" type="button" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';

                    $button .= '<button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="deleteData(' . $post->id . ')">
                                <i class="fas fa-trash"></i>
                            </button>';

                    $htmlButton = '<div class="btn-group" role="group">
                            ' . $button . '
                        </div>';

                    $nestedData['nomor'] = $post->nomor;
                    $nestedData['member_nomor'] = $post->member_nomor;
                    $nestedData['member_name'] = $post->member_name;
                    $nestedData['city_name'] = $post->city_name;
                    $nestedData['nilai'] = $post->nilai;
                    $nestedData['created_at'] = Carbon::parse($post->created_at)->format('d/m/Y H:i');
                    $nestedData['action'] = $htmlButton;
                    $nestedData['DT_RowIndex'] = ($key + 1) + $start;

                    $data[] = $nestedData;
                }
            }

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalData),
                "data"            => $data
            );

            return response()->json($json_data);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $is_edit = false;
        $data = null;
        $cities = City::all();
        return view('admin.medical.create', compact('is_edit', 'data', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'nomor'               => 'required|string|max:255',
            'klasifikasi'         => 'required',
            'hal'                 => 'required|string|max:255',
            'no_peserta'          => 'required|string|max:50',
            'name'                => 'required|string|max:255',
            'gender'              => 'required|in:L,P',
            'city'                => 'required|integer|exists:indonesia_cities,id',
            'no_kesehatan_tahap1' => 'required|string|max:255',
            'hal_khusus'          => 'nullable|string',
            'nilai'               => 'required|string',
            'saran'               => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 2. Simpan ke tabel medical_checks
            $medical = MedicalCheckUp::create([
                'nomor'       => $validated['nomor'],
                'klasifikasi' => $validated['klasifikasi'],
                'hal'         => $validated['hal'],
                'hal_khusus'  => $validated['hal_khusus'] ?? null,
                'nilai'       => $validated['nilai'],
                'saran'       => $validated['saran'] ?? null,
                'created_by'  => Auth::id(), // user yang login
            ]);

            // 3. Simpan ke tabel pesertas
            Member::create([
                'medical_check_up_id' => $medical->id,
                'nomor'               => $validated['no_peserta'],
                'name'                => $validated['name'],
                'jk'                  => $validated['gender'],
                'city_id'             => $validated['city'], // kalau kamu bedain kab_id & kota_id bisa disesuaikan
                'no_kesehatan_tahap1' => $validated['no_kesehatan_tahap1'],
                'no_kesehatan_tahap2' => $request->input('no_kesehatan_tahap2') ?? null,
            ]);

            DB::commit();
            return redirect()->route('dashboard.medical.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $is_edit = false;
        $data = MedicalCheckUp::findOrFail($id);
        $cities = City::all();
        return view('admin.medical.show', compact('is_edit', 'data', 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $is_edit = true;
        $data = MedicalCheckUp::with('member')->findOrFail($id);
        $cities = City::all();
        return view('admin.medical.create', compact('is_edit', 'data', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi input (sama seperti store)
        $validated = $request->validate([
            'nomor'               => 'required|string|max:255',
            'klasifikasi'         => 'required',
            'hal'                 => 'required|string|max:255',
            'no_peserta'          => 'required|string|max:50',
            'name'                => 'required|string|max:255',
            'gender'              => 'required|in:L,P',
            'city'                => 'required|integer|exists:indonesia_cities,id',
            'no_kesehatan_tahap1' => 'required|string|max:255',
            'hal_khusus'          => 'nullable|string',
            'nilai'               => 'required|string',
            'saran'               => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 2. Cari data medical
            $medical = MedicalCheckUp::findOrFail($id);

            // 3. Update tabel medical_checks
            $medical->update([
                'nomor'       => $validated['nomor'],
                'klasifikasi' => $validated['klasifikasi'],
                'hal'         => $validated['hal'],
                'hal_khusus'  => $validated['hal_khusus'] ?? null,
                'nilai'       => $validated['nilai'],
                'saran'       => $validated['saran'] ?? null,
                'updated_by'  => Auth::id(), // user yang login
            ]);

            // 4. Update peserta terkait
            $member = Member::where('medical_check_up_id', $medical->id)->firstOrFail();
            $member->update([
                'nomor'               => $validated['no_peserta'],
                'name'                => $validated['name'],
                'jk'                  => $validated['gender'],
                'city_id'             => $validated['city'],
                'no_kesehatan_tahap1' => $validated['no_kesehatan_tahap1'],
                'no_kesehatan_tahap2' => $request->input('no_kesehatan_tahap2') ?? null,
            ]);

            DB::commit();
            return redirect()->route('dashboard.medical.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            // 1. Cari data medical
            $medical = MedicalCheckUp::findOrFail($id);

            // 2. Hapus peserta yang terkait
            Member::where('medical_check_up_id', $medical->id)->delete();

            // 3. Hapus medical check
            $medical->delete();

            DB::commit();
            return redirect()->route('dashboard.medical.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        $timestamp = Carbon::now()->format('dmY_Hi');
        $fileName = "{$timestamp}_MedicalCheck.xlsx";

        return Excel::download(new MedicalCheckUpExport, $fileName);
    }
}
