<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Halaman utama presensi: menampilkan daftar santri + status hari ini,
     * bisa filter per kelas dan per tanggal.
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $kelasId = $request->get('kelas_id');

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Query santri aktif
        $query = Santri::with('kelas')
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $santris = $query->get();

        // Ambil data presensi yang sudah ada untuk tanggal ini
        $presensiHariIni = Presensi::whereDate('tanggal', $tanggal)
            ->pluck('status', 'santri_id')
            ->toArray();

        $keteranganHariIni = Presensi::whereDate('tanggal', $tanggal)
            ->pluck('keterangan', 'santri_id')
            ->toArray();

        // Hitung statistik
        $totalHadir = count(array_filter($presensiHariIni, fn($s) => $s === 'Hadir'));
        $totalSakit = count(array_filter($presensiHariIni, fn($s) => $s === 'Sakit'));
        $totalIzin = count(array_filter($presensiHariIni, fn($s) => $s === 'Izin'));
        $totalAlfa = count(array_filter($presensiHariIni, fn($s) => $s === 'Alfa'));
        $totalBelum = $santris->count() - count($presensiHariIni);

        return view('admin.presensi.index', compact(
            'santris', 'kelasList', 'tanggal', 'kelasId',
            'presensiHariIni', 'keteranganHariIni',
            'totalHadir', 'totalSakit', 'totalIzin', 'totalAlfa', 'totalBelum'
        ));
    }

    /**
     * Simpan presensi massal (bulk save).
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
            'presensi.*.santri_id' => 'required|exists:santris,id',
            'presensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alfa',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->presensi as $item) {
            Presensi::updateOrCreate(
                [
                    'santri_id' => $item['santri_id'],
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.presensi.index', ['tanggal' => $tanggal, 'kelas_id' => $request->kelas_id])
            ->with('success', 'Presensi berhasil disimpan untuk tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y'));
    }

    /**
     * Halaman rekap presensi per bulan.
     */
    public function rekap(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::today()->month);
        $tahun = $request->get('tahun', Carbon::today()->year);
        $kelasId = $request->get('kelas_id');

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $query = Santri::with('kelas')
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $santris = $query->get();

        // Ambil semua presensi bulan ini
        $presensiBulan = Presensi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('santri_id');

        // Hitung jumlah hari di bulan ini
        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;

        // Daftar bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.presensi.rekap', compact(
            'santris', 'kelasList', 'bulan', 'tahun',
            'presensiBulan', 'jumlahHari', 'namaBulan', 'kelasId'
        ));
    }

    /**
     * Mengambil summary presensi (Sakit, Izin, Alfa/Ghoib) berdasarkan no_induk.
     */
    public function getSummary($no_induk)
    {
        $santri = Santri::where('no_induk', $no_induk)->first();
        
        if (!$santri) {
            return response()->json([
                'success' => false,
                'message' => 'Santri tidak ditemukan.'
            ], 404);
        }

        $sakit = Presensi::where('santri_id', $santri->id)->where('status', 'Sakit')->count();
        $izin = Presensi::where('santri_id', $santri->id)->where('status', 'Izin')->count();
        $alfa = Presensi::where('santri_id', $santri->id)->where('status', 'Alfa')->count();

        return response()->json([
            'success' => true,
            'sakit' => $sakit,
            'izin' => $izin,
            'ghoib' => $alfa
        ]);
    }

    /**
     * Update atau hapus presensi untuk satu santri pada tanggal tertentu (AJAX).
     */
    public function updateSingle(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'tanggal' => 'required|date',
            'status' => 'nullable|in:Hadir,Sakit,Izin,Alfa,kosong,-,H,S,I,A,h,s,i,a',
        ]);

        $status = $request->status;

        if (empty($status) || $status === '-' || $status === 'kosong') {
            // Hapus presensi jika status kosong atau '-'
            Presensi::where('santri_id', $request->santri_id)
                ->whereDate('tanggal', $request->tanggal)
                ->delete();

            return response()->json([
                'success' => true,
                'status' => '',
                'message' => 'Presensi berhasil dihapus.'
            ]);
        }

        // Normalise status
        $normalizedStatus = null;
        $statusUpper = strtoupper($status);
        if ($statusUpper === 'H' || $statusUpper === 'HADIR') {
            $normalizedStatus = 'Hadir';
        } elseif ($statusUpper === 'S' || $statusUpper === 'SAKIT') {
            $normalizedStatus = 'Sakit';
        } elseif ($statusUpper === 'I' || $statusUpper === 'IZIN') {
            $normalizedStatus = 'Izin';
        } elseif ($statusUpper === 'A' || $statusUpper === 'ALFA') {
            $normalizedStatus = 'Alfa';
        }

        if (!$normalizedStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid.'
            ], 422);
        }

        $presensi = Presensi::updateOrCreate(
            [
                'santri_id' => $request->santri_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'status' => $normalizedStatus,
                'keterangan' => $request->keterangan ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'status' => $presensi->status,
            'message' => 'Presensi berhasil diperbarui.'
        ]);
    }
}
