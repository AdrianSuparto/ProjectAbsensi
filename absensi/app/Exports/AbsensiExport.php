<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfDay();

        return Absensi::with('siswa')
            ->where('tanggal', '>=', $sixMonthsAgo)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($absen) {
                return [
                    'ID Siswa' => $absen->siswa_id,
                    'Nama Siswa' => optional($absen->siswa)->nama ?? '-',
                    'Kelas' => optional($absen->siswa->kelasSiswa)->nama ?? '-',
                    'Tanggal' => $absen->tanggal->format('Y-m-d'),
                    'Jam Masuk' => $absen->jam_masuk,
                    'Status Masuk' => $absen->status_masuk,
                    'Jam Pulang' => $absen->jam_pulang,
                    'Status Pulang' => $absen->status_pulang,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Siswa',
            'Nama Siswa',
            'Kelas',
            'Tanggal',
            'Jam Masuk',
            'Status Masuk',
            'Jam Pulang',
            'Status Pulang',
        ];
    }
}

