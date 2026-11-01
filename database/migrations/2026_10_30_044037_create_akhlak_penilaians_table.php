<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akhlak_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('set null');
            $table->date('tanggal');
            
            // 5 Indikator Buku Mutabaah Harian (Skala 1 - 4)
            $table->unsignedTinyInteger('skor_ibadah')->default(4)->comment('1-4: Sholat 5 waktu berjamaah, masuk KBM, halaqah tahfidz');
            $table->unsignedTinyInteger('skor_adab')->default(4)->comment('1-4: Tidak pacaran, tidak merokok, adab pergaulan, tidak membully');
            $table->unsignedTinyInteger('skor_kebersihan')->default(4)->comment('1-4: Kebersihan kamar/asrama, kelas, fasilitas pondok');
            $table->unsignedTinyInteger('skor_kegiatan')->default(4)->comment('1-4: Keaktifan semaan Al-Quran & kegiatan keagamaan');
            $table->unsignedTinyInteger('skor_kedisiplinan')->default(4)->comment('1-4: Kepatuhan tata tertib / nihil catatan pelanggaran');
            
            // Hasil Kalkulasi Otomatis
            $table->unsignedTinyInteger('total_skor')->default(20)->comment('Total skor 5 - 20');
            $table->decimal('nilai_angka', 5, 2)->default(100.00)->comment('Skala 0 - 100: (total_skor / 20) * 100');
            $table->enum('predikat', ['Kurang', 'Cukup', 'Baik', 'Sangat Baik'])->default('Sangat Baik');
            $table->decimal('nilai_fuzzy', 5, 2)->default(95.00)->comment('Representasi nilai fuzzy segitiga (37.5, 77.5, 85, 95)');
            
            // Atribut Tambahan
            $table->string('tipe_penilai', 50)->default('musyrif')->comment('musyrif / guru_tahfidz / guru_kbm');
            $table->text('catatan_pelanggaran')->nullable()->comment('Catatan mutabaah atau pelanggaran jika ada');
            $table->timestamps();

            // Indeks untuk pencarian cepat & mencegah duplikasi nilai harian santri oleh penilai yang sama
            $table->index(['santri_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akhlak_penilaians');
    }
};
