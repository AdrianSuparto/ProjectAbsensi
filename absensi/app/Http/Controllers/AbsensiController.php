<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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

        $validated['id'] = (string) Str::uuid(); // karena pakai UUID

        // Carbon parsing
        $validated['tanggal'] = Carbon::parse($validated['tanggal']);
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

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $tanggal)
            ->first();

        if (!$absensi) {
            // Absen pertama (masuk)
            Absensi::create([
                'id' => (string) Str::uuid(),
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $now,
                'status_masuk' => $now->format('H:i') > '07:00' ? 'Terlambat' : 'Hadir',
            ]);
            return response()->json(['message' => "Absen Masuk: {$siswa->nama}"]);
        }

        if (!$absensi->jam_pulang) {
            // Absen kedua (pulang)
            $absensi->update([
                'jam_pulang' => $now,
                'status_pulang' => 'Pulang',
            ]);
            return response()->json(['message' => "Absen Pulang: {$siswa->nama}"]);
        }

        // Sudah absen masuk dan pulang
        return response()->json(['message' => "{$siswa->nama} sudah absen lengkap hari ini."]);
    }

    public function showScanPage()
    {
        return view('absensi.scan');
    }
}
