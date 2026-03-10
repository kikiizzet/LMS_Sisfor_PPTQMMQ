@extends('layout')

@section('main-content')
<style>
   
    /* Styling tambahan agar input musyrif readonly terlihat seperti teks biasa */
    .input-musyrif-field:focus {
        box-shadow: none;
        outline: none;
    }

    :root {
        --primary-gold: #c5a059;
        --primary-green: #2d5a27;
        --soft-gold: #f4ece1;
        --soft-green: #e9f0e8;
        --primary-gradient: linear-gradient(135deg, #2d5a27 0%, #1e3d1a 100%);
        --accent-blue: #0061ff;
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
        width: 100%;
    }

    /* Status Pill Animation */
    .status-pill {
        background: var(--soft-green);
        color: var(--primary-green);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(45, 90, 39, 0.1);
    }

    .dot-pulse {
        width: 8px;
        height: 8px;
        background: var(--primary-green);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(45, 90, 39, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(45, 90, 39, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(45, 90, 39, 0); }
    }

    /* Musyrif Chips */
    .musyrif-chip {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 6px 6px 6px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        user-select: none;
    }

    .musyrif-chip:hover {
        border-color: var(--primary-gold);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(197, 160, 89, 0.1);
    }

    .musyrif-chip.active {
        background: var(--soft-gold);
        border-color: var(--primary-gold);
        box-shadow: 0 4px 15px rgba(197, 160, 89, 0.15);
    }

    .musyrif-chip.active .btn-nama {
        color: var(--primary-gold) !important;
        font-weight: 800;
    }

    [data-theme="dark"] .musyrif-chip {
        background: #1e293b !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] .musyrif-chip .btn-nama {
        color: #e2e8f0 !important;
    }
    
    [data-theme="dark"] .musyrif-chip.active {
        background: rgba(197, 160, 89, 0.15) !important;
        border-color: var(--primary-gold) !important;
    }

    .chip-actions {
        display: flex;
        gap: 4px;
        padding-left: 8px;
        border-left: 1px solid #e2e8f0;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    /* Score Inputs */
    .score-input {
        background-color: var(--soft-green); 
        border: 1px solid #e2e8f0; 
        border-radius: 8px; 
        transition: all 0.2s ease;
        font-weight: 800;
    }

    .score-input:focus {
        background-color: white; 
        border-color: var(--primary-gold); 
        box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.1); 
    }

    [data-theme="dark"] .score-input {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
        border-color: #475569 !important;
    }

    /* Fixed visibility for Musyrif and Average */
    .musyrif-text-fix { color: var(--accent-blue); }
    [data-theme="dark"] .musyrif-text-fix { color: #38bdf8 !important; }

    .average-score { color: #1e293b; }
    [data-theme="dark"] .average-score { color: #f1f5f9 !important; }
    
    /* Table Typography */
    .table-premium thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8e9aaf;
        padding: 15px 10px;
        background: #fcfdfe;
    }

    /* Predicate Badges */
    .badge-mumtaz { background: #dcfce7 !important; color: #166534 !important; }
    .badge-jayyid-jiddan { background: #dbeafe !important; color: #1e40af !important; }
    .badge-jayyid { background: #fef9c3 !important; color: #854d0e !important; }
    .badge-maqbul { background: #ffedd5 !important; color: #9a3412 !important; }
    .badge-dhaif { background: #fee2e2 !important; color: #991b1b !important; }

    .rating-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 8px;
        min-width: 80px;
        border: 1px solid #f1f5f9;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    [data-theme="dark"] .rating-box {
        background: #1e293b !important;
        border-color: #334155 !important;
    }

    .search-input-wrapper {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 0 10px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    [data-theme="dark"] .search-input-wrapper {
        background: #1e293b !important;
        border-color: #334155 !important;
    }

    .search-input-wrapper:focus-within {
        background: white;
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.1);
    }

    .input-musyrif-field:focus {
        box-shadow: none;
        outline: none;
    }

    /* Button Styling */
    .btn-save-data {
        background: var(--primary-green);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save-data:hover {
        background: #1e3d1a;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        color: white;
    }

    .btn-save-data:hover {
        background: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        color: white;
    }

    .btn-reset {
        color: #64748b;
        font-weight: 600;
        text-decoration: none;
    }
</style>

<div class="content-wrapper py-3 py-lg-5">
    <div class="container-fluid px-3 px-lg-4">
        
        <div class="row mb-4 align-items-center text-center text-md-start">
            <div class="col-md-7 mb-3 mb-md-0">
                <h2 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-center justify-content-md-start gap-2" style="font-size: clamp(1.2rem, 4vw, 2.2rem);">
                    <i class="bi bi-journal-plus text-primary"></i> Entry Nilai Tahfidz
                </h2>
                <p class="text-muted mb-0 small opacity-75">Sistem Laporan Capaian Progres Santri MMQ</p>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end justify-content-center">
                <div class="status-pill">
                    <div class="dot-pulse"></div>
                    <span>SESI INPUT AKTIF</span>
                </div>
            </div>
        </div>

       <div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-2">
            <div class="card-body p-2 p-md-4">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-4 px-2 gap-3">
                        <div class="d-flex align-items-center bg-light px-3 py-2 rounded-pill">
                            <i class="bi bi-people-fill text-primary me-2 small"></i>
                            <span class="fw-bold text-dark" style="font-size: 0.75rem; letter-spacing: 0.5px;">PILIH USTADZ PENGAMPU</span>
                        </div>
                        
                        <div class="d-flex gap-2 w-100 w-sm-auto">
                            <input type="text" id="newMusyrifName" placeholder="Nama Baru..." 
                                class="form-control form-control-sm border-0 bg-light px-3" style="max-width: 220px; border-radius: 10px;">
                            <button type="button" onclick="tambahMusyrif()" class="btn btn-sm btn-primary rounded-3 px-3 shadow-none">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3" id="musyrifContainer">
                    @forelse ($musyrif_list as $musyrif)
                        <div class="musyrif-chip {{ $loop->first ? 'active' : '' }}" 
                             id="item-{{ $musyrif->id }}" 
                             onclick="pilihMusyrif('{{ $musyrif->nama }}', this)"
                             data-id="{{ $musyrif->id }}">
                            
                            <span class="btn-nama text-dark small">{{ $musyrif->nama }}</span>
                            
                            <div class="chip-actions">
                                <i class="bi bi-pencil-square text-warning" onclick="event.stopPropagation(); editMusyrif({{ $musyrif->id }}, '{{ $musyrif->nama }}')" style="cursor: pointer; font-size: 0.8rem;"></i>
                                <i class="bi bi-trash text-danger" onclick="event.stopPropagation(); hapusMusyrif({{ $musyrif->id }})" style="cursor: pointer; font-size: 0.8rem;"></i>
                            </div>
                        </div>
                    @empty
                        <div id="no-data" class="ps-2 text-muted small">Belum ada daftar pengampu.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
        <form action="/simpan-nilai" method="POST">
            @csrf
            <div class="card card-main overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="ps-3" width="50">No</th>
                                <th width="200">Santri</th>
                                <th class="text-center" width="70">Adab</th>
                                <th class="text-center" width="70">Lancar</th>
                                <th class="text-center" width="70">Tajwid</th>
                                <th class="text-center" width="70">Fashahah</th>
                                <th class="text-center d-none d-sm-table-cell" width="90">Rata</th>
                                <th class="pe-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                            <tr class="santri-row">
                                <td class="ps-3 text-center">
                                    <span class="text-muted fw-bold" style="font-size: 0.7rem;">#{{ $i + 1 }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="search-container position-relative mb-1">
                                            <input type="text" name="santri[{{$i}}][nama]" 
                                                class="form-control query-input ps-4" 
                                                placeholder="Cari Santri..." 
                                                autocomplete="off"
                                                list="santri-list"
                                                style="font-size: 0.85rem; border-radius: 8px; background-color: var(--input-bg); color: var(--text-main); width: 100%;">
                                            <i class="bi bi-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.75rem;"></i>
                                        </div>
                                        <div class="d-flex align-items-center ps-1">
                                            <i class="bi bi-person-fill me-1" style="font-size: 0.7rem; color: var(--accent-blue);"></i>
                                            <input type="text" name="santri[{{$i}}][musyrif]" 
                                                class="input-musyrif-field border-0 p-0 fw-bold musyrif-text-fix" 
                                                value="{{ Auth::user()->name }}" 
                                                readonly 
                                                style="background: transparent; outline: none; width: 100%; font-size: 0.7rem;">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" name="santri[{{$i}}][adab]" class="form-control score-input px-1" placeholder="0" min="0" max="100" style="font-size: 0.9rem;">
                                </td>
                                <td>
                                    <input type="number" name="santri[{{$i}}][kelancaran]" class="form-control score-input px-1" placeholder="0" min="0" max="100" style="font-size: 0.9rem;">
                                </td>
                                <td>
                                    <input type="number" name="santri[{{$i}}][tajwid]" class="form-control score-input px-1" placeholder="0" min="0" max="100" style="font-size: 0.9rem;">
                                </td>
                                <td>
                                    <input type="number" name="santri[{{$i}}][fashahah]" class="form-control score-input px-1" placeholder="0" min="0" max="100" style="font-size: 0.9rem;">
                                </td>
                                <td class="text-center d-none d-sm-table-cell">
                                    <div class="rating-box d-flex flex-column align-items-center">
                                        <span class="average-score fw-bold" style="font-size: 0.9rem;">-</span>
                                        <span class="badge border rounded-pill predicate-badge bg-light text-muted" style="font-size: 0.5rem; letter-spacing: 0.3px;">...</span>
                                    </div>
                                </td>
                                <td class="pe-3">
                                    <input type="text" name="santri[{{$i}}][catatan]" 
                                        class="form-control form-control-sm border-0 bg-light px-3" 
                                        placeholder="Ketik catatan di sini..." style="font-size: 0.75rem; border-radius: 8px;">
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="reset" class="btn btn-reset small">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> RESET FORM
                        </button>
                        <button type="submit" class="btn btn-save-data shadow-sm">
                            <i class="bi bi-cloud-arrow-up me-2"></i> SIMPAN KE DATABASE
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- DATALIST UNTUK AUTOCOMPLETE NAMA SANTRI -->
<datalist id="santriList">
    @foreach($santri_list as $s)
        <option value="{{ $s->nama_santri }}" 
            data-musyrif="{{ $s->musyrif }}"
            data-adab="{{ $s->adab }}"
            data-kelancaran="{{ $s->kelancaran }}"
            data-tajwid="{{ $s->tajwid }}"
            data-fashahah="{{ $s->fashahah }}"
            data-catatan="{{ $s->catatan }}">
    @endforeach
</datalist>

<script>
    // CSRF Token untuk AJAX
    const csrfToken = '{{ csrf_token() }}';

    // 1. Fungsi Pilih Nama
    function pilihMusyrif(nama, element) {
        // Isi semua input di tabel
        document.querySelectorAll('.input-musyrif-field').forEach(input => {
            input.value = nama;
        });

        // Beri tanda aktif pada chip yang dipilih
        document.querySelectorAll('.musyrif-chip').forEach(chip => chip.classList.remove('active'));
        element.closest('.musyrif-chip').classList.add('active');
    }

    // 2. Fungsi Tambah Musyrif (AJAX ke Database)
    async function tambahMusyrif() {
        const nameInput = document.getElementById('newMusyrifName');
        const name = nameInput.value.trim();
        
        if (name === "") {
            alert('Nama pengampu tidak boleh kosong!');
            return;
        }

        try {
            const response = await fetch('/musyrif/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nama: name })
            });

            const result = await response.json();

            if (result.success) {
                // Hapus pesan "Belum ada daftar"
                const noData = document.getElementById('no-data');
                if (noData) noData.remove();

                // Tambahkan elemen baru ke UI
                const container = document.getElementById('musyrifContainer');
                const html = createMusyrifElement(result.data.id, result.data.nama);
                container.insertAdjacentHTML('beforeend', html);
                
                nameInput.value = "";
                alert('✅ Musyrif berhasil ditambahkan!');
                
                // Auto-pilih musyrif yang baru ditambahkan
                pilihMusyrif(result.data.nama, container.lastElementChild.querySelector('.btn-nama'));
            } else {
                alert('❌ ' + (result.message || 'Gagal menambahkan musyrif'));
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }

    // 3. Fungsi Edit Nama (AJAX ke Database)
    async function editMusyrif(id, oldName) {
        const newName = prompt("Edit Nama Pengampu:", oldName);
        
        if (newName === null || newName.trim() === "" || newName === oldName) {
            return; // User cancel atau tidak ada perubahan
        }

        try {
            const response = await fetch(`/musyrif/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nama: newName.trim() })
            });

            const result = await response.json();

            if (result.success) {
                // Update UI
                const item = document.getElementById('item-' + id);
                const btnNama = item.querySelector('.btn-nama');
                btnNama.innerText = result.data.nama;
                btnNama.setAttribute('onclick', `pilihMusyrif('${result.data.nama}', this)`);
                
                // Update juga di edit button
                item.querySelector('.btn-outline-warning').setAttribute('onclick', `editMusyrif(${id}, '${result.data.nama}')`);
                
                alert('✅ Musyrif berhasil diupdate!');
                
                // Jika nama ini sedang aktif di tabel, update juga
                if (btnNama.classList.contains('bg-primary')) {
                    pilihMusyrif(result.data.nama, btnNama);
                }
            } else {
                alert('❌ ' + (result.message || 'Gagal mengupdate musyrif'));
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }

    // 4. AUTO-CALCULATION & LIVE PREVIEW (Fitur Baru)
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.santri-row');
        
        rows.forEach(row => {
            const inputs = row.querySelectorAll('.score-input');
            const avgDisplay = row.querySelector('.average-score');
            const badgeDisplay = row.querySelector('.predicate-badge');
            
            function calculate() {
                let sum = 0;
                let filled = 0;
                
                inputs.forEach(input => {
                    let val = parseFloat(input.value);
                    if (!isNaN(val)) {
                        sum += val;
                        filled++;
                    }
                });
                
                // Hitung rata-rata (Dibagi 4 Mapel)
                let avg = sum / 4;
                
                if (filled > 0) {
                    avgDisplay.innerText = avg.toFixed(1);
                    
                    // Reset Class
                    badgeDisplay.className = "badge border rounded-pill predicate-badge";
                    
                    // Tentukan Predikat
                    if (avg >= 90) {
                        badgeDisplay.innerText = "Mumtaz";
                        badgeDisplay.className = "badge rounded-pill predicate-badge badge-mumtaz";
                    } else if (avg >= 80) {
                        badgeDisplay.innerText = "Jayyid Jiddan";
                        badgeDisplay.className = "badge rounded-pill predicate-badge badge-jayyid-jiddan";
                    } else if (avg >= 70) {
                        badgeDisplay.innerText = "Jayyid";
                        badgeDisplay.className = "badge rounded-pill predicate-badge badge-jayyid";
                    } else if (avg >= 60) {
                        badgeDisplay.innerText = "Maqbul";
                        badgeDisplay.className = "badge rounded-pill predicate-badge badge-maqbul";
                    } else {
                        badgeDisplay.innerText = "Dhaif";
                        badgeDisplay.className = "badge rounded-pill predicate-badge badge-dhaif";
                    }
                } else {
                    avgDisplay.innerText = "-";
                    badgeDisplay.innerText = "Menunggu";
                    badgeDisplay.className = "badge bg-light text-muted border rounded-pill predicate-badge";
                }
            }
            
            inputs.forEach(input => {
                input.addEventListener('input', calculate);
            });
        });
    });

    // 5. AUTO-FILL MUSYRIF & SKOR TERAKHIR
    function autoFillSantriData(inputElement) {
        const val = inputElement.value;
        const list = document.getElementById('santriList');
        const options = list.options;
        
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                const musyrifName = options[i].getAttribute('data-musyrif');
                
                // Cari input musyrif di baris yang sama
                // Structure: inputWrapper -> flex-grow-1 -> d-flex (row container)
                // inputElement is inside .flex-grow-1
                // The musyrif input is the NEXT sibling div -> input
                
                // Traverse DOM cleanly:
                const container = inputElement.closest('td'); 
                const musyrifInput = container.querySelector('.input-musyrif-field');
                
                if (musyrifInput && musyrifName) {
                    musyrifInput.value = musyrifName;
                    
                    // Flash effect to show it updated
                    musyrifInput.style.transition = "color 0.3s";
                    musyrifInput.style.color = "#28a745"; // Green
                    setTimeout(() => {
                        musyrifInput.style.color = ""; // Revert
                    }, 1000);
                }

                // Fill Scores & Note
                const adab = options[i].getAttribute('data-adab');
                const lancar = options[i].getAttribute('data-kelancaran');
                const tajwid = options[i].getAttribute('data-tajwid');
                const fashahah = options[i].getAttribute('data-fashahah');
                const catatan = options[i].getAttribute('data-catatan');

                const row = inputElement.closest('tr');
                if (row) {
                    const inputs = row.querySelectorAll('.score-input');
                    // inputs order: adab, kelancaran, tajwid, fashahah
                    if (inputs[0]) { inputs[0].value = adab; inputs[0].dispatchEvent(new Event('input')); }
                    if (inputs[1]) { inputs[1].value = lancar; inputs[1].dispatchEvent(new Event('input')); }
                    if (inputs[2]) { inputs[2].value = tajwid; inputs[2].dispatchEvent(new Event('input')); }
                    if (inputs[3]) { inputs[3].value = fashahah; inputs[3].dispatchEvent(new Event('input')); }
                    
                    // Catatan (last input typically)
                    const noteInput = row.querySelector('input[name*="[catatan]"]');
                    if (noteInput) noteInput.value = catatan || '';
                }

                break;
            }
        }
    }

    // 4. Fungsi Hapus (AJAX ke Database)
    async function hapusMusyrif(id) {
        if (!confirm('Hapus musyrif ini dari database? Data tidak dapat dikembalikan!')) {
            return;
        }

        try {
            const response = await fetch(`/musyrif/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();

            if (result.success) {
                // Hapus dari UI
                document.getElementById('item-' + id).remove();
                
                // Jika tidak ada lagi musyrif, tampilkan pesan
                const container = document.getElementById('musyrifContainer');
                if (container.children.length === 0) {
                    container.innerHTML = '<div id="no-data" class="ps-2 text-muted small italic">Belum ada daftar pengampu. Silakan tambahkan.</div>';
                }
                
                alert('✅ Musyrif berhasil dihapus!');
            } else {
                alert('❌ ' + (result.message || 'Gagal menghapus musyrif'));
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    }

    // Helper untuk membuat elemen HTML
    function createMusyrifElement(id, name) {
        return `
            <div class="musyrif-chip" id="item-${id}" onclick="pilihMusyrif('${name}', this)" data-id="${id}">
                <span class="btn-nama text-dark small">${name}</span>
                <div class="chip-actions">
                    <i class="bi bi-pencil-square text-warning" onclick="event.stopPropagation(); editMusyrif(${id}, '${name}')" style="cursor: pointer; font-size: 0.8rem;"></i>
                    <i class="bi bi-trash text-danger" onclick="event.stopPropagation(); hapusMusyrif(${id})" style="cursor: pointer; font-size: 0.8rem;"></i>
                </div>
            </div>`;
    }

    // Event listener untuk Enter key pada input
    document.getElementById('newMusyrifName')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            tambahMusyrif();
        }
    });
</script>


@endsection