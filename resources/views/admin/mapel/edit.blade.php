@extends('layout')

@section('main-content')
<style>
    .content-wrapper { background: radial-gradient(circle at top right, #f8faff, #ffffff); min-height: 100vh; }
    .card-main { border: none; border-radius: 24px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); }
    .form-group label { font-weight: 600; color: #0f172a; margin-bottom: 8px; }
    .form-control, .form-select { border-radius: 12px; border: 2px solid #f0f0f0; padding: 10px 16px; font-size: 0.95rem; transition: all 0.3s ease; }
    .form-control:focus, .form-select:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
    .btn-submit { border-radius: 12px; padding: 12px 32px; font-weight: 700; }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <div class="mb-4">
            <a href="{{ route('admin.mapel.index') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <h1 class="h3 fw-bold mt-2">
                <i class="fas fa-book me-2" style="color: #7c3aed;"></i>Edit Mata Pelajaran
            </h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="card card-main">
            <div class="card-body p-4">
                <form action="{{ route('admin.mapel.update', $mapel) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Kode Mapel</label>
                                <input type="text" name="kode" class="form-control" value="{{ old('kode', $mapel->kode) }}" placeholder="Contoh: MTK01">
                            </div>
                        </div>
                        <div class="col-md-8 mb-3">
                            <div class="form-group">
                                <label>Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mapel" class="form-control" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Induk</label>
                                <input type="text" name="induk" class="form-control" value="{{ old('induk', $mapel->induk) }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Kelompok</label>
                                <input type="text" name="kelompok" class="form-control" value="{{ old('kelompok', $mapel->kelompok) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $mapel->jurusan) }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>JJM (Jam Jumlah Mengajar)</label>
                                <input type="number" name="jjm" class="form-control" value="{{ old('jjm', $mapel->jjm) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label>Urutan</label>
                                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $mapel->urutan) }}" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Kurikulum</label>
                                <select name="kurikulum" class="form-select">
                                    <option value="">-- Pilih Kurikulum --</option>
                                    <option value="Kurikulum Merdeka" {{ old('kurikulum', $mapel->kurikulum) == 'Kurikulum Merdeka' ? 'selected' : '' }}>Kurikulum Merdeka</option>
                                    <option value="K13" {{ old('kurikulum', $mapel->kurikulum) == 'K13' ? 'selected' : '' }}>K13</option>
                                    <option value="KMI" {{ old('kurikulum', $mapel->kurikulum) == 'KMI' ? 'selected' : '' }}>KMI</option>
                                    <option value="Diniyah" {{ old('kurikulum', $mapel->kurikulum) == 'Diniyah' ? 'selected' : '' }}>Diniyah</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="form-check mt-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $mapel->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn text-white btn-submit" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.mapel.index') }}" class="btn btn-secondary btn-submit">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
