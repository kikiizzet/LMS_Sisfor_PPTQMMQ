<?php

namespace App\Http\Controllers;

use App\Models\Raport;
use App\Models\RaportKmi;
use App\Models\RaportToken;
use Illuminate\Http\Request;

class PublicRaportController extends Controller
{
    /**
     * Display raport publicly via token (no login required).
     */
    public function show(string $token)
    {
        $raportToken = RaportToken::where('token', $token)->firstOrFail();

        // Check if token has expired
        if ($raportToken->isExpired()) {
            abort(410, 'Link raport ini sudah kedaluwarsa. Silakan hubungi pihak pesantren.');
        }

        $namaSantri = $raportToken->nama_santri;

        // Fetch Tahfidz raport
        $raportTahfidz = Raport::where('nama_santri', $namaSantri)->latest()->first();

        // Fetch KMI raport (match by normalized name)
        $raportKmi = RaportKmi::get()->first(function ($item) use ($namaSantri) {
            return $this->normalize($item->nama_santri) === $this->normalize($namaSantri);
        });

        return view('raport-public', compact('raportToken', 'raportTahfidz', 'raportKmi', 'namaSantri'));
    }

    /**
     * Generate a token for a specific santri (admin only).
     */
    public function generateToken(Request $request)
    {
        $request->validate([
            'nama_santri' => 'required|string',
        ]);

        $token = RaportToken::generateFor($request->nama_santri);

        return response()->json([
            'success' => true,
            'token' => $token->token,
            'url' => $token->getUrl(),
            'whatsapp_url' => $token->getWhatsappUrl(),
            'expires_at' => $token->expires_at?->format('d M Y'),
        ]);
    }

    /**
     * Normalize a name for comparison.
     */
    private function normalize(string $name): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
    }
}
