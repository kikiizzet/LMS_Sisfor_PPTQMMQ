@extends('layout')

@section('main-content')
<style>
    .grid-input-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
        transition: background 0.3s ease;
    }
    [data-theme="dark"] .grid-input-wrapper {
        background: #0f172a;
    }
    .score-cell {
        width: 80px;
        text-align: center;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 5px;
        font-weight: 600;
        transition: all 0.2s;
    }
    [data-theme="dark"] .score-cell {
        background: #1e293b;
        border-color: #334155;
        color: #38bdf8;
    }
    .score-cell:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .saving-indicator {
        font-size: 0.75rem;
        color: #64748b;
        display: none;
    }
    [data-theme="dark"] .saving-indicator {
        color: #94a3b8;
    }
    .status-success { color: #10b981; }
    .status-error { color: #ef4444; }

    /* Dark Mode  */
    [data-theme="dark"] .card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] thead.bg-light,
    [data-theme="dark"] .bg-light {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .text-dark {
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .modal-content {
        background: #1e293b;
        color: #f1f5f9;
    }
    [data-theme="dark"] .form-select {
        background-color: #1e293b;
        color: #f1f5f9;
        border-color: #334155;
    }
</style>
//p
<div class="grid-input-wrapper">
    <div class="container-fluid px-lg-5">
        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold text-dark mb-1">Smart Grid KMI</h1>
                <p class="text-muted">Input nilai masal per mata pelajaran (Auto-Save)</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex justify-content-md-end align-items-end gap-3">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm mb-1" data-bs-toggle="modal" data-bs-target="#addSantriModal">
                        <i class="bi bi-person-plus-fill me-2"></i> Tambah Santri
                    </button>
                    <div class="text-start">
                        <label class="small fw-bold text-muted uppercase mb-1 d-block">Pilih Mata Pelajaran</label>
                        <select id="mapelSelector" class="form-select border-0 shadow-sm rounded-3" style="min-width: 250px;">
                            @foreach($mapels as $m)
                                <option value="{{ $m }}">{{ ucfirst(str_replace('_', ' ', $m)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="gridTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small uppercase fw-bold" style="width: 50px;">No</th>
                            <th class="py-3 text-muted small uppercase fw-bold">Nama Santri</th>
                            <th class="text-center py-3 text-muted small uppercase fw-bold">Pengetahuan (P)</th>
                            <th class="text-center py-3 text-muted small uppercase fw-bold">Keterampilan (K)</th>
                            <th class="text-center py-3 text-muted small uppercase fw-bold">Sikap (S)</th>
                            <th class="text-center py-3 text-muted small uppercase fw-bold">Status</th>
                            <th class="text-center py-3 text-muted small uppercase fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="gridBody">
                        @foreach($raport_kmis as $index => $raport)
                        <tr data-id="{{ $raport->id }}">
                            <td class="ps-4 text-muted small counter">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark text-uppercase">{{ $raport->nama_santri }}</div>
                                <div class="text-muted small" style="font-size: 0.7rem;">NI: {{ $raport->no_induk }} | {{ $raport->kelas }}</div>
                            </td>
                            <td class="text-center">
                                <input type="number" class="score-cell ajax-input" data-type="p_a" value="{{ $raport->nilai_mapel['nahwu']['p_a'] ?? 0 }}">
                            </td>
                            <td class="text-center">
                                <input type="number" class="score-cell ajax-input" data-type="k_a" value="{{ $raport->nilai_mapel['nahwu']['k_a'] ?? 0 }}">
                            </td>
                            <td class="text-center">
                                <input type="number" class="score-cell ajax-input" data-type="s_a" value="{{ $raport->nilai_mapel['nahwu']['s_a'] ?? 0 }}">
                            </td>
                            <td class="text-center">
                                <span class="saving-indicator"><i class="bi bi-clock"></i> Saving...</span>
                                <span class="success-indicator d-none status-success"><i class="bi bi-check-circle-fill"></i> Saved</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 btn-detail" data-id="{{ $raport->id }}">
                                    <i class="bi bi-list-check me-1"></i> Lengkapi
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lengkapi Data Santri -->
<div class="modal fade" id="detailSantriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-0">Lengkapi Data Santri</h5>
                    <p class="text-muted small mb-0" id="detailSantriHeader">Nama Santri | Kelas</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="detailSantriForm">
                <input type="hidden" name="id" id="detailId">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Absensi -->
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-4 h-100">
                                <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Ketidakhadiran</h6>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="small fw-bold">Sakit</label>
                                        <input type="number" name="sakit" class="form-control form-control-minimal text-center" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="small fw-bold">Izin</label>
                                        <input type="number" name="izin" class="form-control form-control-minimal text-center" value="0">
                                    </div>
                                    <div class="col-4">
                                        <label class="small fw-bold">Ghoib</label>
                                        <input type="number" name="ghoib" class="form-control form-control-minimal text-center" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mental / Karakter -->
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-4 h-100">
                                <h6 class="fw-bold mb-3"><i class="bi bi-heart me-2 text-danger"></i>Mental / Karakter</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="small fw-bold">Moral</label>
                                        <select name="mental_moral" class="form-select form-control-minimal">
                                            <option value="A">A (Sangat Baik)</option>
                                            <option value="B">B (Baik)</option>
                                            <option value="C">C (Cukup)</option>
                                            <option value="D">D (Kurang)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small fw-bold">Disiplin</label>
                                        <select name="mental_kedisiplinan" class="form-select form-control-minimal">
                                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small fw-bold">Rajin</label>
                                        <select name="mental_kerajinan" class="form-select form-control-minimal">
                                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small fw-bold">Bersih</label>
                                        <select name="mental_kebersihan" class="form-select form-control-minimal">
                                            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan & Ekskul -->
                        <div class="col-12">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded-4">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-star me-2 text-warning"></i>Ekstrakurikuler</h6>
                                        <textarea name="ekstrakurikuler" class="form-control form-control-minimal" rows="3" placeholder="Contoh: Pramuka, Tapak Suci, dll..."></textarea>
                                        <small class="text-muted" style="font-size: 0.65rem;">Pisahkan dengan koma</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded-4">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2 text-success"></i>Catatan Wali Kelas</h6>
                                        <textarea name="catatan_wali_kelas" class="form-control form-control-minimal" rows="3" placeholder="Berikan catatan perkembangan santri..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm" id="btnSaveDetail">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Santri -->
<div class="modal fade" id="addSantriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Santri Baru (KMI)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSantriForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_santri" required class="form-control form-control-minimal" placeholder="Contoh: Ahmad Abdullah">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">No Induk</label>
                            <input type="text" name="no_induk" required class="form-control form-control-minimal" placeholder="B-202X-...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Kelas</label>
                            <input type="text" name="kelas" required class="form-control form-control-minimal" placeholder="Contoh: 1-KMI">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Semester</label>
                            <select name="semester" class="form-select form-control-minimal">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Tahun Pelajaran</label>
                            <input type="text" name="tahun_pelajaran" required class="form-control form-control-minimal" value="2023/2024">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSubmitSantri">Simpan & Mulai Input</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let raportData = @json($raport_kmis);
    const csrfToken = '{{ csrf_token() }}';

    document.getElementById('mapelSelector').addEventListener('change', function() {
        let selectedMapel = this.value;
        const rows = document.querySelectorAll('#gridTable tbody tr');
        
        rows.forEach(row => {
            let id = row.dataset.id;
            let record = raportData.find(r => r.id == id);
            
            if (record && record.nilai_mapel && record.nilai_mapel[selectedMapel]) {
                row.querySelector('[data-type="p_a"]').value = record.nilai_mapel[selectedMapel].p_a || 0;
                row.querySelector('[data-type="k_a"]').value = record.nilai_mapel[selectedMapel].k_a || 0;
                row.querySelector('[data-type="s_a"]').value = record.nilai_mapel[selectedMapel].s_a || 0;
            } else {
                row.querySelector('[data-type="p_a"]').value = 0;
                row.querySelector('[data-type="k_a"]').value = 0;
                row.querySelector('[data-type="s_a"]').value = 0;
            }
        });
    });

    // Event Delegation untuk Tombol Detail
    document.getElementById('gridBody').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-detail');
        if (btn) {
            const id = btn.dataset.id;
            const record = raportData.find(r => r.id == id);
            if (record) openDetailModal(record);
        }
    });

    function openDetailModal(r) {
        document.getElementById('detailId').value = r.id;
        document.getElementById('detailSantriHeader').innerText = `${r.nama_santri} | ${r.kelas}`;
        
        const form = document.getElementById('detailSantriForm');
        form.sakit.value = r.sakit || 0;
        form.izin.value = r.izin || 0;
        form.ghoib.value = r.ghoib || 0;
        form.mental_moral.value = r.mental_moral || 'A';
        form.mental_kedisiplinan.value = r.mental_kedisiplinan || 'A';
        form.mental_kerajinan.value = r.mental_kerajinan || 'A';
        form.mental_kebersihan.value = r.mental_kebersihan || 'A';
        form.catatan_wali_kelas.value = r.catatan_wali_kelas || '';
        
        // Handle Ekskul Array to String
        if (Array.isArray(r.ekstrakurikuler)) {
            form.ekstrakurikuler.value = r.ekstrakurikuler.join(', ');
        } else {
            form.ekstrakurikuler.value = r.ekstrakurikuler || '';
        }
        
        new bootstrap.Modal(document.getElementById('detailSantriModal')).show();
    }

    // Submit Detail Form
    document.getElementById('detailSantriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveDetail');
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('{{ route("raport-kmi.update-detail") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                // Update Local Data with fresh data from server
                const index = raportData.findIndex(r => r.id == res.raport.id);
                if (index !== -1) {
                    raportData[index] = res.raport;
                }
                bootstrap.Modal.getInstance(document.getElementById('detailSantriModal')).hide();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data pelengkap diperbarui!', timer: 1500, showConfirmButton: false });
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = 'Simpan Perubahan';
        });
    });

    // Event Delegation untuk Autosave
    document.getElementById('gridBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('ajax-input')) {
            saveValue(e.target);
        }
    });

    function saveValue(input) {
        let row = input.closest('tr');
        let id = row.dataset.id;
        let mapel = document.getElementById('mapelSelector').value;
        let type = input.dataset.type;
        let value = input.value;
        
        let saving = row.querySelector('.saving-indicator');
        let success = row.querySelector('.success-indicator');

        saving.style.display = 'inline-block';
        success.classList.add('d-none');

        fetch('{{ route("raport-kmi.update-score") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ id, mapel, type, value })
        })
        .then(response => response.json())
        .then(data => {
            saving.style.display = 'none';
            if (data.success) {
                success.classList.remove('d-none');
                setTimeout(() => success.classList.add('d-none'), 2000);
                
                let record = raportData.find(r => r.id == id);
                if (record) {
                    if (!record.nilai_mapel) record.nilai_mapel = {};
                    if (!record.nilai_mapel[mapel]) record.nilai_mapel[mapel] = {};
                    record.nilai_mapel[mapel][type] = value;
                }
            }
        })
        .catch(error => {
            saving.style.display = 'none';
            alert('Gagal menyimpan nilai.');
        });
    }

    // Navigasi Arrow Keys (Event Delegation)
    document.addEventListener('keydown', function(e) {
        if (!e.target.classList.contains('ajax-input')) return;

        let input = e.target;
        let cell = input.parentElement;
        let row = cell.parentElement;
        let index = Array.from(cell.parentElement.children).indexOf(cell);

        if (e.key === 'ArrowDown') {
            let nextRow = row.nextElementSibling;
            if (nextRow) nextRow.children[index].querySelector('input').focus();
        } else if (e.key === 'ArrowUp') {
            let prevRow = row.previousElementSibling;
            if (prevRow) prevRow.children[index].querySelector('input').focus();
        }
    });

    // Quick Add Santri
    document.getElementById('addSantriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitSantri');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('{{ route("raport-kmi.store-ajax") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('ERR_DB_SAVE_FAILED');
            return response.json();
        })
        .then(res => {
            if (res.success) {
                raportData.push(res.raport);
                appendRow(res.raport);
                bootstrap.Modal.getInstance(document.getElementById('addSantriModal')).hide();
                this.reset();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Santri ditambahkan! Silakan isi nilainya.', timer: 2000, showConfirmButton: false });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Gagal Simpan', text: 'Terjadi kesalahan sistem atau database (Check Integrity). Silakan coba lagi.' });
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = 'Simpan & Mulai Input';
        });
    });

    function appendRow(raport) {
        const body = document.getElementById('gridBody');
        const count = body.children.length + 1;
        const mapel = document.getElementById('mapelSelector').value;
        
        const newRow = document.createElement('tr');
        newRow.dataset.id = raport.id;
        newRow.innerHTML = `
            <td class="ps-4 text-muted small counter">${count}</td>
            <td>
                <div class="fw-bold text-dark text-uppercase">${raport.nama_santri}</div>
                <div class="text-muted small" style="font-size: 0.7rem;">NI: ${raport.no_induk} | ${raport.kelas}</div>
            </td>
            <td class="text-center">
                <input type="number" class="score-cell ajax-input" data-type="p_a" value="0">
            </td>
            <td class="text-center">
                <input type="number" class="score-cell ajax-input" data-type="k_a" value="0">
            </td>
            <td class="text-center">
                <input type="number" class="score-cell ajax-input" data-type="s_a" value="0">
            </td>
            <td class="text-center">
                <span class="saving-indicator"><i class="bi bi-clock"></i> Saving...</span>
                <span class="success-indicator d-none status-success"><i class="bi bi-check-circle-fill"></i> Saved</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 btn-detail" data-id="${raport.id}">
                    <i class="bi bi-list-check me-1"></i> Lengkapi
                </button>
            </td>
        `;
        body.insertBefore(newRow, body.firstChild);
        
        // Update Nos
        document.querySelectorAll('.counter').forEach((td, i) => td.innerText = i + 1);
        
        // Focus ke input pertama santri baru
        newRow.querySelector('[data-type="p_a"]').focus();
    }
</script>
@endsection
