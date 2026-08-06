<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class TeachingController extends Controller
{
    public function index(Request $request)
    {
        $query = Teaching::with('guru', 'kelas');

        // Filter berdasarkan tingkat (dari kelas)
        if ($request->filled('tingkat')) {
            $query->whereHas('kelas', function ($q) {
                $q->where('tingkat', request('tingkat'));
            });
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $teachings = $query->orderBy('created_at', 'desc')->get();
        
        // Ambil data untuk filter dropdown
        $tingkats = Kelas::distinct('tingkat')->where('is_active', true)->pluck('tingkat');
        $kelas_list = Kelas::where('is_active', true)->orderBy('nama_kelas')->get();

        return view('admin.teaching.index', compact('teachings', 'tingkats', 'kelas_list'));
    }

    public function create()
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        $kelas = Kelas::where('is_active', true)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.teaching.create', compact('gurus', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string',
            'induk' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'jtm' => 'nullable|integer',
        ]);

        Teaching::create($validated);
        return redirect()->route('admin.teaching.index')->with('success', 'Data Mengajar berhasil ditambahkan.');
    }

    public function edit(Teaching $teaching)
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        $kelas = Kelas::where('is_active', true)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.teaching.edit', compact('teaching', 'gurus', 'kelas'));
    }

    public function update(Request $request, Teaching $teaching)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran' => 'required|string',
            'induk' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'jurusan' => 'nullable|string',
            'jtm' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $teaching->update($validated);
        return redirect()->route('admin.teaching.index')->with('success', 'Data Mengajar berhasil diperbarui.');
    }

    public function destroy(Teaching $teaching)
    {
        $teaching->delete();
        return redirect()->route('admin.teaching.index')->with('success', 'Data Mengajar berhasil dihapus.');
    }
}
