<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MutasiKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $santris = [];
        
        // Load all active santris for individual mutasi dropdown
        $allSantris = Santri::where('status', 'Aktif')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        if ($request->filled('kelas_asal_id')) {
            $santris = Santri::where('kelas_id', $request->kelas_asal_id)
                             ->where('status', 'Aktif')
                             ->orderBy('nama_lengkap', 'asc')
                             ->get();
        }

        return view('admin.mutasi_kelas.index', compact('kelasList', 'santris', 'allSantris'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'kelas_tujuan_id' => 'required|exists:kelas,id',
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id'
        ]);

        if ($request->kelas_asal_id == $request->kelas_tujuan_id) {
            return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal.');
        }

        Santri::whereIn('id', $request->santri_ids)
            ->update(['kelas_id' => $request->kelas_tujuan_id]);

        return redirect()->route('admin.mutasi-kelas.index')->with('success', count($request->santri_ids) . ' santri berhasil dipindahkan ke kelas tujuan.');
    }

    /**
     * Pindah kelas untuk 1 santri secara langsung.
     */
    public function prosesIndividu(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kelas_tujuan_id' => 'required|exists:kelas,id',
        ]);

        $santri = Santri::findOrFail($request->santri_id);

        if ($santri->kelas_id == $request->kelas_tujuan_id) {
            return back()->with('error', 'Kelas tujuan tidak boleh sama dengan kelas asal santri.');
        }

        $santri->update([
            'kelas_id' => $request->kelas_tujuan_id
        ]);

        return redirect()->route('admin.mutasi-kelas.index')->with('success', 'Santri ' . $santri->nama_lengkap . ' berhasil dipindahkan ke kelas tujuan.');
    }

    /**
     * Proses naik kelas/tinggal kelas/lulus massal akhir semester.
     */
    public function prosesNaikKelas(Request $request)
    {
        $request->validate([
            'kelas_asal_id' => 'required|exists:kelas,id',
            'actions' => 'required|array',
            'actions.*' => 'required|in:naik,tetap,lulus',
            'kelas_tujuan_ids' => 'nullable|array',
        ]);

        $kelasAsalId = $request->kelas_asal_id;
        $actions = $request->actions;
        $kelasTujuanIds = $request->kelas_tujuan_ids ?? [];
        $currentYear = date('Y');

        $processedCount = 0;

        foreach ($actions as $santriId => $action) {
            $santri = Santri::where('id', $santriId)
                ->where('kelas_id', $kelasAsalId)
                ->where('status', 'Aktif')
                ->first();

            if (!$santri) {
                continue;
            }

            if ($action === 'naik') {
                $tujuanId = $kelasTujuanIds[$santriId] ?? null;
                if ($tujuanId && $tujuanId != $kelasAsalId) {
                    $santri->update([
                        'kelas_id' => $tujuanId
                    ]);
                    $processedCount++;
                }
            } elseif ($action === 'lulus') {
                $santri->update([
                    'status' => 'Lulus',
                    'kelas_id' => null,
                    'tahun_lulus' => $currentYear,
                ]);
                $processedCount++;
            }
            // Jika 'tetap', kelas_id dan status tidak berubah.
        }

        return redirect()->route('admin.mutasi-kelas.index')->with('success', $processedCount . ' santri berhasil diproses naik/lulus kelas.');
    }
}
