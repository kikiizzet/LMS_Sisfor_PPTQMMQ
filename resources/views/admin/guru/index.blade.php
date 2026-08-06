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

    .btn-edit { color: #0061ff; }
    .btn-edit:hover { background: #0061ff; color: white; }
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
        border-radius: 12px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 97, 255, 0.2);
    }

    .avatar-guru {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #f8faff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    [data-theme="dark"] .card-main {
        background: rgba(30, 41, 59, 0.95);
    }

    [data-theme="dark"] .table-premium thead th {
        color: #cbd5e1;
        border-bottom-color: #334155;
    }

    [data-theme="dark"] .text-guru-name {
        color: #ffffff;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-person-badge me-2"></i>Data Guru</h1>
                <p>Master Data · Kelola informasi guru pengajar</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Guru
            </a>
        </div>

        <!-- Alert Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan!</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Data Table -->
        <div class="card card-main">
            <div class="card-body p-4">
                <!-- Search Bar -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="search-container">
                            <i class="fas fa-search search-icon-inside"></i>
                            <input type="text" class="form-control ps-5" id="searchInput" placeholder="Cari nama atau NUPTK..." style="border-radius: 12px; border: 2px solid #f0f0f0;">
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">Total: <strong>{{ $gurus->count() }} guru</strong></small>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-premium mb-0" id="guruTable">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 8%">Foto</th>
                                <th style="width: 15%">Nama</th>
                                <th style="width: 12%">NIK/NUPTK</th>
                                <th style="width: 8%">L/P</th>
                                <th style="width: 15%">TTL</th>
                                <th style="width: 12%">Pendidikan</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $index => $guru)
                                <tr class="guru-row">
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        @if($guru->foto)
                                            <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->nama }}" class="avatar-guru">
                                        @else
                                            <div class="avatar-circle" style="width: 45px; height: 45px;">
                                                {{ substr($guru->nama, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-guru-name fw-bold">{{ $guru->nama }}</span>
                                        @if($guru->wali_kelas)
                                            <br><small class="text-muted">Wali: {{ $guru->wali_kelas }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $guru->nuptk ?? '-' }}</div>
                                        <small class="text-muted">{{ $guru->nik ?? '-' }}</small>
                                    </td>
                                    <td>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : ($guru->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                                    <td>
                                        <div>{{ $guru->tempat_lahir ?? '-' }}</div>
                                        <small class="text-muted">{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d/m/Y') : '-' }}</small>
                                    </td>
                                    <td>{{ $guru->pendidikan ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $guru->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $guru->is_active ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.guru.edit', $guru) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.guru.destroy', $guru) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox me-2"></i>Tidak ada data guru
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
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.guru-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endsection
