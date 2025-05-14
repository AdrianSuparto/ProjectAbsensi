<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\IzinSakit;
use App\Models\Libur;
use App\Models\KelasSiswa;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\WablasHelper;




class AbsensiController extends Controller
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

        return view('absensi.index', compact('absensis', 'kelasList', 'kelasId'));
    }


    public function create()
    {
        $siswas = Siswa::all();
        return view('absensi.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_pulang' => 'nullable|date_format:H:i:s',
            'status_masuk' => 'nullable|string',
            'status_pulang' => 'nullable|string',
        ]);

        $tanggal = Carbon::today()->toDateString();

        $izinSakit = IzinSakit::where('siswa_id', $validated['siswa_id'])
        ->whereDate('tanggal', $tanggal)
        ->first();

        if ($izinSakit) {
            return redirect()->back()->withErrors(['siswa_id' => 'Siswa ini sudah tercatat izin/sakit hari ini.']);
        }

        // Cek apakah sudah pernah absen
        $sudahAbsen = Absensi::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->withErrors(['siswa_id' => 'Siswa ini sudah absen pada tanggal tersebut.']);
        }

        $validated['id'] = (string) Str::uuid();
        $validated['tanggal'] = \Illuminate\Support\Carbon::parse($validated['tanggal']);

        if ($validated['jam_masuk']) {
            $validated['jam_masuk'] = Carbon::createFromFormat('H:i:s', $validated['jam_masuk']);
        }
        if ($validated['jam_pulang']) {
            $validated['jam_pulang'] = Carbon::createFromFormat('H:i:s', $validated['jam_pulang']);
        }

        Absensi::create($validated);
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }


    public function edit(Absensi $absensi)
    {
        $siswas = Siswa::all();
        return view('absensi.edit', compact('absensi', 'siswas'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_pulang' => 'nullable|date_format:H:i:s',
            'status_masuk' => 'nullable|string',
            'status_pulang' => 'nullable|string',
        ]);

        $validated['tanggal'] = Carbon::parse($validated['tanggal']);
        if ($validated['jam_masuk']) {
            $validated['jam_masuk'] = Carbon::createFromFormat('H:i:s', $validated['jam_masuk']);
        }
        if ($validated['jam_pulang']) {
            $validated['jam_pulang'] = Carbon::createFromFormat('H:i:s', $validated['jam_pulang']);
        }

        $absensi->update($validated);
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Data absensi berhasil dihapus.');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'uid' => 'required|digits:10',
        ]);

        // Cek hari Sabtu/Minggu
        $dayOfWeek = Carbon::now()->dayOfWeek;
        if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
            return response()->json([
                'message' => 'Absensi tidak diperlukan di hari Sabtu/Minggu'
            ]);
        }

        $siswa = Siswa::where('no_kartu', $request->uid)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Kartu tidak dikenali.'], 404);
        }

        $tanggal = Carbon::today();
        $now = Carbon::now();

        // Cek libur nasional
        $libur = Libur::whereDate('tanggal', $tanggal)->first();
        if ($libur) {
            return response()->json([
                'message' => "{$siswa->nama} tidak dapat absen karena hari ini libur ({$libur->keterangan})."
            ]);
        }

        // Cek izin/sakit
        $izinSakit = IzinSakit::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($izinSakit) {
            return response()->json([
                'message' => "{$siswa->nama} tidak dapat absen karena sedang {$izinSakit->jenis}."
            ]);
        }

        // Cek atau buat record absensi dengan default 'Tidak Masuk'
        $absensi = Absensi::firstOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal
            ],
            [
                'id' => (string) Str::uuid(),
                'status_masuk' => 'Tidak Masuk',
                'status_pulang' => 'Tidak Masuk'
            ]
        );

        // Proses absensi masuk/pulang
        if (!$absensi->jam_masuk) {
            $statusMasuk = $now->format('H:i') > '07:00' ? 'Terlambat' : 'Hadir';
            
            $absensi->update([
                'jam_masuk' => $now,
                'status_masuk' => $statusMasuk
            ]);
            
            // Kirim notifikasi
            return response()->json([
                'message' => "✅ Absensi Masuk Berhasil\n" .
                            "Nama: {$siswa->nama}\n" .
                            "Jam Masuk: " . $now->format('H:i:s') . "\n" .
                            "Status: {$statusMasuk}"
            ]);
        }

        if (!$absensi->jam_pulang) {
            $absensi->update([
                'jam_pulang' => $now,
                'status_pulang' => 'Pulang'
            ]);
            
            // Kirim notifikasi
            return response()->json([
                'message' => "✅ Absensi Pulang Berhasil\n" .
                            "Nama: {$siswa->nama}\n" .
                            "Jam Pulang: " . $now->format('H:i:s') . "\n" .
                            "Status: Pulang"
            ]);
        }

        return response()->json([
            'message' => "{$siswa->nama} sudah melakukan absensi lengkap hari ini."
        ]);
    }

    public function showScanPage()
    {
        return view('absensi.scan');
    }

    public function ekspor()
    {
        $filename = 'absensi-6-bulan-terakhir.xlsx';
        return Excel::download(new AbsensiExport, $filename);
    }
    
}
