<?php

namespace App\Http\Controllers;

use App\Models\AkhlakPenilaian;
use App\Models\RaportKmi;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AkhlakController extends Controller
{
    /**
     * Halaman Utama: Daftar Penilaian Akhlak Harian
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kelasId = $request->input('kelas_id');
        $search = $request->input('search');

        $query = AkhlakPenilaian::with(['santri.kelas', 'guru'])
            ->whereDate('tanggal', $tanggal);

        if ($kelasId) {
            $query->whereHas('santri', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($search) {
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_induk', 'like', "%{$search}%");
            });
        }

        $penilaians = $query->latest('updated_at')->paginate(20)->withQueryString();

        // Statistik ringkasan untuk tanggal terpilih
        $totalSantriHariIni = AkhlakPenilaian::whereDate('tanggal', $tanggal)->count();
        $rataRataHariIni = AkhlakPenilaian::whereDate('tanggal', $tanggal)->avg('nilai_angka');
        $jumlahSangatBaik = AkhlakPenilaian::whereDate('tanggal', $tanggal)->where('predikat', 'Sangat Baik')->count();
        $jumlahBaik = AkhlakPenilaian::whereDate('tanggal', $tanggal)->where('predikat', 'Baik')->count();
        $jumlahCukup = AkhlakPenilaian::whereDate('tanggal', $tanggal)->where('predikat', 'Cukup')->count();
        $jumlahKurang = AkhlakPenilaian::whereDate('tanggal', $tanggal)->where('predikat', 'Kurang')->count();

        $kelasList = Kelas::where('is_active', true)->get();

        return view('admin.akhlak.index', compact(
            'penilaians',
            'tanggal',
            'kelasId',
            'search',
            'kelasList',
            'totalSantriHariIni',
            'rataRataHariIni',
            'jumlahSangatBaik',
            'jumlahBaik',
            'jumlahCukup',
            'jumlahKurang'
        ));
    }

    /**
     * Form Tambah / Input Penilaian Akhlak
     * Mendukung mode input per kelas (batch) atau individual
     */
    public function create(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kelasId = $request->input('kelas_id');
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();
        $kelasList = Kelas::where('is_active', true)->get();

        $santris = collect();
        $existingPenilaians = collect();

        if ($kelasId) {
            $santris = Santri::where('kelas_id', $kelasId)
                ->where('status', 'Aktif')
                ->orderBy('nama_lengkap')
                ->get();

            // Ambil data penilaian yang sudah ada di tanggal tersebut
            $existingPenilaians = AkhlakPenilaian::whereDate('tanggal', $tanggal)
                ->whereIn('santri_id', $santris->pluck('id'))
                ->get()
                ->keyBy('santri_id');
        }

        return view('admin.akhlak.create', compact(
            'tanggal',
            'kelasId',
            'gurus',
            'kelasList',
            'santris',
            'existingPenilaians'
        ));
    }

    /**
     * Simpan Data Penilaian Akhlak Harian (Mendukung Batch per Kelas & Individual)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'guru_id' => 'nullable|exists:gurus,id',
            'tipe_penilai' => 'required|string|in:musyrif,guru_tahfidz,guru_kbm',
        ]);

        $tanggal = $request->input('tanggal');
        $guruId = $request->input('guru_id');
        $tipePenilai = $request->input('tipe_penilai');

        // Jika input batch per kelas
        if ($request->has('penilaian') && is_array($request->input('penilaian'))) {
            $penilaianData = $request->input('penilaian');
            $savedCount = 0;

            foreach ($penilaianData as $santriId => $scores) {
                // Pastikan checkbox atau nilai terisi
                if (!isset($scores['skor_ibadah'])) continue;

                $ibadah = (int)($scores['skor_ibadah'] ?? 4);
                $adab = (int)($scores['skor_adab'] ?? 4);
                $kebersihan = (int)($scores['skor_kebersihan'] ?? 4);
                $kegiatan = (int)($scores['skor_kegiatan'] ?? 4);
                $kedisiplinan = (int)($scores['skor_kedisiplinan'] ?? 4);
                $catatan = $scores['catatan_pelanggaran'] ?? null;

                $hasil = AkhlakPenilaian::hitungNilaiDanFuzzy($ibadah, $adab, $kebersihan, $kegiatan, $kedisiplinan);

                AkhlakPenilaian::updateOrCreate(
                    [
                        'santri_id' => $santriId,
                        'tanggal' => $tanggal,
                        'tipe_penilai' => $tipePenilai,
                    ],
                    [
                        'guru_id' => $guruId,
                        'skor_ibadah' => $ibadah,
                        'skor_adab' => $adab,
                        'skor_kebersihan' => $kebersihan,
                        'skor_kegiatan' => $kegiatan,
                        'skor_kedisiplinan' => $kedisiplinan,
                        'total_skor' => $hasil['total_skor'],
                        'nilai_angka' => $hasil['nilai_angka'],
                        'predikat' => $hasil['predikat'],
                        'nilai_fuzzy' => $hasil['nilai_fuzzy'],
                        'catatan_pelanggaran' => $catatan,
                    ]
                );
                $savedCount++;
            }

            return redirect()->route('admin.akhlak.index', ['tanggal' => $tanggal])
                ->with('success', "Berhasil menyimpan penilaian akhlak untuk {$savedCount} santri pada tanggal " . Carbon::parse($tanggal)->translatedFormat('d F Y') . ".");
        }

        // Jika input individual tunggal
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'skor_ibadah' => 'required|integer|min:1|max:4',
            'skor_adab' => 'required|integer|min:1|max:4',
            'skor_kebersihan' => 'required|integer|min:1|max:4',
            'skor_kegiatan' => 'required|integer|min:1|max:4',
            'skor_kedisiplinan' => 'required|integer|min:1|max:4',
            'catatan_pelanggaran' => 'nullable|string',
        ]);

        $santriId = $request->input('santri_id');
        $ibadah = (int)$request->input('skor_ibadah');
        $adab = (int)$request->input('skor_adab');
        $kebersihan = (int)$request->input('skor_kebersihan');
        $kegiatan = (int)$request->input('skor_kegiatan');
        $kedisiplinan = (int)$request->input('skor_kedisiplinan');
        $catatan = $request->input('catatan_pelanggaran');

        $hasil = AkhlakPenilaian::hitungNilaiDanFuzzy($ibadah, $adab, $kebersihan, $kegiatan, $kedisiplinan);

        AkhlakPenilaian::updateOrCreate(
            [
                'santri_id' => $santriId,
                'tanggal' => $tanggal,
                'tipe_penilai' => $tipePenilai,
            ],
            [
                'guru_id' => $guruId,
                'skor_ibadah' => $ibadah,
                'skor_adab' => $adab,
                'skor_kebersihan' => $kebersihan,
                'skor_kegiatan' => $kegiatan,
                'skor_kedisiplinan' => $kedisiplinan,
                'total_skor' => $hasil['total_skor'],
                'nilai_angka' => $hasil['nilai_angka'],
                'predikat' => $hasil['predikat'],
                'nilai_fuzzy' => $hasil['nilai_fuzzy'],
                'catatan_pelanggaran' => $catatan,
            ]
        );

        return redirect()->route('admin.akhlak.index', ['tanggal' => $tanggal])
            ->with('success', 'Data penilaian akhlak santri berhasil disimpan.');
    }

    /**
     * Form Edit Penilaian Akhlak Individual
     */
    public function edit($id)
    {
        $penilaian = AkhlakPenilaian::with('santri.kelas')->findOrFail($id);
        $gurus = Guru::where('is_active', true)->orderBy('nama')->get();

        return view('admin.akhlak.edit', compact('penilaian', 'gurus'));
    }

    /**
     * Update Data Penilaian Akhlak
     */
    public function update(Request $request, $id)
    {
        $penilaian = AkhlakPenilaian::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'guru_id' => 'nullable|exists:gurus,id',
            'tipe_penilai' => 'required|string|in:musyrif,guru_tahfidz,guru_kbm',
            'skor_ibadah' => 'required|integer|min:1|max:4',
            'skor_adab' => 'required|integer|min:1|max:4',
            'skor_kebersihan' => 'required|integer|min:1|max:4',
            'skor_kegiatan' => 'required|integer|min:1|max:4',
            'skor_kedisiplinan' => 'required|integer|min:1|max:4',
            'catatan_pelanggaran' => 'nullable|string',
        ]);

        $hasil = AkhlakPenilaian::hitungNilaiDanFuzzy(
            $validated['skor_ibadah'],
            $validated['skor_adab'],
            $validated['skor_kebersihan'],
            $validated['skor_kegiatan'],
            $validated['skor_kedisiplinan']
        );

        $penilaian->update(array_merge($validated, $hasil));

        return redirect()->route('admin.akhlak.index', ['tanggal' => $penilaian->tanggal->toDateString()])
            ->with('success', 'Penilaian akhlak santri berhasil diperbarui.');
    }

    /**
     * Hapus Data Penilaian Akhlak
     */
    public function destroy($id)
    {
        $penilaian = AkhlakPenilaian::findOrFail($id);
        $tanggal = $penilaian->tanggal ? $penilaian->tanggal->toDateString() : date('Y-m-d');
        $penilaian->delete();

        return redirect()->route('admin.akhlak.index', ['tanggal' => $tanggal])
            ->with('success', 'Data penilaian akhlak berhasil dihapus.');
    }

    /**
     * Sinkronisasi Nilai Akhlak Bulanan ke Raport KMI (Mental/Karakter)
     * Mapping: skor_adab → mental_moral, skor_kedisiplinan → mental_kedisiplinan
     *          avg(skor_ibadah + skor_kegiatan) → mental_kerajinan, skor_kebersihan → mental_kebersihan
     */
    public function sinkronRaportKmi(Request $request)
    {
        $request->validate([
            'semester'       => 'required|in:1,2',
            'tahun_pelajaran'=> 'required|string',
            'periode_awal'   => 'required|date',
            'periode_akhir'  => 'required|date|after_or_equal:periode_awal',
        ]);

        $semester        = $request->input('semester');
        $tahunPelajaran  = $request->input('tahun_pelajaran');
        $periodeAwal     = $request->input('periode_awal');
        $periodeAkhir    = $request->input('periode_akhir');

        // Ambil semua santri aktif
        $santris = Santri::where('status', 'Aktif')->get();

        $sinkronCount = 0;
        $buatBaru     = 0;

        foreach ($santris as $santri) {
            // Ambil semua penilaian akhlak santri dalam periode
            $penilaians = AkhlakPenilaian::where('santri_id', $santri->id)
                ->whereBetween('tanggal', [$periodeAwal, $periodeAkhir])
                ->get();

            if ($penilaians->isEmpty()) continue;

            // Hitung rata-rata per aspek
            $rataAdab        = $penilaians->avg('skor_adab');        // → Moral
            $rataKedisiplinan= $penilaians->avg('skor_kedisiplinan');// → Kedisiplinan
            $rataIbadah      = $penilaians->avg('skor_ibadah');
            $rataKegiatan    = $penilaians->avg('skor_kegiatan');
            $rataKerajinan   = ($rataIbadah + $rataKegiatan) / 2;   // → Kerajinan
            $rataKebersihan  = $penilaians->avg('skor_kebersihan');  // → Kebersihan

            // Fungsi konversi skor (1-4) ke huruf raport
            $skorKeHuruf = function ($rata) {
                // Skala 1-4: 4=SB (A), 3=B, 2=C, 1=K (D)
                if ($rata >= 3.5) return 'A';
                if ($rata >= 2.5) return 'B';
                if ($rata >= 1.5) return 'C';
                return 'D';
            };

            $mentalMoral        = $skorKeHuruf($rataAdab);
            $mentalKedisiplinan = $skorKeHuruf($rataKedisiplinan);
            $mentalKerajinan    = $skorKeHuruf($rataKerajinan);
            $mentalKebersihan   = $skorKeHuruf($rataKebersihan);

            // Cari raport KMI santri di semester & tahun pelajaran ini
            $raport = RaportKmi::where('santri_id', $santri->id)
                ->where('semester', $semester)
                ->where('tahun_pelajaran', $tahunPelajaran)
                ->first();

            if ($raport) {
                // Update nilai mental/karakter
                $raport->update([
                    'mental_moral'        => $mentalMoral,
                    'mental_kedisiplinan' => $mentalKedisiplinan,
                    'mental_kerajinan'    => $mentalKerajinan,
                    'mental_kebersihan'   => $mentalKebersihan,
                ]);
                $sinkronCount++;
            }
            // Jika raport belum ada, tidak otomatis buat (biarkan admin buat dulu via form raport)
        }

        return redirect()->route('admin.akhlak.rekap', [
            'bulan' => Carbon::parse($periodeAkhir)->month,
            'tahun' => Carbon::parse($periodeAkhir)->year,
        ])->with('success', "Berhasil sinkron nilai akhlak ke {$sinkronCount} raport KMI. (Santri tanpa raport KMI tidak terpengaruh)");
    }

    /**
     * Rekapitulasi Nilai Akhlak (Akumulasi Bulanan / Semesteran untuk SPK Fuzzy-SAW C3)
     */
    public function rekap(Request $request)
    {
        $bulan = (int)$request->input('bulan', Carbon::now()->month);
        $tahun = (int)$request->input('tahun', Carbon::now()->year);
        $kelasId = $request->input('kelas_id');
        $kelasList = Kelas::where('is_active', true)->get();

        $query = Santri::with('kelas')->where('status', 'Aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $santris = $query->orderBy('nama_lengkap')->get();

        // Ambil data penilaian akhlak di bulan & tahun terpilih
        $penilaiansBulanIni = AkhlakPenilaian::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy('santri_id');

        // Hitung agregasi per santri
        $rekapData = $santris->map(function ($santri) use ($penilaiansBulanIni) {
            $catatanList = $penilaiansBulanIni->get($santri->id, collect());
            $totalHari = $catatanList->count();
            $rataNilaiAngka = $totalHari > 0 ? round($catatanList->avg('nilai_angka'), 2) : 0;
            $rataNilaiFuzzy = $totalHari > 0 ? round($catatanList->avg('nilai_fuzzy'), 2) : 0;

            // Tentukan predikat akumulasi
            if ($rataNilaiAngka >= 90) {
                $predikat = 'Sangat Baik';
                $fuzzyCrisp = 95.0;
            } elseif ($rataNilaiAngka >= 80) {
                $predikat = 'Baik';
                $fuzzyCrisp = 85.0;
            } elseif ($rataNilaiAngka >= 75) {
                $predikat = 'Cukup';
                $fuzzyCrisp = 77.5;
            } elseif ($totalHari > 0) {
                $predikat = 'Kurang';
                $fuzzyCrisp = 37.5;
            } else {
                $predikat = 'Belum Ada Nilai';
                $fuzzyCrisp = 0;
            }

            // Jumlah catatan pelanggaran
            $jumlahPelanggaran = $catatanList->filter(function ($item) {
                return !empty($item->catatan_pelanggaran) || $item->skor_kedisiplinan < 3;
            })->count();

            return (object)[
                'santri' => $santri,
                'total_hari' => $totalHari,
                'rata_nilai_angka' => $rataNilaiAngka,
                'rata_nilai_fuzzy' => $rataNilaiFuzzy,
                'predikat' => $predikat,
                'fuzzy_crisp' => $fuzzyCrisp,
                'jumlah_pelanggaran' => $jumlahPelanggaran,
            ];
        });

        return view('admin.akhlak.rekap', compact(
            'rekapData',
            'bulan',
            'tahun',
            'kelasId',
            'kelasList'
        ));
    }
}

