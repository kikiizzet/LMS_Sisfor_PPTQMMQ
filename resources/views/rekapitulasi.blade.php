@extends('layout')

@section('main-content')
<style>
    .btn-action-share {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: none;
        background: #f0fdf4;
        color: #25d366;
        cursor: pointer;
    }
    .btn-action-share:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(37, 211, 102, 0.2);
        background: #25d366;
        color: white;
    }
    .share-modal-body .link-box {
        background: #f8fafc;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        word-break: break-all;
        font-size: 0.85rem;
        color: #0369a1;
        font-weight: 600;
    }
    .share-btn-group { display: flex; gap: 10px; margin-top: 16px; }
    .share-btn-group .btn { flex: 1; border-radius: 12px; font-weight: 700; padding: 10px; font-size: 0.85rem; }
    .share-btn-wa { background: #25d366; color: white; border: none; }
    .share-btn-wa:hover { background: #1da851; color: white; }
    .share-btn-copy { background: #0ea5e9; color: white; border: none; }
    .share-btn-copy:hover { background: #0284c7; color: white; }
</style>
<div class="container-fluid px-3">
    <!-- Header Section -->
    <div class="row mb-4 align-items-center text-center text-md-start">
        <div class="col-md-7 mb-3 mb-md-0">
            <h3 class="fw-bold text-dark mb-1" style="font-size: clamp(1.2rem, 4vw, 1.75rem);">Rekapitulasi Nilai Santri</h3>
            <p class="text-muted small mb-0">Laporan gabungan capaian Tahfidz dan KMI.</p>
            <a href="{{ route('raport-kmi.grid') }}" class="btn btn-warning btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm mt-3 animate-pulse">
                <i class="bi bi-grid-3x3-gap-fill me-2"></i> Mode Smart Grid (Input Cepat)
            </a>
        </div>
        <div class="col-md-5">
            <div class="bg-white p-2 rounded-pill shadow-sm border d-flex align-items-center">
                <i class="bi bi-search ms-2 text-muted"></i>
                <input type="text" id="searchInput" class="form-control border-0 bg-transparent shadow-none" placeholder="Cari nama..." style="width: 100%;">
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0ea5e9, #2563eb);">
                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <p class="mb-1 opacity-75">Total Santri Terdata</p>
                        <h2 class="fw-bold mb-0">{{ $rekap->count() }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <p class="mb-1 opacity-75">Rata-rata Tahfidz</p>
                        <h2 class="fw-bold mb-0">{{ number_format($rekap->where('has_tahfidz', true)->avg('rata_tahfidz'), 2) }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-book-half fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981, #059669);">
                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <p class="mb-1 opacity-75">Rata-rata KMI</p>
                        <h2 class="fw-bold mb-0">{{ number_format($rekap->where('has_kmi', true)->avg('rata_kmi'), 2) }}</h2>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="bi bi-mortarboard-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="rekapTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3 py-3 text-uppercase small fw-bold text-muted d-none d-md-table-cell" style="width: 50px;">No</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Santri</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center d-none d-sm-table-cell">Tahfidz</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center d-none d-sm-table-cell">KMI</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Rata</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center pe-3">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center pe-3" style="width: 60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap->sortByDesc('total_rata') as $index => $item)
                    <tr>
                        <td class="ps-3 fw-medium text-muted d-none d-md-table-cell">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-dark text-nowrap" style="font-size: 0.85rem;">{{ $item->nama_santri }}</div>
                            <small class="text-muted d-block" style="font-size: 0.65rem;">Musyrif: {{ $item->musyrif }}</small>
                        </td>
                        <td class="text-center d-none d-sm-table-cell">
                            @if($item->has_tahfidz)
                                <span class="badge bg-info bg-opacity-10 text-info fw-bold rounded-pill" style="font-size: 0.7rem;">
                                    {{ number_format($item->rata_tahfidz, 1) }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.65rem;">-</span>
                            @endif
                        </td>
                        <td class="text-center d-none d-sm-table-cell">
                            @if($item->has_kmi)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold rounded-pill" style="font-size: 0.7rem;">
                                    {{ number_format($item->rata_kmi, 1) }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.65rem;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="fw-bold {{ $item->total_rata >= 85 ? 'text-primary' : ($item->total_rata >= 75 ? 'text-success' : 'text-warning') }}" style="font-size: 0.9rem;">
                                {{ number_format($item->total_rata, 1) }}
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            @php
                                $status = 'Cukup';
                                $badgeClass = 'bg-warning';
                                if($item->total_rata >= 90) { $status = 'Mumtaz'; $badgeClass = 'bg-primary'; }
                                elseif($item->total_rata >= 80) { $status = 'J.Jiddan'; $badgeClass = 'bg-info'; }
                                elseif($item->total_rata >= 70) { $status = 'Jayyid'; $badgeClass = 'bg-success'; }
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="font-size: 0.6rem; padding: 4px 8px;">{{ $status }}</span>
                        </td>
                        <td class="text-center pe-3">
                            <button type="button" class="btn-action-share" title="Share Link Raport" onclick="generateShareLink('{{ addslashes($item->nama_santri) }}')">
                                <i class="bi bi-share"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <span class="text-muted">Belum ada data santri yang terdaftar di sistem.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Share Link Modal -->
<div class="modal fade" id="shareLinkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-link-45deg text-primary me-2"></i>Link Raport</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body share-modal-body">
                <p class="text-muted small mb-2">Link raport untuk: <strong id="shareStudentName"></strong></p>
                <div class="link-box" id="shareLinkBox">Generating...</div>
                <p class="text-muted mt-2 mb-0" style="font-size: 0.75rem;" id="shareExpiry"></p>
                <div class="share-btn-group">
                    <button class="btn share-btn-copy" onclick="copyShareLink()">
                        <i class="bi bi-clipboard me-1"></i> Copy Link
                    </button>
                    <a href="#" class="btn share-btn-wa" id="shareWaBtn" target="_blank">
                        <i class="bi bi-whatsapp me-1"></i> Share WA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#rekapTable tbody tr');
        
        rows.forEach(row => {
            let name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Generate Share Link
    function generateShareLink(namaSantri) {
        const modal = new bootstrap.Modal(document.getElementById('shareLinkModal'));
        document.getElementById('shareStudentName').textContent = namaSantri;
        document.getElementById('shareLinkBox').textContent = 'Generating...';
        document.getElementById('shareExpiry').textContent = '';
        document.getElementById('shareWaBtn').href = '#';
        modal.show();

        fetch('{{ route("raport.generate-token") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ nama_santri: namaSantri })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('shareLinkBox').textContent = data.url;
                document.getElementById('shareWaBtn').href = data.whatsapp_url;
                if (data.expires_at) {
                    document.getElementById('shareExpiry').textContent = '⏱️ Berlaku hingga ' + data.expires_at;
                }
            } else {
                document.getElementById('shareLinkBox').textContent = 'Gagal generate link.';
            }
        })
        .catch(() => {
            document.getElementById('shareLinkBox').textContent = 'Terjadi kesalahan.';
        });
    }

    function copyShareLink() {
        const linkText = document.getElementById('shareLinkBox').textContent;
        navigator.clipboard.writeText(linkText).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Tersalin!',
                text: 'Link raport sudah di-copy ke clipboard.',
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-4' }
            });
        });
    }
</script>
@endsection
x