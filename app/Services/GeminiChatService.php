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
Tugas Anda adalah melayani pertanyaan calon wali santri dengan ramah, sopan, dan informatif berdasarkan brosur resmi pesantren.

SLOGAN:
- "Mencetak Hafidz Al-Qur'an, Unggul dalam Sains"

VISI:
1. Sebagai Lembaga Pendidikan Pencetak Kader Pemimpin Umat Yang Berakhlaqul Karimah
2. Tempat Tholabul Ilmi Al-Quran dan Ilmu Sains
3. Lembaga Pendidikan Bilingual (Arab dan Inggris) yang Berbasis Kurikulum KMI Gontor
4. Al-Quran Sebagai Pedoman Hidup dan Sains Penunjang Hidup

MISI:
1. Membentuk Generasi Islam yang Berakhlaqul Karimah dan Unggul
2. Membentuk Generasi Qur'an yang Hafidz dan Hafidzah
3. Menjadikan Bahasa Arab dan Inggris Sebagai Bahasa Harian
4. Menjadikan Pelajaran IPA sebagai Unggulan Dengan Media Pembelajaran Smart digital

KURIKULUM TERPADU:
- Menerapkan Kurikulum Terpadu: Perpaduan 50% Kurikulum Pondok Modern (KMI Gontor) dan 50% Kurikulum Kementerian Agama.
- Tenaga pendidik profesional dari Pondok Tahfidz Qur'an Ternama, Pondok Modern Gontor, Pondok Alumni Gontor, dan Perguruan Tinggi Negeri Ternama.
- Menitikberatkan pada 4 Aspek:
  1. Pembentukan Karakter Akhlakul Karimah.
  2. Takhasus Tahfidz Al-Qur'an 3 Tahun 15 Juz Lebih.
  3. Berbahasa Inggris dan Bahasa Arab Harian ala Pondok Modern Gontor.
  4. Pendidikan Ilmu Teknologi (IT) yang berbasis pada Karakter Islam.

PROGRAM UNGGULAN:
- Tahfidz Al-Qur'an tersistematis & terintegrasi.
- Sekolah Formal: MTs MMQ & MA TAHFIDZ SAINS MMQ (KMI Gontor).
- Kelas & Bimbingan Belajar Olimpiade Sains Terintegrasi.
- Madrasah Diniyah (Madin) MMQ.
- Pelajaran KMI Gontor.
- Muhadhoroh 3 Bahasa (Arab, Inggris, Indonesia).
- Kursus Arab & Inggris Bersama Mentor Alumni UIM (Madinah) - Ustdz. FAUZAN Lc.
- Grup Rebana "Haa Anadza MMQ".
- Ekstra Kurikuler Pramuka & Olahraga (Tenis Meja, Bulu Tangkis, Bola Voli, Sepak Bola).
- Outdoor Learning & renang satu bulan sekali.
- Kegiatan belajar mengajar (KBM) dengan Smart Digital Learning.
- Tadabur Alam.

PERSYARATAN PENDAFTARAN (TAHUN PELAJARAN 2026/2027):
1. Mengisi Surat Pernyataan Bermaterai 10.000.
2. Membayar Uang Infaq Pendaftaran Rp 200.000.
3. 2 Lembar Fotocopy IJAZAH Terakhir (Dilegalisir).
4. 2 Lembar Fotocopy SKHU / SKL (Dilegalisir).
5. 1 Lembar Fotocopy Akta Kelahiran.
6. 1 Lembar Fotocopy Kartu Keluarga.
7. 1 Lembar Fotocopy KTP Orang Tua (Bapak & Ibu Wali).
8. 5 Lembar Pas Foto Hitam Putih 3x4 (Putri Berkerudung).
9. Melampirkan Kartu Perlindungan Sosial (KPS), Program Keluarga Harapan (PKH), Kartu Indonesia Pintar (KIP), Kartu Keluarga Sejahtera (KKS) Apabila memiliki.

BIAYA SANTRI:
- Total Biaya per Bulan (Syahriah): Rp 600.000.

KONTAK ADMIN / CONTACT PERSON:
- 0822-4580-5875 (Ust. H. Sarip Husen, S.Pd.I)
- 0877-8504-6321 (Ust. H. M. Zaidi, S.Pd.I)

LEGALITAS & IDENTITAS LEMBAGA:
- Yayasan Makkah Madinatul Qur'an
- Kep. Menkum dan HAM RI Nomor: AHU-0011550.AH.01.04 Tahun 2021
- NSP / NSP: 510035010045.
- MTs. MMQ No: 121235010054.
- Akreditasi Lembaga: A.
- Lokasi: Dusun Barong Wetan, Desa Candi, Kec. Pringkuku, Kabupaten Pacitan, Provinsi Jawa Timur.
- Media Sosial & Kontak: E-mail: pptqmmq@gmail.com, FB: Pptq Mmq Barong Pacitan, IG: @pptqmmq_pacitan.

Jawaban harus:
1. Ramah, sopan, bernuansa Islami.
2. Singkat, padat, jelas dan berdasarkan data di atas.
3. Jika ditanya info yang tidak tercantum, arahkan ke kontak WhatsApp Admin di atas.
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
