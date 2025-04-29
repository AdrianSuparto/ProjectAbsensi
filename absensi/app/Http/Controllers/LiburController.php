<?php

namespace App\Http\Controllers;

use App\Models\Libur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LiburController extends Controller
{
    /**
     * Tampilkan daftar semua libur.
     */
    public function index()
    {
        $liburs = Libur::orderBy('tanggal', 'asc')->get();
        Carbon::setLocale('id');
        // Format tanggal pakai Carbon sebelum dikirim ke view (opsional)
        foreach ($liburs as $libur) {
            $libur->formatted_tanggal = Carbon::parse($libur->tanggal)->translatedFormat('l, d F Y');
        }

        return view('libur.index', compact('liburs'));
    }

    /**
     * Tampilkan form untuk membuat data libur baru.
     */
    public function create()
    {
        return view('libur.create');
    }

    /**
     * Simpan data libur baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date|unique:liburs,tanggal',
            'keterangan' => 'required|string|max:255',
        ]);

        Libur::create($validatedData);

        return redirect()->route('libur.index')->with('success', 'Data libur berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail 1 libur (opsional, jika dipakai).
     */
    public function show(Libur $libur)
    {
        $libur->formatted_tanggal = Carbon::parse($libur->tanggal)->translatedFormat('l, d F Y');
        return view('libur.show', compact('libur'));
    }

    /**
     * Tampilkan form untuk edit data libur.
     */
    public function edit(Libur $libur)
    {
        return view('libur.edit', compact('libur'));
    }

    /**
     * Perbarui data libur di database.
     */
    public function update(Request $request, Libur $libur)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date|unique:liburs,tanggal,' . $libur->id,
            'keterangan' => 'required|string|max:255',
        ]);

        $libur->update($validatedData);

        return redirect()->route('libur.index')->with('success', 'Data libur berhasil diperbarui.');
    }

    /**
     * Hapus data libur dari database.
     */
    public function destroy(Libur $libur)
    {
        try {
            $libur->delete();
            return redirect()->route('libur.index')->with('success', 'Data libur berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data libur: ' . $e->getMessage());
            return redirect()->route('libur.index')->with('error', 'Gagal menghapus data libur.');
        }
    }
}
