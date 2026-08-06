@extends('layout')

@section('main-content')
<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-chat-quote-fill me-2 text-primary"></i>Kelola Data Testimoni</h1>
                <p>Kelola testimoni dari santri, wali santri, dan tokoh untuk ditampilkan di web utama</p>
            </div>
            <a href="{{ route('admin.testimoni.create') }}" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Testimoni
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
                                <th>Foto</th>
                                <th>Penulis / Role</th>
                                <th>Status</th>
                                <th style="width: 150px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonis as $index => $testimoni)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $index + 1 }}</td>
                                <td>
                                    @if($testimoni->image)
                                        <img src="{{ Storage::url($testimoni->image) }}" alt="Testimoni" class="rounded-circle object-fit-cover" width="50" height="50">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted" style="width:50px; height:50px;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $testimoni->name }}</div>
                                    <div class="text-muted small mb-1">{{ $testimoni->role }}</div>
                                    <div class="text-secondary small text-truncate" style="max-width:300px;">"{{ Str::limit($testimoni->content, 60) }}"</div>
                                </td>
                                <td>
                                    @if($testimoni->is_active)
                                        <span class="status-badge status-aktif">Aktif (Tampil)</span>
                                    @else
                                        <span class="status-badge status-pindah">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.testimoni.edit', $testimoni->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.testimoni.destroy', $testimoni->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data testimoni ini?')">
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
                                    Belum ada data testimoni.
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
