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

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-building me-2"></i>Data Kelas</h1>
                <p>Master Data · Kelola data kelas dan wali kelas</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
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
                            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                            <input type="text" class="form-control ps-5" id="searchInput" placeholder="Cari nama kelas..." style="border-radius: 12px; border: 2px solid #f0f0f0;">
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">Total: <strong>{{ $kelas->count() }} kelas</strong></small>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-premium mb-0" id="kelasTable">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Nama Kelas</th>
                                <th style="width: 10%">Jumlah Siswa</th>
                                <th style="width: 15%">Wali Kelas</th>
                                <th style="width: 12%">Tingkat</th>
                                <th style="width: 12%">Jurusan</th>
                                <th style="width: 10%">Jenis</th>
                                <th style="width: 10%">Kurikulum</th>
                                <th style="width: 11%">Status</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelas as $index => $k)
                                <tr class="kelas-row">
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td><strong>{{ $k->nama_kelas }}</strong></td>
                                    <td><span class="badge-info">{{ $k->jumlah_siswa ?? 0 }} siswa</span></td>
                                    <td>{{ $k->waliKelas->nama ?? '-' }}</td>
                                    <td>{{ $k->tingkat ?? '-' }}</td>
                                    <td>{{ $k->jurusan ?? '-' }}</td>
                                    <td>{{ $k->jenis ?? '-' }}</td>
                                    <td>{{ $k->kurikulum ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $k->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $k->is_active ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.kelas.edit', $k) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox me-2"></i>Tidak ada data kelas
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
    const rows = document.querySelectorAll('.kelas-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endsection
