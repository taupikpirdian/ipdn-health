<?php

namespace App\Exports;

use App\Models\MedicalCheckUp;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MedicalCheckUpExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    public function collection()
    {
        return MedicalCheckUp::with(['member.city', 'user'])->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Surat',
            'Klasifikasi',
            'Perihal',
            'No Peserta',
            'Nama Peserta',
            'Jenis Kelamin',
            'Kota/Kabupaten',
            'No Kesehatan Tahap I',
            'No Kesehatan Tahap II',
            'Hal Khusus',
            'Nilai Kesehatan',
            'Saran',
            'Dibuat Oleh',
            'Tanggal Dibuat',
        ];
    }

    public function map($data): array
    {
        return [
            $data->nomor,
            ucfirst($data->klasifikasi),
            $data->hal,
            $data->member->nomor ?? '-',
            $data->member->name ?? '-',
            $data->member->jk == 'L' ? 'Pria' : 'Wanita',
            $data->member->city->name ?? '-',
            $data->member->no_kesehatan_tahap1 ?? '-',
            $data->member->no_kesehatan_tahap2 ?? '-',
            $data->hal_khusus ?? '-',
            $data->nilai,
            $data->saran ?? '-',
            $data->user->name ?? 'System',
            optional($data->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Geser heading ke baris 4 → kop header di baris 1-3
                $sheet->insertNewRowBefore(1, 3);

                // Tambahkan kop header
                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1', 'LAPORAN DATA MEDICAL CHECK UP');

                $sheet->mergeCells('A2:N2');
                $sheet->setCellValue('A2', 'Generated pada: ' . now()->format('d/m/Y H:i'));

                // Styling judul
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->getStyle('A2')->getFont()->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Styling header tabel
                $sheet->getStyle('A4:N4')->getFont()->setBold(true);
                $sheet->getStyle('A4:N4')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A4:N4')->getFill()->setFillType('solid')
                    ->getStartColor()->setARGB('FFE0E0E0');
            },
        ];
    }
}
