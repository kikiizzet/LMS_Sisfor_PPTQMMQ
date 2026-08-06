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

    .status-aktif { background: #dcfce7; color: #166534; }
    .status-lulus { background: #dbeafe; color: #1e40af; }
    .status-pindah { background: #fee2e2; color: #991b1b; }

    .badge-kelas {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-person-lines-fill me-2"></i>Data Santri</h1>
                <p>Master Data · Kelola informasi santri terdaftar</p>
            </div>
            <a href="{{ route('admin.santri.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Santri
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

        <!-- Filter & Search -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.santri.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="search-container position-relative">
                            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                            <input type="text" name="search" class="form-control ps-5" value="{{ request('search') }}" placeholder="Cari nama atau nomor induk..." style="border-radius: 12px; border: 2px solid #f0f0f0;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="kelas_id" class="form-select" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 600;">Filter Data</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card card-main">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Santri</th>
                                <th>No Induk / NISN</th>
                                <th>Kelas</th>
                                <th>L/P</th>
                                <th>Status</th>
                                <th style="width: 12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santris as $index => $santri)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $santris->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $santri->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $santri->tempat_lahir ?? '-' }}, {{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d M Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $santri->no_induk }}</span><br>
                                        <small class="text-muted">{{ $santri->nisn ?? '-' }}</small>
                                    </td>
                                    <td><span class="badge-kelas">{{ $santri->kelas->nama_kelas ?? '-' }}</span></td>
                                    <td>{{ $santri->jenis_kelamin }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($santri->status) }}">
                                            {{ $santri->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.santri.edit', $santri) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.santri.destroy', $santri) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox me-2 d-block mb-2 fs-3"></i>Tidak ada data santri
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $santris->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
