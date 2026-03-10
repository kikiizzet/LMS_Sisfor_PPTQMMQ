<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai KMI - {{ $raport->nama_santri }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; position: relative; }
        .header h2, .header h3, .header h4 { margin: 2px 0; text-transform: uppercase; }
        .logo { position: absolute; top: 0; width: 60px; }
        .logo-left { left: 10px; }
        .logo-right { right: 10px; }
        
        .info-table { width: 100%; margin-bottom: 10px; }
        .info-table td { padding: 2px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th { background-color: #f2f2f2; padding: 5px; text-align: center; }
        td { padding: 4px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer-table { width: 100%; border: none; margin-top: 20px; }
        .footer-table td { border: none; text-align: center; padding: 20px 0; }
        
        .section-header { font-weight: bold; background-color: #eee; }
        
        .note-box { border: 1px solid black; padding: 10px; margin-top: 10px; min-height: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo placeholder jika ada -->
        <h3>LAPORAN PENILAIAN HASIL BELAJAR SANTRI</h3>
        <h3>PENILAIAN AKHIR SEMESTER ( PAS )</h3>
        <h4>PROGRAM KULLIYATUL MU'ALLIMIN WAL MU'ALLIMAT AL ISLAMIYAH ( KMI )</h4>
        <h4>PONDOK PESANTREN TAHFIDZUL QUR'AN MAKKAH MADINATUL QUR'AN</h4>
    </div>

    <table class="info-table" style="border: none;">
        <tr style="border: none;">
            <td style="border: none; width: 15%;">Nama Pesantren</td>
            <td style="border: none; width: 1%;">:</td>
            <td style="border: none; width: 34%;">{{ $raport->nama_pesantren }}</td>
            <td style="border: none; width: 15%;">Kelas</td>
            <td style="border: none; width: 1%;">:</td>
            <td style="border: none; width: 34%;">{{ $raport->kelas }}</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none;">Nama Santri</td>
            <td style="border: none;">:</td>
            <td style="border: none;">{{ $raport->nama_santri }}</td>
            <td style="border: none;">Semester</td>
            <td style="border: none;">:</td>
            <td style="border: none;">{{ $raport->semester }}</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none;">No. Induk</td>
            <td style="border: none;">:</td>
            <td style="border: none;">{{ $raport->no_induk }}</td>
            <td style="border: none;">Tahun Pelajaran</td>
            <td style="border: none;">:</td>
            <td style="border: none;">{{ $raport->tahun_pelajaran }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">No.</th>
                <th rowspan="2" style="width: 20%;">Mata Pelajaran</th>
                <th rowspan="2" style="width: 5%;">KKM</th>
                <th colspan="2">Pengetahuan (Kognitif)</th>
                <th colspan="2">Keterampilan (Psikomotor)</th>
                <th colspan="2">Sikap (Afektif)</th>
                <th rowspan="2" style="width: 15%;">Ketercapaian Kompetensi</th>
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
            <tr><td colspan="10" class="section-header">Kelompok A (Ujian Tulis)</td></tr>
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
                    $sumP += ($val['p_a'] ?? 0);
                    $sumK += ($val['k_a'] ?? 0);
                    $sumS += ($val['s_a'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $name }}</td>
                    <td class="text-center">{{ $val['kkm'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['p_a'] ?? '-' }}</td>
                    <td>{{ $val['p_h'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['k_a'] ?? '-' }}</td>
                    <td>{{ $val['k_h'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['s_a'] ?? '-' }}</td>
                    <td>{{ $val['s_h'] ?? '-' }}</td>
                    <td>{{ $val['ketercapaian'] ?? '-' }}</td>
                </tr>
            @endforeach

            <tr><td colspan="10" class="section-header">Kelompok B (Ujian Lisan / Praktik)</td></tr>
            @php
                $subjectsB = [
                    'bahasa_inggris' => 'Bahasa Inggris', 'bahasa_arab' => 'Bahasa Arab', 'praktik_ibadah' => 'Praktik Ibadah'
                ];
                $noB = 1;
            @endphp
            @foreach($subjectsB as $key => $name)
                @php 
                    $val = $raport->nilai_mapel[$key] ?? []; 
                    $sumP += ($val['p_a'] ?? 0);
                    $sumK += ($val['k_a'] ?? 0);
                    $sumS += ($val['s_a'] ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $noB++ }}</td>
                    <td>{{ $name }}</td>
                    <td class="text-center">{{ $val['kkm'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['p_a'] ?? '-' }}</td>
                    <td>{{ $val['p_h'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['k_a'] ?? '-' }}</td>
                    <td>{{ $val['k_h'] ?? '-' }}</td>
                    <td class="text-center">{{ $val['s_a'] ?? '-' }}</td>
                    <td>{{ $val['s_h'] ?? '-' }}</td>
                    <td>{{ $val['ketercapaian'] ?? '-' }}</td>
                </tr>
            @endforeach

            <tr style="font-weight: bold;">
                <td colspan="3" class="text-center">Jumlah Nilai</td>
                <td class="text-center">{{ $sumP }}</td>
                <td></td>
                <td class="text-center">{{ $sumK }}</td>
                <td></td>
                <td class="text-center">{{ $sumS }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="3" class="text-center">Rata-rata</td>
                <td class="text-center">{{ number_format($sumP / (count($subjectsA) + count($subjectsB)), 2) }}</td>
                <td></td>
                <td class="text-center">{{ number_format($sumK / (count($subjectsA) + count($subjectsB)), 2) }}</td>
                <td></td>
                <td class="text-center">{{ number_format($sumS / (count($subjectsA) + count($subjectsB)), 2) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between;">
        <div style="width: 48%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">No.</th>
                        <th>EKSTRAKURIKULER</th>
                        <th style="width: 20%;">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    @php $eks = $raport->ekstrakurikuler ?? []; @endphp
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td class="text-center">{{ $i+1 }}.</td>
                            <td>{{ $eks[$i]['nama'] ?? '' }}</td>
                            <td class="text-center">{{ $eks[$i]['nilai'] ?? '' }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">NO</th>
                        <th>Ketidakhadiran</th>
                        <th style="width: 20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-center">1.</td><td>Sakit</td><td class="text-center">{{ $raport->sakit }} Hari</td></tr>
                    <tr><td class="text-center">2.</td><td>Ijin</td><td class="text-center">{{ $raport->izin }} Hari</td></tr>
                    <tr><td class="text-center">3.</td><td>tidak Hadir ( Ghoib )</td><td class="text-center">{{ $raport->ghoib }} Hari</td></tr>
                    <tr style="font-weight: bold;">
                        <td colspan="2" class="text-center">Jumlah</td>
                        <td class="text-center">{{ $raport->sakit + $raport->izin + $raport->ghoib }} Hari</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="width: 48%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">No.</th>
                        <th>MENTAL</th>
                        <th style="width: 20%;">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-center">1.</td><td>Moral</td><td class="text-center">{{ $raport->mental_moral }}</td></tr>
                    <tr><td class="text-center">2.</td><td>Kedisiplinan</td><td class="text-center">{{ $raport->mental_kedisiplinan }}</td></tr>
                    <tr><td class="text-center">3.</td><td>Kerajinan</td><td class="text-center">{{ $raport->mental_kerajinan }}</td></tr>
                    <tr><td class="text-center">4.</td><td>Kebersihan</td><td class="text-center">{{ $raport->mental_kebersihan }}</td></tr>
                </tbody>
            </table>
            
            <div style="border: 1px solid black; padding: 5px; margin-top: 10px;">
                <strong>Catatan Wali kelas:</strong>
                <p style="margin: 5px 0;">" {{ $raport->catatan_wali_kelas }} "</p>
            </div>
        </div>
    </div>

    <table class="footer-table">
        <tr>
            <td style="width: 33%;">
                Orang Tua / Wali<br><br><br><br>
                ..........................................
            </td>
            <td style="width: 33%;">
                Wali Kelas<br><br><br><br>
                <strong>{{ $raport->wali_kelas_nama }}</strong><br>
                NIP : {{ $raport->wali_kelas_nip ?? '-' }}
            </td>
            <td style="width: 33%;">
                {{ $raport->tempat_tanggal_cetak }}<br>
                Mengetahui,<br>
                Kepala Madrasah<br><br><br><br>
                <strong>{{ $raport->kepala_madrasah_nama }}</strong><br>
                NIP : {{ $raport->kepala_madrasah_nip ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
