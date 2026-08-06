<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('kurikulum')) {
            $query->where('kurikulum', $request->kurikulum);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_mapel', 'like', "%$s%")
                  ->orWhere('kode', 'like', "%$s%");
            });
        }

        $mapels = $query->orderBy('urutan')->orderBy('nama_mapel')->get();
        $kurikulums = Mapel::distinct()->whereNotNull('kurikulum')->pluck('kurikulum');

        return view('admin.mapel.index', compact('mapels', 'kurikulums'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'nama_mapel' => 'required|string|max:255',
            'induk' => 'nullable|string|max:255',
            'kelompok' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'jjm' => 'nullable|integer',
            'urutan' => 'nullable|integer',
            'kurikulum' => 'nullable|string|max:255',
        ]);

        Mapel::create($validated);
        return redirect()->route('admin.mapel.index')->with('success', 'Data Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'kode' => 'nullable|string|max:50',
            'nama_mapel' => 'required|string|max:255',
            'induk' => 'nullable|string|max:255',
            'kelompok' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'jjm' => 'nullable|integer',
            'urutan' => 'nullable|integer',
            'kurikulum' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $mapel->update($validated);
        return redirect()->route('admin.mapel.index')->with('success', 'Data Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        try {
            $mapel->delete();
            return redirect()->route('admin.mapel.index')->with('success', 'Data Mata Pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.mapel.index')->with('error', 'Gagal menghapus. Mungkin masih ada data mengajar yang terkait.');
        }
    }
}
