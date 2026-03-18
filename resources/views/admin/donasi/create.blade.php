@extends('layout')

@section('main-content')
<div class="container-fluid max-w-4xl">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.donasi.index') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Tambah Poster Donasi Baru</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.donasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Judul / Keterangan (Opsional)</label>
                            <input type="text" name="title" class="form-control form-control-lg rounded-3 @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Poster Ramadhan Sahabat MMQ">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Upload Gambar Poster</label>
                            <input type="file" name="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small">Format: JPG, PNG maksimal 5MB.</div>
                        </div>

                        <div class="card bg-light border-0 rounded-4 mb-4">
                            <div class="card-body">
                                <div class="form-check form-switch d-flex align-items-center gap-3">
                                    <input class="form-check-input fs-4 m-0" type="checkbox" role="switch" id="isActive" name="is_active" value="1" checked>
                                    <label class="form-check-label fw-bold" for="isActive">
                                        Tampilkan di Halaman Donasi
                                        <div class="text-muted small fw-normal">Aktifkan untuk menampilkan poster ini di publik.</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-light">
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>Simpan Poster
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
