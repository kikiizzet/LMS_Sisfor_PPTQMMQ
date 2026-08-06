<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'gurus';
    protected $fillable = [
        'nik',
        'nuptk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'wali_kelas',
        'jtm',
        'foto',
        'ttd',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_lahir' => 'date'
    ];

    // Relasi
    public function ekstrakurikulers()
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }

    public function teachings()
    {
        return $this->hasMany(Teaching::class, 'guru_id');
    }

    /**
     * Cari Guru dengan pencocokan nama yang fleksibel/toleran
     */
    public static function findByName($inputName)
    {
        if (empty($inputName) || $inputName === '-' || strtolower($inputName) === 'belum diisi') {
            return null;
        }

        // 1. Coba exact match
        $guru = self::where('nama', $inputName)->first();
        if ($guru) {
            return $guru;
        }

        // 2. Coba case-insensitive exact match
        $guru = self::whereRaw('LOWER(nama) = ?', [strtolower($inputName)])->first();
        if ($guru) {
            return $guru;
        }

        // Helper fungsi normalisasi nama
        $normalize = function($name) {
            $name = strtolower($name);
            // Hapus gelar kehormatan/prefix sapaan
            $name = preg_replace('/^(ustadz|ustad|ust|ustdz|h|hj|haji)\.?\s+/i', '', $name);
            // Hapus gelar akademis/suffix
            $name = preg_replace('/,?\s*(s\.?pd\.?i|s\.?pd|s\.?ag|m\.?si|s\.?h\.?i|l\.?c)\b/i', '', $name);
            // Hapus spasi dan simbol non-alfanumerik
            $name = preg_replace('/[^a-z0-9]/', '', $name);
            return $name;
        };

        $normalizedInput = $normalize($inputName);
        if (empty($normalizedInput)) {
            return null;
        }

        $gurus = self::all();
        
        // 3. Pencocokan normalisasi
        foreach ($gurus as $g) {
            $normalizedGuru = $normalize($g->nama);
            if ($normalizedGuru === $normalizedInput || 
                (!empty($normalizedGuru) && str_contains($normalizedInput, $normalizedGuru)) || 
                (!empty($normalizedInput) && str_contains($normalizedGuru, $normalizedInput))) {
                return $g;
            }
        }

        // 4. Pencocokan khusus "Syarif" dan "Sarip"
        if (str_contains($normalizedInput, 'syarif') || str_contains($normalizedInput, 'sarip')) {
            foreach ($gurus as $g) {
                $normalizedGuru = $normalize($g->nama);
                if (str_contains($normalizedGuru, 'syarif') || str_contains($normalizedGuru, 'sarip')) {
                    return $g;
                }
            }
        }

        return null;
    }
}
