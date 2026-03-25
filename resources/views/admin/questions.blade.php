@extends('layout')

@section('main-content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-slate-800 mb-1">
                    <i class="bi bi-chat-square-text me-2 text-primary"></i>Kelola FAQ (Knowledge Base)
                </h2>
                <p class="text-muted small mb-0">Kelola pertanyaan umum untuk melatih AI Chatbot</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddFaq">
                <i class="bi bi-plus-lg me-1"></i> Tambah FAQ
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Total Pertanyaan</p>
                                <h3 class="fw-bold mb-0">{{ $questions->total() }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-2 rounded">
                                <i class="bi bi-chat-dots text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Belum Dijawab</p>
                                <h3 class="fw-bold mb-0 text-warning">{{ \App\Models\Question::unanswered()->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-2 rounded">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Sudah Dijawab</p>
                                <h3 class="fw-bold mb-0 text-info">{{ \App\Models\Question::whereNotNull('answer')->count() }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-2 rounded">
                                <i class="bi bi-check2-circle text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1 small">Dipublikasi</p>
                                <h3 class="fw-bold mb-0 text-success">{{ \App\Models\Question::published()->count() }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-2 rounded">
                                <i class="bi bi-eye text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0">Penanya</th>
                                <th class="py-3 border-0">Pertanyaan</th>
                                <th class="py-3 border-0">Jawaban</th>
                                <th class="py-3 border-0 text-center">Status</th>
                                <th class="py-3 border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $q)
                                <tr>
                                    <td class="ps-4">
                                        <div>
                                            <p class="mb-0 fw-bold small">{{ $q->name }}</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem">{{ $q->email }}</p>
                                            <small class="text-muted">{{ $q->created_at->format('d M Y H:i') }}</small>
                                        </div>
                                    </td>
                                    <td style="max-width: 300px;">
                                        <p class="mb-0 small text-truncate">{{ Str::limit($q->question, 100) }}</p>
                                    </td>
                                    <td style="max-width: 300px;">
                                        @if($q->answer)
                                            <p class="mb-0 small text-muted text-truncate">{{ Str::limit($q->answer, 80) }}</p>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning">Belum dijawab</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($q->is_published)
                                            <span class="badge bg-success">
                                                <i class="bi bi-eye me-1"></i>Published
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-eye-slash me-1"></i>Hidden
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAnswer{{ $q->id }}">
                                            <i class="bi bi-pencil-square"></i> Jawab
                                        </button>
                                        <form action="{{ route('admin.questions.publish', $q) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $q->is_published ? 'btn-secondary' : 'btn-success' }}">
                                                <i class="bi {{ $q->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.questions.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Answer -->
                                <div class="modal fade" id="modalAnswer{{ $q->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary bg-opacity-10">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-chat-left-text me-2"></i>Jawab Pertanyaan
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="bg-light p-3 rounded mb-4">
                                                    <p class="mb-2"><strong>Penanya:</strong> {{ $q->name }} ({{ $q->email }})</p>
                                                    <p class="mb-0"><strong>Pertanyaan:</strong></p>
                                                    <p class="mb-0">{{ $q->question }}</p>
                                                </div>
                                                <form action="{{ route('admin.questions.update', $q) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Jawaban Anda</label>
                                                        <textarea name="answer" class="form-control" rows="6" required placeholder="Tulis jawaban yang informatif dan membantu...">{{ $q->answer }}</textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">
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
                                        <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                        <p class="text-muted">Belum ada pertanyaan dari pengunjung</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($questions->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Add FAQ -->
<div class="modal fade" id="modalAddFaq" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Tambah FAQ Baru (Knowledge Base)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Pertanyaan yang Anda tambahkan di sini akan langsung dipublikasikan dan digunakan oleh <strong>AI Chatbot</strong> sebagai basis pengetahuan.
                </div>
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Pertanyaan Utama</label>
                        <input type="text" name="question" class="form-control" required placeholder="Contoh: Kapan pendaftaran gelombang 2 dibuka?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Jawaban/Informasi</label>
                        <textarea name="answer" class="form-control" rows="6" required placeholder="Berikan jawaban yang detail agar AI bisa memberikan informasi yang akurat kepada wali santri..."></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i>Simpan FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
