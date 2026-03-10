@extends('layout')

@section('main-content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Nilai Santri</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/update-santri/{{ $santri->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Santri</label>
                                <input type="text" name="nama_santri" class="form-control" value="{{ $santri->nama_santri }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Musyrif</label>
                                <input type="text" name="musyrif" class="form-control" value="{{ $santri->musyrif }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Adab</label>
                                <input type="number" name="adab" class="form-control" value="{{ $santri->adab }}" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Kelancaran</label>
                                <input type="number" name="kelancaran" class="form-control" value="{{ $santri->kelancaran }}" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Tajwid</label>
                                <input type="number" name="tajwid" class="form-control" value="{{ $santri->tajwid }}" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Fashahah</label>
                                <input type="number" name="fashahah" class="form-control" value="{{ $santri->fashahah }}" min="0" max="100">
                            </div>
                            
                            <div class="col-12 text-end mt-4">
                                <a href="/daftar" class="btn btn-light border fw-bold me-2 text-muted">Batal</a>
                                <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection