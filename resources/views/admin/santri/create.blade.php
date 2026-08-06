@extends('layout')

@section('main-content')
<style>
    .content-wrapper { background: radial-gradient(circle at top right, #f8faff, #ffffff); min-height: 100vh; width: 100%; }
    .card-main { border: none; border-radius: 24px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); }
    .form-control-minimal { border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; font-size: 0.95rem; }
    .form-control-minimal:focus { border-color: #0061ff; box-shadow: 0 0 0 4px rgba(0, 97, 255, 0.1); }
    .form-label-custom { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 8px; }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.santri.index') }}" class="btn btn-light rounded-circle p-2 me-3 shadow-sm"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="h3 fw-bold mb-1">Tambah Data Santri</h1>
                <p class="text-muted small">Masukkan informasi detail santri baru</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius: 16px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-main">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('admin.santri.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-minimal" value="{{ old('nama_lengkap') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">No Induk</label>
                            <input type="text" name="no_induk" class="form-control form-control-minimal" value="{{ old('no_induk') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">NISN</label>
                            <input type="text" name="nisn" class="form-control form-control-minimal" value="{{ old('nisn') }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label-custom">Kelas Tujuan</label>
                            <select name="kelas_id" class="form-select form-control-minimal" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select form-control-minimal" required>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select form-control-minimal" required>
                                <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Lulus" {{ old('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="Pindah" {{ old('status') == 'Pindah' ? 'selected' : '' }}>Pindah Keluar</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control form-control-minimal" value="{{ old('tempat_lahir') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control form-control-minimal" value="{{ old('tanggal_lahir') }}">
                        </div>
                    </div>
                    
                    <hr class="my-4" style="border-color: #e2e8f0;">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 py-2" style="border-radius: 12px; font-weight: 600;">Simpan Data Santri</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
