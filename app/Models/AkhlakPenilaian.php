<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkhlakPenilaian extends Model
{
    protected $table = 'akhlak_penilaians';

    protected $fillable = [
        'santri_id',
        'guru_id',
        'tanggal',
        'skor_ibadah',
        'skor_adab',
        'skor_kebersihan',
        'skor_kegiatan',
        'skor_kedisiplinan',
        'total_skor',
        'nilai_angka',
        'predikat',
        'nilai_fuzzy',
        'tipe_penilai',
        'catatan_pelanggaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'skor_ibadah' => 'integer',
        'skor_adab' => 'integer',
        'skor_kebersihan' => 'integer',
        'skor_kegiatan' => 'integer',
        'skor_kedisiplinan' => 'integer',
        'total_skor' => 'integer',
        'nilai_angka' => 'float',
        'nilai_fuzzy' => 'float',
    ];

    /**
     * Boot model untuk menghitung otomatis skor, nilai angka (0-100), predikat, dan nilai fuzzy
     */
    protected static function booted()
    {
        static::saving(function ($penilaian) {
            $hasil = self::hitungNilaiDanFuzzy(
                $penilaian->skor_ibadah ?? 4,
                $penilaian->skor_adab ?? 4,
                $penilaian->skor_kebersihan ?? 4,
                $penilaian->skor_kegiatan ?? 4,
                $penilaian->skor_kedisiplinan ?? 4
            );

            $penilaian->total_skor = $hasil['total_skor'];
            $penilaian->nilai_angka = $hasil['nilai_angka'];
            $penilaian->predikat = $hasil['predikat'];
            $penilaian->nilai_fuzzy = $hasil['nilai_fuzzy'];
        });
    }

    /**
     * Hitung akumulasi skor, konversi nilai (0-100), predikat linguistik,
     * serta nilai representasi fuzzy sesuai Bab 3 Proposal Skripsi
     */
    public static function hitungNilaiDanFuzzy($ibadah, $adab, $kebersihan, $kegiatan, $kedisiplinan)
    {
        $totalSkor = (int)$ibadah + (int)$adab + (int)$kebersihan + (int)$kegiatan + (int)$kedisiplinan;
        $maxSkor = 5 * 4; // 5 indikator x skor maksimal 4 = 20
        $nilaiAngka = round(($totalSkor / $maxSkor) * 100, 2);

        // Pengelompokan predikat sesuai Tabel 3.13 proposal
        if ($nilaiAngka >= 90) {
            $predikat = 'Sangat Baik';
            $nilaiFuzzy = 95.0; // Puncak segitiga (90; 95; 100)
        } elseif ($nilaiAngka >= 80) {
            $predikat = 'Baik';
            $nilaiFuzzy = 85.0; // Puncak segitiga (80; 85; 90)
        } elseif ($nilaiAngka >= 75) {
            $predikat = 'Cukup';
            $nilaiFuzzy = 77.5; // Puncak segitiga (75; 77.5; 80)
        } else {
            $predikat = 'Kurang';
            $nilaiFuzzy = 37.5; // Puncak segitiga (0; 37.5; 75)
        }

        return [
            'total_skor' => $totalSkor,
            'nilai_angka' => $nilaiAngka,
            'predikat' => $predikat,
            'nilai_fuzzy' => $nilaiFuzzy,
        ];
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}

