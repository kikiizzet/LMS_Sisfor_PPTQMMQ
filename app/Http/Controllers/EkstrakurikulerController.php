<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekstrakurikulers = Ekstrakurikuler::with('pembina')->orderBy('nama_ekstrakurikuler')->get();
        return view('admin.ekstrakurikuler.index', compact('ekstrakurikulers'));
    }

    public function create()
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        return view('admin.ekstrakurikuler.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ekstrakurikuler' => 'required|string|unique:ekstrakurikulers',
            'pembina_id' => 'nullable|exists:gurus,id',
        ]);

        Ekstrakurikuler::create($validated);
        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler)
    {
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        return view('admin.ekstrakurikuler.edit', compact('ekstrakurikuler', 'gurus'));
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $validated = $request->validate([
            'nama_ekstrakurikuler' => 'required|string|unique:ekstrakurikulers,nama_ekstrakurikuler,' . $ekstrakurikuler->id,
            'pembina_id' => 'nullable|exists:gurus,id',
            'is_active' => 'nullable|boolean',
        ]);

        $ekstrakurikuler->update($validated);
        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        try {
            $ekstrakurikuler->delete();
            return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.ekstrakurikuler.index')->with('error', 'Gagal menghapus data ekstrakurikuler. Mungkin masih ada data rapor yang terkait.');
        }
    }
}
