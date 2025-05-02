<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\IzinSakit;
use App\Models\Libur;


class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::with('siswa')->orderBy('tanggal', 'desc')->get();


        return view('absensi.index', compact('absensis'));

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

        $siswa = Siswa::where('no_kartu', $request->uid)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Kartu tidak dikenali.'], 404);
        }

        $tanggal = Carbon::today();
        $now = Carbon::now();

        // Cek izin/sakit
        $izinSakit = IzinSakit::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($izinSakit) {
            return response()->json([
                'message' => "{$siswa->nama} tidak dapat absen karena sedang {$izinSakit->jenis}."
            ]);
        }

        $libur = Libur::whereDate('tanggal', $tanggal)->first();
        if ($libur) {
            return response()->json([
                'message' => "{$siswa->nama} tidak dapat absen karena hari ini libur ({$libur->keterangan})."
            ]);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$absensi) {
            // Absen masuk
            $statusMasuk = $now->format('H:i') > '07:00' ? 'Terlambat' : 'Hadir';

            $absenMasuk = Absensi::create([
                'id' => (string) Str::uuid(),
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $now,
                'status_masuk' => $statusMasuk,
            ]);

            return response()->json([
                'message' => "✅ Absensi Masuk Berhasil\n" .
                            "Nama: {$siswa->nama}\n" .
                            "Jam Masuk: " . $now->format('H:i:s') . "\n" .
                            "Status: {$statusMasuk}"
            ]);
        }

        if (!$absensi->jam_pulang) {
            // Absen pulang
            $absensi->update([
                'jam_pulang' => $now,
                'status_pulang' => 'Pulang',
            ]);

            return response()->json([
                'message' => "✅ Absensi Pulang Berhasil\n" .
                            "Nama: {$siswa->nama}\n" .
                            "Jam Pulang: " . $now->format('H:i:s') . "\n" .
                            "Status: Pulang"
            ]);
        }

        // Sudah lengkap
        return response()->json([
            'message' => "{$siswa->nama} sudah melakukan absensi masuk dan pulang hari ini."
        ]);
    }

    public function showScanPage()
    {
        return view('absensi.scan');
    }
}
