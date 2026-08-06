<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas',
            'jumlah_siswa' => 'nullable|integer',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tingkat' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'jenis' => 'nullable|string',
            'kurikulum' => 'nullable|string',
        ]);

        Kelas::create($validated);
        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kela', 'gurus'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,' . $kela->id,
            'jumlah_siswa' => 'nullable|integer',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
            'tingkat' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'jenis' => 'nullable|string',
            'kurikulum' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $kela->update($validated);
        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        try {
            $kela->delete();
            return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus data kelas. Mungkin masih ada data rapor yang terkait.');
        }
    }
}
