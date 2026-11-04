@extends('layout')

@section('main-content')
<style>
    .card-main {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
    }
    .score-select {
        font-weight: 700;
        text-align: center;
        border-radius: 8px;
        padding: 5px 8px;
        min-width: 60px;
    }
    .score-select[data-val="4"] { background-color: #dcfce7; color: #166534; border-color: #86efac; }
    .score-select[data-val="3"] { background-color: #e0f2fe; color: #075985; border-color: #7dd3fc; }
    .score-select[data-val="2"] { background-color: #fef9c3; color: #854d0e; border-color: #fde047; }
    .score-select[data-val="1"] { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5; }

    .badge-live-predikat {
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .guide-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
    }

    [data-theme="dark"] .guide-box {
        background: #1e293b;
        border-color: #334155;
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-4 py-4">

        <!-- HeaderS -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.akhlak.index', ['tanggal' => $tanggal]) }}" class="btn btn-light rounded-circle border p-2">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="h4 fw-bold mb-1 text-dark">Input Penilaian Akhlak</h1>
                    <p class="text-muted small mb-0">Input penilaian mutabaah harian santri (skala 1 - 4)</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.akhlak.rekap') }}" class="btn btn-outline-secondary px-3 py-2 rounded-3">
                    Rekapitulasi
                </a>
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

        <!-- Card Pemilihan Tanggal & Kelas -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.akhlak.create') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-muted mb-1">Tanggal Penilaian</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="document.getElementById('filterForm').submit()">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label small fw-bold text-muted mb-1">Pilih Kelas</label>
                            <select name="kelas_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">-- Pilih Kelas untuk Input Per Kelas --</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }} ({{ $k->santris()->where('status', 'Aktif')->count() }} Santri)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                Tampilkan Daftar Santri
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panduan Indikator Sesuai Proposal Skripsi Bab 3 (Tabel 3.12) -->
        <div class="accordion mb-4" id="accordionGuide">
            <div class="accordion-item border rounded-3 overflow-hidden">
                <h2 class="accordion-header" id="headingGuide">
                    <button class="accordion-button collapsed py-2 px-3 bg-light text-dark fw-semibold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuide" aria-expanded="false">
                        Panduan Pedoman Skor 1 - 4 (Buku Mutabaah PPTQ MMQ)
                    </button>
                </h2>
                <div id="collapseGuide" class="accordion-collapse collapse" data-bs-parent="#accordionGuide">
                    <div class="accordion-body p-3 bg-white">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="guide-box h-100">
                                    <h6 class="fw-bold text-dark mb-1">1. Ibadah & Akademik</h6>
                                    <small class="text-muted d-block mb-2">Sholat 5 waktu berjamaah, masuk KBM, halaqah tahfidz.</small>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        <li><strong>4</strong>: Hadir tepat waktu & tertib</li>
                                        <li><strong>3</strong>: Terlambat 1x dengan udzur jelas</li>
                                        <li><strong>2</strong>: Terlambat tanpa udzur / masbuq</li>
                                        <li><strong>1</strong>: Tidak hadir tanpa keterangan</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="guide-box h-100">
                                    <h6 class="fw-bold text-dark mb-1">2. Adab & Pergaulan</h6>
                                    <small class="text-muted d-block mb-2">Sopan santun, pergaulan sehat, tidak membully.</small>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        <li><strong>4</strong>: Sangat sopan & teladan</li>
                                        <li><strong>3</strong>: Sopan dalam keseharian</li>
                                        <li><strong>2</strong>: Pernah ditegur karena guyonan berlebih</li>
                                        <li><strong>1</strong>: Melanggar adab / norma santri</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="guide-box h-100">
                                    <h6 class="fw-bold text-dark mb-1">3. Kebersihan & Tanggung Jawab</h6>
                                    <small class="text-muted d-block mb-2">Kebersihan kamar, kelas, dan fasilitas pondok.</small>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        <li><strong>4</strong>: Kamar rapi, piket tertib</li>
                                        <li><strong>3</strong>: Melaksanakan piket dengan baik</li>
                                        <li><strong>2</strong>: Perlu diingatkan kebersihan kamar</li>
                                        <li><strong>1</strong>: Mengabaikan piket & kebersihan</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="guide-box">
                                    <h6 class="fw-bold text-dark mb-1">4. Keaktifan Keagamaan (Semaan)</h6>
                                    <small class="text-muted d-block mb-2">Keikutsertaan santri dalam kegiatan semaan Al-Qur'an.</small>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        <li><strong>4</strong>: Aktif menyimak & hadir penuh</li>
                                        <li><strong>3</strong>: Hadir dan menyimak</li>
                                        <li><strong>2</strong>: Hadir namun kurang fokus</li>
                                        <li><strong>1</strong>: Tidak hadir semaan</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="guide-box">
                                    <h6 class="fw-bold text-dark mb-1">5. Kedisiplinan & Tata Tertib</h6>
                                    <small class="text-muted d-block mb-2">Kepatuhan pada seluruh aturan pondok pesantren.</small>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        <li><strong>4</strong>: Tertib penuh, nihil pelanggaran</li>
                                        <li><strong>3</strong>: Pelanggaran sangat ringan (teguran lisan)</li>
                                        <li><strong>2</strong>: Pelanggaran ringan tercatat</li>
                                        <li><strong>1</strong>: Pelanggaran sedang/berat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($kelasId && $santris->count() > 0)
            <!-- Form Batch Penilaian Santri Per Kelas -->
            <form action="{{ route('admin.akhlak.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <!-- Metadata Penilai -->
                <div class="card card-main mb-4">
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-5">
                                <label class="form-label small fw-bold text-muted mb-1">Guru / Musyrif Penilai</label>
                                <select name="guru_id" class="form-select">
                                    <option value="">-- Pilih Guru / Musyrif (Opsional) --</option>
                                    @foreach($gurus as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold text-muted mb-1">Sumber Penilai</label>
                                <select name="tipe_penilai" class="form-select" required>
                                    <option value="musyrif" selected>Musyrif Asrama</option>
                                    <option value="guru_tahfidz">Guru Tahfidz</option>
                                    <option value="guru_kbm">Guru KBM Pagi</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 text-md-end pt-3">
                                <span class="text-muted small">Total: <strong>{{ $santris->count() }} santri</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Set Nilai Cepat -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAllScores(4)">
                            Set Semua Skor 4
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setAllScores(3)">
                            Set Semua Skor 3
                        </button>
                    </div>
                    <small class="text-muted">Skor: 1 (Kurang), 2 (Cukup), 3 (Baik), 4 (Sangat Baik)</small>
                </div>

                <!-- Tabel Input Santri -->
                <div class="card card-main mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-premium w-100 mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;" class="text-center">No</th>
                                        <th style="min-width: 180px;">Nama Santri</th>
                                        <th class="text-center" style="width: 85px;">Ibadah</th>
                                        <th class="text-center" style="width: 85px;">Adab</th>
                                        <th class="text-center" style="width: 85px;">Kebersihan</th>
                                        <th class="text-center" style="width: 85px;">Kegiatan</th>
                                        <th class="text-center" style="width: 85px;">Disiplin</th>
                                        <th class="text-center" style="width: 90px;">Nilai</th>
                                        <th class="text-center" style="width: 110px;">Predikat</th>
                                        <th style="min-width: 200px;">Catatan Pelanggaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($santris as $index => $s)
                                        @php
                                            $exist = $existingPenilaians->get($s->id);
                                            $sIbadah = $exist ? $exist->skor_ibadah : 4;
                                            $sAdab = $exist ? $exist->skor_adab : 4;
                                            $sKebersihan = $exist ? $exist->skor_kebersihan : 4;
                                            $sKegiatan = $exist ? $exist->skor_kegiatan : 4;
                                            $sKedisiplinan = $exist ? $exist->skor_kedisiplinan : 4;
                                            $sCatatan = $exist ? $exist->catatan_pelanggaran : '';
                                        @endphp
                                        <tr data-santri-id="{{ $s->id }}">
                                            <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $s->nama_lengkap }}</div>
                                                <small class="text-muted">NIS: {{ $s->no_induk }}</small>
                                            </td>
                                            <td class="text-center">
                                                <select name="penilaian[{{ $s->id }}][skor_ibadah]" class="form-select form-select-sm score-select score-input" data-aspect="ibadah" data-val="{{ $sIbadah }}" onchange="updateRowScore({{ $s->id }})">
                                                    <option value="4" {{ $sIbadah == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ $sIbadah == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ $sIbadah == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ $sIbadah == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="penilaian[{{ $s->id }}][skor_adab]" class="form-select form-select-sm score-select score-input" data-aspect="adab" data-val="{{ $sAdab }}" onchange="updateRowScore({{ $s->id }})">
                                                    <option value="4" {{ $sAdab == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ $sAdab == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ $sAdab == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ $sAdab == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="penilaian[{{ $s->id }}][skor_kebersihan]" class="form-select form-select-sm score-select score-input" data-aspect="kebersihan" data-val="{{ $sKebersihan }}" onchange="updateRowScore({{ $s->id }})">
                                                    <option value="4" {{ $sKebersihan == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ $sKebersihan == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ $sKebersihan == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ $sKebersihan == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="penilaian[{{ $s->id }}][skor_kegiatan]" class="form-select form-select-sm score-select score-input" data-aspect="kegiatan" data-val="{{ $sKegiatan }}" onchange="updateRowScore({{ $s->id }})">
                                                    <option value="4" {{ $sKegiatan == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ $sKegiatan == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ $sKegiatan == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ $sKegiatan == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <select name="penilaian[{{ $s->id }}][skor_kedisiplinan]" class="form-select form-select-sm score-select score-input" data-aspect="kedisiplinan" data-val="{{ $sKedisiplinan }}" onchange="updateRowScore({{ $s->id }})">
                                                    <option value="4" {{ $sKedisiplinan == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ $sKedisiplinan == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ $sKedisiplinan == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ $sKedisiplinan == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold text-primary score-display-nilai" id="nilai_{{ $s->id }}">100.0</div>
                                                <small class="text-muted score-display-total" id="total_{{ $s->id }}">(20/20)</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-live-predikat bg-success-subtle text-success" id="predikat_{{ $s->id }}">
                                                    Sangat Baik
                                                </span>
                                            </td>
                                            <td>
                                                <input type="text" name="penilaian[{{ $s->id }}][catatan_pelanggaran]" class="form-control form-control-sm" placeholder="Catatan pelanggaran jika ada..." value="{{ $sCatatan }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <a href="{{ route('admin.akhlak.index', ['tanggal' => $tanggal]) }}" class="btn btn-light border px-4 py-2 rounded-3">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold">
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        @elseif($kelasId && $santris->count() == 0)
            <div class="card card-main p-5 text-center text-muted">
                <p class="mb-0">Tidak ada santri berstatus Aktif di kelas ini.</p>
            </div>
        @else
            <!-- Pilihan Input Santri Individual jika tidak memilih kelas -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card card-main">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-1">Input Penilaian Perorangan</h5>
                            <p class="text-muted small">Pilih santri untuk input penilaian individu</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.akhlak.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Santri</label>
                                    <select name="santri_id" class="form-select" required>
                                        <option value="">-- Pilih Santri --</option>
                                        @foreach(\App\Models\Santri::where('status', 'Aktif')->with('kelas')->orderBy('nama_lengkap')->get() as $santriItem)
                                            <option value="{{ $santriItem->id }}">
                                                {{ $santriItem->nama_lengkap }} (NIS: {{ $santriItem->no_induk }} - {{ $santriItem->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Guru / Musyrif Penilai</label>
                                        <select name="guru_id" class="form-select">
                                            <option value="">-- Pilih Guru / Musyrif --</option>
                                            @foreach($gurus as $g)
                                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Sumber Penilai</label>
                                        <select name="tipe_penilai" class="form-select" required>
                                            <option value="musyrif" selected>Musyrif Asrama</option>
                                            <option value="guru_tahfidz">Guru Tahfidz</option>
                                            <option value="guru_kbm">Guru KBM Pagi</option>
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
                                        <option value="4" selected>4 - Sangat Baik</option>
                                        <option value="3">3 - Baik</option>
                                        <option value="2">2 - Cukup</option>
                                        <option value="1">1 - Kurang</option>
                                    </select>
                                </div>

                                <!-- 2. Adab -->
                                <div class="mb-3 p-3 bg-light rounded-3">
                                    <label class="fw-bold text-dark mb-1">2. Adab & Pergaulan</label>
                                    <small class="text-muted d-block mb-2">Tidak merokok, tidak pacaran, sopan santun, tidak membully.</small>
                                    <select name="skor_adab" class="form-select" required>
                                        <option value="4" selected>4 - Sangat Baik</option>
                                        <option value="3">3 - Baik</option>
                                        <option value="2">2 - Cukup</option>
                                        <option value="1">1 - Kurang</option>
                                    </select>
                                </div>

                                <!-- 3. Kebersihan -->
                                <div class="mb-3 p-3 bg-light rounded-3">
                                    <label class="fw-bold text-dark mb-1">3. Kebersihan & Tanggung Jawab</label>
                                    <small class="text-muted d-block mb-2">Menjaga kebersihan kamar, kelas, dan fasilitas pondok.</small>
                                    <select name="skor_kebersihan" class="form-select" required>
                                        <option value="4" selected>4 - Sangat Baik</option>
                                        <option value="3">3 - Baik</option>
                                        <option value="2">2 - Cukup</option>
                                        <option value="1">1 - Kurang</option>
                                    </select>
                                </div>

                                <!-- 4. Kegiatan Semaan -->
                                <div class="mb-3 p-3 bg-light rounded-3">
                                    <label class="fw-bold text-dark mb-1">4. Keaktifan Keagamaan (Semaan)</label>
                                    <small class="text-muted d-block mb-2">Keikutsertaan dan keaktifan dalam semaan Al-Qur'an.</small>
                                    <select name="skor_kegiatan" class="form-select" required>
                                        <option value="4" selected>4 - Sangat Baik</option>
                                        <option value="3">3 - Baik</option>
                                        <option value="2">2 - Cukup</option>
                                        <option value="1">1 - Kurang</option>
                                    </select>
                                </div>

                                <!-- 5. Kedisiplinan -->
                                <div class="mb-3 p-3 bg-light rounded-3">
                                    <label class="fw-bold text-dark mb-1">5. Kedisiplinan & Pelanggaran</label>
                                    <small class="text-muted d-block mb-2">Kepatuhan tata tertib pondok pesantren.</small>
                                    <select name="skor_kedisiplinan" class="form-select" required>
                                        <option value="4" selected>4 - Sangat Baik</option>
                                        <option value="3">3 - Baik</option>
                                        <option value="2">2 - Cukup</option>
                                        <option value="1">1 - Kurang</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted">Catatan Pelanggaran / Observasi</label>
                                    <textarea name="catatan_pelanggaran" class="form-control" rows="2" placeholder="Catatan pelanggaran jika ada..."></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-3 fw-bold">
                                        Simpan Penilaian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function updateRowScore(santriId) {
        const row = document.querySelector(`tr[data-santri-id="${santriId}"]`);
        if (!row) return;

        const selects = row.querySelectorAll('.score-input');
        let total = 0;

        selects.forEach(sel => {
            const val = parseInt(sel.value) || 0;
            sel.setAttribute('data-val', val);
            total += val;
        });

        const nilaiAngka = ((total / 20) * 100).toFixed(1);
        const nilaiDisplay = document.getElementById(`nilai_${santriId}`);
        const totalDisplay = document.getElementById(`total_${santriId}`);
        const predikatDisplay = document.getElementById(`predikat_${santriId}`);

        if (nilaiDisplay) nilaiDisplay.textContent = nilaiAngka;
        if (totalDisplay) totalDisplay.textContent = `(${total}/20)`;

        if (predikatDisplay) {
            predikatDisplay.className = 'badge-live-predikat';
            if (nilaiAngka >= 90) {
                predikatDisplay.textContent = 'Sangat Baik';
                predikatDisplay.className = 'badge-live-predikat bg-success-subtle text-success';
            } else if (nilaiAngka >= 80) {
                predikatDisplay.textContent = 'Baik';
                predikatDisplay.className = 'badge-live-predikat bg-info-subtle text-primary';
            } else if (nilaiAngka >= 75) {
                predikatDisplay.textContent = 'Cukup';
                predikatDisplay.className = 'badge-live-predikat bg-warning-subtle text-warning';
            } else {
                predikatDisplay.textContent = 'Kurang';
                predikatDisplay.className = 'badge-live-predikat bg-danger-subtle text-danger';
            }
        }
    }

    function setAllScores(score) {
        document.querySelectorAll('.score-input').forEach(sel => {
            sel.value = score;
            sel.setAttribute('data-val', score);
        });

        document.querySelectorAll('tr[data-santri-id]').forEach(row => {
            const id = row.getAttribute('data-santri-id');
            updateRowScore(id);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('tr[data-santri-id]').forEach(row => {
            const id = row.getAttribute('data-santri-id');
            updateRowScore(id);
        });
    });
</script>
@endsection
