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

    .table-premium thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 14px 10px;
        border-bottom: 2px solid #f8f9fa;
        white-space: nowrap;
    }
    .table-premium tbody td {
        padding: 10px;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .badge-kelas {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Status mini badges */
    .mini-badge {
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
    }
    .mini-hadir { background: #dcfce7; color: #166534; }
    .mini-sakit { background: #fef3c7; color: #92400e; }
    .mini-izin { background: #dbeafe; color: #1e40af; }
    .mini-alfa { background: #fee2e2; color: #991b1b; }
    .mini-kosong { background: #f1f5f9; color: #cbd5e1; }

    /* Summary row */
    .summary-row td {
        font-weight: 700 !important;
        background: #f8fafc !important;
        border-top: 2px solid #e2e8f0 !important;
    }

    /* Legend */
    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-right: 16px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 5px;
        display: inline-block;
    }

    /* Editable cell styling */
    .editable-cell:hover {
        background-color: rgba(0, 97, 255, 0.05) !important;
        box-shadow: inset 0 0 0 1px rgba(0, 97, 255, 0.2);
    }
    
    .editable-cell:focus {
        outline: none !important;
        background-color: rgba(0, 97, 255, 0.1) !important;
        box-shadow: inset 0 0 0 2px #0061ff, 0 0 8px rgba(0, 97, 255, 0.3) !important;
        z-index: 10;
    }

    /* Saving animation */
    .editable-cell.saving .mini-badge {
        animation: pulse-saving 0.8s infinite ease-in-out alternate;
        opacity: 0.6;
    }

    @keyframes pulse-saving {
        0% { transform: scale(1); }
        100% { transform: scale(0.85); filter: brightness(1.2); }
    }

    /* Floating Status Picker Popover */
    .status-picker-popover {
        position: absolute;
        z-index: 9999;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.08);
        padding: 8px;
        display: flex;
        gap: 6px;
        align-items: center;
        pointer-events: auto;
        transform: translate(-50%, -100%);
        margin-top: -8px;
        animation: popover-fade-in 0.18s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .status-picker-popover::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 50%;
        transform: translateX(-50%);
        border-width: 6px 6px 0;
        border-style: solid;
        border-color: #ffffff transparent;
        display: block;
        width: 0;
    }

    .picker-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        font-size: 0.75rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .picker-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .picker-btn:active {
        transform: scale(0.95);
    }

    .picker-btn.btn-hadir { background: #dcfce7; color: #166534; }
    .picker-btn.btn-sakit { background: #fef3c7; color: #92400e; }
    .picker-btn.btn-izin { background: #dbeafe; color: #1e40af; }
    .picker-btn.btn-alfa { background: #fee2e2; color: #991b1b; }
    .picker-btn.btn-kosong { background: #f1f5f9; color: #64748b; }

    @keyframes popover-fade-in {
        from { opacity: 0; transform: translate(-50%, -90%) scale(0.9); }
        to { opacity: 1; transform: translate(-50%, -100%) scale(1); }
    }

    /* Sticky columns configuration */
    .table-premium th.sticky-col,
    .table-premium td.sticky-col {
        position: sticky;
        z-index: 5;
        background-color: #ffffff;
    }
    
    .table-premium td.sticky-col {
        z-index: 4;
    }

    [data-theme="dark"] .table-premium th.sticky-col,
    [data-theme="dark"] .table-premium td.sticky-col {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
        border-color: #334155 !important;
    }

    /* Dark Mode overrides for Attendance Rekap Grid */
    [data-theme="dark"] .mini-hadir { background: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important; }
    [data-theme="dark"] .mini-sakit { background: rgba(251, 191, 36, 0.2) !important; color: #fbbf24 !important; }
    [data-theme="dark"] .mini-izin { background: rgba(59, 130, 246, 0.2) !important; color: #60a5fa !important; }
    [data-theme="dark"] .mini-alfa { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; }
    [data-theme="dark"] .mini-kosong { background: #334155 !important; color: #64748b !important; }

    /* Summary Headers & Cells in Dark Mode */
    [data-theme="dark"] th.summary-h { background: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important; }
    [data-theme="dark"] td.total-hadir { background: rgba(34, 197, 94, 0.1) !important; color: #4ade80 !important; }

    [data-theme="dark"] th.summary-s { background: rgba(251, 191, 36, 0.2) !important; color: #fbbf24 !important; }
    [data-theme="dark"] td.total-sakit { background: rgba(251, 191, 36, 0.1) !important; color: #fbbf24 !important; }

    [data-theme="dark"] th.summary-i { background: rgba(59, 130, 246, 0.2) !important; color: #60a5fa !important; }
    [data-theme="dark"] td.total-izin { background: rgba(59, 130, 246, 0.1) !important; color: #60a5fa !important; }

    [data-theme="dark"] th.summary-a { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; }
    [data-theme="dark"] td.total-alfa { background: rgba(239, 68, 68, 0.1) !important; color: #f87171 !important; }

    /* Popover Picker in Dark Mode */
    [data-theme="dark"] .status-picker-popover {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    [data-theme="dark"] .status-picker-popover::after {
        border-color: #1e293b transparent;
    }

    [data-theme="dark"] .picker-btn.btn-hadir { background: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important; }
    [data-theme="dark"] .picker-btn.btn-sakit { background: rgba(251, 191, 36, 0.2) !important; color: #fbbf24 !important; }
    [data-theme="dark"] .picker-btn.btn-izin { background: rgba(59, 130, 246, 0.2) !important; color: #60a5fa !important; }
    [data-theme="dark"] .picker-btn.btn-alfa { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; }
    [data-theme="dark"] .picker-btn.btn-kosong { background: #334155 !important; color: #a8b8cd !important; }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="fas fa-chart-bar me-2"></i>Rekap Presensi Bulanan
                </h1>
                <p class="text-muted small mb-0">Rekapitulasi kehadiran santri per bulan</p>
            </div>
            <a href="{{ route('admin.presensi.index') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 700;">
                <i class="fas fa-clipboard-check me-2"></i>Input Presensi Harian
            </a>
        </div>

        <!-- Filter -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.presensi.rekap') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-calendar me-1"></i>Bulan
                        </label>
                        <select name="bulan" class="form-select" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                            @foreach($namaBulan as $num => $nama)
                                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-calendar-alt me-1"></i>Tahun
                        </label>
                        <select name="tahun" class="form-select" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                            @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-school me-1"></i>Kelas
                        </label>
                        <select name="kelas_id" class="form-select" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 600; padding: 10px;">
                            <i class="fas fa-filter me-1"></i>Tampilkan Rekap
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Legend -->
        <div class="mb-3 px-1">
            <span class="legend-item"><span class="legend-dot mini-hadir"></span> H = Hadir</span>
            <span class="legend-item"><span class="legend-dot mini-sakit"></span> S = Sakit</span>
            <span class="legend-item"><span class="legend-dot mini-izin"></span> I = Izin</span>
            <span class="legend-item"><span class="legend-dot mini-alfa"></span> A = Alfa</span>
            <span class="legend-item"><span class="legend-dot mini-kosong"></span> - = Belum Input</span>
        </div>

        <!-- Rekap Table -->
        <div class="card card-main">
            <div class="card-body p-3">
                <h5 class="fw-bold mb-3 px-2">
                    <i class="fas fa-table me-2 text-primary"></i>
                    Rekap Bulan {{ $namaBulan[$bulan] }} {{ $tahun }}
                </h5>

                @if($santris->count() > 0)
                <div class="table-responsive">
                    <table class="table table-premium table-bordered mb-0" style="font-size: 0.8rem;">
                        <thead>
                            <tr>
                                <th class="sticky-col" style="left: 0;">No</th>
                                <th class="sticky-col" style="left: 30px; min-width: 150px;">Nama Santri</th>
                                <th>Kelas</th>
                                @for($d = 1; $d <= $jumlahHari; $d++)
                                    <th class="text-center" style="min-width: 32px;">{{ $d }}</th>
                                @endfor
                                <th class="text-center summary-h" style="min-width: 36px; background: #dcfce7;">H</th>
                                <th class="text-center summary-s" style="min-width: 36px; background: #fef3c7;">S</th>
                                <th class="text-center summary-i" style="min-width: 36px; background: #dbeafe;">I</th>
                                <th class="text-center summary-a" style="min-width: 36px; background: #fee2e2;">A</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($santris as $index => $santri)
                            @php
                                $dataPresensi = $presensiBulan[$santri->id] ?? collect();
                                $presensiByDate = $dataPresensi->keyBy(fn($p) => \Carbon\Carbon::parse($p->tanggal)->day);
                                $countH = $dataPresensi->where('status', 'Hadir')->count();
                                $countS = $dataPresensi->where('status', 'Sakit')->count();
                                $countI = $dataPresensi->where('status', 'Izin')->count();
                                $countA = $dataPresensi->where('status', 'Alfa')->count();
                            @endphp
                            <tr>
                                <td class="fw-bold text-muted sticky-col" style="left: 0;">{{ $index + 1 }}</td>
                                <td class="sticky-col" style="left: 30px;">
                                    <strong>{{ $santri->nama_lengkap }}</strong>
                                </td>
                                <td><span class="badge-kelas">{{ $santri->kelas->nama_kelas ?? '-' }}</span></td>
                                @for($d = 1; $d <= $jumlahHari; $d++)
                                    @php
                                        $p = $presensiByDate[$d] ?? null;
                                        $label = '-';
                                        $class = 'mini-kosong';
                                        if ($p) {
                                            switch($p->status) {
                                                case 'Hadir': $label = 'H'; $class = 'mini-hadir'; break;
                                                case 'Sakit': $label = 'S'; $class = 'mini-sakit'; break;
                                                case 'Izin':  $label = 'I'; $class = 'mini-izin'; break;
                                                case 'Alfa':  $label = 'A'; $class = 'mini-alfa'; break;
                                            }
                                        }
                                    @endphp
                                    <td class="text-center p-1 editable-cell" 
                                        data-santri-id="{{ $santri->id }}" 
                                        data-tanggal="{{ sprintf('%04d-%02d-%02d', $tahun, $bulan, $d) }}" 
                                        data-current-status="{{ $p ? $p->status : '' }}"
                                        tabindex="0"
                                        style="cursor: pointer; position: relative; transition: all 0.2s;"
                                        title="Klik untuk mengedit (Ketik H/S/I/A/-)">
                                        <span class="mini-badge {{ $class }}">{{ $label }}</span>
                                    </td>
                                @endfor
                                <td class="text-center fw-bold total-hadir" data-santri-id="{{ $santri->id }}" style="background: #f0fdf4;">{{ $countH }}</td>
                                <td class="text-center fw-bold total-sakit" data-santri-id="{{ $santri->id }}" style="background: #fefce8;">{{ $countS }}</td>
                                <td class="text-center fw-bold total-izin" data-santri-id="{{ $santri->id }}" style="background: #eff6ff;">{{ $countI }}</td>
                                <td class="text-center fw-bold total-alfa" data-santri-id="{{ $santri->id }}" style="background: #fef2f2;">{{ $countA }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox d-block mb-2 fs-1"></i>
                    <p class="mb-0">Tidak ada data santri aktif. Silakan tambahkan santri terlebih dahulu.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let activeCell = null;

    // Create status picker dynamically
    const picker = document.createElement('div');
    picker.className = 'status-picker-popover d-none';
    picker.innerHTML = `
        <button class="picker-btn btn-hadir" data-status="Hadir" title="Hadir (H)">H</button>
        <button class="picker-btn btn-sakit" data-status="Sakit" title="Sakit (S)">S</button>
        <button class="picker-btn btn-izin" data-status="Izin" title="Izin (I)">I</button>
        <button class="picker-btn btn-alfa" data-status="Alfa" title="Alfa (A)">A</button>
        <button class="picker-btn btn-kosong" data-status="-" title="Hapus / Belum Input (-)">-</button>
    `;
    document.body.appendChild(picker);

    function showPicker(cell) {
        activeCell = cell;
        cell.focus();
        
        const rect = cell.getBoundingClientRect();
        picker.classList.remove('d-none');
        
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        picker.style.left = `${rect.left + rect.width / 2 + scrollLeft}px`;
        picker.style.top = `${rect.top + scrollTop}px`;
    }

    function hidePicker() {
        picker.classList.add('d-none');
        activeCell = null;
    }

    // Attach click handler to cells
    document.querySelectorAll('.editable-cell').forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            showPicker(cell);
        });

        cell.addEventListener('keydown', function(e) {
            let key = e.key.toUpperCase();
            let status = null;

            if (key === 'H') status = 'Hadir';
            else if (key === 'S') status = 'Sakit';
            else if (key === 'I') status = 'Izin';
            else if (key === 'A') status = 'Alfa';
            else if (key === '-' || key === 'BACKSPACE' || key === 'DELETE') status = '-';
            else if (key === 'ESCAPE') {
                hidePicker();
                cell.blur();
                return;
            }

            if (status) {
                e.preventDefault();
                updateAttendance(cell, status);
            }
        });
    });

    // Handle picker button click
    picker.querySelectorAll('.picker-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (activeCell) {
                const status = btn.getAttribute('data-status');
                updateAttendance(activeCell, status);
            }
        });
    });

    // Close picker when clicking outside
    document.addEventListener('click', function(e) {
        if (activeCell && !activeCell.contains(e.target) && !picker.contains(e.target)) {
            hidePicker();
        }
    });

    // Update row totals locally
    function updateRowTotals(santriId) {
        const cells = document.querySelectorAll(`.editable-cell[data-santri-id="${santriId}"]`);
        let h = 0, s = 0, i = 0, a = 0;
        
        cells.forEach(c => {
            const status = c.getAttribute('data-current-status');
            if (status === 'Hadir') h++;
            else if (status === 'Sakit') s++;
            else if (status === 'Izin') i++;
            else if (status === 'Alfa') a++;
        });

        const totalH = document.querySelector(`.total-hadir[data-santri-id="${santriId}"]`);
        const totalS = document.querySelector(`.total-sakit[data-santri-id="${santriId}"]`);
        const totalI = document.querySelector(`.total-izin[data-santri-id="${santriId}"]`);
        const totalA = document.querySelector(`.total-alfa[data-santri-id="${santriId}"]`);

        if (totalH) totalH.textContent = h;
        if (totalS) totalS.textContent = s;
        if (totalI) totalI.textContent = i;
        if (totalA) totalA.textContent = a;
    }

    // Map status to css class and short label
    const badgeMapping = {
        'Hadir': { label: 'H', class: 'mini-hadir' },
        'Sakit': { label: 'S', class: 'mini-sakit' },
        'Izin': { label: 'I', class: 'mini-izin' },
        'Alfa': { label: 'A', class: 'mini-alfa' },
        '-': { label: '-', class: 'mini-kosong' },
        '': { label: '-', class: 'mini-kosong' }
    };

    // AJAX call to save attendance
    function updateAttendance(cell, status) {
        const santriId = cell.getAttribute('data-santri-id');
        const tanggal = cell.getAttribute('data-tanggal');
        
        // Show loading state
        cell.classList.add('saving');
        
        fetch("{{ route('admin.presensi.update-single') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                santri_id: santriId,
                tanggal: tanggal,
                status: status
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal menyimpan presensi');
            }
            return response.json();
        })
        .then(data => {
            cell.classList.remove('saving');
            if (data.success) {
                // Update attribute
                const newStatus = data.status || '-';
                cell.setAttribute('data-current-status', newStatus);
                
                // Update badge visuals
                const badge = cell.querySelector('.mini-badge');
                if (badge) {
                    const cfg = badgeMapping[newStatus] || badgeMapping['-'];
                    badge.textContent = cfg.label;
                    // Reset class list on badge
                    badge.className = `mini-badge ${cfg.class}`;
                }
                
                // Recalculate totals
                updateRowTotals(santriId);
                
                // Close picker
                hidePicker();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: data.message || 'Gagal memperbarui presensi.'
                });
            }
        })
        .catch(err => {
            cell.classList.remove('saving');
            Swal.fire({
                icon: 'error',
                title: 'Error Koneksi',
                text: 'Terjadi kegagalan komunikasi dengan server.'
            });
            console.error(err);
        });
    }
});
</script>
@endsection
