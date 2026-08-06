@extends('layout')

@section('main-content')
<style>
    .content-wrapper {
        background: radial-gradient(circle at top right, #f8faff, #ffffff);
        min-height: 100vh;
    }

    .card-main {
        border: none;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    }

    .form-group label {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        padding: 10px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0061ff;
        box-shadow: 0 0 0 3px rgba(0, 97, 255, 0.1);
    }

    .btn-submit {
        border-radius: 12px;
        padding: 12px 32px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .photo-preview {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    [data-theme="dark"] .card-main {
        background: rgba(30, 41, 59, 0.95);
    }

    [data-theme="dark"] .form-group label {
        color: #e2e8f0;
    }
</style>

<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('admin.guru.index') }}" class="text-muted text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <h1 class="h3 fw-bold mt-2">
                <i class="fas fa-user-tie me-2"></i>Edit Data Guru
            </h1>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Card -->
        <div class="card card-main">
            <div class="card-body p-4">
                <form action="{{ route('admin.guru.update', $guru) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Foto -->
                        <div class="col-md-4 text-center mb-4">
                            <div class="form-group">
                                <label class="d-block mb-3">Foto Guru</label>
                                @if($guru->foto)
                                    <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->nama }}" class="photo-preview mb-3" id="photoPreview">
                                @else
                                    <div class="photo-preview mb-3 bg-light d-flex align-items-center justify-content-center" id="photoPreview">
                                        <i class="fas fa-camera fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput" onchange="previewPhoto()">
                                <small class="text-muted d-block mt-2">Max 2MB, format: JPEG, PNG, JPG, GIF</small>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="col-md-8">
                            <!-- Nama -->
                            <div class="form-group mb-3">
                                <label>Nama Guru <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required>
                            </div>

                            <div class="row">
                                <!-- NIK -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" name="nik" class="form-control" value="{{ old('nik', $guru->nik) }}">
                                    </div>
                                </div>

                                <!-- NUPTK -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>NUPTK</label>
                                        <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="form-group mb-3">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div class="row">
                                <!-- Tempat Lahir -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $guru->tempat_lahir) }}">
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $guru->tanggal_lahir?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Pendidikan -->
                            <div class="form-group mb-3">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan" class="form-control" placeholder="Contoh: S1, S2, S3" value="{{ old('pendidikan', $guru->pendidikan) }}">
                            </div>

                            <div class="row">
                                <!-- Wali Kelas -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Wali Kelas</label>
                                        <input type="text" name="wali_kelas" class="form-control" value="{{ old('wali_kelas', $guru->wali_kelas) }}">
                                    </div>
                                </div>

                                <!-- JTM -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>JTM (Jam Tugas Mengajar)</label>
                                        <input type="number" name="jtm" class="form-control" value="{{ old('jtm', $guru->jtm) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $guru->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        Status Aktif
                                    </label>
                                </div>
                            </div>

                            <!-- Tanda Tangan Digital -->
                            <div class="form-group mb-4">
                                <label class="d-block mb-2">Tanda Tangan Digital</label>
                                <div class="signature-pad-wrapper" style="border: 2px dashed #cbd5e1; border-radius: 16px; background: #f8fafc; padding: 12px; max-width: 450px;">
                                    <canvas id="signatureCanvas" style="width: 100%; height: 180px; border: 1px solid #e2e8f0; background: #ffffff; border-radius: 12px; cursor: crosshair; touch-action: none; display: block;"></canvas>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Tulis tanda tangan di atas canvas</small>
                                        <button type="button" class="btn btn-sm btn-danger px-3" id="clearSignature" style="border-radius: 8px;">
                                            <i class="fas fa-eraser me-1"></i>Hapus
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="ttd" id="ttdInput" value="{{ old('ttd', $guru->ttd) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary btn-submit">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Signature Canvas Setup
const canvas = document.getElementById('signatureCanvas');
const ctx = canvas.getContext('2d');
const ttdInput = document.getElementById('ttdInput');
const clearBtn = document.getElementById('clearSignature');

let isDrawing = false;
let lastX = 0;
let lastY = 0;

// Initialize canvas size and styling once
function initCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
    
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    // Restore signature if editing and exists
    @if($guru->ttd)
        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        img.src = "{{ $guru->ttd }}";
    @endif
}

// Draw to canvas
function draw(x, y) {
    if (!isDrawing) return;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.stroke();
    [lastX, lastY] = [x, y];
    ttdInput.value = canvas.toDataURL('image/png');
}

// Mouse events
canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
});

canvas.addEventListener('mousemove', (e) => {
    draw(e.offsetX, e.offsetY);
});

canvas.addEventListener('mouseup', () => isDrawing = false);
canvas.addEventListener('mouseout', () => isDrawing = false);

// Touch events for mobile devices
canvas.addEventListener('touchstart', (e) => {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    const touch = e.touches[0];
    lastX = touch.clientX - rect.left;
    lastY = touch.clientY - rect.top;
});

canvas.addEventListener('touchmove', (e) => {
    if (!isDrawing) return;
    e.preventDefault(); // Stop scrolling
    const rect = canvas.getBoundingClientRect();
    const touch = e.touches[0];
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    draw(x, y);
});

canvas.addEventListener('touchend', () => isDrawing = false);

// Clear signature
clearBtn.addEventListener('click', function(e) {
    e.preventDefault();
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ttdInput.value = '';
});

// Photo preview logic
function previewPhoto() {
    const file = document.getElementById('fotoInput').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'photo-preview mb-3';
                img.id = 'photoPreview';
                preview.parentNode.insertBefore(img, preview);
                preview.remove();
            }
        };
        reader.readAsDataURL(file);
    }
}

// Init on load
window.addEventListener('load', initCanvas);
</script>
@endsection
