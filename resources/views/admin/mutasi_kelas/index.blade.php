@extends('layout')

@section('main-content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
        width: 100%;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: var(--glass-bg, rgba(255, 255, 255, 0.95));
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
    }

    .form-control-minimal {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.95rem;
    }

    .form-control-minimal:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .form-label-custom {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .santri-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .list-group-item-custom {
        border: 1px solid #f1f5f9;
        border-radius: 12px !important;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .list-group-item-custom:hover {
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }

    .nav-pills .nav-link {
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-weight: 600;
        padding: 10px 20px;
    }
    .nav-pills .nav-link:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .nav-pills .nav-link.active {
        background: var(--primary-gradient);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-arrow-left-right me-2 text-primary"></i>Mutasi & Naik Kelas</h1>
                <p>Kelola perpindahan kelas dan kenaikan kelas santri akhir semester</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-4 gap-2" id="mutasiTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request('tab', 'massal') == 'massal' ? 'active' : '' }}" href="{{ route('admin.mutasi-kelas.index', ['tab' => 'massal']) }}">
                    <i class="fas fa-users me-2"></i>Pindah Kelas Massal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'individu' ? 'active' : '' }}" href="{{ route('admin.mutasi-kelas.index', ['tab' => 'individu']) }}">
                    <i class="fas fa-user me-2"></i>Pindah Kelas Individual
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'naik' ? 'active' : '' }}" href="{{ route('admin.mutasi-kelas.index', ['tab' => 'naik']) }}">
                    <i class="fas fa-graduation-cap me-2"></i>Naik / Lulus Kelas
                </a>
            </li>
        </ul>

        @if(request('tab', 'massal') == 'massal')
        <!-- Tab 1: Pindah Kelas Massal -->
        <div class="row g-4">
            <!-- Kolom Kiri: Pilih Kelas Asal -->
            <div class="col-lg-7">
                <div class="card card-main h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="fas fa-sign-out-alt me-2 text-warning"></i>1. Pilih Kelas Asal</h5>
                        
                        <form action="{{ route('admin.mutasi-kelas.index') }}" method="GET" class="mb-4">
                            <input type="hidden" name="tab" value="massal">
                            <div class="d-flex gap-2">
                                <select name="kelas_asal_id" class="form-select form-control-minimal w-100" required>
                                    <option value="">-- Pilih Kelas Asal --</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}" {{ (request('tab', 'massal') == 'massal' && request('kelas_asal_id') == $k->id) ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-dark px-4" style="border-radius: 12px;">Tampilkan</button>
                            </div>
                        </form>

                        @if(request('tab', 'massal') == 'massal' && request()->has('kelas_asal_id'))
                            @if(count($santris) > 0)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                                    <div class="form-check">
                                        <input class="form-check-input santri-checkbox" type="checkbox" id="checkAll">
                                        <label class="form-check-label fw-bold ms-2 mt-1 cursor-pointer" for="checkAll">
                                            Pilih Semua ({{ count($santris) }} Santri)
                                        </label>
                                    </div>
                                </div>

                                <form id="mutasiForm" action="{{ route('admin.mutasi-kelas.proses') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="kelas_asal_id" value="{{ request('kelas_asal_id') }}">
                                    
                                    <div class="list-group" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                                        @foreach($santris as $santri)
                                        <label class="list-group-item list-group-item-custom d-flex justify-content-between align-items-center cursor-pointer">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3 santri-checkbox item-checkbox" type="checkbox" name="santri_ids[]" value="{{ $santri->id }}">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $santri->nama_lengkap }}</h6>
                                                    <small class="text-muted">NIS: {{ $santri->no_induk }}</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-light text-dark border">{{ $santri->jenis_kelamin }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox me-2 d-block mb-2 fs-3"></i>Tidak ada santri aktif di kelas ini
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Pilih Kelas Tujuan -->
            <div class="col-lg-5">
                <div class="card card-main h-100" style="background: #f8fafc; border: 2px dashed #cbd5e1;">
                    <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                        <div class="mb-4">
                            <div class="d-inline-flex justify-content-center align-items-center bg-white rounded-circle shadow-sm mb-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-sign-in-alt fs-2 text-success"></i>
                            </div>
                            <h5 class="fw-bold">2. Pilih Kelas Tujuan</h5>
                            <p class="text-muted small px-3">Tentukan kelas tujuan untuk santri yang telah dicentang di sebelah kiri.</p>
                        </div>

                        <div class="text-start">
                            <label class="form-label-custom">Kelas Tujuan Baru</label>
                            <select name="kelas_tujuan_id" form="mutasiForm" class="form-select form-control-minimal bg-white" required>
                                <option value="">-- Pilih Kelas Tujuan --</option>
                                @foreach($kelasList as $k)
                                    @if($k->id != request('kelas_asal_id'))
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-5">
                            <button type="submit" form="mutasiForm" class="btn btn-success w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700; font-size: 1.1rem;">
                                <i class="fas fa-paper-plane me-2"></i> Proses Pindah Kelas
                            </button>
                        </div>
                        
                        @if(request('tab', 'massal') == 'massal' && request()->has('kelas_asal_id') && count($santris) > 0)
                            </form> <!-- Tutup form dari sebelah kiri -->
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @elseif(request('tab') == 'individu')
        <!-- Tab 2: Pindah Kelas Individual -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-main">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex justify-content-center align-items-center bg-light rounded-circle mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-tag text-success fs-3"></i>
                            </div>
                            <h4 class="fw-bold">Pindah Kelas Individual</h4>
                            <p class="text-muted small">Pindahkan santri tertentu ke kelas lain secara langsung</p>
                        </div>

                        <form action="{{ route('admin.mutasi-kelas.individu') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label-custom">Pilih Santri</label>
                                <select name="santri_id" id="santri_select_individual" class="form-select form-control-minimal" required>
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach($allSantris as $s)
                                        <option value="{{ $s->id }}" data-kelas="{{ $s->kelas->nama_kelas ?? 'Belum ada kelas' }}">
                                            {{ $s->nama_lengkap }} (NIS: {{ $s->no_induk }} | Kelas: {{ $s->kelas->nama_kelas ?? 'Belum ada kelas' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Kelas Saat Ini (Asal)</label>
                                <input type="text" id="kelas_asal_display" class="form-control form-control-minimal bg-light" readonly placeholder="Pilih santri terlebih dahulu">
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom">Pilih Kelas Tujuan Baru</label>
                                <select name="kelas_tujuan_id" class="form-select form-control-minimal" required>
                                    <option value="">-- Pilih Kelas Tujuan --</option>
                                    @foreach($kelasList as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700;">
                                    <i class="fas fa-exchange-alt me-2"></i> Proses Pindah Kelas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @elseif(request('tab') == 'naik')
        <!-- Tab 3: Naik / Lulus Kelas -->
        <div class="card card-main mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="fas fa-graduation-cap me-2 text-success"></i>Naik / Lulus Kelas Akhir Semester</h5>
                
                <form action="{{ route('admin.mutasi-kelas.index') }}" method="GET" class="mb-4">
                    <input type="hidden" name="tab" value="naik">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-9">
                            <label class="form-label-custom">Pilih Kelas Asal Santri</label>
                            <select name="kelas_asal_id" class="form-select form-control-minimal" required>
                                <option value="">-- Pilih Kelas Asal --</option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}" {{ (request('tab') == 'naik' && request('kelas_asal_id') == $k->id) ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark w-100 py-3" style="border-radius: 12px;">Tampilkan</button>
                        </div>
                    </div>
                </form>

                @if(request('tab') == 'naik' && request()->has('kelas_asal_id'))
                    @if(count($santris) > 0)
                        <form action="{{ route('admin.mutasi-kelas.naik-kelas') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_asal_id" value="{{ request('kelas_asal_id') }}">
                            
                            <div class="table-responsive mt-4">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 30%">Nama Santri</th>
                                            <th style="width: 45%">Aksi Akhir Semester</th>
                                            <th style="width: 25%">Kelas Tujuan (Jika Naik)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($santris as $santri)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0 fw-bold">{{ $santri->nama_lengkap }}</h6>
                                                <small class="text-muted">NIS: {{ $santri->no_induk }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input action-radio" type="radio" name="actions[{{ $santri->id }}]" value="naik" id="naik_{{ $santri->id }}" checked data-santri-id="{{ $santri->id }}">
                                                        <label class="form-check-label text-success fw-bold cursor-pointer" for="naik_{{ $santri->id }}">Naik Kelas</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input action-radio" type="radio" name="actions[{{ $santri->id }}]" value="tetap" id="tetap_{{ $santri->id }}" data-santri-id="{{ $santri->id }}">
                                                        <label class="form-check-label text-warning fw-bold cursor-pointer" for="tetap_{{ $santri->id }}">Tinggal Kelas</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input action-radio" type="radio" name="actions[{{ $santri->id }}]" value="lulus" id="lulus_{{ $santri->id }}" data-santri-id="{{ $santri->id }}">
                                                        <label class="form-check-label text-primary fw-bold cursor-pointer" for="lulus_{{ $santri->id }}">Lulus (Alumni)</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="kelas_tujuan_ids[{{ $santri->id }}]" id="tujuan_{{ $santri->id }}" class="form-select form-control-minimal py-1 px-2 select-tujuan" required>
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($kelasList as $k)
                                                        @if($k->id != request('kelas_asal_id'))
                                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100 py-3 shadow-sm" style="border-radius: 12px; font-weight: 700; font-size: 1.1rem;">
                                    <i class="fas fa-save me-2"></i> Proses Naik / Lulus Kelas
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox me-2 d-block mb-2 fs-3"></i>Tidak ada santri aktif di kelas ini
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Individual santri select change
        const santriSelectIndividu = document.getElementById('santri_select_individual');
        const kelasAsalDisplay = document.getElementById('kelas_asal_display');
        if (santriSelectIndividu && kelasAsalDisplay) {
            santriSelectIndividu.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption) {
                    const kelas = selectedOption.getAttribute('data-kelas');
                    kelasAsalDisplay.value = kelas ? kelas : 'Belum ada kelas';
                } else {
                    kelasAsalDisplay.value = '';
                }
            });
        }

        // Naik kelas radio change
        const actionRadios = document.querySelectorAll('.action-radio');
        actionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const santriId = this.getAttribute('data-santri-id');
                const selectTujuan = document.getElementById('tujuan_' + santriId);
                if (selectTujuan) {
                    if (this.value === 'naik') {
                        selectTujuan.disabled = false;
                        selectTujuan.required = true;
                    } else {
                        selectTujuan.disabled = true;
                        selectTujuan.required = false;
                        selectTujuan.value = '';
                    }
                }
            });
        });

        // Massal Pindah Checkbox Logic
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = checkAll.checked;
                });
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const someChecked = Array.from(checkboxes).some(c => c.checked);
                    checkAll.checked = allChecked;
                    checkAll.indeterminate = someChecked && !allChecked;
                });
            });
        }
        
        const mutasiForm = document.getElementById('mutasiForm');
        if(mutasiForm) {
            mutasiForm.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.item-checkbox:checked');
                if(checked.length === 0) {
                    e.preventDefault();
                    alert('Silakan centang minimal 1 santri untuk dipindahkan!');
                }
            });
        }
    });
</script>
@endsection
