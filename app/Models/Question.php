<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'question',
        'answer',
        'is_published',
        'answered_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'answered_at' => 'datetime',
    ];

    /**
     * Scope untuk pertanyaan yang sudah dipublish
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope untuk pertanyaan yang belum dijawab
     */
    public function scopeUnanswered($query)
    {
        return $query->whereNull('answer');
    }
}
