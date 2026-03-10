<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportKmi extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'nilai_mapel' => 'array',
        'ekstrakurikuler' => 'array',
    ];
}
