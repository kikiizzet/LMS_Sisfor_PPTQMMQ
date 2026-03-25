@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --table-border: #f1f4f9;
        --accent-color: #6366f1;
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f5f3ff, #ffffff);
        min-height: 100vh;
        width: 100%;
        transition: background 0.3s ease;
    }

    [data-theme="dark"] .content-wrapper {
        background: radial-gradient(circle at top right, #0f172a, #1e293b);
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
    }

    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: none;
        background: #f8faff;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.1);
    }

    .btn-edit { color: #f59e0b; }
    .btn-edit:hover { background: #f59e0b; color: white; }
    .btn-print { color: #10b981; }
    .btn-print:hover { background: #10b981; color: white; }
    .btn-delete { color: #ef4444; }
    .btn-delete:hover { background: #ef4444; color: white; }

    .table-premium thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 20px;
        border-bottom: 2px solid #f8f9fa;
    }

    .search-container {
        position: relative;
    }

    .search-icon-inside {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        z-index: 5;
    }

    /* Dark Mode specific overrides */
    [data-theme="dark"] .table-premium thead th {
        background: #1e293b;
        color: #94a3b8;
        border-bottom-color: #334155;
    }
    [data-theme="dark"] .table-premium tbody td {
        border-bottom-color: #334155;
    }
    [data-theme="dark"] .btn-action {
        background: #334155 !important;
        border-color: #475569 !important;
    }
    [data-theme="dark"] .avatar-circle {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    [data-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .bg-white {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
</style>

<div class="content-wrapper py-3 py-lg-5">
    <div class="container-fluid px-3 px-lg-4">
        
        <div class="row mb-4 align-items-center text-center text-md-start">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2 class="fw-bold text-dark mb-1" style="font-size: clamp(1.2rem, 4vw, 2rem);">Raport KMI</h2>
                <p class="text-muted mb-0 small">Manajemen laporan hasil belajar KMI</p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end justify-content-center align-items-center gap-2">
                    <a href="{{ route('raport-kmi.create') }}" class="btn btn-primary btn-sm shadow-sm rounded-3 px-3">
                        <i class="bi bi-plus-lg me-1"></i> Baru
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert" style="background: #ecfdf5; color: #065f46;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        <div class="card card-main border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-2">
                <div class="search-container">
                    <i class="bi bi-search search-icon-inside" style="font-size: 0.85rem;"></i>
                    <input type="text" id="searchInput" class="form-control ps-5 rounded-3 py-2 border-0 bg-light small" placeholder="Cari santri, kelas...">
                </div>
            </div>
        </div>

        <div class="card card-main overflow-hidden">
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0" id="kmiTable">
                    <thead>
                        <tr>
                            <th class="ps-3 d-none d-md-table-cell">No</th>
                            <th>Santri</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center d-none d-sm-table-cell">Semester</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($raport_kmis as $index => $raport)
                        <tr class="raport-row">
                            <td class="ps-3 text-muted fw-bold d-none d-md-table-cell">#{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($raport->nama_santri, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-uppercase mb-0 nama-santri" style="font-size: 0.8rem;">{{ $raport->nama_santri }}</div>
                                        <small class="text-muted d-sm-none" style="font-size: 0.65rem;">{{ $raport->semester }} | {{ $raport->tahun_pelajaran }}</small>
                                        <small class="text-muted d-none d-sm-block" style="font-size: 0.65rem;">Induk: {{ $raport->no_induk }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-secondary small fw-bold" style="font-size: 0.75rem;">{{ $raport->kelas }}</td>
                            <td class="text-center d-none d-sm-table-cell">
                                <span class="badge rounded-pill bg-light text-primary px-2 py-1 border fw-bold" style="font-size: 0.65rem;">{{ $raport->semester }}</span>
                            </td>
                            <td class="pe-3">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('raport-kmi.cetak', $raport->id) }}" target="_blank" class="btn-action btn-print" title="Cetak" style="width: 30px; height: 30px;">
                                        <i class="bi bi-printer" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <a href="{{ route('raport-kmi.edit', $raport->id) }}" class="btn-action btn-edit" title="Edit" style="width: 30px; height: 30px;">
                                        <i class="bi bi-pencil-square" style="font-size: 0.8rem;"></i>
                                    </a>
                                    <form action="{{ route('raport-kmi.destroy', $raport->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus" style="width: 30px; height: 30px;">
                                            <i class="bi bi-trash3" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/white/empty-folder.svg" alt="Empty" style="width: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3">Belum ada data raport KMI yang tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Pencarian Sederhana
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.raport-row');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if(text.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
@endsection
