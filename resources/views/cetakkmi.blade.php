<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai KMI - {{ $raport->nama_santri }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 10px; margin: 0; padding: 10px; color: #000; line-height: 1.2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 2px 0; text-transform: uppercase; font-size: 12px; }
        .header h4 { margin: 2px 0; font-size: 11px; }
        
        .info-table { width: 100%; margin-bottom: 10px; border: none !important; }
        .info-table td { border: none !important; padding: 1px 4px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; border: 1.5px solid black; }
        th, td { border: 1px solid black; padding: 3px; }
        th { background-color: #fff; font-weight: bold; text-align: center; font-size: 9px; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        
        .section-header { font-weight: bold; background-color: #f9f9f9; text-align: left; }
        
        .summary-table td { padding: 4px; font-size: 9px; vertical-align: middle; }
        .terbilang-cell { font-size: 8px; font-style: italic; background-color: #fafafa; }
        
        .side-by-side { width: 100%; border: none !important; margin-top: 5px; }
        .side-by-side > tr > td { width: 50%; padding: 0 5px 0 0; border: none !important; vertical-align: top; }
        .side-by-side > tr > td:last-child { padding: 0 0 0 5px; }

        .footer-table { width: 100%; border: none !important; margin-top: 20px; }
        .footer-table td { border: none !important; text-align: center; padding: 5px 0; vertical-align: top; }
        
        .signature-box { margin-top: 40px; }
    </style>
</head>
@php
if (!function_exists('terbilang_kmi')) {
    function terbilang_kmi($n) {
        $n = abs($n);
        $arr = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";
        if ($n < 12) {
            $temp = " ".$arr[$n];
        } else if ($n < 20) {
            $temp = terbilang_kmi($n - 10)." Belas";
        } else if ($n < 100) {
            $temp = terbilang_kmi(floor($n/10))." Puluh".terbilang_kmi($n%10);
        } else if ($n < 200) {
            $temp = " Seratus".terbilang_kmi($n - 100);
        } else if ($n < 1000) {
            $temp = terbilang_kmi(floor($n/100))." Ratus".terbilang_kmi($n%100);
        } else if ($n < 2000) {
            $temp = " Seribu".terbilang_kmi($n - 1000);
        } else if ($n < 1000000) {
            $temp = terbilang_kmi(floor($n/1000))." Ribu".terbilang_kmi($n%1000);
        }
        return trim($temp);
    }
    
    function terbilang_rata($n) {
        $n = number_format($n, 2, '.', '');
        $labels = ["Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
        $res = "";
        for($i=0; $i<strlen($n); $i++) {
            if ($n[$i] == '.') $res .= " Koma";
            else $res .= " " . $labels[(int)$n[$i]];
        }
        return trim($res);
    }
}
@endphp
<body>
    <div class="header">
        <h3>LAPORAN PENILAIAN HASIL BELAJAR SANTRI</h3>
        <h3>PENILAIAN AKHIR SEMESTER ( PAS )</h3>
        <h4>PROGRAM KULLIYATUL MU'ALLIMIN WAL MU'ALLIMAT AL ISLAMIYAH ( KMI )</h4>
        <h4>PONDOK PESANTREN TAHFIDZUL QUR'AN MAKKAH MADINATUL QUR'AN</h4>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%;">Nama Pesantren</td>
            <td style="width: 1%;">:</td>
            <td style="width: 34%;" class="fw-bold">DQ MAKKAH MADINATUL QUR'AN</td>
            <td style="width: 15%;">Kelas</td>
            <td style="width: 1%;">:</td>
            <td style="width: 34%;">{{ $raport->kelas }}</td>
        </tr>
        <tr>
            <td>Nama Santri</td>
            <td>:</td>
            <td class="fw-bold text-uppercase">{{ $raport->nama_santri }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $raport->semester }}</td>
        </tr>
        <tr>
            <td>No. Induk</td>
            <td>:</td>
            <td>{{ $raport->no_induk }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $raport->tahun_pelajaran }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">No.</th>
                <th rowspan="2" style="width: 20%;">MATA PELAJARAN</th>
                <th rowspan="2" style="width: 4%;">KKM</th>
                <th colspan="2">Pengetahuan (P)</th>
                <th colspan="2">Keterampilan (K)</th>
                <th colspan="2">Sikap (S)</th>
                <th rowspan="2" style="width: 18%;">KETERCAPAIAN KOMPETENSI</th>
            </tr>
            <tr>
                <th style="width: 5%;">Angka</th>
                <th style="width: 12%;">Huruf</th>
                <th style="width: 5%;">Angka</th>
                <th style="width: 12%;">Huruf</th>
                <th style="width: 5%;">Angka</th>
                <th style="width: 12%;">Huruf</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="10" class="section-header ms-2">Kelompok A (Ujian Tulis)</td></tr>
            @php
                $subjectsA = [
                    'nahwu' => 'Nahwu', 'mutholaah' => 'Mutholaah', 'shorof' => 'Shorof', 'mahfudzat' => 'Mahfudzat',
                    'reading' => 'Reading', 'dictation' => 'Dictation', 'mufrodzat' => 'Mufrodzat', 'vocabularies' => 'Vocabularies',
                    'akhlak_lil_banin' => 'Akhlak Lil Banin', 'durusuttafsir' => 'Durusuttafsir', 'ushul_fiqh' => 'Ushul Fiqh', 'arbain_nawawi' => 'Arba\'in Nawawi'
                ];
                $no = 1;
                $sumP = 0; $sumK = 0; $sumS = 0;
            @endphp
            @foreach($subjectsA as $key => $name)
                @php 
                    $val = $raport->nilai_mapel[$key] ?? []; 
                    $sumP += (int)($val['p_a'] ?? 0);
                    $sumK += (int)($val['k_a'] ?? 0);
                    $sumS += (int)($val['s_a'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}.</td>
                    <td>{{ $name }}</td>
                    <td class="text-center">{{ $val['kkm'] ?? '65' }}</td>
                    <td class="text-center fw-bold">{{ $val['p_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['p_h'] ?? '-' }}</td>
                    <td class="text-center fw-bold">{{ $val['k_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['k_h'] ?? '-' }}</td>
                    <td class="text-center fw-bold">{{ $val['s_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['s_h'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['ketercapaian'] ?? '-' }}</td>
                </tr>
            @endforeach

            <tr><td colspan="10" class="section-header ms-2">Kelompok B (Ujian Lisan / Praktik)</td></tr>
            @php
                $subjectsB = [
                    'bahasa_inggris' => 'Bahasa Inggris', 'bahasa_arab' => 'Bahasa Arab', 'praktik_ibadah' => 'Praktik Ibadah'
                ];
                $noB = 1;
            @endphp
            @foreach($subjectsB as $key => $name)
                @php 
                    $val = $raport->nilai_mapel[$key] ?? []; 
                    $sumP += (int)($val['p_a'] ?? 0);
                    $sumK += (int)($val['k_a'] ?? 0);
                    $sumS += (int)($val['s_a'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $noB++ }}.</td>
                    <td>{{ $name }}</td>
                    <td class="text-center">{{ $val['kkm'] ?? '65' }}</td>
                    <td class="text-center fw-bold">{{ $val['p_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['p_h'] ?? '-' }}</td>
                    <td class="text-center fw-bold">{{ $val['k_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['k_h'] ?? '-' }}</td>
                    <td class="text-center fw-bold">{{ $val['s_a'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['s_h'] ?? '-' }}</td>
                    <td style="font-size: 8px;">{{ $val['ketercapaian'] ?? '-' }}</td>
                </tr>
            @endforeach

            <tr class="fw-bold">
                <td colspan="3" class="text-center">Jumlah Nilai</td>
                <td class="text-center">{{ $sumP }}</td>
                <td class="terbilang-cell">{{ terbilang_kmi($sumP) }}</td>
                <td class="text-center">{{ $sumK }}</td>
                <td class="terbilang-cell">{{ terbilang_kmi($sumK) }}</td>
                <td class="text-center">{{ $sumS }}</td>
                <td class="terbilang-cell">{{ terbilang_kmi($sumS) }}</td>
                <td></td>
            </tr>
            @php 
                $totalMapel = count($subjectsA) + count($subjectsB);
                $avgP = $sumP / $totalMapel;
                $avgK = $sumK / $totalMapel;
                $avgS = $sumS / $totalMapel;
            @endphp
            <tr class="fw-bold">
                <td colspan="3" class="text-center">Rata-rata</td>
                <td class="text-center">{{ number_format($avgP, 2) }}</td>
                <td class="terbilang-cell">{{ terbilang_rata($avgP) }}</td>
                <td class="text-center">{{ number_format($avgK, 2) }}</td>
                <td class="terbilang-cell">{{ terbilang_rata($avgK) }}</td>
                <td class="text-center">{{ number_format($avgS, 2) }}</td>
                <td class="terbilang-cell">{{ terbilang_rata($avgS) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="side-by-side">
        <tr>
            <td>
                <!-- Ekstrakurikuler -->
                <table style="height: 120px;">
                    <thead>
                        <tr style="height: 20px;">
                            <th style="width: 10%;">No.</th>
                            <th style="width: 65%;">EKSTRAKURIKULER</th>
                            <th style="width: 25%;">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $eks = $raport->ekstrakurikuler ?? []; @endphp
                        @for($i = 0; $i < 5; $i++)
                            <tr style="height: 20px;">
                                <td class="text-center">{{ $i+1 }}.</td>
                                <td>{{ $eks[$i]['nama'] ?? '' }}</td>
                                <td class="text-center fw-bold">{{ $eks[$i]['nilai'] ?? '' }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </td>
            <td>
                <!-- Absensi -->
                <table style="height: 120px;">
                    <thead>
                        <tr style="height: 20px;">
                            <th style="width: 10%;">NO</th>
                            <th style="width: 60%;">Ketidakhadiran</th>
                            <th style="width: 30%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 20px;"><td class="text-center">1.</td><td>Sakit</td><td class="text-center fw-bold">{{ $raport->sakit }} Hari</td></tr>
                        <tr style="height: 20px;"><td class="text-center">2.</td><td>Ijin</td><td class="text-center fw-bold">{{ $raport->izin }} Hari</td></tr>
                        <tr style="height: 20px;"><td class="text-center">3.</td><td>tidak Hadir ( Ghoib )</td><td class="text-center fw-bold">{{ $raport->ghoib }} Hari</td></tr>
                        <tr style="height: 20px;" class="fw-bold">
                            <td colspan="2" class="text-center">Jumlah</td>
                            <td class="text-center">{{ $raport->sakit + $raport->izin + $raport->ghoib }} Hari</td>
                        </tr>
                        <!-- Empty row to match Ekstrakurikuler height -->
                        <tr style="height: 20px;">
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table class="side-by-side">
        <tr>
            <td>
                <!-- Mental 1 -->
                <table>
                    <thead>
                        <tr style="height: 20px;">
                            <th style="width: 10%;">No.</th>
                            <th style="width: 65%;">MENTAL</th>
                            <th style="width: 25%;">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 20px;"><td class="text-center">1.</td><td>Moral</td><td class="text-center fw-bold">{{ $raport->mental_moral }}</td></tr>
                        <tr style="height: 20px;"><td class="text-center">2.</td><td>Kedisiplinan</td><td class="text-center fw-bold">{{ $raport->mental_kedisiplinan }}</td></tr>
                    </tbody>
                </table>
            </td>
            <td>
                <!-- Mental 2 -->
                <table>
                    <thead>
                        <tr style="height: 20px;">
                            <th style="width: 10%;">NO</th>
                            <th style="width: 65%;">MENTAL</th>
                            <th style="width: 25%;">NILAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 20px;"><td class="text-center">3.</td><td>Kerajinan</td><td class="text-center fw-bold">{{ $raport->mental_kerajinan }}</td></tr>
                        <tr style="height: 20px;"><td class="text-center">4.</td><td>Kebersihan</td><td class="text-center fw-bold">{{ $raport->mental_kebersihan }}</td></tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div style="border: 1.5px solid black; padding: 5px; margin-top: 5px; min-height: 40px;">
        <span class="fw-bold">Catatan Wali kelas:</span>
        <span style="font-style: italic;">" {{ $raport->catatan_wali_kelas }} "</span>
    </div>

    <table class="footer-table">
        <tr>
            <td style="width: 33%; vertical-align: top;">
                <br>
                Orang Tua / Wali
                <div style="height: 60px;"></div>
                ..........................................
            </td>
            <td style="width: 33%; vertical-align: top;">
                <br>
                Wali Kelas
                <div style="height: 60px;"></div>
                <strong>{{ $raport->wali_kelas_nama }}</strong><br>
                NIP : -
            </td>
            <td style="width: 34%; vertical-align: top;">
                Pacitan, {{ $raport->tempat_tanggal_cetak }}<br>
                Mengetahui,<br>
                Kepala Madrasah
                <div style="height: 48px;"></div>
                <strong>H. Muhammad Zaidi, S. Pd. I</strong><br>
                NIP : -
            </td>
        </tr>
    </table>
</body>
</html>
