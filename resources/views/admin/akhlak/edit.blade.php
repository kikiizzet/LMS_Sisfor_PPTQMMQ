@extends('layout')

@section('main-content')
<div class="content-wrapper">
    <div class="container-fluid px-4 py-4">

        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.akhlak.index', ['tanggal' => $penilaian->tanggal->toDateString()]) }}" class="btn btn-light rounded-circle border p-2 me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h4 fw-bold mb-1 text-dark">Edit Penilaian Akhlak</h1>
                <p class="text-muted small mb-0">Perbarui data penilaian mutabaah santri</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card card-main">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $penilaian->santri->nama_lengkap ?? 'Santri' }}</h5>
                                <small class="text-muted">NIS: {{ $penilaian->santri->no_induk }} | Kelas: {{ $penilaian->santri->kelas->nama_kelas ?? '-' }}</small>
                            </div>
                            <span class="text-muted small">
                                Tanggal: {{ $penilaian->tanggal->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.akhlak.update', $penilaian->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $penilaian->tanggal->toDateString()) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Guru / Musyrif Penilai</label>
                                    <select name="guru_id" class="form-select">
                                        <option value="">-- Pilih Guru/Musyrif --</option>
                                        @foreach($gurus as $g)
                                            <option value="{{ $g->id }}" {{ old('guru_id', $penilaian->guru_id) == $g->id ? 'selected' : '' }}>
                                                {{ $g->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted">Sumber Penilai</label>
                                    <select name="tipe_penilai" class="form-select" required>
                                        <option value="musyrif" {{ old('tipe_penilai', $penilaian->tipe_penilai) == 'musyrif' ? 'selected' : '' }}>Musyrif Asrama</option>
                                        <option value="guru_tahfidz" {{ old('tipe_penilai', $penilaian->tipe_penilai) == 'guru_tahfidz' ? 'selected' : '' }}>Guru Tahfidz</option>
                                        <option value="guru_kbm" {{ old('tipe_penilai', $penilaian->tipe_penilai) == 'guru_kbm' ? 'selected' : '' }}>Guru KBM Pagi</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4 text-muted">

                            <h6 class="fw-bold text-dark mb-3">5 Indikator Penilaian (Skala 1 - 4):</h6>

                            <!-- 1. Ibadah -->
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <label class="fw-bold text-dark mb-1">1. Ibadah & Akademik Harian</label>
                                <small class="text-muted d-block mb-2">Sholat 5 waktu berjamaah, masuk KBM, halaqah tahfidz.</small>
                                <select name="skor_ibadah" class="form-select" required>
                                    <option value="4" {{ old('skor_ibadah', $penilaian->skor_ibadah) == 4 ? 'selected' : '' }}>4 - Sangat Baik</option>
                                    <option value="3" {{ old('skor_ibadah', $penilaian->skor_ibadah) == 3 ? 'selected' : '' }}>3 - Baik</option>
                                    <option value="2" {{ old('skor_ibadah', $penilaian->skor_ibadah) == 2 ? 'selected' : '' }}>2 - Cukup</option>
                                    <option value="1" {{ old('skor_ibadah', $penilaian->skor_ibadah) == 1 ? 'selected' : '' }}>1 - Kurang</option>
                                </select>
                            </div>

                            <!-- 2. Adab -->
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <label class="fw-bold text-dark mb-1">2. Adab & Pergaulan</label>
                                <small class="text-muted d-block mb-2">Sopan santun, pergaulan sehat, tidak membully.</small>
                                <select name="skor_adab" class="form-select" required>
                                    <option value="4" {{ old('skor_adab', $penilaian->skor_adab) == 4 ? 'selected' : '' }}>4 - Sangat Baik</option>
                                    <option value="3" {{ old('skor_adab', $penilaian->skor_adab) == 3 ? 'selected' : '' }}>3 - Baik</option>
                                    <option value="2" {{ old('skor_adab', $penilaian->skor_adab) == 2 ? 'selected' : '' }}>2 - Cukup</option>
                                    <option value="1" {{ old('skor_adab', $penilaian->skor_adab) == 1 ? 'selected' : '' }}>1 - Kurang</option>
                                </select>
                            </div>

                            <!-- 3. Kebersihan -->
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <label class="fw-bold text-dark mb-1">3. Kebersihan & Tanggung Jawab</label>
                                <small class="text-muted d-block mb-2">Kebersihan kamar, kelas, dan fasilitas pondok.</small>
                                <select name="skor_kebersihan" class="form-select" required>
                                    <option value="4" {{ old('skor_kebersihan', $penilaian->skor_kebersihan) == 4 ? 'selected' : '' }}>4 - Sangat Baik</option>
                                    <option value="3" {{ old('skor_kebersihan', $penilaian->skor_kebersihan) == 3 ? 'selected' : '' }}>3 - Baik</option>
                                    <option value="2" {{ old('skor_kebersihan', $penilaian->skor_kebersihan) == 2 ? 'selected' : '' }}>2 - Cukup</option>
                                    <option value="1" {{ old('skor_kebersihan', $penilaian->skor_kebersihan) == 1 ? 'selected' : '' }}>1 - Kurang</option>
                                </select>
                            </div>

                            <!-- 4. Kegiatan -->
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <label class="fw-bold text-dark mb-1">4. Keaktifan Keagamaan (Semaan)</label>
                                <small class="text-muted d-block mb-2">Keikutsertaan santri dalam kegiatan semaan Al-Qur'an.</small>
                                <select name="skor_kegiatan" class="form-select" required>
                                    <option value="4" {{ old('skor_kegiatan', $penilaian->skor_kegiatan) == 4 ? 'selected' : '' }}>4 - Sangat Baik</option>
                                    <option value="3" {{ old('skor_kegiatan', $penilaian->skor_kegiatan) == 3 ? 'selected' : '' }}>3 - Baik</option>
                                    <option value="2" {{ old('skor_kegiatan', $penilaian->skor_kegiatan) == 2 ? 'selected' : '' }}>2 - Cukup</option>
                                    <option value="1" {{ old('skor_kegiatan', $penilaian->skor_kegiatan) == 1 ? 'selected' : '' }}>1 - Kurang</option>
                                </select>
                            </div>

                            <!-- 5. Kedisiplinan -->
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <label class="fw-bold text-dark mb-1">5. Kedisiplinan & Tata Tertib</label>
                                <small class="text-muted d-block mb-2">Kepatuhan tata tertib pondok pesantren.</small>
                                <select name="skor_kedisiplinan" class="form-select" required>
                                    <option value="4" {{ old('skor_kedisiplinan', $penilaian->skor_kedisiplinan) == 4 ? 'selected' : '' }}>4 - Sangat Baik</option>
                                    <option value="3" {{ old('skor_kedisiplinan', $penilaian->skor_kedisiplinan) == 3 ? 'selected' : '' }}>3 - Baik</option>
                                    <option value="2" {{ old('skor_kedisiplinan', $penilaian->skor_kedisiplinan) == 2 ? 'selected' : '' }}>2 - Cukup</option>
                                    <option value="1" {{ old('skor_kedisiplinan', $penilaian->skor_kedisiplinan) == 1 ? 'selected' : '' }}>1 - Kurang</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Catatan Pelanggaran / Observasi</label>
                                <textarea name="catatan_pelanggaran" class="form-control" rows="2" placeholder="Tuliskan catatan khusus jika ada...">{{ old('catatan_pelanggaran', $penilaian->catatan_pelanggaran) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.akhlak.index', ['tanggal' => $penilaian->tanggal->toDateString()]) }}" class="btn btn-light border px-4 py-2 rounded-3">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
