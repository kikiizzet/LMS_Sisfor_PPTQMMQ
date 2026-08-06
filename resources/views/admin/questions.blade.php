@extends('layout')

@section('main-content')
<div class="content-wrapper">
    <div class="container-lg mt-4 mb-5">
        <!-- Header Section -->
        <div class="page-header">
            <div class="page-header-left">
                <h1><i class="bi bi-chat-square-text-fill me-2 text-primary"></i>Kelola FAQ (Knowledge Base)</h1>
                <p>Kelola pertanyaan umum untuk melatih AI Chatbot</p>
            </div>
            <button class="btn btn-primary" style="border-radius: 12px; font-weight: 600; padding: 10px 20px;" data-bs-toggle="modal" data-bs-target="#modalAddFaq">
                <i class="bi bi-plus-lg me-2"></i>Tambah FAQ
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Total Pertanyaan</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $questions->total() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                                <i class="bi bi-chat-dots text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Belum Dijawab</p>
                                <h3 class="fw-bold mb-0 text-warning">{{ \App\Models\Question::unanswered()->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded-3">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Sudah Dijawab</p>
                                <h3 class="fw-bold mb-0 text-info">{{ \App\Models\Question::whereNotNull('answer')->count() }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded-3">
                                <i class="bi bi-check2-circle text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase fw-bold" style="letter-spacing: 0.5px; font-size: 0.7rem;">Dipublikasi</p>
                                <h3 class="fw-bold mb-0 text-success">{{ \App\Models\Question::published()->count() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded-3">
                                <i class="bi bi-eye text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="card card-main">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 border-0">Penanya</th>
                                <th class="border-0">Pertanyaan</th>
                                <th class="border-0">Jawaban</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center" style="width: 180px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $q)
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <p class="mb-0 fw-bold small text-dark">{{ $q->name }}</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem">{{ $q->email }}</p>
                                            <small class="text-muted" style="font-size: 0.7rem">{{ $q->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <p class="mb-0 small text-dark text-wrap">{{ Str::limit($q->question, 100) }}</p>
                                    </td>
                                    <td style="max-width: 250px;">
                                        @if($q->answer)
                                            <p class="mb-0 small text-muted text-wrap">{{ Str::limit($q->answer, 80) }}</p>
                                        @else
                                            <span class="status-badge status-pindah">Belum dijawab</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($q->is_published)
                                            <span class="status-badge status-aktif">
                                                <i class="bi bi-eye me-1"></i>Published
                                            </span>
                                        @else
                                            <span class="status-badge" style="background: #e2e8f0; color: #475569;">
                                                <i class="bi bi-eye-slash me-1"></i>Hidden
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button class="btn btn-sm btn-primary px-3" style="border-radius: 8px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#modalAnswer{{ $q->id }}">
                                                <i class="bi bi-pencil-square"></i> Jawab
                                            </button>
                                            <form action="{{ route('admin.questions.publish', $q) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $q->is_published ? 'btn-secondary' : 'btn-success' }}" style="border-radius: 8px; width: 32px; height: 32px; padding: 0;" title="{{ $q->is_published ? 'Sembunyikan' : 'Publikasikan' }}">
                                                    <i class="bi {{ $q->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.questions.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; width: 32px; height: 32px; padding: 0;" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Answer -->
                                <div class="modal fade" id="modalAnswer{{ $q->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
                                            <div class="modal-header bg-primary bg-opacity-10 border-0 p-4">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-chat-left-text me-2 text-primary"></i>Jawab Pertanyaan
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="bg-light p-3 rounded-4 mb-4">
                                                    <p class="mb-2"><strong>Penanya:</strong> {{ $q->name }} ({{ $q->email }})</p>
                                                    <p class="mb-0"><strong>Pertanyaan:</strong></p>
                                                    <p class="mb-0 text-muted">{{ $q->question }}</p>
                                                </div>
                                                <form action="{{ route('admin.questions.update', $q) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 0.5px;">Jawaban Anda</label>
                                                        <textarea name="answer" class="form-control" rows="6" required placeholder="Tulis jawaban yang informatif dan membantu..." style="border-radius: 12px; border: 2px solid #f0f0f0;">{{ $q->answer }}</textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-light border px-4" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px; font-weight: 600;">
                                                            <i class="bi bi-check-circle me-1"></i>Simpan Jawaban
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-3 opacity-50"></i>
                                        <p class="text-muted">Belum ada pertanyaan dari pengunjung</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($questions->hasPages())
                <div class="card-footer bg-white border-top p-3">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Add FAQ -->
<div class="modal fade" id="modalAddFaq" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Tambah FAQ Baru (Knowledge Base)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 mb-4 rounded-4" style="background: rgba(13, 202, 240, 0.1); color: #055160;">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Pertanyaan yang Anda tambahkan di sini akan langsung dipublikasikan dan digunakan oleh <strong>AI Chatbot</strong> sebagai basis pengetahuan.
                </div>
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 0.5px;">Pertanyaan Utama</label>
                        <input type="text" name="question" class="form-control" required placeholder="Contoh: Kapan pendaftaran gelombang 2 dibuka?" style="border-radius: 12px; border: 2px solid #f0f0f0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 0.5px;">Jawaban/Informasi</label>
                        <textarea name="answer" class="form-control" rows="6" required placeholder="Berikan jawaban yang detail agar AI bisa memberikan informasi yang akurat kepada wali santri..." style="border-radius: 12px; border: 2px solid #f0f0f0;"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light border px-4" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px; font-weight: 600;">
                            <i class="bi bi-check-circle me-1"></i>Simpan FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
