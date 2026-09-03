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
}
