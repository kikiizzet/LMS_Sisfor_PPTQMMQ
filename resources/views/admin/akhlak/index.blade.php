@extends('layout')

@section('main-content')
<style>
    .stat-card {
        border-radius: 16px;
        padding: 20px 22px;
        border: 1px solid #e2e8f0;
        background: #fff;
        height: 100%;
        transition: box-shadow 0.2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }
    .badge-predikat {
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.78rem;
        display: inline-block;
    }
    .badge-sangat-baik { background: #dcfce7; color: #166534; }
    .badge-baik { background: #e0f2fe; color: #0369a1; }
    .badge-cukup { background: #fef9c3; color: #854d0e; }
    .badge-kurang { background: #fee2e2; color: #991b1b; }

    .score-pill {
        display: inline-block;
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
    }
    .score-pill.score-4 { background: #dcfce7; color: #166534; }
    .score-pill.score-3 { background: #e0f2fe; color: #075985; }
    .score-pill.score-2 { background: #fef9c3; color: #854d0e; }
    .score-pill.score-1 { background: #fee2e2; color: #991b1b; }

    [data-theme="dark"] .stat-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .badge-sangat-baik { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    [data-theme="dark"] .badge-baik { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }
    [data-theme="dark"] .badge-cukup { background: rgba(234, 179, 8, 0.2); color: #facc15; }
    [data-theme="dark"] .badge-kurang { background: rgba(239, 68, 68, 0.2); color: #f87171; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-4 py-4">

        <!-- Header Halaman -->
        <div class="page-header">
            <div class="page-header-left">
                <h1>Penilaian Akhlak Santri</h1>
                <p>Rekam jejak perilaku dan kedisiplinan santri harian</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.akhlak.rekap') }}" class="btn btn-light border px-3 py-2 rounded-3 fw-semibold">
                    Rekapitulasi Nilai
                </a>
                <a href="{{ route('admin.akhlak.create') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                    Input Nilai
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 d-flex align-items-center mb-4" role="alert">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Kartu Ringkasan -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Santri Dinilai</span>
                    <h3 class="fw-bold mb-1 text-dark">{{ $totalSantriHariIni }} <span class="fs-6 text-muted fw-normal">santri</span></h3>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}</small>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Rata-rata Nilai</span>
                    <h3 class="fw-bold mb-1 text-dark">{{ $rataRataHariIni ? number_format($rataRataHariIni, 1) : '0' }} <span class="fs-6 text-muted fw-normal">/ 100</span></h3>
                    <small class="text-muted">Rata-rata skor hari ini</small>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Sangat Baik & Baik</span>
                    <h3 class="fw-bold mb-1 text-success">{{ $jumlahSangatBaik + $jumlahBaik }} <span class="fs-6 text-muted fw-normal">santri</span></h3>
                    <small class="text-muted">{{ $jumlahSangatBaik }} Sangat Baik, {{ $jumlahBaik }} Baik</small>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Perlu Pembinaan</span>
                    <h3 class="fw-bold mb-1 {{ ($jumlahCukup + $jumlahKurang) > 0 ? 'text-danger' : 'text-dark' }}">{{ $jumlahCukup + $jumlahKurang }} <span class="fs-6 text-muted fw-normal">santri</span></h3>
                    <small class="text-muted">{{ $jumlahCukup }} Cukup, {{ $jumlahKurang }} Kurang</small>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card card-main mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.akhlak.index') }}" class="row g-3 align-items-center">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Kelas</label>
                        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Cari Santri</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Nama atau No Induk santri..." value="{{ $search }}">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        </div>
                    </div>

                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.akhlak.index', ['tanggal' => date('Y-m-d')]) }}" class="btn btn-light border w-100 py-2 fw-semibold">
                            Hari Ini
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data Penilaian Harian -->
        <div class="card card-main">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Data Penilaian: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h5>
                    <small class="text-muted">Total {{ $penilaians->total() }} santri</small>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium w-100 mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Santri</th>
                                <th>Kelas</th>
                                <th class="text-center">Ibadah</th>
                                <th class="text-center">Adab</th>
                                <th class="text-center">Kebersihan</th>
                                <th class="text-center">Kegiatan</th>
                                <th class="text-center">Disiplin</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Predikat</th>
                                <th class="text-center">Fuzzy C3</th>
                                <th>Catatan / Penilai</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penilaians as $index => $item)
                                <tr>
                                    <td class="text-center text-muted small">{{ $penilaians->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->santri->nama_lengkap ?? '-' }}</div>
                                        <small class="text-muted">NIS: {{ $item->santri->no_induk ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-kelas">{{ $item->santri->kelas->nama_kelas ?? '-' }}</span>
                                    </td>
                                    <td class="text-center"><span class="score-pill score-{{ $item->skor_ibadah }}">{{ $item->skor_ibadah }}</span></td>
                                    <td class="text-center"><span class="score-pill score-{{ $item->skor_adab }}">{{ $item->skor_adab }}</span></td>
                                    <td class="text-center"><span class="score-pill score-{{ $item->skor_kebersihan }}">{{ $item->skor_kebersihan }}</span></td>
                                    <td class="text-center"><span class="score-pill score-{{ $item->skor_kegiatan }}">{{ $item->skor_kegiatan }}</span></td>
                                    <td class="text-center"><span class="score-pill score-{{ $item->skor_kedisiplinan }}">{{ $item->skor_kedisiplinan }}</span></td>
                                    <td class="text-center fw-bold">{{ $item->total_skor }} <small class="text-muted">/20</small></td>
                                    <td class="text-center fw-bold text-primary">{{ number_format($item->nilai_angka, 1) }}</td>
                                    <td class="text-center">
                                        @if($item->predikat == 'Sangat Baik')
                                            <span class="badge-predikat badge-sangat-baik">Sangat Baik</span>
                                        @elseif($item->predikat == 'Baik')
                                            <span class="badge-predikat badge-baik">Baik</span>
                                        @elseif($item->predikat == 'Cukup')
                                            <span class="badge-predikat badge-cukup">Cukup</span>
                                        @else
                                            <span class="badge-predikat badge-kurang">Kurang</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted fw-semibold">
                                        {{ number_format($item->nilai_fuzzy, 1) }}
                                    </td>
                                    <td>
                                        @if($item->catatan_pelanggaran)
                                            <div class="small text-danger">{{ $item->catatan_pelanggaran }}</div>
                                        @else
                                            <div class="small text-muted">-</div>
                                        @endif
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">
                                            {{ $item->guru->nama ?? ucfirst(str_replace('_', ' ', $item->tipe_penilai)) }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.akhlak.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('admin.akhlak.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data penilaian santri ini?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-5 text-muted">
                                        Belum ada data penilaian akhlak pada tanggal ini.
                                        <div class="mt-2">
                                            <a href="{{ route('admin.akhlak.create', ['tanggal' => $tanggal]) }}" class="btn btn-sm btn-primary">
                                                Input Nilai Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($penilaians->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $penilaians->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
