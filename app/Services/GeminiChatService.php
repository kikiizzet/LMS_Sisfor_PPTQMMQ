<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Question;

class GeminiChatService
{
    private array $apiKeys;
    private string $model;
    private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        // Load keys from config (comma separated string supported)
        $keysConfig = config('services.gemini.api_keys');
        if (is_string($keysConfig)) {
            $this->apiKeys = array_map('trim', explode(',', $keysConfig));
        } else {
            $this->apiKeys = is_array($keysConfig) ? $keysConfig : [];
        }

        // Force use the reliable model found in debug list
        // gemini-1.5-flash & gemini-pro -> 404. gemini-2.0-flash -> 429.
        $this->model = 'gemini-2.5-flash'; 
    }

    public function sendMessage(string $userMessage, array $history = []): array
    {
        if (empty($this->apiKeys)) {
            return ['success' => false, 'message' => 'API Keys belum dikonfigurasi.'];
        }

        // 1. Cek Cache (untuk pertanyaan berulang yang sama persis)
        $cacheKey = 'gemini_reply_' . md5(strtolower(trim($userMessage)));
        if (empty($history) && Cache::has($cacheKey)) {
            return ['success' => true, 'message' => Cache::get($cacheKey)];
        }

        // 2. Siapkan Context
        $context = $this->getSystemContext();
        $contents = [];
        
        // Add System Instruction (Context)
        $contents[] = ['role' => 'user', 'parts' => [['text' => $context]]];
        $contents[] = ['role' => 'model', 'parts' => [['text' => 'Mengerti, saya siap membantu.']]];

        // Add History
        foreach ($history as $msg) {
            $contents[] = ['role' => $msg['role'], 'parts' => [['text' => $msg['content']]]];
        }

        // Add Current Message
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        // 3. Loop: Coba Setiap Key Sampai Berhasil
        foreach ($this->apiKeys as $index => $apiKey) {
            if (empty($apiKey)) continue;

            try {
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(15) // Batas waktu 15 detik per request
                    ->post("{$this->apiUrl}{$this->model}:generateContent?key={$apiKey}", [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 1000,
                        ]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($reply) {
                        // Simpan cache 1 jam
                        if (empty($history)) {
                            Cache::put($cacheKey, $reply, 3600);
                        }
                        return ['success' => true, 'message' => $reply];
                    }
                } else {
                    // Log error tapi LANJUT ke key berikutnya
                    Log::warning("Gemini Key #{$index} Failed: " . $response->body());
                }

            } catch (\Exception $e) {
                Log::error("Gemini Key #{$index} Exception: " . $e->getMessage());
                // Lanjut ke key berikutnya
            }
        }

        // Jika sampai sini, berarti SEMUA key gagal
        return [
            'success' => false,
            'message' => 'Mohon maaf, sistem sedang sibuk saat ini. Silakan coba beberapa saat lagi.'
        ];
    }

    public function getQuickReplies(): array
    {
        return [
            'Biaya Pendaftaran', 'Cara Daftar', 'Syarat Pendaftaran', 
            'Jadwal Pendaftaran', 'Tentang Pesantren', 'Lokasi'
        ];
    }

    private function getSystemContext(): string
    {
        // Gabungkan Context Statis & FAQ Dinamis
        $staticContext = $this->getPesantrenContext();
        $faqContext = $this->getFAQContext();
        return $staticContext . "\n\n" . $faqContext;
    }

    private function getPesantrenContext(): string
    {
        return <<<EOT
Anda adalah asisten virtual resmi PPTQ Makkah Madinatul Qur'an (MMQ) Pacitan.
Tugas anda adalah melayani pertanyaan calon wali santri dengan ramah, sopan, dan informatif.

INFORMASI UTAMA:
- Nama: PPTQ Makkah Madinatul Qur'an (MMQ)
- Alamat: RT 001 RW 004, Dusun Barong Wetan, Desa Candi, Kec. Pringkuku, Pacitan, Jawa Timur.
- Program: Tahfidz Al-Qur'an & Sekolah Formal (MA & MTs).
- Target: Hafal 15 Juz dalam 3 tahun.
- Biaya Bulanan (Syahriah): Rp 600.000 (Sangat Terjangkau).
- Uang Masuk/Pendaftaran: Rp 200.000.
- Kontak Admin: Ustadz Syarif (0822-4580-5875), Ustadz Zaidi (0877-8504-6321).

JADWAL PENDAFTARAN 2026/2027:
- Gelombang 1: 19 Januari - 31 Maret 2026
- Gelombang 2: 6 April - 13 Juni 2026

BIAYA MASUK:
- Formulir: Rp 200.000
- Seragam, Buku, dll: (Silakan hubungi admin untuk detail rincian kitab/seragam)

Jawaban harus:
1. Menggunakan Bahasa Indonesia yang baik.
2. Singkat, padat, jelas.
3. Jika tidak tahu, arahkan ke WhatsApp Admin.
EOT;
    }

    private function getFAQContext(): string
    {
        try {
            $faqs = Question::published()->latest()->take(5)->get();
            if ($faqs->isEmpty()) return "";

            $text = "FAQ (Pertanyaan Umum):\n";
            foreach ($faqs as $q) {
                $text .= "T: {$q->question}\nJ: {$q->answer}\n";
            }
            return $text;
        } catch (\Exception $e) {
            return "";
        }
    }
}
