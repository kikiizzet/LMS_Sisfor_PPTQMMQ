<?php

namespace App\Http\Controllers;

use App\Models\RaportKmi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RaportKmiController extends Controller
{
    public function index()
    {
        $raport_kmis = RaportKmi::latest()->get();
        return view('daftarkmi', compact('raport_kmis'));
    }

    public function create()
    {
        return view('inputkmi');
    }

    public function grid()
    {
        $raport_kmis = RaportKmi::latest()->get();
        $mapels = [
            'nahwu', 'mutholaah', 'shorof', 'mahfudzat', 'reading', 'dictation', 
            'mufrodzat', 'vocabularies', 'akhlak_lil_banin', 'durusuttafsir', 
            'ushul_fiqh', 'arbain_nawawi', 'bahasa_inggris', 'bahasa_arab', 'praktik_ibadah'
        ];
        return view('gridkmi', compact('raport_kmis', 'mapels'));
    }

    public function store(Request $request)
    {
        // Validasi dan penyimpanan nilai_mapel serta ekstrakurikuler yang dikirim sebagai array
        RaportKmi::create($request->all());
        return redirect()->route('raport-kmi.index')->with('success', 'Raport KMI berhasil ditambahkan.');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'nama_santri' => 'required',
            'no_induk' => 'required',
            'kelas' => 'required',
            'semester' => 'required',
            'tahun_pelajaran' => 'required'
        ]);

        // Ambil default wali kelas & kepala madrasah dari record terakhir agar tidak error DB
        $lastRecord = RaportKmi::latest()->first();
        $wali = $lastRecord ? $lastRecord->wali_kelas_nama : 'Belum Diisi';
        $kepala = $lastRecord ? $lastRecord->kepala_madrasah_nama : 'H. Muhammad Ulil Albab, S.H.I';

        $raport = RaportKmi::create([
            'nama_santri' => $request->nama_santri,
            'no_induk' => $request->no_induk,
            'kelas' => $request->kelas,
            'semester' => $request->semester,
            'tahun_pelajaran' => $request->tahun_pelajaran,
            'nilai_mapel' => [],
            'ekstrakurikuler' => [],
            'wali_kelas_nama' => $wali,
            'kepala_madrasah_nama' => $kepala
        ]);

        return response()->json([
            'success' => true,
            'raport' => $raport
        ]);
    }

    public function edit($id)
    {
        $raport = RaportKmi::findOrFail($id);
        return view('editkmi', compact('raport'));
    }

    public function update(Request $request, $id)
    {
        $raport = RaportKmi::findOrFail($id);
        $raport->update($request->all());
        return redirect()->route('raport-kmi.index')->with('success', 'Raport KMI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $raport = RaportKmi::findOrFail($id);
        $raport->delete();
        return redirect()->route('raport-kmi.index')->with('success', 'Raport KMI berhasil dihapus.');
    }

    public function cetak($id)
    {
        $raport = RaportKmi::findOrFail($id);
        // Menyiapkan PDF dengan paper A4 Portrait (sesuai gambar yang memanjang ke bawah)
        $pdf = Pdf::loadView('cetakkmi', compact('raport'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream("Raport-KMI-{$raport->nama_santri}.pdf");
    }

    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_raport_kmi.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'nama_santri', 'no_induk', 'kelas', 'semester', 'tahun_pelajaran', 'tempat_tanggal_cetak',
            'wali_kelas_nama', 'kepala_madrasah_nama', 'mental_moral', 'mental_kedisiplinan', 
            'mental_kerajinan', 'mental_kebersihan', 'sakit', 'izin', 'ghoib', 'catatan_wali_kelas'
        ];

        // Daftar mapel untuk header
        $mapels = [
            'nahwu', 'mutholaah', 'shorof', 'mahfudzat', 'reading', 'dictation', 
            'mufrodzat', 'vocabularies', 'akhlak_lil_banin', 'durusuttafsir', 
            'ushul_fiqh', 'arbain_nawawi', 'bahasa_inggris', 'bahasa_arab', 'praktik_ibadah'
        ];

        foreach ($mapels as $m) {
            $columns[] = "{$m}_kkm";
            $columns[] = "{$m}_p";
            $columns[] = "{$m}_k";
            $columns[] = "{$m}_s";
        }

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file_csv')->getRealPath(), 'r');
        $header = fgetcsv($file);
        
        // Bersihkan header dari BOM jika ada
        if (isset($header[0])) {
            $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
        }

        $count = 0;
        while ($row = fgetcsv($file)) {
            $data = array_combine($header, $row);
            
            $raportData = [
                'nama_santri' => $data['nama_santri'] ?? '',
                'no_induk' => $data['no_induk'] ?? '',
                'kelas' => $data['kelas'] ?? '',
                'semester' => $data['semester'] ?? 'Ganjil',
                'tahun_pelajaran' => $data['tahun_pelajaran'] ?? '2023/2024',
                'tempat_tanggal_cetak' => $data['tempat_tanggal_cetak'] ?? '',
                'wali_kelas_nama' => $data['wali_kelas_nama'] ?? '',
                'kepala_madrasah_nama' => $data['kepala_madrasah_nama'] ?? '',
                'mental_moral' => $data['mental_moral'] ?? 'A',
                'mental_kedisiplinan' => $data['mental_kedisiplinan'] ?? 'A',
                'mental_kerajinan' => $data['mental_kerajinan'] ?? 'A',
                'mental_kebersihan' => $data['mental_kebersihan'] ?? 'A',
                'sakit' => $data['sakit'] ?? 0,
                'izin' => $data['izin'] ?? 0,
                'ghoib' => $data['ghoib'] ?? 0,
                'catatan_wali_kelas' => $data['catatan_wali_kelas'] ?? '',
                'nilai_mapel' => [],
                'ekstrakurikuler' => [] // Ekskul lewat import Excel dikosongkan dulu atau dibuat kolom tambahan jika perlu
            ];

            $mapels = [
                'nahwu', 'mutholaah', 'shorof', 'mahfudzat', 'reading', 'dictation', 
                'mufrodzat', 'vocabularies', 'akhlak_lil_banin', 'durusuttafsir', 
                'ushul_fiqh', 'arbain_nawawi', 'bahasa_inggris', 'bahasa_arab', 'praktik_ibadah'
            ];

            foreach ($mapels as $m) {
                $p = $data["{$m}_p"] ?? 0;
                $kkm = $data["{$m}_kkm"] ?? 65;
                $raportData['nilai_mapel'][$m] = [
                    'kkm' => $kkm,
                    'p_a' => $p,
                    'p_h' => $this->terbilang($p),
                    'k_a' => $data["{$m}_k"] ?? 0,
                    'k_h' => $this->terbilang($data["{$m}_k"] ?? 0),
                    's_a' => $data["{$m}_s"] ?? 0,
                    's_h' => $this->terbilang($data["{$m}_s"] ?? 0),
                    'ketercapaian' => (int)$p >= (int)$kkm ? 'Melampaui Kompetensi' : 'Perlu Bimbingan'
                ];
            }

            RaportKmi::create($raportData);
            $count++;
        }

        fclose($file);

        return redirect()->route('raport-kmi.index')->with('success', "Berhasil mengimport $count data raport KMI.");
    }

    private function terbilang($n) {
        if ($n === "" || $n === null) return "";
        $labels = ["Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $result = "";
        
        $n = (int)$n;
        if ($n < 12) $result = $labels[$n];
        elseif ($n < 20) $result = $this->terbilang($n - 10) . " Belas";
        elseif ($n < 100) $result = $this->terbilang(floor($n / 10)) . " Puluh " . ($n % 10 !== 0 ? $this->terbilang($n % 10) : "");
        elseif ($n === 100) $result = "Seratus";
        
        return trim($result);
    }

    public function updateScoreAjax(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:raport_kmis,id',
            'mapel' => 'required',
            'type' => 'required|in:p_a,k_a,s_a',
            'value' => 'required|numeric|min:0|max:100'
        ]);

        $raport = RaportKmi::findOrFail($request->id);
        $mapel = $request->mapel;
        $type = $request->type;
        $value = $request->value;

        $nilai_mapel = $raport->nilai_mapel;
        
        if (!isset($nilai_mapel[$mapel])) {
            $nilai_mapel[$mapel] = [
                'kkm' => 65,
                'p_a' => 0, 'p_h' => '',
                'k_a' => 0, 'k_h' => '',
                's_a' => 0, 's_h' => '',
                'ketercapaian' => ''
            ];
        }

        // Update nilai angka
        $nilai_mapel[$mapel][$type] = $value;
        
        // Update terbilang (huruf) otomatis
        $type_huruf = str_replace('_a', '_h', $type);
        $nilai_mapel[$mapel][$type_huruf] = $this->terbilang($value);

        // Update ketercapaian jika yang diupdate adalah Pengetahuan (p_a)
        if ($type === 'p_a') {
            $kkm = $nilai_mapel[$mapel]['kkm'] ?? 65;
            $nilai_mapel[$mapel]['ketercapaian'] = (int)$value >= (int)$kkm ? 'Melampaui Kompetensi' : 'Perlu Bimbingan';
        }

        $raport->nilai_mapel = $nilai_mapel;
        $raport->save();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil diperbarui',
            'new_huruf' => $nilai_mapel[$mapel][$type_huruf],
            'ketercapaian' => $nilai_mapel[$mapel]['ketercapaian'] ?? ''
        ]);
    }

    public function updateDetailAjax(Request $request)
    {
        $raport = RaportKmi::findOrFail($request->id);
        
        // Handle Ekskul String to Array
        $ekskul = [];
        if ($request->has('ekstrakurikuler') && !empty($request->ekstrakurikuler)) {
            $ekskul = array_map('trim', explode(',', $request->ekstrakurikuler));
        }

        $raport->update([
            'sakit' => $request->sakit ?? 0,
            'izin' => $request->izin ?? 0,
            'ghoib' => $request->ghoib ?? 0,
            'mental_moral' => $request->mental_moral,
            'mental_kedisiplinan' => $request->mental_kedisiplinan,
            'mental_kerajinan' => $request->mental_kerajinan,
            'mental_kebersihan' => $request->mental_kebersihan,
            'catatan_wali_kelas' => $request->catatan_wali_kelas,
            'ekstrakurikuler' => $ekskul
        ]);

        return response()->json([
            'success' => true,
            'raport' => $raport
        ]);
    }
}
