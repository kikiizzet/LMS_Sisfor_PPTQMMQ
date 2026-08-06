<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = Santri::where('status', 'Lulus')
            ->whereNotNull('tahun_lulus')
            ->distinct()
            ->orderBy('tahun_lulus', 'desc')
            ->pluck('tahun_lulus');

        $query = Santri::where('status', 'Lulus')
            ->orderBy('nama_lengkap', 'asc');

        if ($request->filled('tahun')) {
            $query->where('tahun_lulus', $request->tahun);
        }

        $alumni = $query->get();

        return view('admin.alumni.index', compact('alumni', 'tahunList'));
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        
        $santri->update([
            'status' => 'Aktif',
            'tahun_lulus' => null,
            'keterangan_alumni' => null,
        ]);

        return redirect()->route('admin.alumni.index')->with('success', 'Santri ' . $santri->nama_lengkap . ' berhasil dikembalikan ke status Aktif.');
    }
}
