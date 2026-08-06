@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #0061ff 0%, #60efff 100%);
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
        width: 100%;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    .search-container {
        position: relative;
    }

    /* Memastikan icon berada di tengah input secara vertikal */
    .search-icon-inside {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        z-index: 5;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 97, 255, 0.2);
    }

    .badge-nilai {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: none;
        background: #f8faff;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 12px rgba(0,0,0,0.1);
    }

    .btn-edit { color: #0061ff; }
    .btn-edit:hover { background: #0061ff; color: white; }
    .btn-print { color: #ef4444; }
    .btn-print:hover { background: #ef4444; color: white; }
    .btn-delete { color: #6b7280; }
    .btn-delete:hover { background: #374151; color: white; }

    .table-premium thead th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 20px;
        border-bottom: 2px solid #f8f9fa;
    }
    
    [data-theme="dark"] .table-premium thead th {
        color: #cbd5e1;
        border-bottom-color: #334155;
    }

    /* Explicit Fix for Santri Name */
    .text-santri-name { color: #0f172a; font-weight: 800; }
    [data-theme="dark"] .text-santri-name { color: #ffffff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }
</style>

<div class="content-wrapper py-3 py-lg-4">
    <div class="container-lg">
        <!-- Header Section -->
        <div class="page-header flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
            <div class="page-header-left">
                <h1><i class="bi bi-people-fill me-2 text-primary"></i>Database Santri Tahfidz</h1>
                <p>Manajemen laporan progres dan transkrip nilai.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap ms-sm-auto">
                @if(count($data_rapor) > 0)
                <form action="{{ route('raport.truncate') }}" method="POST" id="form-hapus-semua" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger" style="border-radius: 12px; font-weight: 600; padding: 10px 16px;" onclick="konfirmasiHapusSemua()">
                        <i class="bi bi-trash3 me-1"></i> Kosongkan Data
                    </button>
                </form>
                @endif
                <a href="{{ url('/cetak-semua') }}" id="tombolCetak" class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;">
                    <i class="bi bi-printer me-1"></i> Cetak Rekap
                </a>
                <div class="bg-white px-3 py-2 rounded-3 shadow-sm border d-none d-md-block" style="border-radius: 12px !important;">
                    <span class="text-muted small fw-bold">TOTAL:</span>
                    <span class="text-primary fw-bold ms-1 small">{{ count($data_rapor) }}</span>
                </div>
            </div>
        </div>

        <div class="card card-main border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="search-container">
                            <i class="bi bi-search search-icon-inside"></i>
                            <input type="text" id="searchInput" class="form-control ps-5 rounded-3 py-2" placeholder="Cari berdasarkan nama atau ustadz...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="search-container">
                            <i class="bi bi-sort-numeric-down search-icon-inside"></i>
                            <select id="sortFilter" class="form-select ps-5 rounded-3 py-2">
                                <option value="default">Urutkan Berdasarkan...</option>
                                <option value="rerata">Nilai Rata-rata (Rank)</option>
                                <option value="tajwid">Nilai Tajwid</option>
                                <option value="adab">Nilai Adab</option>
                                <option value="kelancaran">Nilai Kelancaran</option>
                                <option value="fashahah">Nilai Fashahah</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-main">
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0" id="santriTable">
                    <thead>
                        <tr>
                            <th class="ps-4 d-none d-md-table-cell" width="80">No</th>
                            <th>Detail Santri</th>
                            <th class="d-none d-lg-table-cell">Musyrif</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_rapor as $index => $r)
                        @php 
                            $scores = collect([$r->adab, $r->kelancaran, $r->tajwid, $r->fashahah])->filter(fn($v) => $v !== null);
                            $rerata = $scores->count() > 0 ? $scores->average() : 0; 
                        @endphp
                        <tr class="santri-row" 
                            data-rerata="{{ $rerata }}" 
                            data-tajwid="{{ $r->tajwid ?? 0 }}" 
                            data-adab="{{ $r->adab ?? 0 }}" 
                            data-kelancaran="{{ $r->kelancaran ?? 0 }}" 
                            data-fashahah="{{ $r->fashahah ?? 0 }}">
                            
                            <td class="ps-4 d-none d-md-table-cell">
                                <span class="text-muted fw-bold">#{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle d-none d-sm-flex">
                                        {{ strtoupper(substr($r->nama_santri, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="nama-santri text-santri-name text-uppercase mb-0" style="font-size: 0.85rem;">{{ $r->nama_santri }}</div>
                                        <div class="d-lg-none text-muted" style="font-size: 0.7rem;">
                                            <i class="bi bi-person me-1"></i>{{ $r->musyrif }}
                                        </div>
                                        <small class="text-muted d-none d-sm-block" style="font-size: 0.7rem;">ID: MMQ-{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-mortarboard me-2 text-primary"></i>
                                    <span class="musyrif-name text-secondary small">{{ $r->musyrif }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-nilai {{ $rerata >= 85 ? 'bg-success text-white' : 'bg-primary text-white' }}" style="padding: 5px 10px; font-size: 0.75rem;">
                                    {{ number_format($rerata, 1) }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="/edit-santri/{{ $r->id }}" class="btn-action btn-edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="#" onclick="openPrintModalTahfidz({{ $r->id }}, '{{ addslashes($r->nama_santri) }}')" class="btn-action btn-print" title="Cetak PDF">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <form action="/hapus-santri/{{ $r->id }}" method="POST" id="form-hapus-{{ $r->id }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" onclick="konfirmasiHapus('{{ $r->id }}', '{{ $r->nama_santri }}')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted">Belum ada rekaman nilai santri</p>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="notFoundRow" style="display: none;">
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted">Data tidak ditemukan.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Cetak Tahfidz -->
<div class="modal fade" id="printModalTahfidz" tabindex="-1" aria-labelledby="printModalTahfidzLabel" aria-hidden="true" style="border-radius: 16px;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; background: var(--glass-bg, #ffffff);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="printModalTahfidzLabel">Konfirmasi Parameter Cetak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="printFormTahfidz" method="GET" target="_blank" onsubmit="bootstrap.Modal.getInstance(document.getElementById('printModalTahfidz')).hide();">
                <div class="modal-body py-3">
                    <p class="text-muted small mb-3">Sesuaikan parameter cetak rapor untuk santri <strong id="modalSantriNameTahfidz" class="text-primary"></strong> sebelum mencetak.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Semester</label>
                        <select name="semester" class="form-select" style="border-radius: 10px;">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="2025/2026" style="border-radius: 10px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Halaqoh</label>
                        <input type="text" name="halaqoh" class="form-control" value="Tahfidz" style="border-radius: 10px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">KKM</label>
                        <input type="number" name="kkm" class="form-control" value="60" style="border-radius: 10px;">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Tempat & Tanggal Cetak</label>
                        <input type="text" name="tanggal_cetak" class="form-control" value="Pringkuku, 20 Desember 2025" style="border-radius: 10px;">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                    <button type="submit" class="btn btn-primary text-white" style="border-radius: 10px; font-weight: 600;">
                        <i class="bi bi-printer me-1"></i> Buka Rapor & Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Fitur Ranking / Sorting dengan Sinkronisasi ke Tombol Cetak
    const sortFilter = document.getElementById('sortFilter');
    const btnCetak = document.getElementById('tombolCetak');

    if (sortFilter && btnCetak) {
        sortFilter.addEventListener('change', function() {
            let criteria = this.value;
            let tableBody = document.querySelector('#santriTable tbody');
            let rows = Array.from(tableBody.querySelectorAll('.santri-row'));

            // Update URL tombol cetak sesuai kriteria yang dipilih
            if (criteria === 'default' || criteria === 'rerata') {
                // Jika default atau rata-rata, gunakan parameter 'rata_rata'
                btnCetak.href = `{{ url('/cetak-semua') }}?kategori=rata_rata`;
            } else {
                // Jika kategori spesifik (tajwid, adab, kelancaran, fashahah)
                btnCetak.href = `{{ url('/cetak-semua') }}?kategori=${criteria}`;
            }

            // Jika bukan default, lakukan sorting di frontend
            if (criteria !== 'default') {
                rows.sort((a, b) => {
                    // Mengambil nilai dari data-attribute: data-rerata, data-tajwid, dll
                    let valA = parseFloat(a.getAttribute('data-' + criteria)) || 0;
                    let valB = parseFloat(b.getAttribute('data-' + criteria)) || 0;

                    return valB - valA; // Ranking: Terbesar ke Terkecil
                });

                // Susun ulang baris di dalam tabel
                rows.forEach(row => tableBody.appendChild(row));
            }
        });
    }

    // 2. Fitur Pencarian Dinamis
    // Kita panggil berdasarkan ID 'searchInput' agar lebih akurat
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.santri-row');
            let visibleCount = 0;

            rows.forEach(row => {
                // Pastikan class 'nama-santri' dan 'musyrif-name' ada di dalam <td>
                let nama = row.querySelector('.nama-santri').innerText.toLowerCase();
                let musyrif = row.querySelector('.musyrif-name').innerText.toLowerCase();
                
                if (nama.includes(filter) || musyrif.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            // Tampilkan pesan "Data tidak ditemukan"
            const notFoundRow = document.getElementById('notFoundRow');
            if (notFoundRow) {
                notFoundRow.style.display = (visibleCount === 0 && filter !== "") ? "" : "none";
            }
        });
    }

    // 3. Initialize Tooltips (Bootstrap 5)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // 4. Konfirmasi Hapus SweetAlert2
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data " + nama + " akan dihapus permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#374151',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        })
    }

    function konfirmasiHapusSemua() {
        Swal.fire({
            title: 'Kosongkan Semua Data?',
            text: "Seluruh data Nilai Tahfidz akan dihapus PERMANEN dari sistem. Tindakan ini tidak dapat dibatalkan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-semua').submit();
            }
        })
    }

    // 5. Alert Sukses dari Session Laravel
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-4' }
        });
    @endif
    // 6. Buka Modal Cetak Rapor Tahfidz
    function openPrintModalTahfidz(id, namaSantri) {
        document.getElementById('modalSantriNameTahfidz').innerText = namaSantri;
        const form = document.getElementById('printFormTahfidz');
        form.action = `/cetak/${id}`;
        
        const myModal = new bootstrap.Modal(document.getElementById('printModalTahfidz'));
        myModal.show();
    }
</script>
@endsection

