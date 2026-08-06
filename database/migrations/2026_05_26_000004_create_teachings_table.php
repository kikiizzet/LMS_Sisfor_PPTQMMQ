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
        Schema::create('teachings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('mata_pelajaran');
            $table->string('induk')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('jurusan')->nullable();
            $table->integer('jtm')->nullable()->comment('Jam Tugas Mengajar per mata pelajaran');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['guru_id', 'kelas_id', 'mata_pelajaran']);
            $table->index('guru_id');
            $table->index('kelas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachings');
    }
};
