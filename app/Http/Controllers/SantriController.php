<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with('kelas');
        
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('no_induk', 'like', '%' . $request->search . '%');
        }

        $santris = $query->latest()->paginate(20);
        $kelasList = Kelas::all();

        return view('admin.santri.index', compact('santris', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        return view('admin.santri.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_induk' => 'required|string|max:50|unique:santris',
            'nisn' => 'nullable|string|max:50',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'status' => 'required|in:Aktif,Lulus,Pindah',
        ]);

        Santri::create($validated);

        return redirect()->route('admin.santri.index')->with('success', 'Data Santri berhasil ditambahkan.');
    }

    public function edit(Santri $santri)
    {
        $kelasList = Kelas::all();
        return view('admin.santri.edit', compact('santri', 'kelasList'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_induk' => 'required|string|max:50|unique:santris,no_induk,' . $santri->id,
            'nisn' => 'nullable|string|max:50',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'status' => 'required|in:Aktif,Lulus,Pindah',
        ]);

        $santri->update($validated);

        return redirect()->route('admin.santri.index')->with('success', 'Data Santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        try {
            $santri->delete();
            return redirect()->route('admin.santri.index')->with('success', 'Data Santri berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.santri.index')->with('error', 'Gagal menghapus data santri, mungkin ada relasi yang terkait.');
        }
    }
}
