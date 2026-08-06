<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $table = 'santris';
    protected $fillable = [
        'nama_lengkap',
        'no_induk',
        'nisn',
        'kelas_id',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status',
        'tahun_lulus',
        'keterangan_alumni',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'santri_id');
    }
}
