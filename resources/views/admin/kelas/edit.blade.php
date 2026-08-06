@extends('layout')

@section('main-content')
<style>
    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    .form-group label {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        padding: 10px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0061ff;
        box-shadow: 0 0 0 3px rgba(0, 97, 255, 0.1);
    }

    .btn-submit {
        border-radius: 12px;
        padding: 12px 32px;
        font-weight: 700;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('admin.kelas.index') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <h1 class="h3 fw-bold mt-2">
                <i class="fas fa-school me-2"></i>Edit Data Kelas
            </h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Card -->
        <div class="card card-main">
            <div class="card-body p-4">
                <form action="{{ route('admin.kelas.update', $kela) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nama Kelas -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Nama Kelas <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required>
                            </div>
                        </div>

                        <!-- Jumlah Siswa -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Jumlah Siswa</label>
                                <input type="number" name="jumlah_siswa" class="form-control" value="{{ old('jumlah_siswa', $kela->jumlah_siswa) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Wali Kelas -->
                    <div class="form-group mb-3">
                        <label>Wali Kelas</label>
                        <select name="wali_kelas_id" class="form-select">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kela->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <!-- Tingkat -->
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Tingkat</label>
                                <input type="text" name="tingkat" class="form-control" placeholder="Contoh: VIII, IX" value="{{ old('tingkat', $kela->tingkat) }}">
                            </div>
                        </div>

                        <!-- Jurusan -->
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $kela->jurusan) }}">
                            </div>
                        </div>

                        <!-- Jenis -->
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Jenis</label>
                                <input type="text" name="jenis" class="form-control" value="{{ old('jenis', $kela->jenis) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Kurikulum -->
                    <div class="form-group mb-3">
                        <label>Kurikulum</label>
                        <input type="text" name="kurikulum" class="form-control" placeholder="Contoh: K13, Merdeka" value="{{ old('kurikulum', $kela->kurikulum) }}">
                    </div>

                    <!-- Status -->
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $kela->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Status Aktif
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary btn-submit">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
