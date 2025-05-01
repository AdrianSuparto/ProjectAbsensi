<?php

namespace App\Http\Controllers;

use App\Models\IzinSakit;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\KelasSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IzinSakitController extends Controller
{
    /**
     * Tampilkan semua data izin/sakit.
     */
    public function index()
    {
        $izinSakits = IzinSakit::with('siswa')->orderBy('tanggal', 'desc')->get();

        foreach ($izinSakits as $izinSakit) {
            $izinSakit->formatted_tanggal = Carbon::parse($izinSakit->tanggal)->translatedFormat('l, d F Y');
        }

        return view('izinSakit.index', compact('izinSakits'));
    }


    /**
     * Tampilkan form tambah data izin/sakit.
     */
    public function create()
    {
        $siswas = Siswa::all();
        return view('izinSakit.create', compact('siswas'));
    }

    /**
     * Simpan data izin/sakit baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis' => 'required|in:Izin,Sakit',
            'keterangan' => 'nullable|string',
        ]);

        $tanggal = Carbon::now()->toDateString();

        // Cek apakah siswa sudah absen
        $sudahAbsen = Absensi::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->withErrors(['siswa_id' => 'Siswa ini sudah absen hari ini, tidak bisa dicatat izin/sakit.']);
        }

        // Cek apakah sudah ada izin sebelumnya
        $sudahIzin = IzinSakit::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahIzin) {
            return redirect()->back()->withErrors(['siswa_id' => 'Siswa ini sudah memiliki data izin/sakit hari ini.']);
        }

        $validated['tanggal'] = $tanggal;

        // Simpan izin/sakit
        $izinSakit = IzinSakit::create($validated);

        // Membuat absensi dengan status Izin/Sakit
        Absensi::create([
            'siswa_id' => $izinSakit->siswa_id,
            'tanggal' => $izinSakit->tanggal,
            'jam_masuk' => null,  // Karena tidak ada jam masuk
            'status_masuk' => ucfirst($izinSakit->jenis), // Izin/Sakit
            'jam_pulang' => null, // Karena tidak ada jam pulang
            'status_pulang' => ucfirst($izinSakit->jenis), // Izin/Sakit
        ]);

        return redirect()->route('izinSakit.index')->with('success', 'Data izin/sakit berhasil ditambahkan.');
    }


    /**
     * Tampilkan form edit data izin/sakit.
     */
    public function edit(IzinSakit $izinSakit)
    {
        $siswas = Siswa::all();
        return view('izinSakit.edit', compact('izinSakit', 'siswa'));
    }

    /**
     * Update data izin/sakit.
     */
    public function update(Request $request, IzinSakit $izinSakit)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:Izin,Sakit',
            'keterangan' => 'nullable|string',
        ]);

        $validated['tanggal'] = Carbon::parse($validated['tanggal']);

        $izinSakit->update($validated);
        return redirect()->route('izinSakit.index')->with('success', 'Data izin/sakit berhasil diperbarui.');
    }

    /**
     * Hapus data izin/sakit.
     */
    public function destroy(IzinSakit $izinSakit)
    {
        $izinSakit->delete();
        return redirect()->route('izinSakit.index')->with('success', 'Data izin/sakit berhasil dihapus.');
    }
}
