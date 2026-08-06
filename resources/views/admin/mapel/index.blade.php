@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
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

    .btn-edit { color: #7c3aed; }
    .btn-edit:hover { background: #7c3aed; color: white; }
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

    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }

    .badge-info-custom {
        background: #ede9fe;
        color: #5b21b6;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .form-select, .form-control {
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    .form-select:focus, .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-book me-2 text-primary"></i>Mata Pelajaran</h1>
                <p>Master Data - Kelola daftar mata pelajaran</p>
            </div>
            <a href="{{ route('admin.mapel.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Mapel
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.mapel.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold small text-muted">CARI</label>
                        <div class="position-relative">
                            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                            <input type="text" name="search" class="form-control ps-5" value="{{ request('search') }}" placeholder="Cari nama / kode mapel...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">KURIKULUM</label>
                        <select name="kurikulum" class="form-select">
                            <option value="">-- Semua Kurikulum --</option>
                            @foreach($kurikulums as $kur)
                                <option value="{{ $kur }}" {{ request('kurikulum') == $kur ? 'selected' : '' }}>{{ $kur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark flex-grow-1" style="border-radius: 12px; font-weight: 600;">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary" style="border-radius: 12px;">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card card-main">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <small class="text-muted">Total: <strong>{{ $mapels->count() }} mata pelajaran</strong></small>
                </div>

                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Kode</th>
                                <th>Mata Pelajaran</th>
                                <th>Induk</th>
                                <th>Kelompok</th>
                                <th>Jurusan</th>
                                <th>JJM</th>
                                <th>Urutan</th>
                                <th>Status</th>
                                <th style="width: 10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapels as $index => $mapel)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td><span class="badge-info-custom">{{ $mapel->kode ?? '-' }}</span></td>
                                    <td><strong>{{ $mapel->nama_mapel }}</strong></td>
                                    <td>{{ $mapel->induk ?? '-' }}</td>
                                    <td>{{ $mapel->kelompok ?? '-' }}</td>
                                    <td>{{ $mapel->jurusan ?? '-' }}</td>
                                    <td>{{ $mapel->jjm ?? '-' }}</td>
                                    <td>{{ $mapel->urutan ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $mapel->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $mapel->is_active ? 'Aktif' : 'Non Aktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.mapel.edit', $mapel) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.mapel.destroy', $mapel) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?');">
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
                                        <i class="fas fa-book d-block mb-2 fs-3" style="color: #c4b5fd;"></i>
                                        Belum ada data mata pelajaran
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
