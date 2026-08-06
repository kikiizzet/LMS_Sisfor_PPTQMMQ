<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::orderBy('nama')->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'nullable|unique:gurus',
            'nuptk' => 'nullable|unique:gurus',
            'nama' => 'required|string',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string',
            'wali_kelas' => 'nullable|string',
            'jtm' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ttd' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('gurus', 'public');
        }

        Guru::create($validated);
        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nik' => 'nullable|unique:gurus,nik,' . $guru->id,
            'nuptk' => 'nullable|unique:gurus,nuptk,' . $guru->id,
            'nama' => 'required|string',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string',
            'wali_kelas' => 'nullable|string',
            'jtm' => 'nullable|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'ttd' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('gurus', 'public');
        }

        $guru->update($validated);
        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        try {
            // Nullify wali_kelas_id di Kelas yang mengacu guru ini
            Kelas::where('wali_kelas_id', $guru->id)->update(['wali_kelas_id' => null]);

            // Nullify pembina_id di Ekstrakurikuler yang mengacu guru ini
            Ekstrakurikuler::where('pembina_id', $guru->id)->update(['pembina_id' => null]);

            // Hapus foto dari storage jika ada
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
            }

            $guru->delete();
            return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')->with('error', 'Gagal menghapus data guru. ' . $e->getMessage());
        }
    }
}
