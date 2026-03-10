@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --table-border: #f1f4f9;
        --accent-color: #d97706; /* Orange for Edit */
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #fffdfa, #ffffff);
        min-height: 100vh;
        width: 100%;
        padding-bottom: 50px;
    }

    /* Card Styling */
    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .card-header-premium {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 25px 30px;
    }

    /* Form Styling */
    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .form-control-minimal {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: #fff;
    }

    .form-control-minimal:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.1);
        outline: none;
    }

    /* Table Styling */
    .table-premium thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 15px 10px;
        background: #f8fafc;
        border-bottom: 2px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-premium tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .score-input {
        width: 60px;
        text-align: center;
        font-weight: 700;
        color: var(--accent-color);
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 5px;
    }

    /* Group Header */
    .group-header {
        background: #fef3c7; /* Light amber for groups */
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #92400e;
        letter-spacing: 1px;
    }

    /* Button Styling */
    .btn-update-data {
        background: #92400e;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 40px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-update-data:hover {
        background: #78350f;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        color: white;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 12px;
        color: var(--accent-color);
    }
</style>

<div class="content-wrapper py-5">
    <div class="container-fluid px-4">
        
        <!-- Header Page -->
        <div class="row mb-5 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-2">
                    <a href="{{ route('raport-kmi.index') }}" class="btn btn-sm btn-white shadow-sm rounded-circle me-3 p-2">
                        <i class="bi bi-arrow-left fs-5 text-warning"></i>
                    </a>
                    <h2 class="fw-bold text-dark mb-0">Edit Raport KMI</h2>
                </div>
                <p class="text-muted mb-0 ms-md-5">Memperbarui Data & Nilai: <span class="text-warning fw-bold">{{ $raport->nama_santri }}</span></p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="d-inline-flex bg-white px-4 py-2 rounded-4 shadow-sm border border-warning border-opacity-25">
                    <span class="text-muted small fw-bold">ID RAPORT:</span>
                    <span class="text-warning fw-bold ms-2 small">#KMI-{{ $raport->id }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('raport-kmi.update', $raport->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Data Santri -->
            <div class="card card-main">
                <div class="card-header-premium">
                    <div class="section-title">
                        <i class="bi bi-person-badge-fill"></i> INFORMASI SANTRI & AKADEMIK
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label-custom">Nama Lengkap Santri</label>
                            <input type="text" name="nama_santri" value="{{ $raport->nama_santri }}" required class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Nomor Induk</label>
                            <input type="text" name="no_induk" value="{{ $raport->no_induk }}" required class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Kelas / Jenjang</label>
                            <input type="text" name="kelas" value="{{ $raport->kelas }}" required class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Semester</label>
                            <select name="semester" class="form-select form-control-minimal">
                                <option value="Ganjil" {{ $raport->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil (I)</option>
                                <option value="Genap" {{ $raport->semester == 'Genap' ? 'selected' : '' }}>Genap (II)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tahun Pelajaran</label>
                            <input type="text" name="tahun_pelajaran" value="{{ $raport->tahun_pelajaran }}" class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Lokasi & Tanggal Cetak</label>
                            <input type="text" name="tempat_tanggal_cetak" value="{{ $raport->tempat_tanggal_cetak }}" class="form-control form-control-minimal">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Nilai Mapel -->
            @php
                $subjectsA = [
                    'nahwu' => 'Nahwu', 'mutholaah' => 'Mutholaah', 'shorof' => 'Shorof', 'mahfudzat' => 'Mahfudzat',
                    'reading' => 'Reading', 'dictation' => 'Dictation', 'mufrodzat' => 'Mufrodzat', 'vocabularies' => 'Vocabularies',
                    'akhlak_lil_banin' => 'Akhlak Lil Banin', 'durusuttafsir' => 'Durusuttafsir', 'ushul_fiqh' => 'Ushul Fiqh', 'arbain_nawawi' => 'Arba\'in Nawawi'
                ];
                $subjectsB = [
                    'bahasa_inggris' => 'Bahasa Inggris', 'bahasa_arab' => 'Bahasa Arab', 'praktik_ibadah' => 'Praktik Ibadah'
                ];
            @endphp

            <div class="card card-main overflow-hidden">
                <div class="card-header-premium">
                    <div class="section-title">
                        <i class="bi bi-journal-check"></i> DAFTAR NILAI MATA PELAJARAN
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Mata Pelajaran</th>
                                <th class="text-center">KKM</th>
                                <th class="text-center" colspan="2">Pengetahuan (P)</th>
                                <th class="text-center" colspan="2">Keterampilan (K)</th>
                                <th class="text-center" colspan="2">Sikap (S)</th>
                                <th class="pe-4 text-center">Ketercapaian</th>
                            </tr>
                            <tr class="small text-muted" style="background: #fffbeb;">
                                <th></th>
                                <th></th>
                                <th class="text-center border-start">Angka</th>
                                <th class="text-center border-end">Huruf</th>
                                <th class="text-center">Angka</th>
                                <th class="text-center border-end">Huruf</th>
                                <th class="text-center">Angka</th>
                                <th class="text-center border-end">Huruf</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="group-header"><td colspan="9" class="ps-4 py-2">Kelompok A (Ujian Tulis)</td></tr>
                            @foreach($subjectsA as $key => $name)
                            @php $val = $raport->nilai_mapel[$key] ?? []; @endphp
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $name }}</td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="{{ $val['kkm'] ?? 65 }}" class="score-input input-kkm" style="background: #fffbeb; color: #92400e;">
                                </td>
                                <td class="text-center border-start">
                                    <input type="number" name="nilai_mapel[{{ $key }}][p_a]" value="{{ $val['p_a'] ?? '' }}" class="score-input input-angka" data-target="p_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][p_h]" id="p_h_{{ $key }}" value="{{ $val['p_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][k_a]" value="{{ $val['k_a'] ?? '' }}" class="score-input input-angka" data-target="k_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][k_h]" id="k_h_{{ $key }}" value="{{ $val['k_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][s_a]" value="{{ $val['s_a'] ?? '' }}" class="score-input input-angka" data-target="s_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][s_h]" id="s_h_{{ $key }}" value="{{ $val['s_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="pe-4">
                                    <input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="{{ $val['ketercapaian'] ?? 'Melampaui Kompetensi' }}" class="form-control form-control-sm text-center input-ketercapaian" style="font-size: 0.7rem;">
                                </td>
                            </tr>
                            @endforeach
                            
                            <tr class="group-header"><td colspan="9" class="ps-4 py-2">Kelompok B (Ujian Lisan / Praktik)</td></tr>
                            @foreach($subjectsB as $key => $name)
                            @php $val = $raport->nilai_mapel[$key] ?? []; @endphp
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $name }}</td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="{{ $val['kkm'] ?? 65 }}" class="score-input input-kkm" style="background: #fffbeb; color: #92400e;">
                                </td>
                                <td class="text-center border-start">
                                    <input type="number" name="nilai_mapel[{{ $key }}][p_a]" value="{{ $val['p_a'] ?? '' }}" class="score-input input-angka" data-target="p_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][p_h]" id="p_h_{{ $key }}" value="{{ $val['p_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][k_a]" value="{{ $val['k_a'] ?? '' }}" class="score-input input-angka" data-target="k_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][k_h]" id="k_h_{{ $key }}" value="{{ $val['k_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][s_a]" value="{{ $val['s_a'] ?? '' }}" class="score-input input-angka" data-target="s_h_{{ $key }}">
                                </td>
                                <td class="text-center border-end">
                                    <input type="text" name="nilai_mapel[{{ $key }}][s_h]" id="s_h_{{ $key }}" value="{{ $val['s_h'] ?? '' }}" class="form-control form-control-sm text-center input-huruf" style="width: 80px; font-size: 0.7rem;">
                                </td>
                                <td class="pe-4">
                                    <input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="{{ $val['ketercapaian'] ?? 'Melampaui Kompetensi' }}" class="form-control form-control-sm text-center input-ketercapaian" style="font-size: 0.7rem;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 3: Ekskul & Mental & Absensi -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card card-main h-100">
                        <div class="card-header-premium">
                            <div class="section-title"><i class="bi bi-stars"></i> EKSTRAKURIKULER</div>
                        </div>
                        <div class="card-body p-4">
                            @php $eks = $raport->ekstrakurikuler ?? []; @endphp
                            @for($i = 0; $i < 5; $i++)
                            <div class="row g-2 mb-3">
                                <div class="col-8">
                                    <input type="text" name="ekstrakurikuler[{{ $i }}][nama]" value="{{ $eks[$i]['nama'] ?? '' }}" placeholder="Nama Kegiatan" class="form-control form-control-minimal text-sm p-2">
                                </div>
                                <div class="col-4">
                                    <input type="number" name="ekstrakurikuler[{{ $i }}][nilai]" value="{{ $eks[$i]['nilai'] ?? '' }}" placeholder="100" class="form-control form-control-minimal text-center p-2 fw-bold text-warning">
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-main h-100">
                        <div class="card-header-premium">
                            <div class="section-title"><i class="bi bi-shield-check"></i> MENTAL / KARAKTER</div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label-custom">Moral</label>
                                    <input type="text" name="mental_moral" value="{{ $raport->mental_moral }}" class="form-control form-control-minimal text-center fw-bold text-warning">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kedisiplinan</label>
                                    <input type="text" name="mental_kedisiplinan" value="{{ $raport->mental_kedisiplinan }}" class="form-control form-control-minimal text-center fw-bold text-warning">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kerajinan</label>
                                    <input type="text" name="mental_kerajinan" value="{{ $raport->mental_kerajinan }}" class="form-control form-control-minimal text-center fw-bold text-warning">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kebersihan</label>
                                    <input type="text" name="mental_kebersihan" value="{{ $raport->mental_kebersihan }}" class="form-control form-control-minimal text-center fw-bold text-warning">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-main h-100">
                        <div class="card-header-premium">
                            <div class="section-title"><i class="bi bi-calendar-x"></i> KETIDAKHADIRAN</div>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="d-flex justify-content-around align-items-center h-100 py-3">
                                <div>
                                    <label class="form-label-custom d-block">Sakit</label>
                                    <input type="number" name="sakit" value="{{ $raport->sakit }}" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
                                </div>
                                <div class="border-start border-end px-3">
                                    <label class="form-label-custom d-block">Izin</label>
                                    <input type="number" name="izin" value="{{ $raport->izin }}" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
                                </div>
                                <div>
                                    <label class="form-label-custom d-block">Ghoib</label>
                                    <input type="number" name="ghoib" value="{{ $raport->ghoib }}" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Section: Notes & Signatures -->
            <div class="card card-main">
                <div class="card-header-premium">
                    <div class="section-title"><i class="bi bi-chat-left-text"></i> CATATAN & PENGESAHAN</div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label-custom">Catatan Wali Kelas</label>
                            <textarea name="catatan_wali_kelas" class="form-control form-control-minimal" rows="3">{{ $raport->catatan_wali_kelas }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Wali Kelas</label>
                            <input type="text" name="wali_kelas_nama" value="{{ $raport->wali_kelas_nama }}" required class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Kepala Madrasah</label>
                            <input type="text" name="kepala_madrasah_nama" value="{{ $raport->kepala_madrasah_nama }}" required class="form-control form-control-minimal">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 p-4 text-center">
                    <button type="submit" class="btn btn-update-data shadow-sm">
                        <i class="bi bi-save-fill me-2"></i> SIMPAN PERUBAHAN DATA RAPORT
                    </button>
                    <div class="mt-3">
                        <a href="{{ route('raport-kmi.index') }}" class="btn btn-link text-muted text-decoration-none small">
                            <i class="bi bi-x-circle me-1"></i> BATALKAN PERUBAHAN
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function terbilangIndo(n) {
        if (n === "" || n === null) return "";
        let labels = ["Nol", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        let result = "";
        
        n = parseInt(n);
        if (n < 12) result = labels[n];
        else if (n < 20) result = terbilangIndo(n - 10) + " Belas";
        else if (n < 100) result = terbilangIndo(Math.floor(n / 10)) + " Puluh " + (n % 10 !== 0 ? terbilangIndo(n % 10) : "");
        else if (n === 100) result = "Seratus";
        
        return result.trim();
    }

    document.querySelectorAll('.input-angka').forEach(input => {
        input.addEventListener('input', function() {
            let val = this.value;
            let targetId = this.dataset.target;
            let huruField = document.getElementById(targetId);
            
            // Auto Huruf
            if (huruField) {
                huruField.value = terbilangIndo(val);
            }
            
            // Auto Ketercapaian
            let row = this.closest('tr');
            let kkm = parseInt(row.querySelector('.input-kkm').value) || 65;
            let keteField = row.querySelector('.input-ketercapaian');
            
            if (keteField && val !== "") {
                if (parseInt(val) >= kkm) {
                    keteField.value = "Melampaui Kompetensi";
                } else {
                    keteField.value = "Perlu Bimbingan";
                }
            }
        });
    });

    // Validasi KKM - Update ketercapaian jika KKM diubah
    document.querySelectorAll('.input-kkm').forEach(kkmInput => {
        kkmInput.addEventListener('input', function() {
            let row = this.closest('tr');
            let val = row.querySelector('.input-angka').value;
            let kkm = parseInt(this.value) || 0;
            let keteField = row.querySelector('.input-ketercapaian');
            
            if (keteField && val !== "") {
                if (parseInt(val) >= kkm) {
                    keteField.value = "Melampaui Kompetensi";
                } else {
                    keteField.value = "Perlu Bimbingan";
                }
            }
        });
    });
</script>
@endsection
