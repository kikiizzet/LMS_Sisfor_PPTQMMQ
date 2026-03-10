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
    Schema::create('raports', function (Blueprint $table) {
        $table->id();
        $table->string('nama_santri');
        $table->string('musyrif')->default('Ustadz Rizal Habibi');
        $table->integer('adab')->nullable();
        $table->integer('kelancaran')->nullable();
        $table->integer('tajwid')->nullable();
        $table->integer('fashahah')->nullable();
        $table->text('catatan')->nullable(); // Untuk keterangan hafalan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raports');
    }
};
