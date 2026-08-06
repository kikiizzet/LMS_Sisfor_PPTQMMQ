<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = [
        'nama_kelas',
        'jumlah_siswa',
        'wali_kelas_id',
        'tingkat',
        'jurusan',
        'jenis',
        'kurikulum',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function teachings()
    {
        return $this->hasMany(Teaching::class, 'kelas_id');
    }

    public function santris()
    {
        return $this->hasMany(Santri::class, 'kelas_id');
    }
}
