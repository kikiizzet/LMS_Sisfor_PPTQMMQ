@extends('layout')

@section('main-content')
<div class="container-fluid max-w-4xl">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.testimoni.index') }}" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Edit Testimoni</h4>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.testimoni.update', $testimoni->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nama Penulis</label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $testimoni->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Rating (1-5)</label>
                            <select name="rating" class="form-select rounded-3 @error('rating') is-invalid @enderror" required>
                                <option value="5" {{ old('rating', $testimoni->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4" {{ old('rating', $testimoni->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4/5)</option>
                                <option value="3" {{ old('rating', $testimoni->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3/5)</option>
                                <option value="2" {{ old('rating', $testimoni->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2/5)</option>
                                <option value="1" {{ old('rating', $testimoni->rating) == 1 ? 'selected' : '' }}>⭐ (1/5)</option>
                            </select>
                            @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Peran / Status (Opsional)</label>
                            <input type="text" name="role" class="form-control rounded-3 @error('role') is-invalid @enderror" value="{{ old('role', $testimoni->role) }}">
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Isi Testimoni</label>
                            <textarea name="content" class="form-control rounded-3 @error('content') is-invalid @enderror" rows="4" required>{{ old('content', $testimoni->content) }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Upload Foto Baru</label>
                            @if($testimoni->image)
                                <div class="mb-3">
                                    <div class="text-muted small mb-2">Foto saat ini:</div>
                                    <img src="{{ Storage::url($testimoni->image) }}" class="rounded-circle shadow-sm mb-2 object-fit-cover" width="100" height="100">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text mt-2 small">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG maksimal 2MB.</div>
                        </div>

                        <div class="card bg-light border-0 rounded-4 mb-4">
                            <div class="card-body">
                                <div class="form-check form-switch d-flex align-items-center gap-3">
                                    <input class="form-check-input fs-4 m-0" type="checkbox" role="switch" id="isActive" name="is_active" value="1" {{ $testimoni->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="isActive">
                                        Tampilkan di Landing Page
                                        <div class="text-muted small fw-normal">Aktifkan untuk menampilkan testimoni ini di halaman publik.</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-light">
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
