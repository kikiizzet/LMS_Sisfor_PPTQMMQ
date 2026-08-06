<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    protected $table = 'ekstrakurikulers';
    protected $fillable = [
        'nama_ekstrakurikuler',
        'pembina_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi
    public function pembina()
    {
        return $this->belongsTo(Guru::class, 'pembina_id');
    }
}
