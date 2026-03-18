@extends('layout')

@section('main-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Data Testimoni</h4>
        <a href="{{ route('admin.testimoni.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Tambah Testimoni
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold" style="width: 80px">No</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Foto</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Penulis</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold text-end" style="width: 150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonis as $index => $testimoni)
                        <tr>
                            <td class="px-4 text-muted fw-medium">{{ $index + 1 }}</td>
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
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Aktif (Tampil)</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="{{ route('admin.testimoni.edit', $testimoni->id) }}" class="btn btn-sm btn-light btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.testimoni.destroy', $testimoni->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light btn-action btn-delete" title="Hapus">
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
@endsection
