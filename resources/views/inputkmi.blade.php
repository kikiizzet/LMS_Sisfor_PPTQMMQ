@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        --glass-bg: rgba(255, 255, 255, 0.95);
        --table-border: #f1f4f9;
        --accent-color: #0ea5e9;
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f8fafc, #ffffff);
        min-height: 100vh;
        width: 100%;
        padding-bottom: 50px;
        transition: background 0.3s ease;
    }

    [data-theme="dark"] .content-wrapper {
        background: radial-gradient(circle at top right, #0f172a, #1e293b);
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
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
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
        background: #f1f5f9;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #475569;
        letter-spacing: 1px;
    }

    /* Button Styling */
    .btn-save-data {
        background: #1e293b;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 40px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save-data:hover {
        background: #0f172a;
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
        transition: color 0.3s ease;
    }

    [data-theme="dark"] .section-title {
        color: #f1f5f9;
    }

    .section-title i {
        margin-right: 12px;
        color: var(--accent-color);
    }

    /* Dark Mode specific overrides */
    [data-theme="dark"] .form-label-custom {
        color: #94a3b8;
    }
    [data-theme="dark"] .card-header-premium {
        border-bottom-color: #334155;
    }
    [data-theme="dark"] .table-premium thead th {
        background: #1e293b;
        color: #94a3b8;
        border-bottom-color: #334155;
    }
    [data-theme="dark"] .table-premium tbody td {
        border-bottom-color: #334155;
    }
    [data-theme="dark"] .group-header {
        background: #334155;
        color: #cbd5e1;
    }
    [data-theme="dark"] .score-input {
        background: #0f172a;
        border-color: #334155;
        color: #38bdf8;
    }
    [data-theme="dark"] .btn-white {
        background: #1e293b;
        border-color: #334155;
        color: #f1f5f9;
    }
    [data-theme="dark"] .bg-white {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
</style>

<div class="content-wrapper py-3 py-lg-5">
    <div class="container-fluid px-3 px-lg-4">
        
        <!-- Header Page -->
        <div class="row mb-4 align-items-center text-center text-md-start">
            <div class="col-md-7 mb-3 mb-md-0">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-2">
                    <a href="{{ route('raport-kmi.index') }}" class="btn btn-sm btn-white shadow-sm rounded-circle me-3 p-2">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <h2 class="fw-bold text-dark mb-0" style="font-size: clamp(1.2rem, 4vw, 1.75rem);">Input Nilai KMI</h2>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill ms-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Bulk Import Excel
                    </button>
                </div>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="d-inline-flex bg-white px-3 py-1.5 rounded-3 shadow-sm border">
                    <span class="text-info fw-bold small">KMI MMQ v.1.0</span>
                </div>
            </div>
        </div>

        <form action="{{ route('raport-kmi.store') }}" method="POST">
            @csrf
            
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
                            <input type="text" name="nama_santri" required class="form-control form-control-minimal" placeholder="Contoh: Ahmad Fauzi">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Nomor Induk</label>
                            <input type="text" name="no_induk" required class="form-control form-control-minimal" placeholder="Contoh: 2023001">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Kelas / Jenjang</label>
                            <input type="text" name="kelas" required class="form-control form-control-minimal" placeholder="Contoh: 1 KMI">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Semester</label>
                            <select name="semester" class="form-select form-control-minimal">
                                <option value="Ganjil">Ganjil (I)</option>
                                <option value="Genap">Genap (II)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tahun Pelajaran</label>
                            <input type="text" name="tahun_pelajaran" value="2023/2024" class="form-control form-control-minimal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Lokasi & Tanggal Cetak</label>
                            <input type="text" name="tempat_tanggal_cetak" placeholder="Pacitan, 20 Desember 2025" class="form-control form-control-minimal">
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
                            <tr class="text-nowrap">
                                <th class="ps-3">Mata Pelajaran</th>
                                <th class="text-center">KKM</th>
                                <th class="text-center">Pengetahuan</th>
                                <th class="text-center">Keterampilan</th>
                                <th class="text-center d-none d-lg-table-cell">Sikap</th>
                                <th class="pe-3 text-center d-none d-md-table-cell">Ketercapaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="group-header"><td colspan="6" class="ps-3 py-2">Kelompok A (Ujian Tulis)</td></tr>
                            @foreach($subjectsA as $key => $name)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark small">{{ $name }}</div>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="65" class="score-input input-kkm" style="background: #f1f5f9; color: #475569; width: 45px; font-size: 0.75rem;">
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][p_a]" class="score-input input-angka" data-target="p_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][p_h]" id="p_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][k_a]" class="score-input input-angka" data-target="k_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][k_h]" id="k_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="text-center d-none d-lg-table-cell">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][s_a]" class="score-input input-angka" data-target="s_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][s_h]" id="s_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="pe-3 d-none d-md-table-cell">
                                    <input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="Melampaui Kompetensi" class="form-control form-control-sm text-center input-ketercapaian" style="font-size: 0.65rem;">
                                </td>
                            </tr>
                            @endforeach
                            
                            <tr class="group-header"><td colspan="6" class="ps-3 py-2">Kelompok B (Ujian Lisan / Praktik)</td></tr>
                            @foreach($subjectsB as $key => $name)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark small">{{ $name }}</div>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="65" class="score-input input-kkm" style="background: #f1f5f9; color: #475569; width: 45px; font-size: 0.75rem;">
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][p_a]" class="score-input input-angka" data-target="p_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][p_h]" id="p_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][k_a]" class="score-input input-angka" data-target="k_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][k_h]" id="k_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="text-center d-none d-lg-table-cell">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <input type="number" name="nilai_mapel[{{ $key }}][s_a]" class="score-input input-angka" data-target="s_h_{{ $key }}" style="width: 45px; font-size: 0.75rem;">
                                        <input type="hidden" name="nilai_mapel[{{ $key }}][s_h]" id="s_h_{{ $key }}">
                                    </div>
                                </td>
                                <td class="pe-3 d-none d-md-table-cell">
                                    <input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="Melampaui Kompetensi" class="form-control form-control-sm text-center input-ketercapaian" style="font-size: 0.65rem;">
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
                            @for($i = 0; $i < 5; $i++)
                            <div class="row g-2 mb-3">
                                <div class="col-8">
                                    <input type="text" name="ekstrakurikuler[{{ $i }}][nama]" placeholder="Nama Kegiatan" class="form-control form-control-minimal text-sm p-2">
                                </div>
                                <div class="col-4">
                                    <input type="number" name="ekstrakurikuler[{{ $i }}][nilai]" placeholder="100" class="form-control form-control-minimal text-center p-2 fw-bold text-primary">
                                </div>
                            </div>
                            @endfor
                            <p class="text-muted small mt-2 italic"><i class="bi bi-info-circle me-1"></i> Maksimal 5 kegiatan.</p>
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
                                    <input type="text" name="mental_moral" value="A" class="form-control form-control-minimal text-center fw-bold text-success">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kedisiplinan</label>
                                    <input type="text" name="mental_kedisiplinan" value="A" class="form-control form-control-minimal text-center fw-bold text-success">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kerajinan</label>
                                    <input type="text" name="mental_kerajinan" value="A" class="form-control form-control-minimal text-center fw-bold text-success">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Kebersihan</label>
                                    <input type="text" name="mental_kebersihan" value="A" class="form-control form-control-minimal text-center fw-bold text-success">
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
                                    <input type="number" name="sakit" value="0" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
                                </div>
                                <div class="border-start border-end px-3">
                                    <label class="form-label-custom d-block">Izin</label>
                                    <input type="number" name="izin" value="0" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
                                </div>
                                <div>
                                    <label class="form-label-custom d-block">Ghoib</label>
                                    <input type="number" name="ghoib" value="0" class="form-control form-control-minimal text-center fw-bold" style="width: 70px; margin: 0 auto;">
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
                            <textarea name="catatan_wali_kelas" class="form-control form-control-minimal" rows="3" placeholder="Tuliskan perkembangan santri selama satu semester ini..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Wali Kelas (Tanpa Gelar)</label>
                            <input type="text" name="wali_kelas_nama" required class="form-control form-control-minimal" placeholder="Contoh: Muhammad Yusuf">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Kepala Madrasah (Tanpa Gelar)</label>
                            <input type="text" name="kepala_madrasah_nama" required class="form-control form-control-minimal" placeholder="Contoh: Husain Al-Munawwar">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 p-4 text-center">
                    <button type="submit" class="btn btn-save-data shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> SIMPAN RAPORT KMI KE DATABASE
                    </button>
                    <div class="mt-3">
                        <button type="reset" class="btn btn-link text-muted text-decoration-none small">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> RESET SEMUA FORM
                        </button>
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

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="importModalLabel">Import Nilai via Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <!-- STEP 1: Upload & Preview -->
                <div id="importStep1">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small uppercase mb-2">Step 1: Download Template</label>
                        <a href="{{ route('raport-kmi.download-template') }}" class="btn btn-outline-info w-100 rounded-3 py-2">
                            <i class="bi bi-download me-2"></i> Download Template Excel
                        </a>
                        <p class="text-muted small mt-2 italic">Isi data di Excel/Google Sheets, lalu simpan dan upload kembali.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small uppercase mb-2">Step 2: Upload File Excel</label>
                        <input type="file" id="importFileInput" accept=".xml,.xls,.csv" class="form-control form-control-minimal">
                    </div>
                    <div class="mt-3 p-3 bg-light rounded-3 border">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-info-circle-fill text-info me-1"></i> Tips</h6>
                        <ul class="text-muted small ps-3 mb-0">
                            <li>Pastikan header kolom tidak diubah.</li>
                            <li>Format nilai menggunakan angka murni.</li>
                        </ul>
                    </div>
                </div>

                <!-- STEP 2: Preview Confirmation -->
                <div id="importStep2" style="display:none;">
                    <div class="alert alert-success border-0 rounded-3 mb-3">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong id="importCountLabel">0 santri</strong> terdeteksi. Cek daftar nama di bawah sebelum konfirmasi.
                    </div>
                    <div style="max-height: 250px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr><th class="ps-3">#</th><th>Nama Santri</th></tr>
                            </thead>
                            <tbody id="importNamesList"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Loading -->
                <div id="importLoading" style="display:none;" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2">Sedang membaca file...</p>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" id="importBtnBack" style="display:none;" onclick="importGoBack()">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </button>
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" id="importBtnBatal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="importBtnPreview" onclick="importPreview()">
                    <i class="bi bi-eye me-1"></i> Cek Data
                </button>
                <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" id="importBtnConfirm" style="display:none;" onclick="importConfirm()">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Konfirmasi Import
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for actual import -->
<form id="importConfirmForm" action="{{ route('raport-kmi.import') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="temp_path" id="importTempPath">
    <input type="hidden" name="file_extension" id="importFileExtension">
</form>

<script>
function importPreview() {
    const fileInput = document.getElementById('importFileInput');
    if (!fileInput.files.length) {
        alert('Pilih file terlebih dahulu!');
        return;
    }

    document.getElementById('importStep1').style.display = 'none';
    document.getElementById('importLoading').style.display = 'block';
    document.getElementById('importBtnPreview').style.display = 'none';
    document.getElementById('importBtnBatal').style.display = 'none';

    const formData = new FormData();
    formData.append('file_csv', fileInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("raport-kmi.preview-import") }}', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('importLoading').style.display = 'none';
        document.getElementById('importStep2').style.display = 'block';
        document.getElementById('importBtnBack').style.display = 'inline-block';
        document.getElementById('importBtnConfirm').style.display = 'inline-block';

        document.getElementById('importCountLabel').textContent = data.count + ' santri';
        document.getElementById('importTempPath').value = data.temp_path;
        document.getElementById('importFileExtension').value = data.original_extension;

        const tbody = document.getElementById('importNamesList');
        tbody.innerHTML = '';
        data.names.forEach((name, idx) => {
            tbody.innerHTML += `<tr><td class="ps-3 text-muted">${idx+1}</td><td class="fw-semibold">${name}</td></tr>`;
        });
    })
    .catch(() => {
        document.getElementById('importLoading').style.display = 'none';
        document.getElementById('importStep1').style.display = 'block';
        document.getElementById('importBtnPreview').style.display = 'inline-block';
        document.getElementById('importBtnBatal').style.display = 'inline-block';
        alert('Gagal membaca file. Pastikan format file benar.');
    });
}

function importGoBack() {
    document.getElementById('importStep2').style.display = 'none';
    document.getElementById('importStep1').style.display = 'block';
    document.getElementById('importBtnBack').style.display = 'none';
    document.getElementById('importBtnConfirm').style.display = 'none';
    document.getElementById('importBtnPreview').style.display = 'inline-block';
    document.getElementById('importBtnBatal').style.display = 'inline-block';
}

function importConfirm() {
    document.getElementById('importConfirmForm').submit();
}
</script>
@endsection
