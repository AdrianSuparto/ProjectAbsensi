<?php

namespace App\Http\Controllers;

use App\Models\KelasSiswa;
use Illuminate\Http\Request;

class KelasSiswaController extends Controller
{
    /**
     * Tampilkan semua data KelasSiswa.
     */
    public function index()
    {
        $kelasSiswa = KelasSiswa::all();
        return view('kelasSiswa.index', compact('kelasSiswa'));
    }

    /**
     * Tampilkan form untuk membuat KelasSiswa baru.
     */
    public function create()
    {
        return view('kelasSiswa.create');
    }

    /**
     * Simpan data KelasSiswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        KelasSiswa::create($request->all());

        return redirect()->route('kelasSiswa.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail dari satu KelasSiswa.
     */
    public function show(KelasSiswa $kelasSiswa)
    {
        $kelasSiswa->load('siswa');
        return view('kelasSiswa.show', compact('kelasSiswa'));
    }

    /**
     * Tampilkan form untuk mengedit KelasSiswa.
     */
    public function edit(KelasSiswa $kelasSiswa)
    {
        return view('kelasSiswa.edit', compact('kelasSiswa'));
    }

    /**
     * Update data KelasSiswa.
     */
    public function update(Request $request, KelasSiswa $kelasSiswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kelasSiswa->update($request->all());

        return redirect()->route('kelasSiswa.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus data KelasSiswa.
     */
    public function destroy(KelasSiswa $kelasSiswa)
    {
        $kelasSiswa->delete();
        return redirect()->route('kelasSiswa.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
