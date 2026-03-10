@extends('layout')

@section('main-content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.3);
        --primary-soft: #e0f2fe;
        --success-soft: #dcfce7;
        --warning-soft: #fef3c7;
        --danger-soft: #fee2e2;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: fadeIn 0.5s ease-out forwards; }

    /* Hero Section */
    .hero-glass {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.15);
    }
    
    @media (max-width: 768px) {
        .hero-glass {
            padding: 24px;
            border-radius: 20px;
        }
    }

    .hero-glass::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Stats Cards */
    .stat-card-premium {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        cursor: pointer;
        /* Ensure charts inside don't overflow */
        min-width: 0;
        overflow: hidden;
    }

    .stat-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
        border-color: #0ea5e9;
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    /* Quick Action Buttons */
    .btn-quick {
        border-radius: 16px;
        padding: 12px 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s;
        border: 1px solid transparent;
    }

    .btn-quick:hover {
        transform: scale(1.03);
    }

    /* Table & List Styling */
    .leaderboard-item {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 8px;
        background: #fff;
        border: 1px solid #f1f5f9;
        transition: 0.2s;
    }

    .leaderboard-item:hover {
        background: var(--primary-soft);
        transform: translateX(5px);
    }

    /* Dashboard Dark Mode Overrides */
    [data-theme="dark"] .stat-card-premium {
        --glass-bg: rgba(30, 41, 59, 0.95);
        --glass-border: rgba(71, 85, 105, 0.5);
    }
    [data-theme="dark"] .leaderboard-item {
        background: #334155;
        border-color: #475569;
    }
    [data-theme="dark"] .leaderboard-item:hover {
        background: #3b4f6b;
    }
    [data-theme="dark"] .hero-glass {
        background: linear-gradient(135deg, #0c4a6e 0%, #1e3a5f 100%);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    [data-theme="dark"] .badge.bg-light {
        background: #334155 !important;
        color: #94a3b8 !important;
    }
</style>

<div class="container-fluid animate-in">
    <!-- Section 1: Hero Command Center -->
    <div class="hero-glass">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <h1 class="fw-bold mb-3" style="font-size: clamp(1.5rem, 5vw, 2.5rem);">Pusat Komando MMQ Digital</h1>
                <p class="opacity-75 fs-6 mb-4">Pantau seluruh perkembangan akademik santri dalam satu tampilan cerdas.</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
                    <a href="/input" class="btn btn-white text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i> Input Tahfidz
                    </a>
                    <a href="{{ route('raport-kmi.create') }}" class="btn btn-outline-white text-white fw-bold px-4 py-2 rounded-pill border-2">
                        <i class="bi bi-journal-plus me-2"></i> Input KMI
                    </a>
                    <a href="{{ route('raport-kmi.grid') }}" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Smart Grid
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="bi bi-layers-half" style="font-size: 120px; opacity: 0.15;"></i>
            </div>
        </div>
    </div>

    <!-- Section 2: Integrated Statistics -->
    <div class="row g-3 g-xl-4 mb-4 mb-lg-5">
        <div class="col-sm-6 col-xl">
            <div class="stat-card-premium" onclick="window.location='/daftar'">
                <div class="stat-icon bg-primary text-white shadow-sm">
                    <i class="bi bi-people"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.65rem;">Total Santri</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $totalSantriGlobal }}</h2>
                <div class="mt-2 flex-wrap d-flex gap-1">
                    <span class="badge bg-primary-subtle text-primary" style="font-size: 0.6rem;">{{ $totalKmi }} KMI</span>
                    <span class="badge bg-info-subtle text-info" style="font-size: 0.6rem;">{{ $totalSantriTahfidz }} Tahfidz</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl">
            <div class="stat-card-premium" onclick="showDetail('Mumtaz')">
                <div class="stat-icon bg-success text-white shadow-sm">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.65rem;">Mumtaz</h6>
                <h2 class="fw-bold mb-0 text-success">{{ $mumtaz }}</h2>
                <div class="progress mt-2" style="height: 6px; border-radius: 10px;">
                    <div class="progress-bar bg-success" style="width: {{ $totalSantriGlobal > 0 ? ($mumtaz/$totalSantriGlobal)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl">
            <div class="stat-card-premium" onclick="showDetail('Jayyid Jiddan')">
                <div class="stat-icon bg-info text-white shadow-sm">
                    <i class="bi bi-star-fill"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.65rem;">Jayyid Jiddan</h6>
                <h2 class="fw-bold mb-0 text-info">{{ $jayyidJiddan }}</h2>
                <p class="small text-muted mb-0 mt-2" style="font-size: 0.7rem;">Sangat Baik</p>
            </div>
        </div>
        <div class="col-sm-6 col-xl">
            <div class="stat-card-premium" onclick="showDetail('Jayyid')">
                <div class="stat-icon bg-warning text-white shadow-sm">
                    <i class="bi bi-star"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.65rem;">Jayyid</h6>
                <h2 class="fw-bold mb-0 text-warning">{{ $jayyid }}</h2>
                <p class="small text-muted mb-0 mt-2" style="font-size: 0.7rem;">Cukup Baik</p>
            </div>
        </div>
        <div class="col-sm-12 col-xl">
            <div class="stat-card-premium" onclick="showDetail('Maqbul')">
                <div class="stat-icon bg-danger text-white shadow-sm">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h6 class="text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.65rem;">Maqbul</h6>
                <h2 class="fw-bold mb-0 text-danger">{{ $maqbul }}</h2>
                <p class="small text-muted mb-0 mt-2" style="font-size: 0.7rem;">Bimbingan Khusus</p>
            </div>
        </div>
    </div>

    <!-- Section 3: Performance & Distribution -->
    <div class="row g-4 mb-4">
        <!-- Performance Chart: Bar -->
        <div class="col-xl-8">
            <div class="stat-card-premium h-100" style="cursor: default !important;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Statistik Capaian Musyrif (Tahfidz)</h5>
                    <div class="badge bg-light text-dark border shadow-xs">Data Per-Ustadz</div>
                </div>
                <div id="musyrifBarChart" style="height: 350px;"></div>
            </div>
        </div>

        <!-- Distribution Chart: Donut -->
        <div class="col-xl-4">
            <div class="stat-card-premium h-100" style="cursor: default !important;">
                <h5 class="fw-bold mb-4">Sebaran Predikat</h5>
                <div id="predikatDonutChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Section 4: Leaderboard & Activity Logs -->
    <div class="row g-4 mb-5">
        <!-- Global Leaderboard -->
        <div class="col-xl-4">
            <div class="stat-card-premium h-100" style="cursor: default !important;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">🏆 Top 5 Integrated</h5>
                    <a href="{{ route('rekapitulasi') }}" class="small text-decoration-none">Detail →</a>
                </div>
                <div>
                    @forelse($topFiveGlobal as $s)
                    <div class="leaderboard-item d-flex align-items-center">
                        <div class="me-3">
                            <span class="badge rounded-circle {{ $loop->index < 3 ? 'bg-warning text-dark' : 'bg-light text-muted' }}" style="width: 28px; height: 28px; line-height: 18px;">{{ $loop->iteration }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold small text-uppercase">{{ $s->nama }}</h6>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ number_format($s->total, 2) }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted small">Belum ada data nilai terkumpul.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stat-card-premium" style="cursor: default !important;">
                <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Tahfidz</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead><tr class="text-muted"><th style="font-size: 0.65rem;">SANTRI</th><th style="font-size: 0.65rem;">MUSYRIF</th><th class="text-end">🖨️</th></tr></thead>
                        <tbody>
                            @foreach($riwayat as $r)
                            <tr>
                                <td class="fw-bold text-dark text-uppercase" style="font-size: 0.75rem;">{{ Str::limit($r->nama_santri, 15) }}</td>
                                <td class="text-muted small" style="font-size: 0.65rem;">{{ Str::limit($r->musyrif, 12) }}</td>
                                <td class="text-end"><a href="{{ route('raport.cetak', $r->id) }}" class="text-primary"><i class="bi bi-printer"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stat-card-premium" style="cursor: default !important;">
                <h5 class="fw-bold mb-3"><i class="bi bi-journal-text me-2 text-success"></i>Riwayat KMI</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead><tr class="text-muted"><th style="font-size: 0.65rem;">SANTRI</th><th style="font-size: 0.65rem;">KELAS</th><th class="text-end">🖨️</th></tr></thead>
                        <tbody>
                            @foreach($riwayatKmi as $rk)
                            <tr>
                                <td class="fw-bold text-dark text-uppercase" style="font-size: 0.75rem;">{{ Str::limit($rk->nama_santri, 15) }}</td>
                                <td class="text-muted small" style="font-size: 0.65rem;">{{ $rk->kelas }}</td>
                                <td class="text-end"><a href="{{ route('raport-kmi.cetak', $rk->id) }}" class="text-success"><i class="bi bi-printer"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Santri -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="modalTitle">Daftar Santri</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="santriList" class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    {{-- Konten diisi via JS --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const dataIntegrated = @json($rekap);

    function showDetail(predikat) {
        const listContainer = document.getElementById('santriList');
        const titleModal = document.getElementById('modalTitle');
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        
        titleModal.innerText = "Santri " + predikat;
        listContainer.innerHTML = ""; 

        let filtered = dataIntegrated.filter(item => {
            const val = parseFloat(item.total);
            if (predikat === 'Mumtaz') return val >= 90;
            if (predikat === 'Jayyid Jiddan') return val >= 80 && val < 90;
            if (predikat === 'Jayyid') return val >= 70 && val < 80;
            if (predikat === 'Maqbul') return val < 70;
            return false;
        });

        filtered.sort((a, b) => b.total - a.total);

        if (filtered.length > 0) {
            filtered.forEach(item => {
                listContainer.innerHTML += `
                    <div class="list-group-item d-flex align-items-center border-0 px-0 mb-2 bg-light rounded-3 p-3">
                        <div class="avatar-sm me-3 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem;">
                            ${item.nama.charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark text-uppercase" style="font-size: 0.8rem;">${item.nama}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Nilai: ${parseFloat(item.total).toFixed(2)}</div>
                        </div>
                    </div>
                `;
            });
        } else {
            listContainer.innerHTML = `<p class="text-center text-muted my-3 small">Tidak ada data untuk kategori ini.</p>`;
        }
        
        modal.show();
    }

    // Dark mode helpers for ApexCharts
    function isDark() { return document.documentElement.getAttribute('data-theme') === 'dark'; }
    function chartForeColor() { return isDark() ? '#94a3b8' : '#666'; }
    function chartGridColor() { return isDark() ? '#334155' : '#f1f1f1'; }

    // ApexCharts: Sebaran Predikat
    const optionsPredikat = {
        series: [{{ $mumtaz }}, {{ $jayyidJiddan }}, {{ $jayyid }}, {{ $maqbul }}],
        chart: { type: 'donut', height: 320, width: '100%', redrawOnParentResize: true, foreColor: chartForeColor(), background: 'transparent' },
        labels: ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul'],
        colors: ['#10b981', '#0ea5e9', '#f59e0b', '#ef4444'],
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'TOTAL', fontSize: '10px', color: chartForeColor() } } } } },
        dataLabels: { enabled: false },
        legend: { position: 'bottom', labels: { colors: chartForeColor() } },
        stroke: { show: false },
        tooltip: { theme: isDark() ? 'dark' : 'light' }
    };
    const chartPredikat = new ApexCharts(document.querySelector("#predikatDonutChart"), optionsPredikat);
    chartPredikat.render();

    // ApexCharts: Korelasi Musyrif
    const dataKorelasi = @json($korelasiMusyrif); 
    const optionsMusyrif = {
        series: [
            { name: 'Mumtaz', data: dataKorelasi.map(d => d.mumtaz) },
            { name: 'J. Jiddan', data: dataKorelasi.map(d => d.jayyidJiddan) },
            { name: 'Jayyid', data: dataKorelasi.map(d => d.jayyid) },
            { name: 'Maqbul', data: dataKorelasi.map(d => d.maqbul) }
        ],
        chart: { type: 'bar', height: 350, width: '100%', stacked: true, toolbar: { show: false }, redrawOnParentResize: true, foreColor: chartForeColor(), background: 'transparent' },
        colors: ['#10b981', '#0ea5e9', '#f59e0b', '#ef4444'],
        xaxis: { categories: dataKorelasi.map(d => d.nama), labels: { style: { colors: chartForeColor() } } },
        yaxis: { labels: { style: { colors: chartForeColor() } } },
        legend: { position: 'top', horizontalAlign: 'right', labels: { colors: chartForeColor() } },
        fill: { opacity: 1 },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '40%' } },
        grid: { borderColor: chartGridColor(), strokeDashArray: 4 },
        tooltip: { theme: isDark() ? 'dark' : 'light' }
    };
    const chartMusyrif = new ApexCharts(document.querySelector("#musyrifBarChart"), optionsMusyrif);
    chartMusyrif.render();

    // Listen for theme changes and update charts
    const observer = new MutationObserver(function() {
        const fc = chartForeColor();
        const gc = chartGridColor();
        const tt = isDark() ? 'dark' : 'light';
        chartPredikat.updateOptions({
            chart: { foreColor: fc },
            legend: { labels: { colors: fc } },
            tooltip: { theme: tt }
        });
        chartMusyrif.updateOptions({
            chart: { foreColor: fc },
            xaxis: { labels: { style: { colors: fc } } },
            yaxis: { labels: { style: { colors: fc } } },
            legend: { labels: { colors: fc } },
            grid: { borderColor: gc },
            tooltip: { theme: tt }
        });
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
</script>
@endsection
