@extends('layout')

@section('main-content')
<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-trophy-fill me-2 text-primary"></i>Kelola Data Prestasi</h1>
                <p>Kelola daftar prestasi santri dan lembaga untuk ditampilkan di web utama</p>
            </div>
            <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Prestasi
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
                                <th>Gambar</th>
                                <th>Judul / Deskripsi</th>
                                <th>Status</th>
                                <th style="width: 150px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prestasis as $index => $prestasi)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $index + 1 }}</td>
                                <td>
                                    @if($prestasi->image)
                                        <img src="{{ Storage::url($prestasi->image) }}" alt="Prestasi" class="rounded-3 object-fit-cover" width="60" height="60">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width:60px; height:60px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $prestasi->title }}</div>
                                    <div class="text-muted small text-truncate" style="max-width:300px;">{{ Str::limit($prestasi->description, 60) }}</div>
                                </td>
                                <td>
                                    @if($prestasi->is_active)
                                        <span class="status-badge status-aktif">Aktif (Tampil)</span>
                                    @else
                                        <span class="status-badge status-pindah">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.prestasi.edit', $prestasi->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.prestasi.destroy', $prestasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data prestasi ini?')">
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
                                    Belum ada data prestasi.
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
