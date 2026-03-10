<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Musyrif;
use App\Models\Raport;

class MusyrifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Mengambil nama musyrif unik dari tabel raports yang sudah ada
     * dan memigrasikannya ke tabel musyrifs.
     */
    public function run(): void
    {
        // Ambil nama musyrif unik dari tabel raports
        $uniqueMusyrifs = Raport::select('musyrif')
            ->distinct()
            ->whereNotNull('musyrif')
            ->where('musyrif', '!=', '')
            ->pluck('musyrif');

        foreach ($uniqueMusyrifs as $namaMusyrif) {
            Musyrif::firstOrCreate(
                ['nama' => $namaMusyrif]
            );
        }

        $this->command->info('✅ Berhasil memigrasikan ' . $uniqueMusyrifs->count() . ' nama musyrif ke tabel musyrifs.');
    }
}
