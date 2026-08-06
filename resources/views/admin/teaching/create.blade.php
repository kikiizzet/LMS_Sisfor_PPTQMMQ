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
            <a href="{{ route('admin.teaching.index') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <h1 class="h3 fw-bold mt-2">
                <i class="fas fa-book-open me-2"></i>{{ isset($teaching) ? 'Edit Data Mengajar' : 'Tambah Data Mengajar Baru' }}
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
                <form action="{{ isset($teaching) ? route('admin.teaching.update', $teaching) : route('admin.teaching.store') }}" method="POST">
                    @csrf
                    @if(isset($teaching))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Guru -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Guru <span class="text-danger">*</span></label>
                                <select name="guru_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($gurus as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id', isset($teaching) ? $teaching->guru_id : '') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id', isset($teaching) ? $teaching->kelas_id : '') == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="form-group mb-3">
                        <label>Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran', isset($teaching) ? $teaching->mata_pelajaran : '') }}" required>
                    </div>

                    <div class="row">
                        <!-- Induk -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Induk</label>
                                <input type="text" name="induk" class="form-control" value="{{ old('induk', isset($teaching) ? $teaching->induk : '') }}">
                            </div>
                        </div>

                        <!-- Kelompok -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Kelompok</label>
                                <input type="text" name="kelompok" class="form-control" value="{{ old('kelompok', isset($teaching) ? $teaching->kelompok : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Jurusan -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', isset($teaching) ? $teaching->jurusan : '') }}">
                            </div>
                        </div>

                        <!-- JTM -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>JTM (Jam Tugas Mengajar)</label>
                                <input type="number" name="jtm" class="form-control" value="{{ old('jtm', isset($teaching) ? $teaching->jtm : '') }}">
                            </div>
                        </div>
                    </div>

                    @if(isset($teaching))
                        <!-- Status -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $teaching->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    Status Aktif
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- Buttons -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-save me-2"></i>{{ isset($teaching) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('admin.teaching.index') }}" class="btn btn-secondary btn-submit">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
