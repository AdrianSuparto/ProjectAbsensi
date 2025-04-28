<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_kartu' => 'required|unique:siswas',
            'nis' => 'required|unique:siswas',
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_ortu' => 'required',
            'nomor_ortu' => 'required',
        ]);

        Siswa::create($validated);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'no_kartu' => 'required|unique:siswas,no_kartu,' . $siswa->id,
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_ortu' => 'required',
            'nomor_ortu' => 'required',
        ]);

        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
