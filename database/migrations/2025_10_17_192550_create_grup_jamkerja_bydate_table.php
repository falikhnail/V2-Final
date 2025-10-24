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
        Schema::create('grup_jamkerja_bydate', function (Blueprint $table) {
            $table->id();
            $table->string('kode_grup', 3);
            $table->date('tanggal');
            $table->char('kode_jam_kerja', 4);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('kode_grup')->references('kode_grup')->on('grup')->onDelete('cascade');
            $table->foreign('kode_jam_kerja')->references('kode_jam_kerja')->on('presensi_jamkerja')->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['kode_grup', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_jamkerja_bydate');
    }
};
