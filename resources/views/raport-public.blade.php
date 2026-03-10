<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport {{ $namaSantri }} — MMQ Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { overflow-x: hidden; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            min-height: 100vh;
            color: #334155;
            overflow-x: hidden;
            width: 100%;
        }

        /* Header */
        .header-section {
            background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 50%, #0284c7 100%);
            padding: 18px 14px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
        }
        .header-content { position: relative; z-index: 1; }
        .pesantren-name {
            font-size: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 4px;
        }
        .student-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            word-break: break-word;
        }
        .subtitle {
            color: rgba(255,255,255,0.6);
            font-size: 0.65rem;
        }

        /* Main container */
        .main-container {
            max-width: 400px;
            margin: -28px auto 20px;
            padding: 0 10px;
            position: relative;
            z-index: 2;
            width: 100%;
        }

        /* Cards */
        .section-card {
            background: white;
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .section-title {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-title i {
            font-size: 0.85rem;
            color: #0ea5e9;
        }

        /* Score grid */
        .score-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .score-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 8px 6px;
            text-align: center;
            transition: transform 0.2s;
        }
        .score-item:hover { transform: translateY(-1px); }
        .score-label {
            font-size: 0.5rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            font-weight: 600;
        }
        .score-value {
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1;
        }
        .score-predikat {
            font-size: 0.5rem;
            margin-top: 2px;
            padding: 1px 6px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 700;
        }

        /* Average card */
        .avg-card {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            margin-top: 6px;
            color: white;
        }
        .avg-card .avg-label {
            font-size: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .avg-card .avg-value {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1.2;
        }
        .avg-card .avg-predikat {
            font-size: 0.6rem;
            font-weight: 700;
            background: rgba(255,255,255,0.2);
            padding: 2px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 2px;
        }

        /* KMI Table */
        .kmi-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }
        .kmi-table th {
            background: #f1f5f9;
            padding: 6px 8px;
            text-align: left;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
        }
        .kmi-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .kmi-table tr:last-child td { border-bottom: none; }

        /* Info row */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.75rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94a3b8; font-weight: 500; }
        .info-value { font-weight: 700; color: #1e293b; text-align: right; max-width: 60%; word-break: break-word; }

        /* Notes box */
        .notes-box {
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            border-radius: 0 10px 10px 0;
            padding: 10px 12px;
            font-size: 0.75rem;
            color: #92400e;
            line-height: 1.5;
        }

        /* Color helpers */
        .c-mumtaz { color: #059669; }
        .c-jayyid-jiddan { color: #0284c7; }
        .c-jayyid { color: #d97706; }
        .c-maqbul { color: #dc2626; }
        .bg-mumtaz { background: #ecfdf5; color: #059669; }
        .bg-jayyid-jiddan { background: #e0f2fe; color: #0284c7; }
        .bg-jayyid { background: #fef3c7; color: #d97706; }
        .bg-maqbul { background: #fef2f2; color: #dc2626; }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-print {
            background: #0ea5e9;
            color: white;
        }
        .btn-print:hover { background: #0284c7; transform: translateY(-2px); }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }
        .empty-state i { font-size: 2rem; margin-bottom: 10px; display: block; }

        /* Print styles */
        @media print {
            body { background: white; }
            .header-section { background: #0369a1 !important; }
            .main-container { margin-top: -30px; }
            .section-card { box-shadow: 0 0 0 1px #e2e8f0; break-inside: avoid; }
            .action-buttons, .footer, .theme-fab { display: none; }
        }

        /* ===== DARK MODE ===== */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #020617 0%, #0f172a 50%, #020617 100%);
        }
        [data-theme="dark"] .header-section {
            background: linear-gradient(135deg, #0a1628 0%, #0c2d4d 50%, #0a1e3a 100%);
        }
        [data-theme="dark"] .section-card {
            background: #1e293b;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            color: #cbd5e1;
        }
        [data-theme="dark"] .score-item {
            background: #334155;
        }
        [data-theme="dark"] .score-label { color: #94a3b8; }
        [data-theme="dark"] .section-title { color: #64748b; }
        [data-theme="dark"] .info-label { color: #64748b; }
        [data-theme="dark"] .info-value { color: #e2e8f0 !important; }
        [data-theme="dark"] .info-row { border-bottom-color: #334155; }

        [data-theme="dark"] .kmi-table th { background: #334155; color: #94a3b8; }
        [data-theme="dark"] .kmi-table td { border-bottom-color: #334155; color: #cbd5e1; }
        [data-theme="dark"] .kmi-table tr:last-child td { border-bottom: none; }

        [data-theme="dark"] .notes-box {
            background: #1a1c2e;
            border-left-color: #b45309;
            color: #fbbf24;
        }

        [data-theme="dark"] .avg-card {
            background: linear-gradient(135deg, #0369a1, #0c4a6e);
        }

        [data-theme="dark"] .bg-mumtaz { background: #064e3b; color: #34d399; }
        [data-theme="dark"] .bg-jayyid-jiddan { background: #0c4a6e; color: #38bdf8; }
        [data-theme="dark"] .bg-jayyid { background: #451a03; color: #fbbf24; }
        [data-theme="dark"] .bg-maqbul { background: #450a0a; color: #f87171; }

        [data-theme="dark"] .btn-print {
            background: #0369a1;
        }
        [data-theme="dark"] .btn-print:hover { background: #0c4a6e; }

        /* Floating Theme Toggle */
        .theme-fab {
            position: fixed;
            bottom: 20px; right: 20px;
            width: 44px; height: 44px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            border: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: all 0.3s;
            z-index: 999;
            backdrop-filter: blur(10px);
        }
        .theme-fab:hover { transform: scale(1.1); }
        [data-theme="dark"] .theme-fab {
            background: rgba(30,41,59,0.9);
            border-color: #475569;
            color: #fbbf24;
        }
    </style>
</head>
<body>

    <div class="header-section">
        <div class="header-content">
            <p class="pesantren-name">Pondok Pesantren Tahfidzul Qur'an</p>
            <h1 class="student-name">{{ $namaSantri }}</h1>
            <p class="subtitle">Makkah Madinatul Qur'an — Pacitan</p>
        </div>
    </div>

    <div class="main-container">

        {{-- ================= RAPORT TAHFIDZ ================= --}}
        @if($raportTahfidz)
        @php
            $adab = $raportTahfidz->adab;
            $kelancaran = $raportTahfidz->kelancaran;
            $tajwid = $raportTahfidz->tajwid;
            $fashahah = $raportTahfidz->fashahah;
            $rataRata = ($adab + $kelancaran + $tajwid + $fashahah) / 4;

            function getPredikatPublic($nilai) {
                if ($nilai >= 90) return 'Mumtaz';
                if ($nilai >= 80) return 'Jayyid Jiddan';
                if ($nilai >= 70) return 'Jayyid';
                if ($nilai >= 60) return 'Maqbul';
                return 'Dhaif';
            }
            function getPredikatClass($nilai) {
                if ($nilai >= 90) return 'mumtaz';
                if ($nilai >= 80) return 'jayyid-jiddan';
                if ($nilai >= 70) return 'jayyid';
                return 'maqbul';
            }
        @endphp

        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-book"></i> Penilaian Tahfidz
            </div>

            <div class="score-grid">
                @foreach([
                    ['label' => 'Adab', 'value' => $adab],
                    ['label' => 'Kelancaran', 'value' => $kelancaran],
                    ['label' => 'Tajwid', 'value' => $tajwid],
                    ['label' => 'Fashahah', 'value' => $fashahah],
                ] as $item)
                <div class="score-item">
                    <div class="score-label">{{ $item['label'] }}</div>
                    <div class="score-value c-{{ getPredikatClass($item['value']) }}">{{ $item['value'] }}</div>
                    <span class="score-predikat bg-{{ getPredikatClass($item['value']) }}">
                        {{ getPredikatPublic($item['value']) }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="avg-card">
                <div class="avg-label">Rata-Rata Nilai</div>
                <div class="avg-value">{{ number_format($rataRata, 1) }}</div>
                <span class="avg-predikat">{{ getPredikatPublic($rataRata) }}</span>
            </div>
        </div>

        {{-- Identitas & Catatan Tahfidz --}}
        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-person-badge"></i> Informasi
            </div>
            <div class="info-row">
                <span class="info-label">Musyrif</span>
                <span class="info-value">{{ $raportTahfidz->musyrif }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Halaqoh</span>
                <span class="info-value">Tahfidz</span>
            </div>
            <div class="info-row">
                <span class="info-label">KKM</span>
                <span class="info-value">60</span>
            </div>
            @if($raportTahfidz->catatan)
            <div style="margin-top: 12px;">
                <div class="notes-box">
                    <strong>📝 Catatan Musyrif:</strong><br>
                    {{ $raportTahfidz->catatan }}
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-book"></i> Penilaian Tahfidz
            </div>
            <div class="empty-state">
                <i class="bi bi-journal-x"></i>
                <p>Data raport tahfidz belum tersedia.</p>
            </div>
        </div>
        @endif

        {{-- ================= RAPORT KMI ================= --}}
        @if($raportKmi)
        <div class="section-card">
            <div class="section-title">
                <i class="bi bi-mortarboard"></i> Penilaian KMI
            </div>

            {{-- Info KMI --}}
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">{{ $raportKmi->kelas }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Semester</span>
                <span class="info-value">{{ $raportKmi->semester }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tahun Pelajaran</span>
                <span class="info-value">{{ $raportKmi->tahun_pelajaran }}</span>
            </div>

            {{-- Nilai Mata Pelajaran --}}
            @if(!empty($raportKmi->nilai_mapel) && is_array($raportKmi->nilai_mapel))
            <div style="margin-top: 16px; overflow-x: auto;">
                <table class="kmi-table">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th style="text-align: center; width: 80px;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalNilai = 0; $countNilai = 0; @endphp
                        @foreach($raportKmi->nilai_mapel as $key => $mapel)
                        @php
                            $nilai = $mapel['p_a'] ?? $mapel['nilai'] ?? '-';
                            if (is_numeric($nilai)) { $totalNilai += $nilai; $countNilai++; }
                            $namaMapel = $mapel['nama'] ?? $mapel['mapel'] ?? ucwords(str_replace('_', ' ', $key));
                        @endphp
                        <tr>
                            <td>{{ $namaMapel }}</td>
                            <td style="text-align: center; font-weight: 700;">
                                @if(is_numeric($nilai))
                                <span class="c-{{ getPredikatClass($nilai) }}">{{ $nilai }}</span>
                                @else
                                {{ $nilai }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($countNilai > 0)
                        <tr style="background: #f8fafc;">
                            <td style="font-weight: 700;">Rata-Rata</td>
                            <td style="text-align: center; font-weight: 800; font-size: 1.1rem;">
                                @php $avgKmi = $totalNilai / $countNilai; @endphp
                                <span class="c-{{ getPredikatClass($avgKmi) }}">{{ number_format($avgKmi, 1) }}</span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Kehadiran --}}
            @if($raportKmi->sakit || $raportKmi->izin || $raportKmi->ghoib)
            <div style="margin-top: 16px;">
                <div class="section-title" style="margin-bottom: 10px;">
                    <i class="bi bi-calendar-check"></i> Kehadiran
                </div>
                <div class="score-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="score-item">
                        <div class="score-label">Sakit</div>
                        <div class="score-value" style="font-size: 1.3rem; color: #64748b;">{{ $raportKmi->sakit }}</div>
                    </div>
                    <div class="score-item">
                        <div class="score-label">Izin</div>
                        <div class="score-value" style="font-size: 1.3rem; color: #64748b;">{{ $raportKmi->izin }}</div>
                    </div>
                    <div class="score-item">
                        <div class="score-label">Ghoib</div>
                        <div class="score-value" style="font-size: 1.3rem; color: #dc2626;">{{ $raportKmi->ghoib }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Catatan Wali Kelas --}}
            @if($raportKmi->catatan_wali_kelas)
            <div style="margin-top: 16px;">
                <div class="notes-box">
                    <strong>📝 Catatan Wali Kelas:</strong><br>
                    {{ $raportKmi->catatan_wali_kelas }}
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="section-card">
            <div class="action-buttons">
                <button class="btn-action btn-print" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        {{-- Expired info --}}
        @if($raportToken->expires_at)
        <div style="text-align: center; padding: 10px; color: rgba(255,255,255,0.4); font-size: 0.75rem;">
            <i class="bi bi-clock"></i> Link berlaku hingga {{ $raportToken->expires_at->format('d M Y') }}
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Powered by <strong>MMQ Digital</strong> — Sistem Rapor Praktis</p>
        <p style="margin-top: 4px;">© {{ date('Y') }} PPTQ Makkah Madinatul Qur'an</p>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="theme-fab" onclick="toggleTheme()" id="themeFab" title="Toggle Dark/Light Mode">
        <i class="bi bi-moon-fill"></i>
    </button>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('mmq-theme', next);
            updateIcon(next);
        }
        function updateIcon(theme) {
            const btn = document.getElementById('themeFab');
            if (btn) btn.innerHTML = theme === 'dark'
                ? '<i class="bi bi-sun-fill"></i>'
                : '<i class="bi bi-moon-fill"></i>';
        }
        (function() {
            const saved = localStorage.getItem('mmq-theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
            updateIcon(saved);
        })();
    </script>

</body>
</html>
