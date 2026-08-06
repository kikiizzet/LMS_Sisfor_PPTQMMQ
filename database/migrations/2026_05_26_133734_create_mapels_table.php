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
        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('nama_mapel');
            $table->string('induk')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('jurusan')->nullable();
            $table->integer('jjm')->nullable()->comment('Jam Jumlah Mengajar');
            $table->integer('urutan')->nullable()->default(0);
            $table->string('kurikulum')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapels');
    }
};
