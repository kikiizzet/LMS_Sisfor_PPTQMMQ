@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

    .form-control-minimal {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.95rem;
    }

    .form-control-minimal:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
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

    .btn-restore { color: #0061ff; }
    .btn-restore:hover { background: #0061ff; color: white; }

    .table-premium thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 20px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .table-premium tbody td {
        padding: 16px 20px;
        vertical-align: middle;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.95rem;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-mortarboard-fill me-2 text-primary"></i>Data Alumni</h1>
                <p>Daftar santri yang telah menyelesaikan pendidikan (Lulus)</p>
            </div>
            <div class="text-end">
                <span class="badge bg-success" style="border-radius: 10px; font-weight: 700; padding: 10px 16px;">
                    Total Alumni: {{ count($alumni) }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-main">
            <div class="card-body p-4">
                <!-- Filters and Search -->
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-md-6">
                        <div class="search-container">
                            <i class="bi bi-search search-icon-inside"></i>
                            <input type="text" id="searchInput" class="form-control form-control-minimal ps-5 w-100" placeholder="Cari alumni berdasarkan nama atau NIS...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.alumni.index') }}" method="GET" class="d-flex gap-2 justify-content-md-end">
                            <select name="tahun" class="form-select form-control-minimal" style="max-width: 220px;" onchange="this.form.submit()">
                                <option value="">-- Semua Tahun Lulus --</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>Tahun Lulus: {{ $tahun }}</option>
                                @endforeach
                            </select>
                            @if(request('tahun'))
                                <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-secondary" style="border-radius: 12px; display: inline-flex; align-items: center;">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-premium align-middle" id="alumniTable">
                        <thead>
                            <tr>
                                <th style="width: 8%">Foto</th>
                                <th style="width: 35%">Nama Lengkap / NIS</th>
                                <th style="width: 15%">Jenis Kelamin</th>
                                <th style="width: 20%">Tahun Kelulusan</th>
                                <th style="width: 12%" class="text-center">Koreksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumni as $a)
                            <tr class="alumni-row">
                                <td>
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($a->nama_lengkap, 0, 2)) }}
                                    </div>
                                </td>
                                <td>
                                    <h6 class="mb-0 fw-bold alumni-name">{{ $a->nama_lengkap }}</h6>
                                    <small class="text-muted alumni-nis">NIS: {{ $a->no_induk }}</small>
                                </td>
                                <td>
                                    @if($a->jenis_kelamin === 'L')
                                        <span class="badge bg-light text-dark border">Laki-laki</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Perempuan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary px-3 py-2" style="border-radius: 8px;">Lulus {{ $a->tahun_lulus ?? date('Y') }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.alumni.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan kelulusan santri ini dan mengembalikannya ke status Aktif?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-restore" title="Kembalikan ke Aktif">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-mortarboard me-2 d-block mb-2 fs-3"></i>Tidak ada data alumni ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll('.alumni-row');

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                rows.forEach(row => {
                    const name = row.querySelector('.alumni-name').textContent.toLowerCase();
                    const nis = row.querySelector('.alumni-nis').textContent.toLowerCase();
                    
                    if (name.includes(query) || nis.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
