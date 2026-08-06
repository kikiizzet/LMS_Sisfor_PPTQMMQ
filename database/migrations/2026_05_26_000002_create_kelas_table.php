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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->integer('jumlah_siswa')->nullable();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('gurus')->onDelete('set null');
            $table->string('tingkat')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('jenis')->nullable();
            $table->string('kurikulum')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('nama_kelas');
            $table->index('tingkat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
