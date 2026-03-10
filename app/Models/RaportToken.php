<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RaportToken extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Check if the token has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get the public URL for this token.
     */
    public function getUrl(): string
    {
        return url('/raport/' . $this->token);
    }

    /**
     * Get the WhatsApp share URL with a pre-filled message.
     */
    public function getWhatsappUrl(): string
    {
        $message = "Assalamualaikum,\n\nBerikut link raport Ananda *{$this->nama_santri}*:\n{$this->getUrl()}\n\nJazakumullahu khairan 🙏\n\n_Powered by MMQ Digital_";
        return 'https://api.whatsapp.com/send?text=' . urlencode($message);
    }

    /**
     * Generate a new unique token for a santri.
     * If a token already exists (and is not expired), return it instead.
     */
    public static function generateFor(string $namaSantri, ?int $expiresInDays = 90): self
    {
        $existing = static::where('nama_santri', $namaSantri)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existing) {
            return $existing;
        }

        return static::create([
            'nama_santri' => $namaSantri,
            'token' => Str::random(32),
            'expires_at' => $expiresInDays ? now()->addDays($expiresInDays) : null,
        ]);
    }
}
