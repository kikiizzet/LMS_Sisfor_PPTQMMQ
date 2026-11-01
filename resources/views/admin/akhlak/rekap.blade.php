@extends('layout')

@section('main-content')
<style>
    .card-main {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
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

    @media print {
        #sidebar, .top-navbar, .no-print {
            display: none !important;
        }
        #content, .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: #fff !important;
        }
        .card-main {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-4 py-4">

        <!-- Header -->
        <div class="page-header no-print">
            <div class="page-header-left">
                <h1>Rekapitulasi Nilai Akhlak</h1>
                <p>Akumulasi penilaian mutabaah santri per periode</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button onclick="window.print()" class="btn btn-outline-secondary px-3 py-2 rounded-3">
                    Cetak Rekap
                </button>
                <a href="{{ route('admin.akhlak.create') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold">
                    Input Nilai Harian
                </a>
            </div>
        </div>

        <!-- Filter Periode Bulan & Tahun -->
        <div class="card card-main mb-4 no-print">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.akhlak.rekap') }}" class="row g-3 align-items-center">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Bulan</label>
                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-bold text-muted mb-1">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Kelas</label>
                        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3 d-flex align-items-end">
                        <a href="{{ route('admin.akhlak.index') }}" class="btn btn-light border w-100 py-2">
                            Kembali ke Harian
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Notifikasi sukses/gagal --}}
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 py-2 px-3 mb-3 no-print" style="background:#dcfce7; color:#166534;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 py-2 px-3 mb-3 no-print" style="background:#fee2e2; color:#991b1b;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Panel Sinkronisasi ke Raport KMI --}}
        <div class="card card-main mb-4 no-print" style="border-left: 4px solid #3b82f6;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-1">Sinkronisasi ke Raport KMI</h6>
                        <small class="text-muted">
                            Konversi rata-rata nilai akhlak harian → kolom Mental/Karakter di Raport KMI.<br>
                            Mapping: <strong>Adab → Moral</strong>, <strong>Kedisiplinan → Kedisiplinan</strong>,
                            <strong>Ibadah+Kegiatan → Kerajinan</strong>, <strong>Kebersihan → Kebersihan</strong>.
                            Hanya santri yang sudah punya data raport KMI di semester tersebut yang diperbarui.
                        </small>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.akhlak.sinkron-raport-kmi') }}"
                      onsubmit="return confirm('Yakin ingin sinkronisasi nilai akhlak ke Raport KMI? Nilai Mental/Karakter yang sudah ada akan diganti.')">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-bold text-muted mb-1">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-bold text-muted mb-1">Tahun Pelajaran</label>
                            <input type="text" name="tahun_pelajaran" class="form-control"
                                   placeholder="Contoh: 2025/2026"
                                   value="{{ date('Y') . '/' . (date('Y') + 1) }}" required>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-bold text-muted mb-1">Periode Awal</label>
                            <input type="date" name="periode_awal" class="form-control"
                                   value="{{ date('Y-m') . '-01' }}" required>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-bold text-muted mb-1">Periode Akhir</label>
                            <input type="date" name="periode_akhir" class="form-control"
                                   value="{{ date('Y-m-t') }}" required>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                Sinkron ke Raport KMI
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Rekapitulasi --}}
        <div class="card card-main">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    </h5>
                    <small class="text-muted">Total {{ count($rekapData) }} santri terdata</small>
                </div>
                <small class="text-muted no-print">Konversi fuzzy: Kurang (37.5), Cukup (77.5), Baik (85.0), Sangat Baik (95.0)</small>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium w-100 mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Santri</th>
                                <th>Kelas</th>
                                <th class="text-center">Hari Dinilai</th>
                                <th class="text-center">Rata-rata Nilai</th>
                                <th class="text-center">Predikat</th>
                                <th class="text-center">Nilai Fuzzy C3</th>
                                <th class="text-center">Catatan Pelanggaran</th>
                                <th class="text-center no-print" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapData as $index => $item)
                                <tr>
                                    <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->santri->nama_lengkap }}</div>
                                        <small class="text-muted">NIS: {{ $item->santri->no_induk }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-kelas">{{ $item->santri->kelas->nama_kelas ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->total_hari > 0)
                                            <span class="text-dark">{{ $item->total_hari }} hari</span>
                                        @else
                                            <span class="text-muted small">0 hari</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        {{ $item->total_hari > 0 ? number_format($item->rata_nilai_angka, 2) : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if($item->predikat == 'Sangat Baik')
                                            <span class="badge-predikat badge-sangat-baik">Sangat Baik</span>
                                        @elseif($item->predikat == 'Baik')
                                            <span class="badge-predikat badge-baik">Baik</span>
                                        @elseif($item->predikat == 'Cukup')
                                            <span class="badge-predikat badge-cukup">Cukup</span>
                                        @elseif($item->predikat == 'Kurang')
                                            <span class="badge-predikat badge-kurang">Kurang</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold">
                                        @if($item->total_hari > 0)
                                            {{ number_format($item->fuzzy_crisp, 1) }}
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->jumlah_pelanggaran > 0)
                                            <span class="text-danger small">{{ $item->jumlah_pelanggaran }} catatan</span>
                                        @else
                                            <span class="text-muted small">Nihil</span>
                                        @endif
                                    </td>
                                    <td class="text-center no-print">
                                        <a href="{{ route('admin.akhlak.index', ['search' => $item->santri->nama_lengkap]) }}" class="btn btn-sm btn-light border" title="Lihat riwayat">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Tidak ada data santri ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
