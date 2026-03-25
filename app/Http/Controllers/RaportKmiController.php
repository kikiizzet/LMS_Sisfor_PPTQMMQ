<?php

namespace App\Http\Controllers;

use App\Models\RaportKmi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RaportKmiController extends Controller
{
    public function index()
    {
        $raport_kmis = RaportKmi::orderBy('id', 'asc')->get();
        return view('daftarkmi', compact('raport_kmis'));
    }

    public function create()
    {
        return view('inputkmi');
    }

    public function grid()
    {
        $raport_kmis = RaportKmi::orderBy('id', 'asc')->get();
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
        $columns = [
            'nama_santri', 'no_induk', 'kelas', 'semester', 'tahun_pelajaran', 'tempat_tanggal_cetak',
            'wali_kelas_nama', 'kepala_madrasah_nama', 'mental_moral', 'mental_kedisiplinan', 
            'mental_kerajinan', 'mental_kebersihan', 'sakit', 'izin', 'ghoib', 'catatan_wali_kelas'
        ];

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

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        $xml .= ' <Styles>' . "\n";
        $xml .= '  <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Bottom"/><Borders/><Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/><Interior/><NumberFormat/><Protection/></Style>' . "\n";
        $xml .= '  <Style ss:ID="sHeader"><Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/><Interior ss:Color="#4472C4" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>' . "\n";
        $xml .= '  <Style ss:ID="sCell"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders></Style>' . "\n";
        $xml .= ' </Styles>' . "\n";
        $xml .= ' <Worksheet ss:Name="Template Raport KMI">' . "\n";
        $xml .= '  <Table>' . "\n";
        
        // Header Row
        $xml .= '   <Row ss:Height="20">' . "\n";
        foreach ($columns as $col) {
            $xml .= '    <Cell ss:StyleID="sHeader"><Data ss:Type="String">' . $col . '</Data></Cell>' . "\n";
        }
        $xml .= '   </Row>' . "\n";

        // Example Empty Rows with Borders
        for ($i = 0; $i < 5; $i++) {
            $xml .= '   <Row>' . "\n";
            foreach ($columns as $col) {
                $xml .= '    <Cell ss:StyleID="sCell"><Data ss:Type="String"></Data></Cell>' . "\n";
            }
            $xml .= '   </Row>' . "\n";
        }

        $xml .= '  </Table>' . "\n";
        $xml .= ' </Worksheet>' . "\n";
        $xml .= '</Workbook>' . "\n";

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=template_raport_kmi.xls',
        ]);
    }

    /**
     * Parse one XML Row dari XML Spreadsheet 2003, menangani ss:Index attribute
     * supaya cell kosong yang hilang dari XML tetap menghasilkan indeks yang benar.
     */
    private function parseXmlRow($xmlRow, int $totalCols): array
    {
        $row = array_fill(0, $totalCols, '');
        $col = 0; // 0-based pointer

        foreach ($xmlRow->Cell as $cell) {
            // ss:Index (1-based) melompat jika ada cell kosong yang tidak dirender
            $ssIndex = (string)$cell->attributes('urn:schemas-microsoft-com:office:spreadsheet')->Index;
            if ($ssIndex !== '') {
                $col = (int)$ssIndex - 1; // konversi ke 0-based
            }

            if ($col < $totalCols) {
                $row[$col] = (string)$cell->Data;
            }
            $col++;
        }

        return $row;
    }

    public function previewImport(Request $request)
    {
        $request->validate(['file_csv' => 'required']);

        $filePath = $request->file('file_csv')->getRealPath();
        $fileExtension = strtolower($request->file('file_csv')->getClientOriginalExtension());
        
        // Store file temporarily for later import
        $tempPath = $request->file('file_csv')->store('temp_imports');

        $names = [];

        if (in_array($fileExtension, ['xml', 'xls'])) {
            $content = file_get_contents($filePath);
            $xml = simplexml_load_string($content);
            if ($xml !== false) {
                $xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
                $xmlRows = $xml->xpath('//ss:Worksheet/ss:Table/ss:Row');

                if (isset($xmlRows[0])) {
                    // Baca header dengan cara biasa (header baris tidak ada cell kosong)
                    $header = [];
                    foreach ($xmlRows[0]->Cell as $cell) {
                        $header[] = (string)$cell->Data;
                    }
                    $headerIdx = array_search('nama_santri', $header);
                    $totalCols = count($header);

                    for ($i = 1; $i < count($xmlRows); $i++) {
                        $rowData = $this->parseXmlRow($xmlRows[$i], $totalCols);
                        $val = ($headerIdx !== false && isset($rowData[$headerIdx])) ? $rowData[$headerIdx] : '';
                        if (!empty(trim($val))) $names[] = trim($val);
                    }
                }
            }
        } else {
            $fileHandle = fopen($filePath, 'r');
            $firstLine = fgets($fileHandle);
            fclose($fileHandle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

            $file = fopen($filePath, 'r');
            $header = fgetcsv($file, 0, $delimiter);
            if (isset($header[0])) $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);

            $headerIdx = array_search('nama_santri', $header);
            while ($row = fgetcsv($file, 0, $delimiter)) {
                $val = ($headerIdx !== false && isset($row[$headerIdx])) ? $row[$headerIdx] : '';
                if (!empty($val)) $names[] = $val;
            }
            fclose($file);
        }

        return response()->json([
            'names' => $names,
            'count' => count($names),
            'temp_path' => $tempPath,
            'original_extension' => $fileExtension,
        ]);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'temp_path' => 'required|string'
        ]);

        $tempPath = $request->input('temp_path');
        $fileExtension = strtolower($request->input('file_extension', 'xml'));
        
        // Resolve absolute path using Storage facade to handle cross-platform and disk root differences
        if (!Storage::disk('local')->exists($tempPath)) {
            Log::error("Import Excel: File tidak ditemukan di storage.", ['path' => $tempPath]);
            return redirect()->route('raport-kmi.index')->with('error', 'File sementara tidak ditemukan. Silakan upload ulang.');
        }

        $filePath = Storage::disk('local')->path($tempPath);
        Log::info("Import Excel: Memulai proses import dari file.", ['path' => $filePath, 'ext' => $fileExtension]);

        $header = [];
        $rows = [];

        if (in_array(strtolower($fileExtension), ['xml', 'xls'])) {
            // Handle XML Spreadsheet 2003
            $content = file_get_contents($filePath);
            $xml = simplexml_load_string($content);
            if ($xml === false) {
                // Try fallback if XML load fails
                return redirect()->back()->with('error', 'Gagal membaca file Excel (XML). Pastikan format benar.');
            }

            // Register namespace
            $xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
            $xmlRows = $xml->xpath('//ss:Worksheet/ss:Table/ss:Row');

            if (isset($xmlRows[0])) {
                foreach ($xmlRows[0]->Cell as $cell) {
                    $header[] = (string)$cell->Data;
                }
                $totalCols = count($header);
                
                for ($i = 1; $i < count($xmlRows); $i++) {
                    // Gunakan parseXmlRow agar ss:Index ditangani dengan benar
                    $rowData = $this->parseXmlRow($xmlRows[$i], $totalCols);
                    if (!empty(array_filter($rowData))) {
                        $rows[] = $rowData;
                    }
                }
            }
        } else {
            // Handle CSV
            $fileHandle = fopen($filePath, 'r');
            $firstLine = fgets($fileHandle);
            fclose($fileHandle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

            $file = fopen($filePath, 'r');
            $header = fgetcsv($file, 0, $delimiter);
            
            if (isset($header[0])) {
                $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
            }

            while ($row = fgetcsv($file, 0, $delimiter)) {
                $rows[] = $row;
            }
            fclose($file);
        }

        $count = 0;
        Log::info("Import Excel: Ditemukan " . count($rows) . " baris data untuk diproses.");

        foreach ($rows as $index => $row) {
            // Ensure $row has enough columns to match $header
            if (count($header) > count($row)) {
                $row = array_pad($row, count($header), '');
            }
            $data = array_combine($header, array_slice($row, 0, count($header)));
            
            // Log baris yang diproses (terutama nama santri) untuk memudahkan debugging
            $nama = $data['nama_santri'] ?? 'TANPA NAMA';
            Log::info("Import Excel: Memproses baris #" . ($index + 1), ['nama' => $nama]);

            $raportData = [
                'nama_santri' => $nama,
                'no_induk' => $data['no_induk'] ?? '',
                'kelas' => $data['kelas'] ?? '',
                'semester' => !empty(trim($data['semester'] ?? '')) ? $data['semester'] : 'Ganjil',
                'tahun_pelajaran' => !empty(trim($data['tahun_pelajaran'] ?? '')) ? $data['tahun_pelajaran'] : '2023/2024',
                'tempat_tanggal_cetak' => $data['tempat_tanggal_cetak'] ?? '',
                'wali_kelas_nama' => $data['wali_kelas_nama'] ?? '',
                'kepala_madrasah_nama' => $data['kepala_madrasah_nama'] ?? '',
                'mental_moral' => !empty(trim($data['mental_moral'] ?? '')) ? $data['mental_moral'] : 'A',
                'mental_kedisiplinan' => !empty(trim($data['mental_kedisiplinan'] ?? '')) ? $data['mental_kedisiplinan'] : 'A',
                'mental_kerajinan' => !empty(trim($data['mental_kerajinan'] ?? '')) ? $data['mental_kerajinan'] : 'A',
                'mental_kebersihan' => !empty(trim($data['mental_kebersihan'] ?? '')) ? $data['mental_kebersihan'] : 'A',
                'sakit' => (int)($data['sakit'] ?? 0),
                'izin' => (int)($data['izin'] ?? 0),
                'ghoib' => (int)($data['ghoib'] ?? 0),
                'catatan_wali_kelas' => $data['catatan_wali_kelas'] ?? '',
                'nilai_mapel' => [],
                'ekstrakurikuler' => []
            ];

            $mapels = [
                'nahwu', 'mutholaah', 'shorof', 'mahfudzat', 'reading', 'dictation', 
                'mufrodzat', 'vocabularies', 'akhlak_lil_banin', 'durusuttafsir', 
                'ushul_fiqh', 'arbain_nawawi', 'bahasa_inggris', 'bahasa_arab', 'praktik_ibadah'
            ];

            foreach ($mapels as $m) {
                $p = (int)($data["{$m}_p"] ?? 0);
                $kkm = (int)($data["{$m}_kkm"] ?? 65);
                $raportData['nilai_mapel'][$m] = [
                    'kkm' => $kkm ?: 65,
                    'p_a' => $p,
                    'p_h' => $this->terbilang($p),
                    'k_a' => (int)($data["{$m}_k"] ?? 0),
                    'k_h' => $this->terbilang((int)($data["{$m}_k"] ?? 0)),
                    's_a' => (int)($data["{$m}_s"] ?? 0),
                    's_h' => $this->terbilang((int)($data["{$m}_s"] ?? 0)),
                    'ketercapaian' => $p >= $kkm ? 'Melampaui Kompetensi' : 'Perlu Bimbingan'
                ];
            }

            RaportKmi::create($raportData);
            $count++;
        }

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
