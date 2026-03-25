<?php
/**
 * Debug script - jalankan via: php debug_import.php
 * Ini akan membuat file XML test seperti yang dihasilkan template,
 * lalu parse ulang untuk melihat output.
 */

// Simulasi template Excel yang dibuat downloadTemplate()
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

echo "Total kolom: " . count($columns) . "\n";
echo "Kolom 0-3: " . implode(", ", array_slice($columns, 0, 4)) . "\n\n";

// Buat XML simulasi - user hanya mengisi 4 kolom pertama (nama, no_induk, kelas, semester)
// Kolom sisanya KOSONG → Excel XML tidak merender cell kosong di akhir baris

$xml_content = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
 <Worksheet ss:Name="Template Raport KMI">
  <Table>
   <Row ss:Height="20">
';

// Header row - semua kolom terisi
foreach ($columns as $col) {
    $xml_content .= '    <Cell ss:StyleID="sHeader"><Data ss:Type="String">' . $col . '</Data></Cell>' . "\n";
}
$xml_content .= '   </Row>' . "\n";

// Data row 1 - HANYA 4 kolom terisi (simulasi user input Ronaldo)
// Skenario A: Excel menyimpan dengan ss:Index (Excel biasanya tambahkan ini)
$xml_content .= '   <Row>' . "\n";
$xml_content .= '    <Cell><Data ss:Type="String">Ronaldo</Data></Cell>' . "\n";
$xml_content .= '    <Cell><Data ss:Type="Number">12345</Data></Cell>' . "\n";
$xml_content .= '    <Cell><Data ss:Type="String">1KMI</Data></Cell>' . "\n";
$xml_content .= '    <Cell><Data ss:Type="String">Genap</Data></Cell>' . "\n";
// kolom sisanya tidak dirender sama sekali di XML
$xml_content .= '   </Row>' . "\n";

$xml_content .= '  </Table>
 </Worksheet>
</Workbook>';

$tmpFile = sys_get_temp_dir() . '/debug_kmi_test.xls';
file_put_contents($tmpFile, $xml_content);

echo "=== Parsing XML dengan metode LAMA (bergeser) ===\n";
$xml = simplexml_load_string($xml_content);
$xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
$xmlRows = $xml->xpath('//ss:Worksheet/ss:Table/ss:Row');

$header_old = [];
foreach ($xmlRows[0]->Cell as $cell) {
    $header_old[] = (string)$cell->Data;
}
$headerIdx_old = array_search('nama_santri', $header_old);
echo "Index nama_santri: $headerIdx_old\n";

// Metode lama
$cells = array_values((array)$xmlRows[1]->Cell);
echo "Jumlah cell di data row: " . count($cells) . "\n";
$val_old = $headerIdx_old !== false && isset($cells[$headerIdx_old]) ? (string)$xmlRows[1]->Cell[$headerIdx_old]->Data : 'TIDAK TERBACA';
echo "Nama terbaca (lama): '$val_old'\n\n";

echo "=== Parsing XML dengan metode BARU (parseXmlRow) ===\n";
$totalCols = count($header_old);

function parseXmlRow($xmlRow, int $totalCols): array {
    $row = array_fill(0, $totalCols, '');
    $col = 0;
    foreach ($xmlRow->Cell as $cell) {
        $ssIndex = (string)$cell->attributes('urn:schemas-microsoft-com:office:spreadsheet')->Index;
        if ($ssIndex !== '') {
            $col = (int)$ssIndex - 1;
        }
        if ($col < $totalCols) {
            $row[$col] = (string)$cell->Data;
        }
        $col++;
    }
    return $row;
}

$rowData = parseXmlRow($xmlRows[1], $totalCols);
$val_new = ($headerIdx_old !== false && isset($rowData[$headerIdx_old])) ? $rowData[$headerIdx_old] : 'TIDAK TERBACA';
echo "Nama terbaca (baru): '$val_new'\n";
echo "Row data [0-3]: " . $rowData[0] . " | " . $rowData[1] . " | " . $rowData[2] . " | " . $rowData[3] . "\n\n";

echo "=== CEK: Apakah array_filter menyaring row ini? ===\n";
$isEmpty = empty(array_filter($rowData));
echo "empty(array_filter(rowData))? " . ($isEmpty ? 'YA - BARIS DISKIP!' : 'Tidak - baris diproses') . "\n";
echo "Nilai non-empty: " . implode(", ", array_filter($rowData)) . "\n\n";

// Cek juga apakah nama_santri ada di kolom 0
echo "=== Check posisi kolom ===\n";
foreach (array_slice($columns, 0, 6) as $i => $col) {
    echo "Kolom $i: $col → rowData[$i] = '" . ($rowData[$i] ?? 'N/A') . "'\n";
}

unlink($tmpFile);
echo "\nDone.\n";
