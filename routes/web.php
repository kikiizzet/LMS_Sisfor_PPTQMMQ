<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PublicRaportController;

// Rute Bawaan Breeze (Login/Register)
require __DIR__.'/auth.php';

// Landing Page (Public - Tidak perlu login)
Route::get('/', function () {
    $publishedQuestions = \App\Models\Question::published()
        ->latest()
        ->take(10)
        ->get();
    return view('landing', compact('publishedQuestions'));
})->name('landing');

// Halaman Donasi (Public - Tidak perlu login)
Route::get('/donasi', function () {
    return view('donasi');
})->name('donasi');

// Chatbot Test Page (for debugging)
Route::get('/chatbot-test', function () {
    return view('chatbot-test');
});

// Submit Pertanyaan (Public - Tidak perlu login)
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

// Chatbot Test Endpoint
Route::get('/api/chatbot/test', function() {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Chatbot Diagnostic Dashboard
Route::get('/chatbot-status', function() {
    $keysConfig = config('services.gemini.api_keys');
    if (is_string($keysConfig)) {
        $apiKeys = array_map('trim', explode(',', $keysConfig));
    } else {
        $apiKeys = is_array($keysConfig) ? $keysConfig : [];
    }

    // Hardcode model to reliable one for testing
    $model = 'gemini-2.5-flash';
    $results = [];

    foreach ($apiKeys as $index => $key) {
        if (empty($key)) continue;

        $startTime = microtime(true);
        $status = 'BERHASIL';
        $message = 'Koneksi sukses';
        $error = null;

        try {
            // Simple test call with strict timeout
            $response = Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(5)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    ['parts' => [['text' => 'Tes koneksi singkat.']]]
                ]
            ]);

            if ($response->successful()) {
                $status = 'BERHASIL';
            } else {
                $status = 'GAGAL';
                $error = "HTTP " . $response->status() . ": " . Str::limit($response->body(), 100);
            }
        } catch (\Exception $e) {
            $status = 'GAGAL';
            $error = "Exception: " . $e->getMessage();
        }

        $duration = round(microtime(true) - $startTime, 2);

        $results[] = [
            'key_index' => $index + 1, // 1-based index for display
            'key_masked' => substr($key, 0, 4) . '...' . substr($key, -4),
            'status' => $status,
            'duration' => $duration . 's',
            'error' => $error,
        ];
    }

    return view('chatbot-status', compact('results', 'model'));
});

// Chatbot API (Public - Tidak perlu login)
Route::post('/api/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.message');
Route::get('/api/chatbot/quick-replies', [\App\Http\Controllers\ChatbotController::class, 'getQuickReplies'])->name('chatbot.quickReplies');

// Raport Publik (Public - Orang tua akses via token, tanpa login)
Route::get('/raport/{token}', [PublicRaportController::class, 'show'])->name('raport.public');

// Semua rute di bawah ini HANYA bisa diakses jika sudah LOGIN
Route::middleware(['auth'])->group(function () {

    // Halaman Dashboard (setelah login)
    Route::get('/dashboard', [RaportController::class, 'dashboard'])->name('dashboard');

    // Halaman Entry Nilai
    Route::get('/input', [RaportController::class, 'index']);
    Route::post('/simpan-nilai', [RaportController::class, 'store']);

    // Halaman Daftar & Manajemen
    Route::get('/daftar', [RaportController::class, 'list']);
    Route::get('/edit-santri/{id}', [RaportController::class, 'edit']);
    Route::put('/update-santri/{id}', [RaportController::class, 'update']);
    Route::delete('/hapus-santri/{id}', [RaportController::class, 'destroy']);

    // Halaman Cetak
    Route::get('/cetak/{id}', [RaportController::class, 'cetak'])->name('raport.cetak');
    Route::get('/cetak-semua', [RaportController::class, 'cetakSemua'])->name('raport.cetak_semua');

    // Halaman Rekapitulasi (KMI & Tahfidz)
    Route::get('/rekapitulasi', [RaportController::class, 'rekapitulasi'])->name('rekapitulasi');

    // Musyrif Management Routes (CRUD)
    Route::post('/musyrif/store', [App\Http\Controllers\MusyrifController::class, 'store']);
    Route::put('/musyrif/{id}', [App\Http\Controllers\MusyrifController::class, 'update']);
    Route::delete('/musyrif/{id}', [App\Http\Controllers\MusyrifController::class, 'destroy']);

    // Logout route
    Route::post('/logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    // Raport KMI Management Routes
    Route::get('/raport-kmi', [App\Http\Controllers\RaportKmiController::class, 'index'])->name('raport-kmi.index');
    Route::get('/raport-kmi/create', [App\Http\Controllers\RaportKmiController::class, 'create'])->name('raport-kmi.create');
    Route::post('/raport-kmi/store', [App\Http\Controllers\RaportKmiController::class, 'store'])->name('raport-kmi.store');
    Route::get('/raport-kmi/edit/{id}', [App\Http\Controllers\RaportKmiController::class, 'edit'])->name('raport-kmi.edit');
    Route::put('/raport-kmi/update/{id}', [App\Http\Controllers\RaportKmiController::class, 'update'])->name('raport-kmi.update');
    Route::delete('/raport-kmi/hapus/{id}', [App\Http\Controllers\RaportKmiController::class, 'destroy'])->name('raport-kmi.destroy');
    Route::get('/raport-kmi/cetak/{id}', [App\Http\Controllers\RaportKmiController::class, 'cetak'])->name('raport-kmi.cetak');
    Route::post('/raport-kmi/import', [App\Http\Controllers\RaportKmiController::class, 'importCsv'])->name('raport-kmi.import');
    Route::get('/raport-kmi/download-template', [App\Http\Controllers\RaportKmiController::class, 'downloadTemplate'])->name('raport-kmi.download-template');
    Route::get('/raport-kmi/grid', [App\Http\Controllers\RaportKmiController::class, 'grid'])->name('raport-kmi.grid');
    Route::post('/raport-kmi/store-ajax', [App\Http\Controllers\RaportKmiController::class, 'storeAjax'])->name('raport-kmi.store-ajax');
    Route::post('/raport-kmi/update-score', [App\Http\Controllers\RaportKmiController::class, 'updateScoreAjax'])->name('raport-kmi.update-score');
    Route::post('/raport-kmi/update-detail', [App\Http\Controllers\RaportKmiController::class, 'updateDetailAjax'])->name('raport-kmi.update-detail');

    // Admin Question Management (FAQ/Comment System)
    Route::get('/admin/questions', [QuestionController::class, 'index'])->name('admin.questions.index');
    Route::put('/admin/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::post('/admin/questions/{question}/publish', [QuestionController::class, 'togglePublish'])->name('admin.questions.publish');
    Route::delete('/admin/questions/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');

    // Generate Token Raport (Admin only)
    Route::post('/generate-token', [PublicRaportController::class, 'generateToken'])->name('raport.generate-token');
});
