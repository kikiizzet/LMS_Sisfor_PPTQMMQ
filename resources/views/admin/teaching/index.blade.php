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

    .form-select {
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    .form-select:focus {
        border-color: #0061ff;
        box-shadow: 0 0 0 3px rgba(0, 97, 255, 0.1);
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Data Mengajar Guru</h1>
                <p>Master Data - Kelola tugas mengajar guru per mata pelajaran dan kelas</p>
            </div>
            <a href="{{ route('admin.teaching.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Data Mengajar
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

        <!-- Filter Section -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-600">Filter Tingkat</label>
                        <select name="tingkat" class="form-select">
                            <option value="">-- Semua Tingkat --</option>
                            @foreach($tingkats as $tingkat)
                                <option value="{{ $tingkat }}" {{ request('tingkat') == $tingkat ? 'selected' : '' }}>
                                    {{ $tingkat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-600">Filter Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelas_list as $kls)
                                <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                                    {{ $kls->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" style="border-radius: 12px;">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.teaching.index') }}" class="btn btn-secondary" style="border-radius: 12px;">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card card-main">
            <div class="card-body p-4">
                <!-- Info -->
                <div class="row mb-4">
                    <div class="col-md-12 text-end">
                        <small class="text-muted">Total: <strong>{{ $teachings->count() }} data mengajar</strong></small>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Guru</th>
                                <th style="width: 12%">Kelas</th>
                                <th style="width: 15%">Mata Pelajaran</th>
                                <th style="width: 10%">Induk</th>
                                <th style="width: 10%">Kelompok</th>
                                <th style="width: 8%">JTM</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachings as $index => $teaching)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td><strong>{{ $teaching->guru->nama }}</strong></td>
                                    <td>{{ $teaching->kelas->nama_kelas }}</td>
                                    <td>{{ $teaching->mata_pelajaran }}</td>
                                    <td>{{ $teaching->induk ?? '-' }}</td>
                                    <td>{{ $teaching->kelompok ?? '-' }}</td>
                                    <td><span class="badge-info">{{ $teaching->jtm ?? '-' }} jam</span></td>
                                    <td>
                                        <span class="status-badge {{ $teaching->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $teaching->is_active ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.teaching.edit', $teaching) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.teaching.destroy', $teaching) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                                        <i class="fas fa-inbox me-2"></i>Tidak ada data mengajar
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
@endsection
