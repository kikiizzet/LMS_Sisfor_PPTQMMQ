<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor - {{ $rapor->nama_santri }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 10px; position: relative; }
        
        /* Watermark Tengah */
        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            width: 350px;
            z-index: -1;
        }

        /* Header / Kop */
        .header-container { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; }
        .kop-text { text-align: center; flex-grow: 1; }
        .kop-text h3 { margin: 0; font-size: 16px; letter-spacing: 1px; }
        .kop-text h2 { margin: 5px 0; font-size: 18px; line-height: 1.2; }

        /* Identitas */
        .table-identitas { width: 100%; margin-bottom: 20px; font-size: 14px; }
        .table-identitas td { padding: 3px; }

        /* Tabel Nilai */
        .table-nilai { width: 100%; border-collapse: collapse; text-align: center; font-size: 14px; background: rgba(255, 255, 255, 0.8); }
        .table-nilai th, .table-nilai td { border: 1px solid black; padding: 10px; }
        .table-nilai th { background-color: #f2f2f2; }
        .row-rata { background-color: #f9f9f9; font-weight: bold; }

        /* Keterangan Tambahan */
        .box-catatan { border: 1px solid black; padding: 15px; margin-top: 20px; min-height: 80px; text-align: center; position: relative; }
        .label-catatan { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: white; padding: 0 10px; font-weight: bold; font-size: 12px; }

        /* Tanda Tangan */
        .ttd-container { width: 100%; margin-top: 30px; font-size: 14px; }
        .ttd-table { width: 100%; text-align: center; border-collapse: collapse; }
        .ttd-space { height: 70px; }

        @media print { 
            .no-print { display: none; } 
            @page { 
                size: auto; 
                margin: 0; 
            }
            body { 
                margin: 1.5cm; 
                padding: 0; 
            }
        }
    </style>
</head>
<body>

    @php
        // Read parameters from request query string
        $semester = request('semester', 'Ganjil');
        $tahunAjaran = request('tahun_ajaran', '2025/2026');
        $halaqoh = request('halaqoh', 'Tahfidz');
        $kkm = request('kkm', '60');
        $tanggalCetak = request('tanggal_cetak', 'Pringkuku, 20 Desember 2025');

        // Fungsi Penentu Predikat Berdasarkan Catatan
        function getPredikat($nilai) {
            if ($nilai >= 90) return 'Mumtaz';         // 90-100
            if ($nilai >= 80) return 'Jayyid Jiddan';  // 80-89
            if ($nilai >= 70) return 'Jayyid';         // 70-79
            if ($nilai >= 60) return 'Maqbul';         // 60-69
            return 'Dhaif';                            // < 60 (Dibawah KKM)
        }

        // Hitung Rata-rata
        $rataRata = ($rapor->adab + $rapor->kelancaran + $rapor->tajwid + $rapor->fashahah) / 4;
    @endphp

    <button class="no-print" onclick="window.print()" style="margin-bottom: 20px; padding: 12px 20px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 5px; font-weight: bold;">
        🖨️ CETAK RAPOR / SIMPAN PDF
    </button>

    <img src="{{ asset('images/logoo.png') }}" class="watermark" alt="watermark">

    <div class="header-container">
        <img src="{{ asset('images/logoo.png') }}" class="logo" alt="logo-kiri">
        <div class="kop-text">
            <h3>LAPORAN PENILAIAN TAHFIDZ</h3>
            <h2>PONDOK PESANTREN TAHFIDZUL QUR'AN<br>MAKKAH MADINATUL QUR'AN</h2>
            <p style="margin: 0; font-size: 12px;">Pringkuku, Pacitan, Jawa Timur</p>
        </div>
        <div style="width: 80px;"></div>
    </div>

    <table class="table-identitas">
        <tr>
            <td width="18%">Nama Santri</td><td width="32%">: <strong>{{ strtoupper($rapor->nama_santri) }}</strong></td>
            <td width="18%">Semester</td><td width="32%">: {{ $semester }}</td>
        </tr>
        <tr>
            <td>Musyrif</td><td>: {{ $rapor->musyrif }}</td>
            <td>Tahun Ajaran</td><td>: {{ $tahunAjaran }}</td>
        </tr>
        <tr>
            <td>Halaqoh</td><td>: {{ $halaqoh }}</td>
            <td>KKM</td><td>: {{ $kkm }}</td>
        </tr>
    </table>

    <table class="table-nilai">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Aspek Penilaian</th>
                <th width="15%">Nilai</th>
                <th width="35%">Keterangan (Predikat)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td><td style="text-align: left;">Adab di Dalam Halaqah</td>
                <td>{{ $rapor->adab }}</td>
                <td>{{ getPredikat($rapor->adab) }}</td>
            </tr>
            <tr>
                <td>2</td><td style="text-align: left;">Kelancaran Hafalan</td>
                <td>{{ $rapor->kelancaran }}</td>
                <td>{{ getPredikat($rapor->kelancaran) }}</td>
            </tr>
            <tr>
                <td>3</td><td style="text-align: left;">Ketepatan Tajwid</td>
                <td>{{ $rapor->tajwid }}</td>
                <td>{{ getPredikat($rapor->tajwid) }}</td>
            </tr>
            <tr>
                <td>4</td><td style="text-align: left;">Fashahah & Makharijul Huruf</td>
                <td>{{ $rapor->fashahah }}</td>
                <td>{{ getPredikat($rapor->fashahah) }}</td>
            </tr>
            <tr class="row-rata">
                <td colspan="2">RATA-RATA NILAI</td>
                <td>{{ number_format($rataRata, 1) }}</td>
                <td>{{ getPredikat($rataRata) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="box-catatan">
        <span class="label-catatan">CAPAIAN HAFALAN & CATATAN MUSYRIF</span>
        <p style="margin-top: 10px;">{{ $rapor->catatan }}</p>
        <p style="font-size: 12px; color: #555;"><em>"Tetap semangat menambah ziyadah dan menjaga murajaah!"</em></p>
    </div>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td width="50%">Mengetahui,</td>
                <td width="50%">{{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td>Wali Santri</td>
                <td>Ketua Koordinator Tahfidz</td>
            </tr>
            <tr class="ttd-space">
                <td></td>
                <td style="text-align: center; vertical-align: middle; height: 70px;">
                    @if(isset($guru) && $guru->ttd)
                        <img src="{{ $guru->ttd }}" style="height: 65px; max-width: 180px; display: inline-block;" alt="Ttd Musyrif">
                    @endif
                </td>
            </tr>
            <tr>
                <td>( ............................................ )</td>
                <td>( <strong>{{ $rapor->musyrif }}</strong> )</td>
            </tr>
        </table>

        <table class="ttd-table" style="margin-top: 30px;">
            <tr>
                <td colspan="2" style="text-align: center;">Mengetahui,<br>Pimpinan PPTQ Makkah Madinatul Qur'an</td>
            </tr>
            <tr style="height: 60px;"><td colspan="2"></td></tr>
            <tr>
                <td style="width: 50%; text-align: center;">
                    <span style="text-decoration: underline; font-weight: bold;">H. Sarip Husen, S. Pd. I</span>
                </td>
                <td style="width: 50%; text-align: center;">
                    <span style="text-decoration: underline; font-weight: bold;">H. M. Zaidi, S. Pd. I</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>