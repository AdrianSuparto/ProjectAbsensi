<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\KelasSiswa;  

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas');

        $query = Absensi::with(['siswa.kelasSiswa'])->orderBy('tanggal', 'desc');

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_siswa_id', $kelasId);
            });
        }

        $absensis = $query->get();

        // Ambil semua daftar kelas
        $kelasList = KelasSiswa::all();

        return view('dashboard', compact('absensis', 'kelasList', 'kelasId'));
    }
}
