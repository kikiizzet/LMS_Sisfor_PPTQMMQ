@extends('layout')

@section('main-content')
<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-heart-fill me-2 text-primary"></i>Kelola Poster Donasi</h1>
                <p>Kelola poster dan kampanye donasi/pembangunan pondok untuk ditampilkan di web utama</p>
            </div>
            <a href="{{ route('admin.donasi.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Poster
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-main">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px">No</th>
                                <th>Poster</th>
                                <th>Judul / Keterangan</th>
                                <th>Status</th>
                                <th style="width: 150px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donasis as $index => $donasi)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $index + 1 }}</td>
                                <td>
                                    @if($donasi->image)
                                        <img src="{{ Storage::url($donasi->image) }}" alt="Poster" class="rounded-3 object-fit-cover" width="80" height="auto">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width:60px; height:60px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $donasi->title ?? 'Tanpa Judul' }}</div>
                                </td>
                                <td>
                                    @if($donasi->is_active)
                                        <span class="status-badge status-aktif">Aktif (Tampil)</span>
                                    @else
                                        <span class="status-badge status-pindah">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.donasi.edit', $donasi->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.donasi.destroy', $donasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus poster donasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 mb-2 d-block opacity-50"></i>
                                    Belum ada data poster donasi.
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
