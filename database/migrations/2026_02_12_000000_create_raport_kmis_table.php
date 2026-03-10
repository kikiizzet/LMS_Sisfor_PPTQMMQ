<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raport_kmis', function (Blueprint $table) {
            $table->id();
            // Informasi Header
            $table->string('nama_pesantren')->default('Makkah Madinatul Qur\'an');
            $table->string('nama_santri');
            $table->string('no_induk');
            $table->string('kelas');
            $table->string('semester');
            $table->string('tahun_pelajaran');

            // Nilai Mata Pelajaran (Disimpan dalam JSON untuk fleksibilitas)
            $table->json('nilai_mapel'); 

            // Ekstrakurikuler (JSON)
            $table->json('ekstrakurikuler')->nullable();

            // Ketidakhadiran
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('ghoib')->default(0);

            // Mental / Karakter
            $table->string('mental_moral')->nullable();
            $table->string('mental_kedisiplinan')->nullable();
            $table->string('mental_kerajinan')->nullable();
            $table->string('mental_kebersihan')->nullable();

            // Catatan & Tanda Tangan
            $table->text('catatan_wali_kelas')->nullable();
            $table->string('wali_kelas_nama');
            $table->string('wali_kelas_nip')->nullable();
            $table->string('kepala_madrasah_nama');
            $table->string('kepala_madrasah_nip')->nullable();
            $table->string('tempat_tanggal_cetak')->nullable(); // Misal: Pacitan, 20 Desember 2025

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raport_kmis');
    }
};
