<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\KelasSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Tampilkan semua siswa beserta kelasnya.
     */
    public function index()
    {
        $siswas = Siswa::with('kelasSiswa')->get();
        return view('siswa.index', compact('siswa'));
    }

    /**
     * Tampilkan form untuk menambahkan siswa baru.
     */
    public function create()
    {
        $kelasSiswa = KelasSiswa::all();
        return view('siswa.create', compact('kelasSiswa'));
    }

    /**
     * Simpan data siswa baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kartu' => 'required|unique:siswas',
            'nis' => 'required|unique:siswas',
            'nama' => 'required',
            'kelasSiswa_id' => 'required',
            'nama_ortu' => 'required',
            'nomor_ortu' => 'required',
        ]);

        Siswa::create($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data siswa.
     */
    public function edit(Siswa $siswa)
    {
        $kelasSiswa = KelasSiswa::all();
        return view('siswa.edit', compact('siswa', 'kelasSiswa'));
    }

    /**
     * Update data siswa.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'no_kartu' => 'required|unique:siswas,no_kartu,' . $siswa->id,
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required',
            'kelasSiswa_id' => 'required',
            'nama_ortu' => 'required',
            'nomor_ortu' => 'required',
        ]);

        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Data siswa "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    /**
     * Hapus data siswa.
     */
    public function destroy(Siswa $siswa)
    {
        try {
            $nama = $siswa->nama;
            $siswa->delete();
            return redirect()->route('siswa.index')->with('success', 'Siswa "' . $nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus siswa: ' . $e->getMessage());
            return redirect()->route('siswa.index')->with('error', 'Gagal menghapus data siswa.');
        }
    }
}
