@extends('layout')

@section('main-content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h3 class="fw-bold text-dark mb-1">Dashboard Analitik</h3>
            <p class="text-muted small">Selamat datang, berikut adalah ringkasan capaian santri MMQ hari ini.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <span class="badge bg-white text-dark shadow-sm p-2 border d-flex align-items-center">
                <i class="bi bi-calendar3 me-2 text-primary"></i>{{ date('d M Y') }}
            </span>
            <span class="badge bg-dark text-white shadow-sm p-2 border d-flex align-items-center">
                <i class="bi bi-clock me-2 text-warning"></i><span id="liveClock" class="fw-bold">00:00:00</span>
            </span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(45deg, #0ea5e9, #2563eb); border-radius: 16px;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1 opacity-75 small fw-bold">TOTAL SANTRI</p>
                            <h2 class="fw-bold mb-0">{{ $totalSantri }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-3 p-2">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <p class="mb-1 text-muted small fw-bold">MUMTAZ</p>
                    <h3 class="fw-bold mb-0 text-success">{{ $mumtaz }}</h3>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $totalSantri > 0 ? ($mumtaz/$totalSantri)*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <p class="mb-1 text-muted small fw-bold">JAYYID JIDDAN</p>
                    <h3 class="fw-bold mb-0 text-primary">{{ $jayyidJiddan }}</h3>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ $totalSantri > 0 ? ($jayyidJiddan/$totalSantri)*100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body">
                    <p class="mb-1 text-muted small fw-bold">JAYYID</p>
                    <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $jayyid }}</h3>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar" style="width: {{ $totalSantri > 0 ? ($jayyid/$totalSantri)*100 : 0 }}%; background-color: #f59e0b;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; background-color: #fff5f5;">
                <div class="card-body">
                    <p class="mb-1 text-danger small fw-bold">MAQBUL (BUTUH BIMBINGAN)</p>
                    <h3 class="fw-bold mb-0 text-danger">{{ $maqbul }}</h3>
                    <p class="small text-muted mb-0">Segera lakukan evaluasi harian</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Komposisi Predikat</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <canvas id="performaChart" style="max-height: 220px;"></canvas>
                    <div class="mt-4 w-100">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="bi bi-circle-fill text-success me-2"></i>Mumtaz</span>
                            <span class="fw-bold">{{ $mumtaz }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="bi bi-circle-fill text-primary me-2"></i>Jayyid Jiddan</span>
                            <span class="fw-bold">{{ $jayyidJiddan }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span><i class="bi bi-circle-fill me-2" style="color: #f59e0b;"></i>Jayyid</span>
                            <span class="fw-bold">{{ $jayyid }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-danger">
                            <span><i class="bi bi-circle-fill me-2"></i>Maqbul</span>
                            <span class="fw-bold">{{ $maqbul }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Aktivitas Input Terbaru</h6>
                    <a href="/daftar" class="btn btn-sm btn-light border fw-bold text-muted">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="ps-4">SANTRI</th>
                                <th>MUSYRIF</th>
                                <th>WAKTU INPUT</th>
                                <th class="text-end pe-4">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                                            {{ substr($r->nama_santri, 0, 1) }}
                                        </div>
                                        <span class="fw-bold">{{ $r->nama_santri }}</span>
                                    </div>
                                </td>
                                <td><span class="text-muted small">{{ $r->musyrif }}</span></td>
                                <td><span class="text-muted small">{{ $r->created_at->diffForHumans() }}</span></td>
                                <td class="text-end pe-4">
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Berhasil</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">Belum ada aktivitas hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Script Jam Real-time
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('liveClock').textContent = now.toLocaleTimeString('en-GB', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Script Performa Chart (4 Predikat)
    const ctx = document.getElementById('performaChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul'],
            datasets: [{
                data: [{{ $mumtaz }}, {{ $jayyidJiddan }}, {{ $jayyid }}, {{ $maqbul }}],
                backgroundColor: [
                    '#10b981', // Hijau (Mumtaz)
                    '#3b82f6', // Biru (Jayyid Jiddan)
                    '#f59e0b', // Oranye (Jayyid)
                    '#ef4444'  // Merah (Maqbul)
                ],
                hoverOffset: 12,
                borderWidth: 4,
                borderColor: '#ffffff'
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection