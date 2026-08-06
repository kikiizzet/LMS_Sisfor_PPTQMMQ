<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapels';
    protected $fillable = [
        'kode',
        'nama_mapel',
        'induk',
        'kelompok',
        'jurusan',
        'jjm',
        'urutan',
        'kurikulum',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Teaching
    public function teachings()
    {
        return $this->hasMany(Teaching::class, 'mapel_id');
    }
}
