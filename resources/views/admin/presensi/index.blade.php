@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
        width: 100%;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg, rgba(255, 255, 255, 0.95));
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }
    .stat-card .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-top: 8px;
    }

    .stat-hadir { background: linear-gradient(135deg, #dcfce7, #bbf7d0); }
    .stat-hadir::before { background: #22c55e; }
    .stat-hadir .stat-number { color: #166534; }
    .stat-hadir .stat-label { color: #15803d; }

    .stat-sakit { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .stat-sakit::before { background: #f59e0b; }
    .stat-sakit .stat-number { color: #92400e; }
    .stat-sakit .stat-label { color: #a16207; }

    .stat-izin { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
    .stat-izin::before { background: #3b82f6; }
    .stat-izin .stat-number { color: #1e40af; }
    .stat-izin .stat-label { color: #1d4ed8; }

    .stat-alfa { background: linear-gradient(135deg, #fee2e2, #fecaca); }
    .stat-alfa::before { background: #ef4444; }
    .stat-alfa .stat-number { color: #991b1b; }
    .stat-alfa .stat-label { color: #b91c1c; }

    .stat-belum { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); }
    .stat-belum::before { background: #94a3b8; }
    .stat-belum .stat-number { color: #475569; }
    .stat-belum .stat-label { color: #64748b; }

    /* Table */
    .table-premium thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 16px 12px;
        border-bottom: 2px solid #f8f9fa;
    }
    .table-premium tbody td {
        padding: 12px;
        vertical-align: middle;
    }

    /* Status Buttons */
    .status-btn-group {
        display: flex;
        gap: 4px;
    }
    .status-btn {
        width: 40px;
        height: 36px;
        border-radius: 10px;
        border: 2px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 700;
        transition: all 0.2s ease;
        background: #f1f5f9;
        color: #64748b;
    }
    .status-btn:hover {
        transform: scale(1.1);
    }
    .status-btn.active-hadir {
        background: #22c55e;
        color: white;
        border-color: #16a34a;
        box-shadow: 0 3px 10px rgba(34, 197, 94, 0.3);
    }
    .status-btn.active-sakit {
        background: #f59e0b;
        color: white;
        border-color: #d97706;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }
    .status-btn.active-izin {
        background: #3b82f6;
        color: white;
        border-color: #2563eb;
        box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
    }
    .status-btn.active-alfa {
        background: #ef4444;
        color: white;
        border-color: #dc2626;
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
    }

    .badge-kelas {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .keterangan-input {
        width: 100%;
        max-width: 150px;
        font-size: 0.8rem;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        padding: 6px 10px;
        transition: border-color 0.2s;
    }
    .keterangan-input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    /* Quick Action */
    .quick-action-bar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .quick-btn {
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .quick-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .quick-btn-hadir { background: #dcfce7; color: #166534; }
    .quick-btn-hadir:hover { background: #22c55e; color: white; }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-clipboard-check me-2 text-primary"></i>Data Presensi</h1>
                <p>Catat kehadiran santri harian</p>
            </div>
            <a href="{{ route('admin.presensi.rekap') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-bar-chart me-2"></i>Lihat Rekap Bulanan
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.presensi.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-calendar-day me-1"></i>Tanggal
                        </label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-school me-1"></i>Filter Kelas
                        </label>
                        <select name="kelas_id" class="form-select" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 600; padding: 10px;">
                            <i class="fas fa-filter me-1"></i>Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <div class="col">
                <div class="stat-card stat-hadir">
                    <div class="stat-number">{{ $totalHadir }}</div>
                    <div class="stat-label"><i class="fas fa-check-circle me-1"></i>Hadir</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card stat-sakit">
                    <div class="stat-number">{{ $totalSakit }}</div>
                    <div class="stat-label"><i class="fas fa-thermometer me-1"></i>Sakit</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card stat-izin">
                    <div class="stat-number">{{ $totalIzin }}</div>
                    <div class="stat-label"><i class="fas fa-envelope me-1"></i>Izin</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card stat-alfa">
                    <div class="stat-number">{{ $totalAlfa }}</div>
                    <div class="stat-label"><i class="fas fa-times-circle me-1"></i>Alfa</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card stat-belum">
                    <div class="stat-number">{{ $totalBelum }}</div>
                    <div class="stat-label"><i class="fas fa-clock me-1"></i>Belum</div>
                </div>
            </div>
        </div>

        <!-- Presensi Form -->
        <div class="card card-main">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-list-check me-2 text-primary"></i>
                        Daftar Santri — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </h5>
                    <div class="quick-action-bar">
                        <button type="button" class="quick-btn quick-btn-hadir" onclick="setAllStatus('Hadir')">
                            <i class="fas fa-check-double me-1"></i>Semua Hadir
                        </button>
                    </div>
                </div>

                @if($santris->count() > 0)
                <form action="{{ route('admin.presensi.store') }}" method="POST" id="formPresensi">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama Santri</th>
                                    <th>Kelas</th>
                                    <th class="text-center" style="width: 25%">Status Kehadiran</th>
                                    <th style="width: 18%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($santris as $index => $santri)
                                @php
                                    $currentStatus = $presensiHariIni[$santri->id] ?? '';
                                    $currentKeterangan = $keteranganHariIni[$santri->id] ?? '';
                                @endphp
                                <tr>
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $santri->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $santri->no_induk }}</small>
                                    </td>
                                    <td><span class="badge-kelas">{{ $santri->kelas->nama_kelas ?? '-' }}</span></td>
                                    <td>
                                        <input type="hidden" name="presensi[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                        <input type="hidden" name="presensi[{{ $index }}][status]" id="status-{{ $santri->id }}" value="{{ $currentStatus ?: 'Hadir' }}">
                                        <div class="status-btn-group justify-content-center">
                                            <button type="button" class="status-btn {{ $currentStatus === 'Hadir' || !$currentStatus ? 'active-hadir' : '' }}"
                                                onclick="setStatus({{ $santri->id }}, {{ $index }}, 'Hadir', this)" title="Hadir">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="status-btn {{ $currentStatus === 'Sakit' ? 'active-sakit' : '' }}"
                                                onclick="setStatus({{ $santri->id }}, {{ $index }}, 'Sakit', this)" title="Sakit">
                                                <i class="fas fa-thermometer-half"></i>
                                            </button>
                                            <button type="button" class="status-btn {{ $currentStatus === 'Izin' ? 'active-izin' : '' }}"
                                                onclick="setStatus({{ $santri->id }}, {{ $index }}, 'Izin', this)" title="Izin">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                            <button type="button" class="status-btn {{ $currentStatus === 'Alfa' ? 'active-alfa' : '' }}"
                                                onclick="setStatus({{ $santri->id }}, {{ $index }}, 'Alfa', this)" title="Alfa">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="presensi[{{ $index }}][keterangan]"
                                            class="keterangan-input" placeholder="Opsional..."
                                            value="{{ $currentKeterangan }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary btn-lg px-5" style="border-radius: 14px; font-weight: 700;">
                            <i class="fas fa-save me-2"></i>Simpan Presensi
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox d-block mb-2 fs-1"></i>
                    <p class="mb-0">Tidak ada data santri aktif. Silakan tambahkan santri terlebih dahulu atau pilih kelas.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function setStatus(santriId, index, status, btn) {
    // Update hidden input
    document.getElementById('status-' + santriId).value = status;

    // Reset sibling buttons
    const group = btn.parentElement;
    group.querySelectorAll('.status-btn').forEach(b => {
        b.className = 'status-btn';
    });

    // Activate clicked button
    btn.classList.add('active-' + status.toLowerCase());

    // Update stats dynamically
    updateStats();
}

function setAllStatus(status) {
    document.querySelectorAll('[id^="status-"]').forEach(input => {
        input.value = status;
    });

    document.querySelectorAll('.status-btn-group').forEach(group => {
        group.querySelectorAll('.status-btn').forEach(btn => btn.className = 'status-btn');
        // Activate the first button (Hadir)
        group.querySelector('.status-btn').classList.add('active-' + status.toLowerCase());
    });

    updateStats();
}

function updateStats() {
    let hadir = 0, sakit = 0, izin = 0, alfa = 0;
    document.querySelectorAll('[id^="status-"]').forEach(input => {
        switch(input.value) {
            case 'Hadir': hadir++; break;
            case 'Sakit': sakit++; break;
            case 'Izin': izin++; break;
            case 'Alfa': alfa++; break;
        }
    });

    const cards = document.querySelectorAll('.stat-card .stat-number');
    if(cards.length >= 5) {
        cards[0].textContent = hadir;
        cards[1].textContent = sakit;
        cards[2].textContent = izin;
        cards[3].textContent = alfa;
        cards[4].textContent = 0;
    }
}
</script>
@endsection
