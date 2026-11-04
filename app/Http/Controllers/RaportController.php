<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raport;
use App\Models\Musyrif;
use App\Models\AkhlakPenilaian;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;

class RaportController extends Controller
{
    // 1. Halaman Statistik Utama
    public function dashboard()
    {
        // 1. STATISTIK TAHFIDZ
        $totalSantriTahfidz = Raport::select('nama_santri')->distinct()->count();
        $queryRataTahfidz = '(adab + kelancaran + tajwid + fashahah) / 4';
        
        $mumtazTahfidz = Raport::whereRaw("$queryRataTahfidz >= 90")->count();
        $jayyidJiddanTahfidz = Raport::whereRaw("$queryRataTahfidz >= 80 AND $queryRataTahfidz < 90")->count();
        $jayyidTahfidz = Raport::whereRaw("$queryRataTahfidz >= 70 AND $queryRataTahfidz < 80")->count();
        $maqbulTahfidz = Raport::whereRaw("$queryRataTahfidz < 70")->count();

        // 2. STATISTIK KMI
        $totalKmi = \App\Models\RaportKmi::count();
        $riwayatKmi = \App\Models\RaportKmi::latest()->take(5)->get();
        
        // 3. DATA REKAPITULASI (INTEGRATED)
        // Kita gunakan logika yang sama dengan rekapitulasi() tapi diringkas untuk dashboard
        $tahfidzRaw = Raport::latest()->get();
        $kmiRaw = \App\Models\RaportKmi::all();
        $normalize = function($name) { return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)); };
        
        $studentMap = [];
        foreach ($tahfidzRaw as $t) {
            $key = $normalize($t->nama_santri);
            if (!isset($studentMap[$key])) {
                $studentMap[$key] = [
                    'nama' => $t->nama_santri,
                    'rt' => ($t->adab + $t->kelancaran + $t->tajwid + $t->fashahah) / 4,
                    'rk' => 0,
                ];
            }
        }
        foreach ($kmiRaw as $k) {
            $key = $normalize($k->nama_santri);
            $rk = 0;
            if (!empty($k->nilai_mapel) && is_array($k->nilai_mapel)) {
                $totalVal = 0; $countM = 0;
                foreach ($k->nilai_mapel as $m) {
                    if (isset($m['p_a']) && is_numeric($m['p_a'])) { $totalVal += $m['p_a']; $countM++; }
                }
                $rk = $countM > 0 ? $totalVal / $countM : 0;
            }
            if (isset($studentMap[$key])) {
                $studentMap[$key]['rk'] = $rk;
            } else {
                $studentMap[$key] = ['nama' => $k->nama_santri, 'rt' => 0, 'rk' => $rk];
            }
        }

        $rekap = collect($studentMap)->map(function($item) {
            $total = ($item['rt'] > 0 && $item['rk'] > 0) ? ($item['rt'] + $item['rk']) / 2 : max($item['rt'], $item['rk']);
            return (object) ['nama' => $item['nama'], 'total' => $total];
        })->values();

        $totalSantriGlobal = $rekap->count();
        $topFiveGlobal = $rekap->sortByDesc('total')->take(5);
        
        // Predikat Global
        $mumtaz = $rekap->where('total', '>=', 90)->count();
        $jayyidJiddan = $rekap->where('total', '>=', 80)->where('total', '<', 90)->count();
        $jayyid = $rekap->where('total', '>=', 70)->where('total', '<', 80)->count();
        $maqbul = $rekap->where('total', '<', 70)->count();

        // Korelasi Musyrif (Tetap dari Tahfidz)
        $korelasiMusyrif = Raport::select('musyrif as nama')
            ->selectRaw("count(case when $queryRataTahfidz >= 90 then 1 end) as mumtaz")
            ->selectRaw("count(case when $queryRataTahfidz >= 80 and $queryRataTahfidz < 90 then 1 end) as jayyidJiddan")
            ->selectRaw("count(case when $queryRataTahfidz >= 70 and $queryRataTahfidz < 80 then 1 end) as jayyid")
            ->selectRaw("count(case when $queryRataTahfidz < 70 then 1 end) as maqbul")
            ->groupBy('musyrif')
            ->get();

        $riwayat = Raport::latest()->take(5)->get();
        $totalMusyrif = Musyrif::count();

        return view('dashboard', compact(
            'totalSantriTahfidz',
            'totalSantriGlobal',
            'totalMusyrif', 
            'totalKmi',
            'riwayatKmi',
            'mumtaz', 
            'jayyidJiddan', 
            'jayyid', 
            'maqbul', 
            'korelasiMusyrif',
            'topFiveGlobal',
            'riwayat',
            'rekap'
        ));
    }

    // 2. Halaman Form Input Nilai
    public function index()
    {
        // Mengambil semua guru aktif dari tabel gurus untuk digunakan sebagai musyrif
        $musyrif_list = \App\Models\Guru::where('is_active', true)->orderBy('nama')->get();

        // Mengambil daftar santri unik beserta musyrif & skor terakhirnya
        $santri_list = Raport::select('nama_santri', 'musyrif', 'adab', 'kelancaran', 'tajwid', 'fashahah', 'catatan')
                             ->latest()
                             ->get()
                             ->unique('nama_santri');

        // Mengambil seluruh data santri aktif dari database master santri
        $santri_db_list = \App\Models\Santri::where('status', 'Aktif')
                             ->orderBy('nama_lengkap')
                             ->get();

        // Pastikan return view mengarah ke file yang benar (input.blade.php)
        return view('input', compact('musyrif_list', 'santri_list', 'santri_db_list'));
    }

    // 3. Halaman Tabel Daftar Santri
    public function list()
    {
        $data_rapor = Raport::oldest()->get();
        // REVISI: Pastikan memanggil file 'daftar' (bukan 'input')
        return view('daftar', compact('data_rapor'));
    }

    public function store(Request $request)
    {
        $savedCount = 0;
        foreach ($request->santri as $data) {
            if (!empty($data['nama_santri'])) {
                Raport::create([
                    'nama_santri' => $data['nama_santri'],
                    'musyrif'     => $data['musyrif'] ?? 'Ustadz Rizal Habibi',
                    'adab'        => $data['adab'] ?? 0,
                    'kelancaran'  => $data['kelancaran'] ?? 0,
                    'tajwid'      => $data['tajwid'] ?? 0,
                    'fashahah'    => $data['fashahah'] ?? 0,
                    'catatan'     => $data['catatan'] ?? '-',
                ]);
                $savedCount++;
            }
        }
        return redirect('/daftar')->with('success', "Berhasil menyimpan $savedCount data.");
    }

    public function edit($id)
    {
        $santri = Raport::findOrFail($id);
        $gurus = \App\Models\Guru::where('is_active', true)->orderBy('nama')->get();
        return view('edit', compact('santri', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $santri = Raport::findOrFail($id);
        $santri->update($request->all());
        return redirect('/daftar')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $santri = Raport::findOrFail($id);
        $santri->delete();
        return redirect('/daftar')->with('success', 'Data berhasil dihapus.');
    }

    public function truncate()
    {
        Raport::truncate();
        return redirect('/daftar')->with('success', 'Seluruh data berhasil dikosongkan.');
    }

    public function cetak($id)
    {
        $rapor = Raport::findOrFail($id);
        $guru = \App\Models\Guru::findByName($rapor->musyrif);
        return view('cetak', compact('rapor', 'guru'));
    }

    public function cetakSemua(Request $request)
    {
        $kategori = $request->query('kategori', 'rata_rata');

        $query = Raport::select('*')
            ->selectRaw('(adab + kelancaran + tajwid + fashahah) / 4 as rata_rata');

        if (in_array($kategori, ['adab', 'tajwid', 'kelancaran', 'fashahah'])) {
            $query->orderBy($kategori, 'desc');
        } else {
            $query->orderBy('rata_rata', 'desc');
        }

        $data_rapor = $query->get();

        $pdf = Pdf::loadView('pdf_rekap', compact('data_rapor', 'kategori'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream("Rekap-Nilai-$kategori.pdf");
    }
    
    public function rekapitulasi()
    {
        // 1. Get all Tahfidz and KMI data
        $tahfidzRaw = Raport::select('nama_santri', 'adab', 'kelancaran', 'tajwid', 'fashahah', 'musyrif')->latest()->get();
        $kmiRaw = \App\Models\RaportKmi::all();

        // Ambil data akhlak per santri (semua waktu, nilai rata-rata)
        $akhlakRaw = \App\Models\AkhlakPenilaian::select('santri_id')
            ->selectRaw('AVG(nilai_angka) as rata_nilai_angka')
            ->groupBy('santri_id')
            ->get()
            ->keyBy('santri_id');

        // Helper function to normalize name for comparison
        $normalize = function($name) {
            return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        };

        // Fungsi normalisasi SAW akhlak (skala 0-100 → 0-1)
        $normalisasiAkhlak = function($nilaiAngka) {
            // Nilai fuzzy crisp berdasarkan predikat
            if ($nilaiAngka >= 90) $fuzzy = 95.0;
            elseif ($nilaiAngka >= 80) $fuzzy = 85.0;
            elseif ($nilaiAngka >= 75) $fuzzy = 77.5;
            elseif ($nilaiAngka > 0)  $fuzzy = 37.5;
            else $fuzzy = 0;
            // Normalisasi SAW: nilai / max_value (max = 95)
            return $fuzzy > 0 ? round($fuzzy / 95.0, 4) : 0;
        };

        $studentMap = [];

        // 2. Process Tahfidz
        foreach ($tahfidzRaw as $t) {
            $key = $normalize($t->nama_santri);
            if (!isset($studentMap[$key])) {
                $studentMap[$key] = [
                    'nama_santri' => $t->nama_santri,
                    'musyrif' => $t->musyrif,
                    'rata_tahfidz' => ($t->adab + $t->kelancaran + $t->tajwid + $t->fashahah) / 4,
                    'rata_kmi' => 0,
                    'rata_akhlak' => 0,
                    'nilai_akhlak_fuzzy' => 0,
                    'has_tahfidz' => true,
                    'has_kmi' => false,
                    'has_akhlak' => false,
                ];
            }
        }

        // 3. Process KMI
        foreach ($kmiRaw as $k) {
            $key = $normalize($k->nama_santri);

            // Cari data akhlak berdasarkan santri_id dari tabel santris (match by no_induk)
            $santri = \App\Models\Santri::where('no_induk', $k->no_induk)->first();
            $rataAkhlak = 0;
            $nilaiFuzzyNorm = 0;
            $hasAkhlak = false;
            if ($santri && isset($akhlakRaw[$santri->id])) {
                $rataAkhlak = round($akhlakRaw[$santri->id]->rata_nilai_angka, 2);
                $nilaiFuzzyNorm = $normalisasiAkhlak($rataAkhlak);
                $hasAkhlak = true;
            }

            // Calculate KMI average (using p_a for Pengetahuan)
            $rataKmi = 0;
            if (!empty($k->nilai_mapel) && is_array($k->nilai_mapel)) {
                $totalNilai = 0;
                $countMapel = 0;
                foreach ($k->nilai_mapel as $m) {
                    if (isset($m['p_a']) && is_numeric($m['p_a'])) {
                        $totalNilai += $m['p_a'];
                        $countMapel++;
                    }
                }
                $rataKmi = $countMapel > 0 ? $totalNilai / $countMapel : 0;
            }

            if (isset($studentMap[$key])) {
                $studentMap[$key]['rata_kmi'] = $rataKmi;
                $studentMap[$key]['has_kmi'] = true;
                if ($hasAkhlak) {
                    $studentMap[$key]['rata_akhlak'] = $rataAkhlak;
                    $studentMap[$key]['nilai_akhlak_fuzzy'] = $nilaiFuzzyNorm;
                    $studentMap[$key]['has_akhlak'] = true;
                }
            } else {
                $studentMap[$key] = [
                    'nama_santri' => $k->nama_santri,
                    'musyrif' => '-',
                    'rata_tahfidz' => 0,
                    'rata_kmi' => $rataKmi,
                    'rata_akhlak' => $rataAkhlak,
                    'nilai_akhlak_fuzzy' => $nilaiFuzzyNorm,
                    'has_tahfidz' => false,
                    'has_kmi' => true,
                    'has_akhlak' => $hasAkhlak,
                ];
            }
        }

        // 4. Final collection and calculations
        $rekap = collect($studentMap)->map(function ($item) {
            $rt = $item['rata_tahfidz'];
            $rk = $item['rata_kmi'];

            if ($rt > 0 && $rk > 0) {
                $totalRata = ($rt + $rk) / 2;
            } else {
                $totalRata = max($rt, $rk);
            }

            return (object) array_merge($item, ['total_rata' => $totalRata]);
        })->sortByDesc('total_rata');

        return view('rekapitulasi', compact('rekap'));
    }
}