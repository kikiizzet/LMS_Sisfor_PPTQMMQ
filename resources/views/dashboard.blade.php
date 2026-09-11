@extends('layout')

@section('main-content')
<style>
   

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(14,165,233,0.3); }
        50%       { box-shadow: 0 0 0 8px rgba(14,165,233,0); }
    }
    @keyframes floatIcon {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50%       { transform: translateY(-10px) rotate(3deg); }
    }

    .dash-section { animation: fadeSlideUp 0.5s ease-out forwards; }
    .dash-section:nth-child(1) { animation-delay: 0.0s; }
    .dash-section:nth-child(2) { animation-delay: 0.1s; }
    .dash-section:nth-child(3) { animation-delay: 0.2s; }
    .dash-section:nth-child(4) { animation-delay: 0.3s; }

    /* ── Hero ─────────────────────────────────────────────────── */
    .hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0e3a6e 50%, #0ea5e9 100%);
        border-radius: 24px;
        padding: 44px 48px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 340px; height: 340px;
        background: radial-gradient(circle, rgba(56,189,248,0.18) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -80px; left: 20%;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border-radius: 100px;
        padding: 6px 14px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        color: #bae6fd;
    }
    .hero-badge .dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #38bdf8;
        animation: pulseGlow 2s infinite;
    }
    .hero-title {
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 10px;
    }
    .hero-sub {
        opacity: 0.6;
        font-size: 0.92rem;
        font-weight: 400;
        margin-bottom: 28px;
        max-width: 480px;
    }
    .btn-hero {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 100px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s ease;
        text-decoration: none;
        border: 1.5px solid transparent;
    }
    .btn-hero-primary {
        background: rgba(255,255,255,0.95);
        color: #0f172a;
    }
    .btn-hero-primary:hover {
        background: #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        color: #0f172a;
    }
    .btn-hero-outline {
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.9);
        border-color: rgba(255,255,255,0.2);
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
        transform: translateY(-2px);
    }
    .btn-hero-warning {
        background: #f59e0b;
        color: #1c1917;
    }
    .btn-hero-warning:hover {
        background: #fbbf24;
        box-shadow: 0 8px 20px rgba(245,158,11,0.3);
        transform: translateY(-2px);
        color: #1c1917;
    }
    .hero-float-icon {
        font-size: 130px;
        opacity: 0.07;
        animation: floatIcon 6s ease-in-out infinite;
        line-height: 1;
    }

    /* ── Stat Cards ───────────────────────────────────────────── */
    .stat-card-premium {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 22px 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        cursor: pointer;
        min-width: 0;
        overflow: hidden;
        position: relative;
    }
    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        border-radius: 20px 0 0 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .stat-card-premium:hover { 
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.07);
        border-color: transparent;
    }
    .stat-card-premium:hover::before { opacity: 1; }

    .stat-card-primary::before   { background: #0ea5e9; }
    .stat-card-success::before   { background: #10b981; }
    .stat-card-info::before      { background: #6366f1; }
    .stat-card-warning::before   { background: #f59e0b; }
    .stat-card-danger::before    { background: #ef4444; }

    .stat-card-primary:hover  { border-color: rgba(14,165,233,0.2); box-shadow: 0 20px 40px rgba(14,165,233,0.07); }
    .stat-card-success:hover  { border-color: rgba(16,185,129,0.2); box-shadow: 0 20px 40px rgba(16,185,129,0.07); }
    .stat-card-info:hover     { border-color: rgba(99,102,241,0.2); box-shadow: 0 20px 40px rgba(99,102,241,0.07); }
    .stat-card-warning:hover  { border-color: rgba(245,158,11,0.2); box-shadow: 0 20px 40px rgba(245,158,11,0.07); }
    .stat-card-danger:hover   { border-color: rgba(239,68,68,0.2);  box-shadow: 0 20px 40px rgba(239,68,68,0.07); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 14px;
        flex-shrink: 0;
    }
    .stat-icon-primary   { background: #e0f2fe; color: #0284c7; }
    .stat-icon-success   { background: #d1fae5; color: #059669; }
    .stat-icon-info      { background: #ede9fe; color: #7c3aed; }
    .stat-icon-warning   { background: #fef3c7; color: #d97706; }
    .stat-icon-danger    { background: #fee2e2; color: #dc2626; }

    .stat-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }
    .stat-value-primary  { color: #0369a1; }
    .stat-value-success  { color: #047857; }
    .stat-value-info     { color: #5b21b6; }
    .stat-value-warning  { color: #b45309; }
    .stat-value-danger   { color: #b91c1c; }

    /* ── Section Card (Charts, Tables) ─────────────────────────── */
    .section-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 24px;
        height: 100%;
        min-width: 0;
        overflow: hidden;
    }
    .section-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f8fafc;
    }
    .section-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-card-title .title-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #0ea5e9;
        flex-shrink: 0;
    }
    .section-card-badge {
        font-size: 0.68rem;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 100px;
        padding: 4px 12px;
        letter-spacing: 0.3px;
    }

    /* ── Leaderboard ─────────────────────────────────────────── */
    .lb-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .lb-item:hover {
        background: #e0f2fe;
        border-color: #bae6fd;
        transform: translateX(4px);
        color: inherit;
    }
    .lb-rank {
        width: 28px; height: 28px;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .lb-rank-gold   { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; box-shadow: 0 4px 8px rgba(245,158,11,0.3); }
    .lb-rank-silver { background: linear-gradient(135deg, #94a3b8, #64748b); color: #fff; }
    .lb-rank-bronze { background: linear-gradient(135deg, #c47c3b, #a16207); color: #fff; }
    .lb-rank-plain  { background: #e2e8f0; color: #64748b; }

    /* ── Activity Table ─────────────────────────────────────────── */
    .act-table { width: 100%; border-collapse: collapse; }
    .act-table thead th {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        padding: 0 0 12px;
        border: none;
    }
    .act-table tbody tr {
        border-top: 1px solid #f8fafc;
        transition: background 0.15s;
    }
    .act-table tbody tr:hover { background: #f8fafc; }
    .act-table tbody td {
        padding: 11px 0;
        vertical-align: middle;
        font-size: 0.8rem;
        border: none;
    }
    .act-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
        flex-shrink: 0;
    }

    /* ── Dark Modes ───────────────────────────────────────────── */
    [data-theme="dark"] .hero-banner {
        background: linear-gradient(135deg, #060d1e 0%, #0c2d54 50%, #0369a1 100%);
    }
    [data-theme="dark"] .stat-card-premium {
        background: #1e293b;
        border-color: #334155;
    }
    [data-theme="dark"] .stat-card-premium:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
    [data-theme="dark"] .stat-icon-primary  { background: #0c2d54; color: #38bdf8; }
    [data-theme="dark"] .stat-icon-success  { background: #052e16; color: #34d399; }
    [data-theme="dark"] .stat-icon-info     { background: #2e1065; color: #a78bfa; }
    [data-theme="dark"] .stat-icon-warning  { background: #451a03; color: #fbbf24; }
    [data-theme="dark"] .stat-icon-danger   { background: #450a0a; color: #f87171; }
    [data-theme="dark"] .stat-value-primary { color: #38bdf8; }
    [data-theme="dark"] .stat-value-success { color: #34d399; }
    [data-theme="dark"] .stat-value-info    { color: #a78bfa; }
    [data-theme="dark"] .stat-value-warning { color: #fbbf24; }
    [data-theme="dark"] .stat-value-danger  { color: #f87171; }
    [data-theme="dark"] .section-card {
        background: #1e293b;
        border-color: #334155;
    }
    [data-theme="dark"] .section-card-header { border-bottom-color: #334155; }
    [data-theme="dark"] .section-card-title  { color: #f1f5f9; }
    [data-theme="dark"] .section-card-badge  { background: #334155; color: #94a3b8; }
    [data-theme="dark"] .lb-item { background: #334155; border-color: #475569; color: #e2e8f0; }
    [data-theme="dark"] .lb-item:hover { background: #1e3a5f; border-color: #38bdf8; color: #e2e8f0; }
    [data-theme="dark"] .act-table tbody tr { border-top-color: #334155; }
    [data-theme="dark"] .act-table tbody tr:hover { background: #334155; }
    [data-theme="dark"] .act-table tbody td,
    [data-theme="dark"] .act-table thead th { color: #94a3b8; }

    @media (max-width: 768px) {
        .hero-banner { padding: 28px 24px; }
        .hero-title { font-size: 1.5rem; }
        .hero-float-icon { display: none; }
    }
</style>

<div class="container-fluid px-0">

    <div class="dash-section mb-4">
        <div class="hero-banner">
            <div class="row align-items-center">
                <div class="col-lg-8">
                   
                    <h1 class="hero-title">Pusat Komando<br>MMQ Digital</h1>
                    <p class="hero-sub">Pantau seluruh perkembangan akademik santri dalam satu tampilan cerdas dan terintegrasi.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/input" class="btn-hero btn-hero-primary">
                           </i> Input Tahfidz
                        </a>
                        <a href="{{ route('raport-kmi.create') }}" class="btn-hero btn-hero-outline">
                            </i> Input KMI
                        </a>
                        <a href="{{ route('raport-kmi.grid') }}" class="btn-hero btn-hero-warning">
                            </i> Smart Grid
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="dash-section mb-4">
        <div class="row g-3">
            {{-- Total Santri --}}
            <div class="col-6 col-xl">
                <div class="stat-card-premium stat-card-primary" onclick="window.location='/daftar'">
                   
                    <div class="stat-label">Total Santri</div>
                    <div class="stat-value stat-value-primary">{{ $totalSantriGlobal }}</div>
                    <div class="d-flex flex-wrap gap-1 mt-1">
                        <span class="badge rounded-pill" style="background:#e0f2fe;color:#0284c7;font-size:0.62rem;font-weight:600;">{{ $totalKmi }} KMI</span>
                        <span class="badge rounded-pill" style="background:#e0f2fe;color:#0ea5e9;font-size:0.62rem;font-weight:600;">{{ $totalSantriTahfidz }} Tahfidz</span>
                    </div>
                </div>
            </div>

            {{-- Mumtaz --}}
            <div class="col-6 col-xl">
                <div class="stat-card-premium stat-card-success" onclick="showDetail('Mumtaz')">
                    <div class="stat-label">Mumtaz</div>
                    <div class="stat-value stat-value-success">{{ $mumtaz }}</div>
                    <div class="progress mt-2" style="height:5px;border-radius:10px;background:#d1fae5;">
                        <div class="progress-bar" style="width:{{ $totalSantriGlobal > 0 ? ($mumtaz/$totalSantriGlobal)*100 : 0 }}%;background:#10b981;border-radius:10px;"></div>
                    </div>
                </div>
            </div>

            {{-- Jayyid Jiddan --}}
            <div class="col-6 col-xl">
                <div class="stat-card-premium stat-card-info" onclick="showDetail('Jayyid Jiddan')">
                    <div class="stat-label">Jayyid Jiddan</div>
                    <div class="stat-value stat-value-info">{{ $jayyidJiddan }}</div>
                    <div class="stat-label mt-1" style="font-size:0.66rem;color:#7c3aed;">Sangat Baik</div>
                </div>
            </div>

            {{-- Jayyid --}}
            <div class="col-6 col-xl">
                <div class="stat-card-premium stat-card-warning" onclick="showDetail('Jayyid')">
                    <div class="stat-label">Jayyid</div>
                    <div class="stat-value stat-value-warning">{{ $jayyid }}</div>
                    <div class="stat-label mt-1" style="font-size:0.66rem;color:#d97706;">Cukup Baik</div>
                </div>
            </div>

            {{-- Maqbul --}}
            <div class="col-12 col-xl">
                <div class="stat-card-premium stat-card-danger" onclick="showDetail('Maqbul')">
                  
                    <div class="stat-label">Maqbul</div>
                    <div class="stat-value stat-value-danger">{{ $maqbul }}</div>
                    <div class="stat-label mt-1" style="font-size:0.66rem;color:#dc2626;">Bimbingan Khusus</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECTION 3 — CHARTS
    ============================================================ --}}
    <div class="dash-section mb-4">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5 class="section-card-title">
                            <span class="title-dot"></span>
                            Statistik Capaian Musyrif (Tahfidz)
                        </h5>
                        <span class="section-card-badge">Per Ustadz</span>
                    </div>
                    <div id="musyrifBarChart" style="height:320px;"></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="section-card">
                    <div class="section-card-header">
                        <h5 class="section-card-title">
                            <span class="title-dot" style="background:#10b981;"></span>
                            Sebaran Predikat
                        </h5>
                    </div>
                    <div id="predikatDonutChart" style="height:320px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECTION 4 — LEADERBOARD & ACTIVITY
    ============================================================ --}}
    <div class="dash-section mb-4">
        <div class="row g-3">
            {{-- Leaderboard --}}
            <div class="col-xl-4">
                <div class="section-card h-100">
                    <div class="section-card-header">
                        <h5 class="section-card-title">
                            <span class="title-dot" style="background:#f59e0b;"></span>
                            Top 5 Integrated
                        </h5>
                        <a href="{{ route('rekapitulasi') }}" class="section-card-badge text-decoration-none" style="color:#0ea5e9;">
                            Lihat Semua →
                        </a>
                    </div>
                    <div>
                        @forelse($topFiveGlobal as $s)
                        <div class="lb-item">
                            <div class="lb-rank {{ $loop->index === 0 ? 'lb-rank-gold' : ($loop->index === 1 ? 'lb-rank-silver' : ($loop->index === 2 ? 'lb-rank-bronze' : 'lb-rank-plain')) }}">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-bold text-truncate" style="font-size:0.82rem;letter-spacing:0.3px;">{{ strtoupper($s->nama) }}</div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <span class="fw-bold" style="font-size:0.9rem;color:#0284c7;">{{ number_format($s->total, 2) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted" style="font-size:0.82rem;">
                            <i class="bi bi-inbox fs-3 d-block mb-2 opacity-30"></i>
                            Belum ada data nilai.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Riwayat Tahfidz --}}
            <div class="col-xl-4 col-md-6">
                <div class="section-card h-100">
                    <div class="section-card-header">
                        <h5 class="section-card-title">
                            <span class="title-dot" style="background:#6366f1;"></span>
                            Riwayat Tahfidz
                        </h5>
                        <span class="section-card-badge">Terbaru</span>
                    </div>
                    <table class="act-table">
                        <thead>
                            <tr>
                                <th>Santri</th>
                                <th>Musyrif</th>
                                <th class="text-end">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $r)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="act-avatar">{{ strtoupper(substr($r->nama_santri, 0, 1)) }}</div>
                                        <span class="fw-semibold text-truncate" style="max-width:90px;font-size:0.78rem;">{{ Str::limit($r->nama_santri, 14) }}</span>
                                    </div>
                                </td>
                                <td class="text-muted" style="font-size:0.72rem;">{{ Str::limit($r->musyrif, 12) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('raport.cetak', $r->id) }}" class="btn btn-sm" style="background:#e0f2fe;color:#0284c7;border-radius:8px;padding:4px 10px;font-size:0.7rem;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat KMI --}}
            <div class="col-xl-4 col-md-6">
                <div class="section-card h-100">
                    <div class="section-card-header">
                        <h5 class="section-card-title">
                            <span class="title-dot" style="background:#10b981;"></span>
                            Riwayat KMI
                        </h5>
                        <span class="section-card-badge">Terbaru</span>
                    </div>
                    <table class="act-table">
                        <thead>
                            <tr>
                                <th>Santri</th>
                                <th>Kelas</th>
                                <th class="text-end">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatKmi as $rk)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="act-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">{{ strtoupper(substr($rk->nama_santri, 0, 1)) }}</div>
                                        <span class="fw-semibold text-truncate" style="max-width:90px;font-size:0.78rem;">{{ Str::limit($rk->nama_santri, 14) }}</span>
                                    </div>
                                </td>
                                <td class="text-muted" style="font-size:0.72rem;">{{ $rk->kelas }}</td>
                                <td class="text-end">
                                    <a href="{{ route('raport-kmi.cetak', $rk->id) }}" class="btn btn-sm" style="background:#d1fae5;color:#059669;border-radius:8px;padding:4px 10px;font-size:0.7rem;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Modal Detail Santri ───────────────────────────────────────── --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0" style="border-radius:20px;box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h6 class="modal-title fw-bold" id="modalTitle">Daftar Santri</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div id="santriList" style="max-height:400px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const dataIntegrated = @json($rekap);

    function showDetail(predikat) {
        const listContainer = document.getElementById('santriList');
        const titleModal    = document.getElementById('modalTitle');
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        const colorMap = { Mumtaz: '#10b981', 'Jayyid Jiddan': '#6366f1', Jayyid: '#f59e0b', Maqbul: '#ef4444' };
        const accent = colorMap[predikat] || '#0ea5e9';
        titleModal.innerHTML = `<span style="color:${accent}">●</span> Santri ${predikat}`;
        listContainer.innerHTML = '';
        let filtered = dataIntegrated.filter(item => {
            const val = parseFloat(item.total);
            if (predikat === 'Mumtaz')        return val >= 90;
            if (predikat === 'Jayyid Jiddan') return val >= 80 && val < 90;
            if (predikat === 'Jayyid')        return val >= 70 && val < 80;
            if (predikat === 'Maqbul')        return val < 70;
            return false;
        }).sort((a, b) => b.total - a.total);

        if (filtered.length > 0) {
            filtered.forEach(item => {
                listContainer.innerHTML += `
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:12px;background:#f8fafc;margin-bottom:8px;border:1px solid #f1f5f9;">
                        <div style="width:34px;height:34px;border-radius:50%;background:${accent};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.82rem;flex-shrink:0;">
                            ${item.nama.charAt(0).toUpperCase()}
                        </div>
                        <div style="flex:1;overflow:hidden;">
                            <div style="font-weight:700;font-size:0.82rem;text-transform:uppercase;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.nama}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;">Nilai: <b style="color:${accent}">${parseFloat(item.total).toFixed(2)}</b></div>
                        </div>
                    </div>`;
            });
        } else {
            listContainer.innerHTML = `<p class="text-center text-muted my-4 small"><i class="bi bi-inbox fs-4 d-block mb-2 opacity-30"></i>Tidak ada data.</p>`;
        }
        modal.show();
    }

    // ApexCharts helpers
    function isDark()      { return document.documentElement.getAttribute('data-theme') === 'dark'; }
    function chartFg()     { return isDark() ? '#94a3b8' : '#64748b'; }
    function chartGrid()   { return isDark() ? '#334155' : '#f1f5f9'; }
    function chartBg()     { return isDark() ? '#1e293b'  : '#ffffff'; }

    // ── Donut: Sebaran Predikat ──────────────────────────────────
    const optPredikat = {
        series: [{{ $mumtaz }}, {{ $jayyidJiddan }}, {{ $jayyid }}, {{ $maqbul }}],
        chart: {
            type: 'donut', height: 300,
            background: 'transparent',
            foreColor: chartFg(),
            toolbar: { show: false },
            redrawOnParentResize: true,
        },
        labels: ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul'],
        colors: ['#10b981', '#6366f1', '#f59e0b', '#ef4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'TOTAL', fontSize: '11px', fontWeight: 700, color: chartFg() }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: { colors: chartFg() },
            markers: { width: 8, height: 8, radius: 4 },
        },
        stroke: { show: false },
        tooltip: { theme: isDark() ? 'dark' : 'light' },
    };
    const chartPredikat = new ApexCharts(document.querySelector('#predikatDonutChart'), optPredikat);
    chartPredikat.render();

    // ── Bar: Statistik Musyrif ───────────────────────────────────
    const dataKorelasi = @json($korelasiMusyrif);
    const optMusyrif = {
        series: [
            { name: 'Mumtaz',      data: dataKorelasi.map(d => d.mumtaz) },
            { name: 'J. Jiddan',   data: dataKorelasi.map(d => d.jayyidJiddan) },
            { name: 'Jayyid',      data: dataKorelasi.map(d => d.jayyid) },
            { name: 'Maqbul',      data: dataKorelasi.map(d => d.maqbul) },
        ],
        chart: {
            type: 'bar', height: 300,
            stacked: true,
            background: 'transparent',
            foreColor: chartFg(),
            toolbar: { show: false },
            redrawOnParentResize: true,
        },
        colors: ['#10b981', '#6366f1', '#f59e0b', '#ef4444'],
        xaxis: {
            categories: dataKorelasi.map(d => d.nama),
            labels: { style: { colors: chartFg(), fontSize: '11px' } },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: { labels: { style: { colors: chartFg() } } },
        legend: {
            position: 'top', horizontalAlign: 'right',
            fontSize: '12px',
            labels: { colors: chartFg() },
            markers: { width: 8, height: 8, radius: 4 },
        },
        plotOptions: {
            bar: { borderRadius: 5, columnWidth: '42%', borderRadiusApplication: 'end', borderRadiusWhenStacked: 'last' }
        },
        fill: { opacity: 1 },
        grid: { borderColor: chartGrid(), strokeDashArray: 4, padding: { left: 4, right: 4 } },
        tooltip: { theme: isDark() ? 'dark' : 'light' },
    };
    const chartMusyrif = new ApexCharts(document.querySelector('#musyrifBarChart'), optMusyrif);
    chartMusyrif.render();

    // Theme observer — update charts on theme change
    new MutationObserver(() => {
        const fg = chartFg(), gc = chartGrid(), tt = isDark() ? 'dark' : 'light';
        chartPredikat.updateOptions({
            chart: { foreColor: fg },
            legend: { labels: { colors: fg } },
            plotOptions: { pie: { donut: { labels: { total: { color: fg } } } } },
            tooltip: { theme: tt }
        });
        chartMusyrif.updateOptions({
            chart: { foreColor: fg },
            xaxis: { labels: { style: { colors: fg } } },
            yaxis: { labels: { style: { colors: fg } } },
            legend: { labels: { colors: fg } },
            grid: { borderColor: gc },
            tooltip: { theme: tt }
        });
    }).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
</script>
@endsection
